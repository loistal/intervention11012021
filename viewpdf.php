<?php

if (($_SESSION['ds_userid']) < 1) { require('logout.php'); exit; }
require ('inc/standard.php');

if(isset($_GET['image_id']) && is_numeric($_GET['image_id']))
{
  $image_id = $_GET['image_id'] + 0;
  $image_id = preg_replace('/[^0-9]/', '', $image_id);

  $query = 'select image from image where imageid=?';
  $query_prm = array($image_id);
  require('inc/doquery.php');

  header("Content-Type: application/pdf");
  echo $query_result[0]['image'];
}
?>