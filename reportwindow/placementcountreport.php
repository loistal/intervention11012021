<?php

require('preload/user.php');

$query = 'select placementname,userid,counteddate,countedtime from placement order by placementrank,placementname';
$query_prm = array();
require('inc/doquery.php');
echo '<h2>Rapport date du dernier comptage par Emplacement</h2>
<table class=report><tr><td><b>Emplacement</td><td><b>Utilisateur</td><td><b>Date</td><td><b>Heure</td></tr>';
for ($i=0;$i<$num_results;$i++)
{
  echo '<tr><td>' . $query_result[$i]['placementname'] . '</td><td>' . $userA[$query_result[$i]['userid']] . '</td><td>' . datefix2($query_result[$i]['counteddate']) . '</td><td>' . $query_result[$i]['countedtime'] . '</td></tr>';
}
echo '</table>';

?>