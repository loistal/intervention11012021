<?php

require('preload/warehouse.php');
require('preload/product.php');

$supplierbatchname = $_POST['supplierbatchname'];
$arrivalref = $_POST['arrivalref'];  
$orderby = $_POST['orderby']; 
$warehouseid = $_POST['warehouseid']; 
$placementname = $_POST['placementname'];
$map = $_POST['map']+0;
$p_idA = array();
$p_idB = array();
#d_debug('map',$map);#affichage variable

$arrivalid = 0; # select conteneur : found first
if ($arrivalref != '' ) 
{
  $query = 'select arrivalid,arrivalref from arrival where arrivalref LIKE ? limit 1';
  $query_prm = array('%'.$arrivalref.'%');
  require('inc/doquery.php');
  if ($num_results)
    {
      $arrivalid=$query_result[0]['arrivalid'];
      $arrivalref=$query_result[0]['arrivalref'];
    } 
  else 
  {
    $arrivalid = -1; #  , if not found, so display all 
  }
  
} 

if ($placementname == "")
{
  $placementid = -1;
}
else
{
  $query = 'select placementid,placementname from placement where placementname=? limit 1';
  $query_prm = array($placementname);
  require('inc/doquery.php');
  if ($num_results)
  {
    $placementid=$query_result[0]['placementid'];
  }
  else { $placementid = -1; }
}
$pallet_barcode = $_POST['pallet_barcode']; 
$product = $_POST['product']; 
require('inc/findproduct.php'); 
$supplierid=$_POST['supplierid']+0; 


$datename = 'startdate'; $dp_allowempty = 1;
require('inc/datepickerresult.php');
$datename = 'stopdate';$dp_allowempty = 1;
require('inc/datepickerresult.php');

$total_product = 0;

$query_prm = array();

$query = 'select warehouse.warehouseid,warehouse.warehousename,pallet.palletid,pallet.arrivalid,pallet.productid,pallet.quantity,pallet.expiredate,pallet.supplierbatchname,
placement.placementid,placement.placementname, placement.warehouseid,pallet_barcode.pallet_barcodeid,pallet_barcode.barcode,pallet.orig_quantity,
product.productname,product.suppliercode,product.numberperunit,product.netweightlabel,product.supplierid,arrivalref
from pallet, placement, warehouse, pallet_barcode,product, arrival
where pallet.placementid = placement.placementid
and warehouse.warehouseid = placement.warehouseid
and pallet.pallet_barcodeid = pallet_barcode.pallet_barcodeid
and pallet.arrivalid = arrival.arrivalid
and product.productid = pallet.productid
and pallet.deleted=0';

if ($warehouseid > 0 ) # select warehouse
{
  $selection =1 ;
  $query .= ' and placement.warehouseid=?'; $query_prm = array($warehouseid);
}

if ($placementid > 0) #select placement
{
  $query .= ' and placement.placementid=?';array_push($query_prm,$placementid);
}

if ($pallet_barcode != '' ) #select pallett
{
  $query .= ' and barcode=?'; array_push($query_prm,$pallet_barcode);
}  

if ($productid > 0 ) # select product
{
  $query .= ' and pallet.productid=?';array_push($query_prm,$productid);
}

if ($supplierid > 0 ) # select fournisseur
{
  $query .= ' and supplierid=?';array_push($query_prm,$supplierid);  
}    
 
if ($supplierbatchname != '' ) # select lot fournisseur
{
  $query .= ' and pallet.supplierbatchname=?';array_push($query_prm,$supplierbatchname);  
}    
 

if ($arrivalid > 0 ) # select conteneur
{
  $query .= ' and pallet.arrivalid=?';array_push($query_prm,$arrivalid);  
}  
  
if ($startdate != '')
{
  $query .= ' and expiredate >=?';
  array_push($query_prm,$startdate);
}

if ($stopdate != '')
{
  $query .= ' and expiredate <=?';
  array_push($query_prm,$stopdate);
}

  
switch ($orderby)
{
  case '1':
    $query .= ' order by productid,expiredate,barcode';
    break;
  case '2':
    $query .= ' order by expiredate,barcode';
    break;
  default:
    $query .= ' order by warehousename,placementname,barcode';;
}
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;



echo '<h2>Rapport Emplacement</h2>';
showtitle('Rapport Emplacement');

if ($warehouseid > 0 )
{ 
  echo '<p>Entrepôt: ' .$warehouseA[$warehouseid] ;
}

if ($placementid > 0 )
{
  echo '<p>Emplacement: ' .$placementname;
}

if ($pallet_barcode != '' )
{
  echo '<p>Code-Barre Palette: ' .$pallet_barcode ;
}

if ($productid > 0 )
{ 
  echo '<p>Produit ' . d_output($productA[$productid]) .' '. $product_packagingA[$productid];

  #echo '<p>' .$productname;
}

if ($supplierid > 0 ) # select fournisseur
{
  $query = 'select clientname,clientcode from client where clientid=?';
  $query_prm = array($supplierid);
  require('inc/doquery.php');
  $clientname = $query_result[0]['clientname'];
  $clientcode = $query_result[0]['clientcode'];
  echo '<p>Fournisseur: ' .$clientname .'(' .$clientcode .')';
} 

if ($supplierbatchname != '' ) # select lot fournisseur
{
  echo '<p>Batchname: ' .$supplierbatchname ;
} 
   
if ($arrivalid > 0 ) # select conteneur 
{
  echo '<p>Conteneur: ' .$arrivalref ;
}    
if ($arrivalid < 0 ) # selecttion not found 
{
  echo '<p>Conteneur: ' .$arrivalref .' (auncun conteneur sélectionné correspondant)';
}    


if ($startdate > 0 ) 
{
echo '<p>DLV du: ' .datefix2($startdate) ; 
}
if ($stopdate > 0 ) 
{
echo '<p>DLV jusqu\'au: ' .datefix2($stopdate); 
}


echo '<table class=report><thead><th>Entrepôt<th>Emplacement<th>Code-Barre Palette<th>Produit<th>Quantité<th>Q. Orig.<th>DLV<th>Conteneur<th>Batchname<th>Fournisseur</thead>';
for ($i=0;$i<$num_results_main;$i++)
{
  
  ###
  array_push($p_idA, $main_result[$i]['placementid']);
  ###
  $warehousename = d_output($main_result[$i]['warehousename']);
  
  
  
  if ($main_result[$i]['warehousename'] == $main_result[$i-1]['warehousename'])
  {
    $warehousename = ''; 
  }  
    
  $placementname = d_output($main_result[$i]['placementname']);
  if ($main_result[$i]['placementname'] == $main_result[$i-1]['placementname'])
  {
    $placementname = ''; 
  }  
  echo d_tr();
  echo d_td_old($warehousename);
  echo d_td_old($placementname);
  echo d_td_old($main_result[$i]['barcode'],1);
  
  $showproductname = d_output(d_decode($main_result[$i]['productname']));
  if ($_SESSION['ds_useproductcode']) { $showproductname .= ' ('.d_output($main_result[$i]['suppliercode']).') '; }
  else { $showproductname .= ' ('.$main_result[$i]['productid'].') '; }
  if ($main_result[$i]['netweightlabel'] != '')
  {
    if ($main_result[$i]['numberperunit'] > 1) { $showproductname .= $main_result[$i]['numberperunit'] . ' x '; }
    $showproductname .= d_output($main_result[$i]['netweightlabel']);
  }
  echo d_td_old($showproductname);
 
  $kladd = floor($main_result[$i]['quantity'] / $main_result[$i]['numberperunit']);
  echo d_td_old(myfix($kladd),1); $total_product += $kladd;
  
  $kladd = floor($main_result[$i]['orig_quantity'] / $main_result[$i]['numberperunit']);
  echo d_td_old(myfix($kladd),1);
  
  echo d_td_old(datefix2($main_result[$i]['expiredate']),1);
  echo d_td($main_result[$i]['arrivalref']);
  echo d_td($main_result[$i]['supplierbatchname']);
  echo d_td($main_result[$i]['supplierid']);
}  
echo '<tr><td  align=left><b>TOTAUX: '.'<td>' .d_td_old(myfix($i),1) .'<td>' .d_td_old(myfix($total_product),1) .'<td colspan=10>';
echo '</table>';

if ($map == 1 && (empty($p_idA) == 0 )) # map + placementid (for select IN not empty)
{
  #var_dump($p_idA); 
  $p_idA = array_unique($p_idA); # all placementid
  $pid_list = implode(",", $p_idA);
  $query = 'select placementid,mapid from placement where placementid IN (' . $pid_list . ') ORDER BY mapid ASC';
  $query_prm = array();
  require('inc/doquery.php');
  $mapid = $query_result[0]['mapid'] ;
  $mapid_current = $query_result[0]['mapid'] ;
  $end = $num_results - 1 ;  
  for ($i=0;$i<$num_results;$i++)
  {
    $mapid = $query_result[$i]['mapid'] ;
    $placementid = $query_result[$i]['placementid'] ;
    
    if ($mapid == $mapid_current)
    {
      array_push($p_idB, $query_result[$i]['placementid']); # placementid order by mapid
    }
    elseif ($mapid != $mapid_current)
    {
      $placementid = implode(",", $p_idB);
      echo '<br><img src="warehouse_map.php?placementid=' .$placementid .'" height="800px" width="600px" alt="image">';
      $mapid_current = $query_result[$i]['mapid'] ;
      $p_idB = array();
      array_push($p_idB, $query_result[$i]['placementid']); # placementid order by mapid
    }
    
    if ($end == $i) #ultime 
    {
      $placementid = implode(",", $p_idB);
      echo '<br><img src="warehouse_map.php?placementid=' .$placementid .'" height="800px" width="600px" alt="image">';
      $p_idA = array();
      $p_idB = array();
    } 
  }
}

?>


