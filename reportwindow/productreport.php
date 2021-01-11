<?php

require('reportwindow/productreport_cf.php');
require('preload/country.php');
require('preload/productfamily.php');
require('preload/productfamilygroup.php');
require('preload/productdepartment.php');
require('preload/taxcode.php');
require('preload/unittype.php');

$product = $_POST['product']; require('inc/findproduct.php');
$productdepartmentid = $_POST['productdepartmentid'];
$productfamilygroupid = $_POST['productfamilygroupid'];
$productfamilyid = $_POST['productfamilyid'];
$displaydiscontinued = (int)$_POST['displaydiscontinued'];
$displaynotforsale = (int) $_POST['displaynotforsale'];
$eancode = $_POST['eancode'];
$countryid = $_POST['countryid'];
$supplier = $_POST['client']; require('inc/findclient.php');
$taxcodeid = $_POST['taxcodeid'];
$unittypeid = $_POST['unittypeid'];
$orderby = $_POST['orderby'];
$negative_stock = (int) $_POST['negative_stock'];

if (isset($_POST['updatestock'])) { $dp_updatestock = (int) $_POST['updatestock']+0; } else { $dp_updatestock = 0; }
if ($dp_updatestock == 1) { $currentyear = mb_substr($_SESSION['ds_curdate'],0,4); }

//TITLE
$title = d_trad('productreport');
showtitle($title);
echo '<h2>' . $title . '</h2>';

//SELECT
$query = 'select p.accountingnumberid,p.eancode,p.countryid,p.taxcodeid,p.unittypeid,p.netweightlabel,p.promotext,p.productid,p.productname
,p.suppliercode,p.supplierid,p.currentstock,p.currentstockrest,p.salesprice,p.taxcodeid,p.unitsalesprice,pg.productdepartmentid
,pf.productfamilygroupid,p.productfamilyid,p.numberperunit,regulationtypeid,recent_prev,detailsalesprice,displaymultiplier,p.countstock
from product p,productfamily pf,productfamilygroup pg, productdepartment pd,unittype
where p.productfamilyid=pf.productfamilyid and pf.productfamilygroupid=pg.productfamilygroupid and pg.productdepartmentid = pd.productdepartmentid
and p.unittypeid=unittype.unittypeid';
$query_prm = array();

$ourparams = '';
if (isset($productid) && $productid > 0) 
{ 
  $query .= ' and p.productid=?'; 
  array_push($query_prm, $productid);
  $ourparams .= d_trad('product:') .'&nbsp' . $productname . '<br>'; 
}
elseif ($product != '') 
{ 
  $query .= ' and lower(p.productname) LIKE ?'; 
  array_push($query_prm, '%' .  mb_strtolower(d_encode($product)) . '%' );
  $ourparams .= d_trad('product:') .'&nbsp' . d_output($product). '<br>'; 
}

if ($productdepartmentid > 0) 
{ 
  $query .= ' and pg.productdepartmentid=?';
  array_push($query_prm,$productdepartmentid); 
}
if ($productfamilygroupid > 0) 
{ 
  $query .= ' and pf.productfamilygroupid=?';
  array_push($query_prm,$productfamilygroupid); 
}
if ($productfamilyid > 0) 
{ 
  $query .= ' and p.productfamilyid=?';
  array_push($query_prm,$productfamilyid); 
}

if ($eancode != '')
{
  $query .= ' and p.eancode LIKE ?';
  array_push($query_prm,'%' . $eancode . '%'); 
  $ourparams .= d_trad('eancode:') .'&nbsp' . d_output($eancode). '<br>';  
}
if ($countryid > 0)
{
  $query .= ' and p.countryid=?';
  array_push($query_prm,$countryid); 
  $ourparams .= d_trad('origincountry:') .'&nbsp' . $countryA[$countryid] . '<br>';    
}

if ($clientid > 0) 
{ 
  $query .= ' and p.supplierid=?'; 
  array_push($query_prm, $clientid); 
  $ourparams .= d_trad('supplier:') .'&nbsp' . $clientname . '<br>';    
}
elseif ($supplier != '')
{
  $query .= ' and p.supplierid LIKE ?';
  array_push($query_prm, '%' . $supplier . '%');
  $ourparams .= d_trad('supplier:') .'&nbsp' . d_output($supplier) . '<br>';    
}

if ($taxcodeid > 0)
{
  $query .= ' and p.taxcodeid=?';
  array_push($query_prm,$taxcodeid); 
  $ourparams .= d_trad('taxcode:') .'&nbsp' . $taxcodeA[$taxcodeid] . '<br>';    
}

if ($unittypeid > 0)
{
  $query .= ' and p.unittypeid=?';
  array_push($query_prm,$unittypeid); 
  $ourparams .= d_trad('unittype:') .'&nbsp' . $unittypeA[$unittypeid] . '<br>';     
}

switch($displaydiscontinued) 
{
  case 1:
    $query .= ' and p.discontinued=0'; 
    #$ourparams .= d_trad('discontinuedexcluded') . '<br>';
    break;
  case 2:
    $query .= ' and p.discontinued=1';
    $ourparams .= d_trad('onlydiscontinued') . '<br>';         
    break;
  case 0:
  $ourparams .= d_trad('discontinuedexcluded') . '<br>';
  break;     
}
switch($displaynotforsale) 
{ 
  case 1:
    $query .= ' and p.notforsale=0';
    #$ourparams .= d_trad('notforsaleexcluded') . '<br>';
    break;
  case 2:
    $query .= ' and p.notforsale=1';
    $ourparams .= d_trad('onlynotforsale') . '<br>';      
    break;
  case 0:
  $ourparams .= d_trad('notforsaleexcluded') . '<br>';
  break;  
}

if ($negative_stock) { $query .= ' and currentstock<0'; }

switch($orderby)
{
  case 1:
    if ($_SESSION['ds_useproductcode'] == 1) 
    { 
      $query = $query . ' order by p.suppliercode';
    }
    else 
    { 
      $query .= ' order by p.productid';
    }
    break;
  case 2:
    $query .= ' order by productname';
    break;
  case 3 :
    $query .= ' order by pd.departmentrank,productdepartmentname,pg.familygrouprank,productfamilygroupname,pf.familyrank,productfamilyname,p.productname';$subtfield1 = 'productfamilyid';
    break;
}
echo $ourparams;

require('inc/doquery.php');
$row = $query_result; $num_rows = $num_results; unset($query_result, $num_results);

$showsubtotalcurrentstock = 0;$showgrandtotalcurrentstock = 0;
$showsubtotalcurrentstockrest = 0;$showgrandtotalcurrentstockrest = 0;

$subtfield1_count_descr='nbproduct';
require('inc/showreport.php');
unset ($ourparams,$subtfield1,$showsubtfield1,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$showsubtotal,$showgrandtotal);

?>