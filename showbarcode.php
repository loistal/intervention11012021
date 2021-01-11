<?php

if ($_SESSION['ds_userid'] < 1) { require('logout.php'); exit; }

if (isset($_GET['barcode'])) { $barcode = $_GET['barcode']; }
if (isset($_POST['barcode'])) { $barcode = $_POST['barcode']; }
if (!isset($barcode)) { exit; }

if (isset($_GET['height'])) { $height = (int) $_GET['height']; }
else { $height = 200; }
if (isset($_GET['width'])) { $width = (int) $_GET['width']; }
else { $width = 500; }

echo '<img src="barcode.php?size=40&text=' . $barcode . '" width=' . $width . '; height=' . $height . '>';
?>