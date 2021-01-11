<?php
# load $countryA[$countryid]
if (!isset($countryA))
{
  $query = 'select countryid,countryname,sofixcode,fenixcode,deleted,`rank` from country order by deleted,`rank`,countryname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $countryid_temp = (int) ($query_result[$kladd_i]['countryid']+0);
    $countryA[$countryid_temp] = $query_result[$kladd_i]['countryname'];
    $country_sofixcodeA[$countryid_temp] = $query_result[$kladd_i]['sofixcode'];
    $country_fenixcodeA[$countryid_temp] = $query_result[$kladd_i]['fenixcode'];
    $country_deletedA[$countryid_temp] = $query_result[$kladd_i]['deleted'];
    $country_rankA[$countryid_temp] = $query_result[$kladd_i]['rank'];
  }
}
?>