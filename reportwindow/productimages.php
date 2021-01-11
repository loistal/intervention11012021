<?php

$productid = (int) $_GET['productid']; if ($productid < 1) { exit; }
$imageid = (int) $_GET['imageid'];

$query = 'select imageid,imagetype from image where productid=?';
$query_prm = array($_GET['productid']);
if ($imageid > 0) { $query .= ' and imageid=?'; array_push($query_prm, $imageid); }
$query .= ' order by imageorder,imageid';

require ('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  if ($query_result[$i]['imagetype'] == 'pdf')
  {
    echo '<object type="text/html" codetype="application/pdf" data="viewpdf.php?image_id=' . $query_result[$i]['imageid'] . '" width="100%" height="800px"></object>';
  }
  else
  {
    echo '<img src="viewimage.php?image_id=' . $query_result[$i]['imageid'] . '"><br>';
  }
}

?>