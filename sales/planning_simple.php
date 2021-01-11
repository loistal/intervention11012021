<?php

if ($_SESSION['ds_myemployeeid'] > 0)
{
  $simple_form = 1;
  $employee1id = (int) $_POST['employee1id'];
  
  require('reportwindow/calendar.php');
  require('admin/planning.php');
}
else
{
  echo '<p>Votre utilisateur doit correspondre à un employé. (Système => Modifier Utilisateur => Accès RH</p>';
}

?>