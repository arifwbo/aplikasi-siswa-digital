<?php
require '../config.php';
if ($_SESSION['role'] != 'siswa') header('Location: ../login.php');
$id_siswa = $_SESSION['id_siswa'];

// Dapatkan semester yang tersedia
$semesters = array();
$q = $conn->query("SELECT DISTINCT semester FROM nilai WHERE id_siswa=$id_siswa ORDER BY semester ASC");
while ($d = $q->fetch_assoc()) $semesters[] = $d['semester'];

// Dapatkan mapel yang pernah diambil
$mapels = array();
$q2 = $conn->query("SELECT DISTINCT m.id_mapel, m.nama_mapel 
    FROM nilai n JOIN mapel m ON n.id_mapel=m.id_mapel 
    WHERE n.id_siswa=$id_siswa ORDER BY m.nama_mapel ASC");
while ($d = $q2->fetch_assoc()) $mapels[] = $d;

// Pilihan semester & mapel dari form
$semester = (isset($_GET['semester']) && in_array($_GET['semester'],$semesters)) ? intval($_GET['semester']) : (count($semesters)>0 ? $semesters[0] : 1);
$id_mapel = isset($_GET['id_mapel']) ? intval($_GET['id_mapel']) : 0;

// Tampilkan nilai: semua mapel atau per mapel
if ($id_mapel) {
    $result = $conn->query("SELECT m.nama_mapel, n.nilai_angka 
        FROM nilai n JOIN mapel m ON n.id_mapel=m.id_mapel 
        WHERE n.id_siswa=$id_siswa AND n.semester=$semester AND m.id_mapel=$id_mapel");
} else {
    $result = $conn->query("SELECT m.nama_mapel, n.nilai_angka 
        FROM nilai n JOIN mapel m ON n.id_mapel=m.id_mapel 
        WHERE n.id_siswa=$id_siswa AND n.semester=$semester");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lihat Nilai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Nilai Siswa</h2>
    <form method="GET" class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <label class="col-form-label">Semester</label>
        </div>
        <div class="col-auto">
            <select name="semester" class="form-select" onchange="this.form.submit()">
                <?php foreach($semesters as $sem) echo "<option value='$sem' ".($semester==$sem?'selected':'').">$sem</option>"; ?>
            </select>
        </div>
        <div class="col-auto">
            <label class="col-form-label">Mapel</label>
        </div>
        <div class="col-auto">
            <select name="id_mapel" class="form-select" onchange="this.form.submit()">
                <option value="0">Semua</option>
                <?php foreach($mapels as $m) echo "<option value='".$m['id_mapel']."' ".($id_mapel==$m['id_mapel']?'selected':'').">".$m['nama_mapel']."</option>"; ?>
            </select>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
        <tr><th>Mata Pelajaran</th><th>Nilai</th></tr>
        </thead>
        <tbody>
        <?php
        $ada = false;
        while ($row = $result->fetch_assoc()) {
            $ada = true;
            echo "<tr>
                <td>".htmlspecialchars($row['nama_mapel'])."</td>
                <td>{$row['nilai_angka']}</td>
            </tr>";
        }
        if (!$ada) echo "<tr><td colspan='2'>Belum ada nilai.</td></tr>";
        ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary btn-sm">&laquo; Kembali</a>
    <br><br>
    <form method="GET" class="d-inline">
        <input type="hidden" name="semester" value="3">
        <input type="hidden" name="id_mapel" value="<?php
        $id_mat = 0;
        foreach($mapels as $m) if(strtolower($m['nama_mapel'])=='matematika') $id_mat = $m['id_mapel'];
        echo $id_mat;
        ?>">
        <button type="submit" class="btn btn-info btn-sm">Lihat Nilai Matematika Semester 3</button>
    </form>
    <?php
    if (isset($_GET['semester']) && isset($_GET['id_mapel']) && $_GET['semester']==3 && $_GET['id_mapel']>0) {
        $q = $conn->query("SELECT n.nilai_angka FROM nilai n 
            JOIN mapel m ON n.id_mapel=m.id_mapel 
            WHERE n.id_siswa=$id_siswa AND n.semester=3 AND m.id_mapel=".$_GET['id_mapel']);
        if ($d = $q->fetch_assoc()) echo "<div class='alert alert-success mt-3'>Nilai Matematika Semester 3: <strong>{$d['nilai_angka']}</strong></div>";
        else echo "<div class='alert alert-warning mt-3'>Nilai Matematika Semester 3 belum ada.</div>";
    }
    ?>
</div>
</body>
</html>