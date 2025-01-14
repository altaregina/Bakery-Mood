<?php
include('db.php'); // Include file db.php untuk koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $order_id = $_POST['order_id'];
    $sender_name = $_POST['sender_name'];
    $bank_destination = $_POST['bank_destination'];
    $amount_transferred = $_POST['amount_transferred'];

    // Buat objek DbConnect
    $db = new DbConnect();

    // Simpan data pembayaran menggunakan metode storePayment
    try {
        $result = $db->storePayment($order_id, $sender_name, $amount_transferred, $bank_destination);
        if ($result) {
            echo "Pembayaran berhasil disimpan!";
        } else {
            echo "Gagal menyimpan pembayaran.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Tutup koneksi database (opsional, karena PDO akan menutup sendiri saat objek dihancurkan)
    $db->closeConnection();
}
?>