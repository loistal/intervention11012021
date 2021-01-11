<?php

exit;

# customized file to make product catalogue available to external application

# Could you please contact him to give him access to product photos, product name and conditionnement of Family Rice, Sugar, Flour ONLY for a test run

require ('inc/standard.php');

$query = 'select * from globalvariables where primaryunique=1';
$query_prm = array('dauphin');
require ('inc/doquery.php');

if ($query_result[0]['customname'] == 'Wing Chong')
{
  $sep = ';';
  $endline = chr(13) . chr(10);
  echo '# product catalogue, separator ";", endline char(13)+char(10)',$endline;
  echo '# id',$sep,'nom',$sep,'cond',$endline;
  
  $query = 'select productid,productname,numberperunit,netweightlabel
  from product,productfamily
  where product.productfamilyid=productfamily.productfamilyid
  and discontinued=0 and notforsale=0
  and (productfamilygroupid=1 or productfamilygroupid=2 or productfamilygroupid=3)';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    echo $query_result[$i]['productid'],$sep;
    echo d_decode($query_result[$i]['productname']),$sep;
    $cond = $query_result[$i]['netweightlabel'];
    if ($query_result[$i]['numberperunit'] > 1) { $cond = $query_result[$i]['numberperunit'] . ' x ' . $cond; }
    echo $cond;
    echo $endline;
  }
}

?>