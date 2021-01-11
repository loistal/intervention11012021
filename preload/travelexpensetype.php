<?php
# load $travelexpensetypeA[$travelexpensetypeid]
if (!isset($travelexpensetypeA))
{
  $query = 'select * from travelexpensetype';
  if(!$ds_showdeleteditems)
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by travelexpensetypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $travelexpensetypeid_temp = (int) ($query_result[$kladd_i]['travelexpensetypeid']+0);
    $travelexpensetypeA[$travelexpensetypeid_temp] = $query_result[$kladd_i]['travelexpensetypename'];
    $travelexpensetype_refundlimitvatA[$travelexpensetypeid_temp] = $query_result[$kladd_i]['refundlimitVAT'];
    $travelexpensetype_deletedA[$travelexpensetypeid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
?>