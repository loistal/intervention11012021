<?php
if (!isset($familysituationA))
{
  $query = 'select * from familysituation order by familysituationname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $familysituationid_temp = (int) ($query_result[$kladd_i]['familysituationid']+0);
    $familysituationA[$familysituationid_temp] = $query_result[$kladd_i]['familysituationname'];
    $familysituation_deletedA[$familysituationid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>