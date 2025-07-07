<?php
require '../config.php';
if ($_SESSION['role'] != 'admin') header('Location: ../login.php');

// Handle hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM mapel WHERE id_mapel=$id");
    header("Location: mapel.php?msg=hapus");
    exit;
}

// Handle tambah
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nama_mapel = $conn->real_escape_string($_POST['nama_mapel']);
    $conn->query("INSERT INTO mapel (nama_mapel) VALUES ('$nama_mapel')");
    header("Location: mapel.php?msg=tambah");
    exit;
}

// Handle edit
if (isset($_POST['edit'])) {
    $id_mapel = intval($_POST['id_mapel']);
    $nama_mapel = $conn->real_escape_string($_POST['nama_mapel']);
    $conn->query("UPDATE mapel SET nama_mapel='$nama_mapel' WHERE id_mapel=$id_mapel");
    header("Location: mapel.php?msg=edit");
    exit;
}

// Untuk form edit
$mapel_edit = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM mapel WHERE id_mapel=$id");
    if ($res) $mapel_edit = $res->fetch_assoc();
}

// List semua mapel
$result = $conn->query("SELECT * FROM mapel ORDER BY nama_mapel ASC");

// Notifikasi
$msg = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg']=='tambah') $msg = 'Mapel berhasil ditambah.';
    elseif ($_GET['msg']=='edit') $msg = 'Mapel berhasil diubah.';
    elseif ($_GET['msg']=='hapus') $msg = 'Mapel berhasil dihapus.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manajemen Mapel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">&laquo; Kembali ke Dashboard</a>
    <?php if($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>
    <div class="card mb-4">
        <div class="card-header"><?= (isset($mapel_edit) && $mapel_edit) ? 'Edit Mapel' : 'Tambah Mapel' ?></div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="id_mapel" value="<?= isset($mapel_edit['id_mapel']) ? $mapel_edit['id_mapel'] : '' ?>">
                <div class="col-md-6">
                    <label class="form-label">Nama Mapel:</label>
                    <input name="nama_mapel" class="form-control" required value="<?= isset($mapel_edit['nama_mapel']) ? $mapel_edit['nama_mapel'] : '' ?>">
                </div>
                <div class="col-12">
                    <?php if (isset($mapel_edit) && $mapel_edit) { ?>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="mapel.php" class="btn btn-secondary">Batal</a>
                    <?php } else { ?>
                        <button type="submit" name="tambah" class="btn btn-success">Tambah Mapel</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Daftar Mapel</div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                <tr>
                    <th>No</th><th>Nama Mapel</th><th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no=1; while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_mapel']) ?></td>
                        <td>
                            <a href="mapel.php?edit=<?= $row['id_mapel'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="mapel.php?hapus=<?= $row['id_mapel'] ?>" onclick="return confirm('Hapus mapel ini?')" class="btn btn-danger btn-sm">Hapus</a>
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