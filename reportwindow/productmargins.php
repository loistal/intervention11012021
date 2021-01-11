<?php

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['product'] = 'product';
$PA['productdepartmentid'] = 'int';
$PA['productfamilygroupid'] = 'int';
$PA['productfamilyid'] = 'int';
$PA['excludesupplier'] = 'uint';
$PA['costprice'] = 'uint';
$PA['type'] = 'uint';
$PA['client'] = 'supplier';
require('inc/readpost.php');

$title = 'Marges Brutes des Produits ' . datefix2($startdate) . ' à ' . datefix2($stopdate);
showtitle_new($title);

require('inc/showparams.php');
if ($costprice == 1) { echo '<p>Prix de revient: Calculé sur la periode</p>'; }
else { echo '<p>Prix de revient: Dernier</p>'; }

echo d_table('report');
echo '<thead><th colspan=2>Produit<th>Prix Revient<th>Quantité vendu<th>Revenue<th>Marge brut (%)</thead>';

$query = 'select productid,productname,numberperunit,unittypeid,netweightlabel,suppliercode,
          departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname
          from product';
$query .= ',productfamily,productfamilygroup,productdepartment';
$query .= ' where discontinued=0';
$query .= ' and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
            and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid';
$query_prm = array();
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($productid > 0) { $query .= ' and productid=?'; array_push($query_prm, $productid); }
if ($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and product.supplierid<>?'; array_push($query_prm, $supplierid); }
  else { $query .= ' and product.supplierid=?'; array_push($query_prm, $supplierid); }
}
$query .= ' order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname';
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $prev_error = 0;
  echo d_tr();
  if ($_SESSION['ds_useproductcode']) { echo d_td($main_result[$i]['suppliercode']); }
  else { echo d_td($main_result[$i]['productid'], 'int'); }
  echo d_td(d_decode($main_result[$i]['productname']));
  
  if ($costprice == 1)
  {
    $query = 'select prev,amount from purchasebatch where productid=? and prev>0 and amount>0 and arrivaldate>=? and arrivaldate<=?';
    $query_prm = array($main_result[$i]['productid'], $startdate, $stopdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      $t_amount = 0; $t_prev = 0;
      for ($y=0; $y < $num_results_main; $y++)
      {
        $t_amount += $query_result[0]['amount'];
        $t_prev += $query_result[0]['prev'] * $query_result[0]['amount'];
      }
      $prev = $t_prev / $t_amount;
    }
    else { $prev_error = 1; }
  }
  else
  {
    $query = 'select prev from purchasebatch where productid=? and prev>0 order by arrivaldate desc limit 1';
    $query_prm = array($main_result[$i]['productid']);
    require('inc/doquery.php');
    if ($num_results) { $prev = $query_result[0]['prev']; }
    else { $prev_error = 1; }
  }
  
  if (!$prev_error) 
  {
    $quantity = 0; $sales = 0;
    $query = 'select sum(quantity) as quantity,sum(lineprice) as sales,isreturn
              from invoiceitemhistory,invoicehistory
              where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
              and productid=? and accountingdate>=? and accountingdate<=?
              and cancelledid=0 and confirmed=1 and isreturn=0';
    $query_prm = array($main_result[$i]['productid'], $startdate, $stopdate);
    require('inc/doquery.php');
    for ($y=0; $y < $num_results; $y++)
    {
      $sales += $query_result[$y]['sales'];
      $quantity += $query_result[$y]['quantity'];
    }
    $query = 'select sum(quantity) as quantity,sum(lineprice) as sales,isreturn
              from invoiceitemhistory,invoicehistory
              where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
              and productid=? and accountingdate>=? and accountingdate<=?
              and cancelledid=0 and confirmed=1 and isreturn=1';
    $query_prm = array($main_result[$i]['productid'], $startdate, $stopdate);
    require('inc/doquery.php');
    for ($y=0; $y < $num_results; $y++)
    {
      $sales -= $query_result[$y]['sales'];
      $quantity -= $query_result[$y]['quantity'];
    }
    if ($quantity != 0)
    {
      if ($type == 1)
      {
        $query = 'select sum(netchange) as modify from modifiedstock where productid=? and changedate>=? and changedate<=?';
        $query_prm = array($main_result[$i]['productid'], $startdate, $stopdate);
        require('inc/doquery.php');
        if ($num_results) { $sales += $query_result[0]['modify'] * $prev; }
      }
      $quantity = $quantity / $main_result[$i]['numberperunit'];
      $margin = round((($sales / ($quantity * $prev)) - 1) * 100, 2);
      
      echo d_td($prev,'decimal');
      echo d_td($quantity,'decimal');
      echo d_td($sales,'decimal');
      echo d_td($margin,'decimal');
    }
    else { $prev_error = 2; }
  }
  if ($prev_error == 1) { echo d_td('Ne peut calculer le prix de revient','',10); }
  elseif ($prev_error == 2) { echo d_td('Pas de ventes sur la periode','',10); }
}

echo d_table_end();

?>