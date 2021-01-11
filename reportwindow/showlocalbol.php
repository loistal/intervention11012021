<?php

$invoiceid = $_POST['invoiceid']+0;
$maxlines = $_POST['maxlines']+0; if ($maxlines == 0) { $maxlines = 1; }
$deliveryformat = $_POST['deliveryformat']+0;
$maxpage2shown3 = 0;
$maxpage4shown = 0;

require('preload/localvessel.php');
require('preload/unittype.php');

#should possibly be put in a table
$rgntitle[1] = 'PAO';
$rgntitle[2] = 'PPN';
$rgntitle[3] = 'GC';
$rgntitle[4] = 'MG';
$rgntitle[5] = 'Frigo';
$rgntitle[6] = 'Réfrigéré';
$rgntitle[7] = 'Frigo'; # Frigo (PPN)
$rgntitle[8] = 'Réfrigéré'; # Réfrigéré (PPN)
for ($i=1; $i<=8; $i++)
{
  $breakA[$i] = 0;
}
$numpages = 1;

$history = '';
$query = 'select localvesselid,invoiceid,deliverydate,clientname,extraname,tahitinumber,quarter,townname,islandname,clientcategoryid,localvesselid from invoice'.$history.',client,town,island where client.townid=town.townid and town.islandid=island.islandid and invoice'.$history.'.clientid=client.clientid and invoiceid=?';
$query_prm = array($invoiceid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $history = 'history';
  $query = 'select localvesselid,invoiceid,deliverydate,clientname,extraname,tahitinumber,quarter,townname,islandname,clientcategoryid,localvesselid from invoice'.$history.',client,town,island where client.townid=town.townid and town.islandid=island.islandid and invoice'.$history.'.clientid=client.clientid and invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
}

$row = $query_result[0];
$tahitinumber = $row['tahitinumber'];
$extraname = $row['extraname'];
$vesselname = $localvesselA[$row['localvesselid']];
$date = datefix($row['deliverydate']);
$year = substr($row['deliverydate'],0,4);
$clientname = d_decode($row['clientname']);
$destination = $row['townname'] . ' / ' . $row['islandname'];
if ($row['quarter'] != "") { $destination = $row['quarter'] . ' / ' . $destination; }
if ($row['clientcategoryid'] >= 26 && $row['clientcategoryid'] <= 30) { $maxpages = 4; } # HARDCODE for Wing Chong, needs to be generalised
else { $maxpages = 2; }
#echo 'debug invoiceid='.$invoiceid.' clientname='.$clientname.'<br>';
$pagecounter = 0;

#echo $maxpages;

$mainquery = 'select sih,lineprice,volume,weight,quantity,productname,brand,numberperunit,netweightlabel,unittypeid,regroupnumber from invoiceitem'.$history.',product,regulationtype where product.regulationtypeid=regulationtype.regulationtypeid and invoiceitem'.$history.'.productid=product.productid and invoiceid=?';
$mainorderby = ' order by regroupnumber asc,invoiceitem'.$history.'.productid';

### start big loop ###
for ($iii=1;$iii<=$maxpages;$iii++)
{
###

if ($iii == 1 && $maxpages == 2) { $query = $mainquery . ' and (regroupnumber=1 OR regroupnumber=2 OR regroupnumber=3 or regroupnumber=4)'; $titletext = ""; }
if ($iii == 2 && $maxpages == 2) { $query = $mainquery . ' and (regroupnumber=5 OR regroupnumber=6 OR regroupnumber=7 or regroupnumber=8)'; $titletext = ""; }
if ($iii == 1 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=1 or regroupnumber=2)'; $titletext = ""; }
if ($iii == 2 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=3 or regroupnumber=4)'; $titletext = "GC"; }
if ($iii == 3 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=5 or regroupnumber=6)'; $titletext = "Frigo"; }
if ($iii == 4 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=7 or regroupnumber=8)'; $titletext = "Frigo"; }
/*
if ($pagenumber == 1 && $maxpages == 2) { $query = $query . ' and regroupnumber=4'; $titletext = ""; }
if ($pagenumber == 2 && $maxpages == 2) { $query = $query . ' and (regroupnumber=6 OR regroupnumber=8)'; $titletext = ""; }
if ($pagenumber == 1 && $maxpages == 4) { $query = $query . ' and regroupnumber=2'; $titletext = ""; }
if ($pagenumber == 2 && $maxpages == 4) { $query = $query . ' and regroupnumber=4'; $titletext = "MG"; }
if ($pagenumber == 3 && $maxpages == 4) { $query = $query . ' and regroupnumber=6'; $titletext = "Réfrigéré"; }
if ($pagenumber == 4 && $maxpages == 4) { $query = $query . ' and regroupnumber=8'; $titletext = "Réfrigéré"; }
*/
$query = $query . $mainorderby;
$query_prm = array($invoiceid);
require('inc/doquery.php');
$main_result = $query_result;
$num_results_main = $num_results;
/* DISABELED FOR NOW
if ($num_results_main > $maxlines)
{
  $subt = 0;
  $query = 'select count(invoiceitemid) as count,regroupnumber from invoiceitem'.$history.',product,regulationtype where product.regulationtypeid=regulationtype.regulationtypeid and invoiceitem'.$history.'.productid=product.productid and invoiceid=? group by regroupnumber';
  # copy from above, 6 lines
  if ($iii == 1 && $maxpages == 2) { $query = $query . ' and (regroupnumber=1 OR regroupnumber=2 OR regroupnumber=3 or regroupnumber=4)'; }
  if ($iii == 2 && $maxpages == 2) { $query = $query . ' and (regroupnumber=5 OR regroupnumber=6 OR regroupnumber=7 or regroupnumber=8)'; }
  if ($iii == 1 && $maxpages == 4) { $query = $query . ' and (regroupnumber=1 or regroupnumber=2)'; }
  if ($iii == 2 && $maxpages == 4) { $query = $query . ' and (regroupnumber=3 or regroupnumber=4)'; }
  if ($iii == 3 && $maxpages == 4) { $query = $query . ' and (regroupnumber=5 or regroupnumber=6)'; }
  if ($iii == 4 && $maxpages == 4) { $query = $query . ' and (regroupnumber=7 or regroupnumber=8)'; }
  $query = $query . ' order by regroupnumber asc';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  for ($i=0;$i<$num_results;$i++)
  {
    $rgn = $query_result[$i]['regroupnumber'];
    $countedA[$rgn] = $query_result[$i]['count'];
    #echo '<br>$countedA['.$rgn.']='.$countedA[$rgn];
    $subt = $subt + $countedA[$rgn];
    if ($subt >= $maxlines && $i != 1) { $breakA[$lastrgn] = 1; $subt = $countedA[$rgn]; $numpages++; }  #echo '<br>need break after: '.$rgntitle[$lastrgn];
    $lastrgn = $rgn;
  }
}
*/
$lastrgn = -1; $stweight = 0; $stprice = 0; $stvolume = 0; $productcounter = 0; $pagebreak = 0;
for ($i=0;$i<$num_results_main;$i++)
{
  $rgn = $main_result[$i]['regroupnumber'];
  $showsubtotal = 1;
  #if ($maxpages == 4 && $lastrgn == 1 && $maxpage4shown == 1) { $showsubtotal = 0; } ### ??? verify
  if ($maxpages == 2 && ($lastrgn == 1 || $lastrgn == 2) && $maxpage2shown3 == 1) { $showsubtotal = 0; }
  if ($lastrgn != $rgn && $i != 0 && $showsubtotal == 1)
  {
    ###
    if ($productcounter > 1)
    {
      echo '<tr><td colspan=4>&nbsp;</td><td align=right>-----</td><td align=right>-----</td><td align=right>-----</td><td>&nbsp;</td></tr>';
      echo '<tr><td colspan=4>&nbsp;</td><td align=right><b>'.$stweight.'</td><td align=right><b>'.$stprice.'</td><td align=right><b>'.$stvolume.'</td><td>&nbsp;</td></tr>';
    }
    #echo '</table>';
    $stweight = 0; $stprice = 0; $stvolume = 0; $productcounter = 0;
    #echo '<p>$breakA['.$lastrgn.']='.$breakA[$lastrgn].'</p>';
    if ($breakA[$lastrgn] == 1) { $pagebreak = 1; }
    ###
  }
  #if ($i == 0 || $pagebreak)
  if ($i == 0)
  {
    #if ($pagebreak) { echo '<p class="breakhere"></p>'; $pagebreak = 0; $pagecounter++; }
    if ($pagecounter != 0) { echo '</table><p class="breakhere"></p>'; }
    $pagecounter++;
    $outputstring = '';
    
    $outputstring = $outputstring . '<table class="transparent" border=0 cellspacing=1 cellpadding=1><tr><td>';
    $ourlogofile = 'custom/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
    if (file_exists($ourlogofile)) { $outputstring = $outputstring . '<img src="' . $ourlogofile . '">'; }
    $outputstring = $outputstring . '<br>' . $_SESSION['ds_companyinfo'];

    $outputstring = $outputstring . '</td><td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td valign=top>';
    $outputstring = $outputstring . '<b><font size=+1>Connaissement';
    #if ($history == '') { $outputstring = $outputstring . ' NON CONFIRME'; }
    $outputstring = $outputstring . ' de Transport Maritime Interinsulaire</font></b>';
    $outputstring = $outputstring . '<br>Pour être chargé à bord : ' . d_output($vesselname);
    $outputstring = $outputstring . '<br>Destinataire : ' . d_output($clientname) . ' ' . d_output($extraname) . ' ' . $tahitinumber;
    $outputstring = $outputstring . '<br>Destination : ' . d_output($destination);
    $outputstring = $outputstring . '<br><br>Facture : ' . $invoiceid;
    $outputstring = $outputstring . '<br>Date : ' . $date;
    if ($numpages > 1) { $outputstring = $outputstring . '<br>Page : ' . $pagecounter . ' / ' . $numpages; }

    $outputstring = $outputstring . '</td></tr></table><br>';
    echo $outputstring;
   /* echo '<table class="transparent" border=1 cellspacing=1 cellpadding=1 width=95%>';*/
  }
  $showsubheader = 1;
  if ($maxpages == 4 && $rgn == 2 && $maxpage4shown == 1) { $showsubheader = 0; }
  if ($maxpages == 2 && $rgn == 2 && $maxpage2shown3 == 1) { $showsubheader = 0; }
  if ($maxpages == 2 && $rgn == 3 && $maxpage2shown3 == 1) { $showsubheader = 0; }
  #if ($maxpages == 2 && ($rgn == 2 || $rgn == 3)) { $showsubheader = 0; }
  if ($lastrgn != $rgn && $showsubheader == 1)
  {
    if ($i != 0) { echo '<tr><td colspan=10>&nbsp;</td></tr>'; }
    $showrgntitle = $rgntitle[$rgn];
    if ($maxpages == 4 && $rgn == 1) { $showrgntitle = $rgntitle[2]; $maxpage4shown = 1; }
    if ($maxpages == 2 && $rgn == 1) { $showrgntitle = $rgntitle[3]; $maxpage2shown3 = 1; }
    if ($maxpages == 2 && $rgn == 2 && $maxpage2shown3 == 0) { $showrgntitle = $rgntitle[3]; $maxpage2shown3 = 1; }
    echo '<table class="transparent" STYLE="font-family:monospace" border=0 cellspacing=1 cellpadding=1 width=95%><tr><td width=5%>&nbsp;</td><td width=1%>&nbsp;</td><td><b>' . $showrgntitle . '</td><td width=12%>&nbsp;</td><td width=12% align=right>Poids (kg)</td><td width=12% align=right>Valeur</td><td width=12% align=right>Volume(m<sup>3</sup>)</td><td width=12%>&nbsp;</td></tr>';
  }
  $quantity = floor($main_result[$i]['quantity'] / $main_result[$i]['numberperunit'])+0;
  $quantitycalc = ($main_result[$i]['quantity'] / $main_result[$i]['numberperunit'])+0;
  if ($unittype_dmpA[$main_result[$i]['unittypeid']] != 1)
  {
    $quantitycalc = $quantitycalc / $unittype_dmpA[$main_result[$i]['unittypeid']];
  }
  $showquantity = $quantitycalc . '&nbsp;' . $unittypeA[$main_result[$i]['unittypeid']];
  if ($main_result[$i]['numberperunit'] > 1) { $showcond = $main_result[$i]['numberperunit'] . ' x ' . d_output($main_result[$i]['netweightlabel']); }
  else { $showcond = d_output($main_result[$i]['netweightlabel']); }
  if ($quantity == 0)
  {
    $showquantity = ($main_result[$i]['quantity']%$main_result[$i]['numberperunit']+0) . '&nbsp;' . 'unité';
    $showcond = d_output($main_result[$i]['netweightlabel']);
  }
  if ($unittypeA[$main_result[$i]['unittypeid']] == 'kg') # for WC, not sure if to keep
  {
    $showquantity = '1&nbsp;Carton';
  }
  echo '<tr><td align=right>' . $showquantity . '</td><td>&nbsp;</td><td> ' . d_output(d_decode($main_result[$i]['productname'])) . '</td>';
  echo '<td align=right>' . $showcond . '</td>';
  $weight = round((($main_result[$i]['weight']/1000) * $quantitycalc),2);
  $stweight = $stweight + $weight;
  echo '<td align=right>' . $weight . '</td>';
  $price = myround($main_result[$i]['lineprice']);
  $stprice = $stprice + $price;
  echo '<td align=right>' . $price . '</td>';
  $volume = round(($main_result[$i]['volume'] * $quantitycalc),3);
  $stvolume = $stvolume + $volume;
  echo '<td align=right>' . $volume . '</td>';
  echo '<td align=right>' . $main_result[$i]['sih'] . '</td></tr>';
  $productcounter++;
  $lastrgn = $rgn;
}
### copy from above
if ($productcounter > 1)
{
  echo '<tr><td colspan=4>&nbsp;</td><td align=right>-----</td><td align=right>-----</td><td align=right>-----</td><td>&nbsp;</td></tr>';
  echo '<tr><td colspan=4>&nbsp;</td><td align=right><b>'.$stweight.'</td><td align=right><b>'.$stprice.'</td><td align=right><b>'.$stvolume.'</td><td>&nbsp;</td></tr>';
}
$stweight = 0; $stprice = 0; $stvolume = 0; $productcounter = 0;
###
### show fret PPN rgn=9
if ($iii == 1)
{
  $query = $mainquery . ' and regroupnumber=9';
  $query = $query . $mainorderby;
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  for ($xyz=0;$xyz<$num_results;$xyz++)
  {
    echo '<tr><td colspan=2>&nbsp;</td><td><b><font size=+1>' . $query_result[$xyz]['productname'] . ': &nbsp; '.myfix($query_result[$xyz]['lineprice']) . '</font></b></td></tr>';
  }
}
### show fret GC MG rgn=10
if (($iii == 2 && $maxpages == 4) || ($iii == 1 && $maxpages == 2))
{
  $query = $mainquery . ' and regroupnumber=10';
  $query = $query . $mainorderby;
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  for ($xyz=0;$xyz<$num_results;$xyz++)
  {
    echo '<tr><td colspan=2>&nbsp;</td><td><b><font size=+1>' . $query_result[$xyz]['productname'] . ': &nbsp; '.myfix($query_result[$xyz]['lineprice']) . '</font></b></td></tr>';
  }
}
}




?>