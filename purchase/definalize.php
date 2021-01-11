<?php

$shipmentid = (int) $_POST['shipmentid'];
$confirmed = (int) $_POST['confirmed'];

if ($shipmentid > 0)
{
  $query = 'select shipmentstatus from shipment where shipmentid=?';
  $query_prm = array($shipmentid);
  require('inc/doquery.php');
  if ($query_result[0]['shipmentstatus'] != 'Fini')
  {
    echo '<p>Dossier ' . $shipmentid . ' n\'est pas finalized.</p><br>';
    $shipmentid = 0;
  }
}

if ($shipmentid == 0)
{
  ?><h2>De-Finalize</h2>
  <form method="post" action="purchase.php"><table>
  <tr><td>Dossier: </td><td><input autofocus type="text" STYLE="text-align:right" name="shipmentid" size=10></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
}
else
{
  if ($confirmed == 1)
  {
    $query = 'select purchasebatchid from purchasebatch where shipmentid=?';
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    $limit = (int) $num_results;
    $query = 'delete from purchasebatch where shipmentid=? limit ' . $limit;
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    $query = 'update shipment set shipmentstatus="Commandé" where shipmentid=?';
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    echo '<p>Dossier ' . $shipmentid . ' de-finalized.</p>';
  }
  else
  {
    ?><h2>De-Finalize dossier <?php echo $shipmentid; ?>?</h2>
    <form method="post" action="purchase.php"><table>
    <tr><td colspan="2" align="center">
    <input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>">
    <input type=hidden name="shipmentid" value="<?php echo $shipmentid; ?>">
    <input type=hidden name="confirmed" value=1>
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
  }
}

?>