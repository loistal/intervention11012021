<?php

if (isset($_POST['frontpage']))
{
  $query = 'update globalvariables set frontpage=? where primaryunique=1';
  $query_prm = array($_POST['frontpage']);
  require ('inc/doquery.php');
  echo '<p>Page de garde modifiée.</p><br>';
  $frontpage = $_POST['frontpage'];
}
else
{
  $query = 'select frontpage from globalvariables where primaryunique=1';
  $query_prm = array('');
  require ('inc/doquery.php');
  $frontpage = $query_result[0]['frontpage'];
}
echo '<form method="post" action="admin.php">';
echo '<fieldset><legend>Page de garde</legend>';
echo '<textarea type="textarea" name="frontpage" cols=80 rows=25>' . d_input($frontpage) . '</textarea>';
echo '<br><label>&nbsp;</label><button type="submit">Valider</button>';
echo '<input type=hidden name="adminmenu" value="' . $adminmenu . '">';
echo '</fieldset></form>';

?>