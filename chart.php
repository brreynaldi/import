<?php

$specimen_data = [];
while ($row = $members_qry->fetch_assoc()) {
    $specimen_data[] = $row;
}


// Create image
$width = 800;
$height = 600;
$image = imagecreatetruecolor($width, $height);

// Define colors
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$blue = imagecolorallocate($image, 0, 0, 255);

// Fill the background
imagefill($image, 0, 0, $white);

// Draw the axes
imageline($image, 50, $height - 50, $width - 50, $height - 50, $black); // X-axis
imageline($image, 50, $height - 50, 50, 50, $black);   // Y-axis

// Set labels and font
$font_path = __DIR__ . '/arial.ttf'; // Pastikan path ini sesuai dengan lokasi font di sistem Anda
$font_size = 10;

// Draw X-axis labels
foreach ($specimen_data as $index => $data) {
    $x = 50 + ($index * 120); // X position
    $y = $height - 40;
    if (file_exists($font_path)) {
        imagettftext($image, $font_size, 0, round($x) - 20, round($y), $black, $font_path, $data['nama_spesimen']);
    } else {
        echo "Font file does not exist: $font_path";
        exit;
    }
}

// Draw Y-axis labels and grid lines
$max_siklus = max(array_column($specimen_data, 'siklus'));
$min_siklus = min(array_column($specimen_data, 'siklus'));
$y_step = ($height - 100) / ($max_siklus - $min_siklus);

for ($i = $min_siklus; $i <= $max_siklus; $i += ($max_siklus - $min_siklus) / 5) {
    $y = $height - 50 - round(($i - $min_siklus) * $y_step);
    imageline($image, 50, round($y), $width - 50, round($y), $black);
    if (file_exists($font_path)) {
        imagettftext($image, $font_size, 0, 10, round($y) + 5, $black, $font_path, $i);
    } else {
        echo "Font file does not exist: $font_path";
        exit;
    }
}

// Draw data points and lines
$previousX = null;
$previousY = null;

foreach ($specimen_data as $index => $data) {
    $x = 50 + ($index * 120); // X position
    $y = $height - 50 - round(($data['siklus'] - $min_siklus) * $y_step); // Y position

    // Draw point
    imagefilledellipse($image, round($x), round($y), 5, 5, $blue);

    // Draw line
    if ($previousX !== null && $previousY !== null) {
        imageline($image, round($previousX), round($previousY), round($x), round($y), $blue);
    }

    $previousX = $x;
    $previousY = $y;
}

// Set headers and output image
header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>