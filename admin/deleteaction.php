<?php

$deleteaction = $_POST['deleteaction']+0;

if ($deleteaction == 2)
{
  $query = 'update clientaction set deleted=1 where clientactionid=?';
  $query_prm = array($_POST['clientactionid']+0);
  require('inc/doquery.php');
  echo '<p>Évènement supprimé.</p><br>';
}

if ($deleteaction == 1)
{
  require('inc/findclient.php');
  echo '<h2>Supprimer évènement pour client ' . $clientid . ': ' . $clientname . '</h2>';
  $query = 'select clientactionid,actiondate,actionname from clientaction where deleted=0 and clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  echo '<form method="post" action="admin.php"><table class=report><tr><td><b>Supprimer</td><td><b>Date</td><td><b>Évènement</td></tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<tr><td align=right> &nbsp; <input type="radio" name="clientactionid" value="' . $row['clientactionid'] . '"></td><td align=right>' . datefix2($row['actiondate']) . '</td><td>' . $row['actionname'] . '</td></tr>';
  }
  echo '<tr><td colspan="7" align="center"><input type=hidden name="deleteaction" value=2><input type=hidden name="adminmenu" value="' . $adminmenu . '"><input type=hidden name="client" value="' . $clientid . '"><input type="submit" value="Supprimer évènement"></td></tr>';
  echo '</table></form>';
}
else
{
  echo '<br><br>';

  echo '<h2>Supprimer évènement</h2><form method="post" action="admin.php"><table><tr><td>';
  require ('inc/selectclient.php');
  echo '<tr><td colspan="2" align="center"><input type=hidden name="deleteaction" value=1><input type=hidden name="adminmenu" value="' . $adminmenu . '"><input type="submit" value="Valider"></td></tr>';
  echo '</td></tr></table></form>';
}
?>