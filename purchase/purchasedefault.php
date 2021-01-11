<?php

$query = 'select productid,productname,netweightlabel,numberperunit,currentstock,orderalert
from product
where discontinued=0 and orderalert>0 and currentstock<(orderalert*numberperunit)';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
if ($num_results_main)
{
  echo '<div class="myblock" style="width:90%;margin:auto;"><h2>Produits en-dessous du seuil alerte</h2><table class="report">
  <thead><th colspan=2>Produit<th>Stock<th>En cours</thead>';
  for ($i=0;$i < $num_results_main;$i++)
  {
    $incoming = 0;
    $query = 'select sum(amount) as amount from purchase,shipment where purchase.shipmentid=shipment.shipmentid and productid=? and shipmentstatus<>"Fini"';
    $query_prm = array($main_result[$i]['productid']);
    require('inc/doquery.php');
    if ($query_result[0]['amount'] > 0)
    {
      $incoming += ($query_result[0]['amount'] / $main_result[$i]['numberperunit']);
    }
    echo d_tr();
    echo d_td($main_result[$i]['productid'], 'right');
    echo d_td(d_decode($main_result[$i]['productname']) . ' ' . $main_result[$i]['numberperunit'] . ' x ' . $main_result[$i]['netweightlabel']);
    echo d_td($main_result[$i]['currentstock'], 'int');
    echo d_td($incoming, 'int');
  }
  echo '</table></div><br>';
}

?>