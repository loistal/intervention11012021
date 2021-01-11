<?php

$query = 'select regulationtypeid,regulationtypename,regulationcode,regroupnumber,showasterix from regulationtype order by regulationtypeid';
$query_prm = array();
require('inc/doquery.php');
?><h2>Liste des type de regulation</h2>
<table class=report>
<thead><th>Regulationtypeid</th><th>Regulationcode</th><th>Regulationname</th><th>Regroupnumber</th><th>Showasterix</th></thead><?php
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $specialchar = '&nbsp;';
  if ($row['showasterix'] == 1) { $specialchar = "*"; }
  if ($row['showasterix'] == 2) { $specialchar = "+"; }
  if ($row['showasterix'] == 3) { $specialchar = "#"; }
  echo d_tr() . '<td>' . $row['regulationtypeid'] . '</td><td>' . $row['regulationcode'] . '</td><td>' . $row['regulationtypename'] . '</td><td>' . $row['regroupnumber'] . '</td><td>' . $specialchar . '</td></tr>'; 
}
?></table>