<?php

$reportid = 2;

#fields used in order presented here
$ifield = 1;
$dp_fieldnameA[$ifield] = 'productid';$dp_fielddescrA[$ifield] = d_trad('number');$ifield++;
$productnamefield=$ifield;$dp_fieldnameA[$ifield] = 'productname';$dp_fielddescrA[$ifield] = d_trad('product');$ifield++;
$dp_fieldnameA[$ifield] = 'packaging';$dp_fielddescrA[$ifield] = d_trad('packaging');$ifield++;
$dp_fieldnameA[$ifield] = 'productfamilyid';$dp_fielddescrA[$ifield] = d_trad('family');$ifield++;
$stockfield=$ifield;$dp_fieldnameA[$ifield] = 'currentstock';$dp_fielddescrA[$ifield] = d_trad('stock');$ifield++;
$dp_fieldnameA[$ifield] = 'salespricevat';$dp_fielddescrA[$ifield] = d_trad('pricevat');$ifield++;
$dp_fieldnameA[$ifield] = 'salesprice';$dp_fielddescrA[$ifield] = d_trad('priceht');$ifield++;
$dp_fieldnameA[$ifield] = 'unitsalesprice';$dp_fielddescrA[$ifield] = d_trad('priceuht');$ifield++;
$dp_fieldnameA[$ifield] = 'promotext';$dp_fielddescrA[$ifield] = d_trad('promotion');$ifield++;
$dp_fieldnameA[$ifield] = 'eancode';$dp_fielddescrA[$ifield] = d_trad('eancode');$ifield++;
$dp_fieldnameA[$ifield] = 'countryid';$dp_fielddescrA[$ifield] = d_trad('origincountry');$ifield++;
$dp_fieldnameA[$ifield] = 'supplierid';$dp_fielddescrA[$ifield] = d_trad('supplier');$ifield++;
$dp_fieldnameA[$ifield] = 'taxcodeid';$dp_fielddescrA[$ifield] = d_trad('taxcode');$ifield++;
$dp_fieldnameA[$ifield] = 'unittypeid';$dp_fielddescrA[$ifield] = d_trad('unittype');$ifield++;
$dp_fieldnameA[$ifield] = 'suppliercode';$dp_fielddescrA[$ifield] = d_trad('suppliercode');$ifield++;
$dp_fieldnameA[$ifield] = 'fullproductname';$dp_fielddescrA[$ifield] = d_trad('fullproductname');$ifield++;
$dp_fieldnameA[$ifield] = 'image';$dp_fielddescrA[$ifield] = d_trad('image');$ifield++;
$dp_fieldnameA[$ifield] = 'regulationtypeid';$dp_fielddescrA[$ifield] = 'Réglementation';$ifield++;
$dp_fieldnameA[$ifield] = 'recent_prev';$dp_fielddescrA[$ifield] = 'Prix de Revient';$ifield++;
$dp_fieldnameA[$ifield] = 'detailsalespricevat';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_prixalternatif'].' TTC';$ifield++;
$dp_fieldnameA[$ifield] = 'detailsalesprice';$dp_fielddescrA[$ifield] = $_SESSION['ds_term_prixalternatif'];$ifield++;
$dp_fieldnameA[$ifield] = 'accountingnumberid';$dp_fielddescrA[$ifield] = 'Exception comptable';$ifield++;

# keep this last
if ($_SESSION['ds_stockperuser'])
{
  $ifield = 101;
  $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $dp_fieldnameA[$ifield] = 'stockuserid'.$query_result[$i]['userid'];
    $dp_fielddescrA[$ifield] = 'Stock ('.$query_result[$i]['username'].')';
    $ifield++;
  }
}

$dp_numfields = $_SESSION['ds_maxconfig'];
d_sortarray($dp_fielddescrA);

unset($ifield);

?>