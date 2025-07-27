<?php include "../database.php"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="container py-4">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UAS</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../item/">ITEM</a></li>
                    <li class="nav-item"><a class="nav-link" href="../supplier/">SUPPLIER</a></li>
                    <li class="nav-item"><a class="nav-link" href="../masterpenjualan/">PENJUALAN</a></li>
                    <li class="nav-item"><a class="nav-link" href="../masterpembelian/">PEMBELIAN</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container pt-5 mt-2">
        <h2 class="mb-4">Master Pembelian</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalPembelian">+ Tambah Pembelian</button>

        <!-- ✅ TABEL -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Action</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Total Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT m.idpembelian, m.tanggal, s.namasupplier, m.totalqty, m.totalharga 
                    FROM masterpembelian m
                    JOIN supplier s ON m.idsupplier = s.idsupplier
                    ORDER BY m.tanggal DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td><button class='btn btn-sm btn-info view-btn' data-id='{$row['idpembelian']}'>VIEW</button>
                    <td>{$row['tanggal']}</td>
                    <td>{$row['namasupplier']}</td>
                    <td>{$row['totalqty']}</td>
                    <td>Rp " . number_format($row['totalharga'], 0, ',', '.') . "</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- ✅ MODAL FORM -->
        <div class="modal fade" id="modalPembelian" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transaksi Pembelian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Supplier</label>
                                <select id="idsupplier" class="form-select">
                                    <option value="">-- Pilih Supplier --</option>
                                    <?php
                                    $q = $conn->query("SELECT idsupplier, namasupplier, sales, telp FROM supplier ORDER BY namasupplier");
                                    while ($row = $q->fetch_assoc()) {
                                        // Menghilangkan htmlspecialchars
                                        $json = json_encode($row);
                                        echo "<option value='{$row['idsupplier']}' data-supplier='{$json}'>{$row['namasupplier']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nama Sales</label>
                                <input type="text" id="sales" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Telp</label>
                                <input type="text" id="telp" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">No DO</label>
                                <input type="text" id="nodo" class="form-control">
                            </div>
                        </div>

                        <textarea id="keterangan" class="form-control mb-3" placeholder="Keterangan..."></textarea>

                        <h6 class="fw-bold">Tambah Item</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-md-2">
                                <label class="form-label">Kode</label>
                                <select id="iditem" class="form-select">
                                    <option value="">-- Pilih Kode Item --</option>
                                    <?php
                                    $q = $conn->query("SELECT kodeitem, namaitem, satuan, hargabeli FROM item");
                                    while ($row = $q->fetch_assoc()) {
                                        $json = json_encode($row);
                                        echo "<option value='{$row['kodeitem']}' data-item='{$json}'>{$row['kodeitem']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2"><label class="form-label">Nama Item</label><input type="text" id="nama" class="form-control" readonly></div>
                            <div class="col-md-2"><label class="form-label">Satuan</label><input type="text" id="satuan" class="form-control" readonly></div>
                            <div class="col-md-2"><label class="form-label">Harga</label><input type="number" id="harga" class="form-control" readonly></div>
                            <div class="col-md-2"><label class="form-label">Qty</label><input type="number" id="qty" class="form-control"></div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button id="tambah" class="btn btn-success w-100">+ Tambah</button>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Action</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="tabledata"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">TOTAL QTY</td>
                                    <td>
                                        <div id="totalqty">0</div>
                                    </td>
                                    <td>
                                        <div id="total">0</div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button id="save" class="btn btn-primary">SIMPAN</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ✅ MODAL VIEW -->
        <div class="modal fade" id="modalView" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pembelian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Kode:</strong> <span id="view-kode"></span><br>
                                <strong>Tanggal:</strong> <span id="view-tanggal"></span><br>
                                <strong>Supplier:</strong> <span id="view-supplier"></span><br>
                                <strong>Sales / Telp:</strong> <span id="view-sales"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>No DO:</strong> <span id="view-nodo"></span><br>
                                <strong>Keterangan:</strong><br>
                                <div id="view-ket"></div>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="view-detail-body"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <!-- ✅ Tombol Delete pakai ID -->
                        <button id="btn-hapus" class="btn btn-danger me-auto">Delete</button>

                        <!-- Tombol Print & Back -->
                        <button id="btn-print" class="btn btn-primary">Print Nota</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPrintId = null; // Variabel untuk menyimpan ID pembelian yang sedang dilihat

        function hitung() {
            let total = 0,
                totalqty = 0;
            $('#tabledata tr').each(function() {
                let harga = Number($(this).find("td:eq(4)").text());
                let qty = Number($(this).find("td:eq(5)").text());
                total += harga * qty;
                totalqty += qty;
            });
            $("#totalqty").text(totalqty);
            $("#total").text(total);
        }

        $("#iditem").change(function() {
            let data = $(this).find(':selected').data('item');
            if (data) {
                $("#nama").val(data.namaitem);
                $("#satuan").val(data.satuan);
                $("#harga").val(data.hargabeli);
            } else {
                $("#nama").val('');
                $("#satuan").val('');
                $("#harga").val('');
            }
        });

        $("#idsupplier").change(function() {
            let selectedOption = $(this).find(':selected');
            let supplierData = selectedOption.data('supplier');

            if (supplierData) {
                $("#sales").val(supplierData.sales);
                $("#telp").val(supplierData.telp);
            } else {
                $("#sales").val('');
                $("#telp").val('');
            }
        });

        $("#tambah").click(function() {
            const iditem = $("#iditem").val();
            const nama = $("#nama").val();
            const satuan = $("#satuan").val();
            const harga = $("#harga").val();
            const qty = $("#qty").val();
            const subtotal = harga * qty;

            if (!iditem || !qty) {
                alert("Pilih item dan isi qty!");
                return;
            }

            $("#tabledata").append(`
            <tr>
                <td><button class="btn btn-sm btn-danger remove">X</button></td>
                <td>${iditem}</td>
                <td>${nama}</td>
                <td>${satuan}</td>
                <td>${harga}</td>
                <td>${qty}</td>
                <td>${subtotal}</td>
            </tr>
        `);
            hitung();
        });

        $('#tabledata').on('click', '.remove', function() {
            $(this).closest('tr').remove();
            hitung();
        });

        $("#save").click(function() {
            const formData = new FormData();
            formData.append("tanggal", $("#tanggal").val());
            formData.append("idsupplier", $("#idsupplier").val());
            formData.append("sales", $("#sales").val());
            formData.append("telp", $("#telp").val());
            formData.append("nodo", $("#nodo").val());
            formData.append("keterangan", $("#keterangan").val());
            formData.append("total", $("#total").text());
            formData.append("totalqty", $("#totalqty").text());

            const detail = [];
            $("#tabledata tr").each(function() {
                const row = $(this);
                detail.push({
                    iditem: row.find("td:eq(1)").text(),
                    satuan: row.find("td:eq(3)").text(),
                    harga: row.find("td:eq(4)").text(),
                    qty: row.find("td:eq(5)").text(),
                    subtotal: row.find("td:eq(6)").text()
                });
            });
            formData.append("detail", JSON.stringify(detail));

            $.ajax({
                type: "POST",
                url: "prosespembelian.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        alert(res.success);
                        location.reload();
                    } else {
                        alert(res.error || "Gagal menyimpan");
                    }
                },
                error: function(xhr) {
                    alert("AJAX error: " + xhr.responseText);
                }
            });
        });

        $(document).on("click", ".view-btn", function() {
            const id = $(this).data("id");
            currentPrintId = id; // Simpan ID untuk print nota

            $.ajax({
                type: "POST",
                url: "prosespembelian.php",
                data: {
                    mode: "view",
                    idpembelian: id
                },
                success: function(res) {
                    if (res.header && res.detail) {
                        $("#view-kode").text(res.header.idpembelian);
                        $("#view-tanggal").text(res.header.tanggal);
                        $("#view-supplier").text(res.header.namasupplier);
                        $("#view-sales").text(res.header.sales + " / " + res.header.telp);
                        $("#view-nodo").text(res.header.nodo);
                        $("#view-ket").text(res.header.keterangan);

                        $("#btn-hapus").data("id", id);

                        let html = "";
                        res.detail.forEach((d) => {
                            html += `<tr>
                            <td>${d.iditem}</td>
                            <td>${d.namaitem}</td>
                            <td>${d.satuan}</td>
                            <td>${d.harga}</td>
                            <td>${d.qty}</td>
                            <td>${d.subtotal}</td>
                        </tr>`;
                        });
                        $("#view-detail-body").html(html);
                        $("#modalView").modal("show");
                    } else {
                        alert("Data tidak ditemukan.");
                    }
                },
                error: function(xhr) {
                    alert("Gagal memuat data: " + xhr.responseText);
                }
            });
        });

        $("#btn-hapus").click(function() {
            const id = $(this).data("id");
            if (id) {
                hapusPembelian(id);
            } else {
                alert("ID tidak ditemukan.");
            }
        });

        // Fungsi untuk tombol Print Nota
        $("#btn-print").click(function() {
            if (currentPrintId) {
                cetakNota(currentPrintId);
            } else {
                alert("Tidak ada pembelian yang sedang dilihat.");
            }
        });

        function cetakNota(idpembelian) {
            window.open("printnotapembelian.php?id=" + idpembelian, "_blank");
        }

        function hapusPembelian(idpembelian) {
            if (confirm("Yakin ingin menghapus transaksi ini?")) {
                $.ajax({
                    url: "prosespembelian.php",
                    type: "POST",
                    data: {
                        mode: "delete",
                        idpembelian: idpembelian
                    },
                    success: function(res) {
                        if (res.success) {
                            alert(res.success);
                            location.reload();
                        } else {
                            alert(res.error || "Terjadi kesalahan.");
                        }
                    },
                    error: function(xhr) {
                        alert("Gagal AJAX: " + xhr.responseText);
                    }
                });
            }
        }
    </script>
</body>

</html>
