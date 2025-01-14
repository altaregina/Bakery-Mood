<?php
header('Content-Type: application/json'); // Pastikan respons adalah JSON
error_reporting(E_ALL); // Aktifkan reporting error
ini_set('display_errors', 1); // Tampilkan error di layar

include('db.php'); // Include file db.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = new DbConnect();

        // Ambil data dari form
        $customer_name = $_POST['customer_name'];
        $phone_number = $_POST['phone_number'];
        $cake_name = $_POST['cake_name'];
        $order_date = $_POST['pickup_date'];
        $delivery_option = $_POST['delivery_option'];
        $cake_price = $_POST['cake_price'];
        $delivery_fee = $_POST['delivery_fee'];
        $total_payment = $_POST['total_payment'];
        $delivery_address = isset($_POST['delivery_address']) ? $_POST['delivery_address'] : null;

        // Simpan data pesanan
        $order_id = $db->storeOrder($customer_name, $phone_number, $cake_name, $order_date, $delivery_option, $cake_price, $delivery_fee, $total_payment, $delivery_address);

        if ($order_id) {
            // Simpan data pembayaran
            $sender_name = $customer_name; // Nama pengirim sama dengan nama pembeli
            $amount_transferred = $total_payment;
            $bank_destination = $_POST['bank_destination'];

            $result = $db->storePayment($order_id, $sender_name, $amount_transferred, $bank_destination);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Pesanan dan pembayaran berhasil disimpan!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pembayaran.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesanan.']);
        }

        // Tutup koneksi database
        $db->closeConnection();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
}
?>