<?php
require '../config.php';
if ($_SESSION['role'] != 'admin') header('Location: ../login.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="alert alert-primary text-center mb-4">
                <h3 class="mb-0">Selamat datang, Admin!</h3>
            </div>
            <div class="list-group">
                <a href="siswa.php" class="list-group-item list-group-item-action">Manajemen Siswa</a>
                <a href="mapel.php" class="list-group-item list-group-item-action">Manajemen Mapel</a>
                <a href="nilai.php" class="list-group-item list-group-item-action">Manajemen Nilai</a>
            </div>
            <a href="../logout.php" class="btn btn-danger mt-4">Logout</a>
        </div>
    </div>
</div>
</body>
</html>