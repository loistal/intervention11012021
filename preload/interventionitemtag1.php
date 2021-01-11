<?php
if (!isset($interventionitemtag1A))
{
  $query = 'select * from interventionitemtag1 order by deleted,interventionitemtag1name';
  $query_prm = array();
  require('inc/doquery.php');

  for ($temp_i = 0; $temp_i < $num_results; $temp_i++)
  {
    $id_temp = (int) ($query_result[$temp_i]['interventionitemtag1id'] + 0);
    $interventionitemtag1A[$id_temp] = $query_result[$temp_i]['interventionitemtag1name'];
    $interventionitemtag1_deletedA[$id_temp] = $query_result[$temp_i]['deleted'];
  }
}
?>