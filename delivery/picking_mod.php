<h2>Modifier Livraison</h2>
<?php

$PA['invoicegroupid'] = 'uint';
$PA['update'] = 'uint';
$PA['companytransportid'] = 'uint';
$PA['employeeid'] = 'uint';
$PA['employee2id'] = 'uint';
$PA['preparationtext'] = '';
require('inc/readpost.php');

if ($invoicegroupid > 0)
{
  if ($update == 1)
  {
    $query = 'update invoicegroup set companytransportid=?,employeeid=?,employee2id=?,preparationtext=?
    where invoicegroupid=? and status<>1';
    $query_prm = array($companytransportid,$employeeid,$employee2id,$preparationtext,$invoicegroupid);
    require('inc/doquery.php');
    if ($num_results) { echo '<p>Livraison '.$invoicegroupid.' modifiée.</p>'; }
  }
  $query = 'select companytransportid,employeeid,employee2id,preparationtext
  from invoicegroup where invoicegroupid=? and status<>1';
  $query_prm = array($invoicegroupid);
  require('inc/doquery.php');
  if ($num_results)
  {
    $companytransportid = $query_result[0]['companytransportid'];
    $employeeid = $query_result[0]['employeeid'];
    $employee2id = $query_result[0]['employee2id'];
    $preparationtext = $query_result[0]['preparationtext'];
    echo '<form method="post" action="delivery.php">
    <table><tr><td colspan=2>Modifier livraison '.$invoicegroupid.' :<tr>';
    $dp_itemname = 'companytransport'; $dp_description = 'Bateau'; $dp_selectedid = $companytransportid;
    require('inc/selectitem.php');
    $dp_itemname = 'employee'; $dp_description = 'Chauffeur'; $dp_isdelivery = 1; $dp_selectedid = $employeeid;
    require('inc/selectitem.php');
    $dp_itemname = 'employee'; $dp_description = 'Picking'; $dp_ispicking = 1; $dp_selectedid = $employee2id; $dp_addtoid = 2;
    require('inc/selectitem.php');
    echo '<tr><td>Infos:<td><input type="text" STYLE="text-align:right" name="preparationtext"
    value="' . d_input($preparationtext) . '" size=30>';
    echo '<tr><td colspan=2><input type="submit" value="Valider"></table>
    <input type=hidden name="update" value=1>
    <input type=hidden name="deliverymenu" value="' . $deliverymenu . '">
    <input type=hidden name="invoicegroupid" value="' . $invoicegroupid . '"></form>';
  }
  else { $invoicegroupid = 0; }
}
if ($invoicegroupid == 0)
{
  echo '<form method="post" action="delivery.php">
  <table><tr><td>Livraison :<td><input autofocus name="invoicegroupid">
  <tr><td colspan=2><input type="submit" value="Continuer"></table>
  <input type=hidden name="deliverymenu" value="' . $deliverymenu . '"></form>';
}

?>