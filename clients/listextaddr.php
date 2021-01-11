<?php

$query = 'select clientname,extraaddress.clientid,extraaddress.address,extraaddress.postaladdress,extraaddress.deleted from extraaddress,client where extraaddress.clientid=client.clientid order by clientid';
$query_prm = array();
require('inc/doquery.php');
echo '<h2>Liste des adresses supplémentaires</h2><br>';
echo '<table class="report" border=1 cellpadding=2 cellspacing=2><tr><td><b>Client</b></td><td><b>Adresse</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td>' . $query_result[$i]['clientid'] . ': ' . $query_result[$i]['clientname'] . '</td><td>' . $query_result[$i]['address'] . ' ' . $query_result[$i]['postaladdress'] . '</td></tr>';
}
echo '</table>';

?>