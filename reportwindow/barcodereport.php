<?php
require('preload/user.php');

echo '<h2>Rapport Code Barres</h2>';
showtitle('Rapport Code Barres');

$datename = 'startdate';
require('inc/datepickerresult.php');
$datename = 'stopdate';
require('inc/datepickerresult.php');

$userid = $_POST['userid']+0;

$total = 0;

$query = 'select barcode,userid,barcodedate,barcodetime from pallet_barcode';
$query .= ' where barcodedate>=? and barcodedate<=?';
$query_prm = array($startdate,$stopdate);  
  
if ($userid > 0) 
{
  $query .= ' and userid=?'; array_push($query_prm,$userid);
  echo '<p>Utilisateur: ' . d_output($userA[$userid]);
}
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<p>De: ' .datefix2($startdate) . ' à ' .datefix2($stopdate); 

echo '<table class=report><thead><th>Code Barre<th colspan=2>Date de Création<th>Créé par</thead>';
for ($i=0;$i<$num_results_main;$i++)
  {
    echo d_tr();
    echo d_td_old($main_result[$i]['barcode'],1);
    echo d_td (datefix2($main_result[$i]['barcodedate']),1);
    echo d_td ($main_result[$i]['barcodetime'],1);
    $username = $userA[$main_result[$i]['userid']];
    echo d_td_old($username);
  }  
echo '<tr><td align=center colspan=10><b>' . myfix($num_results_main) .' code barres';
echo '</table>';
?>