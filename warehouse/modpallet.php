<?php

# needs refactoring, have fun sorting out the "steps"

require('preload/placement.php');
require('preload/product.php');

$STEP_FORM_PLACEMENT = 0;
$STEP_LIST_PALLETS = 1;
$STEP_FORM_MODIFY_PALLET = 2;
$STEP_MODIFY_PALLET = 3;

$ds_userid = $_SESSION['ds_userid'];

$palletid = $_POST['palletid']+0;
$currentstep = $_POST['step']+0;

$error = 0; #error from modify pallet screen
$errorform = 0; # error from placement screen

if ($currentstep == $STEP_LIST_PALLETS)   # 2nd screen: list of pallets
{
  $barcode = $_POST['barcode'] . ''; 
  $placementname = $_POST['placementname'] . '';
  
  if ($barcode != '')
  {
    $query = 'select palletid from pallet p,pallet_barcode pb where pb.barcode=? and pb.pallet_barcodeid = p.pallet_barcodeid';
    $query_prm = array($barcode);
    require('inc/doquery.php');
    if ($num_results == 0) # barcode non créé
    { 
      $errorform = 1;
      $currentstep = $STEP_FORM_PLACEMENT; #choose again a placement
    }
    else 
    {
      $palletid = $query_result[0]['palletid'];
      $currentstep = $STEP_FORM_MODIFY_PALLET;
    }
  }
  else if ($placementname != '')
  {
    $query = 'select * from pallet pa, placement pl, pallet_barcode pb where pl.placementid=pa.placementid and pa.pallet_barcodeid = pb.pallet_barcodeid and pl.placementname=? order by pa.pallet_barcodeid';
    $query_prm = array($placementname);
    require('inc/doquery.php');
    if ($num_results > 0)   
    {
      # list pallets with link
      echo '<h2>Liste des palettes de l\'Emplacement : ' . d_output($placementA[$query_result[0]['placementid']]) . '</h2>';
      echo '<table class=report><thead><th>Code-Barre Palette</td><td><b>Produit</td><td><b>Quantité</td><td><b>Date limite</td></tr></thead>';        

      for ($i=0;$i<$num_results;$i++)
      {
        $row = $query_result[$i];
        $href = 'warehouse.php?warehousemenu=' . $warehousemenu . '&palletid=' . $row['palletid'];
        echo '<tr>'; 
        $pallet_barcode = $row['barcode'];
        echo '<td align=right><a href="' . $href . '">'. $pallet_barcode  .'</a></td>'   ;
        
        $productid = $row['productid'];
        $showproductname = $productA[$productid] . ' ' . $product_packagingA[$productid];
        echo '<td><a href="' . $href . '">' . $showproductname .'</a></td>';
        
        if ($product_npuA[$productid]  >= 1)
        { 
          $nbr =  myfix(floor($row['quantity'] / $product_npuA[$productid]) );
          echo '<td align=right><a href="' . $href . '">' . myfix($nbr) .'</a></td>';  
        }
        else
        {
          echo '<td align=right><a href="' . $href . '">' . myfix($row['quantity']) .'</a></td>';  
        }      
        echo '<td align=right><a href="' . $href . '">' . datefix2($row['expiredate']) .'</a></td>';    
        echo '</tr>';
      }
      echo '<input type=hidden name="step" value="' .$STEP_FORM_MODIFY_PALLET . '">';         
      echo '</table>';
    }
    else
    {
      $errorform = 1;
      $currentstep = $STEP_FORM_PLACEMENT; #choose again a placement
    }
  }
  else
  {
    $errorform = 1;
    $currentstep = $STEP_FORM_PLACEMENT; #choose again a placement
  }
}

if ($palletid == 0) 
{ 
  $palletid = $_GET['palletid']+0;
  if ($palletid > 0)
  {
    $currentstep = $STEP_FORM_MODIFY_PALLET;
  }
}

if ($currentstep == $STEP_FORM_MODIFY_PALLET)
{
  #get infos from BDD on this pallet
  $query = 'select * from pallet p, pallet_barcode pb where p.pallet_barcodeid = pb.pallet_barcodeid and p.palletid=?';
  $query_prm = array($palletid);
  require('inc/doquery.php');
  
  if ($num_results > 0)
  {
    $row = $query_result[0];
    $quantity = $row['quantity'];
    $productid = $row['productid'];
    $pallet_barcodeid = $row['pallet_barcodeid'];
    $pallet_barcode = $row['barcode'];
    $warehousereasonid = $row['warehousereasonid'];
    $log_pallet_comment = $row['log_pallet_comment'];
    $supplierbatchname = $row['supplierbatchname'];
    $expiredate = $row['expiredate'];
    $placementid = $row['placementid'];    
    #initialization 
    $from_pallet_barcode = $pallet_barcode;  
    $from_pallet_barcodeid = $pallet_barcodeid;  
    $from_productid = $productid;  
    $from_quantity = $quantity;  
    $from_expiredate = $expiredate;    
  }#else can not happen
}
elseif ($currentstep == $STEP_MODIFY_PALLET )
{
  $quantity = $_POST['quantity']+0; 
  $product = $_POST['product'];
  require('inc/findproduct.php');
  $pallet_barcode = $_POST['pallet_barcode'];
  $warehousereasonid = $_POST['warehousereasonid'];
  $log_pallet_comment = $_POST['log_pallet_comment'];
  $supplierbatchname = $_POST['supplierbatchname'];
  $datename = 'expiredate';$dp_allowempty=1; 
  require('inc/datepickerresult.php');
  $placementid = $_POST['placementid'] +0;
  $from_pallet_barcode = $_POST['from_pallet_barcode'];
  $from_pallet_barcodeid = $_POST['from_pallet_barcodeid'];
  $from_productid = $_POST['from_productid']+0;
  $from_quantity = $_POST['quantity']+0;
  $from_expiredate = $_POST['from_expiredate'];

  # if barcode has changed: controls if pallet_barcodeid exists
  if ($pallet_barcode != $from_pallet_barcode)
  {
    $query = 'select pallet_barcodeid from pallet_barcode where barcode=?'; 
    $query_prm = array($pallet_barcode);
    require('inc/doquery.php');
   
    if ($num_results == 0) 
    {
      #pallet_barcode does not exist
      $error = 1 ; 
    }
    else
    { 
      #controls if pallet_barcode already used by another pallet
      $pallet_barcodeid = $query_result[0]['pallet_barcodeid'];
      $query = 'select pallet_barcodeid from pallet where pallet_barcodeid=? and palletid != ?'; 
      $query_prm = array($pallet_barcodeid,$palletid);
      require('inc/doquery.php');
      if ($num_results > 0) { $error = 2 ; }
    }
  }
  else
  {
    $pallet_barcodeid = $from_pallet_barcodeid;
  }
  
  # controls quantity
  if ($quantity < 0 ) { $error = 3; }
  else
  {
    if ($product_npuA[$productid]  >= 1)
    { 
      $quantity = (int)($quantity); 
      $quantity =  $quantity * $product_npuA[$productid];
    }
  }
  if($error == 0) #update PALLET + LOG_PALLET
  {   
    $query = 'update pallet set pallet_barcodeid=?,productid=?,quantity=?,expiredate=?,supplierbatchname=? where palletid=?';
    $query_prm = array($pallet_barcodeid,(int)$productid,$quantity,$expiredate,$supplierbatchname,$palletid);
    require('inc/doquery.php');

    # HERE insert log
    $query = 'insert into log_pallet (userid,palletid,movestockdate,movestocktime,pallet_barcodeid,productid,quantity,expiredate,warehousereasonid,log_pallet_comment,supplierbatchname) values (?,?,curdate(),curtime(),?,?,?,?,?,?,?)';
    $query_prm = array($ds_userid,$palletid,$pallet_barcodeid,(int)$productid,$quantity,$expiredate,$warehousereasonid,$log_pallet_comment,$supplierbatchname);
    require('inc/doquery.php');    
    echo '<h2>Palette modifiée : ' . d_output($pallet_barcode) . '</h2>';
  }
}

#screen after update
if (($currentstep == $STEP_MODIFY_PALLET) && ($error == 0))
{
  echo '<table class=report><thead><th>Emplacement</th><th>Code-Barre Palette</th><th>Produit</th><th>Quantité</th><th><b>Date limite</th></tr></thead>';       
  echo '<tr><td>' . $placementA[$placementid] .'</td>';
  echo '<td align=rigth>' . $pallet_barcode .'</td>';
      
  $showproductname = $productA[$productid] . ' ' . $product_packagingA[$productid];
  echo '<td>' . $showproductname .'</td>';
  
  
  if ($product_npuA[$productid]  >= 1)
  { 
    $quantity =  $quantity / $product_npuA[$productid]  ;
  }
  echo '<td align=right>' . myfix($quantity) .'</td>';
  
  echo '<td>' . datefix2($expiredate) .'</td>';
  echo '</tr></table>';
  echo '<br>';
}
elseif((($currentstep == $STEP_MODIFY_PALLET) && ($error != 0)) || ($currentstep == $STEP_FORM_MODIFY_PALLET))
{
  echo '<form method="post" action="warehouse.php">';

  $title = 'Modifier palette: ';
  echo '<h2>' . $title;
  if ($error == 1 || $error == 2) 
  { 
    echo d_output($from_pallet_barcode) . '</h2>'; 
  }
  else 
  {
    echo d_output($pallet_barcode) . '</h2>' ; 
  }

  echo '<table class=report>';
  echo '<tr><td>Code-Barre Palette: </td>';
  echo '<td><input autofocus type=text STYLE="text-align:right" name="pallet_barcode" value="' . d_input($pallet_barcode) . '" size=25>';
  if ($error==1) { echo '<span class="alert"> Palette inexistante</span>'; }
  if ($error==2) { echo '<span class="alert"> Palette déjà utilisée</span>'; }    
     
  echo '<tr><td>';
  if ($error == 0) 
  {
    $product = $productid;
  } 
  else 
  { 
    $product = $from_productid ;
  }
  require('inc/selectproduct.php');   
 
  echo '<tr><td>Quantité: <td>';
#  echo '<input  type=text STYLE="text-align:right " name=quantity value="' . d_output($quantity) . '" size=20>';
  if ($product_npuA[$productid] >= 1)
  { 
    $nbr =  floor($quantity / $product_npuA[$productid]);
    echo '<input type=text STYLE="text-align:right " name=quantity value="' . d_input($nbr) . '" size=20>';
  }
  else
  {
    echo '<input  type=text STYLE="text-align:right " name=quantity value="' . d_input($quantity) . '" size=20>';
  }

  if ($error == 3) {echo '<span class="alert"> Quantité >= 0 </span>';}  

  echo '<tr><td>D L V : ' .'<td>' ; 
  $datename = 'expiredate'; $selecteddate = $expiredate; $dp_setempty=1;require('inc/datepicker.php');
  echo '<tr><td>Batchname : ' .'<td><input type=text name=supplierbatchname value="' . $supplierbatchname . '" size=100>';
  
  $dp_itemname = 'warehousereason'; $dp_description = 'Raison de la modification'; $dp_noblank = 1; $dp_selectedid = $warehousereasonid; require('inc/selectitem.php');
  echo '<tr><td>Commentaire de la modification : ' .'<td>' ; 
  echo '<input type=text name=log_pallet_comment value="' . $log_pallet_comment . '" size=100>';
  
  
  echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
  echo '<input type=hidden name="step" value="' .$STEP_MODIFY_PALLET . '">';      
  echo '<input type=hidden name="palletid" value="' . $palletid . '">';
  echo '<input type=hidden name="placementid" value="' . $placementid . '">';
  echo '<input type=hidden name="from_pallet_barcode" value="' . $from_pallet_barcode . '">';
  echo '<input type=hidden name="from_pallet_barcodeid" value="' . $from_pallet_barcodeid . '">';
  echo '<input type=hidden name="from_productid" value="' . $from_productid . '">';
  echo '<input type=hidden name="from_quantity" value="' . $from_quantity . '">';
  echo '<input type=hidden name="from_palletid" value="' . $from_palletid . '">';
  echo '<input type=hidden name="from_expiredate" value="' . $from_expiredate . '">';

  echo '<tr><td colspan="2" align="center">';
  echo '<input type="submit" value="Valider">';
  echo '</table></form>';
}

if (($currentstep == $STEP_FORM_PLACEMENT) || (($currentstep == $STEP_MODIFY_PALLET) && ($error == 0))) #1st screen: choose wich placement
{
  echo '<h2>Correction</h2>';
  if ($errorform == 1) { echo '<p>' . d_trad('noresult') . '</p>'; }
  echo '<form method="post" action="warehouse.php">';
  echo '<table>';
  echo '<tr><td>Code-Barre Palette: <td><input autofocus type=text STYLE="text-align:right" name="barcode" value="" size=10>';    
  echo '<tr><td colspan=2 align=center>ou';
  echo '<tr><td>Emplacement : <td><input autofocus type=text name=placementname size=20>';
  echo '<input type=hidden name="warehousemenu" value="' .$warehousemenu . '">';
  echo '<input type=hidden name="step" value="' .$STEP_LIST_PALLETS . '">';
  echo '<tr><td colspan="2" align="center">';
  echo '<input type="submit" value="Valider">';
  echo '</table></form>';
}
?>
