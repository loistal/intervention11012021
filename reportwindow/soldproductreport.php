<?php

# TODO refactor, use showparams, readpost, etc

$PA['deliverytypeid'] = 'int';
$PA['localvesselid'] = 'int';
require('inc/readpost.php');

require('reportwindow/soldproductreport_cf.php');

$invoicestatus = (int) $_POST['invoicestatus'];
$history = 'history'; if ($invoicestatus == 3) { $history = ''; }

#We build the query
$query_builder_select = 'SELECT ';
$query_builder_from = 'FROM ';
$query_builder_where = 'WHERE ';
$query_builder_groupby = 'GROUP BY ';
$query_builder_orderby = 'ORDER BY ';
$query_builder_limit = 'LIMIT ';

#We create the basic SELECT query builder
$query_builder_select .= 'linevat,returnreasonid,localvesselid,deliverytypeid,cancelledid,confirmed,userid,invoicecomment,basecartonprice,matchingid,givenrebate
,ih.accountingdate, ih.deliverydate, ih.invoicedate, ih.paybydate, p.productfamilyid, p.suppliercode, p.productid, p.productname, cli.clientid, cli.clientname, p.supplierid,
 p.suppliercode, ih.employeeid, cli.clientcategoryid, cli.clientcategory2id,iih.itemcomment,
 cli.townid, cli.clienttermid, p.temperatureid, p.unittypeid, p.countryid, p.producttypeid, p.brand, ih.reference, ih.extraname, ih.field1,
 ih.field2, ih.invoiceid, iih.lineprice, iih.linetaxcodeid, iih.quantity, ih.isreturn, ih.proforma, ih.isnotice, ih.returntostock, p.numberperunit, p.netweightlabel ';

#We create the basic FROM query builder
#Version with INNER JOIN
#$query_builder_from .= 'invoicehistory AS ih INNER JOIN invoiceitemhistory AS iih ON ih.invoiceid = iih.invoiceid INNER JOIN product AS p ON iih.productid = p.productid INNER JOIN client as c ON ih.clientid = cli.clientid ';

#Version with WHERE instead of INNER JOIN
$query_builder_from .= 'client AS cli, invoice'.$history.' AS ih, invoiceitem'.$history.' AS iih, product AS p ';
$query_builder_where .= 'ih.clientid = cli.clientid AND ih.invoiceid = iih.invoiceid AND iih.productid = p.productid ';

#We initialize all $_POST value
$datefield = (int) $_POST['datefield'];

#We retrieve the $_POST['startdate'] and then we filter his value with the datepickerresult
$datename = 'startdate';
require('inc/datepickerresult.php');
if ($_SESSION['ds_restrict_sales_reports'] && $startdate < (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01') { $startdate = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }

#We retrieve the $_POST['stopdate'] and then we filter his value with the datepickerresult
$datename = 'stopdate';
require('inc/datepickerresult.php');

$invoicetype = (int) $_POST['invoicetype'];

$productfamilyid = (int) $_POST['productfamilyid'];
$productfamilygroupid = (int) $_POST['productfamilygroupid'];
$productdepartmentid = (int) $_POST['productdepartmentid'];

#$product = (int) $_POST['product']; # nonono
if ($_POST['product'] == '')
{
  $product = 0;
}
elseif ($_SESSION['ds_useproductcode'])
{
  $query = 'select productid from product where suppliercode=?';
  $query_prm = array($_POST['product']);
  require('inc/doquery.php');
  $product = (int) $query_result[0]['productid'];
}
else
{
  $product = (int) $_POST['product'];
}

#Find client supplier id
if (isset($_POST['clientsupplier']))
{
  $client = $_POST['clientsupplier'];
  require('inc/findclient.php');

  if ($num_clients > 1)
  {
    $supplierterm = $_POST['clientsupplier'];
    $supplier = d_encode($_POST['clientsupplier']);
    $query = 'SELECT clientid FROM client WHERE clientname LIKE ?';
    $query_prm = array('%' . $supplier . '%');

    require('inc/doquery.php');
    $supplier = array();

    foreach ($query_result as $result)
    {
      $supplier[] = $result['clientid'];
    }

    $formatedsupplier = implode(",", $supplier);
    $supplier = '(' . $formatedsupplier . ')';
  }
  elseif ($num_clients == 1)
  {
    $supplierid = $clientid;
    $suppliername = $clientname;
  }

  unset($client);
}

#Find client invoice id
require('inc/findclient.php');

if ($num_clients == 1)
{
  $clientproduct = $clientid;
}
else
{
  $clientproduct = $_POST['client'];
}

$islandid = (int) $_POST['islandid'];
$userid = (int) $_POST['userid'];
$employeeid = (int) $_POST['employeeid'];
$employee1id = (int) $_POST['employee1id'];
$employee2id = (int) $_POST['employee2id'];
if (isset($_POST['clientcategoryid'])) { $clientcategoryid = (int) $_POST['clientcategoryid']; }
else { $clientcategoryid = -1; }
if (isset($_POST['clientcategory2id'])) { $clientcategory2id = (int) $_POST['clientcategory2id']; }
else { $clientcategory2id = -1; }
$clienttermid = (int) $_POST['clienttermid'];
$temperatureid = (int) $_POST['temperatureid'];
$unittypeid = (int) $_POST['unittypeid'];
$countryid = (int) $_POST['countryid'];
$producttypeid = (int) $_POST['producttypeid'];
$brand = $_POST['brand'];
$reference = $_POST['reference'];
$exreference = (int) $_POST['exreference'];
$extraname = $_POST['extraname'];
$exextraname = (int) $_POST['exextraname'];
$field1 = $_POST['field1'];
$field2 = $_POST['field2'];
$orderby = (int) $_POST['orderby'];
$itemcomment = $_POST['itemcomment'];

$title .= 'Produits vendus ' . d_trad('between', array(
    datefix2($startdate),
    datefix2($stopdate)
  ));

session_write_close();
showtitle($title);
echo '<h2>' . $title . '</h2>';

require('inc/showparams.php');

$query_prm = array();

#First we add filter for date
switch ($datefield)
{
  case 0:
    # Case 0 we search all invoice from startdate to stopdate by accountingdate
    $query_builder_where .= 'AND ih.accountingdate BETWEEN ? AND ? ';
    $query_prm[] = $startdate;
    $query_prm[] = $stopdate;
    break;
  case 1:
    # Case 1 we search all invoice from startdate to stopdate by deliverydate
    $query_builder_where .= 'AND ih.deliverydate BETWEEN ? AND ? ';
    $query_prm[] = $startdate;
    $query_prm[] = $stopdate;
    break;
  case 2:
    # Case 2 we search all invoice from startdate to stopdate by invoicedate
    $query_builder_where .= 'AND ih.invoicedate BETWEEN ? AND ? ';
    $query_prm[] = $startdate;
    $query_prm[] = $stopdate;
    break;
  case 3:
    # Case 3 we search all invoice from startdate to stopdate by paybydate
    $query_builder_where .= 'AND ih.paybydate BETWEEN ? AND ? ';
    $query_prm[] = $startdate;
    $query_prm[] = $stopdate;
    break;
}

#Then we add filter for invoicetype
switch ($invoicetype)
{
  case -1:
    #We select 'Display All' in this case no filter needed
    break;
  case 2:
    #We select invoice which are returned
    $query_builder_where .= 'AND ih.isreturn = 1 ';
    break;
  case 3:
    #We select invoice which are proforma
    $query_builder_where .= 'AND ih.proforma = 1 ';
    break;
  case 4:
    #We select invoice which are notice
    $query_builder_where .= 'AND ih.isnotice = 1 ';
    break;
  case 5:
    #We select invoice which are isreturnparam
    $query_builder_where .= 'AND ih.returntostock = 1 ';
    break;
}

#Then we add filter for invoicestatus
switch ($invoicestatus)
{
  case 0:
    #We select invoice which are confirmed
    $query_builder_where .= 'AND ih.confirmed = 1 AND cancelledid = 0 ';
    break;
  case 1:
    #We select invoice which are confirmed and matched
    $query_builder_where .= 'AND ih.confirmed = 1  AND ih.matchingid = 0  AND cancelledid = 0 ';
    break;
  case 2:
    #We select invoice which are matched
    $query_builder_where .= 'AND ih.matchingid > 0  AND cancelledid = 0 ';
    break;
  case 4:
    #We select invoice which are cancelled
    $query_builder_where .= 'AND ih.cancelledid>0 ';
    break;
  case -1:
    $query_builder_where .= 'and ih.cancelledid=0 ';
    break;
}

if ($productfamilyid > 0)
{
  #We select produtcs with the right product family id
  $query_builder_where .= 'AND p.productfamilyid = ? ';
  $query_prm[] = $productfamilyid;
}
elseif ($productfamilygroupid > 0)
{
  #We select produtcs with the right productfamilygroup id
  $query_builder_from .= ', productfamily AS pf ';
  $query_builder_where .= 'AND p.productfamilyid = pf.productfamilyid AND pf.productfamilygroupid = ? ';

  $query_prm[] = $productfamilygroupid;
}
elseif ($productdepartmentid > 0)
{
  #We select produtcs with the right product product department
  $query_builder_from .= ', productfamily AS pf, productfamilygroup AS pfg ';
  $query_builder_where .= 'AND p.productfamilyid = pf.productfamilyid AND pf.productfamilygroupid = pfg.productfamilygroupid AND pfg.productdepartmentid = ? ';
  $query_prm[] = $productdepartmentid;
}

#Then we add filter for productid
if ($product > 0)
{
  $query_builder_where .= 'AND p.productid = ? ';
  $query_prm[] = $product;
}

#Then we add filter for clientid
if ($clientproduct > 0)
{
  $query_builder_where .= 'AND ih.clientid = ? ';
  $query_prm[] = $clientproduct;
}
else
{
  if ($clientproduct != '')
  {
    $query_builder_where .= 'AND cli.clientname LIKE  ? ';
    $query_prm[] = '%' . $clientproduct . '%';
  }
}

#Then we add filter for supplierid
if ($supplierid > 0)
{
  $query_builder_where .= 'AND p.supplierid = ? ';
  $query_prm[] = $supplierid;
}

if ($supplier != '')
{
  $query_builder_where .= 'AND p.supplierid IN ' . $supplier . ' ';
}

#Then we add filter for island
if ($islandid > 0)
{
  #Don't forget to add ',' as separator field
  $query_builder_select .= ', t.islandid ';
  $query_builder_from .= ', town AS t ';

  $query_builder_where .= 'AND cli.townid = t.townid AND t.islandid = ? ';
  $query_prm[] = $islandid;
} else {
  $query_builder_select .= ', t.islandid ';
  $query_builder_from .= ', town AS t ';
  $query_builder_where .= 'AND cli.townid = t.townid ';
}

#Then we add filter for userid
if ($userid > 0)
{
  $query_builder_where .= 'AND ih.userid = ? ';
  $query_prm[] = $userid;
}

#Then we add filter for employeeid (facture),  employeeid1 (vendeur),  employeeid2 (fournisseur)
if ($employeeid >= 0)
{
  $query_builder_where .= 'AND ih.employeeid = ? ';
  $query_prm[] = $employeeid;
}
else
{
  if ($employee1id >= 0)
  {
    $query_builder_where .= 'AND ih.employeeid = ? ';
    $query_prm[] = $employee1id;
  }
  else
  {
    if ($employee2id >= 0)
    {
      $query_builder_where .= 'AND ih.employeeid = ? ';
      $query_prm[] = $employee2id;
    }
  }
}

#Then we add filter for clientcategoryid and clientcategoryid2
if ($clientcategoryid >= 0)
{
  $query_builder_where .= 'AND cli.clientcategoryid = ? ';
  $query_prm[] = $clientcategoryid;
}

if ($clientcategory2id >= 0)
{
  $query_builder_where .= 'AND cli.clientcategory2id = ? ';
  $query_prm[] = $clientcategory2id;
}

#Then we add filter for clienttermid
if ($clienttermid > 0)
{
  $query_builder_where .= 'AND cli.clienttermid = ? ';
  $query_prm[] = $clienttermid;
}

#Then we add filter for temperatureid
if ($temperatureid > 0)
{
  $query_builder_where .= 'AND p.temperatureid = ? ';
  $query_prm[] = $temperatureid;
}

#Then we add filter for unittypeid
if ($unittypeid > 0)
{
  $query_builder_where .= 'AND p.unittypeid = ? ';
  $query_prm[] = $unittypeid;
}

#Then we add filter for countryid
if ($countryid > 0)
{
  $query_builder_where .= 'AND p.countryid = ? ';
  $query_prm[] = $countryid;
}

#Then we add filter for producttypeid
if ($producttypeid > 0)
{
  $query_builder_where .= 'AND p.producttypeid = ? ';
  $query_prm[] = $producttypeid;
}

#Then we add filter for brand
if ($brand != '')
{
  $query_builder_where .= 'AND p.brand LIKE  ? ';
  $query_prm[] = '%' . $brand . '%';
}

#Then we add filter for invoicecomment
if ($reference != '')
{
  if ($exreference == 1)
  {
    $query_builder_where .= 'AND ih.invoicecomment NOT LIKE  ? ';
    $query_prm[] = '%' . $reference . '%';
  }
  else
  {
    $query_builder_where .= 'AND ih.invoicecomment LIKE  ? ';
    $query_prm[] = '%' . $reference . '%';
  }
}

#Then we add filter for extraname
if ($extraname != '')
{
  if ($exextraname == 1)
  {
    $query_builder_where .= 'AND ih.extraname NOT LIKE  ? ';
    $query_prm[] = '%' . $extraname . '%';
  }
  else
  {
    $query_builder_where .= 'AND ih.extraname LIKE  ? ';
    $query_prm[] = '%' . $extraname . '%';
  }
}

#Then we add filter for field1
if ($field1 != '')
{
  $query_builder_where .= 'AND ih.field1 LIKE  ? ';
  $query_prm[] = '%' . $field1 . '%';
}

#Then we add filter for field2
if ($field2 != '')
{
  $query_builder_where .= 'AND ih.field2 LIKE  ? ';
  $query_prm[] = '%' . $field2 . '%';
}

if ($itemcomment != '')
{
  $query_builder_where .= 'AND iih.itemcomment LIKE  ? ';
  $query_prm[] = '%' . $itemcomment . '%';
}

if ($deliverytypeid > 0)
{
  $query_builder_where .= 'and deliverytypeid=? ';
  $query_prm[] = $deliverytypeid;
}

if ($localvesselid >= 0)
{
  $query_builder_where .= 'and localvesselid=? ';
  $query_prm[] = $localvesselid;
}

if ($_SESSION['ds_allowedclientlist'] != '') { $query_builder_where .= ' and ih.clientid in '.$_SESSION['ds_allowedclientlist'].' '; }
if ($_SESSION['ds_userrepresentsclientid']) { $query_builder_where .= ' and p.supplierid='.$_SESSION['ds_userrepresentsclientid'].' '; }# TODO param

$query = $query_builder_select . $query_builder_from . $query_builder_where;

if ($invoicestatus == 3)
{
  #$query_union = $query;
  $query = str_replace('invoicehistory AS ih', 'invoice AS ih', $query);
  $query = str_replace('invoiceitemhistory AS iih', 'invoiceitem AS iih', $query);
  #$query_union = str_replace('ih.', 'ihx.', $query_union);
  #$query_union = str_replace('iih.', 'iihx.', $query_union);
  #$query = $query . ' UNION ' . $query_union;
  #$query_prm = array_merge($query_prm, $query_prm);
}

#Then we add orderby
switch ($orderby)
{
  case 0:
    $query_builder_orderby .= 'invoiceid ';
    break;
  case 1:
    $query_builder_orderby .= 'clientid, invoiceid ';
    break;
  case 2:
    $query_builder_orderby .= 'reference, invoiceid ';
    break;
  case 3:
    $query_builder_orderby .= 'field1, invoiceid ';
    break;
  case 4:
    $query_builder_orderby .= 'field2, invoiceid ';
    break;
  case 5:
    $query_builder_orderby .= 'cli.clientname, invoiceid ';
    break;
  case 6:
    $query_builder_orderby .= 'p.suppliercode, invoiceid ';
    break;
  case 7:
    $query_builder_orderby .= 'p.productid, invoiceid ';
    break;
  default:
    $query_builder_orderby .= 'invoiceid ';
}

$query = $query . $query_builder_orderby;

if ($_SESSION['ds_sqllimit'] > 0)
{
  $query_builder_limit .= $_SESSION['ds_sqllimit'];
  $query .= $query_builder_limit;
}

require('inc/doquery.php');

$row = $query_result;
$num_rows = $num_results;

unset($query_result, $num_results);

require('inc/showreport.php');
?>

































