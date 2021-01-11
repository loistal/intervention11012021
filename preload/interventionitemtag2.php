<?php
if (!isset($interventionitemtag2A))
{
  $query = 'select * from interventionitemtag2 order by deleted,interventionitemtag2name';
  $query_prm = array();
  require('inc/doquery.php');

  for ($temp_i = 0; $temp_i < $num_results; $temp_i++)
  {
    $id_temp = (int) ($query_result[$temp_i]['interventionitemtag2id'] + 0);
    $interventionitemtag2A[$id_temp] = $query_result[$temp_i]['interventionitemtag2name'];
    $interventionitemtag2_deletedA[$id_temp] = $query_result[$temp_i]['deleted'];
  }
}
?>