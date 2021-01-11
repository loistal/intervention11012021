<?php

$PA['product'] = '';
$PA['competitorid'] = 'int';
$PA['priceinfo'] = '';
$PA['actionname'] = '';
$PA['productactionfield1'] = '';
$PA['actiondate'] = 'date';
$PA['productactiontagid'] = 'uint';
require('inc/readpost.php');

require ('inc/findproduct.php');

if (isset($productid) && $productid > 0) #  && $_POST['actionname'] != ''
{
  $query = 'insert into productaction (productactiontagid,competitorid,productid,actiondate,employeeid,productactioncatid,actionname,userid,productactionfield1,priceinfo) values (?,?,?,?,?,?,?,?,?,?)';
  $query_prm = array($productactiontagid, $competitorid, $productid, $actiondate, $_POST['employeeid'], $_POST['productactioncatid'], $actionname, $_SESSION['ds_userid'], $productactionfield1, $priceinfo);
  require('inc/doquery.php');
  if ($num_results) { echo '<p>Évènement ajouté pour produit ' . $productid . '.</p>'; $productactionid = $query_insert_id; }
  
  $filename = $_FILES['imagefile']['tmp_name'];
  if ($productactionid && is_uploaded_file($filename))
  {
    $image = file_get_contents($filename);
		$imagetype = pathinfo ( $_FILES['imagefile']['name'], PATHINFO_EXTENSION);		

    if ($image)
    {
      $query = 'insert into image (image,imagetype) values (?,?)';
      $query_prm = array($image,$imagetype);
      require ('inc/doquery.php');
      if ($num_results)
      {
        $imageid = $query_insert_id;
        echo '<p>Image ajouté.</p>';
        $query = 'update productaction set imageid=? where productactionid=?';
        $query_prm = array($imageid, $productactionid);
        require ('inc/doquery.php');
      }
    }
  }
  echo '<br>';
}


?>
<h2>Évènement</h2>
<form enctype="multipart/form-data" method="post" action="products.php">
<table><tr><td>
<?php
require ('inc/selectproduct.php');
?>
</td></tr>
<tr><td>Date:</td><td><?php
$datename = 'actiondate'; #$dp_datepicker_min = $_SESSION['ds_curdate'];
require('inc/datepicker.php');
?></td></tr>
<tr><td>Employé(e):</td>
<td><select name="employeeid"><option value="0"></option><?php
$query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where deleted=0 order by employeename';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  if ($row2['employeeid'] == $_POST['employeeid']) { echo '<option value="' . $row2['employeeid'] . '" selected>' . $row2['employeename'] . '</option>'; }
  else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
}
?></select></td></tr>
<tr><td>Catégorie d'action:</td>
<td><select name="productactioncatid"><option value="0"></option><?php

$query = 'select productactioncatid,productactioncatname from productactioncat where deleted=0 order by productactioncatname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  if ($row2['productactioncatid'] == $_POST['productactioncatid']) { echo '<option value="' . $row2['productactioncatid'] . '" selected>' . $row2['productactioncatname'] . '</option>'; }
  else { echo '<option value="' . $row2['productactioncatid'] . '">' . $row2['productactioncatname'] . '</option>'; }
}
echo '</select></td></tr>';

$dp_itemname = 'competitor'; $dp_description = 'Entreprise concurrente'; $dp_selectedid = $competitorid;
require('inc/selectitem.php');

if (isset($_SESSION['ds_term_productactiontag']) && $_SESSION['ds_term_productactiontag'] != '')
{
  $dp_itemname = 'productactiontag'; $dp_description = $_SESSION['ds_term_productactiontag']; $dp_selectedid = $productactiontagid;
  require('inc/selectitem.php');
}

echo '<tr><td>Évènement:</td><td><input type="text" STYLE="text-align:left" name="actionname" value="' . $actionname . '" size=80></td></tr>';
echo '<tr><td>Info prix:</td><td><input type="text" STYLE="text-align:left" name="priceinfo" value="' . $priceinfo . '" size=80></td></tr>';

if (isset($_SESSION['ds_term_productactionfield1']) && $_SESSION['ds_term_productactionfield1'] != '')
{
  echo '<tr><td>' . d_output($_SESSION['ds_term_productactionfield1']) . ':</td><td><input type="text" STYLE="text-align:left" name="productactionfield1" value="' . $productactionfield1 . '" size=80></td></tr>';
}

echo '<tr><td>Ajouter image:</td><td><input name="imagefile" type="file" size=50></td></tr>';

echo '<tr><td colspan="2" align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Valider"></td></tr>';
echo '</table></form>';

?>