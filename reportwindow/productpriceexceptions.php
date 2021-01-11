<?php

require('preload/regulationzone.php');
require('preload/island.php');

$PA['product'] = 'product';
$PA['client'] = 'client';
require('inc/readpost.php');

if ($clientid > 0)
{
  echo '<h2>',$clientname,' (',$clientid,')</h2><br>';
  $query = 'select clientcategoryid,clientcategory2id,clientcategory3id,islandid
  from client,town
  where client.townid=town.townid and clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  $ccid = $query_result[0]['clientcategoryid'];
  $cc2id = $query_result[0]['clientcategory2id'];
  $cc3id = $query_result[0]['clientcategory3id'];
  $islandid = $query_result[0]['islandid'];
  $rzid = $island_regulationzoneidA[$islandid];
}
else
{
  $ccid = 0;
  $cc2id = 0;
  $cc3id = 0;
  $islandid = 0;
  $rzid = 0;
}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,clientcategoryname,categoryprice
from categorypricing,clientcategory,product
where categorypricing.productid=product.productid and categorypricing.clientcategoryid=clientcategory.clientcategoryid
and categorypricing.deleted=0';
$query_prm = array();
if ($ccid > 0) { $query .= ' and categorypricing.clientcategoryid=?'; array_push($query_prm, $ccid); }
if ($productid > 0) { $query .= ' and categorypricing.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by clientcategoryname,productname';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix/'.$_SESSION['ds_term_clientcategory'].'</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Catégorie</b></td><td colspan=2><b>Produit</b></td><td><b>Prix de vente</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td>' . $row['clientcategoryname'] . '</td><td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['categoryprice']) . '</td></tr>';
}
echo '</table>';
echo '<br>';}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,clientcategory2name,categoryprice
from categorypricing2,clientcategory2,product
where categorypricing2.productid=product.productid and categorypricing2.clientcategory2id=clientcategory2.clientcategory2id
and categorypricing2.deleted=0';
$query_prm = array();
if ($cc2id > 0) { $query .= ' and categorypricing2.clientcategory2id=?'; array_push($query_prm, $cc2id); }
if ($productid > 0) { $query .= ' and categorypricing2.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by clientcategory2name,productname';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix/'.$_SESSION['ds_term_clientcategory2'].'</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Catégorie</b></td><td colspan=2><b>Produit</b></td><td><b>Prix de vente</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td>' . $row['clientcategory2name'] . '</td><td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['categoryprice']) . '</td></tr>';
}
echo '</table>';
echo '<br>';}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,clientcategory3name,categoryprice
from categorypricing3,clientcategory3,product where categorypricing3.productid=product.productid
and categorypricing3.clientcategory3id=clientcategory3.clientcategory3id and categorypricing3.deleted=0';
$query_prm = array();
if ($cc3id > 0) { $query .= ' and categorypricing3.clientcategory3id=?'; array_push($query_prm, $cc3id); }
if ($productid > 0) { $query .= ' and categorypricing3.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by clientcategory3name,productname';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix/'.$_SESSION['ds_term_clientcategory3'].'</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Catégorie</b></td><td colspan=2><b>Produit</b></td><td><b>Prix de vente</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td>' . $row['clientcategory3name'] . '</td><td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['categoryprice']) . '</td></tr>';
}
echo '</table>';
echo '<br>';}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,dateprice,startdate,stopdate,datepricingid
from datepricing,product
where dateprice>0 and datepricing.productid=product.productid and stopdate>=curdate()';
$query_prm = array();
if ($productid > 0) { $query .= ' and datepricing.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by stopdate desc,startdate desc,productname,datepricingid desc';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix/dates</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td colspan=2><b>Produit</b></td><td><b>Prix</b></td><td><b>Début</b></td><td><b>Fin</b></td><td><b>Priorité</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['dateprice']) . '</td><td align=right>' . datefix2($row['startdate']) . '</td><td align=right>' . datefix2($row['stopdate']) . '</td><td align=right>' . $row['datepricingid'] . '</td></tr>';
}
echo '</table>';
echo '<br>';}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,regulationzoneid,regionprice,regionpricing.retailprice
from regionpricing,product
where regionpricing.productid=product.productid and regionpricing.deleted=0';
$query_prm = array();
if ($rzid > 0) { $query .= ' and regulationzoneid=?'; array_push($query_prm, $rzid); }
if ($productid > 0) { $query .= ' and regionpricing.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by regulationzoneid,productname';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix/région</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Région</b></td><td colspan=2><b>Produit</b></td><td><b>Prix de vente</b></td><td><b>Prix de réglementé</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td>';
  if (isset($regulationzoneA[$row['regulationzoneid']])) { echo $regulationzoneA[$row['regulationzoneid']]; }
  echo '<td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['regionprice']) . '</td><td align=right>' . myfix($row['retailprice']) . '</td></tr>';
}
echo '</table>';
echo '<br>';}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,islandid,islandprice,islandpricing.retailprice
from islandpricing,product
where islandpricing.productid=product.productid and islandpricing.deleted=0';
$query_prm = array();
if ($islandid > 0) { $query .= ' and islandid=?'; array_push($query_prm, $islandid); }
if ($productid > 0) { $query .= ' and islandpricing.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by islandid,productname';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix/île</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Ile</b></td><td colspan=2><b>Produit</b></td><td><b>Prix de vente</b></td><td><b>Prix de réglementé</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td>' . $islandA[$row['islandid']] . '</td><td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['islandprice']) . '</td><td align=right>' . myfix($row['retailprice']) . '</td></tr>';
}
echo '</table>';
echo '<br>';}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,client.clientid,clientname,clientpricing.salesprice
,fromdate,todate
from clientpricing,client,product
where clientpricing.productid=product.productid and clientpricing.clientid=client.clientid and clientpricing.deleted=0';
$query_prm = array();
if ($clientid > 0) { $query .= ' and clientpricing.clientid=?'; array_push($query_prm, $clientid); }
if ($productid > 0) { $query .= ' and clientpricing.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by clientname,productname';
require('inc/doquery.php');
if ($num_results){
$main_result = $query_result; $num_results_main = $num_results;
echo '<h2>Prix/client</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Client</b></td><td colspan=2><b>Produit</b></td><td><b>Prix de vente</b></td><td><b>Dernier prix de revient<td colspan=2><b>Validité</b></td></tr>';
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];

  if (isset($prev_pidA[$row['productid']])) { $prev = $prev_pidA[$row['productid']]; }
  else
  {
    $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
    $query = $query . ' from purchasebatch,usertable';
    $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0 and prev>0';
    $query = $query . ' and productid=?';
    $query = $query . ' order by ';
    $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc limit 1';
    $query_prm = array($row['productid']);
    require('inc/doquery.php');
    if ($num_results) { $prev = $query_result[0]['prev']; $prev_pidA[$row['productid']] = $prev; } else { $prev = 0; }
  }
  
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td>' . $row['clientid'] . ': ' . mb_substr(d_output(d_decode($row['clientname'])),0,30) . '</td>
  <td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['salesprice']) . '</td>
  <td align=right>';
  if ($prev > $row['salesprice']) { echo '<span class="alert">'.myfix($prev).'</span>'; }
  else { echo myfix($prev); }
  echo '<td align=right>' . datefix2($row['fromdate']) . '</td><td align=right>' . datefix2($row['todate']) . '</td></tr>';
}
echo '</table>';
echo '<br>';}

$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,freemonthly.clientid,clientname,freequantity
from freemonthly,client,product
where freemonthly.deleted=0 and freemonthly.productid=product.productid and freemonthly.clientid=client.clientid';
$query_prm = array();
if ($clientid > 0) { $query .= ' and freemonthly.clientid=?'; array_push($query_prm, $clientid); }
if ($productid > 0) { $query .= ' and freemonthly.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by clientname,productname';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Gratuités par mois</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Client</b></td><td colspan=2><b>Produit</b></td><td><b>Quantité</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['freequantity']) . '</td></tr>';
}
echo '</table>';
echo '<br>';}

if ($clientid ==0){
$query = 'select product.productid,productname,netweightlabel,numberperunit,suppliercode,price,listpricingcatname,generic
from listpricing,product,listpricingcat
where listpricing.productid=product.productid and listpricing.listpricingcatid=listpricingcat.listpricingcatid
and listpricingcat.deleted=0 and price>0';
$query_prm = array();
if ($productid > 0) { $query .= ' and listpricing.productid=?'; array_push($query_prm, $productid); }
$query .= ' order by productname,listpricingcatname';
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix par liste</h2>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td colspan=2><b>Produit</b></td><td><b>Prix</b></td><td><b>Liste</b></td><td>&nbsp;</td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $showproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  $productname = d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  echo '<tr><td align=right>' . $showproductid . '</td><td>' . d_output($productname) . '</td><td align=right>' . myfix($row['price']) . '</td><td>' . d_output($row['listpricingcatname']) . '</td><td>';
  if ($row['generic'] == 0) { echo '<span class=alert>Ce produit n\'est pas générique.</span>'; }
  else { echo '&nbsp;'; }
  echo '</td></tr>';
}
echo '</table>';}

$query = 'select productid,algorithm from calcpricing where algorithm>0';
$query_prm = array();
if ($productid > 0) { $query .= ' and productid=?'; array_push($query_prm, $productid); }
require('inc/doquery.php');
if ($num_results){
echo '<h2>Prix par calcul</h2>';
echo '<table class="report"><tr><td><b>Produit</b></td><td><b>Calcul</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<tr><td>' . $row['productid'] . '</td><td>' . $row['algorithm'] . '</td></tr>';
}
echo '</table>';}}

?>