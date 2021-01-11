<?php
echo '<h2>Rapport Mouvement et Correction Palette</h2>';
echo '<form method="post" action="reportwindow.php" target=_blank>';

echo '<table><tr><td>Du: <td>';

$datename = 'startdate'; $selecteddate = $_SESSION['ds_curdate'];
require('inc/datepicker.php');
echo ' Au: ';
$datename = 'stopdate'; $selecteddate = $_SESSION['ds_curdate'];
require('inc/datepicker.php');

echo '<tr><td>Emplacement de départ: <td><input type=text  name="fromplacementname" size=15>';

echo ' Emplacement d\'arrivée: <input type=text  name="toplacementname" size=15>';

echo '<tr><td>Code-Barre Palette: <td>
<input type=text STYLE="text-align:right" name="pallet_barcode" size=15>';

echo '<tr><td>'; require('inc/selectproduct.php');

echo '<tr><td>Utilisateur: <td><select name="userid">';
$query = 'select userid,name from usertable';
$query_prm = array();
require('inc/doquery.php');
echo '<option value=-1>'. d_trad('selectall');
for ($i=0; $i < $num_results; $i++)
{
  echo '<option value=' . $query_result[$i]['userid'] . '>' . $query_result[$i]['name'] . '</option>';
}  
echo '<tr><td>Raison:';
$dp_itemname = 'warehousereason'; $dp_allowall = 1; $dp_noblank = 1;
require('inc/selectitem.php');

echo '<tr><td>Commentaire: <td><input type=text  name="comment" size=50>';

echo '<tr><td>', d_trad('orderby:'),'<td><select name="orderby">' ;
echo '<option value="0">Utilisateur</option>';
echo '<option value="1">Produit</option>';
echo '<option value="2">Date et Heure de Correction</option>';

echo '<tr><td colspan="2" align="center">';
echo '<input type=hidden name="report" value="logpalletreport">';
echo '<input type="submit" value="Valider">';
echo '</table></form>';
?>