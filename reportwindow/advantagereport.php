<?php

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');

$title = 'Déclaration Prix PPN ' . datefix2($startdate) . ' à ' . datefix2($stopdate);
showtitle($title);
echo '<h2>' . $title . '</h2>';

$query = 'select sofixdate,shipmentid,sofixvessel,customscode from shipment where sofixadvantage="390"
and sofixdate>=? and sofixdate<=? order by sofixdate';
$query_prm = array($startdate,$stopdate);
require('inc/doquery.php');

echo '<table class=report><thead><th>DATE DE DECLARATION</th><th>No DECLARATION</th><th>No DOSSIER</th><th>NAVIRE</th><th>DESCRIPTION</th></thead>';
for ($i = 0; $i < $num_results; $i++)
{
  echo '<tr><td align=center>' . datefix2($query_result[$i]['sofixdate']) . '</td><td align=center>' . substr($query_result[$i]['customscode'],10,6) . '</td>
  <td align=center>' . $query_result[$i]['shipmentid'] . '</td><td align=center>' . $query_result[$i]['sofixvessel'] . '</td><td>&nbsp;</td></tr>';
}
echo '</table>';

?>