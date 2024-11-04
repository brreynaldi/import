<?php
header('Content-Type: image/png');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('db-connect.php'); // Ganti dengan koneksi database Anda

// Ambil data dari tabel members dan urutkan berdasarkan siklus (ASC)
$members_sql = "SELECT * FROM `members` ORDER BY siklus ASC";
$members_qry = $conn->query($members_sql);

// Buat array untuk menyimpan data spesimen
$specimen_data = [];
while ($row = $members_qry->fetch_assoc()) {
    $specimen_data[] = $row;
}

// Tutup koneksi database
$conn->close();

// Buat gambar dengan ukuran 800x600 piksel
$width = 800;
$height = 600;
$image = imagecreatetruecolor($width, $height);

// Definisikan warna
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$blue = imagecolorallocate($image, 0, 0, 255);
$red = imagecolorallocate($image, 255, 0, 0);
$green = imagecolorallocate($image, 0, 255, 0);

// Isi background dengan warna putih
imagefill($image, 0, 0, $white);

// Gambar sumbu X dan Y
imageline($image, 50, $height - 50, $width - 50, $height - 50, $black); // Sumbu X
imageline($image, 50, $height - 50, 50, 50, $black);   // Sumbu Y

// Set label dan font
$font_path = __DIR__ . '/arial.ttf'; // Pastikan font tersedia
$font_size = 10;

// Gambar label sumbu X (Siklus)
$max_siklus = max(array_column($specimen_data, 'siklus'));
$min_siklus = min(array_column($specimen_data, 'siklus'));
$x_step = ($width - 100) / count($specimen_data); // Hitung jarak antar titik di sumbu X

foreach ($specimen_data as $index => $data) {
    $x = 50 + ($index * $x_step); // Posisi X untuk Siklus
    $y = $height - 40;
    if (file_exists($font_path)) {
        imagettftext($image, $font_size, 0, round($x), round($y), $black, $font_path, $data['siklus']);
    }
}

// Gambar label sumbu Y (Tegangan MPa)
$max_tegangan = max(array_column($specimen_data, 'tegangan_mpa'));
$min_tegangan = min(array_column($specimen_data, 'tegangan_mpa'));
$y_step = ($height - 100) / ($max_tegangan - $min_tegangan);

for ($i = $min_tegangan; $i <= $max_tegangan; $i += ($max_tegangan - $min_tegangan) / 5) {
    $y = $height - 50 - round(($i - $min_tegangan) * $y_step); // Posisi Y untuk Tegangan
    imageline($image, 50, round($y), $width - 50, round($y), $black); // Gambar garis horizontal untuk label tegangan
    if (file_exists($font_path)) {
        imagettftext($image, $font_size, 0, 10, round($y) + 5, $black, $font_path, round($i, 2));
    }
}

// Gambar titik data untuk setiap material (Aluminium, Baja, Tembaga)
$previousX = null;
$previousY = null;

foreach ($specimen_data as $index => $data) {
    $x = 50 + ($index * $x_step); // Posisi X untuk siklus
    $y = $height - 50 - round(($data['tegangan_mpa'] - $min_tegangan) * $y_step); // Posisi Y untuk tegangan

    // Tentukan warna berdasarkan nama spesimen
    $color = $red; // Default warna hitam
    if ($data['nama'] == 'Tembaga') {
        $color = $black;
    } elseif ($data['nama'] == 'Baja AISI') {
        $color = $green;
    } elseif ($data['nama'] == 'Aluminium') {
        $color = $blue;
    }

    // Gambar titik
    imagefilledellipse($image, round($x), round($y), 5, 5, $color);

    // Gambar garis yang menghubungkan titik-titik
    if ($previousX !== null && $previousY !== null) {
        imageline($image, round($previousX), round($previousY), round($x), round($y), $color);
    }

    // Update posisi sebelumnya untuk garis berikutnya
    $previousX = $x;
    $previousY = $y;
}

// Output gambar ke browser
imagepng($image);
imagedestroy($image);
?>
