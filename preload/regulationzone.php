<?php
# load $regulationzoneA[$regulationzoneid]
if (!isset($regulationzoneA))
{
  $query = 'select regulationzoneid,regulationzonename,regulationzonerate from regulationzone order by regulationzonename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $regulationzoneid_temp = (int) ($query_result[$kladd_i]['regulationzoneid']+0);
    $regulationzoneA[$regulationzoneid_temp] = $query_result[$kladd_i]['regulationzonename'];
    $regulationzone_regulationzonerateA[$regulationzoneid_temp] = $query_result[$kladd_i]['regulationzonerate'];
  }
}
?>