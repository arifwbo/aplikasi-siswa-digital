<?php
require 'config.php';
if (!isset($_SESSION['login'])) header('Location: login.php');
if ($_SESSION['role'] == 'admin') header('Location: admin/dashboard.php');
else header('Location: siswa/dashboard.php');