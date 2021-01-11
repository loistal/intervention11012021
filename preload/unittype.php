<?php
if (!isset($unittypeA))
{
  $query = 'select unittypeid,unittypename,unittypefullname,deleted,displaymultiplier from unittype order by deleted,unittypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $unittypeid_temp = (int) ($query_result[$kladd_i]['unittypeid']+0);
    $unittypeA[$unittypeid_temp] = $query_result[$kladd_i]['unittypename'];
    $unittype_fullnameA[$unittypeid_temp] = $query_result[$kladd_i]['unittypefullname'];
    $unittype_deletedA[$unittypeid_temp] = $query_result[$kladd_i]['deleted'];
    $unittype_dmpA[$unittypeid_temp] = $query_result[$kladd_i]['displaymultiplier'];
  }
}
unset($kladd_i,$unittypeid_temp);
?>