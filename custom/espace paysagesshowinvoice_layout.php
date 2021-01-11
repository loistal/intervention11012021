<?php

$output = '';

$output .= '<div class="main">';

if ($stupidlines && ($totalpages == 1 || ($totalpages > 1 && $pagenumber == $totalpages)))
{
  $output .= '<div class="line1"></div>';
  $output .= '<div class="line2"></div>';
}

if (file_exists($ourlogofile))
{
  $imagesizeA = getimagesize($ourlogofile);
  $output .= '<div class="logo"><img src="'.$ourlogofile.'"'; # TODO only page 1
  if ($imagesizeA[1] > 150) { $output .= ' height=150'; }
  $output .= '></div>';
}

if ($totalpages > 1)
{
  
  $output .= '<div class="logo-tem2">Page '.$pagenumber.' / '.$totalpages.'</div>';
  /*
  $output .= '<div class="logo-tem2"><img src="pics/logo.png" height="50"></div>'; # TODO only last page
  */
}

$output .= '<div class="invoicedate">Punaauia, le '; # TODO only page 1
$output .= datefix($accountingdate);
#if ($paybydate != $accountingdate && $confirmed == 1) { $output .= '<br>Échéance : ' . datefix($paybydate); }
$output .= '</div>';

$output .= '<div class="companyinfo">';
#$output .= $_SESSION['ds_companyinfo'];
if($_SESSION['ds_customname'] == 'Espace 7')
{
  $output .= 'SARL ESPACE 7<br>
  Société au capital de 2 400 000 F - RC 8959B - TAHITI 188 607<br>
  PK 10,8 c/mont - BP 380574 - 98718 Tamanu<br>
  Tél : 40 54 18 98 - FAX : 40 43 31 11 - Email : compta@espace7.pf';
}
elseif($_SESSION['ds_customname'] == 'Espace Paysages')
{ 
  $output .= 'SARL ESPACE PAYSAGES<br>
  Société au capital de 12 500 000 F - RC 6567B - TAHITI 433 052<br>
  PK 10,8 c/mont - Matatia - BP 380574 - 98718 PUNAAUIA<br>
  Tél : 40 54 18 98 - FAX : 40 43 31 11 - Email : secretariat@gps.pf';
}
elseif($_SESSION['ds_customname'] == 'HOTU NUI')
{ 
  $output .= 'SCA SERRES HOTU NUI<br>
  RC 4931 C - TAHITI 282244<br>
  BP 380574 Tamanu - 98718 PUNAAUIA<br>
  Tél : 40 54 18 98 - FAX : 40 43 31 11';
}
elseif($_SESSION['ds_customname'] == 'ESPACE TERRASSEMENT')
{ 
  $output .= 'SCA ESPACE TERRASSEMENT<br>
  BP 380574 Tamanu - 98718 PUNAAUIA<br>
  Tél : 40 54 18 98 - FAX : 40 43 31 11';
}
$output .= '</div>';

$output .= '<div class="clientinfo">'; # TODO top is 180 for first page, ? for next pages
$output .= '<table border=0 cellspacing=0 style="border-collapse: collapse;" width="714.5px"><tr>';
$output .= '<td width=380 height=100 valign=bottom class="invoicetitle">';
$output .= mb_convert_case($typetext, MB_CASE_UPPER, "UTF-8");
$output .= ' N° ' . $format_invoiceid . '<br><br>';
$output .= '<td class="border_right" width=330 colspan=5 valign=top><br>';
if ($extraname != '') { $output .= '<b>A l\'attention de ' . d_output($extraname).'<br><br>'; }
$output .= '<span class="clientname">'.d_output(d_decode($clientname)).'</span>';
if (isset($address) && !empty($address)) { $output .= '<br>'.d_output($address); }
if (isset($postaladdress) && $postaladdress != '') { $output .= '<br>' . d_output($postaladdress); }
$output .= ' - ' . $postalcode;
if (ctype_digit(preg_replace('/\s+/', '', $postalcode)))
{
  if ($town_name != '') { $output .= ' ' . $town_name; }
  else { $output .= ' ' . $townA[$townid]; }
}
$output .= '<tr><td height=75 class="border" width=380 valign=top><br>';
$output .= 'Mode de règlement : '.d_output($clienttermname);
if ($clientcode != '') { $output .= '<br>Code client : '.d_output($clientcode); }
if ($field2 != '') { $output .= '<br>'.d_output($_SESSION['ds_term_field2']).' : '.d_output($field2); }
$output .= '<td class="border_right" width=330 valign=top colspan=5><br>';
if ($reference != '') { $output .= 'Libellé:&nbsp;' . d_output($reference).'<br><br>'; }
if ($employeeid > 0) { $output .= 'Affaire suivie par : ' . d_output($employeeA[$employeeid]).'<br><br>'; }
$output .= '<tr><td class="header">DESIGNATION';
if($_SESSION['ds_customname'] == 'Espace 7') { $output .= '<td class="header" colspan=2>QUANTITE'; }
else { $output .= '<td class="header">U<td class="header">QUANTITE'; }
$output .= '<td class="header">PU<td class="header">Montant';
$output .= $informationTable;
$output .= '<tr><td colspan=10 class="header">'; # quick bottom double line
###
/*
solution from terevau:
$substract_lines = substr_count($informationIsNotice,'<br>');
if ($informationIsNotice != '') { $substract_lines++; }
$x = 38 - $num_lines - $substract_lines;
$output .= '<tr><td colspan=10 height="'.(16*$x).'px">&nbsp;';

total table height is  height=840
*/
#$output .= '<tr><td colspan=10 height="'.(16*1).'px">&nbsp;';
###

$output .= '</table>';
if ($invoicecomment != '')
{
  if($_SESSION['ds_customname'] == 'Espace 7') { $output .= '<br><b>' . d_output($invoicecomment) . '</b>'; }
  else { $output .= '<br>' . d_output($invoicecomment); }
}
$output .= '</div>';

$output .= '<div class="totals">';
$output .= '<table border=0 cellspacing=0 style="border-collapse: collapse;" width=715>';
$output .= $informationTotalPages;
if ($totalpages == 1 || $pagenumber == $totalpages)
{
$output .= '<tr><td colspan=5 class="items"><br> &nbsp; <b>';
if ($totalprice_deductions > 0 && $totalprice_deductions != $invoiceprice)
{
  $output .= mb_convert_case(convertir($totalprice_deductions), MB_CASE_UPPER, "UTF-8");
}
else
{
  $output .= mb_convert_case(convertir($invoiceprice), MB_CASE_UPPER, "UTF-8");
}
$output .= '</b><tr><td colspan=5 class="bottom">Loi 92-1442 du 31/12/1992 - Tout retard de paiement déclenchera
une pénalité calculée sur la base d\'un taux valant une fois et demie le taux de l\'intérêt légal en vigueur.';
if ($_SESSION['ds_customname'] == 'Espace Paysages')
{
  $output .= '<tr><td colspan=5 class="bottom">SOC 17469 - 00024 - 50207200034 - 95
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; BDT 12239 - 00001 - 30498301000 - 55
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; BDP 12149 - 06730 - 30001956041 - 58';
}
elseif ($_SESSION['ds_customname'] == 'Espace 7')
{
  $output .= '<tr><td colspan=5 class="bottom">SOC 17469 - 00024 - 50172200010 - 92
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; BP&nbsp; 12149 - 06730 - 30003270656 - 39';
}
}
$output .= '</table>';
$output .= '</div>';

echo $output;

?>