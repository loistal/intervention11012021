<?php

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['product'] = '';
$PA['dateprice'] = 'udecimal';
$PA['vatincl'] = 'udecimal';
$PA['datepricingid'] = 'uint';
$PA['deleted'] = 'uint';
$PA['rebate'] = 'udecimal';
$PA['rebate_type'] = 'uint';
require('inc/readpost.php');
require('inc/findproduct.php');

if (($productid > 0) || ($deleted == 1 && $datepricingid > 0))
{
  if ($vatincl > 0)
  {
    $query = 'select taxcode from product,taxcode where product.taxcodeid=taxcode.taxcodeid and productid=?';
    $query_prm = array($productid);
    require ('inc/doquery.php');
    $taxcode = $query_result[0]['taxcode'];
    $dateprice = (100 * $vatincl) / (100 + $taxcode);
  }
  if ($datepricingid > 0)
  {
    $query = 'update datepricing set rebate=?,rebate_type=?,productid=?,dateprice=?,startdate=?,stopdate=?,deleted=? where datepricingid=?';
    $query_prm = array($rebate, $rebate_type, $productid, $dateprice, $startdate, $stopdate, $deleted, $datepricingid);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>Prix par date modifié.</p>';
    }
  }
  else
  {
    $query = 'insert into datepricing (rebate,rebate_type,productid,dateprice,startdate,stopdate) values (?,?,?,?,?,?)';
    $query_prm = array($rebate, $rebate_type, $productid, $dateprice, $startdate, $stopdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<p>Prix par date établi.</p>';
    }
  }
}

if ($datepricingid > 0)
{
  $query = 'select * from datepricing where datepricingid=?';
  $query_prm = array($datepricingid);
  require('inc/doquery.php');
  $product = $query_result[0]['productid'];
  $client = $query_result[0]['clientid'];
  $startdate = $query_result[0]['startdate'];
  $stopdate = $query_result[0]['stopdate'];
  $dateprice = $query_result[0]['dateprice'];
  $rebate = $query_result[0]['rebate'];
  $rebate_type = $query_result[0]['rebate_type'];
  $deleted = $query_result[0]['deleted'];
  echo '<h2>Modifier prix par date:</h2>';
}
else
{
  echo '<h2>Ajouter prix par date:</h2>';
}
echo '<form method="post" action="products.php"><table><tr><td>';
require('inc/selectproduct.php');
echo '</td></tr><tr><td>De:</td><td>';
$datename = 'startdate'; $selecteddate = $startdate;
require('inc/datepicker.php');
echo '</td></tr><tr><td>A:</td><td>';
$datename = 'stopdate'; $selecteddate = $stopdate;
require('inc/datepicker.php');
echo '</td></tr><tr><td>Prix de vente:</td><td><input type="text" STYLE="text-align:right" name="dateprice" value="',d_input($dateprice,'decimal'),'" size=10></td></tr>';
echo '<tr><td>Prix TTC souhaité:</td><td><input type="text" STYLE="text-align:right" name="vatincl" size=10>';
echo '<tr><td>Remise par défaut:</td><td><input type="text" STYLE="text-align:right" name="rebate" value="',d_input($rebate,'decimal'),'" size=10>
<select name="rebate_type"><option value=0>XPF</option><option value=1'; if ($rebate_type == 1) { echo ' selected'; } echo '>%</option></select>';
echo '<tr><td>Supprimé:<td><input type=checkbox name="deleted"'; if ($deleted == 1) { echo ' checked'; } echo ' value=1>';
echo '<tr><td colspan="2" align="center">
<input type=hidden name="productsmenu" value="' . $productsmenu . '">
<input type=hidden name="datepricingid" value="' . $datepricingid . '">
<input type="submit" value="Valider"></td></tr>
</table></form>';

echo '<br><br><h2>Modifier prix par date</h2><form method="post" action="products.php"><table class=report>';
echo '<thead><th><th>Début<th>Fin<th colspan=2>Produit<th>Prix</thead>';
$query = 'select datepricingid,datepricing.productid,startdate,stopdate,dateprice,productname
from datepricing,product
where datepricing.productid=product.productid';
if ($_SESSION['ds_showdeleteditems'] != 1) { $query .= ' and datepricing.deleted=0'; }
$query .= ' order by startdate desc,stopdate desc';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  echo d_tr();
  echo d_td_unfiltered('<input type=radio name="datepricingid" value="'.d_input($query_result[$i]['datepricingid']).'">','right');
  echo d_td($query_result[$i]['startdate'],'date');
  echo d_td($query_result[$i]['stopdate'],'date');
  echo d_td($query_result[$i]['productid'],'int');
  echo d_td(d_decode($query_result[$i]['productname']));
  echo d_td($query_result[$i]['dateprice'],'decimal');
}
echo '<tr><td colspan=10 align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Modifier"></td></tr>
</table></form>';

?>