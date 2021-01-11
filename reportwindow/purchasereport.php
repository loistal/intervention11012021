<style>
#comment {
  width: auto;
}
</style>
<?php

if(!isset($productfamilyA)){require('preload/productfamily.php');}
if(!isset($productfamilygroupA)){require('preload/productfamilygroup.php');}
if(!isset($productdepartmentA)){require('preload/productdepartment.php');}
if(!isset($productdepartmentA)){require 'preload/productdepartment.php';}
if(!isset($productfamilygroupA)){require 'preload/productfamilygroup.php';}  
if(!isset($productfamilyA)){require 'preload/productfamily.php';} 

$product = $_POST['product'];
if ($product == "") { $productid = ""; }
else
{
  if (!isset($product)) { $product = $_GET['product']; }
  require ('inc/findproduct.php');
}
$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$userid = $_POST['userid'];
$warehouseid = $_POST['warehouseid'];
$orderby = $_POST['orderby'];
$productdepartmentid = $_POST['productdepartmentid'];
$productfamilygroupid = $_POST['productfamilygroupid'];
$productfamilyid = $_POST['productfamilyid'];
$num_results=0;$product = $_POST['product'];require('inc/findproduct.php');$productnum_results=$num_results;
$brand = $_POST['brand'];
$ds_useemplacement = $_SESSION['ds_useemplacement'];
$ds_useproductcode = $_SESSION['ds_useproductcode'];
$ds_usedlv = $_SESSION['ds_usedlv'];
$ds_useunits = $_SESSION['ds_useunits'];
session_write_close();

$ORDER_BY_DATE = 1;
$ORDER_BY_PRODUCT_NUMBER = 2;
$ORDER_BY_PRODUCT_CODE = 3;
$ORDER_BY_PRODUCT_FAMILY = 4;
$ORDER_BY_BRAND = 5;
#Title
$title = d_trad('purchasereport:');
showtitle($title);
echo '<h2>' . $title . '</h2>';
$ourparams = '<br>';
 
if ($productdepartmentid >= 0 && $productfamilygroupid <= 0 && $productfamilyid <= 0) { $ourparams .= '<p>' . d_trad('department') . ': ' . d_output($productdepartmentA[$productdepartmentid]) . '</p>'; }
if ($productfamilygroupid >= 0 && $productfamilyid <= 0) { $ourparams .= '<p>' . d_trad('family') . ': ' . d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamilygroupid]] . '/' . $productfamilygroupA[$productfamilygroupid]) . '</p>'; }
if ($productfamilyid >= 0) { $ourparams .= '<p>' . d_trad('subfamily') . ': ' . d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$productfamilyid]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$productfamilyid]] . ' / ' . $productfamilyA[$productfamilyid]) . '</p>';}
if ($startdate  >= 0 && $stopdate >=0) { $ourparams .= '<p>' . d_trad('between',array(datefix2($startdate),datefix2($stopdate))).'</p>'; }
if ($brand != ""){ $ourparams .= '<p>' . d_trad('brand') . ': ' . d_output($brand) . '</p>';}

echo $ourparams . '<br>';

echo '<table class="report">';

#SELECT
$query = 'select displaymultiplier,purchasebatchgroupid,pr.brand,pu.origamount,pu.vat,pu.totalcost,pu.purchasebatchid,pu.productid,pr.suppliercode,pr.productname,pr.numberperunit,pr.netweightlabel,pu.arrivaldate,pu.useby,pu.description,pu.amount,us.initials,ut.unittypename';
if ($ds_useemplacement) { $query = $query . ',wa.warehousename,pl.placementname'; }

#FROM
$query = $query . ' from purchasebatch pu,product pr,usertable us,unittype ut';
if ($ds_useemplacement) { $query = $query . ',placement pl,warehouse wa'; }
if($orderby == $ORDER_BY_PRODUCT_FAMILY || $productfamilyid > 0 || $productfamilygroupid > 0 || $productdepartmentid > 0)
{
  $query .= ',productfamily pf, productdepartment pd, productfamilygroup pg';
}

#WHERE
$query = $query . ' where pu.arrivaldate>=? and pu.arrivaldate<=? and pu.productid=pr.productid and pu.userid=us.userid and pr.unittypeid=ut.unittypeid';#purchasebatchgroupid>0 and 
$query_prm = array($startdate,$stopdate);
if ($ds_useemplacement) { $query .= ' and pu.placementid=pl.placementid and pl.warehouseid=wa.warehouseid'; }
if ($userid != -1) 
{ 
  $query .= ' and pu.userid=?'; 
  array_push($query_prm,$userid);
}
if ($productid > 0) 
{ 
  $query .= ' and pu.productid=?';
  array_push($query_prm,$productid);
}
if ($brand != "") 
{ 
  $query .= ' and pr.brand LIKE ?'; 
  array_push($query_prm,'%' .  mb_strtolower($brand) . '%');
}
if ($ds_useemplacement && $warehouseid != -1) 
{ 
  $query .= ' and pl.warehouseid=?';
  array_push($query_prm,$warehouseid); 
}
if ($product > 0)
{ 
  $query .= ' and pr.productid=?'; 
  array_push($query_prm, $product); 
}
elseif ($productnum_results > 0)
{ 
  $query .= ' and lower(pr.productname) LIKE ?'; 
  array_push($query_prm, '%' .  mb_strtolower(d_encode($_POST['product'])) . '%' ); 
}
if($orderby == $ORDER_BY_PRODUCT_FAMILY || $productfamilyid > 0 || $productfamilygroupid > 0 || $productdepartmentid > 0)
{
  $query .= ' and pr.productfamilyid= pf.productfamilyid and pf.productfamilygroupid = pg.productfamilygroupid and pg.productdepartmentid = pd.productdepartmentid ';
  if($productfamilyid > 0)
  {
    $query .= ' and pf.productfamilyid=?';
    array_push($query_prm, $productfamilyid);
  }
}

#ORDER BY
switch ($orderby)
{
  case $ORDER_BY_DATE:
    $query .= ' order by pu.arrivaldate,pu.purchasebatchid';
    break;
  case $ORDER_BY_PRODUCT_NUMBER:
    $query .= ' order by pr.productid,pu.arrivaldate,pu.purchasebatchid'; 
    break;
  case $ORDER_BY_PRODUCT_CODE:
    $query .= ' order by pr.suppliercode,pu.arrivaldate,pu.purchasebatchid'; 
    break;
  case $ORDER_BY_PRODUCT_FAMILY:
    $query .= ' order by pr.productfamilyid,pf.productfamilygroupid,pg.productdepartmentid,pr.productid,pu.arrivaldate,pu.purchasebatchid';
    break;    
  case $ORDER_BY_BRAND:
    $query .= ' order by pr.brand,pr.productid,pu.arrivaldate,pu.purchasebatchid';
    break;
}
require('inc/doquery.php');

if($num_results > 0)
{
  #REPORT TABLE
  echo d_tr();
  echo '<thead><th>Numéro<th>' . d_trad('batch') . '</th>';
  echo  '<th>' . d_trad('product') . '</th><th>' . d_trad('brand') . '</th><th>' . d_trad('quantity') . '</th><th>' . d_trad('user') . '</th><th>' . d_trad('arrivaldate') . '</th><th>' . d_trad('costprice') . '</th>';
  if ($ds_usedlv) { echo '<th>' . d_trad('SBD') . '</th>'; }
  if ($ds_useemplacement) { echo '<th>' . d_trad('place') . '</th>'; }
  echo '<th id="comment">' . d_trad('comment') . '</th>';
  echo '</thead>';
  for ($y=0; $y < $num_results; $y++)
  {
    $row2 = $query_result[$y];
    if ($ds_useproductcode == 1) { $productname = $row2['suppliercode']; }
    else { $productname = $row2['productid']; }
    $productname = $productname . ': ' . $row2['productname'] . ' ';
    if ($ds_useunits && $row2['numberperunit'] > 1) { $productname = $productname . $row2['numberperunit'] . ' x '; }
    $productname = $productname . $row2['netweightlabel'];
    $amount = floor(($row2['amount'] / $row2['numberperunit']) / $row2['displaymultiplier']) . ' ' . $row2['unittypename'];
    if ($row2['amount'] % $row2['numberperunit'] != 0) { $amount = $amount . ' <font size=-1>' . $row2['amount'] % $row2['numberperunit'] . '</font>'; }
    echo d_tr();
    echo '<tr><td>', $row2['purchasebatchgroupid'];
    echo '<td>' . $row2['purchasebatchid'] . '</td><td>' . $productname . '</td><td>' . $row2['brand'] . '</td><td>' . $amount . '</td><td>' . $row2['initials'] . '</td><td align=right>' . datefix2($row2['arrivaldate']) . '</td>';
    $origamount = $row2['origamount']; if ($origamount == 0) { $origamount = 1; }
    $prev = (($row2['totalcost']-$row2['vat'])*$row2['numberperunit'])/$origamount;
    echo '<td align=right>' . myfix($prev) . '</td>';
    if ($ds_usedlv) { echo '<td align=right>' . datefix2($row2['useby']) . '</td>'; }
    if ($ds_useemplacement) { echo '<td>' . $row2['placementname'] . ' (' . $row2['warehousename'] . ')</td>'; }
    echo '<td>' . $row2['description'] . '</td>';
    echo '</tr>';
  }
  echo '</table>';
}
else
{
  echo '<p>' . d_trad('noresult') . '</p>';
}
?>