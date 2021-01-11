<?php
if (!isset($fenix_prev_procedureA))
{
  $query = 'select fenix_prev_procedureid,code,description,deleted from fenix_prev_procedure order by deleted,fenix_prev_procedureid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['fenix_prev_procedureid']+0);
    $fenix_prev_procedureA[$temp_id] = $query_result[$kladd_i]['description'];
    $fenix_prev_procedure_codeA[$temp_id] = $query_result[$kladd_i]['code'];
    $fenix_prev_procedure_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
?>