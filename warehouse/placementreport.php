<?php

echo '<h2>RAPPORT EMPLACEMENT</h2>';
echo '<form method="post" action="reportwindow.php" target=_blank>';
echo '<table><tr><td>DLV du: <td>';
$datename = 'startdate';
$selecteddate = '';$dp_setempty = 1;
require('inc/datepicker.php');
  
echo '<tr><td>  jusqu\'au: <td>';
$datename = 'stopdate';
$selecteddate = '';$dp_setempty = 1;
require('inc/datepicker.php');

echo '<tr><td>Entrepôt: <td><select name="warehouseid">';
$query = 'select warehouseid,warehousename from warehouse';
$query_prm = array();
require('inc/doquery.php');
echo '<option value=-1>'. d_trad('selectall');
for ($i=0; $i < $num_results; $i++)
{
  echo '<option value=' . $query_result[$i]['warehouseid'] . '>' . $query_result[$i]['warehousename'] . '</option>';
}
echo '</select>'; 

echo '<tr><td>Emplacement: <td><input autofocus type=text STYLE="text-align:right" name="placementname" size=10>';

echo '<tr><td>Code-Barre Palette: <td>';
echo '<input type=text STYLE="text-align:right" name="pallet_barcode" size=10>';

echo '<tr><td>N° Fournisseur: <td>';
echo '<input  type=text STYLE="text-align:right" name="supplierid" size=10>';

echo '<tr><td>Batchname: <td>';
echo '<input  type=text STYLE="text-align:right" name="supplierbatchname" size=10>';


echo '<tr><td>Conteneur: <td>';
echo '<input  type=text STYLE="text-align:right" name="arrivalref" size=20>';

echo '<tr><td>'; require('inc/selectproduct.php');

echo '<tr><td>', d_trad('orderby:'),'<td><select name="orderby">';
echo '<option value="0">Entrepot/Emplacement</option>';
echo '<option value="1">', d_trad('product'), '/DLV</option>';
echo '<option value="2">', d_trad('SBD'), '</option>';

echo '<tr><td>Avec Plan Entrepôt :<td>';
echo '<input type="checkbox" name="map" value="1">';

echo '<tr><td colspan="2" align="center">';
echo '<input type=hidden name="report" value="placementreport">';
echo '<input type="submit" value="Valider">';
echo '</table></form>';
?>