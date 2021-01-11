<?php

if ($_SESSION['ds_autocomplete'] && !isset($autocomplete_loaded))
{
  echo '<link rel="stylesheet" type="text/css" href="jq/jquery-ui.min.css">';
  require("jq/autocomplete.css.php");
  echo '<script type="text/javascript" src="jq/jquery.js"></script>
  <script type="text/javascript" src="jq/jquery-ui.min.js"></script>
  <script type="text/javascript" src="jq/jquery.ui.autocomplete.html.js"></script>';
  $autocomplete_loaded = 1;
}
if ($_SESSION['ds_autocomplete'] && !isset($autocomplete_product_loaded))
{
  echo '<script type="text/javascript" src="jq/jquery.autocomplete.select.product.js"></script>';
  $autocomplete_product_loaded = 1;
}

?>