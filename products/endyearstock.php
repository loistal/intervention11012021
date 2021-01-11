<style>
#rightalign {
  text-align:right;
}
</style>

<?php
$NB_MAX_PRODUCTS = 50;
$ds_useunits = $_SESSION['ds_useunits'];
$ds_useproductcode = $_SESSION['ds_useproductcode'];

$save = $_POST['save'];
if($save == d_trad('validate')){$currentstep = 2;}
else if($save == d_trad('update')){$currentstep = 1;}
else {$currentstep = 0;}

$addstock = (int) $_POST['addstock'];
$productidA = array();$amountA = array();$amountunitsA = array();$numberperunitA = array();
for ($i=1; $i <= $NB_MAX_PRODUCTS; $i++)
{
  $productidA[$i] =  $_POST['productid' . $i];
  $amountA[$i] = $_POST['amount' . $i];  
  $amountunitsA[$i] = $_POST['amountunits' . $i];  
}
$currentyear = mb_substr($_SESSION['ds_curdate'],0,4)-1;
if (substr($_SESSION['ds_curdate'],5,2) == 12) { $currentyear++; }
echo '<h2>' . d_trad ('endofyearstock:',$currentyear);

if( $currentstep == 2) { echo '&nbsp;' . d_trad('validated');}
echo '</h2>';

echo '<form method="post" action="products.php">';
echo '<input type=hidden value="' . $NB_MAX_PRODUCTS . '"/>';
echo '<table><tr><td>Ajouter au stock déja compté : <input type="checkbox" name="addstock" value="1"';
if ($addstock == 1) { echo ' checked'; }
echo '/></td></tr></table>';

if ($currentstep != 2)
{
  echo '<div align="center"><input name="save" type="submit" value="' . d_trad('update') . '"> <input name="save" type="submit" value="' . d_trad('validate') . '"><input type=hidden name="productsmenu" value="' . $productsmenu . '"></div>';
}
echo '<table class=report><thead><th></th><th>' . d_trad('product') . '</th><th>' . d_trad('unitsquantity') . '</th>';
if ($ds_useunits) { echo '<th>' . d_trad('subunitsquantity') . '</th>'; }
echo '</thead>';
for ($i=1; $i <= $NB_MAX_PRODUCTS; $i++)
{
  $inputval = '';
  if ($productidA[$i] != '')
  {
    $productid = $productidA[$i];
    $inputval = $productid;
#$temp=$productid;echo "productid$i=$temp<br>";    
    $query = 'select productid,productname,netweightlabel,numberperunit,discontinued,notforsale,suppliercode from product';
    if ($ds_useproductcode == 1)
    {
      $query .= ' where suppliercode like ? order by suppliercode limit 1';
      $query_prm = array('%'.$productid.'%');
    }
    else
    {
      $query .= ' where productid=?';
      $query_prm = array($productid);
    }
    require('inc/doquery.php');
    $row2 = $query_result[0];
    
    $productidA[$i] = $row2['productid'];
    $npu = $row2['numberperunit'];
    $numberperunitA[$i] = $npu;
    #echo "npu = $npu / numberperunitA[$i] = $numberperunitA[$i]<br>";
    
    echo d_tr() . '<td>' . $i . '.</td><td><input type="text" id="rightalign" name="productid' . $i . '"';
    if ($ds_useproductcode == 1)
    {
      echo ' value="' . $row2['suppliercode'] . '">';
    }
    else 
    { 
      echo ' value="' . $productid . '">'; 
    }
    $productname = $row2['productname'] . ' ';
    if ($ds_useunits && $npu > 1) { $productname .= $npu . ' x '; }
    $productname .= $row2['netweightlabel'];
    echo ' ' . $productname;
    if ($row2['discontinued'] == 1) { echo ' <font color=red>' . d_trad('discontinued') . '</font>'; }
    if ($row2['notforsale'] == 1) { echo ' <font color=red>' . d_trad('notforsale') . '</font>'; }
  }
  else 
  { 
    echo d_tr() . '<td>' . $i . '.</td><td><input type="text" id="rightalign" name="productid' . $i . '" value="' . $inputval . '">'; 
  }
  echo '</td><td id="rightalign"><input type="text" id="rightalign" name="amount' . $i . '" value="' . $amountA[$i] . '"></td>';
  if ($ds_useunits) { echo '<td id="rightalign"><input type="text" id="rightalign" name="amountunits' . $i . '"  value="' . $amountunitsA[$i] . '"></td>'; }
  echo '</tr>';
}
echo '</table>';

if ($currentstep != 2)
{
  echo '<div class="center"><input name="save" type="submit" value="' . d_trad('update') . '"> <input name="save" type="submit" value="' . d_trad('validate') . '"><input type=hidden name="productsmenu" value="' . $productsmenu . '"></div>';
}
else
{
  for ($i=1; $i <= $NB_MAX_PRODUCTS; $i++)
  {
    if ($amountA[$i] > 0 || $amountunitsA[$i] > 0)
    {
      $productid = $productidA[$i];
      $valuetostore = ($amountA[$i] * $numberperunitA[$i]) + $amountunitsA[$i];
      #echo "amountA[$i]  = $amountA[$i] / numberperunitA[$i] = $numberperunitA[$i] / amountunitsA[$i] = $amountunitsA[$i]<br>";

      $query = 'select stock from endofyearstock where year=? and productid=?';
      $query_prm = array($currentyear,$productid);
      require('inc/doquery.php');
      $main_result = $query_result; $num_results_main = $num_results;unset($query_result,$num_results);
      if ($num_results_main == 0)
      {
        $query = 'insert into endofyearstock (productid,year,stock) values (?,?,?)';
        $query_prm = array($productid,$currentyear,$valuetostore);
        require('inc/doquery.php');
      }
      elseif ($num_results_main == 1)
      {
        if ($addstock == 1)
        {
          $row = $main_result[0];
          $valuetostore +=  $row['stock'];
        }
        $query = 'update endofyearstock set stock=? where year=? and productid=?';
        $query_prm = array($valuetostore,$currentyear,$productid);
        require('inc/doquery.php');
      }
    }
  }
}
 
?>
</form>