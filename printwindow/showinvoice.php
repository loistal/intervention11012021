<?php

# need major refactor

$informationTotalPages = '';

$PA['pagenumber'] = 'uint';
$PA['linesperpage'] = 'int';
$PA['template'] = 'int';
$PA['invoiceid'] = 'uint';
$PA['hidediscount'] = 'uint';
$PA['hideprices'] = 'uint';
$PA['invoicemerge'] = ''; # there is an int_list type TODO use it
require('inc/readpost.php');

if ($pagenumber < 1)
{
  $pagenumber = 1;
}

$ds_customname = strtolower($_SESSION['ds_customname']);
$showcustom = 0;
if ($template == 0) 
{
  if ($_SESSION['ds_custominvoiceisdefault'] && file_exists('custom/' . $ds_customname . 'showinvoice.php')) { $showcustom = 1; $template = 99; }
  else { $template = $_SESSION['ds_invoicetemplate']; }
}
elseif ($template >= 99)
{
  $showcustom = 1;
}

### TODO option
$isnotice_only = 0;
if ($_SESSION['ds_clientaccess'] && $_SESSION['ds_customname'] == 'Wing Chong')
{
  $isnotice_only = 1;
}
###

if ($linesperpage < 1)
{
  $linesperpage = $_SESSION['ds_invoicelines'];
}


if (isset($_POST['shareinvoice']) && $_POST['shareinvoice'] == 1 && $_SESSION['ds_allowinvoiceshare'] == 1 && $token == '')
{
  $token = md5(uniqid(mt_rand(), TRUE));

  $query = 'SELECT token FROM invoiceshare WHERE invoiceid = ?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');

  if ($num_results)
  {
    $query = 'UPDATE invoiceshare SET token = ?, instancename = ?, userid = ?, showcustom = ?, template = ? WHERE invoiceid = ?';
  }
  else
  {
    $query = 'INSERT INTO invoiceshare (token, instancename, userid, showcustom, template, invoiceid) values (?, ?, ?, ?, ?, ?)';
  }

  $query_prm = array(
    $token,
    $dauphin_instancename,
    $_SESSION['ds_userid'],
    $showcustom,
    $template,
    $invoiceid
  );

  require('inc/doquery.php');
  print '<META http-equiv="refresh" content="0;URL=printwindow.php?report=showinvoice&invoiceid=' . $invoiceid . '&instancename=' . $dauphin_instancename . '&token=' . $token . '&linesperpage=' . $linesperpage . '">';

  exit;
}

if ($showcustom)
{ 
  switch($template)
  {
    case 100:
      $filename = 'custom/' . $ds_customname  .'showinvoice0.php';
      //$isretail=1;       
      break;
    case 101:
      $filename = 'custom/' . $ds_customname  .'showinvoice1.php';
      //$isretail=0;         
      break;
    default:
      $filename = 'custom/' . $ds_customname . 'showinvoice.php';
  }
  if ($ds_customname != "" && file_exists($filename))
  {
    require($filename);
  }
  exit;
}

require('preload/invoicetag.php');
require('inc/fulltextcurrency_func.php');
require('preload/taxcode.php');
require('preload/localvessel.php');
require('preload/employee.php');
require('preload/town.php');
require('preload/island.php');
require('preload/returnreason.php');
require('preload/bank.php');
require('preload/country.php');
require('preload/advance.php');

$usehistory = 0;
$totaltva = 0;
$totalht = 0;
$subtotal = 0;
$subtotal_lines = 0;

### TODO options
$split_quantity = 0;
$invoice_title_below = 0;
$narrow_lines = 0;
$show_idtahiti = 1; if ($_SESSION['ds_customname'] == 'SARL TEHEI') { $show_idtahiti = 0; }
$summary_top = 1;
if ($template == 7 && ($_SESSION['ds_customname'] == 'TERE UTA' || $_SESSION['ds_customname'] == 'MAT RIGGING SERVICES'))
{
  $invoice_title_below = 1;
}
if ($template == 7 && $_SESSION['ds_customname'] == 'Espace Paysages')
{
  $split_quantity = 1;
  $invoice_title_below = 1;
  $narrow_lines = 1;
  $show_idtahiti = 0;
  $summary_top = 0;
}
###

$tvaM = array();
$tvaMt = array();

$query = 'select idtahiti,rc from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  $idtahiti = $query_result[0]['idtahiti'];
  $rc = $query_result[0]['rc'];
}
else { $rc = $idtahiti = ''; }

$query = 'select town_name,countryid,localvesselid,invoice.employeeid,invoicevat,field1,field2,townid,invoice.userid,invoicetagid,vatexempt,isnotice,contact,
          invoice.clientid,clientname,extraname,accountingdate,custominvoicedate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,
          proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,username,client_customdate1,
          cellphone,email,fax,extraaddressid,clienttermname,confirmed,matchingid,loyalty_start,use_loyalty_points,returnreasonid,invoicedate,invoicetime,advanceid
          ,clientfield1,clientfield2,clientfield3,clientfield4,clientfield5,clientfield6
          FROM invoice,client,usertable,clientterm
          WHERE invoice.clientid = client.clientid
          AND invoice.userid = usertable.userid
          AND client.clienttermid = clientterm.clienttermid
          AND invoice.invoiceid = ?';

$query_prm = array();
$query_prm[] = $invoiceid;

if ($isnotice_only) { $query .= ' and isnotice=1';  }

if ($_SESSION['ds_clientaccess'] == 1)
{
  $query = $query . ' AND client.clientid = ?';
  $query_prm[] = $_SESSION['ds_userid'];
}

if ($_SESSION['ds_allowedclientlist'] != '')
{
  $query = $query . ' AND invoice.clientid IN ' . $_SESSION['ds_allowedclientlist'];
}

if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' AND (invoice.userid = ?';
  $query_prm[] = $_SESSION['ds_userid'];

  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' OR invoice.employeeid = ?';
    $query_prm[] = $_SESSION['ds_myemployeeid'];
  }

  $query .= $queryadd . ')';
}

require('inc/doquery.php');

if (!$num_results)
{
  $usehistory = 1;

  $query = 'SELECT town_name,countryid,localvesselid,invoicehistory.employeeid,invoicevat,field1,field2,townid,invoicehistory.userid,invoicetagid,vatexempt,isnotice,contact,
            invoicehistory.clientid,clientname,extraname,accountingdate,custominvoicedate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,
            proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,username,client_customdate1,
            cellphone,email,fax,extraaddressid,clienttermname,confirmed,matchingid,loyalty_start,use_loyalty_points,returnreasonid,invoicedate,invoicetime,advanceid
            ,clientfield1,clientfield2,clientfield3,clientfield4,clientfield5,clientfield6
            FROM invoicehistory,client,usertable,clientterm
            WHERE invoicehistory.clientid = client.clientid
            AND invoicehistory.userid = usertable.userid
            AND client.clienttermid = clientterm.clienttermid
            AND invoicehistory.invoiceid = ?';
            
  $query_prm = array();
  $query_prm[] = $invoiceid;
  
  if ($isnotice_only) { $query .= ' and isnotice=1'; }

  if ($_SESSION['ds_clientaccess'] == 1)
  {
    $query = $query . ' AND client.clientid = ?';
    $query_prm[] = $_SESSION['ds_userid'];
  }

  if ($_SESSION['ds_allowedclientlist'] != '')
  {
    $query = $query . ' AND invoicehistory.clientid IN ' . $_SESSION['ds_allowedclientlist'];
  }

  if ($_SESSION['ds_confirmonlyown'] == 1)
  {

    $queryadd = ' AND (invoicehistory.userid = ?';
    $query_prm[] = $_SESSION['ds_userid'];

    if ($_SESSION['ds_myemployeeid'] > 0)
    {
      $queryadd .= ' OR invoicehistory.employeeid = ?';
      $query_prm[] = $_SESSION['ds_myemployeeid'];
    }

    $query .= $queryadd . ')';
  }

  require('inc/doquery.php');
}

if (!$num_results)
{
  echo '<p class="alert">Facture inexistante.</p>';
  exit;
}

$row = $query_result[0];
$clientfield1 = $row['clientfield1'];
$clientfield2 = $row['clientfield2'];
$clientfield3 = $row['clientfield3'];
$clientfield4 = $row['clientfield4'];
$clientfield5 = $row['clientfield5'];
$clientfield6 = $row['clientfield6'];
$client_customdate1 = $row['client_customdate1'];
$email = $row['email'];
$returnreasonid = $row['returnreasonid'];
$use_loyalty_points = $row['use_loyalty_points'];
$loyalty_start = $row['loyalty_start'];
$matchingid = $row['matchingid'];
$telephone = $row['telephone'];
$cellphone = $row['cellphone'];
$isnotice = $row['isnotice'] + 0;
### TODO option see below
$fake_isnotice = 0;
if ($isnotice && $_SESSION['ds_customname'] == 'Natural & Organic') # TODO option (what does this do and why?)
{
  $fake_isnotice = 1;
}
###
$totalprice = 0;
$invoiceprice = $row['invoiceprice'];
$userid = (int) $row['userid'];
$username = $row['username'];
#$totaltva = $row['invoicevat'];

$vesselname = ''; if ($row['localvesselid'] > 0) { $vesselname = $localvesselA[$row['localvesselid']]; }

$cancelledid = $row['cancelledid'];
$reference = $row['reference'];
$extraname = $row['extraname'];

$deliverydate = $row['deliverydate'];
$accountingdate = $row['accountingdate'];
$custominvoicedate = $row['custominvoicedate'];
$invoicedate = $row['invoicedate'];
$invoicetime = $row['invoicetime'];
$paybydate = $row['paybydate'];
$advanceid = $row['advanceid'];

$invoicetagid = $row['invoicetagid'];

$field1 = $row['field1'];
$field2 = $row['field2'];

$invoicecomment = $row['invoicecomment'];
$invoicecomment2 = $row['invoicecomment2'];

$clientid = $row['clientid'];
$clientname = d_decode($row['clientname']);
$companytypename = $row['companytypename'];
$tahitinumber = $row['tahitinumber'];

$extraaddressid = $row['extraaddressid'];
$postaladdress = $row['postaladdress'];
$address = $row['address'];

$postalcode = $row['postalcode'];
$townid = $row['townid'];
$town_name = $row['town_name'];
if ($_SESSION['ds_customname'] == 'Transpol') # TODO option
{
  $countryname = $countryA[$row['countryid']];
}
else { $countryname = ''; }

$employeeid = $row['employeeid'];

$proforma = $row['proforma'];
$isreturn = $row['isreturn'];
$confirmed = $row['confirmed'];

$typetext = 'Facture ';

if ($proforma == 1 && $confirmed == 0)
{
  $typetext = 'Proforma ';
}

if ($isnotice)
{
  $typetext = $_SESSION['ds_term_invoicenotice'];
}

if ($isreturn == 1)
{
  $typetext = 'Avoir ';
  if ($isnotice)
  {
    if ($returnreasonid > 0)
    {
      $typetext = $_SESSION['ds_term_invoicenotice'] . ' ' . $returnreasonA[$returnreasonid];
    }
    else { $typetext .= $_SESSION['ds_term_invoicenotice']; }
  }
}

if ($template != 1 && $confirmed == 0 && $isnotice == 0 && $cancelledid != 1) # ($template == 2 || $template == 3) &&    TODO
{
  $typetext = 'Devis'; # TODO option for AFEQ to disable this (the reason why model 1 is excluded)
  if ($_SESSION['ds_customname'] == 'Tahiti Crew')
  {
    $typetext = 'Invoice ';
  }
  if ($isreturn == 1)
  {
    $typetext .= ' Avoir ';
  }
}

if ($cancelledid == 1) { $typetext = 'ANNULÉ(E) : '.$typetext; }

/* Options for invoice title like Pacitech */

$year = mb_substr($row['accountingdate'], 0, 4) + 0;

if ($_SESSION['ds_showinvoice_dateformat'] == 1)
{
  if (mb_strlen($invoiceid) == 1)
  {
    $format_invoiceid = '000' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 2)
  {
    $format_invoiceid = '00' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 3)
  {
    $format_invoiceid = '0' . $invoiceid;
  }

  elseif(mb_strlen($invoiceid) > 4) {
    $format_invoiceid = substr($invoiceid, -4);
  }

  showtitle($typetext . $year . $format_invoiceid);

  $format_invoiceid = $year . $format_invoiceid;
}
elseif ($_SESSION['ds_showinvoice_dateformat'] == 2)
{
  if (mb_strlen($invoiceid) == 1)
  {
    $format_invoiceid = '0000' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 2)
  {
    $format_invoiceid = '000' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 3)
  {
    $format_invoiceid = '00' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 4)
  {
    $format_invoiceid = '0' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) > 5)
  {
    $format_invoiceid = substr($invoiceid, -5);
  }

  showtitle($typetext . $year . $format_invoiceid);
  $format_invoiceid = $year . $format_invoiceid;
}
else
{
  showtitle($typetext . $invoiceid);
  $format_invoiceid = $invoiceid;
  if ($invoicemerge == '') { $format_invoiceid = myfix($format_invoiceid); }
}

$query_prm = array();

if ($usehistory)
{
  $query = 'SELECT concat(employeename," ",employeefirstname) as employeename
            FROM invoicehistory,employee
            WHERE invoicehistory.employeeid = employee.employeeid
            AND invoiceid = ?';

  $query_prm[] = $invoiceid;
}
else
{
  $query = 'SELECT concat(employeename," ",employeefirstname) as employeename
            FROM invoice,employee
            WHERE invoice.employeeid = employee.employeeid
            AND invoiceid = ?';

  $query_prm[] = $invoiceid;
}

require('inc/doquery.php');

if ($num_results > 0)
{
  $employeename = $query_result[0]['employeename'];
}
else { $employeename = ''; }

$invoicetagname = '';
if ($invoicetagid > 0)
{
  $query = 'SELECT invoicetagname FROM invoicetag WHERE invoicetagid = ?';
  $query_prm = array($invoicetagid);

  require('inc/doquery.php');
  $invoicetagname = $query_result[0]['invoicetagname'];
}

if ($template == 1)
{
  if ($confirmed == 0 && $_SESSION['ds_customname'] != 'airfroideq' && $_SESSION['ds_customname'] != 'AirFroid')
  {
    $typetext = $typetext . 'non confirmée ';
  }
}

$ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';

if ($extraaddressid < 1)
{
}
else
{
  $query = 'SELECT address,postaladdress,postalcode,telephone,townname,islandname
            FROM extraaddress,town,island
            WHERE extraaddress.townid = town.townid
            AND town.islandid = island.islandid
            AND extraaddressid = ?';

  $query_prm = array();
  $query_prm[] = $extraaddressid;

  require('inc/doquery.php');

  $row3 = $query_result[0];

  if ($row3['telephone'] != '') { $telephone = $row3['telephone']; }
  if ($postaladdress != "")
  {
    $address = stripslashes($row3['postaladdress']);
  }
  else
  {
    $address = stripslashes($row3['address']);
  }
}

###
if ($invoicemerge != '')
{
  $invoicemergeA = explode(' ',$invoicemerge);
  $invoicemergeA = array_filter(array_unique($invoicemergeA));
  $im_list = '(';
  foreach ($invoicemergeA as $kladd)
  {
    $kladd = (int) $kladd;
    $im_list .= $kladd . ',';
    $format_invoiceid .= ','.$kladd;
  }
  $im_list = rtrim($im_list,',') . ')';
  if ($im_list == '()') { $im_list = ''; }
  $invoiceprice = 0; # 2020 11 24
} else { $im_list = ''; }
###

$query = 'SELECT producttypeid,linevalue,linedate,employeeid,serial,eancode,displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitem.productid,productname,suppliercode,
          productdetails,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,lineprice,hide_price_on_invoice,
          linevat,itemcomment,taxcode,rebate_type,invoiceitemid,weight,volume,productfamily.familyrank,productfamilyname,supplierid,
          invoice_priceoption1id,invoice_priceoption2id,invoice_priceoption3id,invoiceitem.invoiceid
          FROM invoiceitem,product,unittype,taxcode,productfamily
          WHERE invoiceitem.productid = product.productid
          AND product.unittypeid = unittype.unittypeid
          AND product.taxcodeid = taxcode.taxcodeid
          and product.productfamilyid=productfamily.productfamilyid';
          if ($im_list != '')
          {
            $query .= ' AND (invoiceitem.invoiceid=? OR invoiceitem.invoiceid in '.$im_list.')';
          }
          else { $query .= ' AND invoiceitem.invoiceid = ?'; }
          if ($_SESSION['ds_invoice_display_by_family']) { $query .= ' order by familyrank,invoiceitemid'; }
          else { $query .= ' ORDER BY invoiceitemid'; }

if ($usehistory)
{
  $query = 'SELECT producttypeid,linevalue,linedate,employeeid,serial,eancode,displaymultiplier,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode,productdetails,
            unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,hide_price_on_invoice,
            linevat,itemcomment,taxcode,rebate_type,invoiceitemid,weight,volume,productfamily.familyrank,productfamilyname,supplierid,
            invoice_priceoption1id,invoice_priceoption2id,invoice_priceoption3id,invoiceitemhistory.invoiceid
            FROM invoiceitemhistory,product,unittype,taxcode,productfamily
            WHERE invoiceitemhistory.productid = product.productid
            AND product.unittypeid = unittype.unittypeid
            AND product.taxcodeid = taxcode.taxcodeid
            and product.productfamilyid=productfamily.productfamilyid';
            if ($im_list != '')
            {
              $query .= ' AND (invoiceitemhistory.invoiceid=? OR invoiceitemhistory.invoiceid in '.$im_list.')';
            }
            else { $query .= ' AND invoiceitemhistory.invoiceid = ?'; }
            if ($_SESSION['ds_invoice_display_by_family']) { $query .= ' order by familyrank,invoiceitemid'; }
            else { $query .= ' ORDER BY invoiceitemid'; }
}

$query_prm = array();
$query_prm[] = $invoiceid;
require('inc/doquery.php');

$num_lines = $num_results;
$main_result = $query_result;

if ($_SESSION['ds_use_invoiceitemgroup'])
{
  # sort lines by invoiceitemgroupnumber
  /*
  select where invoiceid=?
  add results to $main_result
  sort
  if y==0 or different, display title
  */
  $gnA = array(); $gn_titleA = array();
  $query = 'select invoiceitemgroupnumber,invoiceitemid,invoiceitemgrouptitle,is_optional from invoiceitemgroup where invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    #echo '<br>',$query_result[$i]['invoiceitemid'],' ',$query_result[$i]['invoiceitemgroupnumber'];
    $gnA[$query_result[$i]['invoiceitemid']] = $query_result[$i]['invoiceitemgroupnumber']+0;
    $gn_titleA[$query_result[$i]['invoiceitemid']] = $query_result[$i]['invoiceitemgrouptitle'];
    $gn_optionalA[$query_result[$i]['invoiceitemid']] = $query_result[$i]['is_optional']+0;
  }
  for ($y = 0; $y < $num_lines; $y++)
  {
    #echo '<br>',$main_result[$y]['invoiceitemid'],' ',$gnA[$main_result[$y]['invoiceitemid']];
    if (isset($gnA[$main_result[$y]['invoiceitemid']]))
    { $main_result[$y]['gn'] = $gnA[$main_result[$y]['invoiceitemid']]; }
    else { $main_result[$y]['gn'] = ''; }
    if (isset($gn_titleA[$main_result[$y]['invoiceitemid']]))
    { $main_result[$y]['title'] = $gn_titleA[$main_result[$y]['invoiceitemid']]; }
    else { $main_result[$y]['title'] = ''; }
    if (isset($gn_optionalA[$main_result[$y]['invoiceitemid']]))
    { $main_result[$y]['gn_optional'] = $gn_optionalA[$main_result[$y]['invoiceitemid']]; }
    else { $main_result[$y]['gn_optional'] = ''; }
    
    # create array of titles by gn
    if (!isset($titleA[$main_result[$y]['gn']]) && $main_result[$y]['title'] != '')
    {
      $titleA[$main_result[$y]['gn']] = $main_result[$y]['title'];
    }
  }
  $sortresultsA = ["gn","invoiceitemid"];
  d_sortresults($main_result, $sortresultsA, $num_lines);
}

$totalpages = ceil($num_lines / $linesperpage);

$totalrebate = 0;

for ($y = 0; $y < $num_lines; $y++)
{
  if ($im_list != '')
  {
    $query = 'select isreturn from invoice';
    if ($usehistory) { $query .= 'history'; }
    $query .= ' where invoiceid=?';
    $query_prm = array($main_result[$y]['invoiceid']);
    require('inc/doquery.php');
    $main_result[$y]['isreturn'] = (int) $query_result[0]['isreturn'];
    if ($main_result[$y]['isreturn'])
    {
      $invoiceprice -= $main_result[$y]['lineprice'] + $main_result[$y]['linevat'];
    }
    else
    {
      $invoiceprice += $main_result[$y]['lineprice'] + $main_result[$y]['linevat'];
    }
  }
  else { $main_result[$y]['isreturn'] = 0; }
  
  if ($main_result[$y]['isreturn'])
  {
    $totalrebate -= $main_result[$y]['givenrebate'];
  }
  else
  {
    $totalrebate += $main_result[$y]['givenrebate'];
  }
  
  $kladd = $main_result[$y]['taxcode'];
  if (!isset($vatrateA[$kladd])) { $vatrateA[$kladd] = 0; }
  $vatrateA[$kladd] = 1;
}
if (isset($vatrateA)) { $num_vatrate = count($vatrateA); }
else { $num_vatrate = 0; }

$colspan = 6;

if ($totalrebate == 0)
{
  $colspan = $colspan - 2;
  if ($template == 6 && $_SESSION['ds_customname'] == 'TEM') # 2020 09 01 really really need refactor  
  {
    $colspan++;
  }
}

if ($_SESSION['ds_useitemadd'])
{
  $colspan = $colspan + 4;
}

if ($template >= 6)
{
  $colspan++;
  if ($_SESSION['ds_discount_line'] != 0) { $colspan--; }
  if ($_SESSION['ds_uselocalbol'] == 2) { $colspan++; }
}

$informationTable_header = '<thead>';
if ($_SESSION['ds_useitemadd']) { $informationTable_header .= '<th>Date<th>Début<th>Fin<th>Employé'; }
$informationTable_header .= '<th';
$temp_colspan = 1;
if (!$_SESSION['ds_use_invoiceitemgroup']) { $temp_colspan++; }

$informationTable_header .= ' colspan='.$temp_colspan;
$informationTable_header .= '>Produit'; # <font size=-2>
if ($_SESSION['ds_use_salesprice_mod'])
{
  require('preload/invoice_priceoption1.php');
  require('preload/invoice_priceoption2.php');
  require('preload/invoice_priceoption3.php');
  if (isset($invoice_priceoption1A)) { $informationTable_header .= '<th>'.$_SESSION['ds_term_invoice_priceoption1']; }
  if (isset($invoice_priceoption2A)) { $informationTable_header .= '<th>'.$_SESSION['ds_term_invoice_priceoption2']; }
  if (isset($invoice_priceoption3A)
  && $_SESSION['ds_customname'] != 'TAHITI MARQUAGES')
  { $informationTable_header .= '<th>'.$_SESSION['ds_term_invoice_priceoption3']; }
}
if ($_SESSION['ds_select_itemcomment'])
{
  $informationTable_header .= '<th>';
  if ($_SESSION['ds_customname'] == 'TAHITI MARQUAGES') { $informationTable_header .= 'Couleur'; }
}
if ($split_quantity) { $informationTable_header .= '<th style="text-align: center;">U'; }
if ($_SESSION['ds_customname'] == 'Espace Paysages') { $informationTable_header .= '<th style="text-align: center;">QUANTITÉ'; } # TODO term
else { $informationTable_header .= '<th>Quantité'; }
if ($_SESSION['ds_uselocalbol'] == 2) { $informationTable_header .= '<th>Poids'; }
if (!$isnotice)
{
  if ($_SESSION['ds_show_unittotalvat'])
  {
    $informationTable_header .= '<th>P.U.HT';
    $informationTable_header .= '<th>P.U.TTC';
  }
  else
  {
    if ($_SESSION['ds_customname'] == 'Espace Paysages') { $informationTable_header .= '<th style="text-align: center;">PU'; } # TODO term
    else { $informationTable_header .= '<th>Prix UHT'; }
  }
  if ($totalrebate > 0 && $_SESSION['ds_discount_line'] == 0) { $informationTable_header .= '<th>Remise'; }
  if ($num_vatrate > 1) { $informationTable_header .= '<th>TVA'; }
  if ($_SESSION['ds_customname'] == 'Espace Paysages') { $informationTable_header .= '<th style="text-align: center;">Montant'; } # TODO option
  else
  {
    $informationTable_header .= '<th>Montant';
    if ($_SESSION['ds_customname'] == 'TAHITI MARQUAGES') { $informationTable_header .= ' HT'; }
  }
}
$informationTable_header .= '</thead>';

$informationTable = '';

for ($y = 0; $y < $num_lines; $y++)
{
  $row2 = $main_result[$y];
  if ($row2['displaymultiplier'] == 0) { $row2['displaymultiplier'] = 1; }
  if (!isset($row2['gn_optional']) || !$row2['gn_optional'])
  {
    if ($row2['isreturn'])
    {
      $totalht -= $row2['lineprice'];
      $totaltva -= $row2['linevat'];
      $totalprice -= $row2['lineprice'] + $row2['linevat'];
    }
    else
    {
      $totalht += $row2['lineprice'];
      $totaltva += $row2['linevat'];
      $totalprice += $row2['lineprice'] + $row2['linevat'];
    }
  }
  $quantity = $row2['quantity'] / $row2['numberperunit'];
  $unittypename = $row2['unittypename'];
  $bcp = myround($row2['basecartonprice']);

  if ($_SESSION['ds_useunits'] && $row2['quantity'] % $row2['numberperunit'])
  {
    $quantity = $row2['quantity'];
    $unittypename = 'pièce';
    $bcp = myround($bcp / $row2['numberperunit']);
  }

  $bcpdivider = $bcp;

  if ($bcpdivider == 0)
  {
    $bcpdivider = 1;
  }

  # 2017 02 08 everything used to be shown in %
  # TODO option, always show as % for AF/AFEQ
  $gr = 0;
  if ($_SESSION['ds_customname'] == 'AirFroid' || $_SESSION['ds_customname'] == 'airfroideq') { $row2['rebate_type'] = 1; }
  if ($row2['rebate_type'] == 0)
  {
    $gr = myfix(d_add($row2['givenrebate'], 0));
    if ($gr == 0)
    {
      $gr = '&nbsp;';
    }
    else
    {
      $gr = $gr . '<span class="small-percent"> XPF</span>';
    }
  }
  elseif ($row2['rebate_type'] == 1)
  {
    $gr = 0;
    if (($row2['lineprice'] + $row2['givenrebate']) != 0)
    {
      $gr = myfix(100 * $row2['givenrebate'] / ($row2['lineprice'] + $row2['givenrebate']));
    }
    if ($gr == 0)
    {
      $gr = '&nbsp;';
    }
    else
    {
      if ($_SESSION['ds_use_invoiceitemgroup'] && $row2['gn_optional'])
      {
        $gr = myfix(100*$row2['givenrebate']/$quantity/$bcp);
        $row2['lineprice'] = $bcp * $quantity * (100-$gr)/100;
      }
      $gr = $gr . '<span class="small-percent">%</span>';
    }
  }
  elseif ($row2['rebate_type'] == 2)
  {
    if (isset($row2['basecartonprice']) && $row2['basecartonprice'] != 0)
    { $gr = myfix(myround($row2['givenrebate'] / $row2['basecartonprice'])); }
    if ($gr == 0)
    {
      $gr = '&nbsp;';
    }
    else
    {
      if ($_SESSION['ds_use_invoiceitemgroup'])
      {
        $gr = 'Pour Mémoire';
      }
      else
      {
        $gr = $gr . '<span class="small-percent"> '.d_output($unittypename).'</span>';
      }
    }
  }
  elseif ($row2['rebate_type'] == 3)
  {
    $gr = d_add($row2['givenrebate'], 0);
    $gr = $gr * ((100 + $row2['taxcode']) / 100);
    $gr = myfix($gr);
    if ($gr == 0)
    {
      $gr = '&nbsp;';
    }
    else
    {
      $gr = $gr . '<span class="small-percent"> Points</span>';
    }
  }

  $showtva = myround($row2['taxcode']) . '<span class="small-percent">%</span>';

  $kladd = $row2['taxcode'];

  if ($row2['linetaxcodeid'] > 0)
  {
    $kladd = $taxcodeA[$row2['linetaxcodeid']];
    if ($row2['linetaxcodeid'] == 59999)
    {
      $showtva = '0<span class="small-percent">%</span>';
    }
    else
    {
      $showtva = myround($taxcodeA[$row2['linetaxcodeid']]) . '<span class="small-percent">%</span>';
    }
  }

  if (!isset($tvaM[$kladd])) { $tvaM[$kladd] = 0; }
  if (!isset($tvaMt[$kladd])) { $tvaMt[$kladd] = 0; }
  if ($row2['isreturn'])
  {
    $tvaM[$kladd] -= myround($row2['linevat']);
    $tvaMt[$kladd] -= myround($row2['lineprice']);
  }
  else
  {
    $tvaM[$kladd] += myround($row2['linevat']);
    $tvaMt[$kladd] += myround($row2['lineprice']);
  }

  if ($template >= 6)
  {
    $productname = d_decode($row2['productname']);
  }
  else
  {
    $productname = $row2['productid'];

    if ($_SESSION['ds_useproductcode'] == 1)
    {
      $productname = $row2['suppliercode'];
    }

    if ($_SESSION['ds_customname'] == 'Fenua Pharm')
    {
      $productname = $row2['eancode'];
      if (strlen($productname) == 13)
      {
        $productname = substr($productname, 0, 5) . '<b>' . substr($productname, 5, 7) . '</b>' . substr($productname, 12, 1);
      }
    }

    $productname = $productname . ': ' . d_decode($row2['productname']) . ' ';
  }
  $productname = $productname . ' ';
  if ($_SESSION['ds_useunits'] == 1 && $row2['numberperunit'] > 1)
  {
    $productname = $productname . $row2['numberperunit'] . ' x ';
  }

  $productname = $productname . $row2['netweightlabel'];
  if (floor($y / $linesperpage) == ($pagenumber - 1))
  {
    if ($_SESSION['ds_use_invoiceitemgroup'] && ($y==0 || $row2['gn'] != $main_result[($y-1)]['gn']) && $row2['gn'] > 0)
    {
      $informationTable .= '<tr><td style="text-align: left;" colspan=30> &nbsp; ';
      $start_whitespace = strlen($titleA[$row2['gn']])-strlen(ltrim($titleA[$row2['gn']]));
      $titleA[$row2['gn']] = ($row2['gn']+0) . ' ' . ltrim($titleA[$row2['gn']]);
      for ($x=0; $x < $start_whitespace; $x++)
      {
        $titleA[$row2['gn']] = ' '.$titleA[$row2['gn']];
      }
      $informationTable .= '<b>' .d_output(str_replace(' ','&nbsp;',$titleA[$row2['gn']])).'</b>';
    }
    
    $informationTable .= '<tr>';

    if ($_SESSION['ds_useitemadd'] || $_SESSION['ds_useserialnumbers'])
    {

      if ($_SESSION['ds_useitemadd'])
      {
        $informationTable .= '<td>' . datefix2($row2['linedate']) . '<td><td><td>';
        if ($employeeid > 0) { $informationTable .= $employeeA[$employeeid]; }
      }

      if ($_SESSION['ds_useserialnumbers'] && $row2['serial'] != '')
      {
        $productname .= ' [' . $row2['serial'] . ']';
      }
    }

    if ($template >= 6)
    {
      $noid = 0;
      if ($_SESSION['ds_customname'] == 'TAHITI MARQUAGES' || $_SESSION['ds_customname'] == '	Pro Peinture')
      {# TODO option
        $noid = 1;
      }
      if ($_SESSION['ds_use_invoiceitemgroup'] || $noid == 1)
      {
        #$informationTable .= '<td>';
      }
      else
      {
        if ($_SESSION['ds_useproductcode'] == 1)
        {
          $informationTable .= '<td style="text-align: left;">' . d_decode($row2['suppliercode']);
        }
        else
        {
          if ($_SESSION['ds_customname'] == 'Fenua Pharm') # TODO option
          {
            #$informationTable .= '<td>' . $row2['eancode'];
            $informationTable .= '<td>' . substr($row2['eancode'],0,5) . '<b>' . substr($row2['eancode'],5,7) . '</b>' . substr($row2['eancode'],12);
          }
          else { $informationTable .= '<td>' . myfix($row2['productid']); }
        }
      }
      $informationTable .= '<td class="breakme" style="text-align: left;"';
      $temp_colspan = 1;
      if ($template == 6 && $_SESSION['ds_use_invoiceitemgroup']) { $temp_colspan++; }
      if ($noid) { $temp_colspan++; }
      if ($temp_colspan > 1) { $informationTable .= ' colspan='.$temp_colspan; }
      if ($_SESSION['ds_use_invoiceitemgroup'] && $row2['gn_optional']) { $productname = 'OPTION : '.$productname; }
      $informationTable .= '>' . $productname;
      if ($_SESSION['ds_use_salesprice_mod'])
      {
        if ($row2['invoice_priceoption1id'] > 0) { $informationTable .= d_td($invoice_priceoption1A[$row2['invoice_priceoption1id']],'center'); }
        if ($row2['invoice_priceoption2id'] > 0) { $informationTable .= d_td($invoice_priceoption2A[$row2['invoice_priceoption2id']],'center'); }
        if ($row2['invoice_priceoption3id'] > 0
        && $_SESSION['ds_customname'] != 'TAHITI MARQUAGES')
        { $informationTable .= d_td($invoice_priceoption3A[$row2['invoice_priceoption3id']],'center'); }
      }
      if ($_SESSION['ds_select_itemcomment'])
      {
        if (substr($row2['serial'],0,7) == 'colorid')
        {
          require_once('preload/color.php');
          $informationTable .= d_td($colorA[substr($row2['serial'],7)],'center');
        }
        else { $informationTable .= d_td($row2['serial']); }
      }
    }
    else
    {
      $informationTable .= '<td class="breakme letters">' . $productname . '';
    }

    if ($row2['productdetails'] != "")
    {
      $informationTable .= '<br>' . $row2['productdetails'];
    }

    if ($hidediscount == 1)
    {
      $bcp = $row2['lineprice'] / $quantity;
      $gr = '&nbsp;';
    }

    if ($row2['displaymultiplier'] != 1)
    {
      $quantity = $quantity / $row2['displaymultiplier'];
      $bcp = $row2['basecartonprice'] * $row2['displaymultiplier'];
    }
    
    if ($fake_isnotice) # 2017 05 31
    {
      $query = 'select salesprice from product where productid=?';
      $query_prm = array($row2['productid']);
      require('inc/doquery.php');
      $bcp = $query_result[0]['salesprice'];
      $row2['lineprice'] = myround($bcp * $quantity);
      $totalht += $row2['lineprice'];
      $totaltva += myround($row2['lineprice'] * ($row2['taxcode']/100));
      $invoiceprice += $row2['lineprice'] + myround($row2['lineprice'] * ($row2['taxcode']/100));
    }

    if ($split_quantity) { $informationTable .= '<td class="numbers">'.$unittypename.'<td class="numbers">' . $quantity; }
    else
    {
      if ($_SESSION['ds_customname'] == 'TAHITI MARQUAGES')
      {
        $informationTable .= d_td($quantity,'center');
      }
      else
      {
        $informationTable .= '<td class="numbers">' . $quantity . ' ' . $unittypename;
      }
    }
    
    if ($_SESSION['ds_uselocalbol'] == 2) # HERE TODO  use "itemaddvalue"?
    {
      $informationTable .= '<td class="numbers">';
      if ($row2['linevalue'] > 0) { $informationTable .= (double) $row2['linevalue']; }
    }
    
    

    if ($isnotice && !$fake_isnotice || $hideprices == 1 || $row2['hide_price_on_invoice'] == 1)
    {

    }
    else
    {
      if ($_SESSION['ds_discount_line'] != 0) { $showlineprice = myfix($row2['lineprice']+$row2['givenrebate']); }
      else { $showlineprice = myfix($row2['lineprice']); }
      if ($row2['isreturn']) { $showlineprice = '- '.$showlineprice; }
      if ($_SESSION['ds_use_invoiceitemgroup'] && $row2['gn_optional'])
      {
        if (1==0&&$totalrebate > 0) # 2020 04 15 taken off at PRO PEINTURE request
        { $gr = 'En option'; $showlineprice = myfix($bcp * $quantity); }
        else { $showlineprice = 'PM'; }
      }
      $informationTable .= '<td class="numbers">' . myfix($bcp);
      if ($_SESSION['ds_show_unittotalvat'])
      { $informationTable .= '<td class="numbers">' . myfix($bcp * ((100+$row2['taxcode'])/100)); }
      if ($totalrebate > 0)
      {
        if ($_SESSION['ds_customname'] == 'Vaimato' && ($template == 2 || $template == 1))
        {
          if ($main_result[$y]['producttypeid'] == 1)
          {
            $informationTable .= '<td><td class="numbers">' . $gr;
          }
          else
          {
            $informationTable .= '<td class="numbers">' . $gr . '<td>';
          }
        }
        else
        {
          if ($_SESSION['ds_discount_line'] == 0) { $informationTable .= '<td class="numbers">' . $gr; }
          if ($template <= 6)
          {
            if ($_SESSION['ds_discount_line'] == 0)
            {
              $informationTable .= '<td class="numbers">' . myfix($row2['lineprice'] / $quantity);
            }
            else
            {
              $informationTable .= '<td class="numbers">' . myfix(($row2['lineprice']+$row2['givenrebate']) / $quantity);
            }
          }
        }
      }
      if ($template <= 6 || $num_vatrate > 1) { $informationTable .= '<td class="numbers">&nbsp;' . $showtva; }
      $informationTable .= '<td class="numbers">' . $showlineprice;
      if ($_SESSION['ds_customname'] == 'ANIMALICE')
      {
        $informationTable .= '<td class="numbers">' . myfix($row2['lineprice']+$row2['linevat']);
      }
    }
    
    if ($_SESSION['ds_discount_line'] != 0 && $row2['givenrebate'] > 0)
    {
      if ($template >= 7)
      {
        $informationTable .= '<tr><td align="left">Remise commerciale';
        if (($colspan-6)>0)
        { $informationTable .= '<td colspan="'.($colspan-6).'">'; }
      }
      else { $informationTable .= '<tr><td style="text-align: left;">Remise commerciale<td colspan="'.($colspan-4).'">'; }
      $informationTable .= '<td>';
      if ($row2['rebate_type'] == 1)
      {
        $informationTable .= '<td class="numbers">- '
        . myfix(($row2['givenrebate']*100)/($row2['lineprice']+$row2['givenrebate'])).' %';
      }
      else { $informationTable .= '<td class="numbers">- ' . myfix($row2['givenrebate'] / $quantity); }
      if ($template <= 6 || $num_vatrate > 1) { $informationTable .= '<td>'; }
      $informationTable .= '<td align=right>- '.myfix(d_add($row2['givenrebate'], 0));
    }

    if ($row2['itemcomment'] != "")
    {
      $itemcomment = str_replace('§', '', $row2['itemcomment']); # TODO remove once clients learn not to use §
      $informationTable .= '<tr>';
      if ($template >= 6 && $_SESSION['ds_use_invoiceitemgroup'] == 0
      && $noid == 0) { $informationTable .= '<td>'; }
      $informationTable .= '<td class="breakme letters" colspan=20><span class="item-comment">' . d_output($itemcomment) . '</span>';
    }
  }
  if ($_SESSION['ds_use_invoiceitemgroup'] && floor($y / $linesperpage) == ($pagenumber - 1))
  {
    if (!$row2['gn_optional'])
    {
      if ($row2['isreturn']) { $subtotal -= $row2['lineprice']; }
      else { $subtotal += $row2['lineprice']; }
    }
    $subtotal_lines++;
    if (!isset($main_result[($y+1)]['gn']) || $row2['gn'] != $main_result[($y+1)]['gn'])
    {
      if ($subtotal_lines > 1)
      {
        if ($template == 6)
        {
          $sub_colspan = 4; # 2020 01 09 changed from 5 to 4 TODO refactor all colspans
          if ($totalrebate == 0) { $sub_colspan++; }
        } 
        else
        {
          $sub_colspan = 4;
          if ($num_vatrate <= 1) { $sub_colspan--; }
          if ($split_quantity) { $sub_colspan++; }
        }
        if ($totalrebate > 0)
        {
          $sub_colspan++;
          if ($template == 6) { $sub_colspan++; }
        }
        if ($_SESSION['ds_customname'] != 'Fenua AC Cleaner')
        {
          $informationTable .= '<tr><td colspan='.$sub_colspan.' align=right>';
          $informationTable .= '<b>Sous-total<td align=right><b>'.myfix($subtotal);
        }
      }
      $subtotal = 0;
      $subtotal_lines = 0;
    }
  }
}

if ($_SESSION['ds_invoice_display_by_family']) # 2019 11 16 this option currently only for Tahti Crew, needs to be generalized
{
  $informationTable_header = '';
  $informationTable = '';
  for ($y = 0; $y < $num_lines; $y++)
  {
    $row2 = $main_result[$y];
    if (floor($y / $linesperpage) == ($pagenumber - 1))
    {
      if ($y == 0 || $main_result[$y]['familyrank'] != $main_result[($y-1)]['familyrank'])
      {
        if ($y > 0) { $informationTable .= '<tr><td colspan=4>&nbsp;'; }
        $informationTable .= '<tr><td colspan=4><b>'.d_output($row2['productfamilyname']);
        $informationTable .= '<tr><td align=center><b>Supplier<td align=center><b>Description<td align=center><b>Date<td align=center><b>Amount';
      }
      $query = 'select clientname from client where clientid=?';
      $query_prm = array($row2['supplierid']);
      require('inc/doquery.php');
      if ($num_results) { $suppliername = d_decode($query_result[0]['clientname']); }
      else { $suppliername = ''; }
      if (strpos($row2['itemcomment'],'§'))
      {
        $c1 = strstr($row2['itemcomment'],'§',true);
        $c2 = mb_substr($row2['itemcomment'],strpos($row2['itemcomment'],'§')+1);
      }
      elseif (strpos($row2['itemcomment'],'~')) 
      {
        $c1 = strstr($row2['itemcomment'],'~',true);
        $c2 = mb_substr($row2['itemcomment'],strpos($row2['itemcomment'],'~')+1);
      }
      else { $c1 = $row2['itemcomment']; $c2 = ''; }
      if ($row2['productid'] == 20) { $informationTable .= '<tr><td colspan=20>&nbsp;'; }
      $informationTable .= '<tr>';
      $informationTable .= '<td valign=top>'.d_output($suppliername);
      $informationTable .= '<td valign=top class="breakme letters">';
      if ($c1 != '') { $informationTable .= '<b>'; }
      $informationTable .= d_output(d_decode($row2['productname']));
      if ($c1 != '') { $informationTable .= '</b>'; }
      $informationTable .= '<br>'.d_output($c1);
      $informationTable .= '<td valign=top class="breakme letters">'.d_output($c2);
      $informationTable .= '<td valign=top align=right>'.myfix($row2['lineprice']).' FCP';
    }
  }
}

if ($field2 == 'Échelonnée')
{
  $total_paid = $total_rest = 0;
  require('preload/paymenttype.php');
  $split_invoice_show = '<table class="invoiceitems">';
  $split_invoice_show .= '<tr><td><b>Échéances<td align=center><b>Date<td><b>Paiement<td><b>'
  .$_SESSION['ds_term_paymfield2'].'<td><b>Restant';
  $query = 'select accountingdate,invoiceprice,matchingid,invoiceid from invoicehistory
  where cancelledid=0 and isreturn=0 and invoicecomment=?
  order by accountingdate,invoiceid';
  $query_prm = array($invoiceid.' Échelonnée');
  require('inc/doquery.php');
  $split_results = $query_result; $num_results_split = $num_results;
  for ($i=0; $i<$num_results_split; $i++)
  {
    $rest_pay = $split_results[$i]['invoiceprice'];
    $split_invoice_show .= '<tr><td>'.($i+1).'. Réf '.myfix($split_results[$i]['invoiceid']);
    $split_invoice_show .= '<td>'.datefix($split_results[$i]['accountingdate'],'short');
    $temp_text = $temp_text2 = '';
    $query = 'select paymentdate,paymfield2,paymentid,value,paymenttypeid
    from payment where reimbursement=0 and forinvoiceid=?';
    $query_prm = array($split_results[$i]['invoiceid']);
    require('inc/doquery.php');
    if ($num_results == 0 && $split_results[$i]['matchingid'] > 0)
    {
      $query = 'select paymentdate,paymfield2,paymentid,value,paymenttypeid
      from payment where reimbursement=0 and matchingid=?';
      $query_prm = array($split_results[$i]['matchingid']);
      require('inc/doquery.php');
      $temp_text = ' Lettrée avec : ';
    }
    for ($y=0; $y<$num_results; $y++)
    {
      $rest_pay -= $query_result[$y]['value'];
      $total_paid += $query_result[$y]['value'];
      if ($y > 0)
      {
        $temp_text .= ', ';
        $temp_text2 .= ' ';
      }
      $temp_text .= myfix($query_result[$y]['value']).' XPF (Paiement '.$query_result[$y]['paymentid'].', '
      .$paymenttypeA[$query_result[$y]['paymenttypeid']].')';
      $temp_text2 .= $query_result[$y]['paymfield2'];
    }
    if ($rest_pay < 0) { $rest_pay = 0; }
    $split_invoice_show .= d_td($temp_text,'breakme');
    $split_invoice_show .= d_td($temp_text2);
    $split_invoice_show .= d_td($rest_pay,'currency');
    $total_rest += $rest_pay;
  }
  $split_invoice_show .= d_tr();
  $split_invoice_show .= d_td('','',2);
  $split_invoice_show .= d_td('Total payé : '.myfix($total_paid));
  $split_invoice_show .= d_td('Total restant :');
  $split_invoice_show .= d_td($total_rest,'currency');
  $split_invoice_show .= '</table>';
}

$show_sig = 0;
if ($_SESSION['ds_use_invoice_sig']) # TODO important security for diff images and shared invoices
{
  $show_sig = 1;
  $signature_show = '<br><div class="sign_box">Signature: ';
  $query = 'select imageid from image where sig_invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  if ($num_results)
  {
    $signature_show .= '<img src="viewimage.php?image_id='.$query_result[0]['imageid'].'">';
  }
  else
  {
    $signature_show .= '<form action="sig_save.php" method="post">
    <input type="hidden" name="invoiceid" value="'.$invoiceid.'">
    <div id="signatureparent">
    <div id="signature"></div>
    <button class="no-print" type="button" onclick="$(\'#signature\').jSignature(\'clear\')">Effacer</button>
    <button class="no-print" type="submit" id="btnSave">Enregistrer</button>
    </div>
    <input type="hidden" id="hiddenSigData" name="hiddenSigData" />
    </form>
    <div id="scrollgrabber"></div>
    <script src="jq/jquery.js"></script>
    <script src="jq/jSignature.js"></script>
    <script src="jq/plugins/jSignature.CompressorBase30.js"></script>
    <script src="jq/plugins/jSignature.CompressorSVG.js"></script>
    <script src="jq/plugins/jSignature.UndoButton.js"></script> 
    <script>
        $(document).ready(function() {
            var $sigdiv = $("#signature").jSignature({\'UndoButton\':false});
            $(\'#btnSave\').click(function(){
                var sigData = $(\'#signature\').jSignature(\'getData\',\'base30\');
                $(\'#hiddenSigData\').val(sigData);
            });
        })
    </script>';
  }
  $signature_show .= '</div>';
}

if ($totalpages == 1 || $pagenumber == $totalpages)
{
  if ($isnotice && !$fake_isnotice || $hideprices == 1)
  {
    
  }
  else
  {
    $informationTotalPages .= '<tr><td colspan=' . $colspan . '>Total HT</td><td class="numbers">' . myfix($totalht) . '</td></tr>';

    if ($totaltva > 0)
    {
      $informationTotalPages .= '<tr><td colspan=' . $colspan . '>TVA</td><td class="numbers">' . myfix($totaltva) . '</td></tr>';
    }

    $informationTotalPages .= '<tr><td colspan=' . $colspan . '>';

    if ($fake_isnotice)
    {
      $informationTotalPages .= 'Valeur TTC';
    }
    else
    {
      if ($isreturn == 1)
      {
        $informationTotalPages .= 'Total à rembourser';
      }
      else
      {
        if ($_SESSION['ds_customname'] == 'SARL TEHEI') { $informationTotalPages .= 'Total TTC XPF'; }
        else { $informationTotalPages .= 'Total à payer'; }
      }
    }
    $informationTotalPages .= '<td class="numbers"><b>' . myfix($totalprice) . '</b>';
    
    if ($_SESSION['ds_invoice_display_by_family']) # should probably be separate option
    {
      $query = 'select currencyrate from currency where currencyacronym="EUR"';
      $query_prm = array();
      require('inc/doquery.php');
      $currencyrate = $query_result[0]['currencyrate']+0;
      $kladd = $totalprice / $currencyrate;
      $informationTotalPages .= '<tr><td colspan=' . $colspan . '>EUR ('.$currencyrate.')<td class="numbers">' . myfix($kladd);
      $query = 'select currencyrate from currency where currencyacronym="USD"';
      $query_prm = array();
      require('inc/doquery.php');
      $currencyrate = $query_result[0]['currencyrate']+0;
      $kladd = $totalprice / $currencyrate;
      $informationTotalPages .= '<tr><td colspan=' . $colspan . '>USD ('.$currencyrate.')<td class="numbers">' . myfix($kladd);
    }
    
    if ($_SESSION['ds_invoicedeductions'] == 1)
    {
      $query = 'SELECT deduction_desc, deduction, linenr
                FROM invoicededuction
                WHERE invoiceid = ?
                AND deduction_prevat = 0
                ORDER BY linenr';

      $query_prm = array($invoiceid);
      require('inc/doquery.php');

      for ($i = 0; $i < $num_results; $i++)
      {
        $informationTotalPages .= '<tr><td colspan=' . $colspan . '>' . d_output($query_result[$i]['deduction_desc']) . '<td align=right>-' . myfix($query_result[$i]['deduction']);
      }
    }
  }
}

if ($isnotice && !$fake_isnotice)
{
  
}
else
{
  $informationIsNotice = '';

  $totalpaid = 0;
  $paymentid = 0;

  $query = 'SELECT paymentid, value, reimbursement, paymenttypename, payment.paymenttypeid, bankid, chequeno, paymentdate
            FROM payment,paymenttype
            WHERE payment.paymenttypeid = paymenttype.paymenttypeid
            AND forinvoiceid = ?';

  $query_prm = array();
  $query_prm[] = $invoiceid;
  require('inc/doquery.php');

  for ($y = 0; $y < $num_results; $y++)
  {
    if ($query_result[$y]['reimbursement'] == 1)
    {
      $totalpaid = $totalpaid - $query_result[$y]['value'];
    }
    else
    {
      $totalpaid = $totalpaid + $query_result[$y]['value'];
    }
  }

  if ($advanceid > 0)
  {
    $advance_amount = myround($totalprice*$advance_percentageA[$advanceid]/100);
    $informationTotalPages .= '<tr><td colspan=' . $colspan . '>';
    if ($totalpaid < $advance_amount) { $informationTotalPages .= 'Acompte à verser'; }
    else { $informationTotalPages .= 'Acompte versé'; }
    $informationTotalPages .= '<td class="numbers"><b>' . myfix($advance_amount) . '</b>';
  }

  if ($totalpaid > 0 || $matchingid > 0)
  {
    if ($totalpaid >= $invoiceprice || $matchingid > 0)
    {
      if ($_SESSION['ds_customname'] == 'Tahiti Crew') { $informationIsNotice .= '<p>This invoice has been paid.'; }
      elseif ($field2 != 'Échelonnée') { $informationIsNotice .= '<p>Cette facture a été entièrement réglée.'; }
    }
    else { $informationIsNotice .= '<p>Cette facture a été <i>partiellement</i> réglée.'; }

    for ($y = 0; $y < $num_results; $y++)
    {
      $paymentid = $query_result[$y]['paymentid'];
      $paymenttypename = $query_result[$y]['paymenttypename'];
      $paymenttypeid = $query_result[$y]['paymenttypeid'];
      $bankid = $query_result[$y]['bankid'];
      $chequeno = $query_result[$y]['chequeno'];
      
      if ($query_result[$y]['reimbursement'] == 0 && $query_result[$y]['value'] > 0)
      {
        if ($num_results > 1) { $informationIsNotice .= '<br>'; }
        else { $informationIsNotice .= ' '; }
        $informationIsNotice .= '(Paiement ' . $paymentid . ', ';
        $informationIsNotice .= datefix($query_result[$y]['paymentdate'],'short').', ';
        $informationIsNotice .= $paymenttypename;
        if ($bankid > 0)
        {
          $informationIsNotice .= ': ';
          $informationIsNotice .= $bankA[$bankid];
          $informationIsNotice .= ' ' . $chequeno;
        }
        if ($num_results > 1 || $totalpaid < $invoiceprice)
        {
          $informationIsNotice .= ' '.myfix($query_result[$y]['value']).' XPF';
        }
        $informationIsNotice .= ')';
      }
    }
    if ($totalpaid > 0 && $totalpaid < $invoiceprice)
    {
      #$informationIsNotice .= '<br>Reste à payer : '.myfix($invoiceprice-$totalpaid).' XPF';
      $informationTotalPages .= '<tr><td colspan=' . $colspan . '>Reste à payer
      <td class="numbers"><b>' . myfix($invoiceprice-$totalpaid) . '</b>';
    }
    $informationIsNotice .= '</p>';
  }
}

### modified copy from invoicing.php
$points_string = '';
if ($_SESSION['ds_use_loyalty_points'] && $use_loyalty_points)
{
  $points = $loyalty_start;
  $points_gained = 0; $points_used = 0; 
  
  $query = 'select givenrebate,linetaxcodeid,lineprice,linevat,isreturn,rebate_type,invoiceitemhistory.invoiceid
  from invoiceitemhistory,invoicehistory
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
  and clientid=? and cancelledid=0 and confirmed=1 and isreturn=0';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    if ($query_result[$i]['givenrebate'] > 0)
    {
      if ($query_result[$i]['rebate_type'] == 3)
      {
        $kladd = round($query_result[$i]['givenrebate'] + ($query_result[$i]['givenrebate'] * $taxcodeA[$query_result[$i]['linetaxcodeid']] / 100));
        if ($query_result[$i]['isreturn'] == 1)
        {
          $points += $kladd;
          if ($query_result[$i]['invoiceid'] == $invoiceid) { $points_gained += $kladd; }
        }
        else
        {
          $points -= $kladd;
          if ($query_result[$i]['invoiceid'] == $invoiceid) { $points_used += $kladd; }
        }
      }
    }
    else
    {
      $kladd = round(($query_result[$i]['lineprice'] + $query_result[$i]['linevat']) * $_SESSION['ds_loyalty_points_percent'] / 100);
      if ($query_result[$i]['isreturn'] == 1)
      {
        $points -= $kladd;
        if ($query_result[$i]['invoiceid'] == $invoiceid) { $points_used += $kladd; }
      }
      else
      {
        $points += $kladd;
        if ($query_result[$i]['invoiceid'] == $invoiceid) { $points_gained += $kladd; }
      }
    }
  }

  $points = round($points);

  if (isset($loyaltydate) && $loyaltydate != '0000-00-00' && $loyaltydate != null)
  { $points_string .= '<tr><td colspan=4><b>Date Fidelité : '.datefix($loyaltydate); }
  if ($points > 0) { $points_string .= '<p><b>Points de fidelité : '.myfix($points) . '</b> '; }
  if ($points_gained > 0 || $points_used > 0) { $points_string .= '(Sur cette facture : '; }
  if ($points_gained > 0) { $points_string .= ' Acquis = '.myfix($points_gained); }
  if ($points_used > 0) { $points_string .= ' Utilisé = '.myfix($points_used); }
  if ($points_gained > 0 || $points_used > 0) { $points_string .= ')'; }
  $points_string .= '</p>';
}
###

#Display invoice template
switch ($template)
{
  case 1:
    require('printwindow/style_print1.php');
    require('printwindow/template1.php');
    break;

  case 2:
    require('printwindow/style_print2.php');
    require('printwindow/template2.php');
    break;

  case 6:
    ?>
    <link rel="stylesheet" href="declaration/bootstrap.css">
    <?php
    require('printwindow/style_print6.php');
    require('printwindow/template6.php');
    break;
  
  case 7:
    ?>
    <link rel="stylesheet" href="declaration/bootstrap.css">
    <?php
    require('printwindow/style_print7.php');
    require('printwindow/template7.php');
    break;
}