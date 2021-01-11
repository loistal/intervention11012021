<?php

# good luck refactoring this

$shipmentid = (int) $_POST['shipmentid'];

# just delete old lines to be sure
$query = 'delete from fenix_lines where shipmentid=?';
$query_prm = array($shipmentid);
require('inc/doquery.php');

$query = 'select unloadingcost,weight,arrivaldate,vesselname,currencyacronym,incotermname,currencyrate,noinv,shipmentcomment
from shipment,vessel,currency,incoterm
where shipment.currencyid=currency.currencyid and shipment.vesselid=vessel.vesselid and shipment.incotermid=incoterm.incotermid
and shipmentid=?';
$query_prm = array($shipmentid);
require('inc/doquery.php');
if ($num_results == 0)
{
  echo "Ce commande n'existe pas.";
  exit;
}
$row = $query_result[0];
$shipmentcomment = $row['shipmentcomment'];
$noinv = $row['noinv'];
$vesselname = $row['vesselname'];
$arrivaldate = $row['arrivaldate'];
$currencyacronym = $row['currencyacronym'];
$totalweight = $row['weight']; if ($totalweight == 0) { $totalweight = 1; };
$totalunloadingcost = $row['unloadingcost'];
$incotermname = $row['incotermname'];
$exchangerate = $row['currencyrate'];
$totalprice = 0;
$subtotalnetweight = 0;
$totalnetweight = 0;
$subtotalweight = 0;
$calctotalweight = 0;
$totalprice = 0;
$subtotalprice = 0;
$subtotalfreightcost = 0;
$subtotalinsurance = 0;
$subtotalcif = 0;
$subtotalunloadingcost = 0;
$subtotal_fenix42 = 0;
$restsihunloadingcost = 0;
$subtotalunitamount = 0;
$totalunitamount = 0;
$restcif = 0;
$restweight = 0;
$restnetweight = 0;
$restfreightcost = 0;
$restinsurance = 0;
$sihnumbercounter = 0;
$exchange_text = $currencyacronym . ': ' . $exchangerate;

$query = 'select currencyacronym,currencyrate from shipment,currency where shipment.freightcostcurrencyid=currency.currencyid and shipmentid=?';
$query_prm = array($shipmentid);
require('inc/doquery.php');
if ($num_results)
{
  $row = $query_result[0];
  $freightcostcurrencyacronym = $row['currencyacronym'];
  $freightcostexchangerate = $row['currencyrate'];
  if ($freightcostcurrencyacronym != $currencyacronym)
  {
    $exchange_text .= ' '.$freightcostcurrencyacronym . ': ' . $freightcostexchangerate;
  }
}
else
{
  $freightcostcurrencyacronym = '';
  $freightcostexchangerate = 0;
}

$query = 'select currencyacronym,currencyrate from shipment,currency where shipment.insurancecurrencyid=currency.currencyid and shipmentid=?';
$query_prm = array($shipmentid);
require('inc/doquery.php');
if ($num_results)
{
  $row = $query_result[0];
  $insurancecurrencyacronym = $row['currencyacronym'];
  $insuranceexchangerate = $row['currencyrate'];
  if ($insurancecurrencyacronym != $currencyacronym && $insurancecurrencyacronym != $freightcostcurrencyacronym)
  {
    $exchange_text .= ' '.$insurancecurrencyacronym . ': ' . $insuranceexchangerate;
  }
}
else
{
  $insurancecurrencyacronym = '';
  $insuranceexchangerate = 0;
}

$query = 'select clientname as suppliername,countryname,traderegionid from client,product,purchase,country
where client.clientid=product.supplierid and client.countryid=country.countryid and product.productid=purchase.productid
and shipmentid=?';
$query_prm = array($shipmentid);
require('inc/doquery.php');
$row = $query_result[0];
$countryname = $row['countryname'];
if ($row['traderegionid'] == 1) { $countryname = 'UE '.$countryname; }
$suppliername = d_decode($row['suppliername']);

$query = 'select freightcost,insurance from shipment where shipment.shipmentid=?';
$query_prm = array($shipmentid);
require('inc/doquery.php');
$row = $query_result[0];
$totalfreightcost = $row['freightcost'];
$totalinsurance = $row['insurance'];

$query = 'select sofixcode,purchaseid,avantage,supplierid,purchase.productid as productid,sih,weight,netweight
,amount,amountcartons,numberperunit,purchaseprice,countryname,code_suffixe,fenixcode,case_j
,fenix_req_procedureid,fenix_prev_procedureid,traderegionid,fenix42,tcp_gradient
from purchase,product,country
where purchase.productid=product.productid and product.countryid=country.countryid
and purchase.shipmentid=? order by purchaseid';
$query_prm = array($shipmentid);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=1; $i <= $num_results_main; $i++)
{
  $row = $main_result[($i - 1)];
  if ($i == 1) { $supplierid = $row['supplierid']; }
  if ($i > 1 && $supplierid != $row['supplierid']) { $suppliername = 'Plusieurs'; }
  $fenix42[$i] = $row['fenix42'];
  $tcp_gradient[$i] = $row['tcp_gradient'];
  $productid[$i] = $row['productid'];
  $purchaseid[$i] = $row['purchaseid'];
  $sih[$i] = $row['sih'] . $row['countryname'] . $row['avantage'] . $row['tcp_gradient']; # TODO test
  $displaysih[$i] = $row['sih'];
  $avantage[$i] = $row['avantage'];
  $sofixcode[$i] = $row['sofixcode'];
  $code_suffixe[$i] = $row['code_suffixe'];
  $fenixcode[$i] = $row['fenixcode'];
  $case_j[$i] = $row['case_j'];
  if ($case_j[$i] == '') # automatic EU if product from EU country
  {
    if ($row['traderegionid'] == 1) { $case_j[$i] = 'EU'; }
  }
  $sihunitamount[$i] = 0;
  $fenix_req_procedureid[$i] = $row['fenix_req_procedureid'];
  $fenix_prev_procedureid[$i] = $row['fenix_prev_procedureid'];

  # pre-defined Suffixes
  $suffix[$i] = "";
  if ($sih[$i] == "0207.14.00E") { $suffix[$i] = "NV03 "; }
  $suffix[$i] = $suffix[$i] . $row['avantage'];
  $suffix[$i] = '<span style="background:#FFFF00; font-weight:bold">' . $suffix[$i] . '</span>';
  if ($code_suffixe[$i] != '')
  { $suffix[$i] .= ' <span style="background:orange; font-weight:bold">'.$code_suffixe[$i].'</span>'; }

  $weight_temp = $row['weight']; if ($weight_temp == 0) { $weight_temp = 1; }
  $weight[$i] = (($weight_temp / $row['numberperunit']) * $row['amount']) + $restweight;
  $restweight = $weight[$i] - floor($weight[$i]);
  $weight[$i] = floor($weight[$i]);
  if ($i == $num_results_main) { $weight[$i] = round($weight[$i] + $restweight); }

  $netweight_temp = $row['netweight']; if ($netweight_temp == 0) { $netweight_temp = 1; }
  $netweight[$i] = ($netweight_temp / 1000 * $row['amount']);

  $unitamount[$i] = round($row['amount'] / $row['numberperunit']);
  if ($row['amountcartons'] > 0) { $unitamount[$i] = $row['amountcartons']; }
  $purchaseprice[$i] = $row['purchaseprice'];
  $totalprice = $totalprice + $purchaseprice[$i];
  $productcountry[$i] = $row['countryname'];
  if ($row['traderegionid'] == 1) { $productcountry[$i] = 'UE '.$productcountry[$i]; }

  $sorttableeligible[$i] = 1;
}

# create sorttable
$currentindex = 1;
$sorttable[1] = 1;
$sorttableeligible[1] = 0;
$sorttableindex = 2;
$startsort = 1;
$failsafe = 0;
while ($currentindex <= $num_results_main && $startsort <= $num_results_main)
{
  for ($i=$startsort; $i <= $num_results_main; $i++)
  {
    if ($sih[$i] == $sih[$currentindex] && $sorttableeligible[$i] == 1)
    {
      $sorttable[$sorttableindex] = $i;
      $sorttableeligible[$i] = 0;
      $sorttableindex++;
    }
  }
  while (isset($sorttableeligible[$startsort]) && $sorttableeligible[$startsort] == 0 && $startsort <= $num_results_main) { $startsort++; }
  $currentindex = $startsort;
  $sorttable[$sorttableindex] = $currentindex;
  $sorttableeligible[$currentindex] = 0;
  $sorttableindex++;
  $startsort++;
  $failsafe++; if ($failsafe > 100000) { exit; }
}

for ($y=1; $y <= $num_results_main; $y++)
{
  $i = $sorttable[$y];
  $subtotalnetweight = $subtotalnetweight + $netweight[$i];
  $subtotalweight = $subtotalweight + $weight[$i];
  $subtotalunitamount = $subtotalunitamount + $unitamount[$i];
  $subtotalprice = $subtotalprice + $purchaseprice[$i];
  if (isset($sorttable[$y+1])) { $scrap = $sorttable[$y+1]; }
  else { $scrap = -1; }
  if (!isset($sih[$scrap]) || $sih[$i] != $sih[$scrap])
  {
    $totalnetweight = $totalnetweight + $subtotalnetweight;
    $sihnetweight[$i] = $subtotalnetweight;
    $subtotalnetweight = 0;
    $calctotalweight = $calctotalweight + $subtotalweight;
    $sihweight[$i] = $subtotalweight;
    $subtotalweight = 0;
    $totalunitamount = $totalunitamount + $subtotalunitamount;
    $sihunitamount[$i] += $subtotalunitamount;
    $subtotalunitamount = 0;
    $sihprice[$i] = $subtotalprice;
    $subtotalprice = 0;
    $sihnumbercounter = $sihnumbercounter + 1;
    $sihnumber[$i] = $sihnumbercounter;
  }
}

if ($totalnetweight != 0)
{
  for ($y=1; $y <= $num_results_main; $y++)
  {
    $i = $sorttable[$y];
    $weight[$i] = round($totalweight * $netweight[$i] / $totalnetweight);
  }
}

for ($i=1; $i <= $num_results_main; $i++)
{
  $unloadingcost[$i] = ($weight[$i] / $calctotalweight) * $totalunloadingcost;

  $freightcost[$i] = (($weight[$i]*1000) / $calctotalweight) * $totalfreightcost + $restfreightcost;
  $restfreightcost = $freightcost[$i] - floor($freightcost[$i]);
  $freightcost[$i] = floor($freightcost[$i]);
  if ($freightcostcurrencyacronym == "XPF") { $freightcost[$i] = round($freightcost[$i]); }
  if ($i == $num_results_main) { $freightcost[$i] = round($freightcost[$i] + $restfreightcost); }

  $insurance[$i] = ($purchaseprice[$i] / $totalprice) * $totalinsurance + $restinsurance;
  $restinsurance = $insurance[$i] - myround($insurance[$i],2);
  $insurance[$i] = myround($insurance[$i],2);
  if ($insurancecurrencyacronym == "XPF") { $insurance[$i] = round($insurance[$i]); }
  if ($i == $num_results_main) { $insurance[$i] = myround($insurance[$i] + $restinsurance,2); }

  $cif[$i] = ($purchaseprice[$i] * $exchangerate) + ($freightcost[$i] * $freightcostexchangerate) + ($insurance[$i] * $insuranceexchangerate) + $restcif;
  $restcif = $cif[$i] - floor($cif[$i]);
  $cif[$i] = floor($cif[$i]);
  if ($i == $num_results_main) { $cif[$i] = round($cif[$i] + $restcif); }
}
$totalcif = ($totalprice * $exchangerate) + ($totalfreightcost * $freightcostexchangerate) + ($totalinsurance * $insuranceexchangerate);


echo '<TITLE>Note de Detail no. ' . $shipmentid . '</TITLE>';
?></HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>
<form><table>
<tr><td width=300 align=left><?php echo $_SESSION['ds_customname']; ?></td><td colspan=2></td><td width=400 align=center><h2>Note de Detail</h2></td><td colspan=4></td><td width=300 align=right>Edité le: <?php echo date("d-M-Y"); ?></td></tr>
<tr><td align=left>Dossier no <?php echo $shipmentid; ?></td><td colspan=2></td>
  <td align=center><?php echo $exchange_text; ?>
  <td colspan=4><td align=right>Poids brut: <?php echo $totalweight; ?> Kgs</td></tr>
<tr><td align=left>Fournisseur: <?php echo $suppliername; ?></td><td colspan=2></td><td align=center>Pays de chargement: <?php echo $countryname; ?></td><td colspan=4></td><td align=right>Poids net: <?php echo $totalnetweight; ?> Kgs</td></tr>
<tr><td align=left>Vessel: <?php echo $vesselname; ?></td><td colspan=2></td><td align=center>Date d'arrivage: <?php echo date("d-M-Y",strtotime($arrivaldate)); ?></td><td colspan=4></td><td align=right>Incoterm: <?php echo $incotermname; ?></td></tr>
<tr><td align=left>N<sup>o</sup> Facture: <?php echo $noinv; ?>
  <td colspan=2><td colspan=6>Conteneur(s): <?php echo $shipmentcomment; ?>
</table>

<table class="report">
<tr><td><b>Article</b></td><td><b>Avantage/Suffixe</b></td><td><b>Origine<td><b>Gradient<td><b># colis</b></td><td><b>Poids net</b></td><td><b>Poids brut</b></td>
<td><b>Montant (<?php echo $currencyacronym; ?>)</b></td><?php
if ($incotermname != "CAF" && $incotermname != "CFR")
{
  echo '<td><b>Freight ';
  if (1==0 || $freightcostcurrencyacronym != $currencyacronym) { echo '(' . $freightcostcurrencyacronym . ')'; }
  echo '</b></td>';
}
if ($incotermname != "CAF")
{
  echo '<td><b>Assurance ';
  if (1==0 || $insurancecurrencyacronym != $currencyacronym) { echo '(' . $insurancecurrencyacronym . ')'; }
  echo '</b></td>';
}
?><td><b>CIF (XPF)</b></td><td><b>Frais S/ TVA</b></td></tr>
<?php
$mysihweight = 0; $checkunloadingcost = 0; $checkweight = 0;

#
# find rounding errors
#
$addtotalweight = 0;
$addtotalnetweight = 0;
$addtotalfreightcost = 0;
$addtotalinsurance = 0;
$addtotalcif = 0;
for ($y=1; $y <= $num_results_main; $y++)
{
  $i = $sorttable[$y];
  $addtotalweight = $addtotalweight + $weight[$i];
  $addtotalnetweight = $addtotalnetweight + $netweight[$i];
  $addtotalfreightcost = $addtotalfreightcost + $freightcost[$i];
  $addtotalinsurance = $addtotalinsurance + $insurance[$i];
  $addtotalcif = $addtotalcif + $cif[$i];
}

#
# distribute rounding errors
#
# too high
if ($addtotalweight > $totalweight)
{
  $difference = $addtotalweight - $totalweight;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $weight[$x] = $weight[$x] - 1;
  }
}
if ($addtotalnetweight > $totalnetweight)
{
  $difference = $addtotalnetweight - $totalnetweight;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $netweight[$x] = $netweight[$x] - 1;
  }
}
if ($addtotalfreightcost > $totalfreightcost && $freightcostcurrencyacronym == "XPF")
{
  $difference = $addtotalfreightcost - $totalfreightcost;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $freightcost[$x] = $freightcost[$x] - 1;
  }
}
if ($addtotalinsurance > $totalinsurance && $insurancecurrencyacronym == "XPF")
{
  $difference = $addtotalinsurance - $totalinsurance;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $insurance[$x] = $insurance[$x] - 1;
  }
}
if ($addtotalcif > $totalcif)
{
  $difference = $addtotalcif - $totalcif;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $cif[$x] = $cif[$x] - 1;
  }
}
# too low
if ($addtotalweight < $totalweight)
{
  $difference = $totalweight - $addtotalweight;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $weight[$x] = $weight[$x] + 1;
  }
}
if ($addtotalnetweight < $totalnetweight)
{
  $difference = $totalnetweight - $addtotalnetweight;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $netweight[$x] = $netweight[$x] + 1;
  }
}
if ($addtotalfreightcost < $totalfreightcost && $freightcostcurrencyacronym == "XPF")
{
  $difference = $totalfreightcost - $addtotalfreightcost;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $freightcost[$x] = $freightcost[$x] + 1;
  }
}
if ($addtotalinsurance < $totalinsurance && $insurancecurrencyacronym == "XPF")
{
  $difference = $totalinsurance - $addtotalinsurance;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $insurance[$x] = $insurance[$x] + 1;
  }
}
if ($addtotalcif < $totalcif)
{
  $difference = $totalcif - $addtotalcif;
  for ($i=1; $i <= $difference; $i++)
  {
    $x = rand(1,$num_results_main);
    $cif[$x] = $cif[$x] + 1;
  }
}
  
$notedetailnum_results_main = $num_results_main;
for ($y=1; $y <= $num_results_main; $y++)
{
  $i = $sorttable[$y];
# calc subtotals
  $subtotal_fenix42 += $fenix42[$i];
  $subtotalunloadingcost = $subtotalunloadingcost + $unloadingcost[$i];
  $subtotalfreightcost = $subtotalfreightcost + $freightcost[$i];
  $subtotalinsurance = $subtotalinsurance + $insurance[$i];
  $subtotalcif = $subtotalcif + $cif[$i];
#
  echo '<tr><td align=right></td><td align=right>' . $suffix[$i] . '</td><td align=right>' . $productcountry[$i] . '</td>
  <td align=center>'; if ($tcp_gradient[$i] > 0) { echo $tcp_gradient[$i]; }
  echo '<td align=right>' . $unitamount[$i] . '</td><td align=right>' . $netweight[$i] . '</td><td align=right>' . $weight[$i] . '</td>
  <td align=right>' . myfix($purchaseprice[$i],2) . '</td>';
  if ($incotermname != "CAF" && $incotermname != "CFR" && $freightcostcurrencyacronym != "XPF")
  { echo '<td align=right>' . myfix($freightcost[$i],2) . '</td>'; }
  if ($incotermname != "CAF" && $incotermname != "CFR" && $freightcostcurrencyacronym == "XPF")
  { echo '<td align=right>' . myfix($freightcost[$i]) . '</td>'; }
  if ($incotermname != "CAF" && $insurancecurrencyacronym != "XPF") { echo '<td align=right>' . myfix($insurance[$i],2) . '</td>'; }
  if ($incotermname != "CAF" && $insurancecurrencyacronym == "XPF") { echo '<td align=right>' . myfix($insurance[$i]) . '</td>'; }
  echo '<td align=right>' . myfix($cif[$i]) . '</td><td></td></tr>';
  $testone = $sorttable[$y];
  if (isset($sorttable[$y+1])) { $testtwo = $sorttable[$y+1]; }
  else { $testtwo = -1; }
  $mysihweight = $mysihweight + $weight[$i];
  if (!isset($sih[$testtwo])) { $sih[$testtwo] = -1; }
  if ($sih[$testone] != $sih[$testtwo])
  {
    # calc subtotals
    $sihunloadingcost[$i] = $subtotalunloadingcost + $restsihunloadingcost;
    $restsihunloadingcost = $sihunloadingcost[$i] - floor($sihunloadingcost[$i]);
    $sihunloadingcost[$i] = floor($sihunloadingcost[$i]);
    if ($y == $num_results_main) { $sihunloadingcost[$i] = round($sihunloadingcost[$i] + $restsihunloadingcost); }
    $subtotalunloadingcost = 0;

    $sihfreightcost[$i] = $subtotalfreightcost;
    $subtotalfreightcost = 0;

    $sihinsurance[$i] = $subtotalinsurance;
    $subtotalinsurance = 0;

    $sihcif[$i] = $subtotalcif;
    $subtotalcif = 0;

    $mysihunloadingcost = $totalunloadingcost * $mysihweight / $totalweight;
    
    ############### lastlinefix
    if ($y == $num_results_main)
    {
      $mysihunloadingcost = myround($totalunloadingcost) - $checkunloadingcost;
      $mysihweight = $totalweight - $checkweight;
    }
    else 
    {
      $checkunloadingcost = $checkunloadingcost + myround($mysihunloadingcost);
      $checkweight = $checkweight + $mysihweight;
    }
    ###############
    
    # 2019 08 02 HERE TODO save each subtotal line to fenix_lines    
    $query = 'select fenix_linesid from fenix_lines where shipmentid=? and linenr=?';
    $query_prm = array($shipmentid,$i);
    require('inc/doquery.php');
    if ($num_results) { $fenix_linesid = $query_result[0]['fenix_linesid']; }
    else
    {
      $query = 'insert into fenix_lines (shipmentid,linenr) values (?,?)';
      $query_prm = array($shipmentid,$i);
      require('inc/doquery.php');
      $fenix_linesid = $query_insert_id;
    }
    $query = 'update fenix_lines
    set sih=?,avantage=?,code_suffixe=?,fenixcode=?,case_j=?,net_mass=?,gross_mass=?
    ,fenix_req_procedureid=?,fenix_prev_procedureid=?
    ,b42_item_price=?,b44_declared_units=?,fenix42=?
    where shipmentid=? and linenr=?';
    $query_prm = array($displaysih[$i],$avantage[$i],$code_suffixe[$i],$fenixcode[$i],$case_j[$i],$sihnetweight[$i],$mysihweight
    ,$fenix_req_procedureid[$i],$fenix_prev_procedureid[$i]
    ,$sihprice[$i],$sihunitamount[$i],$subtotal_fenix42
    ,$shipmentid,$i);
    require('inc/doquery.php');
    
    echo '<tr style="background-color: '.$_SESSION['ds_formcolor'].';">
    <td align=right><b>' . $sihnumber[$i] . ') <i>' . $displaysih[$i] . '</i></b>
    <td align=right></td><td align=right></td><td><td align=right><b>' . $sihunitamount[$i] . '</td>
    <td align=right><b>' . $sihnetweight[$i] . '</td><td align=right><b>' . $mysihweight . '</td>
    <td align=right><b>' . myfix($sihprice[$i],2) . '</td>';
    if ($incotermname != "CAF" && $incotermname != "CFR" && $freightcostcurrencyacronym != "XPF")
    { echo '<td align=right><b>' . myfix($sihfreightcost[$i],2) . '</td>'; }
    if ($incotermname != "CAF" && $incotermname != "CFR" && $freightcostcurrencyacronym == "XPF")
    { echo '<td align=right><b>' . myfix($sihfreightcost[$i]) . '</td>'; }
    if ($incotermname != "CAF" && $insurancecurrencyacronym != "XPF") { echo '<td align=right><b>' . myfix($sihinsurance[$i],2) . '</td>'; }
    if ($incotermname != "CAF" && $insurancecurrencyacronym == "XPF") { echo '<td align=right><b>' . myfix($sihinsurance[$i]) . '</td>'; }
    echo '<td align=right><b>' . myfix($sihcif[$i]) . '</td><td align=right><b>' . myround($mysihunloadingcost) . '</td></tr>';
    $mysihweight = 0;
    $subtotal_fenix42 = 0;
  }
}
echo '<tr><td align=right><center><b>Totaux</b></center></td><td align=right></td><td align=right></td><td>
<td align=right><b>' . $totalunitamount . '</td><td align=right><b>' . ($totalnetweight) . '</td>
<td align=right><b>' . $totalweight . '</td><td align=right><b>' . myfix($totalprice,2) . '</td>';
if ($incotermname != "CAF" && $incotermname != "CFR" && $freightcostcurrencyacronym != "XPF")
{ echo '<td align=right><b>' . myfix($totalfreightcost,2) . '</td>'; }
if ($incotermname != "CAF" && $incotermname != "CFR" && $freightcostcurrencyacronym == "XPF")
{ echo '<td align=right><b>' . myfix($totalfreightcost) . '</td>'; }
if ($incotermname != "CAF" && $insurancecurrencyacronym != "XPF") { echo '<td align=right><b>' . myfix($totalinsurance,2) . '</td>'; }
if ($incotermname != "CAF" && $insurancecurrencyacronym == "XPF") { echo '<td align=right><b>' . myfix($totalinsurance) . '</td>'; }
echo '<td align=right><b>' . myfix($totalcif) . '</td><td align=right><b>' . myround($totalunloadingcost) . '</td></tr>';
?></table>