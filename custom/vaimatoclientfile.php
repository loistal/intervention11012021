<?php

echo '<br><table class="report" border=1 cellspacing=2 cellpadding=2 width=1200>';
echo '<tr><td><b>Livraisons</td><td><b>Prix</td><td><b>Prochaine</td><td><b>Periodicité</td><td><b>&nbsp;</td></tr>';
$query = 'select reference,frtype,quantity,day,daytype,periodic,deliverydate,vacationdate from vmt_delivery where clientid="' . $clientid . '" order by periodic desc,rentalid desc';
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  
  $productid = 10; # hardcode
  $query = 'select salesprice,detailsalesprice,taxcode from product,taxcode where product.taxcodeid=taxcode.taxcodeid and productid=10'; # hardcode
  $query_prm = array();
  require('inc/doquery.php');
  $row5 = $query_result[0];
  $sp = myround($row5['salesprice']);
  $dsp = myround($row5['detailsalesprice']);
  $taxcode = $row5['taxcode'];
  $query = 'select clientcategoryid,usedetail,surcharge from client where clientid="' . $clientid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row5 = $query_result[0];
  $clientcategoryid = $row5['clientcategoryid'];
  $usedetail = $row5['usedetail'];
  $surcharge = $row5['surcharge'];
  ### PRICE DETERMINATION ### COPY FROM sales.php
  $price = $sp;
  if ($usedetail) { $price = $dsp; }

  # surcharge
  if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

  # check if there is a special category price
  $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $clientcategoryid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

  # check if there is a special client price
  $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $clientid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

  # add VAT
  $price = myround($price + ($price * $taxcode/100));
  
  
  if ($row['periodic'] == 1)
  {
    if ($row['frtype'] == 0) { $type = 'Bonbonnes'; }
    if ($row['frtype'] == 1) { $type = 'Pack'; }
    if ($row['frtype'] == 2) { $type = 'Autre'; }
    if ($row['day'] == 1) { $day = 'Lundi'; }
    if ($row['day'] == 2) { $day = 'Mardi'; }
    if ($row['day'] == 3) { $day = 'Mercredi'; }
    if ($row['day'] == 4) { $day = 'Jeudi'; }
    if ($row['day'] == 5) { $day = 'Vendredi'; }
    if ($row['daytype'] == 1) { $day = $day . ' Tous'; }
    if ($row['daytype'] == 2) { $day = $day . ' Semaine Pair'; }
    if ($row['daytype'] == 3) { $day = $day . ' Semaine Impair'; }
    if ($row['daytype'] == 4) { $day = $day . ' Premier du mois'; }
    if ($row['daytype'] == 6 ) { $day = $day . ' Mensuel (1)'; }
    if ($row['daytype'] == 7 ) { $day = $day . ' Mensuel (2)'; }
    if ($row['daytype'] == 8 ) { $day = $day . ' Mensuel (3)'; }
    if ($row['daytype'] == 9 ) { $day = $day . ' Mensuel (4)'; }
    $day = $day . ', début: ' . datefix2($row['deliverydate']);

    # find next date
    #$nextdate = $_SESSION['ds_curdate'];
    $nextdate = date("Y-m-d", strtotime("-1 day"));
    if ($row['vacationdate'] > $nextdate) { $nextdate = $row['vacationdate']; }
    $done = 0; $safetycounter = 0;
    while($done == 0)
    {
      $nextdate = strtotime('+1 day', strtotime($nextdate));
      $nextdate = date('Y-m-d', $nextdate);
#echo 'nextdate= ' . $nextdate . '<br>';
      $daynumber = date("w",strtotime($nextdate));
#echo 'comparing ' . $row['day'] . ' and ' . $daynumber . '<br>';
      if ($row['day'] == $daynumber)
      {
        if ($row['daytype'] == 1) { $done = 1; }
        elseif ($row['daytype'] == 2)
        {
          $weeknumber = date("W",strtotime($nextdate));
          if ($weeknumber % 2 == 0) { $done = 1; }
         }
        elseif ($row['daytype'] == 3)
        {
          $weeknumber = date("W",strtotime($nextdate));
          if ($weeknumber % 2) { $done = 1; }
         }
        elseif ($row['daytype'] == 4)
        {
          if ((substr($nextdate,8,2)+0) < 8) { $done = 1; }
        }
        elseif ($row['daytype'] == 6)
        {
          $weeknumber = date("W",strtotime($nextdate));
          if ($weeknumber % 4 == 1) { $done = 1; }
        }
        elseif ($row['daytype'] == 7)
        {
          $weeknumber = date("W",strtotime($nextdate));
          if ($weeknumber % 4 == 2) { $done = 1; }
        }
        elseif ($row['daytype'] == 8)
        {
          $weeknumber = date("W",strtotime($nextdate));
          if ($weeknumber % 4 == 3) { $done = 1; }
        }
         elseif ($row['daytype'] == 9)
        {
          $weeknumber = date("W",strtotime($nextdate));
          if ($weeknumber % 4 == 0) { $done = 1; }
        }
      }

      $weeknumber = date("W",$nextdate);
      $odd = 0; $even = 0;
      if ($weeknumber % 2) { $odd = 1; }
      else { $even = 1; }

      if ($done == 1) { $nextdate = datefix2($nextdate); }
      $safetycounter++;
      if ($safetycounter > 365) { $done = 1; $nextdate = "&nbsp;"; }
    }

    echo '<tr><td>' . $row['quantity'] . ' ' . $type . '</td><td>' . $price . '</td><td>' . $nextdate . '</td><td>' . $day . '</td><td>' . $row['reference'] . '</td></tr>';
  }
  elseif ($row['deliverydate'] >= $_SESSION['ds_curdate'])
  {
    echo '<tr><td>' . $row['quantity'] . ' ' . $type . '</td><td>' . $price . '</td><td>' . datefix2($row['deliverydate']) . '</td><td>-</td><td>' . $row['reference'] . '</td></tr>';
  }
}
echo '</table>';

$query = 'select vmt_rental.rentalid,contractdate,rentalprice,reference,vmt_rental.clientid,clientname,months,lastcreatedate from vmt_rental,client';
$query = $query . ' where vmt_rental.clientid=client.clientid';
$query = $query . ' and vmt_rental.clientid="' . $clientid . '"';
$query = $query . ' order by reference';
$query_prm = array();
require('inc/doquery.php');
echo '<br><table class="report" border=1 cellspacing=2 cellpadding=2 width=1200>';
echo '<tr><td><b>Locations</b></td><td><b>Prix Mensuel</b></td><td><b>Date contrat</b></td><td><b>Periodicité</b></td><td><b>Fontaine</b></td><td><b>Dèrniere Facture</b></td></tr>';
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  $lastcd = datefix2($row['lastcreatedate']); if ($row['lastcreatedate'] < "2000-01-01") { $lastcd = '&nbsp;'; }
  $query = 'select fountainname from vmt_fountain where rentalid="' . $row['rentalid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row2 = $query_result[0];
  echo '<tr><td>' . $row['reference'] . '</td><td align=right>' . $row['rentalprice'] . '</td><td>' . datefix2($row['contractdate']) . '</td><td>' . $row['months'] . ' mois</td><td>' . $row2['fountainname'] . '</td><td>' . $lastcd . '</td></tr>';
}
echo '</table>';

$query = 'select fountainname,fountaincatname,changedate,maintdate2 from vmt_fountain,vmt_rental,vmt_fountaincat where vmt_fountain.rentalid=vmt_rental.rentalid and vmt_fountain.fountaincatid=vmt_fountaincat.fountaincatid and clientid="' . $clientid . '"';
$query_prm = array();
require('inc/doquery.php');
echo '<br><table class="report" border=1 cellspacing=2 cellpadding=2 width=1200>';
echo '<tr><td><b>Fontaine</b></td><td><b>Catégorie</b></td><td><b>Date changem.</b></td><td><b>Date proch.</b></td></tr>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  echo '<tr><td>' . $row['fountainname'] . '</td><td>' . $row['fountaincatname'] . '</td><td>' . datefix2($row['changedate']) . '</td><td>' . datefix2($row['maintdate2']) . '</td></tr>';
}
echo '</table>';


?>