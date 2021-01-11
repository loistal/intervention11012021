<?php

require('preload/user.php');
require('preload/product.php');
require('preload/unittype.php');
require('preload/productdepartment.php');
require('preload/productfamilygroup.php');
require('preload/productfamily.php');
require('preload/producttype.php');
require('preload/taxcode.php');

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');

$product = $_POST['product'];
$userid = $_POST['userid'];
$pfgi = $_POST['productfamilygroupid'];
$orderby = $_POST['orderby'];
require ('inc/findproduct.php');
if (!isset($productid)) { $productid = -1; }

$ourtitle = 'Historique Prix ' . datefix2($startdate) .' à '. datefix2($stopdate);
showtitle($ourtitle);
echo '<h2>' . d_output($ourtitle) . '</h2>';
if ($productid > 0) { echo '<p>Produit: ' . $productA[$productid] . '</p>'; }
if ($userid > 0) { echo '<p>Utilisateur: ' . $userA[$userid] . '</p>'; }
if ($pfgi > 0) { echo '<p>Famille: ' . $productdepartmentA[$productfamilygroup_pdidA[$pfgi]] . ' / ' . $productfamilygroupA[$pfgi] . '</p>'; }

echo '<table class=report><tr><th>Type<th>Date</th><th>Heure</th><th>Utilisateur</th>
<th>Produit</th><th>Marque</th><th>Unité de vente</th><th>Conditionnement</th>
<th>Ancien Prix</th><th>Nouveau Prix</th><th>Ecart';
if ($_SESSION['ds_useretailprice']) { echo '<th>Nouveau Prix Detail</th>'; }
echo '<th>Prix unité</th><th>EAN<th>Type<td><b>TVA';

$query = 'select log_salesprice.*,log_salesprice.unitsalesprice,eancode,producttypeid,unittypeid
from log_salesprice,product,productfamily
where logdate>=? and logdate<=?
and log_salesprice.productid=product.productid and product.productfamilyid=productfamily.productfamilyid';
$query_prm = array($startdate,$stopdate);
if ($productid > 0) { $query .= ' and log_salesprice.productid=?'; array_push($query_prm,$productid); }
if ($userid > 0) { $query .= ' and userid=?'; array_push($query_prm,$userid); }
if ($pfgi > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm,$pfgi); }
# Wing Chong drama
if ($_SESSION['ds_customname'] == 'Wing Chong' && $_SESSION['ds_userid'] == 65)
{ $query .= ' and log_salesprice_type>0'; }
if ($orderby == 2) { $query .= ' order by productid,logdate,logtime'; }
elseif ($orderby == 3) { $query .= ' order by userid,logdate,logtime'; }
else { $query .= ' order by logdate,logtime'; }
require('inc/doquery.php');

$main_result = $query_result; unset($query_result); $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  if ($unittype_dmpA[$main_result[$i]['unittypeid']] > 1)
  {
    $main_result[$i]['old_salesprice'] *= $unittype_dmpA[$main_result[$i]['unittypeid']];
    $main_result[$i]['salesprice'] *= $unittype_dmpA[$main_result[$i]['unittypeid']];
    $main_result[$i]['unitsalesprice'] *= $unittype_dmpA[$main_result[$i]['unittypeid']];
    $main_result[$i]['retailprice'] *= $unittype_dmpA[$main_result[$i]['unittypeid']];
  }
  $diff = 0;
  if ($main_result[$i]['old_salesprice'] > 0)
  {
    $diff = round((100 * $main_result[$i]['salesprice']/$main_result[$i]['old_salesprice'])-100,2).'%';
  }
  if ($diff == 0) { $diff = '&nbsp;'; }
  if ($main_result[$i]['unitsalesprice'] == 0) { $main_result[$i]['unitsalesprice'] = $main_result[$i]['salesprice']/$product_npuA[$main_result[$i]['productid']]; }
  echo d_tr(),'<td>';
  if ($main_result[$i]['log_salesprice_type'] == 1)
  {
    echo 'Prix/',$_SESSION['ds_term_clientcategory'];
    require('preload/clientcategory.php');
    if (isset($clientcategoryA[$main_result[$i]['exception_id']]))
    { echo ': '.d_output($clientcategoryA[$main_result[$i]['exception_id']]); }
  }
  elseif ($main_result[$i]['log_salesprice_type'] == 2)
  {
    echo 'Prix/',$_SESSION['ds_term_clientcategory2'];
    require('preload/clientcategory2.php');
    if (isset($clientcategory2A[$main_result[$i]['exception_id']]))
    { echo ': '.d_output($clientcategory2A[$main_result[$i]['exception_id']]); }
  }
  elseif ($main_result[$i]['log_salesprice_type'] == 3)
  {
    echo 'Prix/',$_SESSION['ds_term_clientcategory3'];
    require('preload/clientcategory3.php');
    if (isset($clientcategory3A[$main_result[$i]['exception_id']]))
    { echo ': '.d_output($clientcategory3A[$main_result[$i]['exception_id']]); }
  }
  echo '<td>' . datefix2($main_result[$i]['logdate']) . '</td>
  <td>' . $main_result[$i]['logtime'] . '</td>
  <td>' . $userA[$main_result[$i]['userid']] . '</td>
  <td>' . $productA[$main_result[$i]['productid']] . '</td><td>' . $product_brandA[$main_result[$i]['productid']] . '</td><td>' . $unittypeA[$product_unittypeidA[$main_result[$i]['productid']]] . '</td>
  <td>' . $product_packagingA[$main_result[$i]['productid']] . '</td>
  <td align=right>' . myfix($main_result[$i]['old_salesprice']);
  if ($main_result[$i]['log_salesprice_type'] > 0 && $main_result[$i]['salesprice'] == 0)
  { echo '<td align=right>Supprimé<td>'; }
  else { echo '<td align=right>' . myfix($main_result[$i]['salesprice']) . '<td align=right>' . $diff; }
  if ($_SESSION['ds_useretailprice'])
  {
    $retailprice = $main_result[$i]['retailprice']/$product_npuA[$main_result[$i]['productid']];
    echo '<td align=right>' . myfix($retailprice);
  }
  echo '<td align=right>' . myfix($main_result[$i]['unitsalesprice']) . '</td>
  <td>' . $main_result[$i]['eancode'] . '<td>' . $producttypeA[$main_result[$i]['producttypeid']];
  echo '<td>';
  if (isset($taxcodeA[$main_result[$i]['taxcodeid']])) { echo $taxcodeA[$main_result[$i]['taxcodeid']].' %'; }
}

echo '</table>';
?>