<?php

require_once('printwindow/invoice_options.php');

$output = '';

$output .= '<div class="main">';

if (file_exists($ourlogofile))
{
  $imagesizeA = getimagesize($ourlogofile);
  $output .= '<div class="logo"><img src="'.$ourlogofile.'"';
  if ($imagesizeA[1] > 150) { $output .= ' height=150'; }
  $output .= '></div>';
}

if ($totalpages == 1 || $pagenumber == $totalpages)
{
  if ($_SESSION['ds_customname'] != 'Fenua Pharm')
  {
    $output .= '<div class="logo-tem2"><img src="pics/logo.png" height="50"></div>';
  }
}

if ($_SESSION['ds_customname'] == 'Team ELEC') # TODO option _sig
{
  $output .= '<div class="sig-image"><img src="custom_available/team elec_sig.jpg"></div>';
} 

$output .= '<div class="companyinfo">';
if ($show_idtahiti)
{
  if ($idtahiti == '') { $idtahiti = '<span class="alert">OBLIGATOIRE - À définir</span>'; }
  $output .= 'Numéro TAHITI : '.$idtahiti.'<br>';
  #if ($rc != '') { $output .= 'RC : '.$rc.'<br>'; } # 2019 12 26 removing RC for now, not mandatory, takes space
}
$output .= $_SESSION['ds_companyinfo'];
$output .= '</div>';

$output .= '<div class="clientinfo">';
$output .= '<span class="clientname">'.d_output($companytypename).' '.d_output(d_decode($clientname));
if ($_SESSION['ds_customname'] == 'TT')
{
  $output .= '<span style="font-size: x-small;
  font-weight: normal;
  padding: 0;
  margin: 0;
  white-space-collapsing: discard;
  ">'; # TODO nothing works, remove white space between lines
  if ($clientfield1 != '' || $clientfield2 != '' || $clientfield3 != '') { $output .= '<br>'; }
  if ($clientfield1 != '') { $output .= '<b>'.$_SESSION['ds_term_clientfield1'].'</b>: '.d_output($clientfield1); }
  if ($clientfield2 != '') { $output .= ' <b>'.$_SESSION['ds_term_clientfield2'].'</b>: '.d_output($clientfield2); }
  if ($clientfield3 != '') { $output .= ' <b>'.$_SESSION['ds_term_clientfield3'].'</b>: '.d_output($clientfield3); }
  if ($clientfield5 != '' || $clientfield5 != '' || $client_customdate1 != '') { $output .= '<br>'; }
  if ($clientfield4 != '') { $output .= '<b>'.$_SESSION['ds_term_clientfield4'].'</b>: '.d_output($clientfield4); }
  if ($clientfield5 != '') { $output .= ' <b>'.$_SESSION['ds_term_clientfield5'].'</b>: '.d_output($clientfield5); }
  if ($client_customdate1 != '') { $output .= ' <b>Date de naissance</b>: '.datefix($client_customdate1,'short'); }
  $output .= '</span>';
}
$output .= '</span>';
if ($extraname != '')
{
  $output .= '<br><span class="header_title">'.d_output($_SESSION['ds_term_extraname']).' : </span>' . $extraname;
}
if (isset($address) && !empty($address)) { $output .= '<br>'.d_output($address); }
if (isset($postaladdress) && $postaladdress != '') { $output .= '<br>' . d_output($postaladdress); }
$output .= '<br>' . $postalcode;
if (ctype_digit(preg_replace('/\s+/', '', $postalcode)))
{
  if ($town_name != '') { $output .= ' ' . $town_name; }
  else { $output .= ' ' . $townA[$townid]; }
}
if (isset($countryA[$row['countryid']]))
{
  if ($countryA[$row['countryid']] == 'Polynésie française')
  {
    $output .= ' ' . $islandA[$town_islandidA[$townid]];
  }
  else
  {
    $output .= '<br>' . $countryA[$row['countryid']];
  }
}
if ($telephone != '') { $output .= '<br>Tél: '.d_output($telephone); }
elseif ($cellphone != '') { $output .= '<br>Tél: '.d_output($cellphone); }
if ($email != '') { $output .= '<br>'.d_output($email); }
$output .= '</div>';

if ($invoice_title_below == 0)
{
  $output .= '<div class="invoicetitle">';
  $output .= mb_convert_case($typetext, MB_CASE_UPPER, "UTF-8");
  $output .= ' ' . $format_invoiceid;
  if ($cancelledid == 2) { $output .= ' ARCHIVÉ(E)'; }
  if ($totalpages > 1)
  {
    $output .= ' &nbsp; Page '.$pagenumber.' / ' . $totalpages;
  }
  $output .= '</div>';
}

$output .= '<div class="invoicedate">';
$output .= datefix($accountingdate);
if ($paybydate != $accountingdate
&& $_SESSION['ds_customname'] != 'TERE UTA'
&& $_SESSION['ds_customname'] != 'Fenua AC Cleaner'
&& $confirmed == 1) { $output .= '<br>Échéance : ' . datefix($paybydate); }
if (isset($custominvoicedate) && $custominvoicedate != '0000-00-00')
{ $output .= '<br>'.d_output($_SESSION['ds_term_custominvoicedate']).' : '.datefix($custominvoicedate); }
$output .= '</div>';

$output .= '<div class="items">';

if ($invoice_title_below == 1)
{
  $output .= '<span class="invoicetitle_below">';
  $output .= mb_convert_case($typetext, MB_CASE_UPPER, "UTF-8");
  $output .= ' ' . $format_invoiceid;
  if ($totalpages > 1)
  {
    $output .= ' &nbsp; Page '.$pagenumber.' / ' . $totalpages;
  }
  $output .= '</span>';
}

if ($reference != '' || $extraname != '' || $employeename != '' || $invoicetagname != '' || $invoicecomment != ''
   || $field1 != '' || ($field2 != '' && $field2 != 'Échelonnée'))
{
  $output .= '<table class="invoiceitems_header">';
  if ($_SESSION['ds_showtimeprinted'] == 2)
  {
    $output .= '<tr><td colspan=2>Le '. datefix($invoicedate).' à '.$invoicetime.' par '. d_output($username);
  }
  elseif ($_SESSION['ds_showtimeprinted'] == 1)
  {
    $output .= '<tr><td colspan=2>Le '. datefix($_SESSION['ds_curdate']).' à '.$_SESSION['ds_curtime'].' par '. d_output($_SESSION['ds_initials']);
  }
  $output .= '<tr><td width=50% valign=top>';
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
  /*
  if ($extraname != '')
  {
    $output .= '<span class="header_title">'.$_SESSION['ds_term_extraname'].' : </span>';
    $output .= $extraname;
  }*/
  if ($invoicetagname != '')
  {
    if ($extraname != '') $output .= '<br>';
    $output .= '<span class="header_title">'.$_SESSION['ds_term_invoicetag'].' : </span>';
    $output .= $invoicetagname;
  }  
  if ($field1 != '' || ($field2 != '' && $field2 != 'Échelonnée'))
  {
    $output .= '<tr><td><b>';
    if ($field1 != '') { $output .= d_output($_SESSION['ds_term_field1']).'</b> : '.d_output($field1); }
    $output .= '<td><b>';
    if ($field2 != '') { $output .= d_output($_SESSION['ds_term_field2']).'</b> : '.d_output($field2); }
  }
  if ($invoicecomment != '')
  {
    $output .= '<tr><td colspan=2>';
    if ($_SESSION['ds_customname'] == 'Tahiti Crew') { $output .= '<b><font size=+2>'; } # TODO options
    elseif ($_SESSION['ds_customname'] == 'Fenua AC Cleaner') { $output .= '<b>'; }
    $output .= d_output($invoicecomment);
  }
  $output .= '</table><span>&nbsp;</span>';
}
if ($invoicecomment2 != '')
{
  $output .= '<span>'.$invoicecomment2.'</span>';
}
$output .= '<table class="invoiceitems">';
$output .= $informationTable_header;
$output .= $informationTable;
$output .= '</table>';

$totals = '';
if ($totalpages == 1 || $pagenumber == $totalpages)
{
  $totals = '<table class="invoiceitems_sub"><tr><td width=25% valign=top>';
  if ($totaltva > 0)
  {
    $totals .= '<table class="invoiceitems"><tr><td>Taux TVA&nbsp;<td>Base HT&nbsp;<td>Montant TVA';
    foreach ($taxcodeA as $taxcodeid => $taxcode)
    {
      if (isset($tvaM[$taxcode]) && $tvaM[$taxcode] > 0)
      {
        $totals .= '<tr>';
        $totals .= '<td align="center"> '.$taxcode.'<span class="small-percent">%</span>&nbsp;';
        $totals .= '<td align="right"> '.myfix($tvaMt[$taxcode]);
        $totals .= '<td align="right"> '.myfix($tvaM[$taxcode]);
      }
    }
    $totals .= '</table>';
  }
  $totals .= '<td width=50%><td width=25% valign=top>';
  $totals .= '<table class="invoiceitems">';
  $totals .= $informationTotalPages;
  $totals .= '</table>';
  $totals .= '</table>';
  # TODO option for this?
  if ($_SESSION['ds_customname'] == 'TERE UTA')
  {
    $totals .= '<br><table width=100%><tr><td width=75% valign=top>';
    if ($confirmed == 1 && $isreturn == 0)
    {
      if ($informationIsNotice == '')
      {
        $totals .= '<br>Arrêté la présente '.d_output(mb_convert_case($typetext, MB_CASE_LOWER, "UTF-8")).
        ' à la somme de : '.convertir($invoiceprice) . ' CFP';
      }
      else
      {
        $totals .= $informationIsNotice; # TODO conditions, formatting
      }
    }
    $totals .= '<td width=25% align=right valign=top>';
    $totals .= 'Échéance : ' . datefix($paybydate);
    $totals .= '</table>';
  }
  elseif ($confirmed == 1 && $isreturn == 0)
  {
    if ($informationIsNotice == '')
    {
      $totals .= '<br>Arrêté la présente '.d_output(mb_convert_case($typetext, MB_CASE_LOWER, "UTF-8")).
      ' à la somme de : '.convertir($invoiceprice) . ' CFP';
    }
    else
    {
      $totals .= $informationIsNotice; # TODO conditions, formatting
    }
  }
  if (isset($split_invoice_show) && $split_invoice_show != '') { $totals .= $split_invoice_show; }
}

if ($summary_top)
{
  $output .= '<span>&nbsp;</span>';
  $output .= $totals;
}
if ($show_sig)
{
  $output .= $signature_show;
}
$output .= '</div>';

$output .= '<div class="infofact">';
if (!$summary_top)
{
  $output .= $totals;
}
if ($totalpages == 1 || $pagenumber == $totalpages)
{
  $output .= '<span class="small">';
  if (!$confirmed && $_SESSION['ds_quote_info'] != '') { $output .= $_SESSION['ds_quote_info']; }
  else { $output .= $_SESSION['ds_infofact']; }
  $output .= '</span></div>'; # TODO check /div, error?
}
$output .= '</div>';

echo $output;

?>