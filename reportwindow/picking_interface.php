<style type="text/css">
body {
  font-weight: bold;
  font-size: 300%;
}
</style>

<font color=red>ALPHA TEST</font><br>

<?php

$PA['igid'] = 'uint';
$PA['index'] = 'uint';
$PA['barcode'] = '';
require('inc/readpost.php');

if ($igid > 0)
{
  $query = 'select picking_start from invoicegroup where invoicegroupid=?';
  $query_prm = array($igid);
  require('inc/doquery.php');
  if ($query_result[0]['picking_start'] === NULL)
  {
    $query = 'update invoicegroup set picking_start=now(),status=2 where invoicegroupid=?';
    $query_prm = array($igid);
    require('inc/doquery.php');
  }
  $query = 'select invoiceitemhistory.productid,sum(quantity) as quantity,productname,netweightlabel,numberperunit
  from invoicehistory,invoiceitemhistory,product
  where invoicehistory.invoiceid=invoiceitemhistory.invoiceid and invoiceitemhistory.productid=product.productid
  and excludefromdelivery=0 and invoicegroupid=? group by productid';
  $query_prm = array($igid);
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  if ($index >= $num_results_main) { $index = 0; }
  
  ### deduct all scanned prods from main_result
  $query = 'select productid,sum(quantity) as quantity from picking where invoicegroupid=?';
  $query_prm = array($igid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $takenA[$query_result[$i]['productid']] = $query_result[$i]['quantity'];
  }
  for ($i=0; $i < $num_results_main; $i++)
  {
    if (isset($takenA[$main_result[$i]['productid']]))
    {
      $main_result[$i]['quantity'] -= $takenA[$main_result[$i]['productid']];
    }
  }
  
  if ($barcode != '')
  {
    $palletid = 0;
    $query = 'select palletid,productid,quantity from pallet,pallet_barcode
    where pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
    and barcode=?';
    $query_prm = array($barcode);
    require('inc/doquery.php');
    if ($num_results && $query_result[0]['productid'] == $main_result[$index]['productid'])
    {
      $palletid = $query_result[0]['palletid'];
      $quantity_available = $query_result[0]['quantity'];
      if ($quantity_available >= $main_result[$index]['quantity'])
      { $quantity_taken = $main_result[$index]['quantity']; }
      else { $quantity_taken = $quantity_available; }
      $quantity_remaining = $quantity_available - $quantity_taken;
      #echo '<br>q taken='.$quantity_taken; maybe feedback to user?
      $query = 'update pallet set quantity=?';
      if ($quantity_remaining <= 0) { $query .= ',deleted=1'; }
      $query .= ' where palletid=?';
      $query_prm = array($quantity_remaining,$palletid);
      require('inc/doquery.php');
      $query = 'insert into picking (employeeid,productid,palletid,quantity,invoicegroupid,pickingdate,pickingtime)
      values (?,?,?,?,?,now(),now())';
      $query_prm = array($_SESSION['ds_myemployeeid'],$main_result[$index]['productid'],$palletid,$quantity_taken,$igid);
      require('inc/doquery.php');
      $main_result[$index]['quantity'] -= $quantity_taken;
    }
  }
  
  $counter = 0;
  while ($main_result[$index]['quantity'] <= 0)
  {
    $index++; $counter++;
    if ($index >= $num_results_main) { $index = 0; }
    if ($counter > $num_results_main)
    {
      $query = 'update invoicegroup set picking_complete=now(),status=1 where invoicegroupid=?';
      $query_prm = array($igid);
      require('inc/doquery.php');
      $igid = 0;
      break;
    }
  }
  
  if ($igid > 0)
  {
    require('preload/placement.php');
    echo 'Préparation '.$igid;
    echo d_table('report');
    echo '<thead><th>Quantité<th colspan=2>Produit</thead>';
    echo d_td($main_result[$index]['quantity']/$main_result[$index]['numberperunit'],'center');
    echo d_td($main_result[$index]['productid'],'right');
    $temp = $main_result[$index]['netweightlabel'];
    if ($main_result[$index]['numberperunit'] > 1) { $temp = $main_result[$index]['numberperunit'].' x '.$temp; }
    echo d_td(d_decode($main_result[$index]['productname']).' '.$temp);
    echo d_table_end();
    
    if ($_SESSION['ds_mywarehouseid'] > 0)
    {
      $query = 'select pallet.placementid,barcode,expiredate from pallet,placement,pallet_barcode
      where pallet.placementid=placement.placementid and pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
      and transportzone=0 and productid=? and warehouseid=? and pallet.deleted=0 and quantity>0
      and expiredate is not null order by expiredate,palletid limit 1';
      $query_prm = array($main_result[$index]['productid'], $_SESSION['ds_mywarehouseid']);
    }
    else
    {
      $query = 'select pallet.placementid,barcode,expiredate from pallet,placement,pallet_barcode
      where pallet.placementid=placement.placementid and pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
      and transportzone=0 and productid=? and pallet.deleted=0 and quantity>0
      and expiredate is not null order by expiredate,palletid limit 1';
      $query_prm = array($main_result[$index]['productid']);
    }
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      echo 'Aucun emplacement trouvé pour ce produit'; 
    }
    else
    {
      for ($i=0; $i < $num_results; $i++)
      {
        echo 'Emplacement: ' .d_output($placementA[$query_result[$i]['placementid']]);
        echo '<br>Palette: ' .d_output($query_result[$i]['barcode']);
        if ($query_result[0]['expiredate'] != NULL) { echo ' &nbsp; ' . datefix($query_result[$i]['expiredate'], 'short'); }
      }
    }
    echo '<br><br><form method="post" action="reportwindow.php">
    <input autofocus class="warehouse" type="text" name="barcode" size=20><br>';
    echo '<input type=hidden name="igid" value="' . $igid . '">';
    echo '<input type=hidden name="index" value="' . $index . '">';
    echo '<input type=hidden name="report" value="picking_interface">';
    echo '<input type="submit" style="position: absolute; left: -9999px"/>';
    echo '</form>';
    echo '<br><br><a href="reportwindow.php?report=picking_interface&igid='.$igid.'&index='.($index+1).'">Sauter</a>';
    echo '<br><br><a href="reportwindow.php?report=picking_interface">Retour</a>';
  }
}

if ($igid == 0)
{
  echo 'Vos Préparations à completer :';
  echo d_table('report');
  $query = 'select * from invoicegroup where employee2id=? and status<>1';
  $query_prm = array($_SESSION['ds_myemployeeid']);
  require('inc/doquery.php');
  for ($i=0; $i<$num_results; $i++)
  {
    $link = '<a href="reportwindow.php?report=picking_interface&igid='.$query_result[$i]['invoicegroupid'].'">'.$query_result[$i]['invoicegroupid'].'</a>';
    echo d_tr(),d_td_unfiltered($link);
    if ($query_result[$i]['status'] == 0) { echo d_td(); }
    else { echo d_td('En cours'); }
    echo d_td(datefix($query_result[$i]['invoicegroupdate'],'short'));
    echo d_td(substr($query_result[$i]['invoicegrouptime'],0,5));
    echo d_td($query_result[$i]['preparationtext']);
  }
  echo d_table_end();
}

?>