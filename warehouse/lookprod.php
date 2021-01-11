<?php

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
    /*$query = 'select product.* from product where productid = ? limit 1';
    $query_prm = array($productid);
    require('inc/doquery.php');
    $result =  $query_result;
    $num_result = $num_results;*/

   
    /*$showproductname = d_output(d_decode($query_result[0]['productname']));
    if ($_SESSION['ds_useproductcode']) { $showproductname .= ' ('.d_output($query_result[0]['suppliercode']).') '; }
    else { $showproductname .= ' ('.$query_result[0]['productid'].') '; }
    if ($query_result[0]['netweightlabel'] != '')
      {
        if ($query_result[0]['numberperunit'] > 1) { $showproductname .= $query_result[0]['numberperunit'] . ' x '; }
        $showproductname .= d_output($query_result[0]['netweightlabel']);
      }*/
    
    $query = 'select placementid from pallet where productid = ? order by expiredate limit 1';
    $query_prm = array($productid);
    require('inc/doquery.php');

    if($num_results == 0)
    {
      echo '<p> <h2>Aucun Emplacement trouvé pour ce produit:  ' . d_output($productname) .'</h2><br>'; 

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
  
}
?>