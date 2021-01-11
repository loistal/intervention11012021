<?php

require('preload/placement.php');
require('preload/product.php');
$purchaseid = $_POST['purchaseid']+0;
$shipmentid = $_POST['shipmentid']+0;
$arrivalid = $_POST['arrivalid']+0; 
$arrivalref = $_POST['arrivalref']; 
$placementid = $_POST['placementid']+0;
require ('inc/findproduct.php');
$quantity = $_POST['quantity']+0; 
$first = $_POST['first']+0; 
$id = $_POST['id']+0; 
$step = $_POST['step']+0;
$STEP_FORM_CONTENEUR = 0 ;
$STEP_CTRL_CONTENEUR = 1 ;
$STEP_FORM_PALLET = 2 ;
$STEP_UPDATE_PALLET = 3 ;
$flag_UPDATE_PALLET = $_POST['flag_UPDATE_PALLET']+0; 
$STEP_VALIDATE_PALLET = 4 ;
$flag_VALIDATE_PALLET = $_POST['flag_VALIDATE_PALLET']+0; 
$STEP_PRINT = 5 ;
$save = $_POST['save'];
if($save == d_trad('validate')){$step = 4;}
else if($save == d_trad('update')){$step = 3;}
else if($save == d_trad('print')){$step = 5;}

echo '<h2>Préparation Arrivage</h2>';
if ($step == $STEP_UPDATE_PALLET )
{ 
	$flag_UPDATE_PALLET = 1;
	$_POST['flag_UPDATE_PALLET'] = 1;
	$step = $STEP_FORM_PALLET;
}
if ($step == $STEP_VALIDATE_PALLET )
{ 
	$flag_VALIDATE_PALLET = 1;
	$_POST['flag_VALIDATE_PALLET'] = 1;

	for ($i1=$_POST['first'];$i1<=$_POST['id'];$i1++) #ALL BARCODE
  {
		$query = 'insert into pallet_barcode (userid,barcodedate,barcodetime) values (?,curdate(),curtime())';
    $query_prm = array($_SESSION['ds_userid']);
    require('inc/doquery.php');
    $id = $query_insert_id;
    ###
    # assuming barcode = id for now
    $query = 'update pallet_barcode set barcode=? where pallet_barcodeid=?';
    $query_prm = array($id,$id);
    require('inc/doquery.php');
    ###
	}	
	$stop_line = $_POST['num_results_main'] - 1;
	for ($i=0;$i<=$stop_line;$i++)
		{
			if ($_POST['start_pallet_barcodeid' . $i] > 0)
			{
				$start_pallet = $_POST['start_pallet_barcodeid' . $i];
				$stop_pallet = $_POST['stop_pallet_barcodeid' . $i];
				$pallet_barcodeid = $_POST['start_pallet_barcodeid' . $i];
				for ($i2=$start_pallet;$i2<=$stop_pallet;$i2++) # ALL PRODUCT
				{
					if ($_POST['nbr_stop_pallet' . $i] > 0)
					{
						$quantity = $_POST['nbr_stop_pallet' . $i] * $product_npuA[$_POST['productid' . $i]] ;
						$_POST['nbr_stop_pallet' . $i] = 0;
					}
					else
					{
						$quantity = $_POST['nbr_ti_hi' . $i] * $product_npuA[$_POST['productid' . $i]] ;
					}
					$productid = $_POST['productid' . $i];
					if ($pallet_barcodeid > 0)
					{
						$query = 'insert into pallet (arrivalid,productid,quantity,orig_quantity,placementid,pallet_barcodeid,expiredate,supplierbatchname) values (?,?,?,?,?,?,?,?)';
						$query_prm = array($arrivalid,$productid,$quantity,$quantity,$placementid,$pallet_barcodeid,$_POST['useby'.$i],$_POST['supplierbatchname'.$i]);
						require('inc/doquery.php');
					}
					$pallet_barcodeid++;
				}
			}
		}
	$step = $STEP_FORM_PALLET;
}

if ($step == $STEP_PRINT )
{ 
	echo '<p>';
  $first = $_POST['first'];
	$id = $_POST['id'];
	$displaytext = 'Imprimer Codes Barres de  '. $first . ' à ' .$id;
	echo '<td colspan=5 align=center>';
  echo '<p><a href="reportwindow.php?report=displaypalletbarcode&startbarcode=' .$first .'&stopbarcode=' .$id .'" target="_blank">' . $displaytext . '</a></p><br>';
	
	#$step = $STEP_FORM_CONTENEUR;
}

if ($step == $STEP_CTRL_CONTENEUR )	#STEP_CTRL_CONTENEUR (step = 1)

{
  if ($arrivalref == "")
  {
		$error = 1 ;
  }
  else
  { 
    $query = 'select distinct shipmentid from purchase where batchname=? order by shipmentid desc limit 1';
    $query_prm = array($arrivalref);
    require('inc/doquery.php');
    if ($num_results > 1 || $num_results == 0 )
    { 
			$error = 2 ;
    }
    else
    {
      $shipmentid = $query_result[0]['shipmentid'];  
      $query = 'select done,arrivalid,arrivalcomment,seal from arrival where shipmentid=? and arrivalref=?';
      $query_prm = array($shipmentid,$arrivalref);
      require('inc/doquery.php');
      if ($num_results == 1)
      { 
        if ($query_result[0]['done'] == 1) 
        { 
					$error = 3 ;
        }
        else #update
        {
          $arrivalid = $query_result[0]['arrivalid'];
          $arrivalcomment = $query_result[0]['arrivalcomment'];
          $seal = $query_result[0]['seal'];
          $ok = 1;
        }
      } 
      else if ($num_results == 0) #create
      {
         $ok = 1;
#         $arrivalid = 0;
      }
    }
  }  
  ## CREATE OR UPDATE ARRIVAL
  if ($ok)
  {
    $arrivalcomment = $_POST['arrivalcomment'];
    $seal = $_POST['seal'];
    $done = $_POST['done']+0;
    
    # find warehouse for update arrival.warehouseid
    $query = 'select warehouseid from placement where placementid=?';
    $query_prm = array($placementid);
    require('inc/doquery.php');
    $warehouseid = $query_result[0]['warehouseid'];

    if ($arrivalid > 0 )
    {
      $query = 'update arrival set arrivalcomment=?,seal=?,done=?,warehouseid=?,placementid=?,userid=?,arrivaldate=CURDATE(),arrivaltime=CURTIME() where arrivalid=?';
      $query_prm = array($arrivalcomment,$seal,$done,$warehouseid,$placementid,$_SESSION['ds_userid'],$arrivalid);
      require('inc/doquery.php');
      echo '<p><b>Modification ouverture conteneur: ' .d_output($arrivalref) .' enregistrée.</b>';
    }
    else
    {
      $query = 'insert into arrival (shipmentid,arrivalref,arrivalcomment,seal,done,arrivaldate,arrivaltime,userid,warehouseid,placementid) values (?,?,?,?,?,CURDATE(),CURTIME(),?,?,?)';
      $query_prm = array($shipmentid,$arrivalref,$arrivalcomment,$seal,$done,$_SESSION['ds_userid'],$warehouseid,$placementid);
      require('inc/doquery.php');
      $arrivalid = $query_insert_id;
      echo '<p><b>Ouverture conteneur: ' .d_output($arrivalref) .' enregistrée.</b>';
    } 
  }
	if ($error > 0 )
	{
		$step = 0  ;
		echo '<input type=hidden name="step" value="' . $STEP_FORM_CONTENEUR . '">';
	}		
	if ($error == 0 )
	{
		$step = 2  ;
		echo '<input type=hidden name="step" value="' . $STEP_FORM_PALLET . '">';
	} 
}

# ----------------------------- END STEP_CTRL_CONTENEUR

# -------------------------------------------------------------------------------------------------
if ($step == $STEP_FORM_CONTENEUR )
{
# echo 'STEP_FORM_CONTENEUR';
	echo '<form method="post" action="warehouse.php">';
	echo ' <table>';
	echo '<tr><td>Conteneur:<td><input autofocus type="text" STYLE="text-align:right" name="arrivalref" value="'.d_input($arrivalref).'"size=20>';
	if ($error == 1)
	{
		echo '<p class=alert> Veuillez saisir la référence conteneur </p>';
	}
	if ($error == 2)
	{
      echo '<p class=alert> Conteneur "' . d_output($arrivalref) . '" indisponible.</p>'; 
	}
	if ($error == 3)
	{
    echo '<p class=alert> Conteneur: ' .d_output($arrivalref) .' déja fermé.</p>';
	}
	echo '<tr><td>Scellé:<td><input  type=text STYLE="text-align:right" name=seal value="'.d_input($seal).'" size=20>';
	echo '<tr><td>Commentaire:<td><input type=text STYLE="text-align:left" name=arrivalcomment value="'.d_input($arrivalcomment).'" size=100>';
	echo '<tr><td>Emplacement:<td>';
	$query = 'select placementid,placementname from placement where creationzone=1 and deleted=0';
	$query_prm = array();
	require('inc/doquery.php');
	if ($num_results == 0)
	{ 
		echo ' <span class="alert">Emplacement pour ouverture conteneur à definir</span>'; 
		$error = 101; 
		$arrivalref = '';
		echo '<input type=hidden name="step" value="' . $STEP_FORM_CONTENEUR . '">';
	}
	else 
	{
		echo '<select name="placementid">';
		for ($i=0; $i < $num_results; $i++)
		{
			$selected = '';
			if ($query_result[$i]['placementid'] == $placementid) { $selected = ' SELECTED'; }
			echo '<option value="' . $query_result[$i]['placementid'] . '"' .$selected .' >' . $query_result[$i]['placementname'] . '</option>'; 
		}
	 echo '</select>';
	 echo '<input type=hidden name="saveme" value="1">';

	}
	echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
	echo '<tr><td colspan=2 align=center><input type="submit"  value="Valider">';
	echo '<input type=hidden name="step" value="' . $STEP_CTRL_CONTENEUR . '">';
	echo '</table></form>';
}
#----------------------------------------------------- END STEP_FORM_CONTENEUR (step = 0)

if ($step == $STEP_FORM_PALLET )
{

	if ($_POST['flag_UPDATE_PALLET'] != 1 || $_POST['flag_VALIDATE_PALLET'] != 1)
	{
		$query = 'SELECT pallet_barcodeid FROM pallet_barcode ORDER BY pallet_barcodeid DESC LIMIT 1'; # found the lest 
		$query_prm = array();
		require('inc/doquery.php');
		if ($num_results == 0)
		{
			$pallet_barcodeid = 0;
			$_POST['pallet_barcodeid'] = 0;
		}
		else 
		{
			$pallet_barcodeid = $query_result[0]['pallet_barcodeid'] ;
			$_POST['pallet_barcodeid'] = $query_result[0]['pallet_barcodeid'] ;
		}
	}
	echo '<form method="post" action="warehouse.php">';
	echo '<table>';
	echo '<tr><td>Conteneur: <td>' .$arrivalref;
	echo '<tr><td>Emplacement: <td>' .$placementA[$placementid];
	echo '</table>';

	echo '<table class=report><thead>';
	echo '<td><td>Produit<td>Quantité<td>Libellé Produit<td>TI x HI<td>Nbr de palettes<td>Codes Barres Palettes<td>DLV<td>Batch Code';
	echo '</thead>';

  $query = 'SELECT productid,batchname,amount AS "Total_Product",useby,supplierbatchname,purchaseid FROM purchase
  where batchname=? and shipmentid=?';	
	$query_prm = array($arrivalref,$shipmentid);
	require('inc/doquery.php');
	$main_result = $query_result; $num_results_main = $num_results;
	
	if ($_POST['pallet_barcodeid'] > 0)
	{
		$start_pallet_barcodeid = $_POST['pallet_barcodeid'] + 1;
		$first = $start_pallet_barcodeid;
	}	
	else
	{
		$start_pallet_barcodeid = $_POST['pallet_barcodeid'] + 1;
		$first = $start_pallet_barcodeid;
	}


#=================================================================================================================================
	$line = 1 ;
	for ($i=0; $i < $num_results_main; $i++)
	{
		echo '<tr><td>' .$line;
		#$productid[$i] = $main_result[$i]['productid'];
		
		#$_POST['productid' . $i] = $productid;
		
		$quantity = $main_result[$i]['Total_Product'];
		$productid = $main_result[$i]['productid'];
		if ($product_npuA[$productid] > 0)
		{
			$quantity = ( $quantity / $product_npuA[$productid]);
		}
    echo d_td_old($productid,1);
		echo d_td_old(myfix($quantity),1);
		#$_POST['quantity' . $i] = $quantity;

		$productname = $productA[$productid];
		echo '<td>' .$productname;
		#$_POST['productname' . $i] = $productname;
		
		$nbr_pallet = 0;
		$nbr_stop_pallet = 0;
		
		if ($_POST['nbr_ti_hi' . $i] == 0) 
		{
			$query = 'select ti,hi from product where productid=?';
			$query_prm = array($main_result[$i]['productid']);
			require('inc/doquery.php');
			$ti = $query_result[0]['ti'];
			$hi = $query_result[0]['hi'];
			$nbr_ti_hi = $ti * $hi;
			$_POST['nbr_ti_hi' . $i] = $nbr_ti_hi;
		}
		
		if ($_POST['flag_VALIDATE_PALLET'] == 0)
		{
			echo '<td><input autofocus type="text" STYLE="text-align:right" name="nbr_ti_hi' . $i . '" value="' . d_output($_POST['nbr_ti_hi' . $i]) . '" size=5>';
		}
		else
		{
			echo '<td>' .d_output($_POST['nbr_ti_hi' . $i]) ;
		}
		if ($_POST['nbr_ti_hi' . $i] > 0 )
		{
			$nbr_pallet = myfix(floor($quantity / $_POST['nbr_ti_hi' . $i]));
			$nbr_stop_pallet = $quantity % $_POST['nbr_ti_hi' . $i];
			$_POST['nbr_pallet' . $i] = $nbr_pallet;
			$_POST['nbr_stop_pallet' . $i] = $nbr_stop_pallet;
		}
		else 
		{
			echo '<td>';
		}
		if ($_POST['nbr_ti_hi' . $i] > $quantity) # _POST['quantity' . $i]
		{
			echo 'revoir nombre TIxHI';
			echo '<p class=alert> Nombre TIxHI INFERIEUR à quantité';
		}
		else
		{
			echo d_td_old(d_output($_POST['nbr_pallet' . $i]),1);
		}
		
		if ($_POST['nbr_stop_pallet' . $i] > 0 )
		{
			echo ' + ' . d_output($_POST['nbr_stop_pallet' . $i]) ;
		}
		if ($_POST['nbr_pallet' . $i] > 0)
		{
			$stop_pallet_barcodeid = ($start_pallet_barcodeid + $nbr_pallet) - 1;
			if ($nbr_stop_pallet > 0 )
			{
				$stop_pallet_barcodeid ++;
			}
			
			if ($start_pallet_barcodeid == $stop_pallet_barcodeid) 
			{
				echo '<td>'  .$start_pallet_barcodeid ;
				$id = $start_pallet_barcodeid;
			}
			else
			{
				echo '<td> de ' .$start_pallet_barcodeid .' à ' .$stop_pallet_barcodeid;
				$id = $stop_pallet_barcodeid;
			}
      
      $text = 'de ' .$start_pallet_barcodeid .' à ' .$stop_pallet_barcodeid;
      $query = 'update purchase
      set pallet_list=?
      where purchaseid=?';
      $query_prm = array($text, $main_result[$i]['purchaseid']);
      require('inc/doquery.php');
      
      echo '<td>',datefix($main_result[$i]['useby'],'short');
      echo '<td>',$main_result[$i]['supplierbatchname'];
			echo '<input type=hidden name="start_pallet_barcodeid' . $i . '" value="' . $start_pallet_barcodeid .'">';
			echo '<input type=hidden name="stop_pallet_barcodeid' . $i . '" value="' . $stop_pallet_barcodeid .'">';
			$start_pallet_barcodeid = $stop_pallet_barcodeid + 1;
		}
		echo '<input type=hidden name="id" value="' . $id . '">';
		echo '<input type=hidden name="productid' . $i . '" value="' . $productid .'">';
		echo '<input type=hidden name="quantity' . $i . '" value="' . $quantity .'">';
		echo '<input type=hidden name="productname' . $i . '" value="' . $productname .'">';
		echo '<input type=hidden name="nbr_stop_pallet' . $i . '" value="' . $nbr_stop_pallet .'">';
    echo '<input type=hidden name="useby' . $i . '" value="' . $main_result[$i]['useby'] .'">';
    echo '<input type=hidden name="supplierbatchname' . $i . '" value="' . $main_result[$i]['supplierbatchname'] .'">';
		$line++ ; 
	}
#=================================================================================================================================
	
	echo '<input type=hidden name="arrivalref" value="' . $arrivalref . '">';
	echo '<input type=hidden name="arrivalid" value="' . $arrivalid . '">';
	echo '<input type=hidden name="placementid" value="' . $placementid . '">';
	echo '<input type=hidden name="num_results_main" value="' . $num_results_main . '">';
	echo '<input type=hidden name="pallet_barcodeid" value="' . $pallet_barcodeid . '">';
	echo '<input type=hidden name="start_pallet_barcodeid" value="' . $start_pallet_barcodeid . '">';
	echo '<input type=hidden name="stop_pallet_barcodeid" value="' . $stop_pallet_barcodeid . '">';
	echo '<input type=hidden name="first" value="' . $first . '">';
	echo '<input type=hidden name="flag_UPDATE_PALLET" value="' . $flag_UPDATE_PALLET . '">';
	echo '<input type=hidden name="$flag_VALIDATE_PALLET" value="' . $flag_VALIDATE_PALLET . '">';
	echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
	
	
	if ($flag_VALIDATE_PALLET == 0)
	{
		echo '<tr><td colspan=7 align=center> <input name="save" type="submit" value="' . d_trad('update') . '">';
	}
	if ($_POST['flag_UPDATE_PALLET'] == 1 && $flag_VALIDATE_PALLET == 0)
	{
		echo '<input name="save" type="submit" value="' . d_trad('validate') . '">';
	}
	if ($_POST['flag_VALIDATE_PALLET'] == 1)
	{
	echo '<tr><td colspan=9 align=center> <input name="save" type="submit" value="' . d_trad('print') . '">';
	}
	echo '<input type=hidden name="shipmentid" value='.$shipmentid.'></table></form>';
}
?>
