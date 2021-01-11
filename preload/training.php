<?php
# load $trainingA[$trainingid]
if (!isset($trainingA))
{
  $query = 'select * from training';
  if(!$_SESSION['ds_showdeleteditems'])
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by trainingname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $trainingid_temp = (int) ($query_result[$kladd_i]['trainingid']+0);
    $trainingA[$trainingid_temp] = $query_result[$kladd_i]['trainingname'];
    $training_refA[$trainingid_temp] = $query_result[$kladd_i]['trainingref'];
    $training_employeecategoryidA[$trainingid_temp] = $query_result[$kladd_i]['employeecategoryid'];
    $training_mandatoryA[$trainingid_temp] = $query_result[$kladd_i]['mandatory'];
    $training_periodicA[$trainingid_temp] = $query_result[$kladd_i]['periodic'];
    $training_deletedA[$trainingid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>