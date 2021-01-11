<?php

$PA['colorid'] = 'uint';
$PA['paletteid'] = 'uint';
require('inc/readpost.php');

echo '<h2>Gestion des Palettes</h2><br>';

if ($paletteid < 1)
{
  echo '<form method="post" action="admin.php"><table>';
  $dp_itemname = 'palette'; $dp_noblank = 1; require('inc/selectitem.php');
  echo '<tr><td colspan="2" align="center">
  <input type=hidden name="adminmenu" value="' . $adminmenu . '">
  <input type="submit" value="' . d_trad('continue') . '"></table></form>';
}
else
{
  require('preload/color.php');
  require('preload/palette.php');
  if ($colorid > 0)
  {
    $query = 'insert into palette_color_matrix (paletteid,colorid) values (?,?)';
    $query_prm = array($paletteid,$colorid);
    require('inc/doquery.php');
  }
  echo '<p><b>'.$paletteA[$paletteid].'</b></p><br>
  <form method="post" action="admin.php"><table class="report">';
  $query = 'select palette_color_matrix.colorid,colorname
  from palette_color_matrix,color
  where palette_color_matrix.colorid=color.colorid
  and paletteid=? order by colorname';
  $query_prm = array($paletteid);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  if ($num_results_main) { echo '<thead><th>Couleur<th>Supprimer</thead>'; }
  for ($i=0; $i < $num_results_main; $i++)
  {
    $colorid = $main_result[$i]['colorid'];
    if (isset($_POST['delete'.$colorid]))
    {
      $query = 'delete from palette_color_matrix where paletteid=? and colorid=?';
      $query_prm = array($paletteid,$colorid);
      require('inc/doquery.php');
    }
    else
    {  
      echo d_tr();
      echo d_td($colorA[$colorid]);
      echo d_td_unfiltered('<input type=checkbox name="delete'.$colorid.'" value=1>','center');
    }
  }
  echo d_tr(1);
  echo d_td('Ajouter:');
  $dp_itemname = 'color'; require('inc/selectitem.php');
  echo '<tr><td colspan="2" align="center"><input type=hidden name="adminmenu" value="' . $adminmenu . '">
  <input type=hidden name="paletteid" value="' . $paletteid . '">
  <input type="submit" value="' . d_trad('save') . '"></table></form>';
}


?>