<?php
if (!isset($collectiveagreementA))
{
  $query = 'select * from collectiveagreement order by collectiveagreementname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['collectiveagreementid']+0);
    $collectiveagreementA[$id_temp] = $query_result[$kladd_i]['collectiveagreementname'];
    $collectiveagreement_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>