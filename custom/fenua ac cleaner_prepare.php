<script type='text/javascript' src='jq/jquery.js'></script>

<script type='text/javascript'>
$(window).load(function(){
    $('.chkclass').click(function() {
        var sum = 0;
        $('.chkclass:checked').each(function() {
            sum += parseFloat($(this).closest('tr').find('.weight').text());
        });
        $('#sum').html('<b>'+sum);
    });
});
</script>
<?php

if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 0) { exit; }

$PA['companytransportid'] = 'int';
$PA['employeeid'] = 'int';
$PA['employee2id'] = 'int';
$PA['isreturn'] = 'int';
$PA['filtrer'] = '';
$PA['showonly'] = '';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
require('inc/readpost.php');
if ($_SESSION['ds_deliveryaccessinvoices'] == 1 && $_SESSION['ds_deliveryaccessreturns'] == 0) { $isreturn = 0; }
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { $isreturn = 1; }
if ($startdate == '') { $startdate = d_builddate(1,1,$_SESSION['ds_startyear']); }
if ($stopdate == '') { $stopdate = d_builddate(1,1,$_SESSION['ds_endyear']); }

$ok = 1;
if ($filtrer != '') { $ok = 0; }
if ($showonly == 'Aperçu')
{
  ?>
  
  <form id="TheForm" method="post" action="customreportwindow.php" target="_blank">
  "Preview" affiché.
  <?php
  $ourcounter = 0;
  foreach($_POST as $key => $value)
  {
    $param_name = 'invoice';
    if(substr($key, 0, strlen($param_name)) == $param_name)
    {
      $ourcounter++;
      echo '<input type="hidden" name="invoice'.$ourcounter.'" value="'.substr($key, strlen($param_name)).'">';
    }
  }
  ?>
  <input type="hidden" name="report" value="deliverylist">
  </form><br>
  <script type="text/javascript">
  document.getElementById('TheForm').submit();
  </script>
  <?php
  $ok = 0;
}
if ($ok)
{
  $query = 'select invoiceid from invoice where confirmed=0 and invoicegroupid=0 and isreturn=? and deliverydate>=? and deliverydate<=?';
  $query .= ' order by invoiceid';
  $query_prm = array($isreturn, $startdate, $stopdate);
  require('inc/doquery.php');
  $main_result = $query_result; unset($query_result); $num_results_main = $num_results;
  $invoicegroupid = 0; $totalweight = 0;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $invoiceid = $main_result[$i]['invoiceid'];
    $invoicenumber = 'invoice' . $invoiceid;
    if (isset($_POST[$invoicenumber]) && $_POST[$invoicenumber] == 1)
    {
      if ($invoicegroupid == 0)
      {
        $query = 'insert into invoicegroup (invoicegroupdate,invoicegrouptime,preparationtext,userid,companytransportid,returns,employeeid,employee2id) values (CURDATE(),CURTIME(),?,?,?,?,?,?)';
        $query_prm = array($_POST['preparationtext'],$_SESSION['ds_userid'],$companytransportid,$isreturn,$employeeid,$employee2id);
        require('inc/doquery.php');
        $invoicegroupid = $query_insert_id;
        
        echo 'Livraison <a href="customreportwindow.php?report=deliverylist&invoicegroupid=' . $invoicegroupid . '" target=_blank>' . $invoicegroupid . '</a> créé avec factures: ';
      }
      $query = 'update invoice set invoicegroupid="' . $invoicegroupid . '" where invoiceid="' . $invoiceid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      echo '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $invoiceid . '" target=_blank>'.$invoiceid.'</a> ';
      $query = 'select invoiceweight from invoice where invoiceid="' . $invoiceid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row2 = $query_result[0];
      $totalweight = $totalweight + $row2['invoiceweight'];
    }
  }
  if ($invoicegroupid > 0)
  {
    $query = 'update invoicegroup set totalweight=? where invoicegroupid=?';
    $query_prm = array($totalweight,$invoicegroupid);
    require('inc/doquery.php');
    echo '<br><br>';
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

echo '<form method="post" action="custom.php">';
echo '<h2>À ';
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { echo 'Recevoir'; }
else { echo 'Livrer'; }
echo ', par <select name="orderby">';
$orderme = 'islandname, townname,invoiceid';
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
if ($orderby == 6) { echo ' selected'; $orderme = 'invoice.userid,invoiceid'; }
echo '>Facturier</option>';
echo '</select> <input name="filtrer" type="submit" value="Filtrer"></h2>';

$query = 'select invoicetagid,isreturn,isnotice,invoiceweight,extraaddressid,deliverytypeid,invoicecomment,matchingid,daystopay,invoiceid,invoicedate,invoicetime,deliverydate
,clientname,extraname,invoice.clientid as clientid,townname,islandname,localvesselid,initials,emphasiscolor,clientsectorid,hascold
from invoice,client,town,island,usertable,clientterm
where client.clienttermid=clientterm.clienttermid and invoice.userid=usertable.userid and client.townid=town.townid and town.islandid=island.islandid and invoice.clientid=client.clientid
and confirmed=0 and invoicegroupid=0 and deliverydate>=? and deliverydate<=?';
$query = $query . ' and isreturn="'.$isreturn.'"';
if ($islandid>0) { $query = $query . ' and (extraaddressid>0 or (extraaddressid=0 and town.islandid="'.$islandid.'"))'; }
if ($townid>0) { $query = $query . ' and (extraaddressid>0 or (extraaddressid=0 and client.townid="'.$townid.'"))'; }
if ($localvesselid>=0) { $query = $query . ' and invoice.localvesselid="'.$localvesselid.'"'; }
if ($temperatureid>=0) { $query = $query . ' and invoice.hascold="'.$temperatureid.'"'; }
if ($temperatureid==-2) { $query = $query . ' and invoice.hascold>0'; }
if ($clientsectorid>0) { $query = $query . ' and (extraaddressid>0 or (extraaddressid=0 and client.clientsectorid="'.$clientsectorid.'"))'; }
if ($userid>0) { $query = $query . ' and invoice.userid="'.$userid.'"'; }
if ($clientfilter != "") { $query = $query . ' and (client.clientid="'.($clientfilter+0).'" or lower(clientname) like "%'.mb_strtolower(d_encode($clientfilter)).'%")'; }
if ($deliverytypeid>=0) { $query = $query . ' and deliverytypeid="'.$deliverytypeid.'"'; }
$query = $query . ' order by ' . $orderme;

$query_prm = array($startdate, $stopdate);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);

echo '<table class="detailinput"><tr><td><b>Bateau</td><td><b>Île</td>';
echo '<td><b>Commune</td>';
echo '<td><b>Temp.</td><td><b>Facturier</td><td><b>';
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)
{
  echo 'Avoir';
}
else
{
  echo 'Facture';
}
echo '</td><td><b>Client</td><td><b>Type</td><td><b>Infos</td><td><b>Livraison le</td><td><b>Confirmé le</td><td><b>Livrer</td><td><b>Kg</td></tr>';
echo '<tr>';
$dp_itemname = 'localvessel'; $dp_allowall = 1; $dp_selectedid = $localvesselid;
require('inc/selectitem.php');

$dp_itemname = 'island'; $dp_allowall = 1; $dp_noblank = 1; $dp_selectedid = $islandid;
require('inc/selectitem.php');

$dp_itemname = 'town'; $dp_allowall = 1; $dp_noblank = 1; $dp_selectedid = $townid;
require('inc/selectitem.php');

$dp_itemname = 'temperature'; $dp_allowall = 1; $dp_nonempty = 1; $dp_selectedid = $temperatureid;
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
echo '<td colspan=3>De ';

if ($startdate == d_builddate(1,1,$_SESSION['ds_startyear'])) { $dp_setempty = 1; }
$datename = 'startdate';
require('inc/datepicker.php');
echo ' à ';
if ($stopdate == d_builddate(1,1,$_SESSION['ds_endyear'])) { $dp_setempty = 1; }
$datename = 'stopdate';
require('inc/datepicker.php');

echo '<td>&nbsp;</td><td>&nbsp;</td></tr>';
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
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
  if ($showthisline)
  {
    $invoiceid = $row['invoiceid'];
    if ($row['hascold'] > 0) { $coldstorage = $temperatureA[($row['hascold']+0)]; }
    else { $coldstorage = ''; }
    $vesselname = ''; #$localvesselA[$row['localvesselid']];
    if ($vesselname == 'Aucun') { $vesselname = "&nbsp;"; }
    $initials = $row['initials'];
    echo '<tr>';
    echo '<td>' . $vesselname . '</td><td>' . $islandname . '</td>';
    echo '<td>' . $townname . '</td>';
    echo '<td>' . $coldstorage . '</td><td STYLE="color:#'.$row['emphasiscolor'].'">' . $initials . '</td>';
    echo '<td class="breakme" align=right>';
    if ($row['invoicetagid'] > 0) { echo '('.$invoicetagA[$row['invoicetagid']].') '; }
    if ($row['isnotice']) { echo $_SESSION['ds_term_invoicenotice'] . '&nbsp;'; }
    if ($row['isreturn'] == 1 && !($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1)) { echo '(Avoir)&nbsp;'; }
    echo '<a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $invoiceid . '" target=_blank>'.$invoiceid.'</a></td>';
    $showclientname = d_decode($row['clientname']) . ' (' . $row['clientid'] . ') '.$row['extraname'];
    echo '<td class="breakme"><a href="reportwindow.php?report=showclient&usedefaultstyle=1&client=' . $row['clientid'] . '" target=_blank>' . d_output($showclientname) . '</a></td>';
    $invoiceweight = round($row['invoiceweight']/1000,1);
    echo '<td>'; if (isset($deliverytypeA[$deliverytypeid])) { echo d_output($deliverytypeA[$deliverytypeid]); }
    echo '<td class="breakme"><font size=-1>' . $row['invoicecomment'] . '</font></td><td align=right>' . datefix2($row['deliverydate']) . '</td>';
    echo '<td align=right>' . substr($row['invoicetime'],0,5) . ' ' . datefix2($row['invoicedate']) . '</td>';
    $invoicenumber = 'invoice' . $row['invoiceid'];
    echo '<td align=center><input class="chkclass" type="checkbox" name="' . $invoicenumber . '" value="1"';
    if (isset($_POST[$invoicenumber]) && $_POST[$invoicenumber] == 1) { echo ' checked'; }
    echo '></td><td class="sum weight" align=right>' . $invoiceweight;
  }
}

echo '<tr>';
$dp_itemname = 'companytransport'; $dp_description = 'Bateau'; $dp_colspan = 12; $dp_selectedid = $companytransportid;
require('inc/selectitem.php');
echo '<tr>';
$dp_itemname = 'employee'; $dp_colspan = 12; $dp_description = 'Chauffeur'; $dp_isdelivery = 1; $dp_selectedid = $employeeid;
require('inc/selectitem.php');
echo '<tr>';
$dp_itemname = 'employee'; $dp_colspan = 12; $dp_description = 'Preparateur'; $dp_ispicking = 1; $dp_selectedid = $employee2id;
$dp_addtoid = '2';
require('inc/selectitem.php');
echo '<tr><td>Infos:<td colspan=12><input type="text" STYLE="text-align:right" name="preparationtext" size=30>';
echo ' &nbsp; <input type="submit" value="';
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { echo 'Recevoir'; }
else { echo 'Livrer'; }
echo '"> <td id="sum" align=right>'; # <input type="submit" name="showonly" value="Aperçu">
echo '<input type=hidden name="custommenu" value="' . $custommenu . '"></table></form>';

?>