<?php 
session_start();
include_once('db-connect.php'); // Ganti dengan file koneksi database Anda

if (isset($_FILES['fileData']) && !empty($_FILES['fileData']['tmp_name'])) {

    // Baca file CSV
    $csv_file = fopen($_FILES['fileData']['tmp_name'], "r"); 
    $rowCount = 0;
    $data = [];

    // Iterasi setiap baris dalam file CSV
    while (($row = fgetcsv($csv_file, 1000, ",")) !== FALSE) {
        if ($rowCount > 0) { // Lewati header
            // Sanitasi dan simpan data ke array
            $data[] = [
                'time' => $conn->real_escape_string($row[0]), // Time
                'id' => $conn->real_escape_string($row[1]), // ID
                'nama' => $conn->real_escape_string($row[2]), // Nama
                'diameter_mm' => $conn->real_escape_string($row[3]), // Diameter dalam mm
                'massa_gr' => $conn->real_escape_string($row[4]), // Massa dalam gram
                'jarak' => $conn->real_escape_string($row[5]), // Jarak dalam mm
                'tegangan_mpa' => $conn->real_escape_string($row[6]), // Tegangan dalam Mpa
                'waktu_detik' => $conn->real_escape_string($row[7]), // Waktu dalam detik
                'putaran_rpm' => $conn->real_escape_string($row[8]), // Putaran dalam RPM
                'siklus' => $conn->real_escape_string($row[9]) // Siklus
            ];
        }
        $rowCount++;
    }

    // Tutup file CSV
    fclose($csv_file);

    if (count($data) > 0) {
        // Siapkan pernyataan SQL untuk penyisipan
        $stmt = $conn->prepare("INSERT INTO members (time, id, nama, diameter_mm, massa_gr, jarak, tegangan_mpa, waktu_detik, putaran_rpm, siklus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Proses setiap data untuk disisipkan
        foreach ($data as $rowData) {
            $stmt->bind_param("sssdddddii", 
                $rowData['time'],
                $rowData['id'],
                $rowData['nama'],
                $rowData['diameter_mm'],
                $rowData['massa_gr'],
                $rowData['jarak'],
                $rowData['tegangan_mpa'],
                $rowData['waktu_detik'],
                $rowData['putaran_rpm'],
                $rowData['siklus']
            );
            $stmt->execute(); // Jalankan pernyataan untuk setiap baris
        }

        // Sukses
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Data has been imported successfully.';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'CSV File Data is empty.';
    }

} else {
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'CSV File Data is missing.';
}

$conn->close();
header('location: ./'); // Kembali ke halaman sebelumnya
exit;
?>
