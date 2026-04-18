<?php
/**
 * generate-icons.php
 * Genera los PNG de íconos PWA a partir del SVG fuente.
 * Ejecutar UNA SOLA VEZ desde la raíz del proyecto:
 *   php public/images/icons/generate-icons.php
 *
 * Requiere: extensión GD + librería Imagick, o Inkscape en PATH.
 * Si no dispone de ninguna, use https://squoosh.app o https://realfavicongenerator.net
 */

$sizes   = [72, 96, 128, 144, 152, 192, 384, 512];
$srcSvg  = __DIR__ . '/icon.svg';
$destDir = __DIR__;

if (!file_exists($srcSvg)) {
    die("ERROR: No se encontró icon.svg en $srcSvg\n");
}

// ── Intentar con Imagick ──────────────────────────────────────────────────
if (class_exists('Imagick')) {
    echo "Usando Imagick...\n";
    foreach ($sizes as $size) {
        $img = new Imagick();
        $img->setBackgroundColor(new ImagickPixel('transparent'));
        $img->readImage($srcSvg);
        $img->setImageFormat('png32');
        $img->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
        $out = "$destDir/icon-$size.png";
        $img->writeImage($out);
        $img->clear();
        echo "  ✔ icon-$size.png\n";
    }
    echo "Listo con Imagick.\n";
    exit(0);
}

// ── Intentar con Inkscape (CLI) ───────────────────────────────────────────
$inkscape = trim(shell_exec('which inkscape 2>/dev/null') ?? '');
if (empty($inkscape)) {
    $inkscape = 'inkscape'; // en Windows suele estar en PATH
}

// Probar si Inkscape existe
exec("\"$inkscape\" --version 2>&1", $out, $code);
if ($code === 0) {
    echo "Usando Inkscape...\n";
    foreach ($sizes as $size) {
        $dest = "$destDir/icon-$size.png";
        $cmd  = "\"$inkscape\" --export-type=png --export-width=$size --export-height=$size --export-filename=\"$dest\" \"$srcSvg\" 2>&1";
        exec($cmd, $lines, $rc);
        echo $rc === 0 ? "  ✔ icon-$size.png\n" : "  ✖ Error icon-$size.png: " . implode(' ', $lines) . "\n";
    }
    echo "Listo con Inkscape.\n";
    exit(0);
}

// ── Fallback: GD con SVG cargado como string ──────────────────────────────
// GD no soporta SVG nativamente; generar PNG de color sólido como placeholder
echo "AVISO: Ni Imagick ni Inkscape disponibles. Se generarán íconos placeholder.\n";
echo "       Reemplácelos con íconos reales usando https://realfavicongenerator.net\n\n";

if (!function_exists('imagecreatetruecolor')) {
    die("ERROR: La extensión GD tampoco está disponible.\n");
}

foreach ($sizes as $size) {
    $img   = imagecreatetruecolor($size, $size);
    $blue  = imagecolorallocate($img, 13, 110, 253);   // #0d6efd
    $white = imagecolorallocate($img, 255, 255, 255);

    // Fondo azul con esquinas redondeadas simuladas
    imagefill($img, 0, 0, $blue);

    // Letra "P" centrada como placeholder
    $fontSize = (int)($size * 0.4);
    $cx = (int)($size / 2);
    $cy = (int)($size / 2);
    imagestring($img, 5, $cx - 8, $cy - 10, 'P', $white);

    $dest = "$destDir/icon-$size.png";
    imagepng($img, $dest);
    imagedestroy($img);
    echo "  ✔ icon-$size.png (placeholder)\n";
}

echo "\nPlaceholders generados. Reemplácelos con íconos reales.\n";
