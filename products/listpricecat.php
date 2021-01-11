<?php

if (isset($_POST['listpricingcatid']))
{
  if ($_POST['listpricingcatname'] == "")
  {
    $query = 'update listpricingcat set deleted=1 where listpricingcatid=?';
    $query_prm = array($_POST['listpricingcatid']);
    require ('inc/doquery.php');
    if ($num_results) { echo '<p>Liste supprimé.</p><br>'; }
  }
  else
  {
    if ($_POST['listpricingcatid'] == 0)
    {
      $query = 'insert into listpricingcat (listpricingcatname) values (?)';
      $query_prm = array($_POST['listpricingcatname']);
      require ('inc/doquery.php');
      echo '<p>Liste ' . d_output($_POST['listpricingcatname']) . ' ajouté.</p><br>';
    }
    else
    {
      $query = 'update listpricingcat set listpricingcatname=? where listpricingcatid=?';
      $query_prm = array($_POST['listpricingcatname'],$_POST['listpricingcatid']);
      require ('inc/doquery.php');
      echo '<p>Liste ' . d_output($_POST['listpricingcatname']) . ' modifiée.</p><br>';
    }
  }
}
echo '<h2>Ajouter /  Modifier liste:</h2><form method="post" action="products.php"><table>';
$dp_itemname = 'listpricingcat'; $dp_description = 'Liste'; require('inc/selectitem.php');
echo '<tr><td>Nom:</td><td><input type="text" STYLE="text-align:right" name="listpricingcatname" size=10></td></tr>';
echo '<tr><td colspan="2" align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Valider"></td></tr>';

?>