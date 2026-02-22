<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        if (!in_array($extension, ['png'])) {
            $extension = 'jpg';
        }
        
        return Str::uuid() . '.' . $extension;
    }
    
    /**
     * Crea las diferentes versiones de la imagen usando GD
     */
    private function createImageSizes(UploadedFile $file, string $filename): array
    {
        $pathInfo = pathinfo($filename);
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        // Cargar imagen original
        $imageInfo = getimagesizefromstring($file->getContent());
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        
        // Crear recurso de imagen según el tipo
        $originalImage = $this->createImageFromFile($file);
        
        $paths = [];
        
        // Thumbnail (150x150 - cuadrado con crop centrado)
        $thumbnail = $this->resizeAndCrop($originalImage, $originalWidth, $originalHeight, self::THUMBNAIL_SIZE, self::THUMBNAIL_SIZE);
        $thumbPath = "products/{$baseName}_thumb.{$extension}";
        Storage::disk('public')->put($thumbPath, $this->outputImage($thumbnail, $extension));
        imagedestroy($thumbnail);
        $paths['thumbnail'] = $thumbPath;
        
        // Medium (400px ancho, altura proporcional)
        $mediumDimensions = $this->calculateAspectRatio($originalWidth, $originalHeight, self::MEDIUM_SIZE);
        $medium = $this->resizeImage($originalImage, $originalWidth, $originalHeight, $mediumDimensions['width'], $mediumDimensions['height']);
        $mediumPath = "products/{$baseName}_medium.{$extension}";
        Storage::disk('public')->put($mediumPath, $this->outputImage($medium, $extension));
        imagedestroy($medium);
        $paths['medium'] = $mediumPath;
        
        // Full (800px ancho máximo, altura proporcional)
        $fullDimensions = $this->calculateAspectRatio($originalWidth, $originalHeight, self::FULL_SIZE);
        $full = $this->resizeImage($originalImage, $originalWidth, $originalHeight, $fullDimensions['width'], $fullDimensions['height']);
        $fullPath = "products/{$baseName}_full.{$extension}";
        Storage::disk('public')->put($fullPath, $this->outputImage($full, $extension));
        imagedestroy($full);
        $paths['full'] = $fullPath;
        
        // Limpiar imagen original
        imagedestroy($originalImage);
        
        // Guardar la ruta del medium como principal
        $paths['main'] = $mediumPath;
        
        return $paths;
    }
    
    /**
     * Crea un recurso de imagen desde el archivo subido
     */
    private function createImageFromFile(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $content = $file->getContent();
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromstring($content);
            case 'png':
                $image = imagecreatefromstring($content);
                imagealphablending($image, false);
                imagesavealpha($image, true);
                return $image;
            case 'webp':
                return imagecreatefromstring($content);
            default:
                throw new \InvalidArgumentException('Tipo de imagen no soportado');
        }
    }
    
    /**
     * Redimensiona una imagen manteniendo aspecto
     */
    private function resizeImage($source, $sourceWidth, $sourceHeight, $newWidth, $newHeight)
    {
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preservar transparencia para PNG
        if (imageistruecolor($source)) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefill($newImage, 0, 0, $transparent);
        }
        
        imagecopyresampled(
            $newImage, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $sourceWidth, $sourceHeight
        );
        
        return $newImage;
    }
    
    /**
     * Redimensiona y recorta para obtener dimensiones exactas (crop centrado)
     */
    private function resizeAndCrop($source, $sourceWidth, $sourceHeight, $newWidth, $newHeight)
    {
        // Calcular la escala necesaria para que la imagen complete el área destino
        $scaleX = $newWidth / $sourceWidth;
        $scaleY = $newHeight / $sourceHeight;
        $scale = max($scaleX, $scaleY); // Usar la escala mayor para cubrir completamente
        
        // Nuevas dimensiones escaladas
        $scaledWidth = intval($sourceWidth * $scale);
        $scaledHeight = intval($sourceHeight * $scale);
        
        // Crear imagen temporal escalada
        $scaledImage = $this->resizeImage($source, $sourceWidth, $sourceHeight, $scaledWidth, $scaledHeight);
        
        // Crear imagen final con las dimensiones deseadas
        $croppedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preservar transparencia
        imagealphablending($croppedImage, false);
        imagesavealpha($croppedImage, true);
        $transparent = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
        imagefill($croppedImage, 0, 0, $transparent);
        
        // Calcular offset para centrar el recorte
        $offsetX = intval(($scaledWidth - $newWidth) / 2);
        $offsetY = intval(($scaledHeight - $newHeight) / 2);
        
        // Copiar la porción central
        imagecopy(
            $croppedImage, $scaledImage,
            0, 0, $offsetX, $offsetY,
            $newWidth, $newHeight
        );
        
        imagedestroy($scaledImage);
        
        return $croppedImage;
    }
    
    /**
     * Calcula las nuevas dimensiones manteniendo aspecto
     */
    private function calculateAspectRatio($width, $height, $maxWidth, $maxHeight = null)
    {
        if ($maxHeight === null) {
            $maxHeight = $maxWidth;
        }
        
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        
        // No agrandar imágenes pequeñas
        if ($ratio > 1) {
            $ratio = 1;
        }
        
        return [
            'width' => intval($width * $ratio),
            'height' => intval($height * $ratio)
        ];
    }
    
    /**
     * Genera la salida de la imagen en el formato especificado
     */
    private function outputImage($image, $extension)
    {
        ob_start();
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, null, self::QUALITY);
                break;
            case 'png':
                imagepng($image, null, 9); // Máxima compresión PNG
                break;
            case 'webp':
                imagewebp($image, null, self::QUALITY);
                break;
        }
        
        return ob_get_clean();
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
            
            // Convertir 'thumbnail' a 'thumb' para coincidir con los nombres de archivo
            if ($size === 'thumbnail') {
                $size = 'thumb';
            }
            
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