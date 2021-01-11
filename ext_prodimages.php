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
  echo '# product image index, separator ";", endline char(13)+char(10)',$endline;
  echo '# productid',$sep,'[imageid]...',$endline;
  
  $query = 'select product.productid,imageid
  from product,productfamily,image
  where product.productfamilyid=productfamily.productfamilyid and image.productid=product.productid
  and discontinued=0 and notforsale=0
  and (productfamilygroupid=1 or productfamilygroupid=2 or productfamilygroupid=3)
  order by productid,imageorder';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    echo $query_result[$i]['productid'],$sep;
    echo $query_result[$i]['imageid'];
    echo $endline;
  }
}

?>