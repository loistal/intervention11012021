<?php

echo '<br><h2>' . d_trad('configuration:') . '</h2>';

if (isset($_POST['configureme']))
{
  for ($i = 1; $i <= $dp_numfields; $i++)
  {
    $query = 'select showfield from cf_report where userid=? and reportid=? and fieldnum=?';
    $query_prm = array($_SESSION['ds_userid'], $reportid, $i);
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      $query = 'insert into cf_report (showfield,showtitle,userid,reportid,fieldnum) values (?,?,?,?,?)';
      $query_prm = array($_POST['field'.$i], $_POST['title'.$i], $_SESSION['ds_userid'], $reportid, $i);
      require('inc/doquery.php');
    }
    else
    {
      $query = 'update cf_report set showfield=?,showtitle=? where userid=? and reportid=? and fieldnum=?';
      $query_prm = array($_POST['field'.$i], $_POST['title'.$i], $_SESSION['ds_userid'], $reportid, $i);
      require('inc/doquery.php');
    }

  }
  echo '<br>' . d_trad('configurationsaved') . '<br><br>';
}

$query = 'select showfield,showtitle,fieldnum from cf_report where userid=? and reportid=? order by fieldnum';
$query_prm = array($_SESSION['ds_userid'], $reportid);
require('inc/doquery.php');
for ($i = 0; $i < $num_results; $i++)
{
  $f = $query_result[$i]['fieldnum'];
  $showfieldA[$f] = $query_result[$i]['showfield'];
  $showtitleA[$f] = $query_result[$i]['showtitle'];
}

echo '<form method="post" action="' . $dauphin_currentmenu . '.php"><table><thead><th>Champs<th>Description</thead>';

for ($scrap_i = 1; $scrap_i <= $dp_numfields; $scrap_i++)
{
  echo '<tr><td align=right><select name="field' . $scrap_i . '"><option value=0>&lt;Vide&gt;</option>';
  foreach ($dp_fielddescrA as $scrap_y => $fielddescr)
  {
    if ($fielddescr != '')
    {
      echo '<option value="' . $scrap_y . '"'; if (isset($showfieldA[$scrap_i]) && $scrap_y == $showfieldA[$scrap_i]) { echo ' selected'; }
      echo '>' . $fielddescr . '</option>';
    }
  }
  echo '</select></td><td><input type=text name="title' . $scrap_i . '" value="' . $showtitleA[$scrap_i] . '" size=20></td></tr>';
}
$menu_temp = $dauphin_currentmenu . 'menu';
echo '<tr><td colspan="2">&nbsp;</td></tr>';
echo '<tr><td colspan="2" align="center"><input type=hidden name="' . $menu_temp . '" value="' . $$menu_temp . '"><input type=hidden name=configureme value=1><input type="submit" value="' . d_trad('validate') . '"></td></tr></table></form>';

unset($menu_temp);
?>