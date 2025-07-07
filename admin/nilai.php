<?php
require '../config.php';
if ($_SESSION['role'] != 'admin') header('Location: ../login.php');

// Handle hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM nilai WHERE id_nilai=$id");
    header("Location: nilai.php?msg=hapus");
    exit;
}

// Handle tambah
if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['tambah'])) {
    $id_siswa = intval($_POST['id_siswa']);
    $id_mapel = intval($_POST['id_mapel']);
    $semester = intval($_POST['semester']);
    $nilai = floatval($_POST['nilai']);
    $conn->query("INSERT INTO nilai (id_siswa,id_mapel,semester,nilai_angka) VALUES ($id_siswa,$id_mapel,$semester,$nilai)");
    header("Location: nilai.php?msg=tambah");
    exit;
}

// Handle edit
if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['edit'])) {
    $id_nilai = intval($_POST['id_nilai']);
    $id_siswa = intval($_POST['id_siswa']);
    $id_mapel = intval($_POST['id_mapel']);
    $semester = intval($_POST['semester']);
    $nilai = floatval($_POST['nilai']);
    $conn->query("UPDATE nilai SET id_siswa=$id_siswa, id_mapel=$id_mapel, semester=$semester, nilai_angka=$nilai WHERE id_nilai=$id_nilai");
    header("Location: nilai.php?msg=edit");
    exit;
}

// Untuk form edit
$nilai_edit = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM nilai WHERE id_nilai=$id");
    if ($res) $nilai_edit = $res->fetch_assoc();
}

// Untuk form dropdown
$siswa = $conn->query("SELECT * FROM siswa");
$mapel = $conn->query("SELECT * FROM mapel");

// List semua nilai
$result = $conn->query("SELECT n.*, s.nama, m.nama_mapel FROM nilai n 
    JOIN siswa s ON n.id_siswa=s.id_siswa 
    JOIN mapel m ON n.id_mapel=m.id_mapel 
    ORDER BY s.nama, n.semester, m.nama_mapel");

// Notifikasi
$msg = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg']=='tambah') $msg = 'Nilai berhasil ditambah.';
    elseif ($_GET['msg']=='edit') $msg = 'Nilai berhasil diubah.';
    elseif ($_GET['msg']=='hapus') $msg = 'Nilai berhasil dihapus.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manajemen Nilai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">&laquo; Kembali ke Dashboard</a>
    <?php if($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    <div class="card mb-4">
        <div class="card-header"><?= (isset($nilai_edit) && $nilai_edit) ? "Edit Nilai" : "Input Nilai" ?></div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="id_nilai" value="<?= isset($nilai_edit['id_nilai']) ? $nilai_edit['id_nilai'] : '' ?>">
                <div class="col-md-4">
                    <label class="form-label">Siswa</label>
                    <select name="id_siswa" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php
                        $siswa->data_seek(0);
                        while ($s = $siswa->fetch_assoc())
                            echo "<option value='".$s['id_siswa']."'".(isset($nilai_edit['id_siswa']) && $nilai_edit['id_siswa']==$s['id_siswa']?' selected':'').">".$s['nama']."</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="id_mapel" class="form-select" required>
                        <option value="">-- Pilih Mapel --</option>
                        <?php
                        $mapel->data_seek(0);
                        while ($m = $mapel->fetch_assoc())
                            echo "<option value='".$m['id_mapel']."'".(isset($nilai_edit['id_mapel']) && $nilai_edit['id_mapel']==$m['id_mapel']?' selected':'').">".$m['nama_mapel']."</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester</label>
                    <input type="number" min="1" max="6" name="semester" class="form-control" required value="<?= isset($nilai_edit['semester']) ? $nilai_edit['semester'] : '' ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Nilai</label>
                    <input type="number" step="0.01" name="nilai" class="form-control" required value="<?= isset($nilai_edit['nilai_angka']) ? $nilai_edit['nilai_angka'] : '' ?>">
                </div>
                <div class="col-12">
                    <?php if (isset($nilai_edit) && $nilai_edit) { ?>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="nilai.php" class="btn btn-secondary">Batal</a>
                    <?php } else { ?>
                        <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Daftar Nilai</div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                <tr>
                    <th>No</th><th>Siswa</th><th>Mapel</th><th>Semester</th><th>Nilai</th><th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['nama_mapel']) ?></td>
                        <td><?= $row['semester'] ?></td>
                        <td><?= $row['nilai_angka'] ?></td>
                        <td>
                            <a href="nilai.php?edit=<?= $row['id_nilai'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="nilai.php?hapus=<?= $row['id_nilai'] ?>" onclick="return confirm('Hapus nilai ini?')" class="btn btn-danger btn-sm">Hapus</a>
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