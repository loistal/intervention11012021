<?php
switch($currentstep)
{

  case 0:

  $query = 'select publicpage from globalvariables where primaryunique=1';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  echo '<form method="post" action="admin.php">';
  echo '<fieldset><legend>Page publique</legend>';
  echo '<textarea type="textarea" name="publicpage" cols=80 rows=50>' . $row['publicpage'] . '</textarea>';
  echo '<br><label>&nbsp;</label><button type="submit">Valider</button>';
  echo '<input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="'. $adminmenu . '">';
  echo '</fieldset></form>';
  break;

  case 1:
  $query = 'update globalvariables set publicpage=? where primaryunique=1';
  $query_prm = array($_POST['publicpage']);
  require('inc/doquery.php');
  echo '<p>Page publique modifiée.</p>';
  break;

}
?>