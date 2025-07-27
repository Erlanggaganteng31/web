<?php
include '../database.php';

$aksi = $_POST['aksi'] ?? '';
$kodeitem = $_POST["kodeitem"] ?? '';
$namaitem = $_POST["namaitem"] ?? '';
$satuan = $_POST["satuan"] ?? '';
$hargabeli = $_POST["hargabeli"] ?? 0;
$hargajual = $_POST["hargajual"] ?? 0;

// Menghilangkan real_escape_string untuk kesederhanaan (TIDAK AMAN)
// $kodeitem = $conn->real_escape_string($kodeitem);
// $namaitem = $conn->real_escape_string($namaitem);
// $satuan = $conn->real_escape_string($satuan);

$hargabeli = floatval($hargabeli);
$hargajual = floatval($hargajual);


if ($aksi == "tambah") {
    if (empty($kodeitem) || empty($namaitem) || empty($satuan) || empty($hargabeli) || empty($hargajual)) {
        echo 'Semua field harus diisi untuk menambah item.';
    } else {
        $sql = "INSERT INTO item (kodeitem, namaitem, satuan, hargabeli, hargajual)
                VALUES ('$kodeitem', '$namaitem', '$satuan', $hargabeli, $hargajual)";
        echo $conn->query($sql) ? "Data item berhasil ditambahkan!" : "Gagal menambahkan data: " . $conn->error;
    }
} else if ($aksi == "ubah") {
    if (empty($kodeitem) || empty($namaitem) || empty($satuan) || empty($hargabeli) || empty($hargajual)) {
        echo 'Semua field harus diisi untuk mengubah item.';
    } else {
        $sql = "UPDATE item SET
                    namaitem = '$namaitem',
                    satuan = '$satuan',
                    hargabeli = $hargabeli,
                    hargajual = $hargajual
                WHERE kodeitem = '$kodeitem'";
        echo $conn->query($sql) ? "Data item berhasil diubah!" : "Gagal mengubah data: " . $conn->error;
    }
} else if ($aksi == "hapus") {
    if (empty($kodeitem)) {
        echo 'Kode item tidak boleh kosong untuk menghapus.';
    } else {
        $sql = "DELETE FROM item WHERE kodeitem = '$kodeitem'";
        echo $conn->query($sql) ? "Data item berhasil dihapus!" : "Gagal menghapus data: " . $conn->error;
    }
} else {
    echo "Permintaan tidak valid.";
}
?>
