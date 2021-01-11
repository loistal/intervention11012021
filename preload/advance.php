<?php
if (!isset($advanceA))
{
  $query = 'select * from advance order by deleted,advancename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($temp=0;$temp<$num_results;$temp++)
  {
    $id_temp = (int) ($query_result[$temp]['advanceid']+0);
    $advanceA[$id_temp] = $query_result[$temp]['advancename'];
    $advance_percentageA[$id_temp] = $query_result[$temp]['advance_percentage'];
    $advance_deletedA[$id_temp] = $query_result[$temp]['deleted'];
  }
}
unset($temp,$id_temp);
?>