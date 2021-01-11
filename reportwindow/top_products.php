<?php

#### for PHP ver 5
if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}
####

require('preload/employee.php');
require('preload/product.php');

$PA['productdepartmentid'] = 'int';
$PA['productfamilygroupid'] = 'int';
$PA['productfamilyid'] = 'int';
$PA['brand'] = '';
$PA['userid'] = 'int';
$PA['supplierid'] = 'int';
$PA['choice'] = 'int';
$PA['employeeid'] = 'int';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['startdate2'] = 'date';
$PA['stopdate2'] = 'date';
require('inc/readpost.php');

if ($startdate2 != '' && $stopdate2 != '') { $compare = 1; }
else { $compare = 0; }

showtitle_new('Meilleurs Produits');
if ($startdate != '' || $stopdate != '')
{
  echo '<p>Depuis ';
  if ($startdate == '') { echo 'le début'; }
  else { echo 'le ' . datefix($startdate,'short'); }
  echo ' jusqu\'au ';
  if ($stopdate == '') { echo ' ---'; }
  else { echo datefix($stopdate,'short'); }
  echo '.</p>';
}
if ($startdate2 != '' || $stopdate2 != '')
{
  echo '<p>Comparé avec ';
  if ($startdate2 == '') { echo 'le début'; }
  else { echo 'le ' . datefix($startdate2,'short'); }
  echo ' jusqu\'au ';
  if ($stopdate2 == '') { echo ' ---'; }
  else { echo datefix($stopdate2,'short'); }
  echo '.</p>';
}
if ($supplierid > 0)
{
  $query = 'select clientname as suppliername from client where clientid=?';
  $query_prm = array($supplierid);
  require('inc/doquery.php');
  $suppliername = $query_result[0]['suppliername'];
}
require('inc/showparams.php');

$query = 'select sum(lineprice-linevat) as sum,invoiceitemhistory.productid';
$query .= ' from invoicehistory,invoiceitemhistory';
if ($productfamilyid > 0 || $productfamilygroupid > 0 || $productdepartmentid > 0 || $supplierid > 0 || $brand != '')
{ $query .= ',product'; }
if ($productfamilygroupid > 0 || $productdepartmentid > 0) { $query .= ',productfamily'; }
if ($productdepartmentid > 0) { $query .= ',productfamilygroup'; }
$query .= ' where invoicehistory.invoiceid=invoiceitemhistory.invoiceid';
if ($productfamilyid > 0 || $productfamilygroupid > 0 || $productdepartmentid > 0 || $supplierid > 0 || $brand != '')
{ $query .= ' and invoiceitemhistory.productid=product.productid'; }
if ($productfamilygroupid > 0 || $productdepartmentid > 0) { $query .= ' and product.productfamilyid=productfamily.productfamilyid'; }
if ($productdepartmentid > 0) { $query .= ' and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid'; }
$query_prm = array();
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm,$productfamilyid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm,$productfamilygroupid); }
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm,$productdepartmentid); }
if ($employeeid >= 0) { $query .= ' and invoicehistory.employeeid=?'; array_push($query_prm,$employeeid); }
if ($userid >= 0) { $query .= ' and invoicehistory.userid=?'; array_push($query_prm,$userid); }
if ($supplierid >= 0) { $query .= ' and product.supplierid=?'; array_push($query_prm,$supplierid); }
if ($brand != '') { $query .= ' and product.brand like ?'; array_push($query_prm,'%'.$brand.'%'); }
$query_prm_compare = $query_prm;
if ($startdate != '')
{ $query .= ' and accountingdate>=?'; array_push($query_prm,$startdate); array_push($query_prm_compare,$startdate2); }
if ($stopdate != '')
{ $query .= ' and accountingdate<=?'; array_push($query_prm,$stopdate); array_push($query_prm_compare,$stopdate2); }
$query .= ' and confirmed=1 and cancelledid=0 and isreturn=0
group by productid';
require('inc/doquery.php');
$num_results_main = $num_results;
$main_result = $query_result;

if ($compare)
{
  $query_prm_temp = $query_prm;
  $query_prm = $query_prm_compare;
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $compareA[$query_result[$i]['productid']] = $query_result[$i]['sum'];
  }
}
$query = str_replace('isreturn=0', 'isreturn=1', $query);
if ($compare)
{
  require('inc/doquery.php');
  for ($i=0; $i<$num_results; $i++)
  {
    if (isset($compareA[$query_result[$i]['productid']]))
    { $compareA[$query_result[$i]['productid']] -= $query_result[$i]['sum']; }
  }
  $query_prm = $query_prm_temp;
}

require('inc/doquery.php');
for ($i=0; $i<$num_results; $i++)
{
  $key = array_search($query_result[$i]['productid'], array_column($main_result, 'productid'));
  if ($key > 0) { $main_result[$key]['sum'] -= $query_result[$i]['sum']; }
}

d_sortresults($main_result,'sum',$num_results_main);

echo d_table('report');
echo '<thead><th>Produit<th>Chiffre d\'Affaire';
if ($compare) { echo '<th>Comparé avec<th>Croissance'; }
echo '</thead>';
if ($choice > $num_results_main) { $choice = $num_results_main; }
$stop = $num_results_main-$choice;
for ($i=$num_results_main-1; $i>=$stop; $i--)
{
  echo d_tr();
  echo d_td($productA[$main_result[$i]['productid']]);
  echo d_td($main_result[$i]['sum'],'currency');
  if ($compare)
  {
    if (!isset($compareA[$main_result[$i]['productid']])) { $compareA[$main_result[$i]['productid']] = 0; }
    echo d_td($compareA[$main_result[$i]['productid']],'currency');
    if ($compareA[$main_result[$i]['productid']] == 0) { echo d_td(); }
    else
    {
      $show_percent = (100 * $main_result[$i]['sum'] / $compareA[$main_result[$i]['productid']]) - 100;
      echo d_td($show_percent,'percent');
    }
  }
}
echo d_table_end();

?>