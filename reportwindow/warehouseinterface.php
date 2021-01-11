<style type="text/css">
body {
  font-weight: bold;
  font-size: 300%;
}
</style>

<?php

# needs refactor

$showmap = 1; # display map

require('preload/placement.php');
require('preload/product.php'); # TODO do NOT preload product table!

$PA['input'] = '';
$PA['input2'] = '';
$PA['palletid'] = 'int';
$PA['placementid'] = 'int';
require('inc/readpost.php');

$showsearch = 1;

if ($input != '')
{
  if ($palletid == 0)
  {
    $query = 'select p.palletid, p.placementid from pallet p,pallet_barcode pb where pb.pallet_barcodeid = p.pallet_barcodeid and pb.barcode=?';
    $query .= ' and deleted=0';
    $query_prm = array($input);
    require('inc/doquery.php');
    if ($num_results) 
    {
      $palletid = $query_result[0]['palletid'];
    }
  }
  
  if ($placementid == 0)
  {
    $query = 'select placementid from placement where placementname=? and deleted=0';
    $query_prm = array($input);
    require('inc/doquery.php');
    if ($num_results)
    { 
      $placementid = $query_result[0]['placementid'];        
    }
  }
  
  if ($palletid > 0 && $placementid > 0)
  {   
    $query = 'select p.pallet_barcodeid, pb.barcode, p.placementid, p.productid, p.quantity, p.expiredate, numberperunit
    from pallet p, pallet_barcode pb, product
    where p.productid=product.productid and p.pallet_barcodeid = pb.pallet_barcodeid and palletid=? and p.deleted=0';
    $query_prm = array($palletid);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $row = $query_result[0];
      $pallet_barcodeid = $row['pallet_barcodeid'];
      $barcode = $row['barcode']      ;
      $productid = $row['productid']; 
      $fromplacementid = $row['placementid'];     
      $quantity = $row['quantity']; 
      $expiredate = $row['expiredate'];
      $npu = $row['numberperunit']; if ($npu == 0) { $npu = 1; }
    }
    
    $warehousereasonid = 0;
    $log_pallet_comment = '';
    
    $deleted = 0;
    if ($placement_deletionzoneA[$placementid] == 1) 
    {
      $deleted = 1;
    }
    $query = 'update pallet set placementid=?, deleted=? where palletid=?';
    $query_prm = array($placementid,$deleted,$palletid);
    require('inc/doquery.php');
    
    $query = 'insert into log_pallet (userid,palletid,fromplacementid,toplacementid,movestockdate,movestocktime,pallet_barcodeid,productid,quantity,expiredate,warehousereasonid,log_pallet_comment) values (?,?,?,?,curdate(),curtime(),?,?,?,?,?,?)';
    $query_prm = array($_SESSION['ds_userid'],$palletid, $fromplacementid, $placementid,$pallet_barcodeid,$productid,$quantity,$expiredate,$warehousereasonid,$log_pallet_comment);
    require('inc/doquery.php');

    echo '<h2>Palette déplacée :</h2><table class=report><th>Palette<th>Emplacement<th>Produit<th>Quantité<th>Date limite';
    echo '<tr><td>' . d_output($barcode), '<td>', d_output($placementA[$placementid]) ;
    echo '<td>' . d_output($productA[$productid]) .' '. $product_packagingA[$productid];
    echo '<td align=right>' . myfix($quantity/$npu);
    echo '<td>' . datefix2($expiredate);
    echo '</table><br>';

    $palletid = 0; $placementid = 0;
  }
}

if ($palletid > 0 && $placementid == 0)
{
  $query = 'select barcode, p.productid, placementid,quantity,expiredate,numberperunit
  from pallet p, pallet_barcode pb, product
  where p.productid=product.productid and p.pallet_barcodeid = pb.pallet_barcodeid and palletid=?';
  $query_prm = array($palletid);
  require('inc/doquery.php');
  
  if ($num_results > 0)
  {
    $showsearch = 0;
    $row = $query_result[0];
    $npu = $row['numberperunit']; if ($npu == 0) { $npu = 1; }
    echo '<h2>Déplacer cette palette:</h2><table class=report><th>Palette<th>Emplacement<th>Produit<th>Quantité<th>Date limite';
    echo '<tr><td>', d_output($row['barcode']), '<td>', d_output($placementA[$row['placementid']]);
    $productid = $row['productid'];
    echo '<td>' . d_output($productA[$productid]) .' '. $product_packagingA[$productid];     
    echo '<td align=right>' , myfix($row['quantity']/$npu);
    echo '<td>' , datefix2($row['expiredate']);
    echo '</table><br>';
    
    ### find recommended location
    if ($_SESSION['ds_mywarehouseid'] > 0)
    {
      $query = 'select pallet.placementid from pallet,placement
      where pallet.placementid=placement.placementid
      and transportzone=0 and productid=? and warehouseid=? and expiredate is not null and pallet.placementid<>?
      order by ABS( DATEDIFF( expiredate, ? ) ),palletid limit 1';
      $query_prm = array($productid, $_SESSION['ds_mywarehouseid'],$row['placementid'],$row['expiredate']);
    }
    else
    {
      $query = 'select pallet.placementid from pallet,placement
      where pallet.placementid=placement.placementid
      and transportzone=0 and productid=? and expiredate is not null and pallet.placementid<>?
      order by ABS( DATEDIFF( expiredate, ? ) ),palletid limit 1';
      $query_prm = array($productid,$row['placementid'],$row['expiredate']);
    }
    require('inc/doquery.php');
    if($num_results)
    {
      echo 'Suggestion : '.d_output($placementA[$query_result[0]['placementid']]);
      echo '<br><br>';
    }
    
    ###
  }
}

if ($placementid > 0 && $palletid == 0)
{
  $showsearch = 0;
  echo '<h2>Déplacer vers Emplacement :</h2><table class=report><th>Emplacement'; 
  echo '<tr><td>' .$placementA[$placementid]  ;
  echo '</table><br>';
}

if ($input2 != '') 
{
  $product = $input2;
  require('inc/findproduct.php');
  if($num_products != 1)
  {
    echo '<br><br><br><p><h2>Aucun Produit trouvé :  ' . d_output($product) .'</h2><br>'; 
  }
  else
  {
    if ($_SESSION['ds_mywarehouseid'] > 0)
    {
      $query = 'select pallet.placementid,barcode,expiredate from pallet,placement,pallet_barcode
      where pallet.placementid=placement.placementid and pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
      and transportzone=0 and productid=? and warehouseid=? and pallet.deleted=0 and quantity>0
      and expiredate is not null order by expiredate,palletid limit 3';
      $query_prm = array($productid, $_SESSION['ds_mywarehouseid']);
    }
    else
    {
      $query = 'select pallet.placementid,barcode,expiredate from pallet,placement,pallet_barcode
      where pallet.placementid=placement.placementid and pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
      and transportzone=0 and productid=? and pallet.deleted=0 and quantity>0
      and expiredate is not null order by expiredate,palletid limit 3';
      $query_prm = array($productid);
    }
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      echo '<br><br><br><h2>Aucun emplacement trouvé pour ce produit:<br><br>' . d_output($productname) .'</h2><br>'; 
    }
    else
    {
      for ($i=0; $i < $num_results; $i++)
      {
        echo '<table><tr><td><h2>' . $productname .'</h2>';
        echo '<tr><td><h2>Emplacement: ' .d_output($placementA[$query_result[$i]['placementid']]) .'</h2>';
        echo '<tr><td><h2>Palette: ' .d_output($query_result[$i]['barcode']);
        if ($query_result[0]['expiredate'] != NULL) { echo ' &nbsp; ' . datefix($query_result[$i]['expiredate'], 'short'); }
        echo '</h2></table><br>';
      }
    }
  }
}
else { echo '<br><br><br><br><br>'; }

$autofocus = 1; if ($input2 != '' && $input == '') { $autofocus = 2; }

#echo 'palletid=',$palletid,'<br>';
#echo 'placementid=',$placementid,'<br>';

if ($palletid > 0) { echo 'Emplacement:'; }
elseif ($placementid > 0) { echo 'Palette:'; }
else { echo 'Déplacer (palette ou emplacement):'; }
echo '<br><form method="post" action="reportwindow.php"><input';
if ($autofocus == 1) { echo ' autofocus'; }
echo ' class="warehouse" type=text name=input size=20><br>';
echo '<input type=hidden name="palletid" value="' . $palletid . '">';
echo '<input type=hidden name="placementid" value="' . $placementid . '">'; 
echo '<input type=hidden name="report" value="warehouseinterface">';
echo '<input type="submit" style="position: absolute; left: -9999px"/>';
echo '</form>';
if ($showsearch)
{
  echo '<br><br>Rechercher produit:<br><form method="post" action="reportwindow.php">';
  echo '<table>';
  echo '<input';
  if ($autofocus == 2) { echo ' autofocus'; }
  echo ' class="warehouse" type=text name=input2 size=20>';
  echo '<br>';
  echo '<input type=hidden name="report" value="warehouseinterface">';
  echo '<input type="submit" style="position: absolute; left: -9999px"/>';
  echo '</table></form>';
}
if ($placementid > 0 && $showmap == 1)
{
  echo '<br><br><img src="warehouse_map.php?placementid=' .$placementid .'" height="800px" width="600px">';
}


?>