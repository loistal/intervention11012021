<?php

$a5size = 70; # 100 is A4

$PA['orig'] = 'uint';
$PA['format'] = '';
require('inc/readpost.php');
if ($format == '') { $format = 'A4'; }

if (isset($_POST['startbarcode']))
{
  $startbarcode = $_POST['startbarcode'];
  $stopbarcode = $_POST['stopbarcode'];
}
elseif (isset($_GET['startbarcode']))
{
  $startbarcode = $_GET['startbarcode'];
  $stopbarcode = $_GET['stopbarcode'];  
}
else { exit; }

if ($format == 'A5')
{
  $font_base = 50;
  $width = 300;
  $height = 150;
  if ($a5size > 0 && $a5size < 100)
  {
    $font_base = $a5size;
    $width = (int) 300 * $a5size/50;
    $height = (int) 150 * $a5size/50;
  }
}
else { $font_base = 100; $width = 600; $height = 300; }

showtitle('Edition Code Barres');

# get pallet_barcodeid from barcode
$query = 'select pallet_barcodeid from pallet_barcode where barcode=?';
$query_prm = array($startbarcode);
require('inc/doquery.php');
$startid = 0;
if ($num_results > 0)
{
  $startid = $query_result[0]['pallet_barcodeid'];
}

$query = 'select pallet_barcodeid from pallet_barcode where barcode=?';
$query_prm = array($stopbarcode);
require('inc/doquery.php');
$stopid = 0;
if ($num_results > 0)
{
  $stopid = $query_result[0]['pallet_barcodeid'];
}

# this query assumes ids are continuous with barcodes. while this holds true for Wing Chong it is not necessairily the case !
$query = 'select userid,barcode,pallet_barcodeid from pallet_barcode where pallet_barcodeid>=? and pallet_barcodeid<=? limit 100'; 
$query_prm = array($startid,$stopid);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '
<style>
.barcode_title_page_break
{
  display:block; 
  page-break-after: always;
}
span.note {font-size:'.(4*$font_base).'%;font-weight:bold;color:black;}
@page { size: '.$format.'; margin: 0cm;}
span.note2 {font-size:'.(3*$font_base).'%;}
span.note4 {font-size:'.(2*$font_base).'%;}
hr {
    display: block;
    height: 1px;
    background: transparent;
    width: 100%;
    border: none;
    border-top: solid 1px #aaa;
}
</style>
';
#span.note3 {font-size:150%;}

for ($i=0; $i < $num_results_main; $i++)
{
  if ($i > 0) { echo '<p style="page-break-before: always">'; }
  $barcode = '';
  $suppliercode = '';
  $showproductname = '';
  $quantity = '';
  $expiredate = '';
  $supplierbatchname = '';
  
  $pallet_barcodeid = $main_result[$i]['pallet_barcodeid']; 
  $query = 'select pallet.pallet_barcodeid,pallet.supplierbatchname,pallet.expiredate,pallet.productid,pallet.quantity,pallet.orig_quantity,product.productid,
            product.productname,product.suppliercode,product.unittypeid,product.netweightlabel,product.numberperunit
            from  pallet, product
            where pallet.pallet_barcodeid = ?
            and product.productid = pallet.productid ';
  $query_prm = array($pallet_barcodeid);
  require('inc/doquery.php');
  if ($num_results)
  { 
    $suppliercode = $query_result[0]['suppliercode'];
    $productid = $query_result[0]['productid'];
    $showproductname = d_decode($query_result[0]['productname']);
    if ($query_result[0]['numberperunit'] > 1) { $showproductname .= $query_result[0]['numberperunit'] . ' x '; }
    $showproductname .= ' ' . $query_result[0]['netweightlabel'];
    $quantity = ($query_result[0]['quantity']/$query_result[0]['numberperunit'])+0;
    $orig_quantity = ($query_result[0]['orig_quantity']/$query_result[0]['numberperunit'])+0;
    $expiredate = $query_result[0]['expiredate'];
    $expiredate = datefix($expiredate, 'short');
    $supplierbatchname = $query_result[0]['supplierbatchname'];
  }
  
  $barcode = $main_result[$i]['barcode'];
  echo '<img src="barcode.php?size=40&text=' . $barcode . '" width='.$width.'; height='.$height.'>';
  echo '<p align="center"><span class="note">' .$main_result[$i]['barcode'] .'</span><hr>';
  
  echo '<p><span class="note">CODE: <span class="note2">' ,$productid .'</span></span>';

  echo '<p><span class="note4">' .$showproductname.'</span><hr>';
 
  echo '<p><span class="note">QTE: &nbsp; <span class="note2">';
  if ($orig) { $showquantity = $orig_quantity; }
  else { $showquantity = $quantity; }
  if ($showquantity == 0) { echo 'VIDE'; }
  else { echo myfix($quantity); }
  echo '</span></span><hr>';
  
  echo '<p><span class="note">DLV: <span class="note3">' . $expiredate .'</span></span>';
  
  echo '<br><br><br><p><span class="note">Batch: <span class="note3">' . $supplierbatchname .'</span></span>';
  
  echo '<p align=center><span class="barcode_title_page_break"></span>';
}

?>