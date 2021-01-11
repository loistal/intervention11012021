<?php
if (!isset($qr_locationA))
{
  $query = 'select qr_locationid,qr_locationname,clientid,deleted from qr_location order by deleted,qr_locationid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temp_id = (int) ($query_result[$kladd_i]['qr_locationid']+0);
    $qr_locationA[$temp_id] = $query_result[$kladd_i]['qr_locationname'];
    $qr_location_clientidA[$temp_id] = $query_result[$kladd_i]['clientid'];
    $qr_location_deletedA[$temp_id] = $query_result[$kladd_i]['deleted'];
  }
}
?>