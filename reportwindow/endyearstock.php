<?php

require('preload/taxcode.php');

$currentyear = $_POST['currentyear'];
$ds_useproductcode = $_SESSION['ds_useproductcode'];
$ds_useunits = $_SESSION['ds_useunits'];
session_write_close();

$title = d_trad('endofyearreport:',$currentyear);
showtitle($title);
echo '<h2>' . $title . '</h2><br>';
#$dp_donotupdate = 1; # input for calcstock

echo '<table class="report"><thead>';
if($ds_useproductcode){echo '<th>' . d_trad('productcode') . '</th>';}
else { echo '<th>' . d_trad('productnumber') . '</th>';}
echo '<th>' . d_trad('product') . '</th><th>' . d_trad('productfamily') . '</th><th>' . d_trad('inventorystock') . '</th>';
echo '<th>' . d_trad('pricevat') . '</th><th>' . d_trad('costpricebyunit') . '</th></thead>';

$query = 'select productid,stock from endofyearstock where year=?';
$query_prm = array($currentyear);
require('inc/doquery.php');
unset($endyearstockA);$endyearstockA = array();
$productidin = '';
if($num_results > 0)
{ 
  for ($i=0;$i<$num_results;$i++)
  {
    if ($i==0){$productidin = '(';}
    $endyearstockA[$query_result[$i]['productid']] = $query_result[$i]['stock'];
    $productidin .= (int) $query_result[$i]['productid'];
    if($i < ($num_results -1)){$productidin .= ',';}
    else {$productidin .= ')';}
  }
  $query = 'select pr.salesprice,pr.taxcodeid,pr.productid,pr.productname,pr.netweightlabel,pr.numberperunit,pr.suppliercode,pf.productfamilyname,pg.productfamilygroupname,pd.productdepartmentname from product pr,productfamily pf,productfamilygroup pg,productdepartment pd';
  $query .= ' where pr.productfamilyid=pf.productfamilyid and pf.productfamilygroupid=pg.productfamilygroupid and pg.productdepartmentid=pd.productdepartmentid and pr.discontinued=0';
  $query .= ' and pr.productid in ' . $productidin;
  if ($ds_useproductcode == 1) { $query = $query . ' order by pr.suppliercode'; }
  else { $query = $query . ' order by pr.productid'; }
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0;$i<$num_results_main;$i++)
  {
    $row = $main_result[$i];
    $productid = $row['productid']; $showproductid = $productid; if ($ds_useproductcode == 1) { $showproductid = $row['suppliercode']; }
    $productname = d_decode($row['productname']) . ' ';
    if ($ds_useunits && $row['numberperunit'] > 1) { $productname = $productname . $row['numberperunit'] . ' x '; }
    $productname = $productname . $row['netweightlabel'];
    $numberperunit = $row['numberperunit']; if ($numberperunit == 0) { $numberperunit = 1; }
    /*if (!isset($endyearstockA[$productid]))
    {*/
      /* 2014 01 09 complaint from AFEQ, stock at zero if not counted
      $stock = 'not found';
      #$productid $currentyear $numberperunit $dp_donotupdate
      require('inc/calcstock.php');
      $query = 'insert into endofyearstock (productid,year,stock) values (?,?,?)';
      $query_prm = array($productid,$currentyear,$stock);
      require('inc/doquery.php');
      $stock = $currentstock;
      */
      /*$stock = '';
    }
    else
    {*/
      $stock = round($endyearstockA[$productid] / $numberperunit);
    /*}*/
    
    $query = 'select cost from purchasebatch where productid=? and year(arrivaldate)<=? order by arrivaldate desc limit 1'; # prev,
    $query_prm = array($productid,$currentyear);
    require('inc/doquery.php');
    #$cost = myround($query_result[0]['cost'] * $numberperunit); # old
    if ($num_results)
    { # see products/salesprice.php
      $prev = $query_result[0]['prev']; # * $dmp TODO
      if ($prev == 0) { $prev = $query_result[0]['cost']*$numberperunit; } # backwards compat
      #$prev = $query_result[0]['cost']*$numberperunit; #for dauphin-saas
    }
    else
    {
      $prev = 0;
    }
    $temp = ($main_result[$i]['salesprice'] + ($main_result[$i]['salesprice'] * ($taxcodeA[$main_result[$i]['taxcodeid']]/100)));
    $prixttc = myround(($main_result[$i]['salesprice'] + ($main_result[$i]['salesprice'] * ($taxcodeA[$main_result[$i]['taxcodeid']]/100))),0);
    echo d_tr() . '<td>' . d_output($showproductid) . '</td><td>' . d_output($productname) . '</td><td>' . $row['productdepartmentname'] . '/' . $row['productfamilygroupname'] . '/' . $row['productfamilyname'] . '</td>';
    echo '<td>' . $stock . '</td><td>' . $prixttc . '</td><td>' . $prev . '</td></tr>';

  }
  echo '</table>';
}
else
{
  echo '<p>' . d_trad('noresult') . '</p>';
}
?>