<?php

$output = '';
$output .= '<div class="main"><div class="mainwrap">';

$output .= '<div class="logowrap">';
$output .= '<table><tr><td width=28% class="header">Navire<br>TEREVAU<td width=38% align=right>';
if (file_exists($ourlogofile))
{
  $imagesizeA = getimagesize($ourlogofile);
  $output .= '<img src="'.$ourlogofile.'"';
  if ($imagesizeA[1] > 100) { $output .= ' height=100'; }
  $output .= '>';
}
$output .= '<td width=4%><td width=30%><span class=subheader>SNGV2 MOOREA</span><br><br>BP 90 111 MOTU UTA 98 715<br>
Tél 40 50 03 50 Fax 40 83 63 92</table>';
$output .= '</div>';

$output .= '<table class="infos"><tr><td width=25%><span class="subheader">';
if ($paybydate == $accountingdate) { $output .= 'COMPTANT'; } else { $output .= 'CREDIT'; }
$output .= '</span>';
$output .= '<td width=35%>CONNAISSEMENT N &nbsp; '.$invoiceid;
$output .= '<td width=40% align=right>PAPEETE le ' . datefix($accountingdate);
$output .= '<tr><td colspan=3 class="tdborders">';

$output .= '<table width=720><tr><td width=100><b>NAVIRE<td><b>TEREVAU<td align=right>Voyage N°V &nbsp; ' . $voyage;
$output .= '<tr><td>Expéditeur<td>'.d_output($field1).'<td align=right>Du &nbsp; '.datefix($custominvoicedate);
$output .= '</table>';

$output .= '<tr><td colspan=3>Les colis suivants, dont le contenu est inconnu, pour être délivrés à :';
$output .= '<tr><td colspan=3>';

$output .= '<table width=720><tr><td width=100>Destinataire<td width=250>'.d_output($field2).'<td>Destination<td>'.$to;
$output .= '<tr><td>Réquisition<td>'.d_output($reference).'<td>Provenance<td>'.$from;
$output .= '</table>';

$output .= '<table width=720 border=1 style="border-collapse:collapse">';
$output .= $informationTable_header;
$output .= $informationTable;
$output .= $informationTotalPages;
$output .= '<tr><td colspan=20>'.$informationIsNotice;
$substract_lines = substr_count($informationIsNotice,'<br>');
if ($informationIsNotice != '') { $substract_lines++; }
$x = 38 - $num_lines - $substract_lines;
$output .= '<tr><td colspan=10 height="'.(16*$x).'px">&nbsp;';
$output .= '</table>';

#$output .= $departure; # TODO

$output .= '<tr><td colspan=3 class="tdborders">';
$output .= '<table width=720><tr><td width=100>Par<td width=300>'.$initials.'<td><td width=100>';
$output .= '<tr><td>Voyage<td>'.$voyage.'<td>Coût total du frêt<td align=right>'.myfix($invoiceprice);
$output .= '<tr><td width=100>Départ<td width=300>'.$departure.'<td><td>';
$output .= '</table>';

$output .= '<hr>La responsabilité du transporteur est définie et limitée par la loi du 18 juin 1966 et par les textes subséquents, nonobstant toute valeur déclarée.';

$output .= '</table>';

$output .= '</div></div>';
echo $output;

?>