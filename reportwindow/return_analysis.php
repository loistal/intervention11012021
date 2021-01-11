<?php

require('preload/returnreason.php');

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['product'] = 'product';
$PA['productdepartmentid'] = 'int';
$PA['productfamilygroupid'] = 'int';
$PA['productfamilyid'] = 'int';
$PA['excludesupplier'] = 'uint';
$PA['client'] = 'supplier';
$PA['by'] = 'uint';
require('inc/readpost.php');

session_write_close();

$total = 0;

$title = 'Analyse des avoir '.datefix($startdate,'short').' à '.datefix($stopdate,'short');
showtitle_new($title);

require('inc/showparams.php');

echo d_table('report');
echo '<thead><th>';
if ($by == 1) { echo 'Produit'; require('preload/product.php'); }
else { echo 'Raison d\'avoir'; }
echo '<th>Valeur<th>% des ventes</thead>';

$query = 'select invoiceitemhistory.productid,productname,numberperunit,unittypeid,netweightlabel,suppliercode,salesprice,taxcodeid,
          departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,
          invoicehistory.invoiceid,sum(invoiceprice-invoicevat) as value,returnreasonid
          from product,productfamily,productfamilygroup,productdepartment,invoicehistory,invoiceitemhistory
          where product.productfamilyid=productfamily.productfamilyid
          and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
          and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid
          and invoiceitemhistory.invoiceid=invoicehistory.invoiceid
          and invoiceitemhistory.productid=product.productid
          and isreturn=0 and cancelledid=0 and confirmed=1
          and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate, $stopdate);
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($productid > 0) { $query .= ' and invoiceitemhistory.productid=?'; array_push($query_prm, $productid); }
if ($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and product.supplierid<>?'; array_push($query_prm, $supplierid); }
  else { $query .= ' and product.supplierid=?'; array_push($query_prm, $supplierid); }
}
if ($by == 1) { $query .= ' group by invoiceitemhistory.productid'; }
else { $query .= ' group by returnreasonid'; }
$query .= ' order by value desc';
require ('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if ($by == 1) { $id = $query_result[$i]['productid']; }
  else { $id = $query_result[$i]['returnreasonid']; }
  $dividerA[$id] = $query_result[$i]['value'];
}

$query = 'select invoiceitemhistory.productid,productname,numberperunit,unittypeid,netweightlabel,suppliercode,salesprice,taxcodeid,
          departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,
          invoicehistory.invoiceid,sum(invoiceprice-invoicevat) as value,returnreasonid
          from product,productfamily,productfamilygroup,productdepartment,invoicehistory,invoiceitemhistory
          where product.productfamilyid=productfamily.productfamilyid
          and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
          and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid
          and invoiceitemhistory.invoiceid=invoicehistory.invoiceid
          and invoiceitemhistory.productid=product.productid
          and isreturn=1 and cancelledid=0 and confirmed=1
          and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate, $stopdate);
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($productid > 0) { $query .= ' and invoiceitemhistory.productid=?'; array_push($query_prm, $productid); }
if ($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and product.supplierid<>?'; array_push($query_prm, $supplierid); }
  else { $query .= ' and product.supplierid=?'; array_push($query_prm, $supplierid); }
}
if ($by == 1) { $query .= ' group by invoiceitemhistory.productid'; }
else { $query .= ' group by returnreasonid'; }
$query .= ' order by value desc';
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $total += $query_result[$i]['value'];
  echo d_tr();
  $kladd = '';
  if ($by == 1) { $kladd = $productA[$query_result[$i]['productid']].' '.$product_packagingA[$query_result[$i]['productid']]  ; } # TODO full product name from preload
  elseif (isset($returnreasonA[$query_result[$i]['returnreasonid']]))
  { $kladd = $returnreasonA[$query_result[$i]['returnreasonid']]; }
  echo d_td($kladd);
  echo d_td($query_result[$i]['value'], 'currency');
  if ($by == 1) { $id = $query_result[$i]['productid']; }
  else { $id = $query_result[$i]['returnreasonid']; }
  if (!isset($dividerA[$id]) || $dividerA[$id] == 0) { echo d_td(); }
  else
  {
    $kladd = 100 * $query_result[$i]['value'] / $dividerA[$id];
    echo d_td($kladd,'percent');
  }
}

echo d_tr(1);
echo d_td('Total');
echo d_td($total,'currency');
echo d_td();
echo d_table_end();

?>