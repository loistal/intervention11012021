<?php
if (!isset($unittype_lineA))
{
  $query = 'select unittype_lineid,unittype_linename,deleted from unittype_line order by deleted,unittype_linename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['unittype_lineid']+0);
    $unittype_lineA[$id_temp] = $query_result[$kladd_i]['unittype_linename'];
    $unittype_line_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
  unset($kladd_i,$id_temp);
}
?>