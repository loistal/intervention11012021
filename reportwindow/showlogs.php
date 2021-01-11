<?php

require('preload/user.php');

$clientaccessid = (int) $_POST['clientaccessid'];
$ouruserid = (int) $_POST['userid'];

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');

$ourtitle = 'Rapport accès ';

$ourtitle = $ourtitle . datefix($startdate) . ' à ' . datefix($stopdate);
showtitle($ourtitle);
echo '<h2>' . $ourtitle . '</h2>';
echo '<table class="report" border=1 cellspacing=2 cellpadding=2>';

$query = 'select logtype,userid,logdate,logtime,loginfo,clientaccessid from logtable where logdate>=? and logdate<=?';
$query_prm = array($startdate, $stopdate);
if ($clientaccessid >= 0) { $query .= ' and clientaccessid=?'; array_push($query_prm,$clientaccessid); }
if ($ouruserid >= 0) { $query = $query . ' and logtable.userid=?'; array_push($query_prm,$ouruserid); }
$query = $query . ' order by logdate,logtime';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<tr><td><b>Date</td><td><b>Heure</td><td><b>Utilisateur</td><td><b>Évènement</td></tr>';

for ($y=0; $y < $num_results_main; $y++)
{
  $row = $main_result[$y];
  $username = $userA[$row['userid']];
  if ($row['userid'] == 0) { $username = 'Échec'; }
  if ($row['logtype'] == 2)
  {
    $query = 'select clientlogin from clientaccess where clientaccessid=?';
    $query_prm = array($row['clientaccessid']);
    require('inc/doquery.php');
    if ($num_results) { $username = $query_result[0]['clientlogin']; }
    else { $username = ''; }
  }
  echo '<tr><td>' . datefix2($row['logdate']) . '</td><td>' . $row['logtime'] . '</td><td>' . $username . '</td><td>' . d_output($row['loginfo']) . '</td></tr>';
}

echo '</table>';
?>