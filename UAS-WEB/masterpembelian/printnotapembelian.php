<?php
include "../database.php";

// Ambil ID pembelian dari parameter GET
$idpembelian = isset($_GET['id']) ? $_GET['id'] : die('ID Pembelian tidak valid');

// Query untuk mendapatkan data master pembelian
$sql = "SELECT m.*, s.namasupplier, s.alamat 
        FROM masterpembelian m 
        JOIN supplier s ON m.idsupplier = s.idsupplier 
        WHERE m.idpembelian = '$idpembelian'";
$result = $conn->query($sql);
$pembelian = $result->fetch_assoc();

if (!$pembelian) {
    die('Data pembelian tidak ditemukan');
}

// Query untuk mendapatkan detail pembelian
$sql_detail = "SELECT d.*, i.namaitem 
               FROM detailpembelian d 
               JOIN item i ON d.iditem = i.kodeitem 
               WHERE d.idpembelian = '$idpembelian' 
               ORDER BY d.iditem";
$detail = $conn->query($sql_detail);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Pembelian <?= $pembelian['idpembelian'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-supplier, .info-pembelian {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            width: 200px;
            text-align: center;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>NOTA PEMBELIAN</h2>
        <h3>PT. CONTOH PERUSAHAAN</h3>
        <p>Jl. Contoh No. 123, Jakarta - Telp: 021-12345678</p>
    </div>

    <div class="info">
        <div class="info-supplier">
            <p><strong>Supplier:</strong> <?= $pembelian['namasupplier'] ?></p>
            <p><strong>Alamat:</strong> <?= $pembelian['alamat'] ?></p>
            <p><strong>Nama Sales:</strong> <?= $pembelian['sales'] ?></p>
            <p><strong>No. Telp:</strong> <?= $pembelian['telp'] ?></p>
        </div>
        <div class="info-pembelian">
            <p><strong>No. Nota:</strong> <?= $pembelian['idpembelian'] ?></p>
            <p><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($pembelian['tanggal'])) ?></p>
            <p><strong>No. DO:</strong> <?= $pembelian['nodo'] ?></p>
            <p><strong>Keterangan:</strong> <?= $pembelian['keterangan'] ?></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = $detail->fetch_assoc()): 
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['iditem'] ?></td>
                    <td><?= $row['namaitem'] ?></td>
                    <td><?= $row['satuan'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= number_format($row['qty'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="total">
        <p><strong>Total Qty:</strong> <?= number_format($pembelian['totalqty'], 0, ',', '.') ?></p>
        <p><strong>Total Pembelian:</strong> Rp <?= number_format($pembelian['totalharga'], 0, ',', '.') ?></p>
    </div>

    <div class="footer">
        <div class="signature">
            <p>Hormat Kami,</p>
            <br><br><br>
            <p>_________________________</p>
            <p>(Admin Pembelian)</p>
        </div>
        <div class="signature">
            <p>Diterima Oleh,</p>
            <br><br><br>
            <p>_________________________</p>
            <p>(Supplier)</p>
        </div>
    </div>

    <button onclick="window.print()" style="margin-top: 20px; padding: 10px 20px;">Print Nota</button>
    <button onclick="window.close()" style="margin-top: 20px; padding: 10px 20px;">Tutup</button>
</body>

</html>
