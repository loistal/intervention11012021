<?php

function checktime($time)
{
	return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
}

$clientscheduleid = $_POST['clientscheduleid']+0;
$saveme = $_POST['saveme']+0;

$client = $_POST['client'];
require('inc/findclient.php');
if ($clientid < 1)
{
  echo '<h2>Modifier planning</h2>';
  echo '<form method="post" action="clients.php"><table><tr><td>';
  require('inc/selectclient.php');
  echo '</td></tr><tr><td colspan="2" align="center"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type="submit" value="Valider"></td></tr>';
}

if ($clientid > 0 && $clientscheduleid != 0 && $saveme == 1)
{
  $statustext = '<p>Planning modifié.</p>';
  if ($clientscheduleid == -99)
  {
    $query = 'insert into clientschedule (clientid) values (?)';
    $query_prm = array($clientid);
    require('inc/doquery.php');
    $clientscheduleid = $query_insert_id;
    $statustext = '<p>Planning ajouté.</p>';
  }
  
  $clientschedulecatid = $_POST['clientschedulecatid']+0;
  $schedulecomment = $_POST['schedulecomment'];
  $dayofweek = $_POST['dayofweek']+0;
  $daytype = $_POST['daytype']+0;
  $periodic = $_POST['periodic']+0;
  $deleted = $_POST['deleted']+0;
  $datename = 'scheduledate';
  require('inc/datepickerresult.php');
  $datename = 'notuntildate';
  require('inc/datepickerresult.php');
  $scheduletime = mb_substr($_POST['scheduletime'],0,5);
  if (!checktime($scheduletime)) { $scheduletime = '00:00'; }
  $extraaddressid = $_POST['extraaddressid']+0;
    
  $query = 'update clientschedule set clientid=?,clientschedulecatid=?,schedulecomment=?,dayofweek=?,daytype=?,periodic=?,deleted=?,scheduledate=?,scheduletime=?,notuntildate=?,extraaddressid=? where clientscheduleid=?';
  $query_prm = array($clientid,$clientschedulecatid,$schedulecomment,$dayofweek,$daytype,$periodic,$deleted,$scheduledate,$scheduletime,$notuntildate,$extraaddressid,$clientscheduleid);
  require('inc/doquery.php');
  echo $statustext;
}

if ($clientid > 0 && $clientscheduleid == 0 && $saveme == 0)
{
  $query = 'select clientscheduleid,periodic,scheduledate,dayofweek,daytype,deleted,schedulecomment from clientschedule where clientschedule.clientid=?';
  $query = $query . ' and deleted=0';
  $query = $query . ' order by deleted,periodic desc,clientscheduleid';
  $query_prm = array($clientid);
  require ('inc/doquery.php');
  
  echo '<h2>Modifier planning pour client ' . d_output($clientname) . ' (' . $clientid . ')</h2>';
  echo '<form method="post" action="clients.php">';
  echo '<table border=1 cellpadding=5 cellspacing=5><tr><td>&nbsp;</td><td><b>Periodicité</b></td><td><b>Commentaire</b></td></tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    $when = '';
    if ($row['periodic'] == 0) { $when = datefix2($row['scheduledate']); }
    if ($row['periodic'] == 1)
    {
      if ($row['dayofweek'] == 1) { $when = 'Lundi'; }
      if ($row['dayofweek'] == 2) { $when = 'Mardi'; }
      if ($row['dayofweek'] == 3) { $when = 'Mercredi'; }
      if ($row['dayofweek'] == 4) { $when = 'Jeudi'; }
      if ($row['dayofweek'] == 5) { $when = 'Vendredi'; }
      if ($row['daytype'] == 1) { $when = $when . ' Tous'; }
      if ($row['daytype'] == 2) { $when = $when . ' Semaine Pair'; }
      if ($row['daytype'] == 3) { $when = $when . ' Semaine Impair'; }
      if ($row['daytype'] == 4) { $when = $when . ' Premier du mois'; }
      if ($row['daytype'] == 5) { $when = $when . ' Dèrnier du mois'; }
    }
    echo '<tr><td><input type=radio name=clientscheduleid value="' . $row['clientscheduleid'] . '"';
    if ($i==0) { echo ' checked'; }
    echo '></td><td>' . $when . '</td><td>' . $row['schedulecomment'] . '</td></tr>';
  }
  echo '<tr><td colspan=10><input type=radio name="clientscheduleid" value="-99"';
  if ($num_results == 0) { echo ' checked'; }
  echo '> Ajouter</td></tr>';
  echo '<tr><td colspan=10> &nbsp; <input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type=hidden name="client" value="' . $clientid . '"><input type=hidden name="readvalues" value="1"><input type="submit" value="Valider"></td></tr>';
  echo '</table></form>';
}

if ($clientid > 0 && $clientscheduleid != 0)
{
  if ($clientscheduleid == -99)
  {
    echo '<h2>Ajouter planning pour client ' . d_output($clientname) . ' (' . $clientid . ')</h2>';
  }
  else
  {
    echo '<h2>Modifier planning pour client ' . d_output($clientname) . ' (' . $clientid . ')</h2>';
    if ($_POST['readvalues'] == 1)
    {
      $query = 'select * from clientschedule where clientscheduleid=?';
      $query_prm = array($clientscheduleid);
      require ('inc/doquery.php');
      $clientschedulecatid = $query_result[0]['clientschedulecatid'];
      $schedulecomment = $query_result[0]['schedulecomment'];
      $dayofweek = $query_result[0]['dayofweek'];
      $daytype = $query_result[0]['daytype'];
      $periodic = $query_result[0]['periodic'];
      $deleted = $query_result[0]['deleted'];
      $scheduledate = $query_result[0]['scheduledate'];
      $scheduletime = mb_substr($query_result[0]['scheduletime'],0,5);
      $notuntildate = $query_result[0]['notuntildate'];
      $extraaddressid = $query_result[0]['extraaddressid'];
    }
  }
  
  echo '<form method="post" action="clients.php"><table><tr><td>';
#  $dp_nochangeclient = 1;
  require('inc/selectclient.php');
  echo '</td></tr>';
  
  $query = 'select extraaddressid,address,postaladdress from extraaddress where clientid=? and deleted<>1';
  $query_prm = array($clientid);
  require ('inc/doquery.php');
  if ($num_results > 0)
  {
    echo '<tr><td>Adresse alternative:</td><td><select name="extraaddressid"><option value="0"></option>';
    for ($iy=0; $iy < $num_results; $iy++)
    {
      if ($query_result[$iy]['extraaddressid'] == $extraaddressid) { echo '<option value="' . $query_result[$iy]['extraaddressid'] . '" SELECTED>' . $query_result[$iy]['address'] . ' ' . $query_result[$iy]['postaladdress'] . '</option>'; }
      else { echo '<option value="' . $query_result[$iy]['extraaddressid'] . '">' . $query_result[$iy]['address'] . ' ' . $query_result[$iy]['postaladdress'] . '</option>'; }
    }
    echo '</select></td></tr>';
  }
  
  echo '<tr><td>Commentaire:</td><td><input type="text" STYLE="text-align:right" name="schedulecomment" value="' . $schedulecomment . '" size=50></td></tr>';
  
  $dp_itemname = 'clientschedulecat'; $dp_selectedid = $clientschedulecatid; $dp_description = 'Catégorie';
  require('inc/selectitem.php');

  echo '<tr><td>Periodicité</td><td><input type=radio name="periodic" value="1"'; if ($periodic == 1) { echo ' checked'; }
  echo '>';
  echo '<select name="dayofweek"><option value=1'; if ($dayofweek == 1) { echo ' selected'; }
  echo '>Lundi</option><option value=2'; if ($dayofweek == 2) { echo ' selected'; }
  echo '>Mardi</option><option value=3'; if ($dayofweek == 3) { echo ' selected'; }
  echo '>Mercredi</option><option value=4'; if ($dayofweek == 4) { echo ' selected'; }
  echo '>Jeudi</option><option value=5'; if ($dayofweek == 5) { echo ' selected'; }
  echo '>Vendredi</option></select>';
  echo '<select name="daytype"><option value=1'; if ($daytype == 1) { echo ' selected'; }
  echo '>Tous</option><option value=4'; if ($daytype == 4) { echo ' selected'; }
  echo '>Premier du mois</option><option value=5'; if ($daytype == 5) { echo ' selected'; }
  echo '>Dèrnier du mois</option><option value=2'; if ($daytype == 2) { echo ' selected'; }
  echo '>Semaine Pair</option><option value=3'; if ($daytype == 3) { echo ' selected'; }
  echo '>Semaine Impair</option></select>';
  echo '</td></tr>';
  echo '<tr><td>&nbsp;</td><td><input type=radio name="periodic" value="0"'; if ($periodic == 0) { echo ' checked'; }
  echo '>';
  $datename = 'scheduledate'; $selecteddate = $scheduledate;
  require('inc/datepicker.php');
  echo '</td></tr>';
  
  echo '<tr><td>Heure:</td><td><input type=time name="scheduletime" value="' . mb_substr($scheduletime,0,5) . '"></td></tr>';

  echo '<tr><td>Ne pas planifier jusq\'au:</td><td>';
  $datename = 'notuntildate'; $selecteddate = $notuntildate;
  require('inc/datepicker.php');
  echo '</td></tr>';
  
  echo '<tr><td>Supprimé:</td><td><input type=checkbox name="deleted" value=1';
  if ($deleted) { echo ' checked'; }
  echo '></td></tr>';

  echo '<tr><td colspan=10> &nbsp; <input type=hidden name="clientscheduleid" value="' . $clientscheduleid . '"><input type=hidden name="saveme" value="1"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type=hidden name="client" value="' . $clientid . '"><input type="submit" value="Valider"></td></tr>';
  echo '</table></form>';
  
}

?>