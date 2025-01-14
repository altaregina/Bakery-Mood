<?php
class DbConnect
{
    private string $dbHostname = 'localhost'; // Sesuaikan dengan host Anda
    private string $dbDatabase = 'bakery_mood_db'; // Nama database
    private string $dbUsername = 'root'; // Username database
    private string $dbPassword = ''; // Password database
    public ?PDO $db = null;

    // Method untuk membuat koneksi ke database
    public function connect()
    {
        try {
            $this->db = new PDO("mysql:host=" . $this->dbHostname . ";dbname=" . $this->dbDatabase, $this->dbUsername, $this->dbPassword);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Method untuk menyimpan data pesanan ke tabel orders
    public function storeOrder($customer_name, $phone_number, $cake_name, $order_date, $delivery_option, $cake_price, $delivery_fee, $total_payment, $delivery_address = null)
    {
        if ($this->db == null) {
            $this->connect();
        }

        // Format tanggal ke Y-m-d
        $order_date = date('Y-m-d', strtotime($order_date));

        // Query untuk menyimpan data pesanan
        $stm = $this->db->prepare('INSERT INTO orders (customer_name, phone_number, cake_name, order_date, delivery_option, cake_price, delivery_fee, total_payment, delivery_address) VALUES (:customer_name, :phone_number, :cake_name, :order_date, :delivery_option, :cake_price, :delivery_fee, :total_payment, :delivery_address)');
        $stm->bindValue('customer_name', $customer_name, PDO::PARAM_STR);
        $stm->bindValue('phone_number', $phone_number, PDO::PARAM_STR);
        $stm->bindValue('cake_name', $cake_name, PDO::PARAM_STR);
        $stm->bindValue('order_date', $order_date, PDO::PARAM_STR);
        $stm->bindValue('delivery_option', $delivery_option, PDO::PARAM_STR);
        $stm->bindValue('cake_price', $cake_price, PDO::PARAM_STR);
        $stm->bindValue('delivery_fee', $delivery_fee, PDO::PARAM_STR);
        $stm->bindValue('total_payment', $total_payment, PDO::PARAM_STR);
        $stm->bindValue('delivery_address', $delivery_address, PDO::PARAM_STR);

        // Eksekusi query
        if ($stm->execute()) {
            return $this->db->lastInsertId(); // Mengembalikan ID pesanan terakhir
        } else {
            return false; // Gagal menyimpan pesanan
        }
    }

    // Method untuk menyimpan data pembayaran ke tabel payments
    public function storePayment($order_id, $sender_name, $amount_transferred, $bank_destination)
    {
        if ($this->db == null) {
            $this->connect();
        }

        // Query untuk menyimpan data pembayaran
        $stm = $this->db->prepare('INSERT INTO payments (order_id, sender_name, amount_transferred, bank_destination) VALUES (:order_id, :sender_name, :amount_transferred, :bank_destination)');
        $stm->bindValue('order_id', $order_id, PDO::PARAM_INT);
        $stm->bindValue('sender_name', $sender_name, PDO::PARAM_STR);
        $stm->bindValue('amount_transferred', $amount_transferred, PDO::PARAM_STR);
        $stm->bindValue('bank_destination', $bank_destination, PDO::PARAM_STR);

        // Eksekusi query
        return $stm->execute();
    }

    // Method untuk menutup koneksi database
    public function closeConnection()
    {
        $this->db = null;
    }
}
?>