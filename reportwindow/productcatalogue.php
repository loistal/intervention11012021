<?php

require('preload/unittype.php');
require('preload/taxcode.php');
require('preload/commissionrate.php');

$PA['height'] = 'uint';
$PA['width'] = 'uint';
$PA['productdepartmentid'] = 'int';
$PA['productfamilygroupid'] = 'int';
$PA['productfamilyid'] = 'int';
$PA['excludesupplier'] = 'uint';
$PA['client'] = 'supplier';
$PA['showimages'] = 'uint';
$PA['showweight'] = 'uint';
$PA['showeancode'] = 'uint';
$PA['showpromotext'] = 'uint';
$PA['show_unittype'] = 'uint';
$PA['show_salesprice'] = 'uint';
$PA['show_detailsalesprice'] = 'uint';
$PA['show_islandregulatedprice'] = 'uint';
$PA['show_retailprice'] = 'uint';
$PA['show_stock'] = 'uint';
$PA['show_commissions'] = 'uint';
require('inc/readpost.php');

$query_prm = array();
$query = 'select weight,promotext,unittypeid,commissionrateid,supplierid,currentstock,product.productid,eancode,creationdate,taxcodeid,productname,salesprice
                 ,detailsalesprice,islandregulatedprice,retailprice,numberperunit,netweightlabel,productfamilyname,productfamilygroupname,productdepartmentname,producttypename
                 ,suppliercode,commissionrateid
                 from product,productfamily,productfamilygroup,productdepartment,producttype
                 where product.producttypeid=producttype.producttypeid and product.productfamilyid=productfamily.productfamilyid
                 and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid
                 and discontinued=0 and notforsale=0';
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productfamilyid > 0) { $query .= ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and product.supplierid<>?'; array_push($query_prm, $supplierid); }
  else { $query .= ' and product.supplierid=?'; array_push($query_prm, $supplierid); }
}
$query .= ' order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname';
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

$product_colspan = 2;
$show_images = 0;
for ($i=0; $i < $num_results_main; $i++)
{
  if ($main_result[$i]['detailsalesprice'] != 0 && $main_result[$i]['detailsalesprice'] != $main_result[$i]['salesprice'] && isset($_POST['show_detailsalesprice'])) { $show_detailsalesprice = 1; }
  if ($main_result[$i]['islandregulatedprice'] != 0 && $main_result[$i]['islandregulatedprice'] != $main_result[$i]['salesprice'] && isset($_POST['show_islandregulatedprice'])) { $show_islandregulatedprice = 1; }
  if ($main_result[$i]['retailprice'] != 0 && $main_result[$i]['retailprice'] != $main_result[$i]['salesprice'] && isset($_POST['show_retailprice'])) { $show_retailprice = 1; }
  if ($showimages > 0)
  {
    $imagestring[$i] = '';
    $query = 'select imageid,imagetext,imageorder,imagetype from image where productid=? and imagetype<>"pdf" order by imageorder,imageid';
    if ($showimages == 1) { $query .= ' limit 1'; }
    $query_prm = array($main_result[$i]['productid']);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $show_images = 1;
      for ($y=0; $y < $num_results; $y++)
      {
        $imagestring[$i] .= '<img src="viewimage.php?image_id=' . $query_result[$y]['imageid'] . '"';
        if ($height > 0) { $imagestring[$i] .= ' height='.$height; }
        if ($width > 0) { $imagestring[$i] .= ' width='.$width; }
        $imagestring[$i] .= '> ';
      }
    }
  }
}
if ($show_images) { $product_colspan++; }

$title = 'Catalogue de produits';
showtitle_new($title);
require('inc/showparams.php');
echo d_table('report');

for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];

  if ($i == 0 || $lastpdn != $row['productdepartmentname']) { echo '<thead><th colspan=20><h2>Département ' . d_output($row['productdepartmentname']) . '</h2></thead>'; }
  if ($i == 0 || $lastpfgn != $row['productfamilygroupname'])
  {
    echo '<thead><th colspan=20><i><font size=+1>Famille ' . d_output($row['productfamilygroupname']) . '</font></i></thead>';
  }
  if ($i == 0 || $lastpfn != $row['productfamilyname'])
  {
    echo '<thead><th colspan=20>Sous-famille ' . d_output($row['productfamilyname']) . '</thead>';
    echo '<thead><th colspan=',$product_colspan,'>Produit<th>Conditionnement';
    if ($show_unittype) { echo '<th>Unité'; }
    if ($show_salesprice) { echo '<th>Prix'; }
    if ($show_detailsalesprice) { echo '<th>',d_output($_SESSION['ds_term_prixalternatif']); }
    if ($show_islandregulatedprice) { echo '<th>Prix PGL'; }
    if ($show_retailprice) { echo '<th>Prix réglementé'; }
    if ($show_stock) { echo '<th>Stock'; }
    if ($showweight) { echo '<th>Poids'; }
    if ($showeancode) { echo '<th>Code EAN unité'; }
    if ($show_commissions) { echo '<th>Commission'; }
    if ($showpromotext) { echo '<th>Infos promo'; }
    echo '</thead>';
  }

  $stock = $row['currentstock'];
  $dmp = $unittype_dmpA[$row['unittypeid']];
  $stock /= $dmp;
  $row['salesprice'] *= $dmp;
  $row['detailsalesprice'] *= $dmp;
  $row['islandregulatedprice'] *= $dmp;
  $row['retailprice'] *= $dmp;

  echo d_tr();
  if ($show_images) { echo d_td_unfiltered($imagestring[$i]); }
  echo d_td($row['productid'], 'int');
  echo d_td(d_decode($row['productname']));
  echo d_td($row['numberperunit'] . ' x ' . $row['netweightlabel'], 'right');
  if ($show_unittype) { echo d_td($unittypeA[$row['unittypeid']]); }
  if ($show_salesprice) { echo d_td($row['salesprice'] + ($row['salesprice']*$taxcodeA[$row['taxcodeid']]/100),'currency'); }
  if ($show_detailsalesprice)
  {
    if ($row['detailsalesprice'] != $row['salesprice']) { echo d_td($row['detailsalesprice'] + ($row['detailsalesprice']*$taxcodeA[$row['taxcodeid']]/100),'currency'); }
    else { echo d_td(); }
  }
  if ($show_islandregulatedprice)
  {
    if ($row['islandregulatedprice'] != $row['salesprice']) { echo d_td($row['islandregulatedprice'] + ($row['islandregulatedprice']*$taxcodeA[$row['taxcodeid']]/100),'currency'); }
    else { echo d_td(); }
  }
  if ($show_retailprice)
  {
    if ($row['retailprice'] != $row['salesprice']) { echo d_td($row['retailprice']/$row['numberperunit'] + (($row['retailprice']/$row['numberperunit'])*$taxcodeA[$row['taxcodeid']]/100),'currency'); }
    else { echo d_td(); }
  }
  if ($show_stock) { echo d_td(floor($stock),'int'); }
  if ($showweight)
  {
    if ($row['weight'] >= 100) { $row['weight'] = ($row['weight'] / 1000) . '&nbsp;kg'; }
    else { $row['weight'] = $row['weight'] . '&nbsp;g'; }
    echo d_td($row['weight'],'right');
  }
  if ($showeancode) { echo d_td($row['eancode']); }
  if ($show_commissions)
  {
    if (isset($commissionrateA[$row['commissionrateid']])) { echo d_td($commissionrateA[$row['commissionrateid']],'right'); }
    else { echo d_td(); }
  }
  if ($showpromotext) { echo d_td($row['promotext']); }

  $lastpdn = $row['productdepartmentname'];
  $lastpfgn = $row['productfamilygroupname'];
  $lastpfn = $row['productfamilyname'];
}
echo d_table_end();

?>