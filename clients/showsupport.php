<?php

# NOT USED
$companyname = $_SESSION['ds_customname'];
if ($_SESSION['ds_customname'] == "" || !isset($_SESSION['ds_customname'])) { $companyname = ' entreprise'; }
echo '<h2>Suivi support</h2>';
$query = 'select support.clientid,clientname,supporttitle,clientdate,companydate,lastcompany from support,client';
$query = $query . ' where support.clientid=client.clientid and closed=0';
$query = $query . ' order by lastcompany,clientdate asc';
$query_prm = array();
    require('inc/doquery.php');
echo '<table class="report" border=1 cellspacing=2 cellpadding=2><tr><td><b>Date client</td><td><b>Date ' . $companyname . '</td><td><b>Client</td><td><b>Titre</td></tr>';
if ($num_results)
{
echo '<tr><td colspan=10><i>Cas ouverts, client attend reponse</td></tr>';
$lastcompanyused = 0;
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  if ($row['lastcompany'] == 1 && $lastcompanyused == 0)
  {
    echo '<tr><td colspan=10><i>Cas ouverts, nous avons repondu</td></tr>';
    $lastcompanyused = 1;
  }
  $clientdate = datefix2($row['clientdate']);
  if (!isset($row['clientdate'])) { $clientdate = '&nbsp;'; }
  $companydate = datefix2($row['companydate']);
  if (!isset($row['companydate'])) { $companydate = '&nbsp;'; }
  echo '<tr><td>' . $clientdate . '</td><td>' . $companydate . '</td><td>' . $row['clientid'] . ': ' . $row['clientname'] . '</td><td>' . $row['supporttitle'] . '</td></tr>';
}
}
echo '</table>';

?>