
<?php
session_start();

// Sample credentials
$username = 'admin';
$password = '12345';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        echo '<h1>Welcome to Admin Panel</h1>';
        echo '<p>List of orders and management features will be here.</p>';
    } else {
        echo '<h1>Access Denied</h1>';
    }
} else {
    header('Location: admin.html');
}
?>
