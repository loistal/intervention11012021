<?php

if ($_SESSION['ds_userid'] < 1) { require('logout.php'); exit; }

$PA['qr_location_text'] = '';
$PA['qr_location_eventid'] = 'uint';
require('inc/readpost.php');

require ('inc/standard.php');
require ('inc/top.php');
echo '<h2 style="font-size: 500%">';

if ($qr_location_eventid)
{
  $query = 'update qr_location_event set qr_location_text=? where qr_location_eventid=?';
  $query_prm = array($qr_location_text,$qr_location_eventid);
  require('inc/doquery.php');
  
  if (is_uploaded_file($_FILES['imagefile']['tmp_name']))
  {
    $image = file_get_contents($_FILES['imagefile']['tmp_name']);
    $imagetype = pathinfo($_FILES['imagefile']['tmp_name'], PATHINFO_EXTENSION);
    if ($image)
    {
      $query = 'insert into image (image,imagetype) values (?,?)';
      $query_prm = array($image,$imagetype);
      require('inc/doquery.php');
      $imageid = $query_insert_id;
      
      $query = 'update qr_location_event set imageid=? where qr_location_eventid=?';
      $query_prm = array($imageid,$qr_location_eventid);
      require('inc/doquery.php');
    }
  }
  else
  {
    echo 'Erreur avec l\'image.<br>';
  }
  
  if ($num_results) { echo 'Enregistré'; }
}
  
echo '</h2>';
require ('inc/bottom.php');

?>