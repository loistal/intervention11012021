<?php
if (!isset($paletteA))
{
  $query = 'select * from palette order by deleted,palettename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp=0;$temp<$num_results;$temp++)
  {
    $id_temp = (int) ($query_result[$temp]['paletteid']+0);
    $paletteA[$id_temp] = $query_result[$temp]['palettename'];
    $palette_deletedA[$id_temp] = $query_result[$temp]['deleted'];
  }
}
unset($temp,$id_temp);
?>