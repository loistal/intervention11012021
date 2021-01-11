<?php

# 2016 10 04 overdue for refactor

require('preload/unittype.php');

$shipmentid = (int) $_POST['shipmentid'];

echo '<TITLE>Calcul de Prix dossier ' . $_POST['shipmentid'] . '</TITLE>';
echo '</HEAD><BODY text=#000000 vLink=#003399 aLink=#ff9933 link=#003399 bgColor=#ffffff>';
echo '<h1>Calcul de Prix dossier ' . $_POST['shipmentid'] . '</h1>';
$query = 'select shipmentcomment,weight,freightcost,sanitaryfees,coldstorage,unloadingcost,insurance,vesselname,arrivaldate,currencyacronym,incotermname,exchangerate,insuranceexchangerate,freightcostexchangerate
from shipment,vessel,currency,incoterm where shipment.incotermid=incoterm.incotermid and shipment.currencyid=currency.currencyid and shipment.vesselid=vessel.vesselid and shipmentid=?';
$query_prm = array($_POST['shipmentid']);
require('inc/doquery.php');
$row = $query_result[0];
if ($row['shipmentcomment'] != "") { echo '<p>Libellé:' . $row['shipmentcomment'] . '</p>'; }
$weight = $row['weight'];
$totalfreightcost = $row['freightcost'] * $row['freightcostexchangerate'];
$totalsanitaryfees = $row['sanitaryfees'];
$totalcoldstorage = $row['coldstorage'];
$totalunloadingcost = $row['unloadingcost'];
$totalinsurance = $row['insurance'] * $row['insuranceexchangerate'];

$vesselname = $row['vesselname'];
$arrivaldate = $row['arrivaldate'];
$currencyname = $row['currencyacronym'];
$incotermname = $row['incotermname'];
$exchangerate = $row['exchangerate'];
$totalweight = 0;
$totalprice = 0;
$totalcartons = 0;
$totalcaf = 0;
$totalmytransit = 0;
$totaltransitcost = 0;
$totalstickercost = 0;
$totalpalletpricepercarton = 0;
$totalsf = 0;
$totalcs = 0;
$totalpf = 0;
$totalpfm = 0;
$totalpricexpf = 0;
$totalamount_kg = 0;
$query = 'select palletpricepercarton,transportpricepercarton,stickerpricepercarton,
p_palletpricepercarton,p_transportpricepercarton,p_stickerpricepercarton,
ti,hi,purchaseid,margin,margintype,purchase.productid,productname,amount,amountcartons,
purchaseprice,numberperunit,netweightlabel,netweight,producttypename,unittypeid,sih,countryid,avantage,tcp_gradient
from purchase,product,producttype
where product.producttypeid=producttype.producttypeid and purchase.productid=product.productid and shipmentid=? order by purchaseid';
$query_prm = array($_POST['shipmentid']);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];#echo '<br>',$row['transportpricepercarton'],' ',$row['p_transportpricepercarton'];
  if ($row['p_transportpricepercarton'] != 0) { $row['transportpricepercarton'] = $row['p_transportpricepercarton']; }
  if ($row['p_stickerpricepercarton'] != 0) { $row['stickerpricepercarton'] = $row['p_stickerpricepercarton']; }
  if ($row['p_palletpricepercarton'] != 0) { $row['palletpricepercarton'] = $row['p_palletpricepercarton']; }
  $purchaseid[$i] = $row['purchaseid'];
  $dmp[$i] = $unittype_dmpA[$row['unittypeid']];
  $amount[$i] = $row['amount'];
  $amountcartons[$i] = $row['amount'] / $row['numberperunit']; $companycartons[$i] = $amountcartons[$i];
  if ($row['amountcartons'] > 0) { $amountcartons[$i] = $row['amountcartons']; }
  $totalcartons = $totalcartons + ($amountcartons[$i]);
  $transitcost[$i] = $amountcartons[$i] * $row['transportpricepercarton'];
  $stickercost[$i] = $amountcartons[$i] * $row['stickerpricepercarton'];
  
  $palletpricepercarton[$i] = 0;
  $ti[$i] = $row['ti'];
  $hi[$i] = $row['hi'];
  if ($ti[$i] > 0 && $hi[$i] > 0)
  {
    $palletpricepercarton[$i] = $row['palletpricepercarton'] * ceil($amountcartons[$i] / ($ti[$i] * $hi[$i]));
  }
  
  $purchaseprice[$i] = $row['purchaseprice'];
  $purchasepricexpf[$i] = $purchaseprice[$i] * $exchangerate;
  $productid[$i] = $row['productid'];
  $productname[$i] = d_decode($row['productname']);
  $numberperunit[$i] = $row['numberperunit'];
  $packaging[$i] = $row['netweightlabel']; if ($row['numberperunit'] > 1) { $packaging[$i] = $row['numberperunit'] . ' x ' . $packaging[$i]; }
  if ($unittypeA[$row['unittypeid']] == 'KG' && $numberperunit[$i] == 1) { $soldbykilo[$i] = 1; }
  if ($soldbykilo[$i] == 1) { $totalamount_kg += $amount[$i]; }
  $productweight[$i] = ($row['netweight'] * $row['amount']) / 1000;
#echo '<br>productid= ' .  $productid[$i];
#echo '<br>weight= ' . $productweight[$i] . ' = ' . $row['netweight'] . ' * ' . $row['amount'];
  $totalweight = $totalweight + $productweight[$i];
  $totalprice = $totalprice + $purchaseprice[$i];
  $totalpricexpf = $totalpricexpf + $purchasepricexpf[$i];
  $producttype[$i] = $row['producttypename'];
  $margin[$i] = $row['margin'];
  # 2013 02 18
  /*
  if ($producttype[$i] == "PPN" || $producttype[$i] == "PAO") { $margintype[$i] = "XPF"; }
  else { $margintype[$i] = "%"; }
  */
  $margintype[$i] = $row['margintype'];
  $kladdamount = $amount[$i] * $dmp[$i];
  
  # 2016 10 04 get portfees by purchaseid
  /*
  $query = 'select portfees from purchasebatch where shipmentid="' . $_POST['shipmentid'] . '" and productid="' . $productid[$i] . '" and amount="' . $kladdamount . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row2 = $query_result[0];
  $portfees[$i] = $row2['portfees'];
  */
  /* 2019 08 06 recalculate portfees
  $query = 'select portfees from purchasebatch where purchaseid=?';
  $query_prm = array($purchaseid[$i]);
  require('inc/doquery.php');
  $row2 = $query_result[0];
  $portfees[$i] = $row2['portfees'];
  */
  $sih[$i] = $row['sih'] . $row['countryid'] . $row['avantage'] . $row['tcp_gradient'];
}
for ($i=0;$i < $num_results_main; $i++)
{
  $freightcost[$i] = $totalfreightcost * ($productweight[$i] / $totalweight);
  $sanitaryfees[$i] = $totalsanitaryfees * ($productweight[$i] / $totalweight);
  $coldstorage[$i] = $totalcoldstorage * ($productweight[$i] / $totalweight);
  $unloadingcost[$i] = $totalunloadingcost * ($productweight[$i] / $totalweight);
  $insurance[$i] = $totalinsurance * (($purchasepricexpf[$i] + $freightcost[$i]) / ($totalfreightcost + $totalpricexpf));
}
### 2019 08 06 recalculate portfees
$query = 'select sum(portfees) as pf,sih,countryid,avantage,tcp_gradient,CONCAT(sih,countryid,avantage,tcp_gradient) as oursih
from purchasebatch,product
where purchasebatch.productid=product.productid
and shipmentid=?
group by oursih';
$query_prm = array($_POST['shipmentid']);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $FENIX_sihportfees[$query_result[$i]['oursih']] = $query_result[$i]['pf']; # TODO HERE country and avantage?
  #echo '<br>',$sih[$i],' ',$FENIX_sihportfees[$query_result[$i]['sih']];OK
}
for ($i=0; $i < $num_results_main; $i++)
{
  $caf[$i] = $purchasepricexpf[$i] + $freightcost[$i] + $insurance[$i];
  if (!isset($cafbysih[$sih[$i]])) { $cafbysih[$sih[$i]] = 0; }
  $cafbysih[$sih[$i]] += $caf[$i];
  #echo '<br>',$sih[$i],' ',$cafbysih[$sih[$i]];
}
for ($i=0; $i < $num_results_main; $i++)
{
  #echo '<br>',($i+1),' CAF=',$caf[$i],' DD=',$FENIX_sihportfees[$sih[$i]],' Diviser par total=',$cafbysih[$sih[$i]]; # debug leave for now
  $portfees[$i] = round($caf[$i]) * $FENIX_sihportfees[$sih[$i]] / round($cafbysih[$sih[$i]]);
}
#
echo '<h2>' . $vesselname . ' arrivé le ' . datefix($arrivaldate) . '</h2>';
echo '<h3>' . $incotermname . ' devise ' . $currencyname . '</h3>';
echo '<h3>Taux devise ' . $exchangerate . '</h3>';
echo '<table class="report">';
$twc = 0;
echo '<tr><td colspan=2><b>Produit</b></td><td><b>Condit</b></td><td><b>Qte</b></td>
<td class="breakme"><b>Valeur ' . $incotermname . ' ' . $currencyname . '</b></td>
<td class="breakme"><b>Valeur ' . $incotermname . ' XPF</b></td><td><b>Fret</b></td><td><b>Ass</b></td>
<td class="breakme"><b>Valeur CAF</b></td><td class="breakme"><b>Frais Debarq</b></td><td class="breakme"><b>Transit</b></td>
<td class="breakme"><b>Frais Transport</b></td><td class="breakme"><b>Frais A/R</b></td><td class="breakme"><b>Frais étiquetage</b>
<td class="breakme"><b>Laissez Passer Frais</b></td><td class="breakme"><b>Frais Frigo/Autre</b></td>
<td class="breakme"><b>Prix Total Entrepot</b></td>';
echo '<td class="breakme"><b>Prix Entr. Réel Carton</b></td>';
echo '<td class="breakme"><b>Droits Douane</b></td><td class="breakme"><b>Droits Douane Maj</b></td><td class="breakme"><b>Prix Total Rev</b></td><td class="breakme"><b>Prix Total Rev Carton</b></td><td class="breakme"><b>Prix Total Rev Maj</b></td><td class="breakme"><b>Prix Total Rev Maj Carton</b></td><td class="breakme"><b>Prix Gros Carton</b></td>';
echo '<td class="breakme"><b>Prix Isle Carton</b></td>';
echo '<td class="breakme"><b>Prix Detail</b></td>'; # Carton, but per unit for PPN
echo '<td><b>Type</td></tr>';
for ($i=0; $i < $num_results_main; $i++)
{
  echo '<tr><td align=right>' . $productid[$i] . '</td><td class="breakme">' . $productname[$i] . '</td>
  <td>' . $packaging[$i] . '</td><td align=right>' . myfix($amountcartons[$i]);
  if ($soldbykilo[$i]) { echo ' (' . $amount[$i] . ' kgs)'; }
  echo '</td><td align=right>' . myfix($purchaseprice[$i],2) . '</td><td align=right>' . myfix($purchasepricexpf[$i]) . '</td>
  <td align=right>' . myfix($freightcost[$i]) . '</td><td align=right>' . myfix($insurance[$i]) . '</td>';
  $totalcaf = $totalcaf + $caf[$i];
  $mytransit = $caf[$i] * 0.02; if ($_POST['notransit'] == 1) { $mytransit = 0; }
  echo '<td align=right>' . myfix($caf[$i]) . '</td>';
  echo '<td align=right>' . myfix($unloadingcost[$i]) . '</td><td align=right>' . myfix($mytransit) . '</td>
  <td align=right>' . myfix($transitcost[$i]) . '</td>';
  echo '<td align=right>' . myfix($palletpricepercarton[$i]) . '</td>';
  echo '<td align=right>' . myfix($stickercost[$i]) . '</td><td align=right>' . myfix($sanitaryfees[$i]) . '</td>
  <td align=right>' . myfix($coldstorage[$i]) . '</td>';
  $totalmytransit = $totalmytransit + $mytransit;
  $totaltransitcost = $totaltransitcost + $transitcost[$i];
  $totalstickercost = $totalstickercost + $stickercost[$i];
  $totalpalletpricepercarton += $palletpricepercarton[$i];
  $totalsf = $totalsf + $sanitaryfees[$i];
  $totalcs = $totalcs + $coldstorage[$i];
  $warehousecost = $purchasepricexpf[$i] + $unloadingcost[$i] + $transitcost[$i] + $palletpricepercarton[$i] + $stickercost[$i] + $freightcost[$i] + $insurance[$i] + $sanitaryfees[$i] + $coldstorage[$i] + $mytransit;
  echo '<td align=right>' . myfix($warehousecost) . '</td>';
  # 2013 02 18
  /*
  if ($producttype[$i] == "PAO")
  */
  #if ($margintype[$i] == 2)
  #{
    #echo '<td align=right>'.myfix($margin[$i]).'</td>'; # Prix Entr. Réel Carton
    if ($soldbykilo[$i]) { echo '<td align=right>'.myfix($warehousecost/$amount[$i]).'</td>'; } # Prix Entr. Réel Carton
    else { echo '<td align=right>'.myfix($warehousecost/$amountcartons[$i]).'</td>'; } # Prix Entr. Réel Carton
  #}
  #else
  #{
  #  echo '<td>&nbsp;</td>';
  #}
  echo '<td align=right>' . myfix($portfees[$i]) . '</td>'; # Droits Douane
  echo '<td align=right>' . myfix($portfees[$i] * 1.05) . '</td>'; # Droits Douane Maj
  $twc = $twc + $warehousecost;
  echo '<td align=right>' . myfix($warehousecost + $portfees[$i]) . '</td>'; # prix total rev
  
  #$prev = ($warehousecost + $portfees[$i]) / $amountcartons[$i];
  $prev = ($warehousecost + $portfees[$i]) / $companycartons[$i]; # 2016 10 10 changed by request, Jimmy, Wing Chong
  if ($soldbykilo[$i]) { $prev = ($warehousecost + $portfees[$i]) / $amount[$i]; }
  
  echo '<td align=right>' . myfix($prev) . '</td>';
  echo '<td align=right>' . myfix($warehousecost + ($portfees[$i] * 1.05)) . '</td>';
  $cartonprice = ($warehousecost + ($portfees[$i] * 1.05)) / ($companycartons[$i]);
  $totalpf = $totalpf + $portfees[$i];
  $totalpfm = $totalpfm + ($portfees[$i] * 1.05);
  echo '<td align=right>' . myfix($cartonprice) . '</td>'; # Prix Total Rev Maj Carton

  $cost = $cartonprice;
  $allowedcost = $cost;
  # 2013 02 18
  #if ($margintype[$i] == "XPF") { $actualmargin = $margin[$i]; }
  if ($margintype[$i] == 1) { $actualmargin = $margin[$i]; }
  else
  {
    if ($soldbykilo[$i]) { $amountcartons[$i] = $amount[$i]; }
    $actualmargin = ($warehousecost/($amount[$i]/$numberperunit[$i])) * $margin[$i] / 100;
  }
  $suggestedretailprice = $allowedcost + $actualmargin;
#echo '<br>Prix detail= (' . $allowedcost . '[allowedcost] + '.$actualmargin.'[actualmargin]) / ' . $numberperunit[$i];
  $margintokeep = $_SESSION['ds_margintokeepPGC'];
  if ($producttype[$i] == "PPN") { $margintokeep = $_SESSION['ds_margintokeepPPN']; }
  if ($producttype[$i] != "PPN" && $producttype[$i] != "PGC" && $producttype[$i] != "PGL") { $margintokeep = 1; }
  
  if ($producttype[$i] == "PPN")
  {
    $suggestedprice = $cartonprice + ($actualmargin * $margintokeep);
#echo '<br>Prix Gros Carton = (' . $cartonprice . '[cartonprice] + ('.$actualmargin.'[$actualmargin] * '.$margintokeep.'[$margintokeep])';
  }
  else { $suggestedprice = $allowedcost + ($actualmargin * $margintokeep); }
    
  if ($producttype[$i] == "Libre" || $producttype[$i] == "PGL") { echo '<td>&nbsp;</td>'; }
  elseif ($producttype[$i] == "PAO") { echo '<td>PAO</td>'; }
  else { echo '<td align=right>' . myfix($suggestedprice) . '</td>'; }
  
  #if ($soldbykilo[$i]) { $suggestedretailprice = $suggestedretailprice * ($amountcartons[$i] / $amount[$i]); }
  if ($producttype[$i] == "Libre") { echo '<td>&nbsp;</td>'; }
  elseif ($producttype[$i] == "PAO") { echo '<td>PAO</td>'; }
  else { echo '<td align=right>' . myfix($suggestedprice) . '</td>'; }

  $suggestedretailprice = $suggestedretailprice / $numberperunit[$i];
  
  if ($producttype[$i] == "Libre") { echo '<td>&nbsp;</td>'; }
  elseif ($producttype[$i] == "PAO") { echo '<td>PAO</td>'; }
  else
  {
    echo '<td align=right>' . myfix($suggestedretailprice);
    echo '</td>';
  }
  echo '<td align=right>' . $producttype[$i] . '</td>';
  
  $query = 'update purchasebatch set prev=?,prevmaj=?,pgros=?,pdetail=?,portfees=? where purchaseid=?';
  $query_prm = array($prev,$cartonprice,$suggestedprice,$suggestedretailprice,$portfees[$i],$purchaseid[$i]);
  require('inc/doquery.php');
  if ($num_results == 0)
  {
    ### OLD way, backwards compat
    $query = 'select purchasebatchid from purchasebatch where productid="' . $productid[$i] . '" and arrivaldate="' . $arrivaldate . '" and origamount="' . $amount[$i] . '" limit 1';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results == 1)
    {
      $row2 = $query_result[0];
      $prev = round($prev,2) * $dmp[$i];
      $query = 'update purchasebatch set prev="' . $prev . '",prevmaj="' . round($cartonprice,2) . '",pgros="' . round($suggestedprice,2) . '",pdetail="' . round($suggestedretailprice,2) . '" where purchasebatchid="' . $row2['purchasebatchid'] . '"';
      $query_prm = array();
      require('inc/doquery.php');
    }
  }
  
  # 2016 01 04
  $query = 'update product set recent_prev=? where productid=?';
  $query_prm = array($prev,$productid[$i]);
  require('inc/doquery.php');

  ###

  echo '</tr>';
}
echo '<tr><td colspan=2><b>Produit</b></td><td><b>Condit</b></td><td align=right><b>' . myfix($totalcartons);
if ($totalamount_kg != 0) { echo ' (' . $totalamount_kg . ' kgs)'; }
echo '</b></td><td align=right><b>' . myfix($totalprice,2) . '</b></td><td><b>' . myfix($totalpricexpf) . '</b></td><td align=right><b>' . myfix($totalfreightcost) . '</b></td><td align=right><b>' . myfix($totalinsurance) . '</b></td><td align=right><b>' . myfix($totalcaf) . '</b></td><td align=right><b>' . myfix($totalunloadingcost) . '</b></td><td align=right><b>' . myfix($totalmytransit) . '</b></td>';

echo '<td align=right><b>' . myfix($totaltransitcost) . '</b></td>';

$query = 'update shipment set transitcost=? where shipmentid=?';
$query_prm = array($totaltransitcost,$shipmentid);
require('inc/doquery.php');

echo '<td align=right><b>' . myfix($totalpalletpricepercarton) . '</b></td>'; # TODO check if correct value?

echo '<td align=right><b>' . myfix($totalstickercost) . '</b></td><td align=right><b>' . myfix($totalsf) . '</b></td><td align=right><b>' . myfix($totalcs) . '</b></td><td><b>' . myfix($twc) . '</b></td>
     <td>&nbsp;</td><td align=right><b>' . myfix($totalpf) . '</b></td><td align=right><b>' . myfix($totalpfm) . '</b></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
echo '</table>';
?>