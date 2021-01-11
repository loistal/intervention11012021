<?php
# load $imageA[$imageid]
if (!isset($imageA))
{
  $query = 'select imageid,imagetext,productid,clientid from image'; # no order
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $imageid_temp = (int) ($query_result[$kladd_i]['imageid']+0);
    $imagetextA[$imageid_temp] = $query_result[$kladd_i]['imagetext'];
    if ($imagetextA[$imageid_temp] == '') { $imagetextA[$imageid_temp] = d_trad('withoutname'); }
    $image_productA[$imageid_temp] = $query_result[$kladd_i]['productid'];
    $image_clientA[$imageid_temp] = $query_result[$kladd_i]['clientid'];
    #$image_deletedA[$imageid_temp] = $query_result[$kladd_i]['deleted'];
  }
}
unset($imageid_temp);
?>