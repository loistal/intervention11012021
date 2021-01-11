<?php

exit;

require ('inc/standard.php');

$query = 'select * from globalvariables where primaryunique=1';
$query_prm = array('dauphin');
require ('inc/doquery.php');

if ($query_result[0]['customname'] == 'Wing Chong')
{
  if(isset($_GET['image_id']) && is_numeric($_GET['image_id']))
  {
    $image_id = $_GET['image_id'] + 0;
    $image_id = preg_replace('/[^0-9]/', '', $image_id);

    $query = 'select image from image where imageid=? and productid>0'; # only allow product images!
    $query_prm = array($image_id);
    require('inc/doquery.php');
    
    header("Content-Type: image");
    echo $query_result[0]['image'];
  }
}
?>