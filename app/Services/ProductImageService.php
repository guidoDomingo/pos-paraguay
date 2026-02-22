<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ProductImageService
{
    private const THUMBNAIL_SIZE = 150;
    private const MEDIUM_SIZE = 400;
    private const FULL_SIZE = 800;
    
    private const QUALITY = 85;
    private const FORMATS = ['jpg', 'jpeg', 'png', 'webp'];
    
    /**
     * Procesa y guarda las imágenes del producto en diferentes tamaños
     */
    public function processAndStore(UploadedFile $file, ?string $oldImagePath = null): array
    {
        // Validar archivo
        $this->validateImage($file);
        
        // Generar nombre único
        $filename = $this->generateUniqueFilename($file);
        
        // Eliminar imagen anterior si existe
        if ($oldImagePath) {
            $this->deleteImages($oldImagePath);
        }
        
        // Procesar y guardar en diferentes tamaños
        $paths = $this->createImageSizes($file, $filename);
        
        return $paths;
    }
    
    /**
     * Elimina todas las versiones de una imagen
     */
    public function deleteImages(string $imagePath): void
    {
        $pathInfo = pathinfo($imagePath);
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        // Eliminar todas las versiones
        $versions = ['thumb', 'medium', 'full'];
        foreach ($versions as $version) {
            $path = "products/{$baseName}_{$version}.{$extension}";
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        // Eliminar imagen original si existe
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
    
    /**
     * Valida que el archivo sea una imagen válida
     */
    private function validateImage(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, self::FORMATS)) {
            throw new \InvalidArgumentException('Formato de imagen no válido. Use: ' . implode(', ', self::FORMATS));
        }
        
        if ($file->getSize() > 5 * 1024 * 1024) { // 5MB límite
            throw new \InvalidArgumentException('La imagen no puede ser mayor a 5MB');
        }
    }
    
    /**
     * Genera un nombre único para el archivo
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Convertir a JPG para mejor compresión web si no es PNG con transparencia
        if (!in_array($extension, ['png', 'webp'])) {
            $extension = 'jpg';
        }
        
        return Str::uuid() . '.' . $extension;
    }
    
    /**
     * Crea las diferentes versiones de la imagen
     */
    private function createImageSizes(UploadedFile $file, string $filename): array
    {
        $pathInfo = pathinfo($filename);
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        // Cargar imagen original
        $image = Image::make($file);
        
        // Optimizar orientación (por si viene de móvil)
        $image->orientate();
        
        $paths = [];
        
        // Thumbnail (150x150 - cuadrado con crop centrado)
        $thumbnail = clone $image;
        $thumbnail->fit(self::THUMBNAIL_SIZE, self::THUMBNAIL_SIZE, function ($constraint) {
            $constraint->upsize();
        });
        $thumbPath = "products/{$baseName}_thumb.{$extension}";
        Storage::disk('public')->put($thumbPath, $thumbnail->encode($extension, self::QUALITY));
        $paths['thumbnail'] = $thumbPath;
        
        // Medium (400px ancho, altura proporcional)
        $medium = clone $image;
        $medium->resize(self::MEDIUM_SIZE, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $mediumPath = "products/{$baseName}_medium.{$extension}";
        Storage::disk('public')->put($mediumPath, $medium->encode($extension, self::QUALITY));
        $paths['medium'] = $mediumPath;
        
        // Full (800px ancho máximo, altura proporcional)
        $full = clone $image;
        $full->resize(self::FULL_SIZE, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $fullPath = "products/{$baseName}_full.{$extension}";
        Storage::disk('public')->put($fullPath, $full->encode($extension, self::QUALITY));
        $paths['full'] = $fullPath;
        
        // Guardar la ruta del medium como principal (balance entre calidad y peso)
        $paths['main'] = $mediumPath;
        
        return $paths;
    }
    
    /**
     * Obtiene la URL completa de una imagen
     */
    public function getImageUrl(?string $imagePath, string $size = 'medium'): ?string
    {
        if (!$imagePath) {
            return null;
        }
        
        // Si se pide un tamaño específico, construir la ruta
        if ($size !== 'medium') {
            $pathInfo = pathinfo($imagePath);
            $baseName = $pathInfo['filename'];
            $extension = $pathInfo['extension'];
            
            // Remover '_medium' si ya existe en el nombre
            $baseName = str_replace('_medium', '', $baseName);
            
            $imagePath = "products/{$baseName}_{$size}.{$extension}";
        }
        
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->url($imagePath);
        }
        
        return null;
    }
    
    /**
     * Obtiene todas las URLs de una imagen
     */
    public function getAllImageUrls(?string $imagePath): array
    {
        if (!$imagePath) {
            return [
                'thumbnail' => null,
                'medium' => null,
                'full' => null
            ];
        }
        
        return [
            'thumbnail' => $this->getImageUrl($imagePath, 'thumb'),
            'medium' => $this->getImageUrl($imagePath, 'medium'),
            'full' => $this->getImageUrl($imagePath, 'full')
        ];
    }
}