<?php
include "../database.php"; // Pastikan path ke database.php benar
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Master Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="container py-5">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UAS</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../item/">ITEM</a></li>
                    <li class="nav-item"><a class="nav-link" href="../supplier/">SUPPLIER</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">PENJUALAN</a></li>
                    <li class="nav-item"><a class="nav-link" href="../masterpembelian/">PEMBELIAN</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container pt-3 mt-2">
        <h2 class="mb-3">Master Data Penjualan</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalPenjualan" id="btnTambahPenjualan">+ Tambah Penjualan</button>

        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>Action</th>
                    <th>Kode TR</th>
                    <th>Tanggal</th>
                    <th>Konsumen</th>
                    <th>Total Qty</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody id="tabel-penjualan-body">
                <?php
                // Ambil data master penjualan untuk ditampilkan
                $query = $conn->query("SELECT kodetr, tanggal, konsumen, totalqty, total FROM masterpenjualan ORDER BY tanggal DESC, kodetr DESC");
                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><button class='btn btn-sm btn-info view-detail-btn' data-kodetr='{$row['kodetr']}'>VIEW</button></td>";
                        echo "<td>" . $row['kodetr'] . "</td>"; // Menghilangkan htmlspecialchars
                        echo "<td>" . $row['tanggal'] . "</td>"; // Menghilangkan htmlspecialchars
                        echo "<td>" . $row['konsumen'] . "</td>"; // Menghilangkan htmlspecialchars
                        echo "<td>" . $row['totalqty'] . "</td>"; // Menghilangkan htmlspecialchars
                        echo "<td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data penjualan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah/Edit Penjualan -->
    <div class="modal fade" id="modalPenjualan" tabindex="-1" aria-labelledby="modalPenjualanLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form id="form-penjualan">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPenjualanLabel">Tambah Penjualan Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="konsumen" class="form-label">Konsumen</label>
                                <input type="text" id="konsumen" name="konsumen" class="form-control" placeholder="Nama Konsumen" required>
                            </div>
                            <div class="col-md-4">
                                <label for="telp" class="form-label">Telp</label>
                                <input type="text" id="telp" name="telp" class="form-control" placeholder="Nomor Telepon">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control" placeholder="Alamat Konsumen"></textarea>
                        </div>

                        <hr>
                        <h6 class="fw-bold">Detail Item Penjualan</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-md-3">
                                <label for="select_item" class="form-label">Pilih Item</label>
                                <select id="select_item" class="form-select">
                                    <option value="">-- Pilih Item --</option>
                                    <?php
                                    $q_items = $conn->query("SELECT kodeitem, namaitem, satuan, hargajual FROM item ORDER BY namaitem");
                                    while ($item_row = $q_items->fetch_assoc()) {
                                        // Menghilangkan htmlspecialchars
                                        $item_json = json_encode($item_row);
                                        echo "<option value='{$item_row['kodeitem']}' data-item='{$item_json}'>" . $item_row['namaitem'] . " ({$item_row['kodeitem']})</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="namaitem_add" class="form-label">Nama Item</label>
                                <input type="text" id="namaitem_add" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="hargajual_add" class="form-label">Harga Jual</label>
                                <input type="number" id="hargajual_add" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="qty_add" class="form-label">Qty</label>
                                <input type="number" id="qty_add" class="form-control" min="1" value="1">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-success w-100" id="btn-tambah-barang-detail">+</button>
                            </div>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Action</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-penjualan-detail">
                                    <!-- Detail item akan ditambahkan di sini oleh JavaScript -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">TOTAL</th>
                                        <th id="total-qty-display">0</th>
                                        <th id="total-subtotal-display">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnSimpanTransaksi">Simpan Transaksi</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View Detail Penjualan -->
    <div class="modal fade" id="modalViewDetail" tabindex="-1" aria-labelledby="modalViewDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalViewDetailLabel">Detail Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Kode Transaksi:</strong> <span id="view-kodetr"></span><br>
                            <strong>Tanggal:</strong> <span id="view-tanggal"></span><br>
                            <strong>Konsumen:</strong> <span id="view-konsumen"></span><br>
                            <strong>Telp:</strong> <span id="view-telp"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Alamat:</strong><br>
                            <span id="view-alamat"></span>
                        </div>
                    </div>

                    <h6 class="fw-bold">Item yang Dibeli</h6>
                    <table class="table table-bordered table-striped text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode Item</th>
                                <th>Nama Item</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="view-detail-body">
                            <!-- Detail item akan dimuat di sini -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">TOTAL</th>
                                <th id="view-totalqty">0</th>
                                <th id="view-totalharga">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" id="btn-hapus-transaksi">Hapus Transaksi Ini</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let selectedItemData = null; // Untuk menyimpan data item yang dipilih dari dropdown

            // Fungsi untuk menghitung total Qty dan Subtotal di form penjualan baru
            function calculateTotals() {
                let totalQty = 0;
                let totalSubtotal = 0;
                $('#tbody-penjualan-detail tr').each(function() {
                    const qty = parseFloat($(this).find("td:eq(4)").text());
                    const subtotal = parseFloat($(this).find("td:eq(5)").text());
                    if (!isNaN(qty)) totalQty += qty;
                    if (!isNaN(subtotal)) totalSubtotal += subtotal;
                });
                $("#total-qty-display").text(totalQty);
                $("#total-subtotal-display").text(totalSubtotal.toLocaleString('id-ID')); // Format mata uang
            }

            // Event listener saat modal tambah penjualan dibuka
            $('#btnTambahPenjualan').on('click', function() {
                $('#modalPenjualanLabel').text('Tambah Penjualan Baru');
                $('#form-penjualan')[0].reset(); // Reset form
                $('#tanggal').val('<?php echo date('Y-m-d'); ?>'); // Set tanggal hari ini
                $('#tbody-penjualan-detail').empty(); // Kosongkan detail item
                calculateTotals(); // Hitung ulang total
                selectedItemData = null; // Reset data item yang dipilih
                $('#namaitem_add, #hargajual_add').val(''); // Kosongkan field item
                $('#qty_add').val(1); // Reset qty
            });

            // Event listener saat memilih item dari dropdown
            $('#select_item').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const itemJson = selectedOption.data('item');

                if (itemJson) {
                    selectedItemData = itemJson;
                    $('#namaitem_add').val(selectedItemData.namaitem);
                    $('#hargajual_add').val(selectedItemData.hargajual);
                    $('#qty_add').val(1); // Reset qty setiap kali item baru dipilih
                } else {
                    selectedItemData = null;
                    $('#namaitem_add').val('');
                    $('#hargajual_add').val('');
                    $('#qty_add').val(1);
                }
            });

            // Event listener untuk tombol 'Tambah' item ke tabel detail
            $('#btn-tambah-barang-detail').on('click', function() {
                if (!selectedItemData) {
                    alert('Pilih item terlebih dahulu!');
                    return;
                }

                const kodeitem = selectedItemData.kodeitem;
                const namaitem = selectedItemData.namaitem;
                const hargajual = parseFloat($('#hargajual_add').val());
                const qty = parseInt($('#qty_add').val());

                if (isNaN(hargajual) || hargajual <= 0) {
                    alert('Harga jual tidak valid.');
                    return;
                }
                if (isNaN(qty) || qty <= 0) {
                    alert('Kuantitas tidak valid.');
                    return;
                }

                const subtotal = hargajual * qty;

                // Cek apakah item sudah ada di tabel detail
                let itemExists = false;
                $('#tbody-penjualan-detail tr').each(function() {
                    if ($(this).find('td:eq(1)').text() === kodeitem) {
                        // Jika item sudah ada, update qty dan subtotal
                        let currentQty = parseInt($(this).find('td:eq(4)').text());
                        let newQty = currentQty + qty;
                        let newSubtotal = hargajual * newQty;
                        $(this).find('td:eq(4)').text(newQty);
                        $(this).find('td:eq(5)').text(newSubtotal.toLocaleString('id-ID'));
                        itemExists = true;
                        return false; // Keluar dari loop each
                    }
                });

                if (!itemExists) {
                    // Jika item belum ada, tambahkan baris baru
                    $('#tbody-penjualan-detail').append(`
                        <tr data-kodeitem="${kodeitem}">
                            <td><button type="button" class="btn btn-danger btn-sm remove-item-detail">X</button></td>
                            <td>${kodeitem}</td>
                            <td>${namaitem}</td>
                            <td>${hargajual.toLocaleString('id-ID')}</td>
                            <td>${qty}</td>
                            <td>${subtotal.toLocaleString('id-ID')}</td>
                        </tr>
                    `);
                }

                calculateTotals();
                // Reset input item setelah ditambahkan
                $('#select_item').val('');
                $('#namaitem_add').val('');
                $('#hargajual_add').val('');
                $('#qty_add').val(1);
                selectedItemData = null;
            });

            // Event listener untuk menghapus baris item dari tabel detail
            $('#tbody-penjualan-detail').on('click', '.remove-item-detail', function() {
                $(this).closest('tr').remove();
                calculateTotals();
            });

            // Event listener untuk submit form penjualan
            $('#form-penjualan').on('submit', function(e) {
                e.preventDefault();

                const tanggal = $('#tanggal').val();
                const konsumen = $('#konsumen').val();
                const telp = $('#telp').val();
                const alamat = $('#alamat').val();
                const total = parseFloat($('#total-subtotal-display').text().replace(/\./g, '').replace(/,/g, '.')); // Parse formatted number
                const totalQty = parseFloat($('#total-qty-display').text());

                if (!tanggal || !konsumen || totalQty <= 0) {
                    alert('Pastikan tanggal, nama konsumen, dan setidaknya satu item telah diisi.');
                    return;
                }

                const detailItems = [];
                $('#tbody-penjualan-detail tr').each(function() {
                    const row = $(this);
                    detailItems.push({
                        kodeitem: row.find('td:eq(1)').text(),
                        namaitem: row.find('td:eq(2)').text(),
                        hargajual: parseFloat(row.find('td:eq(3)').text().replace(/\./g, '').replace(/,/g, '.')),
                        qty: parseInt(row.find('td:eq(4)').text()),
                        subtotal: parseFloat(row.find('td:eq(5)').text().replace(/\./g, '').replace(/,/g, '.'))
                    });
                });

                const formData = {
                    mode: 'tambah', // Mode untuk prosespenjualan.php
                    tanggal: tanggal,
                    konsumen: konsumen,
                    telp: telp,
                    alamat: alamat,
                    total: total,
                    totalqty: totalQty,
                    detail: detailItems
                };

                $.ajax({
                    type: 'POST',
                    url: 'prosespenjualan.php',
                    data: JSON.stringify(formData), // Kirim data sebagai JSON string
                    contentType: 'application/json', // Beri tahu server bahwa ini adalah JSON
                    dataType: 'json', // Harap respons JSON
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#modalPenjualan').modal('hide');
                            location.reload(); // Reload halaman untuk melihat perubahan
                        } else {
                            alert('Error: ' + response.message);
                            console.error('Server Error:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('AJAX Error: ' + xhr.responseText);
                        console.error('AJAX Error Details:', xhr.responseText);
                    }
                });
            });

            // Event listener untuk tombol 'VIEW' di tabel master penjualan
            $('#tabel-penjualan-body').on('click', '.view-detail-btn', function() {
                const kodetr = $(this).data('kodetr');

                $.ajax({
                    type: 'POST',
                    url: 'prosespenjualan.php',
                    data: {
                        mode: 'view',
                        kodetr: kodetr
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success' && response.data) {
                            const header = response.data.header;
                            const detail = response.data.detail;

                            $('#view-kodetr').text(header.kodetr);
                            $('#view-tanggal').text(header.tanggal);
                            $('#view-konsumen').text(header.konsumen);
                            $('#view-telp').text(header.telp || '-');
                            $('#view-alamat').text(header.alamat || '-');

                            let detailHtml = '';
                            let totalQtyView = 0;
                            let totalHargaView = 0;
                            detail.forEach(item => {
                                detailHtml += `
                                    <tr>
                                        <td>${item.kodeitem}</td>
                                        <td>${item.namaitem}</td>
                                        <td>${parseFloat(item.hargajual).toLocaleString('id-ID')}</td>
                                        <td>${item.qty}</td>
                                        <td>${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
                                    </tr>
                                `;
                                totalQtyView += parseInt(item.qty);
                                totalHargaView += parseFloat(item.subtotal);
                            });
                            $('#view-detail-body').html(detailHtml);
                            $('#view-totalqty').text(totalQtyView);
                            $('#view-totalharga').text(totalHargaView.toLocaleString('id-ID'));

                            // Set data-kodetr untuk tombol hapus
                            $('#btn-hapus-transaksi').data('kodetr', kodetr);

                            $('#modalViewDetail').modal('show');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('AJAX Error: ' + xhr.responseText);
                        console.error('AJAX Error Details:', xhr.responseText);
                    }
                });
            });

            // Event listener untuk tombol 'Hapus Transaksi Ini' di modal detail
            $('#btn-hapus-transaksi').on('click', function() {
                const kodetrToDelete = $(this).data('kodetr');
                if (confirm('Yakin ingin menghapus transaksi ini? Tindakan ini tidak dapat dibatalkan.')) {
                    $.ajax({
                        type: 'POST',
                        url: 'prosespenjualan.php',
                        data: {
                            mode: 'hapus',
                            kodetr: kodetrToDelete
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(response.message);
                                $('#modalViewDetail').modal('hide');
                                location.reload(); // Reload halaman untuk melihat perubahan
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('AJAX Error: ' + xhr.responseText);
                            console.error('AJAX Error Details:', xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
