<?php
include "../database.php"; // Pastikan path ke database.php benar
header('Content-Type: application/json'); // Memberi tahu browser bahwa respons adalah JSON

$response = ['status' => 'error', 'message' => 'Permintaan tidak valid.'];

// Ambil input JSON jika ada (untuk mode 'tambah')
$input = file_get_contents('php://input');
$data_json = json_decode($input, true);

// Jika bukan JSON, coba ambil dari $_POST (untuk mode 'view' dan 'hapus')
if (json_last_error() !== JSON_ERROR_NONE) {
    $mode = $_POST['mode'] ?? '';
    $kodetr = $_POST['kodetr'] ?? '';
} else {
    $mode = $data_json['mode'] ?? '';
    // Data lainnya akan diambil dari $data_json
}

switch ($mode) {
    case 'tambah':
        // Pastikan semua data yang diperlukan ada
        $tanggal    = $data_json['tanggal'] ?? '';
        $konsumen   = $data_json['konsumen'] ?? '';
        $telp       = $data_json['telp'] ?? '';
        $alamat     = $data_json['alamat'] ?? '';
        $total      = $data_json['total'] ?? 0;
        $totalqty   = $data_json['totalqty'] ?? 0;
        $detail     = $data_json['detail'] ?? [];

        if (empty($tanggal) || empty($konsumen) || !is_array($detail) || empty($detail)) {
            $response['message'] = 'Data utama atau detail item tidak lengkap.';
            echo json_encode($response);
            exit;
        }

        // Mulai transaksi database
        $conn->begin_transaction();

        try {
            // Generate Kode Transaksi unik
            $kodetr = 'TR' . date('YmdHis') . substr(microtime(), 2, 3);

            // Insert ke masterpenjualan
            // Menghilangkan prepared statement (TIDAK AMAN)
            $sql_master = "INSERT INTO masterpenjualan (kodetr, tanggal, konsumen, alamat, telp, total, totalqty) VALUES ('$kodetr', '$tanggal', '$konsumen', '$alamat', '$telp', $total, $totalqty)";
            if (!$conn->query($sql_master)) {
                throw new Exception("Gagal insert master: " . $conn->error);
            }

            // Insert ke detailpenjualan
            // Menghilangkan prepared statement (TIDAK AMAN)
            foreach ($detail as $item) {
                $kodeitem  = $item['kodeitem'] ?? '';
                $namaitem  = $item['namaitem'] ?? '';
                $hargajual = $item['hargajual'] ?? 0;
                $qty       = $item['qty'] ?? 0;
                $subtotal  = $item['subtotal'] ?? 0;

                if (empty($kodeitem) || empty($namaitem) || $hargajual <= 0 || $qty <= 0) {
                    throw new Exception("Data detail item tidak lengkap atau tidak valid.");
                }

                $sql_detail = "INSERT INTO detailpenjualan (kodetr, kodeitem, namaitem, hargajual, qty, subtotal) VALUES ('$kodetr', '$kodeitem', '$namaitem', $hargajual, $qty, $subtotal)";
                if (!$conn->query($sql_detail)) {
                    throw new Exception("Gagal insert detail for item " . $kodeitem . ": " . $conn->error);
                }
            }

            // Commit transaksi jika semua berhasil
            $conn->commit();
            $response['status'] = 'success';
            $response['message'] = 'Transaksi penjualan berhasil disimpan!';

        } catch (Exception $e) {
            // Rollback transaksi jika ada kesalahan
            $conn->rollback();
            $response['message'] = 'Gagal menyimpan transaksi: ' . $e->getMessage();
        }
        break;

    case 'view':
        if (empty($kodetr)) {
            $response['message'] = 'Kode transaksi tidak boleh kosong.';
            echo json_encode($response);
            exit;
        }

        $data = ['header' => null, 'detail' => []];

        // Ambil data header
        // Menghilangkan prepared statement (TIDAK AMAN)
        $sql_header = "SELECT kodetr, tanggal, konsumen, alamat, telp, total, totalqty FROM masterpenjualan WHERE kodetr = '$kodetr'";
        $result_header = $conn->query($sql_header);
        $data['header'] = $result_header->fetch_assoc();
        
        if (!$data['header']) {
            $response['message'] = 'Transaksi tidak ditemukan.';
            echo json_encode($response);
            exit;
        }

        // Ambil data detail
        // Menghilangkan prepared statement (TIDAK AMAN)
        $sql_detail = "SELECT kodeitem, namaitem, hargajual, qty, subtotal FROM detailpenjualan WHERE kodetr = '$kodetr'";
        $result_detail = $conn->query($sql_detail);
        while ($row = $result_detail->fetch_assoc()) {
            $data['detail'][] = $row;
        }

        $response['status'] = 'success';
        $response['message'] = 'Data transaksi berhasil dimuat.';
        $response['data'] = $data;
        break;

    case 'hapus':
        if (empty($kodetr)) {
            $response['message'] = 'Kode transaksi tidak boleh kosong untuk menghapus.';
            echo json_encode($response);
            exit;
        }

        // Mulai transaksi database
        $conn->begin_transaction();

        try {
            // Hapus dari detailpenjualan terlebih dahulu
            // Menghilangkan prepared statement (TIDAK AMAN)
            $sql_delete_detail = "DELETE FROM detailpenjualan WHERE kodetr = '$kodetr'";
            if (!$conn->query($sql_delete_detail)) {
                throw new Exception("Gagal menghapus detail: " . $conn->error);
            }

            // Hapus dari masterpenjualan
            // Menghilangkan prepared statement (TIDAK AMAN)
            $sql_delete_master = "DELETE FROM masterpenjualan WHERE kodetr = '$kodetr'";
            if (!$conn->query($sql_delete_master)) {
                throw new Exception("Gagal menghapus master: " . $conn->error);
            }

            // Commit transaksi
            $conn->commit();
            $response['status'] = 'success';
            $response['message'] = 'Transaksi penjualan berhasil dihapus!';

        } catch (Exception $e) {
            // Rollback transaksi
            $conn->rollback();
            $response['message'] = 'Gagal menghapus transaksi: ' . $e->getMessage();
        }
        break;

    default:
        $response['message'] = 'Mode operasi tidak dikenal.';
        break;
}

echo json_encode($response);
?>
