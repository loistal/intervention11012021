<?php

require('preload/product.php');
require('preload/placement.php');
require('preload/warehousereason.php');
require('preload/user.php');

$datename = 'startdate';
require('inc/datepickerresult.php');
$datename = 'stopdate';
require('inc/datepickerresult.php');

# boy do we need refactoring
$fromplacementid = -1;
$toplacementid = -1;
$total_product1 = 0;
$product = $_POST['product']; require('inc/findproduct.php');
if (!isset($productid)) { $productid = -1; }

$userid=$_POST['userid']; 

$fromplacementname=$_POST['fromplacementname']; 
if ($fromplacementname != '')
{
  $query = 'select placementid,placementname from placement where placementname=?';
  $query_prm = array($fromplacementname);
  require('inc/doquery.php');
  if ($num_results > 0)
  {
    $fromplacementid=$query_result[0]['placementid']+0;
    //echo '<p> fromplacementid : ' .$fromplacementid;
  }
  else
  {
    echo '<p> Emplacement ' . $fromplacementname . 'non trouvé.';
  }
}

$toplacementname=$_POST['toplacementname']; 
//echo '<p> toplacementname : ' .$toplacementname;
if ($toplacementname != '')
{
  $query = 'select placementid,placementname from placement where placementname=?';
  $query_prm = array($toplacementname);
  require('inc/doquery.php');
  if ($num_results > 0)
  {  
    $toplacementid = $query_result[0]['placementid']+0;
  }
  else
  {
    echo '<p> Emplacement ' . $toplacementname . 'non trouvé.';
  }
}
  
$pallet_barcode = $_POST['pallet_barcode'];

$warehousereasonid = $_POST['warehousereasonid']+0; 
$comment = $_POST['comment']; 

$orderby = $_POST['orderby']; 
$total = 0;
$total_g = 0;
$total_placement = 0;
$total_warehousename = 0;

$query = 'select l.userid,l.fromplacementid,l.toplacementid,l.movestockdate,l.movestocktime,l.productid,l.quantity,l.expiredate,l.warehousereasonid,l.log_pallet_comment,p.barcode';
$query .= ' from log_pallet l,pallet_barcode p where l.pallet_barcodeid = p.pallet_barcodeid';
$query_prm = array();

if ($pallet_barcode != '')
{
  $query .= ' and p.barcode =? ';
  array_push($query_prm,$pallet_barcode);  
}

if ($startdate != '')
{
  $query .= ' and l.movestockdate >=? ';
  array_push($query_prm,$startdate);  
}

if ($stopdate != '')
{
  $query .= ' and l.movestockdate <=?'; 
  array_push($query_prm,$stopdate);
}

if ($fromplacementid > 0)
{ 
  $query .= ' and l.fromplacementid=?';
  array_push($query_prm,$fromplacementid); 
}

if ($toplacementid > 0)
{ 
  $query .= ' and l.toplacementid=?';
  array_push($query_prm,$toplacementid);
}

if ($productid > 0 )
{ 
  $query .= ' and l.productid=?';
  array_push($query_prm,$productid);
}

if ($userid > 0 ) 
{ 
  $query .= ' and l.userid=?';
  array_push($query_prm,$userid); 
}

if ($warehousereasonid > 0 )
{ 
  $query .= ' and l.warehousereasonid=?';
  array_push($query_prm,$warehousereasonid);
}

if ($comment != '' ) 
{ 
  $query .= ' and l.log_pallet_comment LIKE ?';
  array_push($query_prm,'%'.$comment.'%');
}

switch ($orderby)
{
  case "1":
    $query .= ' order by l.productid';
    break;
  case "2":
    $query .= ' order by l.movestockdate and l.movestocktime';
    break;
  default:
    $query .= ' order by l.userid';;
}
 
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
#if ($num_results_main ==0) {echo '<h2>Auncun déplacements ni corrections Palettes trouvé</h2>';}

echo '<h2>Rapport Mouvement et Correction Palette</h2>';
showtitle('Rapport Mouvement et Correction Palette');

if ($fromplacementid > 0 )
{ 
  $fromplacementname = $placementA[$fromplacementid];
  echo '<p>De l\'emplacement : ' .$fromplacementname;
}

if ($toplacementid > 0 )
{ 
  $toplacementname = $placementA[$toplacementid];
  echo '<p>A l\'emplacement : ' .$toplacementname;
}
  
if ($pallet_barcode != '' )
{ 
  echo '<p>Palette : ' .$pallet_barcode;
}  

if ($productid > 0 )
{ 
  echo '<p>' .$productname ; 
}

if ($userid > 0 )
{ 
  echo '<p>Utilisateur : ' . $userA[$userid];
}  

if ($warehousereasonid > 0 ) 
{ 
  echo '<p>Raison : ' . $warehousereasonA[$warehousereasonid];
}  

if ($comment != '' ) 
{
  echo '<p>Commentaire contenant : "' . $comment .'"';
}   

if ($startdate != '' )
{
  echo '<p>Du: ' .datefix2($startdate);
}

if ($stopdate != '')
{
  echo ' au: ' .datefix2($stopdate); 
}

echo '<table class=report><thead><th>Date<th>Heure<th>Utilisateur<th>Code-Barre Palette<th>Produit<th>Quantité<th>D L V<th>Emplacement départ<th>Emplacement d\'arrivée<th>Raison<th>Commentaire</thead>';
for ($i=0;$i<$num_results_main;$i++)
{
  echo d_tr();
  echo d_td_old(datefix2($main_result[$i]['movestockdate']),1);
  
  echo d_td_old($main_result[$i]['movestocktime'],1);
 
  $userid=($main_result[$i]['userid']);
  echo d_td_old($userA[$userid]);
  
  echo d_td_old($main_result[$i]['barcode'],1);
  
  $productid = $main_result[$i]['productid']; 
  if ($productid > 0) { echo d_td_old($productA[$productid] . ' ' . $product_packagingA[$productid]); }
  else { echo d_td_old(); }

  $quan = $main_result[$i]['quantity']; 
  if (isset($product_npuA[$productid])) { $npu = $product_npuA[$productid]; }
  else { $npu = 1; }
  $showquantity2 = 0;
  if ($npu > 0)
  {
    $showquantity1 = myfix(floor($quan/$npu));
    $showquantity2 = $quan % $npu;      
  }
  echo d_td_old(myfix($showquantity1),1); # quantity storage unit
  $total_product1 += (int) $showquantity1;
  if ($showquantity2 > 0) 
  {
   echo ' <font size = -1>' .$showquantity2 .'</font>'; # quantity :  unit remains
   $total_product2 += $showquantity2;
  }
  
  echo d_td_old(datefix2($main_result[$i]['expiredate']),1);

  if (isset($placementA[$main_result[$i]['fromplacementid']]))
  { echo d_td_old($placementA[$main_result[$i]['fromplacementid']]); }
  else { echo d_td_old(); }
  
  if (isset($placementA[$main_result[$i]['toplacementid']]))
  { echo d_td_old($placementA[$main_result[$i]['toplacementid']]); }
  else { echo d_td_old(); }

  if (isset($warehousereasonA[$main_result[$i]['warehousereasonid']]))
  { echo d_td_old($warehousereasonA[$main_result[$i]['warehousereasonid']]); }
  else { echo d_td_old(); }
  
  echo d_td_old($main_result[$i]['log_pallet_comment']);
}  

echo '<tr><td  colspan="3" align=left><b>TOTAUX: ';
echo  '<td align=center>' .myfix($i) ;
echo '<td colspan="7">';
?>