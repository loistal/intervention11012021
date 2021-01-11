<?php
require('preload/companytransport.php');
require('preload/extraaddress.php');
require('preload/town.php');
require('preload/clientterm.php');

$datename = 'invoicegroupdate'; require('inc/datepickerresult.php');
$companytransportid = $_POST['companytransportid']+0;

$ourtitle = 'Tourné';
if ($companytransportid > 0) { $ourtitle.=' '.d_output($companytransportA[$companytransportid]); }
$ourtitle.=' '.datefix($invoicegroupdate);
showtitle($ourtitle);
echo '<h2>' . $ourtitle . '</h2>';

$query = 'select matchingid,clienttermid,invoiceprice,invoicehistory.clientid,clientname,quarter,townid,invoiceid,extraaddressid from invoicegroup,invoicehistory,client where invoicegroup.invoicegroupid=invoicehistory.invoicegroupid and invoicehistory.clientid=client.clientid';
$query = $query . ' and invoicegroupdate=?';
$query_prm = array($invoicegroupdate);
if ($companytransportid > 0) { $query = $query . ' and companytransportid=?'; array_push($query_prm, $companytransportid); }
$query = $query . 'order by clientid';
require('inc/doquery.php');
#echo $query .'<br>'.$num_results.'<br>'.$invoicegroupdate;
$main_result = $query_result; $num_results_main = $num_results;
echo '<table border=1 cellpadding=1 cellspacing=1><tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Factures</td><td><b>Montant</td></tr>';
$lastclientid = -1; $clienttotal = 0;
for ($i=0;$i<$num_results_main;$i++)
{
  if ($lastclientid != $main_result[$i]['clientid'])
  {
    if ($i!=0) { echo '</td><td align=right>' . myfix($clienttotal) . '</td></tr>'; $clienttotal = 0; }
    $eaid = $main_result[$i]['extraaddressid'];
    if ($eaid > 0)
    {
      $townid = $extraaddress_townidA[$eaid];
      $address = $extraaddress_quarterA[$eaid] . ' ' . $townA[$townid];
    }
    else
    {
      $townid = $main_result[$i]['townid'];
      $address = $main_result[$i]['quarter'] . ' ' . $townA[$townid];
    }
    echo '<tr><td>' . d_output(d_decode($main_result[$i]['clientname'])) . ' ('.$main_result[$i]['clientid'].')</td><td>'.$address.'</td><td>';
  }
  echo ' ' . $main_result[$i]['invoiceid'];
  $clienttermid = $main_result[$i]['clienttermid'];
  if ($clientterm_daystopayA[$clienttermid]==0 && $main_result[$i]['matchingid']==0) { $clienttotal+=$main_result[$i]['invoiceprice']; } # does not account for the "special" field, example: end of month
  $lastclientid = $main_result[$i]['clientid'];
}
echo '</td><td align=right>' . myfix($clienttotal) . '</td></tr>'; #copied from above
echo '</table>';
?>