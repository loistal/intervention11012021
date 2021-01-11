<?php

ini_set('max_execution_time', 600*2);

require('preload/unittype.php');

$currentyear = mb_substr($_SESSION['ds_curdate'],0,4);

$PA['userid'] = 'uint';
$PA['productfamilyid'] = 'int';
$PA['to_zero'] = 'uint';
$PA['calcglobal'] = 'uint';
$PA['calcglobal2'] = 'uint';
$PA['calc_value'] = 'uint';
require('inc/readpost.php');

if ($calcglobal && $calcglobal2 && $userid == 0) { $calcglobal = 1; }
else { $calcglobal = 0; }

$query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1';
if ($userid > 0) { $query .= ' and userid=?'; }
$query .= ' order by username';
$query_prm = array($userid);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $stockperuserA[$query_result[$i]['userid']] = $query_result[$i]['username'];
}

$title = 'Stock par utilisateur';
showtitle_new($title);

if ($productfamilyid > 0)
{
  require('preload/productdepartment.php');
  require('preload/productfamilygroup.php');
  require('preload/productfamily.php');
  echo d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$productfamilyid]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$productfamilyid]] . ' / ' . $productfamilyA[$productfamilyid]);
}

echo d_table('report');
echo '<thead><th>Produit';
foreach ($stockperuserA as $name)
{
  echo '<th>',d_output($name);
}
if ($userid == 0) { echo '<th>Total<th>Global<th>Écart'; }
if ($calc_value) { echo '<th>Valeur'; }
echo '</thead>';

$query_prm = array();
$query = 'select generic,discontinued,notforsale,productname,brand,supplierid,numberperunit,netweightlabel,suppliercode,productid
from product';
if ($productfamilyid > 0) { $query .= ' where product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $usertotal = 0;
  $productid = $main_result[$i]['productid'];
  $numberperunit = $npu = $main_result[$i]['numberperunit'];
  echo d_tr();
  echo d_td($main_result[$i]['productname'].' ('.$main_result[$i]['productid'].')');
  foreach ($stockperuserA as $dp_userid => $name)
  {
    require('inc/calcstock_user.php');
    if ($to_zero && $userid && $userstock != 0)
    {
      $netchange = 0 - $userstock;
      $query = 'insert into modifiedstock_user (productid,netchange,netvalue,changedate,changetime,userid,foruserid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?,?)';
      $query_prm = array($productid, $netchange, 0, $_SESSION['ds_userid'], $dp_userid, 'Remis à zéro', 0);
      require('inc/doquery.php');
      if ($num_results) { $userstock = 0; }
    }
    echo d_td($userstock, 'int');
    $usertotal += $userstock;
  }
  if ($userid == 0)
  {
    echo d_td();
    require('inc/calcstock.php');
    if ($calcglobal && ($currentstock - $usertotal) != 0)
    {
      $query = 'insert into modifiedstock (productid,netchange,netvalue,changedate,changetime,userid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?)';
      $query_prm = array($productid, ($usertotal - $currentstock), 0, $_SESSION['ds_userid'], 'Somme des utilisateurs', 0);
      require('inc/doquery.php');
      $currentstock = $usertotal;
    }
    echo d_td($currentstock, 'int');
    echo d_td($currentstock - $usertotal, 'int');
    if ($calc_value)
    {
      $query = 'select prev from purchasebatch where deleted=0 and productid=? order by arrivaldate desc limit 1';
      $query_prm = array($productid);
      require('inc/doquery.php');
      if (isset($query_result[0]['prev']) && $currentstock != 0) { echo d_td($currentstock * $query_result[0]['prev'], 'decimal'); }
      else { echo d_td(); }
    }
  }
}
echo d_table_end();

?>