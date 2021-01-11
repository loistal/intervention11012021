<?php ob_start(); ?>
<!doctype html>
<html>
<head>
  <meta http-equiv=content-type content="text/html; charset=UTF-8">
  <link rel="icon" href="pics/temico.png" type="image/png">
  <?php
  if (isset($_SESSION['ds_menustyle']) && $_SESSION['ds_menustyle'] == 5)
  {
    ?>
    <link rel="stylesheet" type="text/css" href="font-awesome.css">
    <link rel="stylesheet" type="text/css" href="style5.css">
    <?php
  }
  else
  {
    ?>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php
    require_once('style.php');
    if (!isset($_SESSION['ds_menustyle']) || $_SESSION['ds_menustyle'] == 4)
    {
      ?>
      <link rel="stylesheet" type="text/css" href="style4.css">
      <?php
      if(file_exists('style4.php')) { require_once('style4.php'); }
    }
    elseif ($_SESSION['ds_menustyle'] == 2)
    {
      ?>
      <link rel="stylesheet" type="text/css" href="style2.css">
      <?php
      if(file_exists('style2.php')) { require_once('style2.php'); }
    }
    elseif ($_SESSION['ds_menustyle'] == 3)
    {
      ?>
      <link rel="stylesheet" type="text/css" href="style3.css">
      <?php
      if(file_exists('style3.php')) { require_once('style3.php'); }
    }
    else
    {
      ?>
      <link rel="stylesheet" type="text/css" href="style1.css">
      <?php
      if(file_exists('style1.php')) { require_once('style1.php'); }
    }
  }
  ?>
</head>
<body>