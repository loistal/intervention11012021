<?php
if (!isset($localvesselA))
{
  $query = 'select localvesselid,localvesselname,deleted from localvessel order by deleted,localvesselname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $localvesselid_temp = (int) ($query_result[$kladd_i]['localvesselid']+0);
    $localvesselA[$localvesselid_temp] = $query_result[$kladd_i]['localvesselname'];
    $localvessel_deletedA[$localvesselid_temp] = $query_result[$kladd_i]['deleted'];
  }
  if ($num_results) { $localvesselA[0] = ''; } # TODO for all preloads
}
?>