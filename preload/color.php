<?php
if (!isset($colorA))
{
  $query = 'select * from color order by deleted,colorname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp=0;$temp<$num_results;$temp++)
  {
    $id_temp = (int) ($query_result[$temp]['colorid']+0);
    $colorA[$id_temp] = $query_result[$temp]['colorname'];
    $color_codeA[$id_temp] = $query_result[$temp]['colorcode'];
    $color_deletedA[$id_temp] = $query_result[$temp]['deleted'];
  }
}
unset($temp,$id_temp);
?>