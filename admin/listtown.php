<?php


$query = 'select townname,townrank,islandname,regulationzonename from town,island,regulationzone where town.islandid=island.islandid and island.regulationzoneid=regulationzone.regulationzoneid order by regulationzonename,islandname,townname';
$query_prm = array();
  require('inc/doquery.php');
?><h2>Liste des regions/îles/villes</h2>
<table class="report"><thead><th>Region</th><th>Île</th><th>Ville</th></thead><?php
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo d_tr() .'<td>' . $row['regulationzonename'] . '</td><td>' . $row['islandname'] . '</td><td>' . $row['townname'] . ' (' . ($row['townrank']+0) . ')</td></tr>';
}
?></table>