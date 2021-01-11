<?php
# load $temperatureA[$temperatureid]
if (!isset($temperatureA))
{
  $query = 'select temperatureid,temperaturename from temperature order by temperaturename';
  #,deleted
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $temperatureid_temp = (int) ($query_result[$kladd_i]['temperatureid']+0);
    $temperatureA[$temperatureid_temp] = $query_result[$kladd_i]['temperaturename'];
    #$temperature_deletedA[$vesselid_prm] = $query_result[$kladd_i]['deleted'];
  }
}
?>