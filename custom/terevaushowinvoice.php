<?php

# 2018 12 23 quick copy/modify from template 7



$template = 7;
$informationTotalPages = '';
require('preload/unittype_line.php');
require('preload/client.php');
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
if ($template == 7 && $_SESSION['ds_customname'] == 'Espace Paysages') # TODO options
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

$query = 'SELECT initials,clientid2,clientid3,town_name,countryid,localvesselid,invoice.employeeid,invoicevat,field1,field2,townid,invoice.userid,invoicetagid,vatexempt,isnotice,contact,
          invoice.clientid,clientname,extraname,accountingdate,custominvoicedate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,
          proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,
          cellphone,email,fax,extraaddressid,clienttermname,confirmed,matchingid,loyalty_start,use_loyalty_points,returnreasonid
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

  $query = 'SELECT initials,clientid2,clientid3,town_name,countryid,localvesselid,invoicehistory.employeeid,invoicevat,field1,field2,townid,invoicehistory.userid,invoicetagid,vatexempt,isnotice,contact,
            invoicehistory.clientid,clientname,extraname,accountingdate,custominvoicedate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,
            proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,
            cellphone,email,fax,extraaddressid,clienttermname,confirmed,matchingid,loyalty_start,use_loyalty_points,returnreasonid
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
$initials = $row['initials'];
$clientid2 = $row['clientid2'];
$clientid3 = $row['clientid3'];
$returnreasonid = $row['returnreasonid'];
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
$invoiceprice = $row['invoiceprice'];
$userid = (int) $row['userid'];
$totaltva = $row['invoicevat'];

$vesselname = ''; if ($row['localvesselid'] > 0) { $vesselname = $localvesselA[$row['localvesselid']]; }

$cancelledid = $row['cancelledid'];
$reference = $row['reference'];
$extraname = $row['extraname'];

$deliverydate = $row['deliverydate'];
$accountingdate = $row['accountingdate'];
$custominvoicedate = $row['custominvoicedate'];
$paybydate = $row['paybydate'];

/*
HARDCODED (maybe add field "extra info" to invoicetag?)
3=> L'heure départ = au voyage sélectionné 1P = 06:40:00

Départ PPT	Départ Moorea
1P 06H40		1M 05H55
2P 08H10		2M 07H25
3P 11H30		3M 09H00
4P 14H00		4M 12H15
5P 15H55		5M 14H45
6P 17H25		6M 16H40

invoicetagname:
MOO-THT: 1M	
MOO-THT: 2M	
MOO-THT: 3M	
MOO-THT: 4M	
MOO-THT: 5M	
MOO-THT: 6M	
THT-MOO: 1P	
THT-MOO: 2P	
THT-MOO: 3P	
THT-MOO: 4P	
THT-MOO: 5P	
THT-MOO: 6P	

*/
$invoicetagid = $row['invoicetagid'];
$voyage = $invoicetagA[$invoicetagid];
$departure = '';
if (substr($voyage,0,1) == 'M')
{
  $to = 'PAPEETE'; $from = 'MOOREA';
  switch(substr($voyage,9,1))
  {
    case 1:
    $departure = '05H55';
    break;
    case 2:
    $departure = '07H25';
    break;
    case 3:
    $departure = '09H00';
    break;
    case 4:
    $departure = '12H15';
    break;
    case 5:
    $departure = '14H45';
    break;
    case 6:
    $departure = '16H40';
    break;
  }
}
else
{
  $from = 'PAPEETE'; $to = 'MOOREA';
  switch(substr($voyage,9,1))
  {
    case 1:
    $departure = '06H40';
    break;
    case 2:
    $departure = '08H10';
    break;
    case 3:
    $departure = '11H30';
    break;
    case 4:
    $departure = '14H00';
    break;
    case 5:
    $departure = '15H55';
    break;
    case 6:
    $departure = '17H25';
    break;
  }
}

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
  $format_invoiceid = myfix($invoiceid);
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

$query = 'SELECT linevalue,invoiceitem.retailprice,lineproducttypeid,linevalue,linedate,employeeid,serial,eancode,displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitem.productid,productname,suppliercode,
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
  $query = 'SELECT linevalue,invoiceitemhistory.retailprice,lineproducttypeid,linevalue,linedate,employeeid,serial,eancode,displaymultiplier,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode,productdetails,
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
$informationTable_header .= '<th width=20>Nb';
if ($_SESSION['ds_useitemadd']) { $informationTable_header .= '<th>Date<th>Début<th>Fin<th>Employé'; }
$informationTable_header .= '<th>Colis';
$informationTable_header .= '<th>Désignation des articles';
$informationTable_header .= '<th>Valeur déclarée';
$informationTable_header .= '<th>Poids';
$informationTable_header .= '<th>Volume<th>Tarif<th>Montant Frêt';
$informationTable_header .= '</thead>';

$informationTable = '';

$t1=$t2=$t3=$t4=$t5=0;

for ($y = 0; $y < $num_lines; $y++)
{
  $row2 = $main_result[$y];
  $totalht = $totalht + $row2['lineprice'];
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
    $informationTable .= '<td style="text-align: right;">' . d_output($row2['serial']);
    $informationTable .= '<td>';
    if (isset($unittype_lineA[$row2['lineproducttypeid']])) { $informationTable .= d_output($unittype_lineA[$row2['lineproducttypeid']]); }
    $informationTable .= '<td class="breakme letters">' . d_decode($row2['itemcomment']) . '';

    $informationTable .= '<td class="numbers">' . (double) $row2['retailprice'];
    $informationTable .= '<td class="numbers">' . (double) $row2['linevalue'];
    $informationTable .= '<td class="numbers">' . (double) $row2['quantity']/$row2['displaymultiplier'];
    $informationTable .= '<td class="numbers">' . (double) $row2['basecartonprice']*$row2['displaymultiplier'];
    $informationTable .= '<td class="numbers">' . (double) $row2['lineprice'];
    
    $t1 += (int) $row2['serial'];
    $t2 += $row2['retailprice'];
    $t3 += $row2['linevalue'];
    $t4 += $row2['quantity']/$row2['displaymultiplier'];
    $t5 += $row2['lineprice'];
    
  }
  
}

$informationTotalPages .= '<tr><td class="numbers"><b>'.$t1.'<td colspan=2 align=right><b>TOTAL &nbsp; ';
$informationTotalPages .= '<td class="numbers"><b>'.$t2;
$informationTotalPages .= '<td class="numbers"><b>'.$t3;
$informationTotalPages .= '<td class="numbers"><b>'.$t4;
$informationTotalPages .= '<td><td class="numbers"><b>'.$t5;

if ($isnotice && !$fake_isnotice)
{
  
}
else
{
  $informationIsNotice = '';

  $totalpaid = 0;
  $paymentid = 0;

  $query = 'SELECT paymentid,value,reimbursement,paymenttypename,payment.paymenttypeid,bankid,chequeno,paymentdate
            FROM payment,paymenttype
            WHERE payment.paymenttypeid = paymenttype.paymenttypeid
            AND forinvoiceid = ?';
  $query_prm = array($invoiceid);
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

  $show_paybydate = 0;
  if ($totalpaid > 0 || $matchingid > 0)
  {
    if ($totalpaid >= $invoiceprice || $matchingid > 0) { $informationIsNotice .= 'Cette facture a été entièrement réglée.'; }
    else { $informationIsNotice .= 'Cette facture a été <i>partiellement</i> réglée.'; $show_paybydate = 1; }

    for ($y = 0; $y < $num_results; $y++)
    {
      $paymentid = $query_result[$y]['paymentid'];
      $paymenttypename = $query_result[$y]['paymenttypename'];
      $paymenttypeid = $query_result[$y]['paymenttypeid'];
      $bankid = $query_result[$y]['bankid'];
      $chequeno = $query_result[$y]['chequeno'];

      if ($num_results > 1) { $informationIsNotice .= '<br>'; }
      else { $informationIsNotice .= ' '; }
      if ($query_result[$y]['reimbursement'])
      {
        if ($paymenttypeid == 1) { $informationIsNotice .= 'Monnaie rendue'; }
        else { $informationIsNotice .= 'Remboursement '.$paymenttypename; }
      }
      else { $informationIsNotice .= $paymenttypename; }
      $informationIsNotice .= ' du ';
      $informationIsNotice .= datefix($query_result[$y]['paymentdate'],'short').', ';
      if ($bankid > 0 && $paymenttypeid > 1)
      {
        $informationIsNotice .= $bankA[$bankid];
        $informationIsNotice .= ' ' . $chequeno . ', ';
      }
      if ($num_results > 1)
      {
        $informationIsNotice .= 'Montant: '.myfix($query_result[$y]['value']).' XPF';
      }
    } 
  }
  else { $show_paybydate = 1; }
  if ($show_paybydate)
  {
    $informationIsNotice .= '<br>Échéance : ' . datefix($paybydate);
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

require('custom/terevaushowinvoice_style.php');

require_once('printwindow/invoice_options.php');

require('custom/terevaushowinvoice_layout.php');

require('printwindow/style_print.php');

if ($logmeout == 1)
{
  session_unset();
  session_destroy();
}

?>


