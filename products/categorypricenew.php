<?php

$tm = '';
if (isset($_GET['tm']) && ($_GET['tm'] == 2 || $_GET['tm'] == 3)) { $tm = $_GET['tm']; }
if (isset($_POST['tm']) && ($_POST['tm'] == 2 || $_POST['tm'] == 3)) { $tm = $_POST['tm']; }

require('preload/clientcategory'.$tm.'.php');

$error = 0; $log_salesprice_type = 1;
if ($tm == 2) {  $log_salesprice_type = 2; if (!isset($clientcategory2A)) { $error = 1; } }
elseif ($tm == 3) {  $log_salesprice_type = 3; if (!isset($clientcategory3A)) { $error = 1; } }
else { if (!isset($clientcategoryA)) { $error = 1; } }
if ($error)
{
  echo '<p>Veuiller définir un ',d_output($_SESSION['ds_term_clientcategory'.$tm]),'.</p>'; exit;
}

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['product'] = '';
$PA['categoryprice'] = 'udecimal';
$PA['vatincl'] = 'udecimal';
$PA['categorypricingid'] = 'uint';
$PA['clientcategoryid'] = 'uint';
$PA['clientcategory2id'] = 'uint';
$PA['clientcategory3id'] = 'uint';
$PA['deleted'] = 'uint';
$PA['percentagerebate'] = 'udecimal';
require('inc/readpost.php');
require('inc/findproduct.php'); if (!isset($productid)) { $productid = -1; }
if ($stopdate == '') { $stopdate = d_builddate(1,1,$_SESSION['ds_endyear']); }
if ($tm == 2) { $clientcategoryid = $clientcategory2id; }
elseif ($tm == 3) { $clientcategoryid = $clientcategory3id; }

if (($productid > 0) || ($deleted == 1 && $categorypricingid > 0))
{
  $query = 'select taxcode,product.taxcodeid from product,taxcode where product.taxcodeid=taxcode.taxcodeid and productid=?';
  $query_prm = array($productid);
  require ('inc/doquery.php');
  $taxcode = $query_result[0]['taxcode'];
  $taxcodeid = $query_result[0]['taxcodeid'];
  if ($vatincl > 0) { $categoryprice = (100 * $vatincl) / (100 + $taxcode); }
  if ($categorypricingid > 0)
  {
    $query = 'select categoryprice from categorypricing'.$tm.' where categorypricing'.$tm.'id=?';
    $query_prm = array($categorypricingid);
    require('inc/doquery.php');
    $old_salesprice = $query_result[0]['categoryprice'];
    $query = 'update categorypricing'.$tm.' set clientcategory'.$tm.'id=?,percentagerebate=?,productid=?,categoryprice=?,startdate=?,stopdate=?,deleted=? where categorypricing'.$tm.'id=?';
    $query_prm = array($clientcategoryid, $percentagerebate, $productid, $categoryprice, $startdate, $stopdate, $deleted, $categorypricingid);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>Prix par ',d_output($_SESSION['ds_term_clientcategory'.$tm]),' modifié.</p>';
    }
  }
  else
  {
    $query = 'insert into categorypricing'.$tm.' (clientcategory'.$tm.'id,percentagerebate,productid,categoryprice,startdate,stopdate) values (?,?,?,?,?,?)';
    $query_prm = array($clientcategoryid, $percentagerebate, $productid, $categoryprice, $startdate, $stopdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>Prix par ',d_output($_SESSION['ds_term_clientcategory'.$tm]),' établi.</p>';
    }
  }
  if ($deleted) { $categoryprice = 0; }
  if (!isset($old_salesprice)) { $old_salesprice = 0; }
  $query = 'insert into log_salesprice (userid,productid,salesprice,taxcodeid,logdate,logtime
  ,log_salesprice_type,old_salesprice,exception_id)
  values (?,?,?,?,curdate(),curtime(),?,?,?)';
  $query_prm = array($_SESSION['ds_userid'],$productid,$categoryprice,$taxcodeid,$log_salesprice_type,$old_salesprice
  ,$clientcategoryid);
  require('inc/doquery.php');
}

if ($categorypricingid > 0)
{
  $query = 'select * from categorypricing'.$tm.' where categorypricing'.$tm.'id=?';
  $query_prm = array($categorypricingid);
  require('inc/doquery.php');
  $product = $query_result[0]['productid'];
  $startdate = $query_result[0]['startdate'];
  $stopdate = $query_result[0]['stopdate'];
  $categoryprice = $query_result[0]['categoryprice'];
  $clientcategoryid = $query_result[0]['clientcategory'.$tm.'id'];
  $percentagerebate = $query_result[0]['percentagerebate'];
  $deleted = $query_result[0]['deleted'];
  echo '<h2>Modifier prix par ',d_output($_SESSION['ds_term_clientcategory'.$tm]),':</h2>';
}
else
{
  echo '<h2>Ajouter prix par ',d_output($_SESSION['ds_term_clientcategory'.$tm]),':</h2>';
}
echo '<form method="post" action="products.php"><table><tr><td>';
require('inc/selectproduct.php');
$dp_itemname = 'clientcategory'.$tm; $dp_description = $_SESSION['ds_term_clientcategory'.$tm]; $dp_selectedid = $clientcategoryid;
require('inc/selectitem.php');
echo '<tr><td>Prix de vente HT:</td><td><input type="text" STYLE="text-align:right" name="categoryprice" value="',d_input($categoryprice,'decimal'),'" size=10></td></tr>';
echo '<tr><td>Prix TTC souhaité:</td><td><input type="text" STYLE="text-align:right" name="vatincl" size=10>';
echo '<tr><td>Remise par défaut:</td><td><input type="text" STYLE="text-align:right" name="percentagerebate" value="',d_input($percentagerebate,'decimal'),'" size=10>';
echo '<tr><td>De:<td>';
$datename = 'startdate'; $selecteddate = $startdate;
require('inc/datepicker.php');
echo '<tr><td>A:<td>';
$datename = 'stopdate'; $selecteddate = $stopdate;
require('inc/datepicker.php');
echo '<tr><td>Supprimé:<td><input type=checkbox name="deleted"'; if ($deleted == 1) { echo ' checked'; } echo ' value=1>';
echo '<tr><td colspan="2" align="center">
<input type=hidden name="productsmenu" value="' . $productsmenu . '">
<input type=hidden name="categorypricingid" value="' . $categorypricingid . '">
<input type=hidden name="tm" value="' . $tm . '">
<input type="submit" value="Valider"></td></tr>
</table></form>';

echo '<br><br><h2>Modifier prix par ',d_output($_SESSION['ds_term_clientcategory'.$tm]),'</h2><form method="post" action="products.php"><table class=report>';
echo '<thead><td colspan=10 align="center"><input type="submit" value="Modifier"></thead>';
echo '<thead><th><th>Début<th>Fin<th>',d_output($_SESSION['ds_term_clientcategory'.$tm]),'<th colspan=2>Produit<th>Prix<th>Remise en % par défaut</thead>';
$query_prm = array();
$query = 'select categorypricing'.$tm.'id,categorypricing'.$tm.'.productid,startdate,stopdate,categoryprice,productname
,percentagerebate,clientcategory'.$tm.'id
from categorypricing'.$tm.',product
where categorypricing'.$tm.'.productid=product.productid';
if ($_SESSION['ds_showdeleteditems'] != 1) { $query .= ' and categorypricing'.$tm.'.deleted=0'; }
if ($clientcategoryid > 0)
{
  $query .= ' and clientcategory'.$tm.'id=?';
  array_push($query_prm, $clientcategoryid);
}
$query .= ' order by clientcategory'.$tm.'id,productname';

require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $cat = '';
  if ($tm == 2) { if ($query_result[$i]['clientcategory2id'] > 0) { $cat = $clientcategory2A[$query_result[$i]['clientcategory2id']]; } }
  elseif ($tm == 3) { if ($query_result[$i]['clientcategory3id'] > 0) { $cat = $clientcategory3A[$query_result[$i]['clientcategory3id']]; } }
  elseif ($query_result[$i]['clientcategoryid'] > 0) { $cat = $clientcategoryA[$query_result[$i]['clientcategoryid']]; }
  echo d_tr();
  echo d_td_unfiltered('<input type=radio name="categorypricingid" value="'.d_input($query_result[$i]['categorypricing'.$tm.'id']).'">','right');
  echo d_td($query_result[$i]['startdate'],'date');
  echo d_td($query_result[$i]['stopdate'],'date');
  echo d_td($cat);
  echo d_td($query_result[$i]['productid'],'int');
  echo d_td(d_decode($query_result[$i]['productname']));
  echo d_td($query_result[$i]['categoryprice'],'decimal');
  echo d_td($query_result[$i]['percentagerebate'],'decimal');
}
echo '<tr><td colspan=10 align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '">
<input type=hidden name="tm" value="' . $tm . '"><input type="submit" value="Modifier">
</table></form>';

?>