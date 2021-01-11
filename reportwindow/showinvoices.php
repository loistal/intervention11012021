<?php

require('preload/town.php');
require('preload/island.php');
require('preload/clientterm.php');
require('preload/returnreason.php');
require('preload/localvessel.php');
require('preload/taxcode.php');
require('preload/country.php');
require('preload/employee.php');
require('preload/invoicetag.php');

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['bynumber'] = 'uint';
$PA['startid'] = 'uint';
$PA['stopid'] = 'uint';
$PA['client'] = 'client';
$PA['duplicate'] = 'uint';
$PA['invoicetype'] = 'uint';
$PA['userid'] = 'int';
$PA['localvesselid'] = 'int';
$PA['invoice_grouped'] = 'uint';
$PA['invoice_list'] = '';
require('inc/readpost.php');
if ($invoice_list != '')
{
  $invoice_listA = explode('|', $invoice_list);
  $invoice_list = '(';
  $invoice_field = '(invoicehistory.invoiceid,';
  foreach ($invoice_listA as $kladd)
  {
    $invoice_list .= $kladd . ',';
    $invoice_field .= $kladd . ',';
  }
  $invoice_list = rtrim($invoice_list,',') . ')';
  $invoice_field = rtrim($invoice_field,',') . ')';
  if ($invoice_list == '()') { $invoice_list = '(-1)'; }
}

$customfile = 'custom/' . mb_strtolower($_SESSION['ds_customname']) . 'showinvoices.php';
if (file_exists($customfile))
{ require($customfile); }
else
{

$query = 'select idtahiti from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];

$query = 'select invoiceid,invoicehistory.clientid,clientname,companytypename,address,postaladdress,postalcode
,townid,clienttermid,proforma,isreturn,confirmed,isnotice,returnreasonid,accountingdate,paybydate,custominvoicedate
,localvesselid,invoicevat,invoiceprice,town_name,countryid,reference,invoicehistory.employeeid,extraname,invoicetagid
,invoicecomment
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
if ($invoice_list != '') { $query .= ' and invoiceid in '.$invoice_list; }
elseif ($bynumber) { $query .= ' and invoiceid>=? and invoiceid<=?'; array_push($query_prm, $startid); array_push($query_prm, $stopid); }
else { $query .= ' and accountingdate>=? and accountingdate<=?'; array_push($query_prm, $startdate); array_push($query_prm, $stopdate); }
if ($clientid > 0) { $query .= ' and invoicehistory.clientid=?'; array_push($query_prm, $clientid); }
if ($localvesselid > 0) { $query .= ' and invoicehistory.localvesselid=?'; array_push($query_prm, $localvesselid); }
if ($userid > 0) { $query .= ' and invoicehistory.userid=?'; array_push($query_prm, $userid); }
if ($invoice_field != '') { $query .= ' order by field'.$invoice_field; }
$query .= ' LIMIT 1000';
require('inc/doquery.php');
$main_result_top = $query_result; $num_results_main = $num_results;

for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result_top[$i];
  $proforma = $row['proforma'];
  $isreturn = $row['isreturn'];
  $confirmed = $row['confirmed'];
  $isnotice = (int) $row['isnotice'];
  $returnreasonid = $row['returnreasonid'];
  $totaltva = $row['invoicevat'];
  $invoiceprice = $row['invoiceprice'];
  $fake_isnotice = 0;
  if ($isnotice && $_SESSION['ds_customname'] == 'Natural & Organic') # TODO option (what does this do and why?)
  {
    $fake_isnotice = 1;
  }
  
  $outputstring = '';
  if ($i>0) { $outputstring = $outputstring . '<p class=breakhere></p>'; }
  
  $outputstring = $outputstring . '<table class="transparent" border=0 cellspacing=1 cellpadding=1><tr><td valign=top width=380>';

  $ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
  if (file_exists($ourlogofile)) { $outputstring = $outputstring . '<p><img style="max-width:200px" src="' . $ourlogofile . '"></p>'; }
  
  $outputstring = $outputstring . '<p>';
  $outputstring .= 'Numéro TAHITI : '.$idtahiti.'<br>';
  $outputstring = $outputstring . $_SESSION['ds_companyinfo'];
  $outputstring = $outputstring . '</p>';

  $outputstring = $outputstring . '</td><td valign=top>&nbsp; &nbsp; &nbsp;</td><td valign=top>';

  $typetext = 'Facture ';
  if ($proforma == 1) { $typetext = 'Proforma '; }
  if ($isnotice) { $typetext = $_SESSION['ds_term_invoicenotice']; }
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
  $outputstring .= '<h2>' . $typetext . $row['invoiceid'] .'</h2>';
  $outputstring .= '<p>'.datefix2($row['accountingdate']).'<br>';
  if ($row['paybydate'] != $row['accountingdate'] && $confirmed == 1)
  { $outputstring .= '<p>Échéance : ' . datefix($row['paybydate']) . '<br>'; }
  $outputstring .= '</p><br>';

  /*
  $outputstring = $outputstring . '<p>' . d_output(d_decode($row['clientname'])) . ' ' . $row['companytypename'];
  if ($row['address'] != "") { $outputstring = $outputstring . '<br>' . $row['address']; }
  if ($row['postaladdress'] != "") { $outputstring = $outputstring . '<br>' . $row['postaladdress']; }
  $outputstring = $outputstring . '<br>' . $row['postalcode'] . ' ' . $townA[$row['townid']];
  $outputstring = $outputstring . '<br>' . $islandA[$town_islandidA[$row['townid']]];
  */
  $output = '<b>'.d_output(d_decode($row['clientname'])).'</b>';
  if (isset($row['address']) && !empty($row['address'])) { $output .= '<br>'.d_output($row['address']); }
  if (isset($row['postaladdress']) && $row['postaladdress'] != '') { $output .= '<br>' . d_output($row['postaladdress']); }
  $output .= '<br>' . $row['postalcode'];
  if (ctype_digit(preg_replace('/\s+/', '', $row['postalcode'])))
  {
    if ($row['town_name'] != '') { $output .= ' ' . $row['town_name']; }
    else { $output .= ' ' . $townA[$row['townid']]; }
  }
  if ($countryA[$row['countryid']] == 'Polynésie française')
  {
    $output .= '<br>' . $islandA[$town_islandidA[$row['townid']]];
  }
  else
  {
    $output .= '<br>' . $countryA[$row['countryid']];
  }
  $outputstring .= $output;

  $outputstring .= '</p></table>';

  $output = '';
  $reference = $row['reference'];
  $extraname = $row['extraname'];
  $employeename = ''; if (isset($employeeA[$row['employeeid']])) { $employeename = $employeeA[$row['employeeid']]; }
  $invoicetagname = ''; if (isset($invoicetagA[$row['invoicetagid']])) { $employeename = $invoicetagA[$row['invoicetagid']]; }
  $invoicecomment = $row['invoicecomment'];
  if ($reference != '' || $extraname != '' || $employeename != '' || $invoicetagname != '' || $invoicecomment != '')
  {
    $output .= '<table class="transparent" width=100%><tr><td width=50% valign=top>';
    if ($reference != '')
    {
      $output .= '<span class="header_title">'.$_SESSION['ds_term_reference'].' : </span>';
      $output .= $reference;
    }
    if ($employeename != '')
    {
      if ($reference != '') $output .= '<br>';
      $output .= '<span class="header_title">'.$_SESSION['ds_term_servedby'].' : </span>';
      $output .= $employeename;
    }
    $output .= '<td width=50% valign=top>';
    if ($extraname != '')
    {
      $output .= '<span class="header_title">'.$_SESSION['ds_term_extraname'].' : </span>';
      $output .= $extraname;
    }
    if ($invoicetagname != '')
    {
      if ($extraname != '') $output .= '<br>';
      $output .= '<span class="header_title">'.$_SESSION['ds_term_invoicetag'].' : </span>';
      $output .= $invoicetagname;
    }  
    $output .= '';
    if ($invoicecomment != '')
    {
      $output .= '<tr><td colspan=2>' . str_replace('§', '<br>', d_output($invoicecomment));
    }
    $output .= '</table><span>&nbsp;</span>';
  }
  $outputstring .= $output;

#################################################################################################
# dirty copy

$totalht = 0;
$hidediscount = 0;
$hideprices = 0;

$query = 'SELECT linedate,employeeid,serial,eancode,displaymultiplier,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode
          ,productdetails,unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice
          ,lineprice,linevat,itemcomment,taxcode,rebate_type,invoiceitemid
          FROM invoiceitemhistory,product,unittype,taxcode
          WHERE invoiceitemhistory.productid = product.productid
          AND product.unittypeid = unittype.unittypeid
          AND product.taxcodeid = taxcode.taxcodeid
          AND invoiceitemhistory.invoiceid = ?
          ORDER BY invoiceitemid';
$query_prm = array($row['invoiceid']);
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
  }
  d_sortresults($main_result, 'gn', $num_lines);
}

$totalrebate = 0;

for ($y = 0; $y < $num_lines; $y++)
{
  $totalrebate += $main_result[$y]['givenrebate'];
}

$colspan = 7;

if ($totalrebate == 0)
{
  $colspan = $colspan - 2;
}

if ($_SESSION['ds_useitemadd'])
{
  $colspan = $colspan + 4;
}

$informationTable = '';

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
    $gr = myfix(myround((100 * $row2['givenrebate'] / $bcpdivider) / ($quantity)));
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

  $productname = d_decode($row2['productname']);

  if ($_SESSION['ds_useunits'] == 1 && $row2['numberperunit'] > 1)
  {
    $productname = $productname . $row2['numberperunit'] . ' x ';
  }

  $productname = $productname . $row2['netweightlabel'];
  if (1==1)
  {
    if ($_SESSION['ds_use_invoiceitemgroup'] && ($y==0 || $row2['gn'] != $main_result[($y-1)]['gn']))
    {
      $informationTable .= '<tr><td style="text-align: left;" colspan=30>';
      if ($row2['gn'] > 0)
      {
        if (floor($row2['gn']) != $row2['gn']) { $informationTable .= ' &nbsp; '; }
        $informationTable .= '<b>';
      }
      $informationTable .= d_output($row2['title']).'</b>';
    }
    
    $informationTable .= '<tr>';

    if ($_SESSION['ds_useitemadd'] || $_SESSION['ds_useserialnumbers'])
    {

      if ($_SESSION['ds_useitemadd'])
      {
        $informationTable .= '<td>' . datefix2($row2['linedate']) . '</td><td></td><td></td><td>' . $employeeA[$employeeid] . '</td>';
      }

      if ($_SESSION['ds_useserialnumbers'] && $row2['serial'] != '')
      {
        $productname .= ' [' . $row2['serial'] . ']';
      }
    }

    if ($_SESSION['ds_use_invoiceitemgroup'])
    {
      $informationTable .= '<td>';
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
    $informationTable .= '<td class="breakme" style="text-align: left;">' . $productname;

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

    $informationTable .= '<td align=right>' . $quantity . ' ' . $unittypename;
    
    if ($_SESSION['ds_use_invoiceitemgroup'] && $row2['gn_optional'])
    {
      $gr = 'En option';
      $row2['lineprice'] = $bcp * $quantity;
    }

    if ($isnotice && !$fake_isnotice || $hideprices == 1)
    {

    }
    else
    {
      $informationTable .= '<td align=right>' . myfix($bcp) . '</td>';

      if ($totalrebate > 0)
      {
        $informationTable .= '<td align=right>' . $gr . '
        <td class="numbers">' . myfix($row2['lineprice'] / $quantity);
      }
      $informationTable .= '<td align=right>' . $showtva . '</td><td align=right>' . myfix($row2['lineprice']);
    }

    if ($row2['itemcomment'] != "")
    {
      $itemcomment = str_replace('§', '<br>', $row2['itemcomment']);
      $informationTable .= '<tr>';
      $informationTable .= '<td>';
      $informationTable .= '<td class="breakme letters" colspan=' . ($colspan + 1) . '><span class="item-comment">' . $itemcomment . '</span></td></tr>';
    }
  }
  if ($_SESSION['ds_use_invoiceitemgroup'])
  {
    $subtotal += $row2['lineprice'];
    if (!isset($main_result[($y+1)]['gn']) || $row2['gn'] != $main_result[($y+1)]['gn'])
    {
      $informationTable .= '<tr><td><b>Sous-total<td colspan=8 align=right><b>'.myfix($subtotal);
      $subtotal = 0;
    }
  }
}

  
  $outputstring .= '<table class=report width=100%><thead>';
  if ($_SESSION['ds_useitemadd'])
  {
    $outputstring .= '<th class="letters">Date<th class="letters">Début<th class="letters">Fin<th class="letters">Employé';
  }
  $outputstring .= '<th colspan=2>Produit</th>';
  $outputstring .= '<th class="numbers">Quantité</th>';
  if ($isnotice && !$fake_isnotice || $hideprices == 1) {} else
  {
    $outputstring .= '<th class="numbers">Prix UHT</th>';
    if ($totalrebate > 0)
    {
      $outputstring .= '<th class="numbers">Remise</th>';
      $outputstring .= '<th class="numbers">PUHT Net</th>';
    }
    $outputstring .= '<th class="numbers">TVA</th>';
    $outputstring .= '<th class="numbers">Total</th>';
  }
  $outputstring .= '</thead>';
  $outputstring .= $informationTable;
  
  $informationTotalPages = '';
  $informationTotalPages .= '<tr><td colspan=' . $colspan . '>Total HT</td><td align=right>' . myfix($totalht) . '</td></tr>';
  if ($totaltva > 0)
  {
    $informationTotalPages .= '<tr><td colspan=' . $colspan . '>TVA</td><td align=right>' . myfix($totaltva) . '</td></tr>';
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
      $informationTotalPages .= 'Total à payer';
    }
  }

  $informationTotalPages .= '</td><td align=right><b>' . myfix($invoiceprice) . '</b></td></tr>';
    
  if ($isnotice && !$fake_isnotice)
  {
    
  }
  else
  {
    $informationIsNotice = '';
  }
  
  $outputstring .= $informationTotalPages;
  $outputstring .= '</table>';
  
  
################################################################################################
  
  
  $outputstring = $outputstring . '</td></tr><tr><td colspan="2" align="center"></td></tr></table>';
  $outputstring = $outputstring . '<br>';

  $outputstring = $outputstring . '<p STYLE="font-size: 65%">' . str_replace("\n<br>",'',$_SESSION['ds_infofact']) . '</p>';
  
  $outputstring = $outputstring . '<span STYLE="text-align: right; width: 99%"><img src="pics/logo.png" height="50"></span>';
  echo $outputstring;
  if ($duplicate) { echo '<p class=breakhere></p>'.$outputstring; }
}

}

?>