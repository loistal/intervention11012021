<?php

# TODO refactor, remove currentstep

$PA['dontreadvalues'] = 'int';
$PA['newshipment'] = 'int';
$PA['shipmentid'] = 'int';
$PA['shipmentlines'] = 'int';
$PA['saveme'] = 'int';
require('inc/readpost.php');

if ($shipmentlines == 0) { $shipmentlines = (int) $_SESSION['ds_purchaselines']; }
if ($shipmentlines < 5) { $shipmentlines = 5; }
if ($shipmentlines > 1000) { $shipmentlines = 1000; }


switch($currentstep)
{
  # Which shipment arrives
  case '0':
  ?><h2>Commande</h2>
  <form method="post" action="purchase.php"><table><tr><td>Numéro commande: </td><td><input autofocus type="text" STYLE="text-align:right" name="shipmentid" size=5></td></tr>
  <tr><td colspan=2><input type="checkbox" name="newshipment" value="1"> Nouvelle commande</td></tr>
  <tr><td colspan="2" align="left"><input type=hidden name="step" value="2"><input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>"><input type="submit" value="Continuer"></td></tr></table></form><?php
  break;

  # Verify data
  case '2':
  require('preload/unittype.php');
  if ($saveme == 1)
  {
  $arrivaldate = $_POST['arrivalyear'] . '-' .  $_POST['arrivalmonth'] . '-' . $_POST['arrivalday'];
  $query = 'update shipment set noimportvalue=?,fenix_transmodeid=?,total_invoiced=?,reg_sta="' . $_POST['reg_sta'] . '"
  ,sofixprocedure="' . $_POST['sofixprocedure'] . '",sofixrf="' . $_POST['sofixrf'] . '",sofixdf="' . $_POST['sofixdf'] . '"
  ,origincountryid="' . $_POST['origincountryid'] . '",fromcountryid="' . $_POST['fromcountryid'] . '",tauxtt="' . $_POST['tauxtt'] . '"
  ,nocom="' . $_POST['nocom'] . '",nopro="' . $_POST['nopro'] . '",noinv="' . $_POST['noinv'] . '"
  ,shipmentcomment2="' . $_POST['shipmentcomment2'] . '",shipmentcomment="' . $_POST['shipmentcomment'] . '"
  ,numberofcontainers40="' . ($_POST['numberofcontainers40']+0) . '",numberofcontainers40cold="' . ($_POST['numberofcontainers40cold']+0) . '"
  ,numberofcontainers20="' . ($_POST['numberofcontainers20']+0) . '",numberofcontainers20cold="' . ($_POST['numberofcontainers20cold']+0) . '"
  ,numberofcontainers20dooropen="' . ($_POST['numberofcontainers20dooropen']+0) . '"
  ,unloadingcost="' . ($_POST['unloadingcost']+0) . '",freightcostcurrencyid="' . ($_POST['freightcostcurrencyid']+0) . '"
  ,insurancecurrencyid="' . ($_POST['insurancecurrencyid']+0) . '",weight="' . ($_POST['grossweight']+0) . '"
  ,currencyid="' . $_POST['currencyid'] . '",vesselid="' . $_POST['vesselid'] . '",arrivaldate="' . $arrivaldate . '"
  ,incotermid="' . $_POST['incotermid'] . '",freightcost="' . ($_POST['freightcost']+0) . '"
  ,insurance="' . ($_POST['insurance']+0) . '" where shipmentid="' . $_POST['shipmentid'] . '"';
  $query_prm = array(($_POST['noimportvalue']+0),$_POST['fenix_transmodeid'],$_POST['total_invoiced']);
  require('inc/doquery.php');
  $query = 'delete from purchase where shipmentid="' . $_POST['shipmentid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $shipmentlines; $i++)
  {
    if ($_POST['productid' . $i] != "")
    {
      $query = 'select numberperunit,salesprice,unittypeid,transportpricepercarton,stickerpricepercarton,palletpricepercarton
      from product where productid=?';
      $query_prm = array($_POST['productid' . $i]);
      require('inc/doquery.php');
      $row3 = $query_result[0];
      $amount_temp = $_POST['amount' . $i] * $row3['numberperunit'];
      $amountcartons_temp = $_POST['amountcartons' . $i]+0;
      $case_j = $_POST['case_j' . $i];
      $batchname[$i] = $_POST['batchname' . $i];
      $supplierbatchname[$i] = $_POST['supplierbatchname' . $i];
      $supplier_pallet_barcode[$i] = $_POST['supplier_pallet_barcode' . $i];
      $datename = 'useby'.$i; $dp_allowempty = 1;
      require('inc/datepickerresult.php');
      #$useby[$i] = $datepicker_date;
      $useby[$i] = $$datename;
      $req[$i] = $_POST['fenix_req_procedure' . $i . 'id'];
      $prev[$i] = $_POST['fenix_prev_procedure' . $i . 'id'];
      $x = 0; # ?
      if ($_POST['readprices'] == 1)
      {
        $_POST['purchaseprice' . $i] = ($_POST['amount' . $i] * $row3['salesprice'] * $unittype_dmpA[$row3['unittypeid']])+0;
      }
      if (!isset($row3['palletpricepercarton']) || $row3['palletpricepercarton'] == NULL) { $row3['palletpricepercarton'] = 0; }
      $query = 'insert into purchase (fenix_req_procedureid,fenix_prev_procedureid,supplier_pallet_barcode,useby,batchname
      ,supplierbatchname,case_j,shipmentid,productid,amount,amountcartons,purchaseprice,
      p_palletpricepercarton,p_transportpricepercarton,p_stickerpricepercarton)
      values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'; # TODO p_palletpricepercarton should not be set here, but in finalize
      $query_prm = array($req[$i],$prev[$i],$supplier_pallet_barcode[$i],$useby[$i],$batchname[$i],$supplierbatchname[$i],$case_j
      ,$_POST['shipmentid'],$_POST['productid' . $i],$amount_temp,$amountcartons_temp
      ,($_POST['purchaseprice' . $i]+0),$row3['palletpricepercarton'],$row3['transportpricepercarton']+0,$row3['stickerpricepercarton']+0);
      require('inc/doquery.php');
    }
  }
  echo "<p>Commande " . $_POST['shipmentid'] . " modifié.</p>";
  }

  if ($newshipment == 1)
  {
    $query = 'insert into shipment (arrivaldate,shipmentstatus) values (?,?)';
    $query_prm = array($_SESSION['ds_curdate'],'Commandé');
    require ('inc/doquery.php');
    $shipmentid = $query_insert_id;
  }
  
  if ($dontreadvalues != 1)
  {
    $query = 'select noimportvalue,fenix_transmodeid,total_invoiced,reg_sta,sofixprocedure,sofixrf,sofixdf,fromcountryid,origincountryid
    ,tauxtt, nocom, nopro, noinv, shipmentcomment2, shipmentcomment, numberofcontainers20cold, numberofcontainers40cold
    , numberofcontainers20, numberofcontainers40, unloadingcost, shipmentstatus, freightcost, freightcostcurrencyid, insurance
    , insurancecurrencyid, weight, vesselid, arrivaldate, currencyid, incotermid, numberofcontainers20dooropen
    from shipment where shipmentid=?';
    $query_prm = array($shipmentid);
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      echo "Ce commande n'existe pas.";
      break;
    }
    $row = $query_result[0];
    if ($row['shipmentstatus'] == 'Fini')
    {
      echo "Ce commande a déja été finalized.";
      break;
    }
    $fenix_transmodeid = $row['fenix_transmodeid'];
    $vesselid = $row['vesselid'];
    $total_invoiced = $row['total_invoiced'];
    $arrivalday = mb_substr($row['arrivaldate'],8,2);
    $arrivalmonth = mb_substr($row['arrivaldate'],5,2);
    $arrivalyear = mb_substr($row['arrivaldate'],0,4);
    $incotermid = $row['incotermid'];
    $noimportvalue = $row['noimportvalue'];
    $currencyid = $row['currencyid'];
    $grossweight = $row['weight'];
    $insurance = $row['insurance']+0;
    $insurancecurrencyid = $row['insurancecurrencyid'];
    $freightcost = $row['freightcost']+0;
    $freightcostcurrencyid = $row['freightcostcurrencyid'];
    $unloadingcost = $row['unloadingcost']+0;
    $numberofcontainers40 = $row['numberofcontainers40'];
    $numberofcontainers20 = $row['numberofcontainers20'];
    $numberofcontainers20dooropen = $row['numberofcontainers20dooropen'];
    $numberofcontainers40cold = $row['numberofcontainers40cold'];
    $numberofcontainers20cold = $row['numberofcontainers20cold'];
    $shipmentcomment = $row['shipmentcomment'];
    $shipmentcomment2 = $row['shipmentcomment2'];
    $noinv = $row['noinv'];
    $nopro = $row['nopro'];
    $nocom = $row['nocom'];
    $tauxtt = $row['tauxtt'];
    $origincountryid = $row['origincountryid'];
    $fromcountryid = $row['fromcountryid'];
    $sofixdf = $row['sofixdf'];
    $sofixrf = $row['sofixrf'];
    $sofixprocedure = $row['sofixprocedure'];
    $reg_sta = $row['reg_sta'];
  }
  else
  {
    $fenix_transmodeid = $_POST['fenix_transmodeid'];
    $total_invoiced = $_POST['total_invoiced'];
    $vesselid = $_POST['vesselid'];
    $arrivalday = $_POST['arrivalday'];
    $arrivalmonth = $_POST['arrivalmonth'];
    $arrivalyear = $_POST['arrivalyear'];
    $incotermid = $_POST['incotermid'];
    $noimportvalue = $_POST['noimportvalue'];
    $currencyid = $_POST['currencyid'];
    $grossweight = $_POST['grossweight'];
    $insurance = $_POST['insurance'];
    $insurancecurrencyid = $_POST['insurancecurrencyid'];
    $freightcost = $_POST['freightcost'];
    $freightcostcurrencyid = $_POST['freightcostcurrencyid'];
    $unloadingcost = $_POST['unloadingcost'];
    $numberofcontainers40 = $_POST['numberofcontainers40'];
    $numberofcontainers20 = $_POST['numberofcontainers20'];
    $numberofcontainers20dooropen = $_POST['numberofcontainers20dooropen'];
    $numberofcontainers40cold = $_POST['numberofcontainers40cold'];
    $numberofcontainers20cold = $_POST['numberofcontainers20cold'];
    $shipmentcomment = $_POST['shipmentcomment'];
    $shipmentcomment2 = $_POST['shipmentcomment2'];
    $noinv = $_POST['noinv'];
    $nopro = $_POST['nopro'];
    $nocom = $_POST['nocom'];
    $tauxtt = $_POST['tauxtt'];
    $origincountryid = $_POST['origincountryid'];
    $fromcountryid = $_POST['fromcountryid'];
    $sofixdf = $_POST['sofixdf'];
    $sofixrf = $_POST['sofixrf'];
    $sofixprocedure = $_POST['sofixprocedure'];
    $reg_sta = $_POST['reg_sta'];
  }
  
  echo '<h2>Commande ' . $shipmentid . '</h2>';
  ?>
  <form method="post" action="purchase.php">
  <table>
  
  <tr><td>Transport:</td><td><select name="vesselid"><?php
  $query = 'select vesselid,vesselname from vessel where deleted=0 order by vesselname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['vesselid'] == $vesselid) { echo '<option value="' . $row['vesselid'] . '" SELECTED>' . $row['vesselname'] . '</option>'; }
    else { echo '<option value="' . $row['vesselid'] . '">' . $row['vesselname'] . '</option>'; }
  }
  ?></select> &nbsp; TRANSMODE (Fenix) : <?php
  
  $dp_itemname = 'fenix_transmode'; $dp_selectedid = $fenix_transmodeid; $dp_noblank = 1; $dp_notable = 1;
  require('inc/selectitem.php');
  
  ?>
  
  <tr><td>Date d'arrivage:</td><td><select name="arrivalday"><?php
  for ($i=1; $i <= 31; $i++)
  {
    if ($i == $arrivalday) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="arrivalmonth"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $arrivalmonth) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="arrivalyear"><?php
  for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  {
    if ($i == $arrivalyear) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  
  <tr><td>Incoterm:</td><td><select name="incotermid"><?php
  $query = 'select incotermid,incotermname,incotermdescription from incoterm order by incotermname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row2 = $query_result[$i];
    if ($row2['incotermid'] == $incotermid) { echo '<option value="' . $row2['incotermid'] . '" SELECTED>' . $row2['incotermname'] . ' ' . $row2['incotermdescription'] . '</option>'; }
    else { echo '<option value="' . $row2['incotermid'] . '">' . $row2['incotermname'] . ' ' . $row2['incotermdescription'] . '</option>'; }
  }
  echo '</select> &nbsp; &nbsp; DHL, Ne pas afficher dans Décl import en valeur: <input type=checkbox name=noimportvalue value=1';
  if ($noimportvalue) { echo ' checked'; }
  echo '>';
  
  echo '<tr><td>Currency:</td><td><select name="currencyid">';
  $query = 'select currencyid,currencyacronym from currency where deleted=0 order by currencyacronym';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['currencyid'] == $currencyid) { echo '<option value="' . $row['currencyid'] . '" SELECTED>' . $row['currencyacronym'] . '</option>'; }
    else { echo '<option value="' . $row['currencyid'] . '">' . $row['currencyacronym'] . '</option>'; }
  }
  echo '</select> &nbsp; Total facturé: <input type=text STYLE="text-align:right" name=total_invoiced value="' . $total_invoiced . '" size=20>';
  
  echo '<tr><td>Pays&nbsp;provenance:</td><td><select name="fromcountryid">';
  $query = 'select countryid,countryname from country order by rank, countryname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['countryid'] == $fromcountryid) { echo '<option value="' . $row['countryid'] . '" SELECTED>' . $row['countryname'] . '</option>'; }
    else { echo '<option value="' . $row['countryid'] . '">' . $row['countryname'] . '</option>'; }
  }
  echo '</select></td></tr>';
  
  echo '<tr><td>Pays&nbsp;origine:</td><td><select name="origincountryid">';
  $query = 'select countryid,countryname from country order by rank,countryname';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    if ($row['countryid'] == $origincountryid) { echo '<option value="' . $row['countryid'] . '" SELECTED>' . $row['countryname'] . '</option>'; }
    else { echo '<option value="' . $row['countryid'] . '">' . $row['countryname'] . '</option>'; }
  }
  echo '</select></td></tr>';
  
  echo '<tr><td>Douane frontière (SOFIX):</td><td><select name="sofixdf">';
  echo '<option value="PPT"';
  if ($sofixdf == 'PPT') { echo ' selected'; }
  echo '>Papeete - Port</option>';
  echo '<option value="FAA"';
  if ($sofixdf == 'FAA') { echo ' selected'; }
  echo '>Faa Fret</option>';
  echo '<option value="CEN"';
  if ($sofixdf == 'CEN') { echo ' selected'; }
  echo '>Central - Tahiti</option>';
  echo '<option value="CMP"';
  if ($sofixdf == 'CMP') { echo ' selected'; }
  echo '>Messageries Postales</option>';
  echo '<option value="MAE"';
  if ($sofixdf == 'MAE') { echo ' selected'; }
  echo '>Maere</option>';
  echo '<option value="777"';
  if ($sofixdf == '777') { echo ' selected'; }
  echo '>Douane version 0</option>';
  echo '</select></td></tr>';
  
  echo '<tr><td>Regime&nbsp;Statistique&nbsp;(SOFIX):</td><td><select name="reg_sta">';
  echo '<option value="I400"';
  if ($reg_sta == 'I400') { echo ' selected'; }
  echo '>I400</option>';
  echo '<option value="IM40"';
  if ($reg_sta == 'IM40') { echo ' selected'; }
  echo '>IM40</option>';
  echo '</select></td></tr>';
  
  echo '<tr><td>Procedure&nbsp;(SOFIX):</td><td><select name="sofixprocedure">';
  echo '<option value="N"';
  if ($sofixprocedure == 'N') { echo ' selected'; }
  echo '>N</option>';
  echo '<option value="D"';
  if ($sofixprocedure == 'D') { echo ' selected'; }
  echo '>D</option>';
  echo '<option value="P"';
  if ($sofixprocedure == 'P') { echo ' selected'; }
  echo '>P</option>';
  echo '</select></td></tr>';
  if ($sofixrf == '') { $sofixrf = '01'; }
  echo '<tr><td>Regime&nbsp;Financier&nbsp;(SOFIX):</td><td><input type="text" STYLE="text-align:right" name="sofixrf" value="' . $sofixrf . '" size=5> examples: "01" ou "02"</td></tr>';
  
  echo '<tr><td>Gross&nbsp;weight:</td><td><input type="text" STYLE="text-align:right" name="grossweight" value="' . $grossweight . '" size=10> kg</td></tr>';

  if ($incotermid != 3)
  {
    echo '<tr><td>Assurance:</td><td><input type="text" STYLE="text-align:right" name="insurance" value="' . $insurance . '" size=10> <select name="insurancecurrencyid">';
    $query = 'select currencyid,currencyacronym from currency where deleted=0 order by currencyacronym';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['currencyid'] == $insurancecurrencyid) { echo '<option value="' . $row2['currencyid'] . '" SELECTED>' . $row2['currencyacronym'] . '</option>'; }
      else { echo '<option value="' . $row2['currencyid'] . '">' . $row2['currencyacronym'] . '</option>'; }
    }
    echo '</select></td></tr>';

    echo '<tr><td>Freight&nbsp;cost:</td><td><input type="text" STYLE="text-align:right" value="' . $freightcost . '" name="freightcost" size=10> <select name="freightcostcurrencyid">';
    $query = 'select currencyid,currencyacronym from currency where deleted=0 order by currencyacronym';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['currencyid'] == $freightcostcurrencyid) { echo '<option value="' . $row2['currencyid'] . '" SELECTED>' . $row2['currencyacronym'] . '</option>'; }
      else { echo '<option value="' . $row2['currencyid'] . '">' . $row2['currencyacronym'] . '</option>'; }
    }
    echo '</select></td></tr>';
  }
  
  echo '<tr><td>Unloading cost:</td><td><input type="text" STYLE="text-align:right" name="unloadingcost" value="' . $unloadingcost . '" size=10> XPF</td></tr>';
  echo '<tr><td>Combien de container 40\' Dry:</td><td><input type="text" STYLE="text-align:right" name="numberofcontainers40" value="' . $numberofcontainers40 . '" size=5></td></tr>';
  echo '<tr><td>Combien de container 20\' Dry:</td><td><input type="text" STYLE="text-align:right" name="numberofcontainers20" value="' . $numberofcontainers20 . '" size=5></td></tr>';
  echo '<tr><td>Combien de container 40\' Reefer:</td><td><input type="text" STYLE="text-align:right" name="numberofcontainers40cold" value="' . $numberofcontainers40cold . '" size=5></td></tr>';
  echo '<tr><td>Combien de container 20\' Reefer:</td><td><input type="text" STYLE="text-align:right" name="numberofcontainers20cold" value="' . $numberofcontainers20cold . '" size=5></td></tr>';
  echo '<tr><td>Combien de container 20\' Door Open:</td><td><input type="text" STYLE="text-align:right" name="numberofcontainers20dooropen" value="' . $numberofcontainers20dooropen . '" size=5></td></tr>';
  echo '<tr><td>Conteneur Numéro:</td><td><input type="text" STYLE="text-align:right" name="shipmentcomment" value="' . $shipmentcomment . '" size=50> (séparer par éspace)';
  echo '<tr><td>Description:</td><td><input type="text" STYLE="text-align:right" name="shipmentcomment2" value="' . $shipmentcomment2 . '" size=50></td></tr>';
  echo '<tr><td>No Facture:</td><td><input type="text" STYLE="text-align:right" name="noinv" value="' . $noinv . '" size=50></td></tr>';
  echo '<tr><td>No Proforma:</td><td><input type="text" STYLE="text-align:right" name="nopro" value="' . $nopro . '" size=50></td></tr>';
  echo '<tr><td>No Commande:</td><td><input type="text" STYLE="text-align:right" name="nocom" value="' . $nocom . '" size=50></td></tr>';
  echo '<tr><td>Taux de T/T:</td><td><input type="text" STYLE="text-align:right" name="tauxtt" value="' . $tauxtt . '" size=50></td></tr>';
  
  if ($dontreadvalues != 1)
  {
    $query = 'select fenix_req_procedureid,fenix_prev_procedureid,supplier_pallet_barcode,useby,batchname,supplierbatchname,case_j,amount,amountcartons,purchase.productid,purchaseprice,numberperunit from purchase,product where purchase.productid=product.productid and shipmentid="' . $shipmentid . '" order by purchaseid';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > $shipmentlines) { $shipmentlines = $num_results; }
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      $batchname[$i] = $row['batchname'];
      $supplierbatchname[$i] = $row['supplierbatchname'];
      $supplier_pallet_barcode[$i] = $row['supplier_pallet_barcode'];
      $productid[$i] = $row['productid'];
      $amount[$i] = $row['amount']/$row['numberperunit'];
      $amountcartons[$i] = $row['amountcartons'];
      $purchaseprice[$i] = $row['purchaseprice']+0;
      $case_j[$i] = $row['case_j'];
      $useby[$i] = $row['useby'];
      $req[$i] = $row['fenix_req_procedureid'];
      $prev[$i] = $row['fenix_prev_procedureid'];
    }
  }
  if ($shipmentlines < 5) { $shipmentlines = 5; }
  echo '<tr><td>Lignes:</td><td><input type="number" STYLE="text-align:right" name="shipmentlines" size=4 value="' . $shipmentlines . '"></td></tr>';
  echo '<tr><td>Reprendre nos prix:</td><td><input type="checkbox" name="readprices" value=1></td></tr>';
  ?></table><br>
  
  <table class="detailinput"><tr><td></td><td valign=top><b>Conteneur</b><td valign=top><b>Batch</b></td><td valign=top><b>Pallet ID</b></td><td valign=top><b>No Produit</b></td><td valign=top><b>Quantité</b></td><td valign=top><b>Q. Fourn. (facult.)</b></td><td valign=top><b>Prix (total)</b></td><td valign=top><b>Productname</b></td><td valign=top><b>Code F.<td valign=top><b>Vendu en</b></td><td valign=top><b>Cond.</b></td><td valign=top><b>DLV<?php
  for ($i=0; $i < $shipmentlines; $i++)
  {
    if ($dontreadvalues == 1)
    {
      $batchname[$i] = $_POST['batchname' . $i];
      $supplierbatchname[$i] = $_POST['supplierbatchname' . $i];
      $supplier_pallet_barcode[$i] = $_POST['supplier_pallet_barcode' . $i];
      $productid[$i] = $_POST['productid' . $i];
      $amount[$i] = $_POST['amount' . $i];
      $amountcartons[$i] = $_POST['amountcartons' . $i];
      $purchaseprice[$i] = $_POST['purchaseprice' . $i];
      $case_j[$i] = $_POST['case_j' . $i];
      $req[$i] = $_POST['fenix_req_procedure' . $i . 'id'];
      $prev[$i] = $_POST['fenix_prev_procedure' . $i . 'id'];
    }

    if ($productid[$i] != "")
    {
      $query = 'select salesprice,discontinued,notforsale,productname,suppliercode,brand,numberperunit,netweightlabel,unittypename from product,unittype where product.unittypeid=unittype.unittypeid and productid=' . $productid[$i];
      $query_prm = array();
      require('inc/doquery.php');
      $row = $query_result[0];
    }
    $discont = "<font color=red>";
    if ($row['discontinued']) { $discont = $discont . "(Dis)"; }
    if ($row['discontinued'] && $row['notforsale']) { $discont = $discont . " "; }
    if ($row['notforsale']) { $discont = $discont . "(NmV)"; }
    $discont = $discont . "</font>";

    echo '<tr><td>' . ($i+1) . '.' . $discont . '</td><td><input type="text" STYLE="text-align:right" name="batchname' . $i . '" value="' . $batchname[$i] . '" size=10></td>
    <td><input type="text" STYLE="text-align:right" name="supplierbatchname' . $i . '" value="' . $supplierbatchname[$i] . '" size=10>
    <td><input type="text" STYLE="text-align:right" name="supplier_pallet_barcode' . $i . '" value="' . $supplier_pallet_barcode[$i] . '" size=10></td>
    <td><input type="text" STYLE="text-align:right" name="productid' . $i . '" value="' . $productid[$i] . '" size=5></td>
    <td><input type="text" STYLE="text-align:right" name="amount' . $i . '" value="' . $amount[$i] . '" size=5></td>
    <td><input type="text" STYLE="text-align:right" name="amountcartons' . $i . '" value="' . $amountcartons[$i] . '" size=5></td>
    <td><input type="text" STYLE="text-align:right" name="purchaseprice' . $i . '" value="' . $purchaseprice[$i] . '" size=10></td>
    <td>' . $row['productname'] . '</td><td>' . $row['suppliercode'] . '</td><td>' . $row['unittypename'] . '</td>';
    $cond = '&nbsp;';
    if ($productid[$i] != "") { $cond = $row['numberperunit'] . ' x ' . $row['netweightlabel']; }
    echo '<td>' . $cond . '</td><td>';
    $datename = 'useby'.$i;
    if ($useby[$i] != '') { $selecteddate = $useby[$i]; }
    else { $dp_setempty = 1; }
    require('inc/datepicker.php');
    
    echo '<td colspan=3> &nbsp; &nbsp; <select name="case_j' . $i . '">';#<tr>
    echo '<option value=""';
    if ($case_j[$i] == '') { echo ' selected'; }
    echo '></option>';
    echo '<option value="EU"';
    if ($case_j[$i] == 'EU') { echo ' selected'; }
    echo '>EU</option>';
    echo '<option value="SE"';
    if ($case_j[$i] == 'SE') { echo ' selected'; }
    echo '>SE (non pour fenix)</option>';
    echo '<option value="330"';
    if ($case_j[$i] == '330') { echo ' selected'; }
    echo '>Soumission doc origine</option>';
    echo '<option value="400"';
    if ($case_j[$i] == '400') { echo ' selected'; }
    echo '>Origine PTOMA</option>';
    echo '<option value="500"';
    if ($case_j[$i] == '500') { echo ' selected'; }
    echo '>Originaire de la Polynésie Française</option></select>';
    echo ' &nbsp; &nbsp; ';
    $dp_itemname = 'fenix_req_procedure'; $dp_colspan = 4; $dp_addtoid = $i; $dp_selectedid = $req[$i]; $dp_noblank = 1;
    require('inc/selectitem.php');
    echo ' &nbsp; &nbsp; ';
    if (!isset($prev[$i])) { $prev[$i] = 10; }
    $dp_itemname = 'fenix_prev_procedure'; $dp_colspan = 4; $dp_addtoid = $i; $dp_selectedid = $prev[$i]; $dp_noblank = 1;
    require('inc/selectitem.php');
    echo '<td colspan=10>';

    $row = 0;
  }
  ?></tr></table>
  <table width=100%><tr><td colspan="4" align="center">
  <input TYPE="hidden" NAME="step" value="2">
  <input type=hidden name="saveme" value=1>
  <input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>">
  <input type=hidden name="dontreadvalues" value=1>
  <input type=hidden name="shipmentid" value="<?php echo $shipmentid; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

}
?>