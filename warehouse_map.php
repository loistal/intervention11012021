<?php

if ($_SESSION['ds_warehouseaccess'] != 1) { require('logout.php'); exit; }

# displays a single map of a warehouse

# input: list of placementids, example: ?placementid=3,5,8

header ("Content-type: image/png");

require ('inc/standard.php');

$mapid = 0;
$placementidA = explode(",", $_GET['placementid']);
foreach($placementidA as $placementid)
{
  $query = 'select placementid,placementname,mapid,map_start_x,map_start_y,map_stop_x,map_stop_y from placement where placementid=?';
  $query_prm = array($placementid);
  require('inc/doquery.php');
  
  if ($mapid == 0)
  {
    $mapid = $query_result[0]['mapid']+0;
    
    if ($mapid == 0)
    {
      $image = imagecreatetruecolor(600, 800);
      $bg = imagecolorallocate ( $image, 255, 255, 255 );
      imagefilledrectangle($image,0,0,600,800,$bg);
    }
    else
    {
      $placement_map = 'custom/' . $_SESSION['ds_customname'] . '_warehousemap_'.$mapid.'.png';
      $image = imagecreatefrompng($placement_map);
    }
    
    imagesetthickness($image, 5);
    $red = imagecolorallocate($image, 255, 10, 10);
  }
  
  if ($num_results == 1 && $mapid != 0)
  {
    $placementname = $query_result[0]['placementname']; 
    
    $map_start_x = $query_result[0]['map_start_x']; 
    $map_start_y = $query_result[0]['map_start_y']; 
    $map_stop_x = $query_result[0]['map_stop_x']; 
    $map_stop_y = $query_result[0]['map_stop_y'];

    imagerectangle($image, $map_start_x, $map_start_y, $map_stop_x, $map_stop_y, $red);
  }
}

imagepng($image);

?> 


