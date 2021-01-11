<?php

require('preload/user.php');
require('preload/unittype.php');

$total = $total2 = 0;
$subt1 = $subt2 = $subt3 = 0;

$PA['userid'] = 'int';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
require('inc/readpost.php');

$average_prevA = array(); # array with productid => average_prev
$currentyear = mb_substr($_SESSION['ds_curdate'],0,4);
$dmp = 1; # TODO remove

echo '<h2>Marge par utilisateur';
if ($userid > 0) { echo ' ',$userA[$userid]; }
if ($userid == -1) { echo ' (tous)'; }
echo '</h2>';
echo '<p>De ', datefix2($startdate), ' à ', datefix2($stopdate);

$productalert_text = '';

echo d_table('report');
echo '<thead><th>Opération<th>Date<th>Produit<th>Quantité<th>PRev<th>Prix<th>Marge<th>%</thead>';

$query = 'select lineprice,linevat,accountingdate as date,invoiceitemhistory.productid,invoicehistory.invoiceid,quantity
,numberperunit,recent_prev,productname,unittypeid
from invoicehistory,invoiceitemhistory,product
where invoicehistory.invoiceid=invoiceitemhistory.invoiceid and invoiceitemhistory.productid=product.productid
and confirmed=1 and isreturn=0 and cancelledid=0 and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate,$stopdate);
if ($userid > 0)
{
  $query .= ' and userid=?'; array_push ($query_prm, $userid);
}
$query .= ' order by accountingdate,invoiceid';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  if (!isset($average_prevA[$main_result[$i]['productid']]))
  {
    # calc $average_prevA[$query_result[$i]['productid']]
    ######################################################
    $first_prev = 0;
    $productid = $main_result[$i]['productid'];
    $numberperunit = $main_result[$i]['numberperunit'];
    require ('inc/calcstock.php');
    
    # list of all purchasebatches
    $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
    $query = $query . ' from purchasebatch,usertable';
    $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0 and prev>0';
    $query = $query . ' and productid=? and arrivaldate<=?';
    $query = $query . ' order by ';
    $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc limit 1'; # 2018 01 23 no more average, find last prev
    $query_prm = array($productid,$stopdate);
    require('inc/doquery.php');
    if ($num_results)
    {
      $showemptylots = 0; $calc_avg = 0; $orig_stock = $stock;
      for ($x=0; $x < $num_results; $x++)
      {
        $row = $query_result[$x];
        if ($x == 0) { $first_prev = $row['prev']; }
        $prev = $row['prev']+0;
      }
      if ($orig_stock == 0) { $average_prevA[$productid] = 0; }
      else { $average_prevA[$productid] = $calc_avg / $orig_stock; }
    }
    elseif ($main_result[$i]['recent_prev'] > 0) { $average_prevA[$productid] = $main_result[$i]['recent_prev']; }
    else { $productalert_text .= ' '.$productid; }
    if (!isset($average_prevA[$productid]) || $average_prevA[$productid] == 0) { $average_prevA[$productid] = $first_prev; }
    ######################################################
  }
  $main_result[$i]['quantity'] = $main_result[$i]['quantity'] / $unittype_dmpA[$main_result[$i]['unittypeid']];
  $value = $main_result[$i]['lineprice'] - ($main_result[$i]['quantity'] * $average_prevA[$main_result[$i]['productid']]); #  + $main_result[$i]['linevat']
  $value = (int) $value;
  echo d_tr();
  if ($i==0 || $main_result[$i]['invoiceid'] != $main_result[($i-1)]['invoiceid']) { echo d_td('Facture '.$main_result[$i]['invoiceid']); }
  else { echo d_td(); }
  echo d_td($main_result[$i]['date'],'date');
  echo d_td(d_decode($main_result[$i]['productname']).' ['.$main_result[$i]['productid'].']');
  echo d_td($main_result[$i]['quantity'],'int');
  echo d_td($average_prevA[$main_result[$i]['productid']], 'decimal');
  echo d_td($main_result[$i]['lineprice'],'decimal');
  echo d_td($value,'decimal');
  if ($main_result[$i]['lineprice'] != 0) { echo d_td(round($value*100/$main_result[$i]['lineprice'],2),'decimal'); }
  else { echo d_td(); }
  $total += $value;
  $total2 += $main_result[$i]['lineprice'];
  $subt1 += $main_result[$i]['quantity'] * $average_prevA[$main_result[$i]['productid']];
  $subt2 += $main_result[$i]['lineprice'];
  $subt3 += $value;
  if (!isset($main_result[($i+1)]['invoiceid']) || $main_result[$i]['invoiceid'] != $main_result[($i+1)]['invoiceid'])
  {
    echo d_tr(1),d_td('Facture '.$main_result[$i]['invoiceid']),d_td(),d_td(),d_td()
    ,d_td($subt1,'decimal'),d_td($subt2,'decimal'),d_td($subt3,'decimal');
    if ($subt2 != 0) { echo d_td(round($subt3*100/$subt2,2),'decimal'); } else { echo d_td(); }
    $subt1 = $subt2 = $subt3 = 0;
  }
}

$query = 'select lineprice,linevat,accountingdate as date,invoiceitemhistory.productid,invoicehistory.invoiceid,quantity
,recent_prev,productname,unittypeid
from invoicehistory,invoiceitemhistory,product
where invoicehistory.invoiceid=invoiceitemhistory.invoiceid and invoiceitemhistory.productid=product.productid
and confirmed=1 and isreturn=1 and cancelledid=0 and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate,$stopdate);
if ($userid > 0)
{
  $query .= ' and userid=?'; array_push ($query_prm, $userid);
}
$query .= ' order by accountingdate,invoiceid';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0; $i < $num_results_main; $i++)
{
  if (!isset($average_prevA[$main_result[$i]['productid']]))
  {
    # calc $average_prevA[$query_result[$i]['productid']]
    ######################################################
    $first_prev = 0;
    $productid = $main_result[$i]['productid'];
    $numberperunit = $main_result[$i]['numberperunit'];
    require ('inc/calcstock.php');
    
    # list of all purchasebatches
    $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
    $query = $query . ' from purchasebatch,usertable';
    $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0';
    $query = $query . ' and productid="' . $productid . '"';
    $query = $query . ' order by ';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . 'placementrank asc,'; } TODO
    $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results)
    {
      $showemptylots = 0; $calc_avg = 0; $orig_stock = $stock;
      for ($x=0; $x < $num_results; $x++)
      {
        $row = $query_result[$x];
        if ($x == 0) { $first_prev = $row['prev']; }
        if ($showemptylots > -1)
        {
          $shipmentid = $row['shipmentid']; if ($shipmentid < 1) { $shipmentid = '&nbsp;'; }
          $lotsize = $row['amount'];
          $showlotsize = $lotsize;
          $stock = $stock - $lotsize;
          $amountleft = $lotsize;
          if ($stock < 0) { $amountleft = $amountleft + $stock; }
          if ($amountleft < 0) { $amountleft = 0; }
          $showamountleft = floor($amountleft/$numberperunit); $showamountleftrest = $amountleft%$numberperunit;
          if ($stock <= 0) { $showemptylots--; }
          $prev = $row['prev']+0;
          $calc_avg += ($prev * $amountleft);
        }
      }
      if ($orig_stock == 0) { $average_prevA[$productid] = 0; }
      else { $average_prevA[$productid] = $calc_avg / $orig_stock; }
    }
    elseif ($main_result[$i]['recent_prev'] > 0) { $average_prevA[$productid] = $main_result[$i]['recent_prev']; }
    else { $productalert_text .= ' '.$productid; }
    if ($average_prevA[$productid] == 0) { $average_prevA[$productid] = $first_prev; }
    ######################################################
  }
  $main_result[$i]['quantity'] = $main_result[$i]['quantity'] / $unittype_dmpA[$main_result[$i]['unittypeid']];
  $value = $main_result[$i]['lineprice'] - ($main_result[$i]['quantity'] * $average_prevA[$main_result[$i]['productid']]); #  + $main_result[$i]['linevat']
  $value = (int) $value;
  $value = 0 - $value;
  echo d_tr();
  if ($i==0 || $main_result[$i]['invoiceid'] != $main_result[($i-1)]['invoiceid']) { echo d_td('Avoir '.$main_result[$i]['invoiceid']); }
  else { echo d_td(); }
  echo d_td($main_result[$i]['date'],'date');
  echo d_td(d_decode($main_result[$i]['productname']).' ['.$main_result[$i]['productid'].']');
  echo d_td($main_result[$i]['quantity'],'int');
  echo d_td($average_prevA[$main_result[$i]['productid']], 'decimal');
  echo d_td($main_result[$i]['lineprice'],'decimal'); #  + $main_result[$i]['linevat']
  echo d_td($value,'decimal');
  if ($main_result[$i]['lineprice'] == 0) { echo d_td(); }
  else { echo d_td(round($value*100/$main_result[$i]['lineprice'],2),'decimal'); }
  $total -= $value;
  $total2 -= $main_result[$i]['lineprice'];
  $subt1 += $main_result[$i]['quantity'] * $average_prevA[$main_result[$i]['productid']];
  $subt2 += $main_result[$i]['lineprice'];
  $subt3 += $value;
  if (!isset($main_result[($i+1)]['invoiceid']) || $main_result[$i]['invoiceid'] != $main_result[($i+1)]['invoiceid'])
  {
    echo d_tr(1),d_td('Avoir '.$main_result[$i]['invoiceid']),d_td(),d_td(),d_td()
    ,d_td($subt1,'decimal'),d_td($subt2,'decimal'),d_td($subt3,'decimal');
    if ($subt2 == 0) { echo d_td(); }
    else { echo d_td(round($subt3*100/$subt2,2),'decimal'); }
    $subt1 = $subt2 = $subt3 = 0;
  }
}

$query = 'select modifiedstock.productid,productname,netvalue,changedate,modifiedstockid,netchange
from modifiedstock,product
where modifiedstock.productid=product.productid
and netvalue<>0 and changedate>=? and changedate<=?';
$query_prm = array($startdate,$stopdate);
if ($userid > 0)
{
  $query .= ' and userid=?'; array_push ($query_prm, $userid);
}
$query .= ' order by changedate';
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  echo d_tr();
  echo d_td('Ajustement '.$query_result[$i]['modifiedstockid']);
  echo d_td($query_result[$i]['changedate'],'date');
  echo d_td($query_result[$i]['netchange'],'int');
  echo d_td(d_decode($query_result[$i]['productname']).' ['.$query_result[$i]['productid'].']');
  echo d_td();
  echo d_td();
  echo d_td($query_result[$i]['netvalue'],'int');
  $total += (int) $query_result[$i]['value'];
}
echo d_tr(1);
echo d_td('Total');
echo d_td();
echo d_td();
echo d_td();
echo d_td();
echo d_td();
echo d_td($total,'int');
if ($total2 == 0) { echo d_td(); }
else { echo d_td(round($total*100/$total2,2),'decimal'); }
echo d_table_end();

/*if ($productalert_text != '')
{
  echo '<p class=alert>Produits sans Prix de Revient : '.$productalert_text.'</p>';
}*/

?>