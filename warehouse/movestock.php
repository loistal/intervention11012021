<?php

# TODO delete this file

require('preload/placement.php');
require('preload/product.php'); # TODO do NOT preload product table!
$input = $_POST['input'];

if ($input != '')
{
  $palletid = $_POST['palletid']+0;
  $placementid = $_POST['placementid']+0;
  $frompalletid = $_POST['frompalletid']+0;
  $fromplacementid = $_POST['fromplacementid']+0;
  $execute = 0;
  $reinitialize = 0;
 
  # search for pallet
  $query = 'select p.palletid, p.placementid from pallet p,pallet_barcode pb where pb.pallet_barcodeid = p.pallet_barcodeid and pb.barcode=?';
  if ($_SESSION['ds_systemaccess'] != 1) { $query .= ' and deleted=0'; } # TODO permissions
  $query_prm = array($input);
  require('inc/doquery.php');

  if ($num_results > 0) 
  {
    #input is PALLET
    $palletid = $query_result[0]['palletid'];

    #we already get a Pallet barcode in 1rst input
    if ( $frompalletid > 0 )
    {
      echo '<span class="alert">2 PALETTES SCANNEES : MOUVEMENT ANNULE </span><p>';
      #re-initialization
      $reinitialize = 1;
      $execute = 0;
    }  
    else
    {
      $frompalletid = $palletid;
      $fromplacementid = $query_result[0]['placementid'];
    }
  }
  else # search for emplacement
  {
    $query = 'select placementid from placement where placementname=? and deleted = 0 ';
    $query_prm = array($input);
    require('inc/doquery.php');
    if ($num_results > 0)
    { 
      #we already get a placement in 1rst input
      if ( $placementid > 0 )
      {
        echo '<span class="alert">2 EMPLACEMENTS SCANNES : MOUVEMENT ANNULE</span><p>';
        #re-initialization
        $reinitialize = 1;
        $execute = 0;
      }  
      else
      {
        #input is PLACEMENT
        $placementid = $query_result[0]['placementid'];     
      }        
    }
    else
    {
      #input not found
      echo '<span class="alert">Code scanné inexistant : MOUVEMENT ANNULE </span><p>';
      #reinitialization
      $reinitialize = 1;
      $execute = 0;
    }
  }
  
  if ($palletid > 0 && $placementid > 0) { $execute = 1;}
  
  if ($execute)
  {
    $deleted = 0; 
    if ($placement_deletionzoneA[$placementid] == 1)
    {
      $deleted = 1;
    }
    $query = 'update pallet set placementid=?, deleted=? where palletid=?';
    $query_prm = array($placementid,$deleted,$palletid);
    require('inc/doquery.php');
    

    $query = 'select p.pallet_barcodeid, pb.barcode, p.placementid, p.productid, p.quantity, p.expiredate, numberperunit
    from pallet p, pallet_barcode pb, product
    where p.productid=product.productid and p.pallet_barcodeid = pb.pallet_barcodeid and palletid=?';
    $query_prm = array($palletid);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $row = $query_result[0];
      $pallet_barcodeid = $row['pallet_barcodeid'];
      $barcode = $row['barcode']      ;
      $productid = $row['productid']; 
      $placementid = $row['placementid'];     
      $quantity = $row['quantity']; 
      $expiredate = $row['expiredate'];
      $npu = $row['numberperunit']; if ($npu == 0) { $npu = 1; }
      
      $warehousereasonid = 0;
      $log_pallet_comment = '';
      
      $query = 'insert into log_pallet (userid,palletid,fromplacementid,toplacementid,movestockdate,movestocktime,pallet_barcodeid,productid,quantity,expiredate,warehousereasonid,log_pallet_comment) values (?,?,?,?,curdate(),curtime(),?,?,?,?,?,?)';
      $query_prm = array($_SESSION['ds_userid'],$palletid, $fromplacementid, $placementid,$pallet_barcodeid,$productid,$quantity,$expiredate,$warehousereasonid,$log_pallet_comment);
      require('inc/doquery.php');

      echo '<h2>Palette déplacée :</h2><table class=report><th>Palette<th>Emplacement<th>Produit<th>Quantité<th>Date limite';
      echo '<tr><td>' . d_output($barcode), '<td>', d_output($placementA[$placementid]) ;
      echo '<td>' . d_output($productA[$productid]) .' '. $product_packagingA[$productid];
      echo '<td align=right>' . myfix($quantity/$npu);
      echo '<td>' . datefix2($expiredate);
      echo '</table>';
    }
    else
    {
      echo '<p class=alert>Palette non trouvée</p>';
    }
    $reinitialize = 1;
    $execute = 0;
  }
}

if ($execute == 0 && $reinitialize == 0)
{
  if($palletid > 0 ) #display PALLET to move
  {
    $query = 'select barcode, p.productid, placementid,quantity,expiredate,numberperunit
    from pallet p, pallet_barcode pb, product
    where p.productid=product.productid and p.pallet_barcodeid = pb.pallet_barcodeid and palletid=?';
    $query_prm = array($palletid);
    require('inc/doquery.php');
    
    if ($num_results > 0)
    {
      $row = $query_result[0];
      $npu = $row['numberperunit']; if ($npu == 0) { $npu = 1; }
      echo '<h2>Déplacer cette palette:</h2><table class=report><th>Palette<th>Emplacement<th>Produit<th>Quantité<th>Date limite';
      echo '<tr><td>', d_output($row['barcode']), '<td>', d_output($placementA[$row['placementid']]) ;
      $productid = $row['productid'];
      echo '<td>' . d_output($productA[$productid]) .' '. $product_packagingA[$productid];     
      echo '<td align=right>' , myfix($row['quantity']/$npu);
      echo '<td>' , datefix2($row['expiredate']);
      echo '</table>';
    }
  }
  elseif ($placementid > 0) #display PLACEMENT
  {
    echo '<h2>Déplacer vers Emplacement :</h2><table class=report><th>Emplacement'; 
    echo '<tr><td>' .$placementA[$placementid]  ;
    echo '</table>';
  }
}

if ($reinitialize == 1)
{
  $palletid = 0;
  $placementid = 0;
  $frompalletid = 0;
  $fromplacementid = 0;
}
?>
<form method="post" action="warehouse.php">
<input autofocus class="warehouse" type=text name=input size=20>
<br>
<?php
echo '<input type=hidden name="palletid" value="' . $palletid . '">'; 
echo '<input type=hidden name="placementid" value="' . $placementid . '">'; 
echo '<input type=hidden name="frompalletid" value="' . $frompalletid . '">'; 
echo '<input type=hidden name="fromplacementid" value="' . $fromplacementid . '">'; 
echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
echo '<input type="submit" style="position: absolute; left: -9999px"/>';
echo '</form>';
?>