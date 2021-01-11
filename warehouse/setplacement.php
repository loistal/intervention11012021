<?php

require('preload/placement.php');
require('preload/user.php');
$maxnumpallets = $_POST['maxnumpallets']+0; if ($maxnumpallets < 1) { $maxnumpallets = 1; }

$placementid = $_POST['placementid']+0;
$placementname = $_POST['placementname'];
$saveme =  $_POST['saveme']+0;

if ($placementid < 1 && $placementname != '')
{
  $placementid = array_search($placementname, $placementA);
}

if ($placementid > 0)
{
  require('preload/product.php'); # to show unittype
  require('preload/unittype.php'); # to show unittype
  if ($saveme)
  {
    $query = 'update placement set userid=?,counteddate=curdate(),countedtime=curtime() where placementid=?';
    $query_prm = array($_SESSION['ds_userid'],$placementid);
    require('inc/doquery.php');
    if ($num_results)
    {
      for ($i=0;$i<$maxnumpallets;$i++)
      {
        $query = 'select palletid from pallet_counted where placementid=? and linenr=?';
        $query_prm = array($placementid,$i);
        require('inc/doquery.php');
        $barcode = $_POST['barcode'.$i]; if ($barcode == NULL) { $barcode = ''; }
        $productid = $_POST['product'.$i]; if ($productid < 1) { $productid = 0; }
        $quantity = $_POST['quantity'.$i]+0; if ($quantity < 0) { $quantity = 0; }
        $quantityrest = $_POST['quantityrest'.$i]+0; if ($quantityrest < 0) { $quantityrest = 0; }
        $datename = 'pallet_exp' . $i; require('inc/datepickerresult.php');
        if ($query_result[0]['palletid'] > 0)
        {
          #echo 'updating';
          $query = 'update pallet_counted set barcode=?,productid=?,quantity=?,quantityrest=?,expiredate=? where placementid=? and linenr=?';
          $query_prm = array($barcode,$productid,$quantity,$quantityrest,$$datename,$placementid,$i);
          require('inc/doquery.php');
        }
        else
        {
          #echo 'inserting';
          $query = 'insert into pallet_counted (barcode,productid,quantity,quantityrest,expiredate,linenr,placementid) values (?,?,?,?,?,?,?)';
          $query_prm = array($barcode,$productid,$quantity,$quantityrest,$$datename,$i,$placementid);
          require('inc/doquery.php');
        }
      }
    }
  }
  
  $query = 'select max(linenr) as maxlinenr from pallet_counted where placementid=?';
  $query_prm = array($placementid);
  require('inc/doquery.php');
  $read_maxnumpallets = $query_result[0]['maxlinenr']+1;
  if ($read_maxnumpallets > $maxnumpallets) { $maxnumpallets = $read_maxnumpallets; }
  
  $query = 'select counteddate,countedtime,userid from placement where placementid=?';
  $query_prm = array($placementid);
  require('inc/doquery.php');
  if ($query_result[0]['counteddate'] != NULL) { $performedby = datefix2($query_result[0]['counteddate']) . ' ' . $query_result[0]['countedtime'] . ' par ' . $userA[$query_result[0]['userid']]; }
  else { $performedby = 'Jamais'; }
  
  $query = 'select barcode,productid,quantity,quantityrest,expiredate from pallet_counted where placementid=? order by linenr';
  $query_prm = array($placementid);
  require('inc/doquery.php');
  $main_result = $query_result;
  #$numpallets = $num_results;
  #if ($numpallets < 4) { $numpallets = $maxnumpallets; }
  echo '<h2>Comptage emplacement ' . $placementA[$placementid] . '</h2>
  <form method="post" action="warehouse.php"><table border=1 cellspacing=1 cellpadding=1>
  <tr><td colspan=10>Effectué: <i>'.$performedby.'</i></td></tr>
  <tr><td><b>Palette</td><td><b>Produit</td><td><b>Quantité';
  #if ($_SESSION['ds_useunits']) { echo ' (sous-unités)'; }
  echo '</td><td><b>Quantité (sous-unités)</td><td><b>DLV</td></tr>';
  for ($i=0;$i<$maxnumpallets;$i++)
  {
    $barcode = $main_result[$i]['barcode']; if ($barcode == NULL) { $barcode = ''; }
    $productid = $main_result[$i]['productid']; if ($productid < 1) { $productid = ''; }
    $quantity = $main_result[$i]['quantity']+0; if ($quantity < 1) { $quantity = ''; }
    $quantityrest = $main_result[$i]['quantityrest']+0; if ($quantityrest < 1) { $quantityrest = ''; }
    echo '<tr><td><input type=text STYLE="text-align:right" name="barcode'.$i.'" value="'.d_input($barcode).'" size=10></td><td>';
    $fp_counter = $i;
    #$product = $productid;
    if (isset($_POST['product' . $fp_counter])) { $product = $_POST['product' . $fp_counter]; }
    else { $product = $productid; }
    require('inc/selectproduct.php');
    if ($productid) { echo ' (' . $unittypeA[$product_unittypeidA[$productid]] . ')'; } # to show unittype
    echo '</td><td><input type=number STYLE="text-align:right" name="quantity'.$i.'" value="'.d_input($quantity).'" size=5>';
    #if ($_SESSION['ds_useunits'] && $product_npu > 1)
    #{
      echo '<td><input type=number STYLE="text-align:right" name="quantityrest'.$i.'" value="'.d_input($quantityrest).'" size=5>';
    #}
    echo '</td><td>';
    $datename = 'pallet_exp' . $i; $selecteddate = $main_result[$i]['expiredate'];
    require('inc/datepicker.php');
    echo '</td></tr>';
  }
  echo '<tr><td colspan=10 align=center>
  <input type=hidden name="warehousemenu" value="' . $warehousemenu . '"><input type=hidden name="maxnumpallets" value="' . $maxnumpallets . '">
  <input type=hidden name="saveme" value="1"><input type=hidden name="placementid" value="' . $placementid . '">
  <input type="submit" value="Valider"></td></tr>
  </table></form>';
}

if ($placementid < 1)
{
  echo '<h2>Comptage emplacement ' . $placementA[$placementid] . '</h2>
  <form method="post" action="warehouse.php"><table border=0 cellpadding=1 cellspacing=1>
  <tr><td>Emplacement:</td><td><input autofocus type="text" STYLE="text-align:right" name="placementname" size=20></td></tr>
  <tr><td>Numéro de palettes par emplacement:</td><td><input type="number" STYLE="text-align:right" name="maxnumpallets" value=4 size=5></td></tr>
  <tr><td colspan=2><input type=hidden name="warehousemenu" value="' . $warehousemenu . '">
  <input type="submit" value="Valider"></td></tr>
  </table></form>'; # hardcoded to 4
}

?>