<?php

$nbrbarcode = $_POST['nbrbarcode']+0;

if ($nbrbarcode > 0)
{
  if ($nbrbarcode > 100) { $nbrbarcode = 100; }
  
  for ($i=0;$i<$nbrbarcode;$i++)
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
    if ($i == 0) { $first = $id; }
  }
  if ($nbrbarcode == 1)
  {
    $displaytext = 'Code barre ' . $id . ' ajouté.';
  }
  else
  {
    $displaytext = 'Codes barres '. $first . ' à ' .$id.' ajoutés.';
  }
  echo '<p><a href="reportwindow.php?report=displaypalletbarcode&startbarcode=' .$first .'&stopbarcode=' .$id .'" target="_blank">' . $displaytext . '</a></p><br>'; # TODO verify link
}

echo '<h2>Création Code barre</h2>';
echo '<form method="post" action="warehouse.php">';
echo '<table>';

echo '<tr><td>Nombre (limite 100): <td><input autofocus type=text STYLE="text-align:right" name="nbrbarcode" size=8>';

echo '<tr><td colspan="2" align="center">';
echo '<input type=hidden name="warehousemenu" value="' . $warehousemenu . '">';
echo '<input type="submit" value="Valider">';

echo '</table></form>';  
?>

 