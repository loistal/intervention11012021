<?php

$arrivalref = $_POST['arrivalref'];
$seal = $_POST['seal'];
$placementid = $_POST['placementid'];
$placementname = $_POST['placementname'];
$arrivalcomment = $_POST['arrivalcomment'];
$saveme = $_POST['saveme'];

$ok = 0;

echo '<h2>Ouverture conteneur</h2>';

if ($saveme == 1 ) 
{
  if ($arrivalref == "")
  {
    echo '<p class=alert> Veuillez saisir la référence conteneur </p>';
  }
  else
  { 
    $query = 'select distinct shipmentid from purchase where batchname=?'; # TODO review this
    $query_prm = array($arrivalref);
    require('inc/doquery.php');
    if ($num_results > 1 || $num_results == 0 )
    { 
      echo '<p class=alert> Conteneur "' . d_output($arrivalref) . '" indisponible.</p>'; 
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
          echo '<p class=alert> Conteneur: ' .d_output($arrivalref) .' déja fermé.</p>'; 
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
         $arrivalid = 0;
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
      echo '<p span class="alert">Modification ouverture conteneur: ' .d_output($arrivalref) .' enregistrée.';
    }
    else
    {
      $query = 'insert into arrival (shipmentid,arrivalref,arrivalcomment,seal,done,arrivaldate,arrivaltime,userid,warehouseid,placementid) values (?,?,?,?,?,CURDATE(),CURTIME(),?,?,?)';
      $query_prm = array($shipmentid,$arrivalref,$arrivalcomment,$seal,$done,$_SESSION['ds_userid'],$warehouseid,$placementid);
      require('inc/doquery.php');
      $arrivalid = $query_insert_id;
      echo '<p span class="alert">Ouverture conteneur: ' .d_output($arrivalref) .' enregistrée.';;
    } 
    $arrivalref='';
    $seal='';
    $arrivalcomment='';
  }
}
  
echo '<form method="post" action="warehouse.php"> <table>';
echo '<tr><td>Conteneur:<td><input autofocus type="text" STYLE="text-align:right" name="arrivalref" value="'.d_input($arrivalref).'"size=20>';
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
  echo '<input type=hidden name="saveme" value="0">';

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
echo '</table></form>';
?>