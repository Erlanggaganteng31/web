<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "uasweb";


// Membuat koneksi
$conn = new mysqli($servername, $username, $password,$db_name);

// Cek koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
?>