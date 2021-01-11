<?php

$stupidlines = 1; # fudge some stupid lines

$template = 7;
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

$usehistory = 0;
$totaltva = 0;
$totalht = 0;
$subtotal = 0;
$subtotal_lines = 0;

$split_quantity = 0;
$invoice_title_below = 0;
$narrow_lines = 0;
$show_idtahiti = 1;
$summary_top = 1;
if ($template == 7 && $_SESSION['ds_customname'] == 'Espace Paysages' || $_SESSION['ds_customname'] == 'Espace 7' || $_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Jurion Protection') # TODO options
{
  $split_quantity = 1;
  $invoice_title_below = 1;
  $narrow_lines = 1;
  $show_idtahiti = 0;
  $summary_top = 0;
}

$tvaM = array();
$tvaMt = array();

$query = 'select idtahiti from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];

$query = 'SELECT town_name,countryid,localvesselid,invoice.employeeid,invoicevat,field1,field2,townid,invoice.userid,invoicetagid,vatexempt,isnotice,contact,
          invoice.clientid,clientname,extraname,accountingdate,custominvoicedate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,
          proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,
          cellphone,email,fax,extraaddressid,clienttermname,confirmed,matchingid,loyalty_start,use_loyalty_points,returnreasonid,clientcode
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
            proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,
            cellphone,email,fax,extraaddressid,clienttermname,confirmed,matchingid,loyalty_start,use_loyalty_points,returnreasonid,clientcode
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

$clientcode = $row['clientcode'];
$returnreasonid = $row['returnreasonid'];
$clienttermname = $row['clienttermname'];
$use_loyalty_points = $row['use_loyalty_points'];
$loyalty_start = $row['loyalty_start'];
$matchingid = $row['matchingid'];
$telephone = $row['telephone'];
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
#$totaltva = $row['invoicevat'];

$vesselname = ''; if ($row['localvesselid'] > 0) { $vesselname = $localvesselA[$row['localvesselid']]; }

$cancelledid = $row['cancelledid'];
$reference = $row['reference'];
$extraname = $row['extraname'];

$deliverydate = $row['deliverydate'];
$accountingdate = $row['accountingdate'];
$custominvoicedate = $row['custominvoicedate'];
$paybydate = $row['paybydate'];

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

if ($proforma == 1)
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

if ($template != 1 && $confirmed == 0 && $isnotice == 0 && $cancelledid == 0) # ($template == 2 || $template == 3) &&    TODO
{
  $typetext = 'Devis'; # TODO option for AFEQ to disable this (the reason why model 1 is excluded)

  if ($isreturn == 1)
  {
    $typetext .= ' Avoir ';
  }
}

if ($cancelledid) { $typetext = 'ANNULÉ(E) : '.$typetext; }  

/* Options for invoice title like Pacitech */

$year = mb_substr($row['accountingdate'], 0, 4) + 0;

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

$query = 'SELECT linevalue,linedate,employeeid,serial,eancode,displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitem.productid,productname,suppliercode,
          productdetails,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,
          lineprice,linevat,itemcomment,taxcode,rebate_type,invoiceitemid,weight,volume
          FROM invoiceitem,product,unittype,taxcode
          WHERE invoiceitem.productid = product.productid
          AND product.unittypeid = unittype.unittypeid
          AND product.taxcodeid = taxcode.taxcodeid
          AND invoiceitem.invoiceid = ?
          ORDER BY invoiceitemid';

if ($usehistory)
{
  $query = 'SELECT linevalue,linedate,employeeid,serial,eancode,displaymultiplier,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode,productdetails,
            unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,
            linevat,itemcomment,taxcode,rebate_type,invoiceitemid,weight,volume
            FROM invoiceitemhistory,product,unittype,taxcode
            WHERE invoiceitemhistory.productid = product.productid
            AND product.unittypeid = unittype.unittypeid
            AND product.taxcodeid = taxcode.taxcodeid
            AND invoiceitemhistory.invoiceid = ?
            ORDER BY invoiceitemid';
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
    $main_result[$y]['gn'] = $gnA[$main_result[$y]['invoiceitemid']];
    $main_result[$y]['title'] = $gn_titleA[$main_result[$y]['invoiceitemid']];
    $main_result[$y]['gn_optional'] = $gn_optionalA[$main_result[$y]['invoiceitemid']];
    
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

###
if (mb_strlen($invoiceid) == 1)
{
  $format_invoiceid = '00' . $invoiceid;
}
elseif (mb_strlen($invoiceid) == 2)
{
  $format_invoiceid = '0' . $invoiceid;
}
elseif (mb_strlen($invoiceid) == 3)
{
  $format_invoiceid = $invoiceid;
}
elseif(mb_strlen($invoiceid) > 4) {
  $format_invoiceid = substr($invoiceid, -4);
}
#if ($totalpages > 1) { $format_invoiceid .= ' &nbsp; Page '.$pagenumber.' / '.$totalpages; }
showtitle($typetext . $year . $format_invoiceid);
$format_invoiceid = substr($year,2,2) . $format_invoiceid;
###

$totalrebate = 0;

for ($y = 0; $y < $num_lines; $y++)
{
  $totalrebate += $main_result[$y]['givenrebate'];
  
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
}

if ($_SESSION['ds_useitemadd'])
{
  $colspan = $colspan + 4;
}

if ($template >= 6)
{
  $colspan++;
  if ($_SESSION['ds_uselocalbol'] == 2) { $colspan++; }
}

$informationTable_header = '<thead>';
if ($_SESSION['ds_useitemadd']) { $informationTable_header .= '<th>Date<th>Début<th>Fin<th>Employé'; }
$informationTable_header .= '<th';
if (!$_SESSION['ds_use_invoiceitemgroup']) { $informationTable_header .= ' colspan=2'; }
$informationTable_header .= '>Produit';
if ($split_quantity) { $informationTable_header .= '<th style="text-align: center;">U'; }
if ($_SESSION['ds_customname'] == 'Espace Paysages' || $_SESSION['ds_customname'] == 'Espace 7' || $_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Jurion Protection') { $informationTable_header .= '<th style="text-align: center;">QUANTITÉ'; } # TODO term
else { $informationTable_header .= '<th>Quantité'; }
if ($_SESSION['ds_uselocalbol'] == 2) { $informationTable_header .= '<th>Poids'; }
if (!$isnotice)
{
  if ($_SESSION['ds_customname'] == 'Espace Paysages' || $_SESSION['ds_customname'] == 'Espace 7' || $_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Jurion Protection') { $informationTable_header .= '<th style="text-align: center;">PU'; } # TODO term
  else { $informationTable_header .= '<th>Prix UHT'; }
  if ($totalrebate > 0) { $informationTable_header .= '<th>Remise'; }
  if ($num_vatrate > 1) { $informationTable_header .= '<th>TVA'; }
  if ($_SESSION['ds_customname'] == 'Espace Paysages' || $_SESSION['ds_customname'] == 'Espace 7' || $_SESSION['ds_customname'] == 'Pacific Batiment' || $_SESSION['ds_customname'] == 'Jurion Protection') { $informationTable_header .= '<th style="text-align: center;">Montant'; } # TODO option
  else { $informationTable_header .= '<th>Montant'; }
}
$informationTable_header .= '</thead>';

$informationTable = '';

for ($y = 0; $y < $num_lines; $y++)
{
  $row2 = $main_result[$y];
  if (!$row2['gn_optional'])
  {
    $totalht += $row2['lineprice'];
    $totaltva += $row2['linevat'];
    $totalprice += $row2['lineprice'] + $row2['linevat'];
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
    $gr = myfix(100 * $row2['givenrebate'] / ($row2['lineprice'] + $row2['givenrebate']));
    #$gr = myfix(myround((100 * $row2['givenrebate'] / $bcpdivider) / ($quantity))); changed 2018 12 07
    if ($gr == 0)
    {
      $gr = '&nbsp;';
    }
    else
    {
      $gr = $gr . '<span class="small-percent">%</span>';
    }
  }
  elseif ($row2['rebate_type'] == 2)
  {
    $gr = myfix(myround($row2['givenrebate'] / $row2['basecartonprice']));
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
  $tvaM[$kladd] += myround($row2['linevat']);
  $tvaMt[$kladd] += myround($row2['lineprice']);

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

  if ($_SESSION['ds_useunits'] == 1 && $row2['numberperunit'] > 1)
  {
    $productname = $productname . $row2['numberperunit'] . ' x ';
  }

  $productname = $productname . $row2['netweightlabel'];
  if (floor($y / $linesperpage) == ($pagenumber - 1))
  {
    if ($_SESSION['ds_use_invoiceitemgroup'] && ($y==0 || $row2['gn'] != $main_result[($y-1)]['gn']) && $row2['gn'] > 0)
    {
      $informationTable .= '<tr><td class="items" style="text-align: left;"> &nbsp; ';
      $start_whitespace = strlen($titleA[$row2['gn']])-strlen(ltrim($titleA[$row2['gn']]));
      $titleA[$row2['gn']] = ($row2['gn']+0) . ' ' . ltrim($titleA[$row2['gn']]);
      for ($x=0; $x < $start_whitespace; $x++)
      {
        $titleA[$row2['gn']] = ' '.$titleA[$row2['gn']];
      }
      $informationTable .= '<b>' .d_output(str_replace(' ','&nbsp;',$titleA[$row2['gn']])).'</b>';
      $informationTable .= '<td class="items"><td class="items"><td class="items"><td class="items">';
    }

    if (isset($_POST['custominvoice_changefields']) && $y>0) # TODO 2nd,3rd page, avoid first line
    {
      $informationTable .= '<tr><td class="items">&nbsp;<td class="items"><td class="items"><td class="items"><td class="items">';
    }
    $informationTable .= '<tr>';

    $informationTable .= '<td class="items breakme" style="text-align: left;"';
    if ($_SESSION['ds_use_invoiceitemgroup'] && $row2['gn_optional']) { $productname = 'OPTION : '.$productname; }
    $informationTable .= '>' . $productname;


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

    if ($split_quantity)
    {
      if($_SESSION['ds_customname'] == 'Espace 7')
      {
        $informationTable .= '<td class="numbers" style="text-align: center !important" colspan=2>' . $quantity;
      }
      else
      {
        $informationTable .= '<td class="items numbers" style="text-align: center !important">'.$unittypename
        .'<td class="numbers" style="text-align: center !important">' . $quantity;
      }
    }
    else { $informationTable .= '<td class="items numbers">' . $quantity . ' ' . $unittypename; }
    
    $showlineprice = myfix($row2['lineprice']);
    if ($_SESSION['ds_use_invoiceitemgroup'] && $row2['gn_optional'])
    {
      if ($totalrebate > 0) { $gr = 'En option'; $showlineprice = myfix($bcp * $quantity); }
      else { $showlineprice = 'PM'; }
    }
    $informationTable .= '<td class="items numbers">' . myfix($bcp) . '</td>';
    if ($totalrebate > 0 && $_SESSION['ds_customname'] != 'Espace 7')
    {
      $informationTable .= '<td class="items numbers">' . $gr;
      if ($template <= 6) { $informationTable .= '<td class="items numbers">' . myfix($row2['lineprice'] / $quantity); }
    }
    #if ($template <= 6 || $num_vatrate > 1) { $informationTable .= '<td class="items numbers">' . $showtva; }
    $informationTable .= '<td class="items numbers">' . $showlineprice;

    if ($row2['itemcomment'] != "")
    {
      $itemcomment = str_replace('§', '<br>', $row2['itemcomment']);
      $informationTable .= '<tr>';
      #$informationTable .= '<td class="items breakme letters" colspan=20><span class="item-comment">' . $itemcomment . '</span></td></tr>';
      $informationTable .= '<td class="items breakme letters"><span class="item-comment">' . $itemcomment . '</span>';
      if($_SESSION['ds_customname'] == 'Espace 7')
      {
        $informationTable .= '<td class="items" colspan=2>';
      }
      else
      {
        $informationTable .= '<td class="items"><td class="items">';
      }
      $informationTable .= '<td class="items"><td class="items">';
    }
  }
  if ($_SESSION['ds_use_invoiceitemgroup'])
  {
    if (!$row2['gn_optional']) { $subtotal += $row2['lineprice']; }
    $subtotal_lines++;
    if (!isset($main_result[($y+1)]['gn']) || $row2['gn'] != $main_result[($y+1)]['gn'])
    {
      if ($subtotal_lines > 1 && floor($y / $linesperpage) == ($pagenumber - 1))
      {
        $informationTable .= '<tr><td class="items"><td class="items"><td class="items"><td class="items">';
        $informationTable .= '<b>Sous-total<td class="items" align=right><b>'.myfix($subtotal);
      }
      $subtotal = 0;
      $subtotal_lines = 0;
    }
  }
}

### 2019 02 18 find returns for this invoice
$query = 'SELECT linevalue,linedate,serial,eancode,displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitem.productid,productname,suppliercode,
          productdetails,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,
          lineprice,linevat,itemcomment,taxcode,rebate_type,invoiceitemid,weight,volume
          FROM invoice,invoiceitem,product,unittype,taxcode
          WHERE invoiceitem.invoiceid = invoice.invoiceid
          and invoiceitem.productid = product.productid
          AND product.unittypeid = unittype.unittypeid
          AND product.taxcodeid = taxcode.taxcodeid
          AND invoice.field1=?
          and invoice.clientid=?
          and isreturn=1 and cancelledid=0
          ORDER BY invoiceitemid';

if ($usehistory)
{
  $query = 'SELECT linevalue,linedate,serial,eancode,displaymultiplier,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode,productdetails,
            unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,
            linevat,itemcomment,taxcode,rebate_type,invoiceitemid,weight,volume
            FROM invoicehistory,invoiceitemhistory,product,unittype,taxcode
            WHERE invoiceitemhistory.invoiceid = invoicehistory.invoiceid
            and invoiceitemhistory.productid = product.productid
            AND product.unittypeid = unittype.unittypeid
            AND product.taxcodeid = taxcode.taxcodeid
            AND invoicehistory.field1=?
            and invoicehistory.clientid=?
            and isreturn=1 and cancelledid=0
            ORDER BY invoiceitemid';
}
$query_prm = array($invoiceid,$clientid);
require('inc/doquery.php');
$show_global_discount = ''; $totalht_deducted = $totalht;
for($y=0;$y < $num_results;$y++)
{
  $row2 = $query_result[$y];
  $quantity = $row2['quantity'];
  $unittypename = $row2['unittypename'];
  $bcp = $row2['basecartonprice'];
  /*
  if (isset($_POST['custominvoice_changefields']) && $y>0) # TODO 2nd,3rd page, avoid first line
  {
    $informationTable .= '<tr><td class="items">&nbsp;<td class="items"><td class="items"><td class="items"><td class="items">';
  }
  $informationTable .= '<tr><td class="items breakme letters">&nbsp;<span class="item-comment"></span>
  <td class="items"><td class="items"><td class="items"><td class="items">';
  $informationTable .= '<tr>';
  $informationTable .= '<td class="items breakme" style="text-align: left;">' . d_decode($row2['productname']);
  if ($hidediscount == 1)
  {
    $bcp = $row2['lineprice'] / $quantity;
  }
  if ($row2['displaymultiplier'] != 1)
  {
    $quantity = $quantity / $row2['displaymultiplier'];
    $bcp = $row2['basecartonprice'] * $row2['displaymultiplier'];
  }
  if ($split_quantity) { $informationTable .= '<td class="items numbers" style="text-align: center !important">'.$unittypename.'<td class="numbers" style="text-align: center !important">' . $quantity; }
  else { $informationTable .= '<td class="items numbers">' . $quantity . ' ' . $unittypename; }
  $showlineprice = myfix($row2['lineprice']);
  $informationTable .= '<td class="items numbers">' . myfix($bcp) . '</td>';
  if ($totalrebate > 0)
  {
    $informationTable .= '<td class="items numbers">';
  }
  $informationTable .= '<td class="items numbers">-' . $showlineprice;
  if ($row2['itemcomment'] != "")
  {
    $itemcomment = str_replace('§', '<br>', $row2['itemcomment']);
    $informationTable .= '<tr>';
    $informationTable .= '<td class="items breakme letters"><span class="item-comment">' . $itemcomment . '</span>
    <td class="items"><td class="items"><td class="items"><td class="items">';
  }
  */
  $totaltva -= $row2['linevat'];
  $totalprice -= $row2['lineprice']+$row2['linevat'];
  $totalht_deducted -= $row2['lineprice'];
  $show_global_discount .= '<tr><td style="border-left: 1px solid #000000;">'.d_decode($row2['productname']).'
  <td class="numbers" style="border-right: 1px solid #000000;" colspan=5>-' . myfix($row2['lineprice']);
}
###

if ($totalpages == 1 || $pagenumber == $totalpages)
{
  if ($isnotice && !$fake_isnotice || $hideprices == 1)
  {
    
  }
  else
  {
    $informationTotalPages .= '<tr><td style="border-left: 1px solid #000000; border-top: 1px solid #000000;">Montant HT
    <td class="numbers" style="border-right: 1px solid #000000; border-top: 1px solid #000000;" colspan=5>' . myfix($totalht);
    if (isset($show_global_discount))
    {
      $informationTotalPages .= $show_global_discount;
      $informationTotalPages .='<tr><td style="border-left: 1px solid #000000;">Montant Total Net HT
      <td class="numbers" style="border-right: 1px solid #000000;" colspan=5>' . myfix($totalht_deducted);
    }
    if ($totaltva > 0)
    {
      $informationTotalPages .= '<tr><td style="border-left: 1px solid #000000;">T.V.A. 13%
      <td class="numbers" style="border-right: 1px solid #000000;" colspan=5>' . myfix($totaltva);
    }
    $informationTotalPages .= '<tr><td style="border-left: 1px solid #000000;">Montant Total Net TTC
    <td colspan=3>
    <td class="numbers" style="border-right: 1px solid #000000; border-top: 1px solid #000000;">' . myfix($totalprice);
    
    if ($_SESSION['ds_invoicedeductions'] == 1)
    {
      $totalprice_deductions = $totalprice;
      $query = 'SELECT deduction_desc, deduction, linenr
                FROM invoicededuction
                WHERE invoiceid = ? and deduction>0
                ORDER BY linenr';

      $query_prm = array($invoiceid);
      require('inc/doquery.php');

      for ($i = 0; $i < $num_results; $i++)
      {
        $informationTotalPages .= '<tr><td style="border-left: 1px solid #000000;">'.d_output($query_result[$i]['deduction_desc']).'
        <td colspan=3>
        <td class="numbers" style="border-right: 1px solid #000000">-' . myfix($query_result[$i]['deduction']);
        $totalprice_deductions -= $query_result[$i]['deduction'];
      }
      if ($totalprice_deductions != $totalprice)
      {
        $informationTotalPages .= '<tr><td style="border-left: 1px solid #000000;">NET A PAYER F cfp
        <td colspan=3>
        <td class="numbers" style="border-right: 1px solid #000000; border-top: 1px solid #000000;">' . myfix($totalprice_deductions);
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

  if ($totalpaid > 0 || $matchingid > 0) # if ($totalpaid >= $invoiceprice || $matchingid > 0)
  {
    if ($totalpaid >= $invoiceprice || $matchingid > 0) { $informationIsNotice .= '<p>Cette facture a été entièrement réglée.'; }
    else { $informationIsNotice .= '<p>Cette facture a été <i>partiellement</i> réglée.'; }

    for ($y = 0; $y < $num_results; $y++)
    {
      $paymentid = $query_result[$y]['paymentid'];
      $paymenttypename = $query_result[$y]['paymenttypename'];
      $paymenttypeid = $query_result[$y]['paymenttypeid'];
      $bankid = $query_result[$y]['bankid'];
      $chequeno = $query_result[$y]['chequeno'];
      
      if ($query_result[$y]['reimbursement'] == 0)
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
        if ($num_results > 1)
        {
          $informationIsNotice .= ' '.myfix($query_result[$y]['value']).' XPF';
        }
        $informationIsNotice .= ')';
      }
    }
    $informationIsNotice .= '</p>';
  }
}

### modified copy from invoicing.php
$points_string = '';
if ($_SESSION['ds_use_loyalty_points'] && $use_loyalty_points)
{
  require('preload/taxcode.php');
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
        $kladd = round($query_result[$i]['givenrebate'] + ($query_result[$i]['givenrebate'] * $taxcodeA[$query_result[$i]['linetaxcodeid']] / 100)); #echo ' -',$kladd;
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
      $kladd = round(($query_result[$i]['lineprice'] + $query_result[$i]['linevat']) * $_SESSION['ds_loyalty_points_percent'] / 100); #echo ' +',$kladd;
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

  if ($loyaltydate != '0000-00-00' && $loyaltydate != null) { $points_string .= '<tr><td colspan=4><b>Date Fidelité : '.datefix($loyaltydate); }
  if ($points > 0) { $points_string .= '<p><b>Points de fidelité : '.myfix($points) . '</b> '; }
  if ($points_gained > 0 || $points_used > 0) { $points_string .= '(Sur cette facture : '; }
  if ($points_gained > 0) { $points_string .= ' Acquis = '.myfix($points_gained); }
  if ($points_used > 0) { $points_string .= ' Utilisé = '.myfix($points_used); }
  if ($points_gained > 0 || $points_used > 0) { $points_string .= ')'; }
  $points_string .= '</p>';
}

#############################


echo '<link rel="stylesheet" href="tem-saas.com/declaration/bootstrap.css">';

require('custom/espace paysagesshowinvoice_style.php');

require_once('printwindow/invoice_options.php');

require('custom/espace paysagesshowinvoice_layout.php');

require('printwindow/style_print.php');

if ($logmeout == 1)
{
  session_unset();
  session_destroy();
}