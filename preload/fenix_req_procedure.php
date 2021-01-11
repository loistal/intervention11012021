<?php
if (!isset($fenix_req_procedureA))
{
  $query = 'select fenix_req_procedureid,code,description,deleted from fenix_req_procedure order by deleted,fenix_req_procedureid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['fenix_req_procedureid']+0);
    $fenix_req_procedureA[$temp_id] = $query_result[$kladd_i]['description'];
    $fenix_req_procedure_codeA[$temp_id] = $query_result[$kladd_i]['code'];
    $fenix_req_procedure_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
?>