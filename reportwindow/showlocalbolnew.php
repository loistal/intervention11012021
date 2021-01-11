<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

function showpageheader($pagecounter)
{
  global $history, $vesselname, $clientname, $extraname, $tahitinumber, $destination, $invoiceid, $date;
  $outputstring = '';
  if ($pagecounter > 1) { $outputstring .= '<p class="breakhere"></p>'; }
  $outputstring = $outputstring . '<table class="transparent" border=0 cellspacing=1 cellpadding=1><tr><td>';
  $ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
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
  #if ($numpages > 1) { $outputstring = $outputstring . '<br>Page : ' . $pagecounter . ' / ' . $numpages; }
$numpages = '$NUMPAGES$'; # search and replace
  $outputstring = $outputstring . '<br>Page : ' . $pagecounter . ' / ' . $numpages;
  $outputstring = $outputstring . '</td></tr></table><br>';
  return $outputstring;
}

$PA['invoiceid'] = 'uint';
$PA['itemfontsize'] = 'uint';
require('inc/readpost.php');

if ($invoiceid < 1) { exit; }
if ($itemfontsize <= 0) { $itemfontsize = 100; }
if ($itemfontsize >= 1000) { $itemfontsize = 1000; }

?>
<STYLE type=text/css>

body {
  background-color: #ffffff;
  color: black;
  font-family: Monaco, Courier New, monospace;
  font-size: <?php echo $itemfontsize; ?>%;
  text-align: justify;
  margin-top: 1px;
  margin-left: 1px
}

table {
  margin-top: 0px;
  margin-left: 0px;
  font-family: Monaco, Courier New, monospace;
  font-size: <?php echo $itemfontsize; ?>%
}

.tr4{
  color: red;
  position: absolute;
  top: 18px;
  left: 950px;
  width: auto;
  border: 3px solid red;
  padding: 0.1em 0.2em 0.1em 0.1em;
  -moz-border-radius: 8px;
  -webkit-border-radius: 8px;
  -opera-border-radius: 8px;
  -khtml-border-radius: 8px;
  border-radius: 8px;
}
</STYLE>
<?php

if (1==0 && $_SESSION['ds_customname'] == 'Wing Chong')
{
echo '<div class="tr4">Ouverture Samedis 16, 23, 30 Décembre: 08h00-12h00<br>
Fermeture Inventaire Mardi 02 au Vendredi 05 Janvier 2018<br>
              Réouverture LUNDI 08 JANVIER 2018</div>';
}

require('preload/localvessel.php');
require('preload/unittype.php');

#info only
/*
$rgntitle[1] = 'PAO';
$rgntitle[2] = 'PPN';
$rgntitle[3] = 'GC';
$rgntitle[4] = 'MG';
$rgntitle[5] = 'Frigo';
$rgntitle[6] = 'Réfrigéré';
$rgntitle[7] = 'Frigo (PPN)';
$rgntitle[8] = 'Réfrigéré (PPN)';
*/

$history = '';
$query = 'select isnotice,localvesselid,invoiceid,deliverydate,clientname,extraname,tahitinumber,quarter,townname,islandname
,clientcategoryid,localvesselid,regulationzoneid
from invoice'.$history.',client,town,island
where client.townid=town.townid and town.islandid=island.islandid and invoice'.$history.'.clientid=client.clientid
and invoiceid=?';
if ($_SESSION['ds_confirmonlyown'] == 1) { $query .= ' and invoice'.$history.'.userid="'.$_SESSION['ds_userid'].'"'; }
if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' and (invoice'.$history.'.userid="'.$_SESSION['ds_userid'].'"';
  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' or invoice'.$history.'.employeeid="'.$_SESSION['ds_myemployeeid'].'"';
  }
  $query .= $queryadd.')';
}
if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and invoice'.$history.'.clientid in ' . $_SESSION['ds_allowedclientlist']; }
$query_prm = array($invoiceid);
require('inc/doquery.php');
if ($num_results == 0)
{
  $history = 'history';
  $query = 'select isnotice,localvesselid,invoiceid,deliverydate,clientname,extraname,tahitinumber,quarter,townname,islandname
  ,clientcategoryid,localvesselid,regulationzoneid
  from invoice'.$history.',client,town,island
  where client.townid=town.townid and town.islandid=island.islandid and invoice'.$history.'.clientid=client.clientid
  and invoiceid=?';
  if ($_SESSION['ds_confirmonlyown'] == 1)
  {
    $queryadd = ' and (invoice'.$history.'.userid="'.$_SESSION['ds_userid'].'"';
    if ($_SESSION['ds_myemployeeid'] > 0)
    {
      $queryadd .= ' or invoice'.$history.'.employeeid="'.$_SESSION['ds_myemployeeid'].'"';
    }
    $query .= $queryadd.')';
  }
  if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and invoice'.$history.'.clientid in ' . $_SESSION['ds_allowedclientlist']; }
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
}

$row = $query_result[0];
$isnotice = $row['isnotice'];
$tahitinumber = $row['tahitinumber'];
$extraname = $row['extraname'];
if (isset($localvesselA[$row['localvesselid']])) { $vesselname = $localvesselA[$row['localvesselid']]; } else { $vesselname = ''; }
$date = datefix($row['deliverydate']);
$year = substr($row['deliverydate'],0,4);
$clientname = d_decode($row['clientname']);
$destination = $row['townname'] . ' / ' . $row['islandname'];
$islandname = $row['islandname'];
$regulationzoneid = $row['regulationzoneid'];
if ($row['quarter'] != "") { $destination = $row['quarter'] . ' / ' . $destination; }

$maxpages = 2;
if ($_SESSION['ds_customname'] == 'Wing Chong')
{
  ### HARDCODE for Wing Chong, needs to be generalised
  if (($row['clientcategoryid'] >= 26 && $row['clientcategoryid'] <= 30) || $row['clientcategoryid'] == 23 || $row['clientcategoryid'] == 22 || $row['clientcategoryid'] == 36 || $row['clientcategoryid'] == 40) { $maxpages = 4; } # "magasin"
}

$mainquery = 'select salesprice,sih,lineprice,givenrebate,volume,weight,quantity,productname,brand,numberperunit,netweightlabel
,unittypeid,regroupnumber,product.productid
from invoiceitem'.$history.',product,regulationtype 
where product.regulationtypeid=regulationtype.regulationtypeid
and invoiceitem'.$history.'.productid=product.productid
and invoiceid=?';
$mainorderby = ' order by regroupnumber asc,invoiceitem'.$history.'.productid,invoiceitemid';
$outputstring = '';
$pagecounter = 0;
# need $numpages = how many pages total?

for ($iii=1;$iii<=$maxpages;$iii++)
{
  # top section
  $topsectionshown = 0; $bottomsectionshown = 0; $runquery = 0; $stweight = 0; $stvolume = 0; $pagevolume = 0; $stprice = 0; $subt = 0; $showsignbox = 0;
  if ($iii == 1 && $maxpages == 2) { $query = $mainquery . ' and (regroupnumber=1 OR regroupnumber=2 OR regroupnumber=3)'; $titletext = 'GC'; $runquery = 1; }
  if ($iii == 2 && $maxpages == 2) { $query = $mainquery . ' and (regroupnumber=5 OR regroupnumber=7)'; $titletext = 'Frigo'; $runquery = 1; }
  if ($iii == 1 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=1 OR regroupnumber=2)'; $titletext = "PPN"; $runquery = 1; $showsignbox = 1; }
  if ($iii == 2 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=3)'; $titletext = "GC"; $runquery = 1; }
  if ($iii == 3 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=5)'; $titletext = "Frigo"; $runquery = 1; }
  if ($iii == 4 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=7)'; $titletext = "Frigo"; $runquery = 1; }
  $query = $query . $mainorderby;
  $query_prm = array($invoiceid);
  if ($runquery == 1) { require('inc/doquery.php'); }
  else { $num_results = -1; }
  $main_result = $query_result;
  $num_results_main = $num_results;
  if ($num_results_main > 0)
  {
    $topsectionshown = 1;
    $pagecounter++;
    $outputstring .= showpageheader($pagecounter);
    $sectionheader = '<table class="transparent" STYLE="font-family:monospace" border=0 cellspacing=1 cellpadding=1 width=95%><tr><td width=5%>&nbsp;</td><td width=1%>&nbsp;</td><td><b>' . $titletext . '</td><td width=12%>&nbsp;</td><td width=12% align=right>Poids (kg)</td><td width=12% align=right>Valeur</td><td width=12% align=right>Volume(m<sup>3</sup>)</td><td width=12%>&nbsp;</td></tr>';
    $outputstring .= $sectionheader;
    for ($i=0;$i<$num_results_main;$i++)
    {
      ### OLD showline, verify
      $quantity = floor($main_result[$i]['quantity'] / $main_result[$i]['numberperunit'])+0;
      $quantitycalc = ($main_result[$i]['quantity'] / $main_result[$i]['numberperunit'])+0;
      if ($unittype_dmpA[$main_result[$i]['unittypeid']] != 1)
      {
        $quantitycalc = $quantitycalc / $unittype_dmpA[$main_result[$i]['unittypeid']];
      }
      if ($main_result[$i]['quantity'] % $main_result[$i]['numberperunit'] != 0)
      {
        $showquantity = $main_result[$i]['quantity'] . '&nbsp;' . 'unité';
        $addquan = $main_result[$i]['quantity'];
      }
      else
      {
        $showquantity = $quantitycalc . '&nbsp;' . $unittypeA[$main_result[$i]['unittypeid']];
        $addquan = $quantitycalc;
      }
      if ($main_result[$i]['numberperunit'] > 1 && $main_result[$i]['quantity'] % $main_result[$i]['numberperunit'] == 0)
      { $showcond = $main_result[$i]['numberperunit'] . ' x ' . d_output($main_result[$i]['netweightlabel']); }
      else { $showcond = d_output($main_result[$i]['netweightlabel']); }
      if ($quantity == 0)
      {
        $showquantity = ($main_result[$i]['quantity']%$main_result[$i]['numberperunit']+0) . '&nbsp;' . 'unité';
        $showcond = d_output($main_result[$i]['netweightlabel']);
        $addquan = ($main_result[$i]['quantity']%$main_result[$i]['numberperunit']+0);
      }
      if ($unittypeA[$main_result[$i]['unittypeid']] == 'KG') # for WC, not sure if to keep
      {
        $showquantity = '1&nbsp;Carton';
        $addquan = 1;
        $showcond = $quantitycalc . '&nbsp;' . d_output($main_result[$i]['netweightlabel']);
      }
      $subt += $addquan;
      $main_result[$i]['productname'] = d_output(d_decode($main_result[$i]['productname']));
      if ($_SESSION['ds_customname'] == 'Wing Chong' # see email 2020 01 22
      && $main_result[$i]['productid'] == 337
      && ($regulationzoneid == 8 || $regulationzoneid == 9 || $regulationzoneid == 12))
      { $main_result[$i]['productname'] .= '<br>Avec retour bonbonne d\'eau vide'; }
      $outputstring .= '<tr><td align=right>' . $showquantity . '</td><td>&nbsp;</td><td> ' . $main_result[$i]['productname'] . '</td>';
      $outputstring .= '<td align=right>' . $showcond . '</td>';
      $weight = round((($main_result[$i]['weight']/1000) * $quantitycalc),2);
      $stweight = $stweight + $weight;
      $weightdecimals = strlen(substr(strrchr($weight, "."), 1));
      if ($weightdecimals == 0) { $weight = $weight . '.00'; }
      if ($weightdecimals == 1) { $weight = $weight . '0'; }
      $outputstring .= '<td align=right>' . $weight . '</td>';
      $price = myround($main_result[$i]['lineprice']);
      if ($main_result[$i]['lineprice'] == 0) { $price = myround($main_result[$i]['givenrebate']); }
      if ($price == 0 && $isnotice == 1) { $price = $main_result[$i]['salesprice'] * $main_result[$i]['quantity'] / $main_result[$i]['numberperunit']; }
      $stprice = $stprice + $price;
      $outputstring .= '<td align=right>' . $price . '</td>';
      $volume = round(($main_result[$i]['volume'] * $quantitycalc),3);
      # 2018 10 04 email from Jimmy: Le systeme doit prendre le nombre de sac (et non le poids au kilo du sac) multiplie par 0,04.
      # les facturieres saisissent 1 sac par ligne. Le nombre de sac egale au nombre de ligne
      if ($_SESSION['ds_customname'] == 'Wing Chong' && $unittype_dmpA[$main_result[$i]['unittypeid']] != 1)
      {
        #$volume = round(($main_result[$i]['volume']),3);
        $volume = 0.04;
      }
      ###
      $stvolume = $stvolume + $volume;
      $volumedecimals = strlen(substr(strrchr($volume, "."), 1));
      if ($volumedecimals == 0) { $volume = $volume . '.000'; }
      if ($volumedecimals == 1) { $volume = $volume . '00'; }
      if ($volumedecimals == 2) { $volume = $volume . '0'; }
      $outputstring .= '<td align=right>' . $volume . '</td>';
      $outputstring .= '<td align=right>' . $main_result[$i]['sih'] . '</td></tr>';
      ###
    }
    if ($num_results_main >= 1) #= 2013 01 03
    {
      $weightdecimals = strlen(substr(strrchr($stweight, "."), 1));
      if ($weightdecimals == 0) { $stweight = $stweight . '.00'; }
      if ($weightdecimals == 1) { $stweight = $stweight . '0'; }
      $volumedecimals = strlen(substr(strrchr($stvolume, "."), 1));
      if ($volumedecimals == 0) { $stvolume = $stvolume . '.000'; }
      if ($volumedecimals == 1) { $stvolume = $stvolume . '00'; }
      if ($volumedecimals == 2) { $stvolume = $stvolume . '0'; }
      $outputstring .= '<tr><td align=right>---</td><td colspan=3>&nbsp;</td><td align=right>-----</td><td align=right>-----</td><td align=right>-----</td><td>&nbsp;</td></tr>';
      $outputstring .= '<tr><td align=right><b>'.$subt.'</td><td colspan=3>&nbsp;</td><td align=right><b>'.$stweight.'</td><td align=right><b>'.$stprice.'</td><td align=right><b>'.$stvolume.'</td><td>&nbsp;</td></tr>';
    }
  }
  $pagevolume += $stvolume;
  # bottom section
  $runquery = 0; $stweight = 0; $stvolume = 0; $stprice = 0; $subt = 0;
  if ($iii == 1 && $maxpages == 2) { $query = $mainquery . ' and (regroupnumber=4)'; $titletext = 'MG'; $runquery = 1; }
  if ($iii == 2 && $maxpages == 2) { $query = $mainquery . ' and (regroupnumber=6 OR regroupnumber=8)'; $titletext = 'Réfrigéré'; $runquery = 1; }
  #if ($iii == 1 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=2)'; $titletext = "PPN"; $runquery = 1; } no bottom section on page 1
  if ($iii == 2 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=4)'; $titletext = "MG"; $runquery = 1; }
  if ($iii == 3 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=6)'; $titletext = "Réfrigéré"; $runquery = 1; }
  if ($iii == 4 && $maxpages == 4) { $query = $mainquery . ' and (regroupnumber=8)'; $titletext = "Réfrigéré"; $runquery = 1; }
  $query = $query . $mainorderby;
  $query_prm = array($invoiceid);
  if ($runquery == 1) { require('inc/doquery.php'); }
  else { $num_results = -1; }
  $main_result = $query_result;
  $num_results_main = $num_results;
  if ($num_results_main > 0)
  {
    $bottomsectionshown = 1;
    if ($topsectionshown == 0)
    {
      $pagecounter++;
      $outputstring .= showpageheader($pagecounter);
    }
    $sectionheader = '<table class="transparent" STYLE="font-family:monospace" border=0 cellspacing=1 cellpadding=1 width=95%><tr><td width=5%>&nbsp;</td><td width=1%>&nbsp;</td><td><b>' . $titletext . '</td><td width=12%>&nbsp;</td><td width=12% align=right>Poids (kg)</td><td width=12% align=right>Valeur</td><td width=12% align=right>Volume(m<sup>3</sup>)</td><td width=12%>&nbsp;</td></tr>';
    $outputstring .= $sectionheader;
    for ($i=0;$i<$num_results_main;$i++)
    {
      ### OLD showline, verify (copy from above)
      $quantity = floor($main_result[$i]['quantity'] / $main_result[$i]['numberperunit'])+0;
      $quantitycalc = ($main_result[$i]['quantity'] / $main_result[$i]['numberperunit'])+0;
      if ($unittype_dmpA[$main_result[$i]['unittypeid']] != 1)
      {
        $quantitycalc = $quantitycalc / $unittype_dmpA[$main_result[$i]['unittypeid']];
      }
      if ($main_result[$i]['quantity'] % $main_result[$i]['numberperunit'] != 0)
      {
        $showquantity = $main_result[$i]['quantity'] . '&nbsp;' . 'unité';
        $addquan = $main_result[$i]['quantity'];
      }
      else
      {
        $showquantity = $quantitycalc . '&nbsp;' . $unittypeA[$main_result[$i]['unittypeid']];
        $addquan = $quantitycalc;
      }
      if ($main_result[$i]['numberperunit'] > 1 && $main_result[$i]['quantity'] % $main_result[$i]['numberperunit'] == 0)
      { $showcond = $main_result[$i]['numberperunit'] . ' x ' . d_output($main_result[$i]['netweightlabel']); }
      else { $showcond = d_output($main_result[$i]['netweightlabel']); }
      if ($quantity == 0)
      {
        $showquantity = ($main_result[$i]['quantity']%$main_result[$i]['numberperunit']+0) . '&nbsp;' . 'unité';
        $showcond = d_output($main_result[$i]['netweightlabel']);
        $addquan = ($main_result[$i]['quantity']%$main_result[$i]['numberperunit']+0);
      }
      if ($unittypeA[$main_result[$i]['unittypeid']] == 'KG') # for WC, not sure if to keep
      {
        $showquantity = '1&nbsp;Carton';
        $addquan = 1;
        $showcond = $quantitycalc . '&nbsp;' . d_output($main_result[$i]['netweightlabel']);
      }
      $subt += $addquan;
      if ($_SESSION['ds_customname'] == 'Wing Chong' # see email 2020 01 22
      && $main_result[$i]['productid'] == 337
      && ($regulationzoneid == 8 || $regulationzoneid == 9 || $regulationzoneid == 12))
      { $main_result[$i]['productname'] .= '<br>Avec retour bonbonne d\'eau vide'; }
      $outputstring .= '<tr><td align=right>' . $showquantity . '</td><td>&nbsp;</td><td> ' . $main_result[$i]['productname'] . '</td>';
      $outputstring .= '<td align=right>' . $showcond . '</td>';
      $weight = round((($main_result[$i]['weight']/1000) * $quantitycalc),2);
      $stweight = $stweight + $weight;
      $weightdecimals = strlen(substr(strrchr($weight, "."), 1));
      if ($weightdecimals == 0) { $weight = $weight . '.00'; }
      if ($weightdecimals == 1) { $weight = $weight . '0'; }
      $outputstring .= '<td align=right>' . $weight . '</td>';
      $price = myround($main_result[$i]['lineprice']);
      if ($main_result[$i]['lineprice'] == 0) { $price = myround($main_result[$i]['givenrebate']); }
      if ($price == 0 && $isnotice == 1) { $price = $main_result[$i]['salesprice'] * $main_result[$i]['quantity'] / $main_result[$i]['numberperunit']; }
      $stprice = $stprice + $price;
      $outputstring .= '<td align=right>' . $price . '</td>';
      $volume = round(($main_result[$i]['volume'] * $quantitycalc),3);
      # 2018 10 04 email from Jimmy: Le systeme doit prendre le nombre de sac (et non le poids au kilo du sac) multiplie par 0,04.
      # les facturieres saisissent 1 sac par ligne. Le nombre de sac egale au nombre de ligne
      if ($unittype_dmpA[$main_result[$i]['unittypeid']] != 1)
      {
        #$volume = round(($main_result[$i]['volume']),3);
        $volume = 0.04;
      }
      $stvolume = $stvolume + $volume;
      $volumedecimals = strlen(substr(strrchr($volume, "."), 1));
      if ($volumedecimals == 0) { $volume = $volume . '.000'; }
      if ($volumedecimals == 1) { $volume = $volume . '00'; }
      if ($volumedecimals == 2) { $volume = $volume . '0'; }
      $outputstring .= '<td align=right>' . $volume . '</td>';
      $outputstring .= '<td align=right>' . $main_result[$i]['sih'] . '</td></tr>';
      ###
    }
    if ($num_results_main >= 1)
    {
      $weightdecimals = strlen(substr(strrchr($stweight, "."), 1));
      if ($weightdecimals == 0) { $stweight = $stweight . '.00'; }
      if ($weightdecimals == 1) { $stweight = $stweight . '0'; }
      $volumedecimals = strlen(substr(strrchr($stvolume, "."), 1));
      if ($volumedecimals == 0) { $stvolume = $stvolume . '.000'; }
      if ($volumedecimals == 1) { $stvolume = $stvolume . '00'; }
      if ($volumedecimals == 2) { $stvolume = $stvolume . '0'; }
      $outputstring .= '<tr><td align=right>---</td><td colspan=3>&nbsp;</td><td align=right>-----</td><td align=right>-----</td><td align=right>-----</td><td>&nbsp;</td></tr>';
      $outputstring .= '<tr><td align=right><b>'.$subt.'</td><td colspan=3>&nbsp;</td><td align=right><b>'.$stweight.'</td><td align=right><b>'.$stprice.'</td><td align=right><b>'.$stvolume.'</td><td>&nbsp;</td></tr>';
    }
    
  }
  $pagevolume += $stvolume;
  if ($islandname == "Moorea" && $titletext == "MG" && $topsectionshown && $bottomsectionshown) # total volume GCMC 2014 08 12
  {
    $volumedecimals = strlen(substr(strrchr($pagevolume, "."), 1));
    if ($volumedecimals == 0) { $pagevolume = $pagevolume . '.000'; }
    if ($volumedecimals == 1) { $pagevolume = $pagevolume . '00'; }
    if ($volumedecimals == 2) { $pagevolume = $pagevolume . '0'; }
    $outputstring .= '<tr><td colspan=5></td><td><b>TOTAL VOLUME GC MG:</b></td><td align=right><b>' . $pagevolume . '</b></td></tr>';
  }
  # end bottom section
  if (($iii == 2 && $maxpages == 4) || ($iii == 1 && $maxpages == 2))
  {
    $query = $mainquery . ' and regroupnumber=10';
    $query = $query . $mainorderby;
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    for ($xyz=0;$xyz<$num_results;$xyz++)
    {
      $outputstring .= '<tr><td colspan=2>&nbsp;</td><td><b><font size=+1>' . $query_result[$xyz]['productname'] . ': &nbsp; '.myfix($query_result[$xyz]['lineprice']) . '</font></b></td></tr>';
    }
  }
  if (($iii == 3 && $maxpages == 4) || ($iii == 2 && $maxpages == 2))
  {
    $query = $mainquery . ' and regroupnumber=9';
    $query = $query . $mainorderby;
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    for ($xyz=0;$xyz<$num_results;$xyz++)
    {
      $outputstring .= '<tr><td colspan=2>&nbsp;</td><td><b><font size=+1>' . $query_result[$xyz]['productname'] . ': &nbsp; '.myfix($query_result[$xyz]['lineprice']) . '</font></b></td></tr>';
    }
  }
  if ($topsectionshown || $bottomsectionshown) { $outputstring .= '</table>'; }
  else { $showsignbox = 0; }
  if ($showsignbox == 1 && $_SESSION['ds_customname'] == 'Wing Chong') # must be generalised
  {
    $outputstring .= '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WING CHONG<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BP 230 Papeete Tahiti<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RC 620B – NT 044016';
  }
}
$outputstring = str_replace('$NUMPAGES$',$pagecounter,$outputstring);
echo $outputstring;



?>