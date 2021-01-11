<?php

$PA['fromdate'] = 'date';
$PA['todate'] = 'date';
$PA['product'] = '';
$PA['client'] = '';
$PA['salesprice'] = 'udecimal';
$PA['retailprice'] = 'udecimal';
$PA['clientpricingid'] = 'uint';
$PA['deleted'] = 'uint';
require('inc/readpost.php');
require('inc/findproduct.php');
require('inc/findclient.php');

if (($clientid > 0 && $productid > 0 && $salesprice > 0) || ($deleted == 1 && $clientpricingid > 0))
{
  if ($clientpricingid > 0)
  {
    $query = 'update clientpricing set productid=?,clientid=?,salesprice=?,fromdate=?,todate=?,deleted=? where clientpricingid=?';
    $query_prm = array($productid, $clientid, $salesprice, $fromdate, $todate, $deleted, $clientpricingid);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>Prix par client modifié.</p>';
    }
  }
  else
  {
    $query = 'insert into clientpricing (productid,clientid,salesprice,fromdate,todate) values (?,?,?,?,?)';
    $query_prm = array($productid, $clientid, $salesprice, $fromdate, $todate);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>Prix par client établi.</p>';
    }
  }
}

if ($clientpricingid > 0)
{
  $query = 'select * from clientpricing where clientpricingid=?';
  $query_prm = array($clientpricingid);
  require('inc/doquery.php');
  $product = $query_result[0]['productid'];
  $client = $query_result[0]['clientid'];
  $fromdate = $query_result[0]['fromdate'];
  $todate = $query_result[0]['todate'];
  $salesprice = $query_result[0]['salesprice'];
  $retailprice = $query_result[0]['retailprice'];
  $deleted = $query_result[0]['deleted'];
  echo '<h2>Modifier prix par client:</h2>';
}
else
{
  echo '<h2>Ajouter prix par client:</h2>';
}
echo '<form method="post" action="products.php"><table><tr><td>';
require('inc/selectclient.php');
echo '<tr><td>';
require('inc/selectproduct.php');
echo '<tr><td>Prix de vente:</td><td><input type="text" STYLE="text-align:right" name="salesprice" value="',d_input($salesprice,'decimal'),'" size=10></td></tr>';
if ($_SESSION['ds_useretailprice'])
{
  echo '<tr><td>Prix réglementé:</td><td><input type="text" STYLE="text-align:right" name="retailprice" value="',d_input($retailprice,'decimal'),'" size=10>';
}
echo '<tr><td>De:<td>';
$datename = 'fromdate'; $selecteddate = $fromdate;
require('inc/datepicker.php');
echo '<tr><td>À:<td>';
$datename = 'todate'; $selecteddate = $todate;
require('inc/datepicker.php');
echo '<tr><td>Supprimé:<td><input type=checkbox name="deleted"'; if ($deleted == 1) { echo ' checked'; } echo ' value=1>';
echo '<tr><td colspan="2" align="center">
<input type=hidden name="productsmenu" value="' . $productsmenu . '">
<input type=hidden name="clientpricingid" value="' . $clientpricingid . '">
<input type="submit" value="Valider"></td></tr>
</table></form>';

echo '<br><br><h2>Modifier prix par client</h2><form method="post" action="products.php"><table class=report>';
echo '<thead><th><th>Début<th>Fin<th colspan=2>Client<th colspan=2>Produit<th>Prix</thead>';
$query_prm = array();
$query = 'select clientpricingid,clientpricing.productid,clientpricing.clientid,fromdate,todate,clientpricing.retailprice
,clientpricing.salesprice,clientname,productname
from clientpricing,client,product
where clientpricing.clientid=client.clientid and clientpricing.productid=product.productid';
if ($_SESSION['ds_showdeleteditems'] != 1) { $query .= ' and clientpricing.deleted=0'; }
if ($clientid > 0) { $query .= ' and clientpricing.clientid=?'; array_push($query_prm, $clientid); }
$query .= ' order by clientname,productname,fromdate desc,todate desc';
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  echo d_tr();
  echo d_td_unfiltered('<input type=radio name="clientpricingid" value="'.d_input($query_result[$i]['clientpricingid']).'">','right');
  echo d_td($query_result[$i]['fromdate'],'date');
  echo d_td($query_result[$i]['todate'],'date');
  echo d_td($query_result[$i]['clientid'],'int');
  echo d_td(d_decode($query_result[$i]['clientname']));
  echo d_td($query_result[$i]['productid'],'int');
  echo d_td(d_decode($query_result[$i]['productname']));
  echo d_td($query_result[$i]['salesprice'],'decimal');
}
echo '<tr><td colspan=10 align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Modifier"></td></tr>
</table></form>';

?>