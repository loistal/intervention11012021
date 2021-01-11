<?php

$arrivalid = $_POST['arrivalid'] +0;
$seal = $_POST['seal'];
$arrivalcomment = $_POST['arrivalcomment'];
$saveme = $_POST['saveme']+0;
$ds_userid = $_SESSION['ds_userid'];

echo '<h2>Fermeture de conteneur</h2>';
if ($saveme == 1)        
{
  $done = 1;
  $query = 'update arrival set arrivalcomment=?,seal=?,done=?,userid=?,arrivaldate=CURDATE(),arrivaltime=CURTIME() where arrivalid=?';
  $query_prm = array($arrivalcomment,$seal,$done,$ds_userid,$arrivalid);
  require('inc/doquery.php');
  
  $query = 'select arrivalref from arrival where arrivalid=?';
  $query_prm = array($arrivalid);
  require('inc/doquery.php');
  if ($num_results > 0)
  {
    echo '<p class=alert> Conteneur: '.$query_result[0]['arrivalref'] .' fermé.';
    $arrivalid = 0;
  }
}       

echo '<form method="post" action="warehouse.php"> <table>';

if ($arrivalid == 0)
{
  $query = 'select arrivalid,shipmentid,arrivalref,seal,arrivalcomment from arrival where done=0'; #selection arrivages ouverts
  $query_prm = array();
  require('inc/doquery.php');
  echo '<tr><td>Conteneur:<td><select name="arrivalid">';  
  for ($i=0; $i < $num_results; $i++)
  {
    echo '<option value=' . $query_result[$i]['arrivalid'] . '>' .$query_result[$i]['arrivalref'] . '</option>';
  }
}
else
{
  $query = 'select arrivalref,seal,arrivalcomment from arrival where done=0 and arrivalid=?';
  $query_prm = array($arrivalid);
  require('inc/doquery.php');
    
  echo '<tr><td>Conteneur:<td>' .d_output($query_result[0]['arrivalref']) ;

  $seal = $query_result[0]['seal']; 
  $arrivalcomment = $query_result[0]['arrivalcomment']; 

  echo '<tr><td>Scellé:<td><input type=text STYLE="text-align:left" name=seal value="'.d_input($seal) .'" size=20>';
  
  echo '<tr><td>Commentaire:<td><input type=text STYLE="text-align:" name=arrivalcomment value="'.d_input($arrivalcomment).'" size=100>';
  echo '<input type=hidden name="saveme" value="1">';
  echo '<input type=hidden name="arrivalid" value="' . $arrivalid . '">';
}  
echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
echo '<tr><td colspan= 2 align=center><input type="submit"  value="Valider">';
echo '</table></form>';
?>