<?php

$PA['num_images'] = 'uint';
$PA['client'] = 'client';
$PA['product'] = 'product';
require('inc/readpost.php');
if (!isset($productid)) { $productid = 0; }

$method = 'admin';
if (isset($clientsmenu)) { $method = 'clients'; }
if (isset($productsmenu)) { $method = 'products'; }

if ($num_images > 0)
{
  for ($i=0;$i<$num_images;$i++)
  {
    $query = 'update image set imagetext=?,imageorder=? where imageid=?';
    $query_prm = array($_POST['imagetext' . $i],$_POST['imageorder' . $i]+0,$_POST['imageid' . $i]);
    require ('inc/doquery.php');
    if ($_POST['delete' . $i] > 0)
    {
      $query = 'delete from image where imageid=?';
      $query_prm = array($_POST['delete' . $i]);
      require ('inc/doquery.php');
    }
  }
}

if ($clientid < 1 && $productid < 1)
{
  echo '<h2>Images</h2><form method="post" action="',$method,'.php"><table>';
  if ($method != 'products') { echo '<tr><td>'; require('inc/selectclient.php'); }
  if ($method != 'clients') { echo '<tr><td>'; require('inc/selectproduct.php'); }
  echo '</td></tr><tr><td colspan=2>';
  if ($method == 'admin') { echo '<input type=hidden name="adminmenu" value="' . $adminmenu . '">'; }
  elseif ($method == 'clients') { echo '<input type=hidden name="clientsmenu" value="' . $clientsmenu . '">'; }
  elseif ($method == 'products') { echo '<input type=hidden name="productsmenu" value="' . $productsmenu . '">'; }
  echo '<input type="submit" value="Valider"></td></tr></table></form>
  <br><p>Formats conseillés : png, jpeg, pdf';
}

if ($clientid > 0 || (isset($productid) && $productid > 0))
{
	$filename = $_FILES['imagefile']['tmp_name'];
  if (is_uploaded_file($filename))
  {
    $image = file_get_contents($filename);
		$imagetype = pathinfo ( $_FILES['imagefile']['name'], PATHINFO_EXTENSION );

    if ($image)
    {
      if ($clientid > 0)
      {
        $query = 'insert into image (clientid,imagetext,imageorder,image,imagetype) values (?,?,?,?,?)';
        $imor = $_POST['imageorder'] + 0;
        $imagetext = $_POST['imagetext']; if (!isset($imagetext)) { $imagetext = ''; }
        $query_prm = array($clientid,$imagetext,$imor,$image,$imagetype);
      }
      if ($productid > 0)
      {
        $query = 'insert into image (productid,imagetext,imageorder,image,imagetype) values (?,?,?,?,?)';
        $imor = $_POST['imageorder'] + 0;
        $imagetext = $_POST['imagetext']; if (!isset($imagetext)) { $imagetext = ''; }
        $query_prm = array($productid,$imagetext,$imor,$image,$imagetype);
      }
      require ('inc/doquery.php');
    }
  }

  if ($clientid > 0) { echo '<h2>Images client ' . $clientid . ': ' . $clientname . '</h2>'; }
  elseif ($productid > 0) { echo '<h2>Images produit ' . $productcode . ': ' . $productname . '</h2>'; }
  echo '<form enctype="multipart/form-data" method="post" action="',$method,'.php"><table class=report></tr>';
  echo '<tr><td><b>Supprimer</td><td><b>Image</td></tr>';
  if ($clientid > 0)
  {
    $query = 'select imageid,imagetext,imageorder,imagetype from image where clientid=? order by imageorder,imageid';
    $query_prm = array($clientid);
  }
  elseif ($productid > 0)
  {
    $query = 'select imageid,imagetext,imageorder,imagetype from image where productid=? order by imageorder,imageid';
    $query_prm = array($productid);
  }
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
  echo '<tr><td>Ajouter image:</td><td><input name="imagefile" type="file" value="' . $_FILES['imagefile']['name'] . '" size=50></td></tr>';
  echo '<tr><td colspan="7" align="center"><input type=hidden name="num_images" value="' . $num_results . '">';
  if ($clientid > 0)
  {
    echo '<input type=hidden name="client" value="' . $clientid . '">';
  }
  elseif ($productid > 0)
  {
    if ($_SESSION['ds_useproductcode'] == 1) { echo '<input type=hidden name="product" value="' . $productcode . '">'; }
    else { echo '<input type=hidden name="product" value="' . $productid . '">'; }
  }
  echo '<input type=hidden name="adminmenu" value="' . $adminmenu . '"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Valider"></td></tr>';
  echo '</table></form>';
}
?>