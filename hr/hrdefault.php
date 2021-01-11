<?php
/*
echo '<div class="myblock">';
echo '<p><b>Le module HR est en développement</b></p><br>';
echo '<p>* = en modification</p><br>';
echo '<p>** = non opérationel</p><br>';
echo '</div><br>';
*/
require('preload/employee.php');
require('preload/team.php');

echo '<div class="myblock">';
echo '<p><b>Bonjour ';
if ($_SESSION['ds_myemployeeid'] > 0) { echo d_output($employeeA[$_SESSION['ds_myemployeeid']]); }
echo '</b></p><br>';
if ($_SESSION['ds_ishrsuperuser'])
{
  echo '<p>Vous êtes super-utilisateur RH.</p>';
}
else
{
  if ($_SESSION['ds_ismanager'] > 0)
  {
    echo '<p>Vous êtes manager de l\'équipe ',d_output($teamA[$_SESSION['ds_ismanager']]),'.</p>';
  }
  if ($_SESSION['ds_teamid'] > 0)
  {
    echo '<p>Vous faites partie de l\'équipe ',d_output($teamA[$_SESSION['ds_teamid']]),'.</p>';
  }
}
echo '</div>';

?>