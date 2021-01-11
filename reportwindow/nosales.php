<?php

$PA['excludesupplier'] = 'int';
require('inc/readpost.php');

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$supplierid = (int) $_POST['client'];
$productdepartmentid = $_POST['productdepartmentid'];
$productfamilygroupid = $_POST['productfamilygroupid'];
$productfamilyid = $_POST['productfamilyid'];
$in_stock = (int) $_POST['in_stock'];

$title = 'Produits sans ventes';
session_write_close(); 
showtitle($title);
echo '<h2>' . $title . '</h2>';
echo '<p>De: '.datefix2($startdate).'<br>A: '.datefix2($stopdate).'</p>';

if ($supplierid > 0)
{
  $query = 'select clientname from client where issupplier=1 and clientid=?';
  $query_prm = array($supplierid);
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<p>Fournisseur: ',d_output($query_result[0]['clientname']);
    if ($excludesupplier) { echo ' (exclu)'; }
    echo '</p>';
  }
  else { $supplierid = 0; }
}
if ($productdepartmentid > 0)
{
  require('preload/productdepartment.php');
  echo '<p>Département: ',d_output($productdepartmentA[$productdepartmentid]),'</p>';
}
if ($productfamilygroupid > 0)
{
  require('preload/productfamilygroup.php');
  echo '<p>Famille: ',d_output($productfamilygroupA[$productfamilygroupid]),'</p>';
}
if ($productfamilyid > 0)
{
  require('preload/productfamily.php');
  echo '<p>Sous-famille: ',d_output($productfamilyA[$productfamilyid]),'</p>';
}

$query = 'select productid,productname,suppliercode,unittypeid,brand,netweightlabel,numberperunit from product';
if ($productfamilygroupid > 0 || $productdepartmentid > 0) { $query .= ',productfamily'; }
if ($productdepartmentid > 0) { $query .= ',productfamilygroup'; }
$query .= ' where';
if ($productfamilygroupid > 0 || $productdepartmentid > 0) { $query .= ' product.productfamilyid=productfamily.productfamilyid and'; }
if ($productdepartmentid > 0) { $query .= ' productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and'; }
$query .= ' discontinued=0 and notforsale=0';
$query_prm = array();
if ($in_stock == 1) { $query .= ' and product.currentstock<>0'; }
if ($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and supplierid<>?'; array_push($query_prm, $supplierid); }
  else { $query .= ' and supplierid=?'; array_push($query_prm, $supplierid); }
}
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
$query .= ' order by productname';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

$prodsalesA = array();
$query = 'select productid from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and accountingdate>=? and accountingdate<=? and cancelledid=0
group by productid';
$query_prm = array($startdate, $stopdate);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $prodsalesA[] = $query_result[$i]['productid'];
}

echo '<table class=report>';
for ($i=0; $i < $num_results_main; $i++)
{
  if (in_array($main_result[$i]['productid'], $prodsalesA))
  {
    # skip
  }
  else
  {
    echo '<tr><td>',d_output(d_decode($main_result[$i]['productname'])),' (',$main_result[$i]['productid'] ,')';
  }
}
echo '</table>';
?>