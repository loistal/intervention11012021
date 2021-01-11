<?php

# TODO remove this file

$showmap = 1; # display map
$input = $_POST['input'];
require('preload/placement.php');
if ($input != '') 
{
  $product = $input;
  require('inc/findproduct.php');
  if($num_products != 1)
  {
    echo '<p><h2>Aucun Produit trouvé :  ' . d_output($product) .'</h2><br>'; 

    $input = '';

  }
  else
  {
    if ($_SESSION['ds_mywarehouseid'] > 0)
    {
      $query = 'select pallet.placementid from pallet,placement
      where pallet.placementid=placement.placementid and productid=? and warehouseid=? and expiredate is not null order by expiredate limit 1';
      $query_prm = array($productid, $_SESSION['ds_mywarehouseid']);
    }
    else
    {
      $query = 'select placementid from pallet where productid=? and expiredate is not null order by expiredate limit 1';
      $query_prm = array($productid);
    }
    require('inc/doquery.php');

    if($num_results == 0)
    {
      echo '<h2>Aucun emplacement trouvé pour ce produit:<br><br>' . d_output($productname) .'</h2><br>'; 

      $input = '';
    }
    else
    {
      $placementid = $query_result[0]['placementid'];
      $placementname = $placementA[$placementid];
      echo '<table>';
      echo '<p align=center><tr><td><h2>' . $productname .'</h2<br>'; 
      echo '<p align=center><tr><td><h2>Emplacement: ' .d_output($placementname) .'</h2><br>'; 
      $input = '';
      echo '</table>';
    }
  } 
}

if ($input == '')
{
  echo '<form method="post" action="warehouse.php">';
  echo '<table>';
  echo '<input autofocus class="warehouse" type=text name=input size=20>';
  echo '<br>';
  echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
  echo '<input type="submit" style="position: absolute; left: -9999px"/>';
  echo '</table></form>';
  echo '<br>';
    if ($placementid != 0 && $showmap == 1)
  {
#    $placementname = $placementA[$placementid] ; 
#    $name_map = 'pics/TITIORO_' .$placement_mapA[$placementid] .'.png';    
#    $varx = $placement_pic_xA[$placementid] ;    
#    $vary = $placement_pic_yA[$placementid] ;    
  
#      $warehouse_3car =  substr($placementname, 0, 3); 
 
#    echo '<img src="warehouse_map.php?name_map=' .$name_map .'&varx=' .$varx .'&vary=' .$vary .'&placementname=' .$placementname .'" height="600px" width="800px" alt="image">';
    
   echo '<br><img src="warehouse_map.php?placementid=' .$placementid .'" height="800px" width="600px" alt="image">';

  }

}
?>

