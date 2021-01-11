<?php
# load $regulationtypeA[$regulationtypeid]
if (!isset($regulationtypeA))
{
  $query = 'select regulationtypeid,regulationtypename,showasterix from regulationtype order by regulationtypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $regulationtypeid_temp = (int) ($query_result[$kladd_i]['regulationtypeid']+0);
    $regulationtypeA[$regulationtypeid_temp] = $query_result[$kladd_i]['regulationtypename'];
    $regulationtype_showasterixA[$regulationtypeid_temp] = $query_result[$kladd_i]['showasterix'];
  }
}
?>