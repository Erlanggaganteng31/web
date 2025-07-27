<?php
include "../database.php";

$aksi = $_POST['aksi'] ?? ''; // Menggunakan variabel $aksi untuk menentukan operasi
$id = $_POST['id'] ?? '';
$nama = $_POST['nama'] ?? '';
$sales = $_POST['sales'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$telp = $_POST['telp'] ?? '';

if ($aksi == "tambah") {
    // Validasi input
    if (empty($id) || empty($nama) || empty($sales) || empty($alamat) || empty($telp)) {
        echo "Semua field harus diisi untuk menambah supplier.";
    } else {
        $sql = "INSERT INTO supplier (idsupplier, namasupplier, sales, alamat, telp)
                VALUES ('$id', '$nama', '$sales', '$alamat', '$telp')";
        echo $conn->query($sql) ? "Data supplier berhasil ditambahkan!" : "Gagal tambah data: " . $conn->error;
    }
} else if ($aksi == "ubah") {
    // Validasi input
    if (empty($id) || empty($nama) || empty($sales) || empty($alamat) || empty($telp)) {
        echo "Semua field harus diisi untuk mengubah supplier.";
    } else {
        $sql = "UPDATE supplier SET
                    namasupplier = '$nama',
                    sales = '$sales',
                    alamat = '$alamat',
                    telp = '$telp'
                WHERE idsupplier = '$id'";
        echo $conn->query($sql) ? "Data supplier berhasil diubah!" : "Gagal ubah data: " . $conn->error;
    }
} else if ($aksi == "hapus") {
    // Validasi input
    if (empty($id)) {
        echo "ID Supplier tidak boleh kosong untuk menghapus.";
    } else {
        $sql = "DELETE FROM supplier WHERE idsupplier = '$id'";
        echo $conn->query($sql) ? "Data supplier berhasil dihapus!" : "Gagal hapus data: " . $conn->error;
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
