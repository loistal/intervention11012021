<?php

### input box for selecting a SEVERAL/single product

# input: $product (for findproduct.php) $fp_counter
# output: $_POST['product' . $fp_counter] + everything from findproduct.php

require('inc/autocomplete_product.php');

require ('inc/findproduct.php');

if (!isset($fp_counter)) { $fp_counter = ''; }

if ($num_products < 1 || $num_products > 20) #$_SESSION['ds_maxresults']
{
  if ($fp_counter == '') { echo 'Produit: </td><td>'; }
  echo '<input ';
  if ($fp_counter == '') { echo 'autofocus '; }
  echo 'type="text" STYLE="text-align:right" id="product_autocomplete' . $fp_counter . '" autocomplete="off" name="product' . $fp_counter . '" value="' . d_input($product) . '" size=';
  if ($_SESSION['ds_useproductcode']) { echo '30'; }
  else { echo '10'; }
  echo '>';
  if (isset($_POST['product' . $fp_counter]) && $_POST['product' . $fp_counter] != '')
  {
    if ($num_products < 1 && $fp_counter == '') { echo ' &nbsp; <span class="alert">Aucun produit trouvé.</span>'; }
    else { echo ' &nbsp; <span class="alert">' . $num_products . ' produits trouvés.</span>'; }
  }
}
elseif ($num_products != 1)
{
  if ($fp_counter == '') { echo 'Produit: </td><td>'; }
  echo '<select ';
  if ($fp_counter == '') { echo 'autofocus '; }
  echo 'name="product' . $fp_counter . '">';
  for ($i_temp=0;$i_temp<$num_products;$i_temp++)
  {
    if ($_SESSION['ds_useproductcode'] == 1)
    {
      echo '<option value="' . d_input(d_decode($query_result[$i_temp]['suppliercode'])) . '">' . d_output(d_decode($query_result[$i_temp]['suppliercode']));
    }
    else
    {
      echo '<option value="' . $query_result[$i_temp]['productid'] . '">' . $query_result[$i_temp]['productid'];
    }
    echo ': ' . d_output(d_decode($query_result[$i_temp]['productname'])) . '</option>';
  }
  echo '</select>';
  echo ' &nbsp; <span class="alert">' . $num_products . ' produits trouvés.</span>';
}
else
{
  if ($fp_counter == '') { echo 'Produit: </td><td>'; }
  echo '<input type="text" STYLE="text-align:right" id="product_autocomplete' . $fp_counter . '" autocomplete="off" name="product' . $fp_counter . '" value="' . d_input($product) . '" size=10> ';
  echo d_output($productname);
}

?>