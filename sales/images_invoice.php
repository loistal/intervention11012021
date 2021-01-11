<?php

$PA['num_images'] = 'uint';
$PA['invoiceid'] = 'uint';
$PA['imageorder'] = 'uint';
$PA['imagetext'] = '';
require('inc/readpost.php');

if ($num_images > 0)
{
  for ($i=0;$i<$num_images;$i++)
  {
    $query = 'update image set imagetext=?,imageorder=? where imageid=?';
    $query_prm = array($_POST['imagetext' . $i],$_POST['imageorder' . $i]+0,$_POST['imageid' . $i]);
    require ('inc/doquery.php');
    if (isset($_POST['delete' . $i]) && $_POST['delete' . $i] > 0)
    {
      $query = 'delete from image where imageid=?';
      $query_prm = array($_POST['delete' . $i]);
      require ('inc/doquery.php');
    }
  }
}

if ($invoiceid < 1)
{
  echo '<h2>Attacher Images aux Factures</h2><form method="post" action="sales.php"><table>';
  echo '<tr><td>Facture:<td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10>';
  echo '</td></tr><tr><td colspan=2>';
  echo '<input type=hidden name="salesmenu" value="' . $salesmenu . '">';
  echo '<input type="submit" value="Valider"></td></tr></table></form>
  <br><p>Formats recommandés : png, jpeg, pdf';
}
else
{
  if (isset($_FILES['imagefile']['tmp_name']))
  {
    $filename = $_FILES['imagefile']['tmp_name'];
    if (is_uploaded_file($filename))
    {
      $image = file_get_contents($filename);
      $imagetype = pathinfo ( $_FILES['imagefile']['name'], PATHINFO_EXTENSION );		

      if ($image)
      {
        $query = 'insert into image (invoiceid,imagetext,imageorder,image,imagetype) values (?,?,?,?,?)';
        $query_prm = array($invoiceid,$imagetext,$imageorder,$image,$imagetype);
        require ('inc/doquery.php');
      }
    }
  }

  echo '<h2>Images facture ' . $invoiceid . '</h2>';
  echo '<form enctype="multipart/form-data" method="post" action="sales.php"><table class=report></tr>';
  echo '<tr><td><b>Supprimer</td><td><b>Image</td></tr>';
  $query = 'select imageid,imagetext,imageorder,imagetype from image where invoiceid=? order by imageorder,imageid';
  $query_prm = array($invoiceid);
  require ('inc/doquery.php');
  for ($i=0;$i<$num_results;$i++)
  {
    echo '<tr><td> &nbsp; <input type=hidden name="imageid' . $i . '" value="' . $query_result[$i]['imageid'] . '"><input type="checkbox" name="delete' . $i . '" value="' . $query_result[$i]['imageid'] . '"><td>';
    if ($query_result[$i]['imagetype'] == 'pdf')
    {
      echo '<object type="text/html" codetype="application/pdf" data="viewpdf.php?image_id=' . $query_result[$i]['imageid'] . '" width="100%" height="300px"></object>';
    }
    elseif ($query_result[$i]['imagetype'] == 'xlsx')
    {
      echo '<object type="text/html" codetype="application/xlsx" data="viewpdf.php?image_id=' . $query_result[$i]['imageid'] . '" width="100%" height="300px"></object>';
    }
    else
    {
      echo '<img src="viewimage.php?image_id=' . $query_result[$i]['imageid'] . '">';
    }
    echo '<tr><td>Description:<td><input type="text" name="imagetext' . $i . '" value="' . $query_result[$i]['imagetext'] . '" size=50>
    <tr><td>Rangement:<td><input type="number" style="text-align:right" min=0 max=10000 name="imageorder' . $i . '" value="' . $query_result[$i]['imageorder'] . '" size=8></td></tr>';
  }
  echo '<tr><td colspan=2>&nbsp;';
  echo '<tr><td>Ajouter image:</td><td><input name="imagefile" type="file"';
  if (isset($filename)) { echo ' value="' . $_FILES['imagefile']['name'] . '"'; }
  echo ' size=50></td></tr>';
  echo '<tr><td colspan="7" align="center"><input type=hidden name="num_images" value="' . $num_results . '">';
  echo '<input type=hidden name="invoiceid" value="' . $invoiceid . '">';
  echo '<input type=hidden name="salesmenu" value="' . $salesmenu . '"><input type="submit" value="Valider"></td></tr>';
  echo '</table></form>';
}
?>