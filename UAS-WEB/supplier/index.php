<?php include "../database.php"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="container py-5">
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

    <div class="container pt-3 mt-2">
        <h2 class="mb-4">Data Supplier</h2>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Supplier</button>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>aksi</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>sales</th>
                    <th>Alamat</th>
                    <th>Telp</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM supplier";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="editData('<?= $row['idsupplier'] ?>','<?= $row['namasupplier'] ?>','<?= $row['sales'] ?>','<?= $row['alamat'] ?>','<?= $row['telp'] ?>')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="hapussupplier('<?= $row['idsupplier'] ?>')">Hapus</button>
                        </td>
                        <td><?= $row['idsupplier'] ?></td>
                        <td><?= $row['namasupplier'] ?></td>
                        <td><?= $row['sales'] ?></td>
                        <td><?= $row['alamat'] ?></td>
                        <td><?= $row['telp'] ?></td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Modal Tambah -->
        <div class="modal fade" id="modalTambah" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2"><label>ID Supplier</label><input type="text" class="form-control" id="id"></div>
                        <div class="mb-2"><label>Nama</label><input type="text" class="form-control" id="nama"></div>
                        <div class="mb-2"><label>Sales</label><input type="text" class="form-control" id="sales"></div>
                        <div class="mb-2"><label>Alamat</label><textarea class="form-control" id="alamat"></textarea></div>
                        <div class="mb-2"><label>Telp</label><input type="text" class="form-control" id="telp"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="simpan">Simpan</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEdit" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idedit">
                        <div class="mb-2"><label>Nama</label><input type="text" class="form-control" id="namaedit"></div>
                        <div class="mb-2"><label>Sales</label><input type="text" class="form-control" id="salesedit"></div>
                        <div class="mb-2"><label>Alamat</label><textarea class="form-control" id="alamatedit"></textarea></div>
                        <div class="mb-2"><label>Telp</label><input type="text" class="form-control" id="telpedit"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning" id="ubah">Ubah</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editData(id, nama, sales, alamat, telp) {
            $("#idedit").val(id);
            $("#namaedit").val(nama);
            $("#salesedit").val(sales);
            $("#alamatedit").val(alamat);
            $("#telpedit").val(telp);
            $("#modalEdit").modal("show");
        }

        $("#simpan").click(function() {
            $.post("prosessupplier.php", {
                aksi: "tambah", // Mengirim parameter aksi
                id: $("#id").val(),
                nama: $("#nama").val(),
                sales: $("#sales").val(),
                alamat: $("#alamat").val(),
                telp: $("#telp").val()
            }, function(res) {
                alert(res);
                location.reload();
            });
        });

        $("#ubah").click(function() {
            $.post("prosessupplier.php", {
                aksi: "ubah", // Mengirim parameter aksi
                id: $("#idedit").val(), // Menggunakan idedit untuk ID supplier yang akan diubah
                nama: $("#namaedit").val(),
                sales: $("#salesedit").val(), // Menggunakan salesedit
                alamat: $("#alamatedit").val(),
                telp: $("#telpedit").val()
            }, function(res) {
                alert(res);
                location.reload();
            });
        });

        function hapussupplier(idsupplier) {
            if (confirm("Yakin hapus data ini?")) {
                $.post("prosessupplier.php", {
                    aksi: "hapus", // Mengirim parameter aksi
                    id: idsupplier // Menggunakan idsupplier yang diterima dari fungsi
                }, function(res) {
                    alert(res);
                    location.reload();
                });
            }
        };
    </script>
</body>

</html>
