<?php
if ($modifyinvoiceid == "") { $modifyinvoiceid = $_POST['modifyinvoiceid']; }
if ($invoicecomment == "" && isset($_POST['invoicecomment'])) { $invoicecomment = $_POST['invoicecomment']; }

if (isset($_POST['saveme']) && $_POST['saveme'] == 1)
{
  $newcomment = $invoicecomment . ' ' . $_POST['addtocomment'];
  $query = 'update invoice';
  if ($_POST['usehistory'] == 1) { $query .= 'history'; }
  $query .= ' set invoicecomment=? where invoiceid=?';
  $query_prm = array($newcomment,$modifyinvoiceid);
  require('inc/doquery.php');
  echo '<p>Commentaire pour facture ' . $modifyinvoiceid . ' modifié: "' . $newcomment . '"</p>';
  if ($_SESSION['ds_useserialnumbers'])
  {
    if ($_POST['results'] > 0)
    {
      echo '<br><p>Numéros de série:<br>';
      for ($i=0; $i < $_POST['results']; $i++)
      {
        $query = 'update invoiceitemhistory set serial=? where invoiceitemid=?';
        $query_prm = array($_POST['serial'.$i],$_POST['invoiceitemid'.$i]);
        require('inc/doquery.php');
        echo $_POST['serial'.$i] . '<br>';
      }
      echo '</p>';
    }
  }
}
else
{
  if ($modifyinvoiceid > 0)
  {
    echo '<h2>Ajouter aux commentaires';
    if ($_SESSION['ds_useserialnumbers']) { echo ' / modifier numéros de série'; }
    echo '</h2><form method="post" action="sales.php"><table';
    if ($_SESSION['ds_useserialnumbers']) { echo ' class=report style="background-color: ' . $_SESSION['ds_formcolor'] . '"'; }
    echo '><tr><td colspan=2>' . $invoicecomment . ' <input type=text name="addtocomment" size=80></td></tr>';
    if ($_SESSION['ds_useserialnumbers'])
    {
      $query = 'select serial,productid,invoiceitemid from invoiceitemhistory where invoiceitemhistory.invoiceid=?';
      $query_prm = array($modifyinvoiceid);
      require('inc/doquery.php');
      $main_result = $query_result; unset($query_result); $num_results_main = $num_results;
      if ($num_results_main > 0)
      {
        require('preload/product.php'); # TODO remove, way too slow!!
        echo '<tr><td><b>Produit</b></td><td><b>No Serie</b></td></tr><input type=hidden name=results value=' . $num_results . '>';
        for ($i=0; $i < $num_results_main; $i++)
        {
          echo '<tr><td>' . $productA[$main_result[$i]['productid']] . '</td><td><input type=text name="serial'.$i.'" size=30 value="' . d_input($main_result[$i]['serial']) . '"></td></tr>
          <input type=hidden name=invoiceitemid' . $i . ' value=' . $main_result[$i]['invoiceitemid'] . '>';
        }
      }
    }
    echo '<tr><td colspan="2" align="center"><input type=hidden name="saveme" value="1"><input type=hidden name="usehistory" value="' . $usehistory . '"><input type=hidden name="modifyinvoiceid" value="' . $modifyinvoiceid . '"><input type=hidden name="invoicecomment" value="' . $invoicecomment . '"><input type=hidden name="salesmenu" value="addtocomment"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form><br>';
  }
}
?>