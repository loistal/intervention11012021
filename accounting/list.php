<?php

echo '<h2>Rapport Plan Comptable Général:</h2>';
echo '<form method="post" action="reportwindow.php" target="_blank"><table>
<tr><td>Comptes TVA uniquement<td><input type=checkbox name="onlyvataccounts" value=1>
<tr><td colspan="2" align="center">
<input type=hidden name="report" value="accountingnumbereport">
<input type="submit" value="Valider"></td></tr></table></form>';

/*

require('preload/vatindex.php');
require('preload/turnoverindex.php');
require('preload/accountinggroup.php');
require('preload/balancesheetindex.php');

$query = 'select * from accountingnumber';
if ($_GET['onlyvataccounts'] == 1) { $query .= ' where vatindexid>0'; }
$query = $query . ' order by deleted,acnumber,acname';
$query_prm = array();
require ('inc/doquery.php');
echo '<h2>Liste des comptes</h2><table class=report><thead><th>Compte</th><th>Description</th><th>Groupe</th>';
echo '<th>Banque</th><th>Lettrage</th><th>Tiers</th><th>Index TVA<th>Index CA<th>Index Bilan</thead>';
$main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  echo d_tr();
  $link = '##accounting.php?accountingmenu=mod&accountingnumberid='.$row['accountingnumberid'];
  echo d_td_old($row['acnumber'],0,0,0,$link);
  $kladd = $row['acname']; if ($row['deleted'] == 1) { $kladd .= ' [Supprimé]'; }
  echo d_td_old($kladd);
  echo d_td_old($accountinggroupA[$row['accountinggroupid']]);
  $kladd = ''; if ($row['isbank']) { $kladd = '&radic;'; }
  echo d_td_old($kladd,4);
  $kladd = ''; if ($row['matchable']) { $kladd = '&radic;'; }
  echo d_td_old($kladd,4);
  $kladd = ''; if ($row['needreference']) { $kladd = '&radic;'; }
  echo d_td_old($kladd,4);
  $kladd = $vatindexA[$row['vatindexid']];
  if ($row['vatnegative'] == 1) { $kladd = '- '.$kladd; }
  echo d_td_old($kladd);
  $kladd = $turnoverindexA[$row['turnoverindexid']];
  echo d_td_old($kladd);
  $kladd = $balancesheetindexA[$row['balancesheetindexid']];
  #$kladd = $row['balancesheetindexid'];
  echo d_td_old($kladd);
}
?></table>
*/