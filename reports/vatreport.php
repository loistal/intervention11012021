<h2>TVA collectée (sur factures):</h2>
<form method="post" action="reportwindow.php" target="_blank"><table>
<?php
echo '<tr><td>De:</td><td>';
$datename = 'startdate';
require('inc/datepicker.php');
echo '</td></tr><tr><td>A:</td><td>';
$datename = 'stopdate';
require('inc/datepicker.php');
echo '</td></tr>';

$dp_itemname = 'invoicetag'; $dp_description = $_SESSION['ds_term_invoicetag']; $dp_allowall = 1; $dp_selectedid = -1;
require ('inc/selectitem.php');
echo ' <input type=checkbox name="exclude_invoicetag" value=1> Exclure';

echo '<tr><td>Ranger par:</td><td><select name="orderby"><option value=0>Date</option><option value=1>Facture</option></select></td></tr>';
?>
<tr><td colspan="2" align="center"><input type=checkbox name=onlytotal value=1> Totaux Uniquement</td></tr>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="vatreport">
<input type="submit" value="Valider"></td></tr></table></form>
<?php
$query = 'select productid,productname from product where exludefromvatreport=1';
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<br><p class=alert>Ces produits ne figurent pas dans ce rappport:';
  $testvar = $num_results - 1;
  for ($i=0;$i<$num_results;$i++)
  {
    echo ' ' . $query_result[$i]['productid'] . ' ' . d_output(d_decode($query_result[$i]['productname']));
    if ($i < $testvar) { echo ','; }
  }
  echo '</p>';
}
?>