<?php

$day_nameA[1] = 'Lun';
$day_nameA[2] = 'Mar';
$day_nameA[3] = 'Mer';
$day_nameA[4] = 'Jeu';
$day_nameA[5] = 'Ven';
$day_nameA[6] = 'Sam';
$day_nameA[7] = 'Dim';

require('inc/func_planning.php');

$PA['weeklyhoursid'] = 'int';
$PA['add'] = '';
$PA['save'] = 'int';
require('inc/readpost.php');

if ($save == 2)
{
  $query = 'insert into weeklyhours (weeklyhoursname';
  $query_part = ') values (?';
  $query_prm = array($_POST['weeklyhoursname']);
  for ($weekday = 1; $weekday <= 7; $weekday++)
  {
    for ($i = 1; $i <= 6; $i++)
    {
      $query .= ',weeklyhour'.$weekday.'_'.$i;
      $query_part .= ',?';
      array_push($query_prm, $_POST['weeklyhour'.$weekday.'_'.$i]);
    }
  }
  $query = $query . $query_part . ')';
  require('inc/doquery.php');
  $weeklyhoursid = $query_insert_id;
}
elseif ($save == 1)
{
  $query = 'update weeklyhours set weeklyhoursname=?';
  $query_prm = array($_POST['weeklyhoursname']);
  for ($weekday = 1; $weekday <= 7; $weekday++)
  {
    for ($i = 1; $i <= 6; $i++)
    {
      $query .= ',weeklyhour'.$weekday.'_'.$i.'=?';
      array_push($query_prm, $_POST['weeklyhour'.$weekday.'_'.$i]);
    }
  }
  $query .= ' where weeklyhoursid=?';
  array_push($query_prm, $weeklyhoursid);
  require('inc/doquery.php');
}

if ($weeklyhoursid != 0)
{
  if ($weeklyhoursid == -1) { echo '<h2>Ajouter</h2>'; }
  else
  {
    echo '<h2>Modifier</h2>';
    $query = 'select * from weeklyhours where weeklyhoursid=?';
    $query_prm = array($weeklyhoursid);
    require('inc/doquery.php');
  }
  echo '<form method="post" action="hr.php"><table class="report"><tr><td colspan=7><input autofocus type=text name="weeklyhoursname" value="',$query_result[0]['weeklyhoursname'],'" size=80>';
  for ($weekday = 1; $weekday <= 7; $weekday++)
  {
    echo '<tr><td>',$day_nameA[$weekday];
    for ($i = 1; $i <= 6; $i++)
    {
      echo '<td><input type="time" name="weeklyhour',$weekday,'_',$i,'" value="',d_displaytime($query_result[0]['weeklyhour'.$weekday.'_'.$i]),'">';
    }
  }
  if ($weeklyhoursid == -1) { echo '<input type=hidden name="save" value="2">'; }
  else { echo '<input type=hidden name="save" value="1"><input type=hidden name="weeklyhoursid" value="' . $weeklyhoursid . '">'; }
  echo '<tr><td colspan=7 align=center><input type=hidden name="hrmenu" value="' . $hrmenu . '"><input type=submit value="' . d_trad('validate') . '"></table></form><br><br>';
}

echo '<h2>Horaires hebdomadaires</h2><form method="post" action="hr.php">';
$query = 'select weeklyhoursid,weeklyhoursname from weeklyhours order by weeklyhoursname';
$query_prm = array();
require('inc/doquery.php');
echo '<table class=report>';
for ($i=0; $i < $num_results; $i++)
{
  echo '<tr><td align=right><input type=radio name="weeklyhoursid" value='.$query_result[$i]['weeklyhoursid'].'><td>'.$query_result[$i]['weeklyhoursname'];
}
echo '<tr><td align=right><input type=radio name="weeklyhoursid" value=-1><td>Ajouter';
echo '<tr><td colspan=2 align=center><input type=submit value="' . d_trad('validate') . '"><input type=hidden name="hrmenu" value="' . $hrmenu . '">';
echo '</table></form>';

?>