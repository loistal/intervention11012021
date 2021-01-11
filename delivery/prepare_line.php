<?php

require('preload/unittype.php');

if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 0) { exit; }

$companytransportid = $_POST['companytransportid']+0;
$isreturn = 0; if ($_POST['isreturn'] == 1) { $isreturn = 1; }
if ($_SESSION['ds_deliveryaccessinvoices'] == 1 && $_SESSION['ds_deliveryaccessreturns'] == 0) { $isreturn = 0; }
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { $isreturn = 1; }

foreach ($_POST as $invoiceitemid => $quantity_delivered)
{
  $quantity_delivered = (double) $quantity_delivered;
  if ($quantity_delivered > 0)
  {
    if (strpos($invoiceitemid, 'quantity_delivered_') === 0)
    {
      $invoiceitemid = (int) substr($invoiceitemid,19);

      if ($invoiceitemid > 0)
      {
        $quantity_remaning = 0; $invoiceid = 0;
        $query = 'select quantity,invoiceid,unittypeid from invoiceitemhistory,product where invoiceitemhistory.productid=product.productid and invoiceitemid=?';
        $query_prm = array($invoiceitemid);
        require('inc/doquery.php');
        $dmp = 1;
        if ($num_results)
        {
          $quantity_remaning = (int) $query_result[0]['quantity'];
          $invoiceid = $query_result[0]['invoiceid'];
          $dmp = $unittype_dmpA[$query_result[0]['unittypeid']];
          $quantity_delivered = $quantity_delivered * $dmp;
        }
        
        $query = 'select sum(quantity_delivered) as quantity_delivered from linedelivery where deleted=0 and invoiceitemid=?';
        $query_prm = array($invoiceitemid);
        require('inc/doquery.php');
        if ($num_results) { $quantity_remaning -= (int) $query_result[0]['quantity_delivered']; }
        
        if ($quantity_delivered <= $quantity_remaning)
        {
          $undelivered = 0;
          $query = 'insert into linedelivery (invoiceitemid,quantity_delivered,userid,linedeliverydate,linedeliverytime,undelivered,linedeliverycomment,companytransportid,isreturn)
          values (?,?,?,curdate(),curtime(),?,?,?,?)';
          $query_prm = array($invoiceitemid,$quantity_delivered,$_SESSION['ds_userid'],$undelivered,$_POST['preparationtext'],$companytransportid,$isreturn);
          require('inc/doquery.php');
          ### update accountingdate (should perhaps be an option)
          if ($invoiceid > 0)
          {
            $query = 'update invoicehistory set accountingdate=curdate() where invoiceid=?';
            $query_prm = array($invoiceid);
            require('inc/doquery.php');
          }
          ###
          if ($quantity_delivered == $quantity_remaning)
          {
            $query = 'update invoiceitemhistory set delivered=1 where invoiceitemid=?';
            $query_prm = array($invoiceitemid);
            require('inc/doquery.php');
          }
        }
      }
    }
  }
}

foreach ($_POST as $invoiceitemid => $close_delivery)
{
  $close_delivery = (int) $close_delivery;
  if ($close_delivery == 1)
  {
    if (strpos($invoiceitemid, 'close_delivery_') === 0)
    {
      $invoiceitemid = (int) substr($invoiceitemid,15);

      if ($invoiceitemid > 0)
      {
        $quantity_remaning = 0;
        $query = 'select quantity from invoiceitemhistory where invoiceitemid=?';
        $query_prm = array($invoiceitemid);
        require('inc/doquery.php');
        if ($num_results) { $quantity_remaning = (int) $query_result[0]['quantity']; }
        
        $query = 'select sum(quantity_delivered) as quantity_delivered from linedelivery where deleted=0 and invoiceitemid=?';
        $query_prm = array($invoiceitemid);
        require('inc/doquery.php');
        if ($num_results) { $quantity_remaning -= (int) $query_result[0]['quantity_delivered']; }
        
        if ($quantity_remaning > 0)
        {
          $undelivered = 1;
          $query = 'insert into linedelivery (invoiceitemid,quantity_delivered,userid,linedeliverydate,linedeliverytime,undelivered,linedeliverycomment,companytransportid,isreturn)
          values (?,?,?,curdate(),curtime(),?,?,?,?)';
          $query_prm = array($invoiceitemid,$quantity_remaning,$_SESSION['ds_userid'],$undelivered,$_POST['preparationtext'],$companytransportid,$isreturn);
          require('inc/doquery.php');
          $query = 'update invoiceitemhistory set delivered=1 where invoiceitemid=?';
          $query_prm = array($invoiceitemid);
          require('inc/doquery.php');
        }
      }
    }
  }
}

require('preload/localvessel.php');
require('preload/temperature.php');
require('preload/deliverytype.php');
require('preload/invoicetag.php');

$localvesselid = $_POST['localvesselid']+0; if (!isset($_POST['localvesselid'])) { $localvesselid = -1; }
$islandid = $_POST['islandid']+0;
$townid = $_POST['townid']+0;
$clientsectorid = $_POST['clientsectorid']+0;
$temperatureid = $_POST['temperatureid']+0; if (!isset($_POST['temperatureid'])) { $temperatureid = -1; }
$userid = $_POST['userid']+0;
$clientfilter = $_POST['clientfilter'];
$deliverytypeid = $_POST['deliverytypeid']+0; if (!isset($_POST['deliverytypeid'])) { $deliverytypeid = -1; }

$orderby = $_POST['orderby']+0;

echo '<form method="post" action="delivery.php">';
echo '<h2>A ';
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { echo 'Recevoir'; }
else { echo 'Livrer'; }
echo ', par <select name="orderby">';
$orderme = 'islandname, townname,invoiceid'; # default now by island as per request 2013 03 25
echo '<option value="3"';
if ($orderby == 3) { echo ' selected'; $orderme = 'islandname,townname,invoiceid'; }
echo '>Île, Commune</option>';
echo '<option value="8"';
if ($orderby == 8) { echo ' selected'; $orderme = 'townname,invoiceid'; }
echo '>Commune</option>';
echo '<option value="1"';
if ($orderby == 1) { echo ' selected'; $orderme = 'deliverydate,invoiceid'; }
echo '>Date livraison</option>';
echo '<option value="7"';
if ($orderby == 7) { echo ' selected'; $orderme = 'accountingdate,invoiceid'; }
echo '>Date comptable</option>';
echo '<option value="2"';
if ($orderby == 2) { echo ' selected'; $orderme = 'localvesselid,invoiceid'; }
echo '>Bateau</option>';
echo '<option value="5"';
if ($orderby == 5) { echo ' selected'; $orderme = 'hascold,invoiceid'; }
echo '>Temperature</option>';
echo '<option value="6"';
if ($orderby == 6) { echo ' selected'; $orderme = 'invoicehistory.userid,invoiceid'; }
echo '>Facturier</option>';
echo '</select> <input name="filtrer" type="submit" value="Filtrer"></h2>';

$query = 'select invoiceitemhistory.invoiceitemid,invoiceitemhistory.productid,quantity,productname,netweightlabel,numberperunit,weight,invoicetagid,isreturn,isnotice,extraaddressid,deliverytypeid,invoicecomment,matchingid,daystopay,invoiceitemhistory.invoiceid,invoicedate,invoicetime,deliverydate
,clientname,extraname,invoicehistory.clientid as clientid,townname,islandname,localvesselid,initials,emphasiscolor,clientsectorid,hascold,unittypeid
from invoicehistory,invoiceitemhistory,product,client,town,island,usertable,clientterm
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid and client.clienttermid=clientterm.clienttermid and invoicehistory.userid=usertable.userid
and client.townid=town.townid and town.islandid=island.islandid and invoicehistory.clientid=client.clientid
and delivered=0 and confirmed=1 and invoicegroupid=0';
$query = $query . ' and isreturn="'.$isreturn.'"';
if ($islandid>0) { $query = $query . ' and (extraaddressid>0 or (extraaddressid=0 and town.islandid="'.$islandid.'"))'; }
if ($townid>0) { $query = $query . ' and (extraaddressid>0 or (extraaddressid=0 and client.townid="'.$townid.'"))'; }
if ($localvesselid>=0) { $query = $query . ' and invoicehistory.localvesselid="'.$localvesselid.'"'; }
if ($temperatureid>=0) { $query = $query . ' and invoicehistory.hascold="'.$temperatureid.'"'; }
if ($temperatureid==-2) { $query = $query . ' and invoicehistory.hascold>0'; }
if ($clientsectorid>0) { $query = $query . ' and (extraaddressid>0 or (extraaddressid=0 and client.clientsectorid="'.$clientsectorid.'"))'; }
if ($userid>0) { $query = $query . ' and invoicehistory.userid="'.$userid.'"'; }
if ($clientfilter != "") { $query = $query . ' and (client.clientid="'.($clientfilter+0).'" or lower(clientname) like "%'.mb_strtolower(d_encode($clientfilter)).'%")'; }
if ($clienttermid>0) { $query = $query . ' and client.clienttermid="'.$clienttermid.'"'; }
if ($deliverytypeid>=0) { $query = $query . ' and deliverytypeid="'.$deliverytypeid.'"'; }
$query = $query . ' order by ' . $orderme;

$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);

echo '<table class="detailinput"><tr><td><b>Bateau</td><td><b>Île</td>';
echo '<td><b>Commune</td>';
echo '<td><b>Facturier</td><td><b>';
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
{
  echo 'Avoir';
}
else
{
  echo 'Facture';
}
echo '</td><td><b>Client</td><td><b>Type</td><td><b>Infos</td><td><b>Livraison le</td>';
echo '<td><b>Produit<td><b>Livré<td><b>Livrer<td><b>Ne plus livrer';
echo '<tr>';
$dp_itemname = 'localvessel'; $dp_allowall = 1; $dp_selectedid = $localvesselid;
require('inc/selectitem.php');

$dp_itemname = 'island'; $dp_allowall = 1; $dp_noblank = 1; $dp_selectedid = $islandid;
require('inc/selectitem.php');

$dp_itemname = 'town'; $dp_allowall = 1; $dp_noblank = 1; $dp_selectedid = $townid;
require('inc/selectitem.php');

$dp_itemname = 'user'; $dp_allowall = 1; $dp_noblank = 1; $dp_short = 1; $dp_selectedid = $userid;
require('inc/selectitem.php');

if ($_SESSION['ds_deliveryaccessinvoices'] == 1 && $_SESSION['ds_deliveryaccessreturns'] == 1)
{
  echo '<td><select name="isreturn"><option value=0>Factures</option><option value=1';
  if ($isreturn == 1) { echo ' selected'; }
  echo '>Avoirs</option></select></td>';
}
else
{
  echo '<td>&nbsp;</td>';
}
echo '<td><input type=text STYLE="text-align:right" name=clientfilter value="'.d_input($clientfilter).'"></td>';

$dp_itemname = 'deliverytype'; $dp_allowall = 1; $dp_selectedid = $deliverytypeid;
require('inc/selectitem.php');
echo '</td>';
echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  $dmp = $unittype_dmpA[$row['unittypeid']]; if ($dmp == 0) { $dmp = 1; }
  $quantity = $row['quantity']/$dmp;
  $showthisline = 1;
  $deliverytypeid = $row['deliverytypeid'];
  if ($row['daystopay'] == 0 && $row['matchingid'] == 0)
  {
    if ($deliverytypeid < 1 || $deliverytype_requirepaymentA[$deliverytypeid] == 1) { $showthisline = 0; }
  }
  $line_clientsectorid = $row['clientsectorid'];
  $townname = $row['townname'];
  $islandname = $row['islandname'];
  $eaid = $row['extraaddressid'];
  if ($eaid > 0)
  {
    $query = 'select clientsectorid,extraaddress.townid,town.islandid,townname,islandname from extraaddress,town,island where extraaddress.townid=town.townid and town.islandid=island.islandid and extraaddressid=?';
    $query_prm = array($eaid);
    require('inc/doquery.php');
    $townname = $query_result[0]['townname'];
    $islandname = $query_result[0]['islandname'];
    $line_clientsectorid = $query_result[0]['clientsectorid'];
    if ($islandid>0 && $query_result[0]['islandid'] != $islandid) { $showthisline = 0; }
    if ($townid>0 && $query_result[0]['townid'] != $townid) { $showthisline = 0; }
    if ($clientsectorid>0 && $query_result[0]['clientsectorid'] != $clientsectorid) { $showthisline = 0; }
  }

  $quantity_delivered = 0;
  $query = 'select sum(quantity_delivered) as quantity_delivered from linedelivery where deleted=0 and invoiceitemid=?';
  $query_prm = array($row['invoiceitemid']);
  require('inc/doquery.php');
  if ($num_results) { $quantity_delivered = $query_result[0]['quantity_delivered']/$dmp; }
  if ($quantity_delivered >= $quantity) { $showthisline = 0; }
  
  if ($showthisline)
  {
    $invoiceid = $row['invoiceid'];

    $coldstorage = $temperatureA[($row['hascold']+0)];
    $vesselname = $localvesselA[$row['localvesselid']];
    if ($vesselname == 'Aucun') { $vesselname = "&nbsp;"; }
    $initials = $row['initials'];

    echo '<tr><td>' . $vesselname . '</td><td>' . $islandname . '</td>';
    echo '<td>' . $townname . '</td>';
    echo '<td STYLE="color:#'.$row['emphasiscolor'].'">' . $initials . '</td>';
    echo '<td class="breakme" align=right>';
    if ($row['invoicetagid'] > 0) { echo '('.$invoicetagA[$row['invoicetagid']].') '; }
    if ($row['isnotice']) { echo $_SESSION['ds_term_invoicenotice'] . '&nbsp;'; }
    if ($row['isreturn'] == 1 && !($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)) { echo '(Avoir)&nbsp;'; }
    echo '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $invoiceid . '" target=_blank>'.$invoiceid.'</a></td>';
    $showclientname = d_decode($row['clientname']) . ' (' . $row['clientid'] . ') '.$row['extraname'];
    echo '<td class="breakme"><a href="reportwindow.php?report=showclient&usedefaultstyle=1&client=' . $row['clientid'] . '" target=_blank>' . d_output($showclientname) . '</a></td>';
    echo '<td>' . d_output($deliverytypeA[$deliverytypeid]) . '</td><td class="breakme"><font size=-1>' . $row['invoicecomment'] . '</font></td><td align=right>' . datefix2($row['deliverydate']) . '</td>';
    echo '<td>', d_output(d_decode($row['productname'])), ' (', $row['productid'] , ')';
    echo '<td align=right>',($quantity_delivered),' / ', ($quantity); # removed myfix because of $dmp
    echo '<td><input style="text-align: right" type=number name="quantity_delivered_',$row['invoiceitemid'],'" min=0 step=0.001>'; # TODO set step to 1 if no $dmp other than 1 exists
    echo '<td align=center><input type=checkbox name="close_delivery_',$row['invoiceitemid'],'" value=1>';
  }
}
echo '<tr>';
$dp_colspan = 14;
$dp_itemname = 'companytransport'; $dp_selectedid = $companytransportid;
require('inc/selectitem.php');
echo ' &nbsp; Infos: <input type="text" STYLE="text-align:right" name="preparationtext" size=30> &nbsp; <input type="submit" value="';
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { echo 'Recevoir'; }
else { echo 'Livrer'; }
echo '"><input type=hidden name="deliverymenu" value="' . $deliverymenu . '"></table></form>';

?>