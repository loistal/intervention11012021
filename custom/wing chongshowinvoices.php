<STYLE type=text/css>

body {
  line-height: normal;
  background-color: #ffffff;
  color: black;
  font-family: Monaco, Courier New, monospace;
  font-size: <?php echo $itemfontsize; ?>%;
  text-align: justify;
}

table {
  margin-top: 0px;
  margin-left: 0px;
  font-family: Monaco, Courier New, monospace;
  font-size: <?php echo $itemfontsize; ?>%
}

table {
    border-collapse: collapse;
    white-space: nowrap;
}

table.report {
    border: 1px solid #000;
    background: white;
}

table.report th, table.report td, table.detailinput th, table.detailinput td {
    border: 1px solid #696969;
}

.logo {
  text-align: left;

  left: 550px;
  top: 0px
}

.logo2 {
  text-align: left;

  left: 500px;
  top: 108px
}

.newalert {
  text-align: left;
  

  left: 500px;
  top: 200px;
  border: 0;
  padding: 0.1em 0.2em 0.1em 0.1em;
  -moz-border-radius: 8px;
  -webkit-border-radius: 8px;
  -opera-border-radius: 8px;
  -khtml-border-radius: 8px;
  border-radius: 8px;
}
}

.tr1{

  top: 0px;
  left: 0px;
  width: auto;
  border: none
}

.tr2{

  top: 110px;
  left: 0px;
  width: auto;
  border: none
}

.tr3{

  top: 0px;
  left: 1000px;
  width: auto;
  border: none
}

.tr4{
  color: red;

  top: 78px;
  left: 1000px;
  width: auto;
  border: 3px solid red;
  padding: 0.1em 0.2em 0.1em 0.1em;
  font-size: 80%;
  -moz-border-radius: 8px;
  -webkit-border-radius: 8px;
  -opera-border-radius: 8px;
  -khtml-border-radius: 8px;
  border-radius: 8px;
}

.tr5{

  top: 180px;
  left: 850px;
  width: auto;
  border: none
}

.tr6{

  top: 232px;
  left: 200px;
  width: auto;
  border: none
}

.tr7{

  top: 268px;
  left: 20px;
  width: auto;
  border: none
}

.tr8 {

  top: 268px;
  left: 200px;
  width: auto;
  border: none;
  font-size: <?php echo $doublefontsize; ?>%;
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  filter: progid:DXImageTransform.Microsoft.Matrix(sizingMethod='auto expand',
        M11=1.5320888862379554, M12=-1.2855752193730787,
        M21=1.2855752193730796, M22=1.5320888862379558);
}

.items{

  top: 312px;
  left: 0px;
  width: auto;
  border: none
}

.small {
    font-size: 75%;
}


.dlogo {

  right: 0px;
  bottom: 0px;
  text-align: right;
  height: 80px
}

p {
  margin: 0.2em 0 0.2em 0;
  padding: 0;
}

</style>

<?php

### TODO option
$isnotice_only = 0;
if ($_SESSION['ds_clientaccess'] && $_SESSION['ds_customname'] == 'Wing Chong')
{
  $isnotice_only = 1;
}
###

require('preload/returnreason.php');
require('preload/country.php');
require('preload/employee.php');
require('preload/clientterm.php');
require('preload/localvessel.php');
require('preload/town.php');
require('preload/island.php');
require('preload/unittype.php');
require('preload/taxcode.php');
require('preload/regulationtype.php');
require('preload/regulationzone.php');
require('preload/producttype.php');
require('preload/invoicetag.php');
require('preload/deliverytype.php');
require('preload/clientcategory.php');
require('preload/clientcategory2.php');
require('preload/clientcategory3.php');

$query = 'select invoiceid,invoicehistory.clientid,clientname,companytypename,address,postaladdress,postalcode
,townid,clienttermid,proforma,isreturn,confirmed,isnotice,returnreasonid,accountingdate,paybydate,custominvoicedate
,localvesselid,invoicevat,invoiceprice,town_name,countryid,reference,invoicehistory.employeeid,extraname,invoicetagid
,invoicecomment,companytypename
from invoicehistory,client
where invoicehistory.clientid=client.clientid
and cancelledid=0 and confirmed=1';
if ($invoicetype == 1) { $query .= ' and isreturn=0'; }
elseif ($invoicetype == 2) { $query .= ' and isreturn=1'; }
elseif ($invoicetype == 3) { $query .= ' and proforma=1'; }
elseif ($invoicetype == 4) { $query .= ' and isnotice=1'; }
elseif ($invoicetype == 5) { $query .= ' and isreturn=1 and isnotice=1'; }
if ($invoice_grouped == 1) { $query .= ' and invoicegroupid=0'; }
elseif ($invoice_grouped == 2) { $query .= ' and invoicegroupid>0'; }
$query_prm = array();
if ($invoice_list != '') { $query .= ' and invoicehistory.invoiceid in '.$invoice_list; }
elseif ($bynumber) { $query .= ' and invoiceid>=? and invoiceid<=?'; array_push($query_prm, $startid); array_push($query_prm, $stopid); }
else { $query .= ' and accountingdate>=? and accountingdate<=?'; array_push($query_prm, $startdate); array_push($query_prm, $stopdate); }
if ($clientid > 0) { $query .= ' and invoicehistory.clientid=?'; array_push($query_prm, $clientid); }
if ($localvesselid > 0) { $query .= ' and invoicehistory.localvesselid=?'; array_push($query_prm, $localvesselid); }
if ($userid > 0) { $query .= ' and invoicehistory.userid=?'; array_push($query_prm, $userid); }
if (isset($invoice_field) && $invoice_field != '') { $query .= ' order by field'.$invoice_field; }
$query .= ' limit 1000';
require('inc/doquery.php');
$main_result_top = $query_result; $num_results_top = $num_results;

$PA['itemfontsize'] = '';
require('inc/readpost.php');
$pagenumber = 1;
$linesperpage= 1000;
if (isset($_POST['hidediscount'])) { $hidediscount = $_POST['hidediscount']+0; } else { $hidediscount = 0; }
if (isset($_POST['custominvoice_changefields'])) { $changefields = $_POST['custominvoice_changefields']+0; } else { $changefields = 0; }
if ($itemfontsize <= 0) { $itemfontsize = 100; }
if ($itemfontsize >= 1000) { $itemfontsize = 1000; }
for ($top=0; $top < $num_results_top; $top++)
{
unset($quantity2,$productid);
$invoiceid = $main_result_top[$top]['invoiceid'];
if ($top > 0) { echo '<p class=breakhere></p>'; }

$printinvoiceid = $invoiceid;
$doublefontsize = $itemfontsize * 2;

if ($printinvoiceid == 0) { echo 'La facture saisi n\'existe pas.'; exit; }

$showregulationcolumn = 0;

$query = 'select clientcategoryid,accountingdate,deliverytypeid,invoicetagid,invoicevat,proforma,clienttermid,isreturn,localvesselid
,townid,invoicehistory.employeeid,invoicetime,invoicedate,extraaddressid,extraname,isnotice,confirmed,invoiceid,invoiceprice as totalprice
,deliverydate,invoicehistory.clientid as clientid,initials,clientname,reference as proformareference,telephone,cellphone,fax,address
,postaladdress,postalcode,tahitinumber,rc,paybydate,companytypename,clientcategory2id,clientcategory3id';
$query = $query . ' from invoicehistory,usertable,client';
$query = $query . ' where invoicehistory.clientid=client.clientid and invoicehistory.userid=usertable.userid';
$query = $query . ' and cancelledid=0 and invoiceid=?';
if ($isnotice_only) { $query .= ' and isnotice=1';  }
if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and invoicehistory.clientid in ' . $_SESSION['ds_allowedclientlist']; }
if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' and (invoicehistory.userid="'.$_SESSION['ds_userid'].'"';
  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' or invoicehistory.employeeid="'.$_SESSION['ds_myemployeeid'].'"';
  }
  $query .= $queryadd.')';
}
$query_prm = array($printinvoiceid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $usetemptable = 1;
  $query = str_replace('invoicehistory', 'invoice', $query);
  require('inc/doquery.php');
}
if ($num_results == 0)
{
  $query = 'select invoicecomment from invoicehistory where invoiceid=?';
  $query_prm = array($printinvoiceid);
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<p>Facture '.$printinvoiceid.' est annulée. Commentaire: '.$query_result[0]['invoicecomment'].'</p>';
  }
}
unset($row);
$row = $query_result[0];
$paybydate = $row['paybydate'];
$clientcategoryid = $row['clientcategoryid'];
$clientcategory2id = $row['clientcategory2id'];
$clientcategory3id = $row['clientcategory3id'];
$isreturn = $row['isreturn']+0;
$confirmed = $row['confirmed']+0;
$invoicetime = $row['invoicetime'];
$invoicedate = $row['invoicedate'];
$accountingdate = $row['accountingdate'];
### Wing Chong custom   We would like to limit the access to Afficher Factures to current year +previous year only for all users except Direction
if (!$_SESSION['ds_systemaccess'])
{
  if ($_SESSION['ds_startyear'] > substr($accountingdate,0,4)) { echo 'ERRUER: RESTRICTION ANNEE'; exit; } # TODO re-enable
}
###
$extraname = $row['extraname'];
$deliveryagentid = $row['isnotice']+0;
$proformainvoice = $row['proforma']+0;
$invoiceid = $printinvoiceid;
$displayinvoiceid = $printinvoiceid;
$totalprice = $row['totalprice'];
$invoicevat = $row['invoicevat'];
$vesselname = $localvesselA[$row['localvesselid']];
$date = $row['deliverydate'];
$clientid = $row['clientid'];
$invoicername = $row['initials'];
$clientname = d_decode($row['clientname']);
$companytypename = $row['companytypename'];
$reference = $row['proformareference'];

$fax = $row['fax'];
if ($row['tahitinumber'] == "") { $tahitinumber = ""; }
else { $tahitinumber = 'No T ' . $row['tahitinumber']; }
if ($row['rc'] == "") { $rc = ""; }
else { $rc = 'RC ' . $row['rc']; }
$employeename = ''; if (isset($employeeA[$row['employeeid']])) { $employeename = $employeeA[$row['employeeid']]; }
$daystopay = $clienttermA[$row['clienttermid']];
$invoicetag = ''; if (isset($invoicetagA[$row['invoicetagid']])) { $invoicetag = $invoicetagA[$row['invoicetagid']]; }
$deliverytypename = $deliverytypeA[$row['deliverytypeid']];

#extraaddress
if ($row['extraaddressid'] == 0)
{
  $telephone = $row['telephone'];
  $mobile = $row['cellphone'];
  $address = $row['address'];
  $postaladdress = $row['postaladdress'];
  $postalcode = $row['postalcode'];
  $townname = $townA[$row['townid']];
  $islandid = $town_islandidA[$row['townid']];
}
else
{
  $query = 'select telephone,address,postaladdress,postalcode,townid from extraaddress where extraaddressid="' . $row['extraaddressid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row2 = $query_result[0];
  $telephone = $row2['telephone'];
  $mobile = '';
  $address = $row2['address'];
  $postaladdress = $row2['postaladdress'];
  $postalcode = $row2['postalcode'];
  $townname = $townA[$row2['townid']];
  $islandid = $town_islandidA[$row2['townid']];
}
$islandname = $islandA[$islandid];
$outerisland = $island_outerislandA[$islandid];
$regulationzoneid = $island_regulationzoneidA[$islandid];
$regulationzonerate = $regulationzone_regulationzonerateA[$regulationzoneid]+0;
$regulationzonerate = 1;

# read invoiceitems
$query = 'select invoiceitemhistory.currentpurchasebatchid,enteredpurchasebatchid,lineproducttypeid,rebate_type,regroupnumber
,invoiceitemhistory.invoiceitemid,linevat,invoiceitemhistory.retailprice,eancode,invoiceitemhistory.productid as productid
,producttypeid,suppliercode,givenrebate,basecartonprice,lineprice as totalprice,quantity,productname,brand,linetaxcodeid
,numberperunit,netweightlabel,unittypeid,product.regulationtypeid as regulationtypeid,netweight
from invoiceitemhistory,product,regulationtype
where invoiceitemhistory.productid=product.productid and product.regulationtypeid=regulationtype.regulationtypeid
and invoiceid=?';
$query_prm = array($invoiceid);
$query .= ' order by regroupnumber asc,invoiceitemhistory.productid,invoiceitemhistory.invoiceitemid';
if (isset($usetemptable) && $usetemptable) { $query = str_replace('invoiceitemhistory', 'invoiceitem', $query); }
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$totalpages = ceil($num_results_main/$linesperpage);
$showrebate = 0; $vattotal = 0; unset($vattotalA); unset($vatbasetotalA); $vattotalA = array(); $vatbasetotalA = array();
$has_insurance = 0; $has_freight = 0;
for ($i=0; $i < $num_results_main; $i++)
{
  $change_prix_ht = 0;
  $quantity2[$i] = ""; $numberperunit[$i] = ""; $salesunit[$i] = ""; $productname[$i] = ""; $taxcode2[$i] = "";
  $packaging[$i] = ""; $price2[$i] = ""; $priceperunit2[$i] = ""; $showregulation[$i] = ""; $RPT[$i] = ""; $RPI[$i] = "";
  $RPTvat[$i] = ""; $RPIvat[$i] = "";
  $row = $main_result[$i];
  if ($row['productid'] == 4203 || $row['productid'] == 4206) { $has_freight = 1; }
  if ($row['productid'] == 4204) { $has_insurance = 1; }
  $epbid[$i] = $row['enteredpurchasebatchid'];
  if ($epbid[$i] == 0 && $row['currentpurchasebatchid'] > 0) { $epbid[$i] = $row['currentpurchasebatchid']; }
  $invoiceitemid[$i] = $row['invoiceitemid'];
  $netweight[$i] = $row['netweight'];
  $productid[$i] = $row['productid'];
  $retailprice = $row['retailprice']+0;
  $netdecimals[$i] = 0; # number of decimals for prix details
  $eancode[$i] = $row['eancode'];
  $suppliercode[$i] = $row['suppliercode'];
  $specialchar[$i] = "";
  $producttypeid = $row['producttypeid'];
  ###
  if ($row['lineproducttypeid'] > 0) { $producttypeid = $row['lineproducttypeid']; }
  else
  {
    # backwards comp
    $query = 'select lineproducttypeid from invoiceitemadd where invoiceitemid=?'; # slow but necessary
    $query_prm = array($invoiceitemid[$i]);
    require('inc/doquery.php');
    if ($num_results && $query_result[0]['lineproducttypeid'] > 0) { $producttypeid = $query_result[0]['lineproducttypeid']; }
  }
  ###
  if (isset($producttypeA[$producttypeid])) { $producttype[$i] = $producttypeA[$producttypeid]; } else { $producttype[$i] = ''; }
  #$unittypename[$i] = $unittypeA[$row['unittypeid']];
  if (isset($unittype_dmpA[$row['unittypeid']])) { $dmp[$i] = $unittype_dmpA[$row['unittypeid']]; } else { $dmp[$i] =  1; }
  #if ($row['producttypeid'] == 1) { $specialchar[$i] = "+"; } # PPN
  #if ($row['producttypeid'] == 2) { $specialchar[$i] = "*"; } # PGL
  #if ($row['producttypeid'] == 8) { $specialchar[$i] = "#"; } # PGC
  $quantity2[$i] = $row['quantity'];
  $orig_quantity2[$i] = $row['quantity'];
  $rebate_type[$i] = $row['rebate_type'];
  $numberperunit[$i] = $row['numberperunit'];
  $packaging[$i] = $row['numberperunit'] . 'x' . $row['netweightlabel'];
  $basecartonprice[$i] = $row['basecartonprice']+0;
  $testvar = $quantity2[$i] - (floor($quantity2[$i] / $numberperunit[$i]) * $numberperunit[$i]);
  if ($testvar == 0)
  {
    $quantity2[$i] = $quantity2[$i] / $numberperunit[$i];
    $salesunit[$i] = $unittypeA[$row['unittypeid']];
  }
  else
  {
    $salesunit[$i] = "unité";
    $change_prix_ht = 1; # 2016 12 19 new, divide price
    #$basecartonprice[$i] = $basecartonprice[$i] / $row['numberperunit']; 2013 01 17
    #$basecartonprice[$i] = $basecartonprice[$i] / $row['numberperunit']; # 2013 01 22 back on, see invoice 5097, shows 2% instead of 100% remise for pid 3333
    # 2013 01 23 still does not work, need to recalculate below
  }
  if ($numberperunit[$i] == 1 || $testvar != 0) { $packaging[$i] = $row['netweightlabel']; }
  $productname[$i] = d_decode($row['productname']);
  if ($dmp[$i] != 1)
  {
    $quantity2[$i] = $quantity2[$i] / $dmp[$i];
    $basecartonprice[$i] = $basecartonprice[$i] * $dmp[$i];
  }
  
  $price2[$i] = $row['totalprice']+0;
  #$total = $total + $price2[$i];
  $givenrebate[$i] = $row['givenrebate']+0; if ($givenrebate[$i] > 0) { $showrebate = 1; }
  $orig_givenrebate[$i] = $row['givenrebate']+0;
  $regulationtypeid = $row['regulationtypeid'];
  $showasterix = $regulationtype_showasterixA[$regulationtypeid];
  # new vat calc
  $taxcode2[$i] = $taxcodeA[$row['linetaxcodeid']]; # IMPORTANT use line saved taxcode, not current product taxcode
  $taxcodeid = $row['linetaxcodeid'];
  $vattotal = $vattotal + $row['linevat'];
  if (!isset($vattotalA[$taxcodeid])) { $vattotalA[$taxcodeid] = 0; $vatbasetotalA[$taxcodeid] = 0; }
  $vattotalA[$taxcodeid] = $vattotalA[$taxcodeid] + $row['linevat'];
  $vatbasetotalA[$taxcodeid] = $vatbasetotalA[$taxcodeid] + $row['totalprice'];

  ### RETAIL PRICE CALCULATION ###

  $showregulation[$i] = 0;
  
  if ($producttype[$i] == 'PGL' && $outerisland == 0) { $showasterix = 0; }

  if ($showasterix)
  {
    $showregulation[$i] = 1; $showregulationcolumn = 1;
    
    ### backwards compat
    if ($retailprice == 0)
    {
      $query = 'select retailprice from invoiceitemadd where invoiceitemid="'.$invoiceitemid[$i].'"';
      $query_prm = array($invoiceitemid[$i]);
      require('inc/doquery.php');
      if ($num_results > 0)
      {
        $row3 = $query_result[0];
        if ($row3['retailprice'] > 0)
        {
          $retailprice = $row3['retailprice']+0;
        }
      }
      else
      {
        $query = 'select retailprice from clientpricing where productid="' . $productid[$i] . '" and clientid="' . $clientid . '" and retailprice > 0 and fromdate <= curdate() and todate >= curdate()';
        $query_prm = array($invoiceitemid[$i]);
        require('inc/doquery.php');
        if ($num_results > 0)
        {
          $row3 = mysql_fetch_array($result3);
          $retailprice = $row3['retailprice']+0;
        }
      }
    }
    ###
    
    $RPT[$i] = round(($retailprice * $dmp[$i]) / $numberperunit[$i],$netdecimals[$i]);
    
    $query = 'select freightpriceperkilo,regulationmargin from regulationmatrix where regulationtypeid=? and regulationzoneid=?';
    $query_prm = array($regulationtypeid,$regulationzoneid);
    require('inc/doquery.php');
    $row2 = $query_result[0];
    $fppk = $row2['freightpriceperkilo']+0;
    $rm = $row2['regulationmargin']+0;

    $RPI[$i] = round((($RPT[$i] * $regulationzonerate) + $fppk) * $rm,$netdecimals[$i]);
    $RPTvat[$i] = round($RPT[$i] * (1 + ($taxcode2[$i]/100)),$netdecimals[$i]);
    $RPIvat[$i] = round($RPI[$i] * (1 + ($taxcode2[$i]/100)),$netdecimals[$i]);

    if ($islandname == "Tahiti") { $RPI[$i] = ""; $RPIvat[$i] = ""; }
    if ($producttype[$i] == 'PGL') { $RPT[$i] = ""; $RPTvat[$i] = ""; }
    #if ($clientcategoryid == 32) { $RPT[$i] = ""; $RPTvat[$i] = ""; } # email 2019 10 02 finally they dont want it
  }
  
  ### END RETAIL PRICE CALCULATION ##

}
if ($hidediscount == 1) { $showrebate = 0; }
if (isset($clientcategory3_groupidA[$clientcategory3id]) && $clientcategory3_groupidA[$clientcategory3id] == 1) # Wing Chong dirty data
{ $showregulationcolumn = 0; }
/*
$typetext = 'Facture ';
if (isset($row['proforma']) && $row['proforma'] == 1) { $typetext = 'Proforma '; }
if (isset($isnotice) && $isnotice) { $typetext = $_SESSION['ds_term_invoicenotice']; }
if (isset($row['isreturn']) && $row['isreturn'] == 1) { $typetext = 'Avoir '; }
showtitle($typetext . $invoiceid);*/

echo '<table class="transparent" width=1600px><tr><td width=30%>';
echo 'Comptes&nbsp;bancaires&nbsp;:<br>BP&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12149-06746-10350101016-77<br>BT&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;12239-00001-20248001000-19<br>CCP&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;14168-00001-8050205S068-65<br>SOCREDO&nbsp;:&nbsp;17469-00016-50140300043-10';
echo '<br>Date&nbsp;facture:&nbsp;&nbsp;&nbsp;&nbsp;'.datefix($accountingdate);

echo '<td width=25%>&nbsp;';
$ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
if (file_exists($ourlogofile)) { echo '<p><img src="' . $ourlogofile . '"></p>'; }
else { echo '<br>WING CHONG<br>'; }

echo '<td valign=top width=45%>';
if ($_SESSION['ds_showtimeprinted']==1) { echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.datefix2($_SESSION['ds_curdate']).' '.$_SESSION['ds_curtime'].' par '.d_output($_SESSION['ds_initials']); }
echo '<br><br>';
if ($vesselname != '') { echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Navire&nbsp;:&nbsp;' . $vesselname; }
if ($totalpages > 1) { echo '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page '.$pagenumber.' / '.$totalpages; }

echo '<tr><td>';
$kladd = 'Facture';
if ($isreturn == 1) { $kladd = 'Avoir&nbsp;&nbsp;'; }
echo '<b>' . $kladd.'&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $displayinvoiceid . '</b>';
echo '<br>Date&nbsp;livraison&nbsp;&nbsp;&nbsp;'.datefix($date);
if ($outerisland)
{
  if ($has_insurance && $has_freight) { echo '&nbsp;CFR&nbsp;Quai&nbsp;'.$islandname; }
  elseif ($has_insurance == 0 && $has_freight == 0) { echo '&nbsp;FAS&nbsp;PPT'; }
}
echo '<br>Par&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicername;
### 2013 10 01
echo '&nbsp;' . datefix2($invoicedate) . ' ' . substr($invoicetime,0,5);
###
$kladd  = d_output($_SESSION['ds_term_reference']);
echo '<br>'.$kladd.'&nbsp;:';
$loopme = 15 - mb_strlen($kladd);
for ($i = 0;$i<$loopme;$i++) { echo '&nbsp;'; }
# 2013 02 18 what to do when field too long?
if (mb_strlen($reference)>30)
{
  echo mb_substr($reference,0,30).'<br>';
  for ($i = 0;$i<16;$i++) { echo '&nbsp;'; }
  echo mb_substr($reference,30,30);
}
else { echo $reference; }
$kladd  = d_output($_SESSION['ds_term_servedby']);
echo '<br>'.$kladd.'&nbsp;:';
$loopme = 15 - mb_strlen($kladd);
for ($i = 0;$i<$loopme;$i++) { echo '&nbsp;'; }
echo $employeename;
echo '<br>Délai&nbsp;paiement&nbsp;:&nbsp;' . $daystopay;
if ($paybydate != $accountingdate) { echo '<br>Echéance&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . datefix($paybydate,'short'); }
$kladd  = ''; # no name or descpriotn for deliverytype
echo '<br>'.$kladd.'&nbsp;&nbsp;';
$loopme = 15 - mb_strlen($kladd);
for ($i = 0;$i<$loopme;$i++) { echo '&nbsp;'; }
echo '<b>' . $deliverytypename . '</b>'; # bold by request 2014 06 20

echo '<td>';
echo 'Fare Ute Papeete - TAHITI - BP 230<br>RC 620 B - No TAHITI 044 016<br>E-mail : commande@wico.pf<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@wico.pf<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;compta@wico.pf';
echo '<br>Tél. 40 543 543<br>
Comptabilité : 40 54 35 41 ou 42<br>
Fax : 444 777 (Gratuit)<font size=-1> - 40 543 540</font>';

echo '<td>';
echo 'Compte&nbsp;&nbsp;' . $clientid . '&nbsp;&nbsp;&nbsp;' . d_output($rc) . '&nbsp;' . d_output($tahitinumber);
echo '<br>Client&nbsp;&nbsp;<b>' . d_output($clientname) . '</b>';
if ($extraname != '') { echo '&nbsp;' . d_output($extraname); }
if ($companytypename != '') { echo '&nbsp;'.d_output($companytypename); }
echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
if ($telephone != '') { echo 'T:&nbsp;'.d_output(mb_substr($telephone,0,20)); }
if ($mobile != '') { echo '&nbsp;V:&nbsp;' . d_output(mb_substr($mobile,0,20)); }
if ($fax != '') { echo '&nbsp;F:&nbsp;' . d_output(mb_substr($fax,0,20)); }
echo '<br>Adresse&nbsp;' . d_output($address);
echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.d_output($postaladdress);
echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.d_output($postalcode) . '&nbsp;' . d_output($townname);
echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.d_output($islandname);

echo '</table>';

/*
echo '<div class="tr4">
Ouvert Samedis 16, 23, 30 Déc:<br>&nbsp;&nbsp;&nbsp;08h00-12h00<br>
Fermé Inventaire Mardi 02 au<br>&nbsp;&nbsp;&nbsp;Vendredi 05 Janvier 2018<br>
Réouverture LUNDI 08 JAN 2018
</div>';
*/

echo '<div class="tr6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
if ($isreturn == 1) { echo 'A&nbsp;V&nbsp;O&nbsp;I&nbsp;R'; }
if ($deliveryagentid > 0) { echo '&nbsp;&nbsp;BON&nbsp;de&nbsp;LIVRAISON'; }
if ($proformainvoice == 1 && $confirmed == 0) 
{
  echo '<b>P&nbsp;R&nbsp;O&nbsp;F&nbsp;O&nbsp;R&nbsp;M&nbsp;A</b>';
  # 2013 06 26 as per email from Jimmy
  echo '<b>' . str_replace(" ", "&nbsp;", '  V A L I D E  P O U R  24  H E U R E S') . '</b>';
}
echo '</div>';

echo '<div class="tr7">';
echo 'Cette facture tient lieu de bon de livraison.';
echo '</div>';

if ($confirmed == 0) { echo '<div class="tr8">&nbsp;&nbsp;&nbsp;NON&nbsp;CONFIRMEE</div>'; }

echo '<div class="items"><table class="report">';
echo '<tr><td>&nbsp;&nbsp;</td><td>Réf&nbsp;</td><td>Quantité&nbsp;&nbsp;</td><td>Désignation&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
if ($showrebate) { echo '</td><td>Remise</td><td>OP'; $descr_length = 28; }
else { echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; $descr_length = 34; }
echo '</td><td>Cond.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
if ($deliveryagentid == 0)
{
  echo '<td>TVA</td><td>Prix&nbsp;HT</td><td><font size=-1>UHT NET</font>';
  if (isset($clientcategory3_groupidA[$clientcategory3id]) && $clientcategory3_groupidA[$clientcategory3id] == 1) # Wing Chong dirty data
  { echo '<td><font size=-1>PRIX HT/KG</font>'; }
  echo '<td>Total&nbsp;HT</td>';
  if ($changefields) { echo '<td colspan=5>Lot fournisseur'; }
  elseif ($showregulationcolumn) { echo '<td colspan=5 STYLE="font-size:68%">Prix détail (T.HT/T.TTC/Î.HT/Î.TTC)</td>'; } # 
}
echo '<td>EAN<td>Code F';
if ($deliveryagentid != 0) { echo '<td>Batchcode'; }
$startitem = (($pagenumber-1)*$linesperpage); $stopitem = ($pagenumber * $linesperpage) - 1;
for ($i=$startitem; $i <= $stopitem; $i++)
{
  if ((isset($productid[$i]) && $productid[$i] > 0) || (isset($quantity2[$i]) && $quantity2[$i] > 0)) # only show lines with a product
  {
    #$countlines++;
    echo '<tr><td align=right>'.($i+1).'</td><td align=right>'.$productid[$i].'</td>';
    
    $showquantity = $quantity2[$i]; if ($quantity2[$i] < 100) { $showquantity = '&nbsp;'.$showquantity; } if ($quantity2[$i] < 10) { $showquantity = '&nbsp;'.$showquantity; }
    $showquantity .= '&nbsp;<b>'.mb_substr($salesunit[$i],0,6).'</b>';
    #if ($dmp[$i] != 1)
    #{
    #  $showquantity = ($quantity2[$i] / $dmp[$i]) . '&nbsp;kg&nbsp;&nbsp;&nbsp;&nbsp;';
    #}
    echo '<td align=right>'.$showquantity.'</td>';
    echo '<td>'.mb_substr(d_output($productname[$i]),0,$descr_length).'</td>';
    if ($showrebate)
    {
      # converting to % and two decimals   2013 01 10 no decimals    2013 01 29 1 decimal  2013 01 30 back to no decimals
      if ($rebate_type[$i] != 2)
      {
        if ($givenrebate[$i] > 0)
        {
          #$givenrebate[$i] = round(100*$givenrebate[$i]/$price2[$i],1).'%';
          $bcpdivider = myround($basecartonprice[$i]); if ($bcpdivider == 0) { $bcpdivider = 1; }
          $givenrebate[$i] = (100*$givenrebate[$i]/$bcpdivider)/$quantity2[$i];
          if ($salesunit[$i] == 'unité') { $givenrebate[$i] *= $numberperunit[$i]; } # TODO use id not "unité"
          if ($price2[$i] == 0) { $givenrebate[$i] = '100'; }
          $givenrebate[$i]  = myround($givenrebate[$i],0).'%';
        }
        else { $givenrebate[$i] = ''; }
      }
      $pofield[$i] = '';
      if ($rebate_type[$i] == 2)
      {
        if ($salesunit[$i] == 'unité') { $x = $givenrebate[$i] / ($basecartonprice[$i] / $numberperunit[$i]); } # TODO use id not "unité"
        else { $x = $quantity2[$i] - ($price2[$i] / $basecartonprice[$i]); }
        $givenrebate[$i] = $x . ' '. mb_substr($salesunit[$i],0,6);
      }
      if ($producttype[$i] == 'PPN' || $producttype[$i] == 'PGC' || ($producttype[$i] == 'PGL' && $outerisland == 1) || $producttype[$i] == 'PAO')
      {
        $basecartonprice[$i] = myround(($price2[$i] / $quantity2[$i]),3);
        $basecartonprice[$i] = rtrim($basecartonprice[$i], '0');
        $basecartonprice[$i] = rtrim($basecartonprice[$i], '.');
        $pofield[$i] = rtrim($givenrebate[$i], "%");
        $givenrebate[$i] = '';
      }
      echo '<td align=right>'.$givenrebate[$i].'</td>';
      if ($pofield[$i] == '0') { $pofield[$i] = ''; }
      echo '<td align=right>'.$pofield[$i].'</td>';
    }
    echo '<td align=right>'.mb_substr(d_output($packaging[$i]),0,11).'</td>';
    if ($deliveryagentid == 0)
    {
      if ($taxcode2[$i] != '') { echo '<td align=right>'.$taxcode2[$i].'%</td>'; }
      else { echo '<td></td>'; }
      if ($change_prix_ht == 1) { echo '<td align=right></td>'; }
      else { echo '<td align=right>'.$basecartonprice[$i].'</td>'; }
      echo '<td align=right>'.(myround($dmp[$i]*($price2[$i]/$orig_quantity2[$i]),3)+0);
      if (isset($clientcategory3_groupidA[$clientcategory3id]) && $clientcategory3_groupidA[$clientcategory3id] == 1) # Wing Chong dirty data
      { echo '<td align=right>',myround((1000*$basecartonprice[$i]/$numberperunit[$i])/$netweight[$i]); }
      echo '<td align=right>'.myround($price2[$i]).'</td>';
      if ($showregulationcolumn)
      {
        if ($changefields)
        {
          $query = 'select supplierbatchname from purchasebatch where purchasebatchid=?';
          $query_prm = array($epbid[$i]);
          require('inc/doquery.php');
          echo '<td colspan=5>',d_output($query_result[0]['supplierbatchname']);
        }
        elseif ($showregulation[$i])
        {
          echo '<td>'.$producttype[$i].'</td>';
          echo '<td align=right>'.$RPT[$i].'</td>';
          echo '<td align=right>'.$RPTvat[$i].'</td>';
          echo '<td align=right>'.$RPI[$i].'</td>';
          echo '<td align=right>'.$RPIvat[$i].'</td>';
        }
        else
        {
          echo '<td colspan=5>';
          #if ($productid[$i] > 0) { echo 'Libre'; }
          echo '</td>';
        }
      }
    }
    if ($deliveryagentid != 0 && $productid[$i] == 4206)
    {
      echo '<td colspan=2 align=right>Prix '.myround($price2[$i]);
    }
    else
    {
      echo '<td>'.$eancode[$i];
      echo '<td>'.$suppliercode[$i];
    }
    if ($deliveryagentid != 0)
    {
      echo '<td>';
      /* # disabled for now
      $showbatchnamesA = array();
      $query = 'select supplierbatchname
      from pallet_exit,pallet
      where pallet_exit.palletid=pallet.palletid
      and invoiceitemid=?';
      $query_prm = array($invoiceitemid[$i]);
      require('inc/doquery.php');
      if ($num_results && 1==0)
      {
        for ($y=0; $y <= $num_results; $y++)
        {
          $showbatchnamesA[] = $query_result[0]['supplierbatchname'];
        }
        $showbatchnamesA = array_unique($showbatchnamesA);
        foreach ($showbatchnamesA as $showbatchname)
        {
          echo ' ',$showbatchname;
        }
      }
      else
      {*/
        $query = 'select supplierbatchname from purchasebatch where purchasebatchid=?';
        $query_prm = array($epbid[$i]);
        require('inc/doquery.php');
        echo d_output($query_result[0]['supplierbatchname']);
      /*}*/
    }
    echo '</tr>';
  }
}
if ($deliveryagentid == 0)
{
  if ($pagenumber == $totalpages)
  {
    $colspan = 4; if ($showrebate) { $colspan++;$colspan++; }
    echo '<tr><td></td><td colspan='.$colspan.'>';
    if ($vattotal > 0 && $deliveryagentid == 0)
    {
      echo '<table class="report"><tr><td>Taux&nbsp;TVA</td><td>Base&nbsp;HT</td><td>Montant&nbsp;TVA</td></tr>';
      foreach ($taxcodeA as $taxcodeid => $taxcode)
      {
        if (isset($vattotalA[$taxcodeid]) && $vattotalA[$taxcodeid] > 0)
        {
          echo '<tr><td align=right>' . $taxcode . ' %</td><td align=right>' . myfix($vatbasetotalA[$taxcodeid]) . '</td><td align=right>' . myfix($vattotalA[$taxcodeid]) . '</td></tr>';
        }
      }
      echo '</table>';
    }
    echo'<td colspan=2 valign=top>';
    echo '<table class="report"><tr><td><b>';
    echo 'Total HT<br>';
    echo 'Total TTC';
    echo '</b></td></tr></table>';
    if (isset($clientcategory3_groupidA[$clientcategory3id]) && $clientcategory3_groupidA[$clientcategory3id] == 1) # Wing Chong dirty data
    { echo '<td colspan=3 valign=top align=right>'; }
    else { echo '<td colspan=2 valign=top align=right>'; }
    echo '<table class="report"><tr><td align=right><b>';
    echo myfix($totalprice-$invoicevat).'<br>';
    echo myfix($totalprice);
    echo '</table>';
  }
}
if ($deliveryagentid == 0) { echo '<td colspan=7></td>'; }
echo '</table>';

if ($clientcategoryid == 11 || $clientcategoryid == 6 || $clientcategoryid == 2 || $clientcategoryid == 1 || $clientcategoryid == 9) # 2014 07 30 Administration; Armée; Communes; Ecole, collège lycée; Hopital
{
  if ($totalpages == 1 || ($totalpages > 1 && $pagenumber == $totalpages))
  {
    if ($proformainvoice == 0 && $deliveryagentid == 0)
    {
      require_once('inc/fulltextcurrency_func.php');
      if ($isreturn == 1)
      {
        echo '<p><b>Arrêté le présent avoir à la somme de : ' . convertir($totalprice) . ' CFP.</b></p>';
      }
      else
      {
        echo '<p><b>Arrêté la présente facture à la somme de : ' . convertir($totalprice) . ' CFP.</b></p>';
      }
    }
  }
}

echo '<p class=small>'.$_SESSION['ds_infofact'].'</p>';
echo '</div>';

if ($islandname == 'Tahiti' || $islandname == 'Moorea')
{
  if ($totalpages == 1 || ($totalpages > 1 && $pagenumber == $totalpages))
  {
    echo '<style>
    .signme {
      border: 3px solid;
      padding: 1em;
      -moz-border-radius: 8px;
      -webkit-border-radius:8px;
      -opera-border-radius:8px;
      -khtml-border-radius:8px;
      border-radius: 8px;
      width: 350px;
    }
    </style>
    <table class="transparent" width=1600px><tr><td width=50%>
    <td>
    <div class="signme">
    <font size=-1><b>Accusé Réception des Marchandises</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <br><font size=-1>Date / Heure :</font>
    <br><font size=-1>Nom, Prénom :</font>
    <br><font size=-1>Signature client</font>
    </div>
    <td><img src="pics/logo.png" height="50">
    </table>';
  }
}

}
?>
