<?php

if ($_SESSION['ds_autocomplete'] && !isset($autocomplete_loaded)) # TODO make sure every javascript is only loaded once
{
  echo '<link rel="stylesheet" type="text/css" href="jq/jquery-ui.min.css">';
  require("jq/autocomplete.css.php");
  echo '<script type="text/javascript" src="jq/jquery.js"></script>
  <script type="text/javascript" src="jq/jquery-ui.min.js"></script>
  <script type="text/javascript" src="jq/jquery.ui.autocomplete.html.js"></script>';
  $autocomplete_loaded = 1;
}
if (isset($dp_supplier) && $dp_supplier == 1 && $_SESSION['ds_autocomplete'] && !isset($autocomplete_supplier_loaded))
{
  echo '<script type="text/javascript" src="jq/jquery.autocomplete.select.supplier.js"></script>';
  $autocomplete_supplier_loaded = 1;
}
elseif ($_SESSION['ds_autocomplete'] && !isset($autocomplete_client_loaded))
{
  echo '<script type="text/javascript" src="jq/jquery.autocomplete.select.client.js"></script>';
  $autocomplete_client_loaded = 1;
}

?>