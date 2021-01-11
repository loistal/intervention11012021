<?php

require('preload/placement.php');

$lastyear = (substr($_SESSION['ds_curdate'],0,4)-1);
$query = 'select stock,productid from monthlystock where year=?';
$query_prm = array($lastyear);
require('inc/doquery.php');
for ($i=0;$i<$num_results;$i++)
{
  $pid = $query_result[$i]['productid'];
  $lastyearstockA[$pid] = $query_result[$i]['stock'];
}

#$query = 'select barcode,pallet_counted.productid,productname,numberperunit,netweightlabel,quantity,quantityrest,expiredate,placementid from pallet_counted,product where pallet_counted.productid=product.productid order by productid,expiredate';
$query = 'select barcode,pallet_counted.productid,productname,numberperunit,netweightlabel,quantity,quantityrest,expiredate,placementid,productdepartmentname,productfamilygroupname,productfamilyname
from pallet_counted,product,productfamily pf,productfamilygroup pfg,productdepartment pd
where pallet_counted.productid=product.productid and product.productfamilyid=pf.productfamilyid and pf.productfamilygroupid=pfg.productfamilygroupid and pfg.productdepartmentid=pd.productdepartmentid
order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname,productid,expiredate';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
echo '<h2>Rapport Inventaire par Produit</h2>
<table class=report><tr><td colspan=2><b>Produit</td><td><b>Emplacement</td><td><b>DLV</td><td><b>Quantité (carton)</td><td><b>31/12</td><td><b>Ecart</td></tr>';
$lastproductid = -1; $subtotal = 0; $lastnpu = 1; $subtotalunits = 0; $lastpfn = 'error';
for ($i=0;$i<$num_results_main;$i++)
{
  #
  $foundproductsA[$i] = $main_result[$i]['productid'];
  $foundplacementA[$i] = $main_result[$i]['placementid'];
  #
  if ($main_result[$i]['productfamilyname'] != $lastpfn)
  {
    echo '<tr><td colspan=20><b>' . $main_result[$i]['productdepartmentname'] .'/' . $main_result[$i]['productfamilygroupname'] .'/'. $main_result[$i]['productfamilyname'];
  }
  $lastpfn = $main_result[$i]['productfamilyname'];
  echo '<tr><td align=right>' . $main_result[$i]['productid'] . '</td><td>' . $main_result[$i]['productname'];
  if ($_SESSION['ds_useunits'] && $main_result[$i]['numberperunit'] > 1) { echo $main_result[$i]['numberperunit'] . ' x '; }
  echo $main_result[$i]['netweightlabel'] . '</td><td align=right>' . $placementA[$main_result[$i]['placementid']] . '</td><td align=right>' . datefix2($main_result[$i]['expiredate']) . '</td>
  <td align=right>' . $main_result[$i]['quantity'];
  if ($main_result[$i]['quantityrest'] > 0)
  {
    echo '<font size=-2>' . $main_result[$i]['quantityrest'] . '</font>';
    $subtotalunits = $subtotalunits + $main_result[$i]['quantityrest'];
  }
  echo '</td><td><td></tr>';
  $lastproductid = $main_result[$i]['productid'];
  $lastnpu = $main_result[$i]['numberperunit']; if ($lastnpu < 1) { $lastnpu = 1; }
  $subtotal = $subtotal + $main_result[$i]['quantity'];
  if ($main_result[$i]['productid'] != $main_result[$i+1]['productid'])
  {
    if ($subtotalunits > 0)
    {
      $subtotal = $subtotal + floor($subtotalunits/$lastnpu);
      $showsubtotal = $subtotal . '<font size=-2>' . $subtotalunits%$lastnpu . '</font>';
    }
    else
    {
      $showsubtotal = $subtotal;
    }
    $currentstockrest = $lastyearstockA[$main_result[$i]['productid']] % $lastnpu;
    $currentstock = floor($lastyearstockA[$main_result[$i]['productid']] / $lastnpu);
    echo '<tr><td colspan=4>&nbsp;</td><td align=right><b>' . $showsubtotal . '</td><td align=right>' . $currentstock;
    if ($currentstockrest > 0) { echo ' <font size=-2>' . $currentstockrest . '</font>'; }
    $diff = ($subtotal * $lastnpu) + $subtotalunits - ($currentstock * $lastnpu) - $currentstockrest;
    ###
    /*
    if ($_SESSION['ds_userid'] == 1)
    {
      $kladd = ($subtotal * $lastnpu) + $subtotalunits;
      ##
      $query = 'select endofyearstockid from endofyearstock where productid=? and year=2014';
      $query_prm = array($query_result[$i]['productid']);
      require('inc/doquery.php');
      if ($num_results) { $query = 'update endofyearstock set stock=? where productid=? and year=?'; }
      else { $query = 'insert into endofyearstock (stock,productid,year) values (?,?,?)'; }
      $query_prm = array($kladd,$main_result[$i]['productid'],2014);
      require('inc/doquery.php');
      echo $query; var_dump($query_prm);
      ##
    }
    */
    ###
    $diffunits = $diff % $lastnpu;
    $diff = floor($diff / $lastnpu);
    echo '</td><td align=right>' . $diff;
    if ($diffunits > 0) { echo ' <font size=-2>' . $diffunits . '</font>'; }
    $subtotal = 0; $subtotalunits = 0;
  }
}
echo '</table>';

$foundproductsA = array_filter(array_unique($foundproductsA));
sort($foundproductsA);
$foundproducts = '(';
foreach ($foundproductsA as $kladd)
{
  $foundproducts .= $kladd . ',';
}
$foundproducts = rtrim($foundproducts,',') . ')';
if ($foundproducts == '()') { $foundproducts = '(-1)'; }
$query = 'select monthlystock.productid,stock,productname,numberperunit as npu from monthlystock,product 
where monthlystock.productid=product.productid and year=2014 and month=12 and discontinued=0 and stock>0
and monthlystock.productid not in '.$foundproducts;
$query .= ' order by productid';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<br><table class=report><tr><th>Produits non-presents avec stock 12/2014 (et non discontinués)<th>Stock 31/12';
  for ($i=0;$i<$num_results;$i++)
  {
    $stock = floor($query_result[$i]['stock'] / $query_result[$i]['npu']);
    $stockrest = $query_result[$i]['stock'] % $query_result[$i]['npu'];
    echo '<tr><td>' . $query_result[$i]['productid'] . ': ' . d_output(d_decode($query_result[$i]['productname'])) . '<td align=right>' . $stock;
    if ($stockrest > 0) { echo ' <font size=-2>' . $stockrest . '</font>'; }
  }
  echo '</table>';
}

$foundplacementA = array_filter(array_unique($foundplacementA));
sort($foundplacementA);
$foundplacements = '(';
foreach ($foundplacementA as $kladd)
{
  $foundplacements .= $kladd . ',';
}
$foundplacements = rtrim($foundplacements,',') . ')';
if ($foundplacements == '()') { $foundplacements = '(-1)'; }
$query = 'select placementname from placement 
where placementid not in '.$foundplacements;
$query .= ' order by placementrank,placementname';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<br><table class=report><tr><th>Emplacements vides';
  for ($i=0;$i<$num_results;$i++)
  {
    echo '<tr><td>' . d_output($query_result[$i]['placementname']);
  }
  echo '</table>';
}

?>