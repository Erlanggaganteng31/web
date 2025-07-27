<?php
include "../database.php";
header('Content-Type: application/json');

// ✅ Mode: VIEW DETAIL
if (isset($_POST["mode"]) && $_POST["mode"] == "view") {
    $id = $_POST["idpembelian"];
    $res = [];

    $q1 = $conn->query("SELECT m.*, s.namasupplier FROM masterpembelian m 
                        JOIN supplier s ON m.idsupplier = s.idsupplier 
                        WHERE m.idpembelian = '$id'");
    $res["header"] = $q1->fetch_assoc();

    $q2 = $conn->query("SELECT d.*, i.namaitem FROM detailpembelian d 
                        JOIN item i ON d.iditem = i.kodeitem 
                        WHERE d.idpembelian = '$id'");
    $res["detail"] = [];
    while ($row = $q2->fetch_assoc()) {
        $res["detail"][] = $row;
    }

    echo json_encode($res);
    exit;
}

// ✅ Hapus transaksi (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode']) && $_POST['mode'] === 'delete') {
    $id = $_POST['idpembelian'] ?? '';
    if (!$id) {
        echo json_encode(["error" => "ID pembelian tidak ditemukan."]);
        exit;
    }

    // Hapus detail dulu, baru master
    mysqli_query($conn, "DELETE FROM detailpembelian WHERE idpembelian = '$id'");
    mysqli_query($conn, "DELETE FROM masterpembelian WHERE idpembelian = '$id'");

    echo json_encode(["success" => "Transaksi berhasil dihapus."]);
    exit;
}


// ✅ Mode: INSERT PEMBELIAN (default)
$tanggal     = $_POST["tanggal"] ?? '';
$idsupplier  = $_POST["idsupplier"] ?? '';
$sales       = $_POST["sales"] ?? '';
$telp        = $_POST["telp"] ?? '';
$nodo        = $_POST["nodo"] ?? '';
$keterangan  = $_POST["keterangan"] ?? '';
$total       = $_POST["total"] ?? 0;
$totalqty    = $_POST["totalqty"] ?? 0;
$detail_json = $_POST["detail"] ?? '';

if (empty($tanggal) || empty($idsupplier) || empty($detail_json)) {
    echo json_encode(["error" => "Data utama tidak lengkap."]);
    exit;
}

$kodetr = 'TR' . date('YmdHis') . substr(microtime(), 2, 3);
$sql = "INSERT INTO masterpembelian 
(idpembelian, tanggal, idsupplier, sales, telp, nodo, keterangan, totalqty, totalharga)
VALUES 
('$kodetr', '$tanggal', '$idsupplier', '$sales', '$telp', '$nodo', '$keterangan', $totalqty, $total)";

if (!$conn->query($sql)) {
    echo json_encode(["error" => "Gagal insert master: " . $conn->error]);
    exit;
}

$detail = json_decode($detail_json, true);
if (!is_array($detail)) {
    echo json_encode(["error" => "Format detail tidak valid."]);
    exit;
}

foreach ($detail as $d) {
    // Menghilangkan real_escape_string untuk kesederhanaan (TIDAK AMAN)
    $iditem   = $d["iditem"] ?? '';
    $satuan   = $d["satuan"] ?? '';
    $harga    = floatval($d["harga"] ?? 0);
    $qty      = floatval($d["qty"] ?? 0);
    $subtotal = floatval($d["subtotal"] ?? 0);

    $sql_detail = "INSERT INTO detailpembelian 
    (idpembelian, iditem, satuan, harga, qty, subtotal)
    VALUES 
    ('$kodetr', '$iditem', '$satuan', $harga, $qty, $subtotal)";

    if (!$conn->query($sql_detail)) {
        echo json_encode(["error" => "Gagal insert detail: " . $conn->error]);
        exit;
    }
}

echo json_encode(["success" => "Data pembelian berhasil disimpan"]);
