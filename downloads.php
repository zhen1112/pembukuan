<?php
include 'koneksi.php'; // Sertakan file koneksi ke database

// Memeriksa apakah parameter bulan tersedia dan valid
if (isset($_GET['bulan']) && $_GET['bulan'] >= 1 && $_GET['bulan'] <= 12) {
    // Query untuk mengambil data penjualan berdasarkan bulan
    $bulan = $_GET['bulan'];
    $sql = "SELECT * FROM data_penjualan WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = 2024";
    $result = mysqli_query($conn, $sql);

    // Memeriksa apakah ada data yang ditemukan
    if (mysqli_num_rows($result) > 0) {
        // Mengatur header untuk menyertakan file CSV dalam respon HTTP
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="penjualan_' . $bulan . '.csv"');
        header('Cache-Control: max-age=0');

        // Membuat file CSV dengan data dari hasil query
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Nama Customer', 'IMEI_SN', 'Tipe', 'Harga Sebelum Diskon', 'Diskon', 'Harga Diskon', 'Catatan', 'Tanggal'));

        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }

        // Menutup file CSV
        fclose($output);
        exit;
    } else {
        echo "Tidak ada data penjualan untuk bulan yang dipilih.";
    }
} else {
    echo "Parameter bulan tidak valid.";
}
?>
