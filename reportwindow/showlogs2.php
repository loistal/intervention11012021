<?php

require('preload/user.php');

$clientaccessid = (int) $_POST['clientaccessid'];
$ouruserid = (int) $_POST['userid'];
$querystring = $_POST['querystring'];
$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');

$ourtitle = 'Rapport logs ';

$ourtitle = $ourtitle . datefix($startdate) . ' à ' . datefix($stopdate);
showtitle($ourtitle);
echo '<h2>' . $ourtitle . '</h2>';
echo '<table class="report" border=1 cellspacing=2 cellpadding=2>';

$query = 'select userid,logdate,logtime,querystring,clientaccessid from log_query where logdate>=? and logdate<=?';
$query_prm =  array($startdate, $stopdate);
if ($clientaccessid >= 0) { $query .= ' and clientaccessid=?'; array_push($query_prm,$clientaccessid); }
if ($ouruserid >= 0) { $query = $query . ' and log_query.userid=?'; array_push($query_prm,$ouruserid); }
if ($querystring != '') { $query .= ' and querystring like ?'; array_push($query_prm, '%' . $querystring . '%'); }
$query = $query . ' order by logdate,logtime';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<tr><td><b>Date</td><td><b>Heure</td><td><b>Utilisateur</td><td><b>Query</td></tr>';

for ($y=0; $y < $num_results_main; $y++)
{
  $row = $main_result[$y];
  echo '<tr><td>' . datefix2($row['logdate']) . '</td><td>' . $row['logtime'] . '</td>';
  if ($row['userid'] > 0) { echo '<td>' . $userA[$row['userid']]; }
  else
  {
    $query = 'select clientlogin,clientname from clientaccess,client
    where clientaccess.clientid=client.clientid
    and clientaccessid=?';
    $query_prm = array($row['clientaccessid']);
    require('inc/doquery.php');
    if ($num_results) { $username = $query_result[0]['clientlogin'] . ' ('.d_decode($query_result[0]['clientname']).')'; }
    else { $username = ''; }
    echo '<td>' . $username . ' (accès client)';
  }
  echo '<td>' . d_output($row['querystring']) . '</td></tr>';
}

echo '</table>';
?>