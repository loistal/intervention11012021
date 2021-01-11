<style type="text/css">
body {
  font-weight: bold;
  font-size: 300%;
}
</style>

<?php

# ALGORITHM
# load invoicegroupid
# load invoiceitemid,productid,quantity for all invoicees
# deduct already prepared products (pallet_exit)
# 
# suggest product to prepare (allow skipping this product)
# for quantities <5, scan each box
# for other quantities, yes/no all, if no, enter quantity
# we are NOT checking if sufficient quantity is left on the pallet
#
# continue til complete or exit from invoicegroupid

$PA['placementname'] = 'uint';
$PA['productid'] = 'uint';
$PA['invoicegroupid'] = 'uint';
$PA['productskip'] = 'uint';
$PA['barcode'] = '';
require('inc/readpost.php');

if ($invoicegroupid > 0)
{
  $query = 'select preparationtext,invoicegroupdate,invoicegrouptime,companytransportid,totalweight
  from invoicegroup where invoicegroupid=?';
  $query_prm = array($invoicegroupid);
  require('inc/doquery.php');
  if ($num_results)
  {
    $preparationtext = $query_result[0]['preparationtext'];
    $invoicegroupdate = $query_result[0]['invoicegroupdate'];
    $invoicegrouptime = $query_result[0]['invoicegrouptime'];
    $companytransportid = $query_result[0]['companytransportid'];
    $totalweight = $query_result[0]['totalweight'];
    
    require('preload/companytransport.php');
    echo 'Livraison ',$invoicegroupid,'<br>',datefix($invoicegroupdate,'short'),' ',$invoicegrouptime;
    if ($companytransportid > 0) { echo '<br>',d_output($companytransportA[$companytransportid]); }
    echo '<br>',$totalweight/1000,' kg';
    
    $query = 'select invoiceid from invoicehistory where confirmed=1 and cancelledid=0 and invoicegroupid=?';
    $query_prm = array($invoicegroupid);
    require('inc/doquery.php');
    $in_invoices = '(';
    for ($i=0;$i<$num_results;$i++)
    {
      $in_invoices .= $query_result[$i]['invoiceid'] . ',';
    }
    $in_invoices = rtrim($in_invoices,',') . ')';
    if ($in_invoices == '()') { $in_invoices = '(-1)'; }
    
    $prepareA = array(); $invoiceitemidA = array();
    $in_invoiceitems = '(';
    $query = 'select invoiceitemid,invoiceitemhistory.productid,quantity,productname,netweightlabel,numberperunit
    from invoiceitemhistory,product
    where invoiceitemhistory.productid=product.productid
    and quantity>0 and excludefromdelivery=0 and invoiceid in '.$in_invoices;
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0;$i<$num_results;$i++)
    {
      if (!isset($prepareA[$query_result[$i]['productid']])) { $prepareA[$query_result[$i]['productid']] = $query_result[$i]['quantity']; }
      else { $prepareA[$query_result[$i]['productid']] += $query_result[$i]['quantity']; }
      $in_invoiceitems .= $query_result[$i]['invoiceitemid'] . ',';
      $productnameA[$query_result[$i]['productid']] = $query_result[$i]['productid'].':';
      $productnameA[$query_result[$i]['productid']] .= ' '.d_decode($query_result[$i]['productname']);
      if ($query_result[$i]['numberperunit'] > 1) { $productnameA[$query_result[$i]['productid']] .= ' '.$query_result[$i]['numberperunit'].' x'; }
      $productnameA[$query_result[$i]['productid']] .= ' '.$query_result[$i]['netweightlabel'];
      $npuA[$query_result[$i]['productid']] = $query_result[$i]['numberperunit'];
      $temp_pid = $query_result[$i]['productid'];
      $temp_quantity = floor($query_result[$i]['quantity'] / $query_result[$i]['numberperunit']);
      if (!isset($invoiceitemidA[$temp_pid]))
      {
        $invoiceitemidA[$temp_pid] = array();
      }
      for ($y=1;$y <= $temp_quantity; $y++)
      {
        $invoiceitemidA[$temp_pid][] = $query_result[$i]['invoiceitemid'];
      }
    }
    $in_invoiceitems = rtrim($in_invoiceitems,',') . ')';
    if ($in_invoiceitems == '()') { $in_invoiceitems = '(-1)'; }
    $query = 'select exit_quantity,productid,invoiceitemhistory.invoiceitemid
    from pallet_exit,invoiceitemhistory
    where pallet_exit.invoiceitemid=invoiceitemhistory.invoiceitemid
    and pallet_exit.invoiceitemid in '.$in_invoiceitems;
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0;$i<$num_results;$i++)
    {
      if (!isset($prepareA[$query_result[$i]['productid']])) { $prepareA[$query_result[$i]['productid']] = 0 - $query_result[$i]['exit_quantity']; }
      else { $prepareA[$query_result[$i]['productid']] -= $query_result[$i]['exit_quantity']; }
      if (isset($invoiceitemidA[$temp_pid]))
      {
        $temp_pid = $query_result[$i]['productid'];
        $temp_quantity = floor($query_result[$i]['exit_quantity'] / $npuA[$temp_pid]);
        for ($y=1;$y <= $temp_quantity; $y++)
        {
          # find invoiceitemid in array and remove element
          unset($invoiceitemidA[$temp_pid][array_search($query_result[$i]['invoiceitemid'], $invoiceitemidA[$temp_pid])]);
        }
      }
    }
    $prepareA = array_filter($prepareA, function($temp) { return $temp > 0; });
    # TODO order products somehow

    $quantity = reset($prepareA);
    for ($i=0;$i<$productskip;$i++)
    {
      $quantity = next($prepareA);
    }
    if (!$quantity) { $quantity = reset($prepareA); $productskip = 0; }
    $productid = key($prepareA);

    if ($barcode != '')
    {
      $query = 'select productid,quantity,palletid,placementid
      from pallet,pallet_barcode
      where pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
      and deleted=0 and barcode=?';
      $query_prm = array($barcode);
      require('inc/doquery.php');
      if ($num_results)
      {
        if (isset($prepareA[$query_result[0]['productid']]))
        {
          if ($prepareA[$query_result[0]['productid']]/$npuA[$query_result[0]['productid']] < 5) # hardcode 5, if less than 5 cartons, scan each individually
          {
            $quantity_taken = $npuA[$query_result[0]['productid']];
          }
          else
          {
            # TODO yes/no all?
            #if all, could be several different invoiceitemids (several inserts)
            $quantity_taken = $npuA[$query_result[0]['productid']];
          }
          $productid = $query_result[0]['productid'];
          $quantity = $prepareA[$query_result[0]['productid']] - $quantity_taken;
          $pallet_quantity = $query_result[0]['quantity'] - $quantity_taken; if ($pallet_quantity < 0) { $pallet_quantity = 0; }
          $invoiceitemid = reset($invoiceitemidA[$productid]);
          $palletid = $query_result[0]['palletid'];
          $placementid = $query_result[0]['placementid'];
          
          $query = 'update pallet set quantity=?';
          $query_prm = array($pallet_quantity);
          #if ($pallet_quantity == 0)  { $query .= ',deleted=1'; } # perhaps just set quantity to 0 (needs testing/dev)
          $query .= ' where palletid=?';
          array_push($query_prm,$palletid);
          require('inc/doquery.php');
          
          $query = 'insert into pallet_exit (exit_quantity,invoiceitemid,palletid,placementid,userid,pallet_exitdate,pallet_exittime) values (?,?,?,?,?,curdate(),curtime())';
          $query_prm = array($quantity_taken,$invoiceitemid,$palletid,$placementid,$_SESSION['ds_userid']);
          require('inc/doquery.php');
          
          $prepareA[$productid] -= $quantity_taken;
          $prepareA = array_filter($prepareA, function($temp) { return $temp > 0; });
          if ($quantity <= 0) { $quantity = reset($prepareA); $productid = key($prepareA); }
          
        }
      }
    }
    
    if (isset($productnameA[$productid]))
    {
      echo '<br><br>',d_output($productnameA[$productid]),'<br>Quantité : <span style="font-size: 350%;">';
      if ($npuA[$productid] > 1)
      {
        echo floor($quantity / $npuA[$productid]);
        if ($quantity % $npuA[$productid] > 0) { echo '</span> <span style="font-size: 250%;">',$quantity % $npuA[$productid]; }
      }
      else { echo $quantity; }
      echo '</span>';

      echo '<form method="post" action="reportwindow.php"><input autofocus class="warehouse" type=text name="barcode" size=20><br>
      <input type=hidden name="invoicegroupid" value="'.$invoicegroupid.'"><input type=hidden name="productid" value="'.$productid.'">
      <input type=hidden name="report" value="preparationinterface"><input type="submit" style="position: absolute; left: -9999px"/>
      </form>
      <br><a href="reportwindow.php?report=preparationinterface&invoicegroupid='.$invoicegroupid.'&productskip='.($productskip+1).'">Sauter Produit</a>
      &nbsp; &nbsp; &nbsp; &nbsp;
      <a href="reportwindow.php?report=preparationinterface">Exit</a>';
    }
    else
    {
      echo '<br><br>Livraison terminé';
      $invoicegroupid = 0;
    }
  }
  else { $invoicegroupid = 0; }
}

if ($invoicegroupid == 0)
{
  echo '<form method="post" action="reportwindow.php"><input autofocus class="warehouse" type=text name="invoicegroupid" size=20><br>
  <input type=hidden name="report" value="preparationinterface"><input type="submit" style="position: absolute; left: -9999px"/>
  </form>';
}

?>