<?php
# load $commissionrateA[$commissionrateid]
if (!isset($commissionrateA))
{
  $query = 'select commissionrateid,commissionrate,deleted from commissionrate order by deleted,commissionrate';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $id_temp = (int) ($query_result[$kladd_i]['commissionrateid']+0);
    $commissionrateA[$id_temp] = $query_result[$kladd_i]['commissionrate']+0;
    $commissionrate_deletedA[$id_temp] = $query_result[$kladd_i]['deleted'];
  }
}
unset ($id_temp, $kladd_i);
?>