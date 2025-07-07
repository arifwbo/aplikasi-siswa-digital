<?php
require '../config.php';
require_once '../vendor/autoload.php'; // pastikan path benar

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SESSION['role'] != 'admin') header('Location: ../login.php');

// Handle import Excel
$import_excel_msg = '';
if (isset($_POST['import_excel'])) {
    if (isset($_FILES['excel']) && $_FILES['excel']['error'] == 0) {
        $file = $_FILES['excel']['tmp_name'];
        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            $success = 0; $fail = 0;
            foreach ($rows as $i => $row) {
                if ($i == 0) continue; // skip header
                if (count($row) < 8) { $fail++; continue; }
                $nis = $conn->real_escape_string(trim($row[0]));
                $nama = $conn->real_escape_string(trim($row[1]));
                $alamat = $conn->real_escape_string(trim($row[2]));
                $tempat_lahir = $conn->real_escape_string(trim($row[3]));
                $tanggal_lahir = $conn->real_escape_string(trim($row[4]));
                $jenis_kelamin = $conn->real_escape_string(trim($row[5]));
                $no_hp = $conn->real_escape_string(trim($row[6]));
                $email = $conn->real_escape_string(trim($row[7]));
                if ($nis && $nama) {
                    $q = $conn->query("INSERT INTO siswa (nis, nama, alamat, tempat_lahir, tanggal_lahir, jenis_kelamin, no_hp, email) VALUES ('$nis','$nama','$alamat','$tempat_lahir','$tanggal_lahir','$jenis_kelamin','$no_hp','$email')");
                    if ($q) $success++; else $fail++;
                } else {
                    $fail++;
                }
            }
            $import_excel_msg = "Import selesai: <b>$success</b> data berhasil, <b>$fail</b> gagal.";
        } catch (Exception $e) {
            $import_excel_msg = "Gagal membaca file Excel: " . $e->getMessage();
        }
    } else {
        $import_excel_msg = "Gagal upload file.";
    }
}

// ... lanjutkan kode hapus, tambah, edit, form, dst, seperti sebelumnya ...
// ... TIDAK ADA perubahan pada kode lain ...

// Untuk form edit
$siswa_edit = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM siswa WHERE id_siswa=$id");
    if ($res) $siswa_edit = $res->fetch_assoc();
}

// List semua siswa
$result = $conn->query("SELECT * FROM siswa ORDER BY nama ASC");

// Notifikasi
$msg = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg']=='tambah') $msg = 'Data siswa berhasil ditambah.';
    elseif ($_GET['msg']=='edit') $msg = 'Data siswa berhasil diubah.';
    elseif ($_GET['msg']=='hapus') $msg = 'Data siswa berhasil dihapus.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manajemen Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary btn-sm">&laquo; Kembali ke Dashboard</a>
    </div>
    <?php if($import_excel_msg): ?>
        <div class="alert alert-info"><?php echo $import_excel_msg; ?></div>
    <?php endif; ?>
    <?php if($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <!-- FORM IMPORT EXCEL -->
    <div class="card mb-4">
        <div class="card-header">Import Siswa dari Excel</div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <input type="file" name="excel" class="form-control" accept=".xls,.xlsx" required>
                </div>
                <div class="col-auto">
                    <button type="submit" name="import_excel" class="btn btn-success">Import Excel</button>
                </div>
                <div class="col-12 small text-muted">
                    Format kolom: nis, nama, alamat, tempat_lahir, tanggal_lahir, jenis_kelamin, no_hp, email (baris pertama = header)<br>
                    <a href="contoh_import_siswa.xlsx" download>Download contoh Excel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- FORM TAMBAH/EDIT SISWA DAN TABEL SISWA (kode kamu sebelumnya, tidak berubah) -->
    <div class="card mb-4">
        <div class="card-header"><?= isset($siswa_edit) && $siswa_edit ? 'Edit Siswa' : 'Tambah Siswa' ?></div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="id_siswa" value="<?= isset($siswa_edit['id_siswa']) ? $siswa_edit['id_siswa'] : '' ?>">
                <div class="col-md-3">
                    <label class="form-label">NIS</label>
                    <input name="nis" class="form-control" required value="<?= isset($siswa_edit['nis']) ? $siswa_edit['nis'] : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input name="nama" class="form-control" required value="<?= isset($siswa_edit['nama']) ? $siswa_edit['nama'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Alamat</label>
                    <input name="alamat" class="form-control" value="<?= isset($siswa_edit['alamat']) ? $siswa_edit['alamat'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input name="tempat_lahir" class="form-control" value="<?= isset($siswa_edit['tempat_lahir']) ? $siswa_edit['tempat_lahir'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input name="tanggal_lahir" type="date" class="form-control" value="<?= isset($siswa_edit['tanggal_lahir']) ? $siswa_edit['tanggal_lahir'] : '' ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="L" <?= isset($siswa_edit['jenis_kelamin']) && $siswa_edit['jenis_kelamin']=='L' ? 'selected':''; ?>>Laki-laki</option>
                        <option value="P" <?= isset($siswa_edit['jenis_kelamin']) && $siswa_edit['jenis_kelamin']=='P' ? 'selected':''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">No HP</label>
                    <input name="no_hp" class="form-control" value="<?= isset($siswa_edit['no_hp']) ? $siswa_edit['no_hp'] : '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input name="email" class="form-control" value="<?= isset($siswa_edit['email']) ? $siswa_edit['email'] : '' ?>">
                </div>
                <div class="col-12">
                    <?php if (isset($siswa_edit) && $siswa_edit) { ?>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="siswa.php" class="btn btn-secondary">Batal</a>
                    <?php } else { ?>
                        <button type="submit" name="tambah" class="btn btn-success">Tambah Siswa</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Daftar Siswa</div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                <tr>
                    <th>No</th><th>NIS</th><th>Nama</th><th>Alamat</th><th>Tempat, Tgl Lahir</th>
                    <th>JK</th><th>No HP</th><th>Email</th><th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nis']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['alamat']) ?></td>
                        <td>
                            <?= htmlspecialchars($row['tempat_lahir']) ?>,
                            <?php
                            if (!empty($row['tanggal_lahir']) && $row['tanggal_lahir'] != '0000-00-00') {
                                $tgl = date_create_from_format('Y-m-d', $row['tanggal_lahir']);
                                echo $tgl ? $tgl->format('d-m-Y') : htmlspecialchars($row['tanggal_lahir']);
                            }
                            ?>
                        </td>
                        <td><?= $row['jenis_kelamin'] ?></td>
                        <td><?= htmlspecialchars($row['no_hp']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <a href="siswa.php?edit=<?= $row['id_siswa'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="siswa.php?hapus=<?= $row['id_siswa'] ?>" onclick="return confirm('Hapus siswa ini?')" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>