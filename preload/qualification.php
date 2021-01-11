<?php
if (!isset($qualificationA))
{
  $query = 'select * from qualification';
  $query .= ' order by qualificationname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $qualificationid_temp = (int) ($query_result[$kladd_i]['qualificationid']+0);
    $qualificationA[$qualificationid_temp] = $query_result[$kladd_i]['qualificationname'];
    $qualification_deletedA[$qualificationid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>