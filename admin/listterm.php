<?php

$query = 'select clienttermid,clienttermname,daystopay,special from clientterm order by clienttermname';
$query_prm = array();
  require('inc/doquery.php');
echo '<h2>Liste des délais paiement</h2><br>';
echo '<table class=report><thead><th>Numéro</th><th>Nom</th><th>Jours</th><th>Spécial</thead>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo d_tr() .'<td>' . $row['clienttermid'] . '</td><td>' . $row['clienttermname'] . '</td><td>' . $row['daystopay'];
  echo '<td>';
  if ($row['special'] == 1) { echo 'Fin du mois'; }
}
echo '</table>';

?>