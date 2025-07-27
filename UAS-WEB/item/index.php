<?php
include '../database.php'; // Pastikan path ke database.php benar
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <!-- Navbar -->
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

    <!-- Konten -->
    <div class="container pt-3 mt-5">
        <h2 class="mb-4">Data Item</h2>

        <!-- Tombol Tambah Item (Membuka Modal Tambah) -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Item
        </button>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Action</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                </tr>
            </thead>
            <tbody id="itemTableBody">
                <?php
                $sql = "SELECT * FROM item";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td>
                            <button class="btn btn-sm btn-warning"
                                onclick="editData('<?= $row['kodeitem'] ?>', '<?= $row['namaitem'] ?>', '<?= $row['satuan'] ?>', '<?= $row['hargabeli'] ?>', '<?= $row['hargajual'] ?>')">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="hapusItem('<?= $row['kodeitem'] ?>')">Hapus</button>

                        </td>
                        <td><?= $row["kodeitem"] ?></td>
                        <td><?= $row["namaitem"] ?></td>
                        <td><?= $row["satuan"] ?></td>
                        <td><?= number_format($row["hargabeli"], 0, ',', '.') ?></td>
                        <td><?= number_format($row["hargajual"], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Item -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tambah_kodeitem" class="form-label">Kode Item</label>
                        <input type="text" class="form-control" id="tambah_kodeitem" name="kodeitem" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_namaitem" class="form-label">Nama Item</label>
                        <input type="text" class="form-control" id="tambah_namaitem" name="namaitem" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="tambah_satuan" name="satuan" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_hargabeli" class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" id="tambah_hargabeli" name="hargabeli" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_hargajual" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="tambah_hargajual" name="hargajual" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="simpanItem">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Item -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_kodeitem_hidden">
                    <div class="mb-3">
                        <label for="edit_kodeitem" class="form-label">Kode Item</label>
                        <input type="text" class="form-control" id="edit_kodeitem" name="kodeitem" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_namaitem" class="form-label">Nama Item</label>
                        <input type="text" class="form-control" id="edit_namaitem" name="namaitem" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="edit_satuan" name="satuan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_hargabeli" class="form-label">Harga Beli</label>
                        <input type="number" class="form-control" id="edit_hargabeli" name="hargabeli" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_hargajual" class="form-label">Harga Jual</label>
                        <input type="number" class="form-control" id="edit_hargajual" name="hargajual" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="ubahItem">Ubah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mengisi data ke modal edit
        function editData(kodeitem, namaitem, satuan, hargabeli, hargajual) {
            $("#edit_kodeitem_hidden").val(kodeitem); // Untuk menyimpan kodeitem asli
            $("#edit_kodeitem").val(kodeitem);
            $("#edit_namaitem").val(namaitem);
            $("#edit_satuan").val(satuan);
            $("#edit_hargabeli").val(hargabeli);
            $("#edit_hargajual").val(hargajual);
            $("#modalEdit").modal("show");
        }

        $(document).ready(function() {
            // Event listener untuk tombol "Simpan" di modal Tambah
            $("#simpanItem").click(function() {
                $.post("prosesitem.php", {
                    aksi: "tambah",
                    kodeitem: $("#tambah_kodeitem").val(),
                    namaitem: $("#tambah_namaitem").val(),
                    satuan: $("#tambah_satuan").val(),
                    hargabeli: $("#tambah_hargabeli").val(),
                    hargajual: $("#tambah_hargajual").val()
                }, function(res) {
                    alert(res);
                    location.reload();
                });
            });

            // Event listener untuk tombol "Ubah" di modal Edit
            $("#ubahItem").click(function() {
                $.post("prosesitem.php", {
                    aksi: "ubah",
                    kodeitem: $("#edit_kodeitem_hidden").val(), // Gunakan kodeitem asli
                    namaitem: $("#edit_namaitem").val(),
                    satuan: $("#edit_satuan").val(),
                    hargabeli: $("#edit_hargabeli").val(),
                    hargajual: $("#edit_hargajual").val()
                }, function(res) {
                    alert(res);
                    location.reload();
                });
            });
        });
        
        // Event listener untuk tombol "Hapus"
        function hapusItem(kodeitem) { // Perbaiki nama fungsi dan gunakan parameter kodeitem
            if (confirm("Yakin ingin menghapus data ini?")) {
                $.post("prosesitem.php", {
                    aksi: "hapus",
                    kodeitem: kodeitem // Gunakan kodeitem yang diterima sebagai parameter
                }, function(res) {
                    alert(res);
                    location.reload();
                });
            }
        };
    </script>
</body>

</html>
