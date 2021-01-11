<?php
# load $jobA[$jobid]
if (!isset($jobA))
{
  $query = 'select * from job';
  if(!$_SESSION['ds_showdeleteditems'])
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by jobname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $jobid_temp = (int) ($query_result[$kladd_i]['jobid']+0);
    $jobA[$jobid_temp] = $query_result[$kladd_i]['jobname'];
    $job_deletedA[$jobid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>