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
if ($_SESSION['ds_autocomplete'] && !isset($autocomplete_accounting_loaded))
{
  echo '<script type="text/javascript" src="jq/jquery.autocomplete.select.accounting.js"></script>';
  $autocomplete_accounting_loaded = 1;
}

?>