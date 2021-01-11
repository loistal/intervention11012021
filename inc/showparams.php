<?php
# displays selected report parameteres on screen

# optional input: $dp_ourparams $dp_noshowempty
# other input: all params with appropriate variable names   example: $clientid

# DO NOT modify the order below, all reports should follow same order as invoicereport

$PAYMENT_DATE = 0;
$DEPOSIT_DATE = 1;
if ( !isset($dp_noshowempty) ) { $dp_noshowempty = 0;}

if (!isset($dp_ourparams))
{
  $dp_ourparams = '';
}

if (isset($datefield) && $datefield > 0)
{
  if ($datefield == 0)
  {
    $datefield = $_SESSION['ds_term_accountingdate'];
  }
  elseif ($datefield == 1)
  {
    $datefield = $_SESSION['ds_term_deliverydate'];
  }
  elseif ($datefield == 2)
  {
    $datefield = 'Date de saisie';
  }
  elseif ($datefield == 3)
  {
    $datefield = 'A payer avant le';
  }
  $dp_ourparams .= '<p>Date : ' . $datefield . '</p>';
}

if (isset($invoicetype) && $invoicetype > 0)
{
  $dp_ourparams .= '<p>' . d_trad('type:') . ' ';
  if ($invoicetype == 1)
  {
    $dp_ourparams .= d_trad('invoice');
  }
  elseif ($invoicetype == 2)
  {
    $dp_ourparams .= d_trad('isreturn');
  }
  elseif ($invoicetype == 3)
  {
    $dp_ourparams .= d_trad('proforma');
  }
  elseif ($invoicetype == 4)
  {
    $dp_ourparams .= $_SESSION['ds_term_invoicenotice'];
  }
  elseif ($invoicetype == 5)
  {
    $dp_ourparams .= d_trad('isreturnparam', $_SESSION['ds_term_invoicenotice']);
  }
  $dp_ourparams .= '</p>';
}

if (isset($invoicestatus) && $invoicestatus >= 0)
{
  $dp_ourparams .= '<p>' . d_trad('status:') . ' ';
  if ($invoicestatus == 0)
  {
    $dp_ourparams .= d_trad('confirmed1');
  }
  elseif ($invoicestatus == 1)
  {
    $dp_ourparams .= d_trad('confirmedandnotmatched');
  }
  elseif ($invoicestatus == 2)
  {
    $dp_ourparams .= d_trad('matched');
  }
  elseif ($invoicestatus == 3)
  {
    $dp_ourparams .= d_trad('notconfirmed');
    $history = '';
  }
  elseif ($invoicestatus == 4)
  {
    $dp_ourparams .= d_trad('cancelled');
  }
  elseif ($invoicestatus == 5)
  {
    $dp_ourparams .= 'Archivée';
  }
  $dp_ourparams .= '</p>';
}

if (isset($clientid))
{
  if($clientid > 0)
  {
    $dp_ourparams .= '<p>' . d_trad('clientwithparams:', array(
        d_output($clientname),
        $clientid
      )) . '</p>';
  } elseif(isset($clientidterm) && $clientidterm != '') {
    $dp_ourparams .= '<p> Client : &nbsp;"' . d_output($clientidterm) . '"</p>';
  }
}

if (isset($productid) && $productid > 0)
{
  $dp_ourparams .= '<p> Produit : ' . d_output($productname) . ' (' . $productid . ')</p>';
}

if (isset($islandid) && $islandid > 0)
{
  require('preload/island.php');
  $dp_ourparams .= '<p>' . d_trad('island:') . '&nbsp;' . d_output($islandA[$islandid]) . '</p>';
}

if (isset($localvesselid) && $localvesselid > 0)
{
  require('preload/localvessel.php');
  $dp_ourparams .= '<p>' . d_output($_SESSION['ds_term_localvessel']) . ':&nbsp;' . d_output($localvesselA[$localvesselid]) . '</p>';
}

if (isset($userid) && $userid  > 0)
{
  require('preload/user.php');
  $dp_ourparams .= '<p>' . d_trad('user:') . '&nbsp;' . d_output($userA[$userid]) . '</p>';
}

if (isset($employeeid)) # TODO not correct
{
  if(($employeeid == 0) && ($dp_noshowempty == 0))
  {
    $dp_ourparams .= '<p>Employé&nbsp;' . d_trad('empty') . '</p>'; #d_trad('invoiceemployee:')
  }
  elseif($employeeid > 0)
  {
    require('preload/employee.php');
    $dp_ourparams .= '<p>Employé: ' . d_output($employeeA[$employeeid]) . '</p>'; #d_trad('invoiceemployee:')
  }
}

if (isset($employee1id))
{
  if (($employee1id == 0) && ($dp_noshowempty == 0))
  {
    $dp_ourparams .= '<p>' . d_trad('employeewithparam:', $_SESSION['ds_term_clientemployee1']) . '&nbsp;' . d_trad('empty') . '</p>';
  }
  elseif ($employee1id > 0)
  {
    require('preload/employee.php');
    $dp_ourparams .= '<p>' . d_trad('employeewithparam:', $_SESSION['ds_term_clientemployee1']) . '&nbsp;' . d_output($employeeA[$employee1id]) . '</p>';
  }
}

if (isset($employee2id))
{
  if (($employee2id == 0) && ($dp_noshowempty == 0)) {
    $dp_ourparams .= '<p>' . d_trad('employeewithparam:', $_SESSION['ds_term_clientemployee2']) . '&nbsp;' . d_trad('empty') . '</p>';
  }
  elseif ($employee2id > 0) {
    require('preload/employee.php');
    $dp_ourparams .= '<p>' . d_trad('employeewithparam:', $_SESSION['ds_term_clientemployee2']) . '&nbsp;' . d_output($employeeA[$employee2id]) . '</p>';
  }
}

if (isset($clientcategoryid))
{
  if (($clientcategoryid == 0) && ($dp_noshowempty == 0))
  {
    $dp_ourparams .= '<p>' . d_trad('clientcategory:') . '&nbsp;' . d_trad('empty') . '</p>';
  }
  elseif ($clientcategoryid > 0)
  {
    require('preload/clientcategory.php');
    $dp_ourparams .= '<p>' . d_trad('clientcategory:') . '&nbsp;' . d_output($clientcategoryA[$clientcategoryid]) . '</p>';
  }
}

if (isset($clientcategory2id))
{
  if (($clientcategory2id == 0) && ($dp_noshowempty == 0))
  {
    $dp_ourparams .= '<p>' . d_trad('clientcategory2:') . '&nbsp;' . d_trad('empty') . '</p>';
  }
  elseif ($clientcategory2id > 0)
  {
    require('preload/clientcategory2.php');
    $dp_ourparams .= '<p>' . d_trad('clientcategory2:') . '&nbsp;' . d_output($clientcategory2A[$clientcategory2id]) . '</p>';
  }
}

if (isset($clientcategorygroupid))
{
  if (($clientcategorygroupid == 0) && ($dp_noshowempty == 0))
  {
    $dp_ourparams .= '<p>' . d_trad('clientcategorygroup:') . '&nbsp;' . d_trad('empty') . '</p>';
  }
  elseif ($clientcategorygroupid > 0)
  {
    require('preload/clientcategorygroup.php');
    $dp_ourparams .= '<p>' . d_trad('clientcategorygroup:') . '&nbsp;' . d_output($clientcategorygroupA[$clientcategorygroupid]) . '</p>';
  }
}

if (isset($clientcategorygroup2id))
{
  if (($clientcategorygroup2id == 0) && ($dp_noshowempty == 0))
  {
    $dp_ourparams .= '<p>' . d_trad('clientcategorygroup2:') . '&nbsp;' . d_trad('empty') . '</p>';
  }
  elseif ($clientcategorygroup2id > 0)
  {
    require('preload/clientcategorygroup2.php');
    $dp_ourparams .= '<p>' . d_trad('clientcategorygroup2:') . '&nbsp;' . d_output($clientcategorygroup2A[$clientcategorygroup2id]) . '</p>';
  }
}

if (isset($clienttermid) && $clienttermid  > 0)
{
  require('preload/clientterm.php');
  $dp_ourparams .= '<p>' . d_trad('clientterm:') . '&nbsp;' . d_output($clienttermA[$clienttermid]) . '</p>';
}

if (isset($invoicetagid) && $invoicetagid > 0)
{
  require('preload/invoicetag.php');
  $dp_ourparams .= '<p>' . $_SESSION['ds_term_invoicetag'] . ': ' . d_output($invoicetagA[$invoicetagid]);
  if (isset($exinvoicetag) && $exinvoicetag == 1) { $dp_ourparams .= ' (Exclu)'; }
  $dp_ourparams .= '</p>';
}

if (isset($reference) && $reference != '')
{
  $dp_ourparams .= '<p>' . $_SESSION['ds_term_reference'];
  if ($exreference == 1)
  {
    $dp_ourparams .= '&nbsp;' . d_trad('excluded');
  }
  $dp_ourparams .= ': ' . d_output($reference) . '</p>';
}

if (isset($extraname) && $extraname != '')
{
  $dp_ourparams .= '<p>' . $_SESSION['ds_term_extraname'];
  if ($exextraname == 1)
  {
    $dp_ourparams .= '&nbsp;' . d_trad('excluded');
  }
  $dp_ourparams .= ': ' . d_output($extraname) . '</p>';
}

if (isset($field1) && $field1 != '')
{
  $dp_ourparams .= '<p>' . $_SESSION['ds_term_field1'] . ': ' . d_output($field1) . '</p>';
}

if (isset($field2) && $field2 != '')
{
  $dp_ourparams .= '<p>' . $_SESSION['ds_term_field2'] . ': ' . d_output($field2) . '</p>';
}

if (isset($supplierterm) && $supplierterm != '')
{
  $dp_ourparams .= '<p>' . d_trad('supplier') . ': "' . $supplierterm . '"</p>';
}

if (isset($supplierid) && $supplierid > 0)
{
  $dp_ourparams .= '<p>' . d_trad('supplier') . ': ' . $suppliername . ' (' . $supplierid . ')';
  if (isset($excludesupplier) && $excludesupplier > 0) { $dp_ourparams .= ' exclu'; }
  $dp_ourparams .= '</p>';
}

if (isset($productdepartmentid) && $productdepartmentid > 0)
{
  require('preload/productdepartment.php');
  $dp_ourparams .= '<p>Département : ' . d_output($productdepartmentA[$productdepartmentid]) . '</p>';
}

if (isset($productfamilygroupid) && $productfamilygroupid > 0)
{
  require('preload/productdepartment.php');
  require('preload/productfamilygroup.php');
  $dp_ourparams .= '<p>Famille : ' . d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamilygroupid]]) . ' / ' . d_output($productfamilygroupA[$productfamilygroupid]) . '</p>';
}

if (isset($productfamilyid) && $productfamilyid > 0)
{
  require('preload/productdepartment.php');
  require('preload/productfamilygroup.php');
  require('preload/productfamily.php');
  $dp_ourparams .= '<p>Sous-famille : '. d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$productfamilyid]]]);
  $dp_ourparams .= ' / ' . d_output($productfamilygroupA[$productfamily_pfgidA[$productfamilyid]]);
  $dp_ourparams .= ' / ' . d_output($productfamilyA[$productfamilyid]) . '</p>';
}

if (isset($temperatureid) && $temperatureid > 0)
{
  require('preload/temperature.php');
  $dp_ourparams .= '<p>Température : ' . d_output($temperatureA[$temperatureid]) . '</p>';
}

if (isset($unittypeid) && $unittypeid > 0)
{
  require('preload/unittype.php');
  $dp_ourparams .= '<p>Type unité : ' . d_output($unittypeA[$unittypeid]) . '</p>';
}

if (isset($countryid) && $countryid > 0)
{
  require('preload/country.php');
  $dp_ourparams .= '<p>Pays : ' . d_output($countryA[$countryid]) . '</p>';
}

if (isset($producttypeid) && $producttypeid > 0)
{
  require('preload/producttype.php');
  $dp_ourparams .= '<p>Type de produit : ' . d_output($producttypeA[$producttypeid]) . '</p>';
}

if (isset($brand) && $brand != '')
{
  $dp_ourparams .= '<p>Marque : ' . d_output($brand) . '</p>';
}

if(isset($clientactioncatid) && $clientactioncatid  > 0) {
  require('preload/clientactioncat.php');
  $dp_ourparams .= '<p>Catégorie action : ' . d_output($clientactioncatA[$clientactioncatid]) . '</p>';
}

if (isset($paymentdatefield) && $paymentdatefield > 0)
{
  if ($paymentdatefield == $PAYMENT_DATE)
  {
    $paymentdatefield = d_trad('paymentdate');
  }
  elseif ($paymentdatefield == $DEPOSIT_DATE)
  {
    $paymentdatefield = d_trad('depositdate');
  }
  $dp_ourparams .= '<p>Date : ' . $paymentdatefield . '</p>';
	if ($paymentstartdate != '' && $paymentstopdate != '')
	{
		$dp_ourparams .= '<p>' . d_trad('between',array(datefix2($paymentstartdate),datefix2($paymentstopdate)));
	}
}

if(isset($paymenttypeid) && $paymenttypeid  > 0) {
  require('preload/paymenttype.php');
  $dp_ourparams .= '<p>' . d_trad('paymenttype:') . d_output($paymenttypeA[$paymenttypeid]) . '</p>';
}

if(isset($paymentcategoryid) && $paymentcategoryid  > 0) {
  require('preload/paymentcategory.php');
  $dp_ourparams .= '<p>' . d_trad('paymentcategory:') . d_output($paymentcategoryA[$paymentcategoryid]) . '</p>';
}

if(isset($bankid) && $bankid  > 0) {
  require('preload/bank.php');
  $dp_ourparams .= '<p>' . d_trad('bank:') . d_output($bankA[$bankid]) . '</p>';
}

if(isset($depositbankid) && $depositbankid  > 0) {
  require('preload/bank.php');
  $dp_ourparams .= '<p>' . d_trad('depositbank:') . d_output($bankA[$depositbankid]) . '</p>';
}

if(isset($reimbursement) && $reimbursement  > 0) {
  $dp_ourparams .= '<p>' . d_trad('reimbursements') . '</p>';
}

if (isset($starttime) && isset($stoptime) && $starttime != '' && $stoptime != '') {
  $dp_ourparams .= '<p>Heure : '.d_output($starttime).' à '.d_output($stoptime).'</p>';
}

if(isset($qualificationid) && $qualificationid > 0) {
  require('preload/qualification.php');
  $dp_ourparams .= '<p>Qualification: ' . d_output($qualificationA[$qualificationid]) . '</p>';
}

if (isset($competitorid) && $competitorid > 0)
{
  require('preload/competitor.php');
  $dp_ourparams .= '<p>Entreprise concurrente: ' . d_output($competitorA[$competitorid]) . '</p>';
}

if (isset($ig_boolean) && $ig_boolean >= 0)
{
  if ($ig_boolean) { $dp_ourparams .= '<p>Préparé: Oui</p>'; }
  else { $dp_ourparams .= '<p>Préparé: Non</p>'; }
}

if ($dp_ourparams == '')
{
  $dp_ourparams = '<br>';
}

echo $dp_ourparams;
?>