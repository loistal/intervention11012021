<?php

require('preload/placement.php');
$purchaseid = $_POST['purchaseid']+0;
$shipmentid = $_POST['shipmentid']+0;
$arrivalid = $_POST['arrivalid']+0;
$arrivalref = $_POST['arrivalref'];
$placementid = $_POST['placementid']+0;
$barcode = $_POST['barcode'];
$palletid = $_POST['palletid'];
$product = $_POST['product'];
require ('inc/findproduct.php');
$numberperunit = $product_npu; #from findproduct.php
$quantity = $_POST['quantity']+0;
$step = $_POST['step']+0;
$purchaseid = $_POST['purchaseid'];
$STEP_CHOOSE_ARRIVAL = 0 ;
$STEP_FORM_PALLET = 1 ;
$STEP_CREATE_PALLET = 2 ;
$STEP_MODIFY_PALLET = 3 ;

$datename = 'expiredate'; require('inc/datepickerresult.php');
$error = 0; 

echo '<h2>DEPOTAGE (mise en palettes)</h2>';
if ($step == $STEP_CREATE_PALLET )
{
  $query = 'select pallet_barcodeid from pallet_barcode where barcode=? ';
  $query_prm = array($barcode);
  require('inc/doquery.php');
  if ($num_results == 0) # barcode non créé
  { 
    $error = 1; 
  }
  else #ctrl barcodeid and pallet
  {
    $pallet_barcodeid = $query_result[0]['pallet_barcodeid'];
    $query = 'select palletid,arrivalid from pallet where pallet_barcodeid=? ';
    $query_prm = array($pallet_barcodeid);
    require('inc/doquery.php');
    if ($num_results > 0 ) 
    { 
      if ($arrivalid != $query_result[0]['arrivalid']) #pallet from an another arrival
      {
        $error = 6;        
      }
      else #  pallet used 
      { 
        $step = $STEP_MODIFY_PALLET ; 
        $palletid = $query_result[0]['palletid'] ;
      }
    } #else creation     
 
    #check if arrival is done or not
    $query = 'select done,placementid from arrival where arrivalid=?';
    $query_prm = array($arrivalid);
    require('inc/doquery.php');
    $done = $query_result[0]['done'] ;
    $placementid = $query_result[0]['placementid'] ;
    if ($done == 1 )
    {
      $error = 7;  
    }
    else
    {
      #check if placement is available or not    
      $query = 'select creationzone,deleted  from placement where placementid=?';
      $query_prm = array($placementid);
      require('inc/doquery.php');
      $creationzone = $query_result[0]['creationzone'] ;
      $deleted = $query_result[0]['deleted'] ;
      if ($creationzone == 0 || $deleted == 1)
      {
        #not an available placement
        $error = 8;   
      }
    }
      
    if ($error == 0)
    {
      #control input data
      if ($quantity <= 0) 
      { 
        $error = 3; 
      }
      elseif ($productid <= 0) 
      { 
        $error = 4; 
      }
      elseif ($placementid < 1) 
      { 
        $error = 5; 
      }
      else
      {
        $query = 'select purchaseid,supplierbatchname,useby from purchase where purchaseid=?';
        $query_prm = array($purchaseid);
        require('inc/doquery.php'); 
        $supplierbatchname = $query_result[0]['supplierbatchname'] ;
        $useby = $query_result[0]['useby'] ;
        $expiredate = $useby; # data from purchase (not input)
        
        $quantity = (int)($quantity);
        $quantity = $quantity * $numberperunit;
        if ($step == $STEP_CREATE_PALLET ) 
        {  
          $query = 'insert into pallet (arrivalid,productid,quantity,orig_quantity,expiredate,placementid,pallet_barcodeid,supplierbatchname) values (?,?,?,?,?,?,?,?)';
          $query_prm = array($arrivalid,$productid,$quantity,$quantity,$expiredate,$placementid,$pallet_barcodeid,$supplierbatchname);
          require('inc/doquery.php');
          echo '<p span class="alert">Palette ' . d_output($barcode) . ' enregistrée.</p>';
        }
        else 
        {
          $query = 'update pallet set arrivalid=?,productid=?,quantity=?,expiredate=?,placementid=?,pallet_barcodeid=?,supplierbatchname=? where palletid=?';
          $query_prm = array($arrivalid,$productid,$quantity,$expiredate,$placementid,$pallet_barcodeid,$supplierbatchname,$palletid);
          require('inc/doquery.php');
          echo '<p span class="alert">Palette ' . d_output($barcode) . ' modifiée.</p>';
        }
        
        # recherche warehouse pour maj arrival 
        $query = 'update arrival set warehouseid= (select warehouseid from placement where placementid = ?) where arrivalid=?';
        $query_prm = array($placementid,$arrivalid);
        require('inc/doquery.php');


        #display a blank form to allow creation after update or creation
        $quantity = 0 ;
        $productid = 0 ;
        $product = '' ;
        $expiredate = 0 ;
        $barcode = 0;
      }
    }
  } 
}  
else 
{
  if ($step ==  $STEP_CHOOSE_ARRIVAL ) # select conteneur
  {
    $query = 'select arrivalid,arrivalref,shipmentid from arrival where done=0';
    $query_prm = array();
    require('inc/doquery.php');  
    echo '<form method="post" action="warehouse.php"><table>';
    if ($num_results == 0 )
    {
      echo '<td><span class="alert"> Il faut d\'abord ouvrir un conteneur</span>'; 
      echo '<input type=hidden name="step" value="' . $STEP_CHOOSE_ARRIVAL . '">';
    }
    else
    {
      echo '<tr><td>Conteneur:<td><select name="arrivalid">';    
      for ($i=0; $i < $num_results; $i++)
      {
        echo '<option value=' . $query_result[$i]['arrivalid'] . '>' .$query_result[$i]['arrivalref'] . '</option>';
      }
      echo '</select>';   
      echo '<input type=hidden name="step" value="' . $STEP_FORM_PALLET . '">';    
      
    } 
    echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
    echo '<tr><td align=center><td><input type="submit"  value="Valider">';
    echo '</table></form>';
  }
  else             # display pallet
  {
    $query = 'select arrivalref,placementid from arrival where arrivalid=?'; 
    $query_prm = array($arrivalid);
    require('inc/doquery.php');
    $arrivalref = $query_result[0]['arrivalref'] ;
    $placementid = $query_result[0]['placementid'] ;
  }
}

if ($step !=  $STEP_CHOOSE_ARRIVAL )
{
  echo '<form method="post" action="warehouse.php"> <table>';
  echo '<tr><td>Conteneur:</td><td>' . d_output($arrivalref) .'</td></tr>';
  echo '<tr><td>Emplacement:</td><td>' .d_output($placementA[$placementid]) . '</td></tr>';

  if ($barcode == 0) { $barcode = ''; }
  echo '<tr><td>Code-Barre Palette: <td><input autofocus type=text STYLE="text-align:right" name="barcode" value="' . d_input($barcode) . '" size=10>';
  switch ($error)
  {
    case 1:
      echo '<span class="alert"> Palette inexistante</span>';
    break;
    case 6:
      echo '<span class="alert"> Palette d\'un autre conteneur</span>'; 
    break;
    case 7:
      echo '<span class="alert"> Conteneur déjà vidé </span>';  
    break;
    case 8:
      echo '<span class="alert"> Cette palette n\'est plus en zone d\'arrivage </span>';  
    break;
    default:
  }
  echo '<tr><td>'; 
  if ($productid > 0)
  {
    require('inc/selectproduct.php');
  }
  else
  {
    echo d_trad('product:') . '</td><td><input type=text name=product></td></tr>';
  }
  if ($error == 4) {echo '<span class="alert"> Produit non renseigné</span>';}
  

  if ($quantity == 0) { $quantity = ''; }
  echo '<tr><td>Quantité: <td><input type=text STYLE="text-align:right" name="quantity" value="' . d_input($quantity) . '" size=10>';
  if ($error == 3)  
  {
    echo '<span class="alert"> Quantité > 0</span>';
  }
  
  $query = 'select arrivalid,shipmentid from arrival where arrivalid=?';
  $query_prm = array($arrivalid);
  require('inc/doquery.php');  
  $shipmentid = $query_result[0]['shipmentid'] ;

  $query = 'select purchaseid,shipmentid,supplierbatchname,useby from purchase where shipmentid=?';
  $query_prm = array($shipmentid);
  require('inc/doquery.php'); 
  echo '<tr><td> Batchcode: <td><select name="purchaseid">';

  for ($i=0; $i < $num_results; $i++)
  {
    $supplierbatchname = $query_result[$i]['supplierbatchname'] ; 
    $useby = $query_result[$i]['useby'] ; 
    if ($query_result[$i]['purchaseid'] == $purchaseid) { $selected = ' SELECTED'; }
    echo '<option value="' . $query_result[$i]['purchaseid'] . '" ' .$selected .' >' . $query_result[$i]['supplierbatchname'] .' (' . datefix2($query_result[$i]['useby']) .') </option>'; 
  }
  echo '</select>';  
  
  if ($error < 100)
  {
    echo '<input type=hidden name="step" value="' . $STEP_CREATE_PALLET . '">';
    echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
    echo '<input type=hidden name="arrivalid" value="' . $arrivalid . '">';
    echo '<input type=hidden name="arrivalref" value="' . $arrivalref . '">';
    echo '<input type=hidden name="palletid" value="' . $palletid . '">';
    echo '<input type=hidden name="shipmentid" value="' . $shipmentid . '">';
    echo '<tr><td colspan=2 align=center><input type="submit" value="Valider">';
  }
  echo '</table></form>';
}
?>