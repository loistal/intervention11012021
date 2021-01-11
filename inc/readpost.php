<?php

# checks for existance of POST or GET varables and sets them to the correct type
# unset variables set to empty or zero, as applicable
#
# supported "formats": int uint decimal udecimal currency ucurrency date product client supplier

# usage:
#
# $PA['saveme'] = 'int';
# $PA['shipmentidinput'] = 'int';
# require('inc/readpost.php');
#

foreach ($PA as $temp_name => $temp_type)
{
  if (isset($_POST[$temp_name]))
  {
    $$temp_name = $_POST[$temp_name];
  }
  elseif (isset($_GET[$temp_name]))
  {
    $$temp_name = $_GET[$temp_name];
  }
  if(!isset($$temp_name)) # default values
  {
    if ($temp_type == 'int' || $temp_type == 'uint' ) { $$temp_name = 0; }
    else { $$temp_name = ''; }
    if ($temp_type == 'client' && !isset($clientid)) { $clientid = 0; }
  }
  else
  {
    if ($temp_type == 'int')
    {
      $$temp_name = str_replace($_SESSION['ds_decimalmark'], '', $$temp_name);
      $$temp_name = (int) preg_replace('/\s+/', '', $$temp_name);
    }
    elseif ($temp_type == 'uint')
    {
      $$temp_name = str_replace($_SESSION['ds_decimalmark'], '', $$temp_name);
      $$temp_name = (int) preg_replace('/\s+/', '', $$temp_name);
      if ($$temp_name < 0) { $$temp_name = 0; }
    }
    elseif ($temp_type == 'decimal' || $temp_type == 'currency')
    {
      $$temp_name = preg_replace('/\s+/', '', $$temp_name);
      # TODO if only one comma and no dots, replace comma by dot    see myfix()
      $$temp_name = d_add($$temp_name, 0);
      $$temp_name = rtrim($$temp_name, "0");
      $$temp_name = rtrim($$temp_name, ".");
      if ($temp_type == 'currency') { $$temp_name = myround($$temp_name, $_SESSION['ds_tem_currencyprecision']); }
    }
    elseif ($temp_type == 'udecimal' || $temp_type == 'ucurrency')
    {
      $$temp_name = preg_replace('/\s+/', '', $$temp_name);
      # TODO if only one comma and no dots, replace comma by dot    see myfix()
      if (d_compare($$temp_name, 0) == -1) { $$temp_name = 0; }
      $$temp_name = d_add($$temp_name, 0);
      $$temp_name = rtrim($$temp_name, "0");
      $$temp_name = rtrim($$temp_name, ".");
      if ($temp_type == 'ucurrency') { $$temp_name = myround($$temp_name, $_SESSION['ds_tem_currencyprecision']); }
    }
    elseif ($temp_type == 'date')
    {
      $datename = $temp_name; require('inc/datepickerresult.php');
    }
    elseif ($temp_type == 'product') # TODO important refactor
    {
      require('inc/findproduct.php');
      if (!isset($productid)) { $productid = 0; }
    }
    elseif ($temp_type == 'client') # TODO important refactor
    {
      $clientname = ''; $client = $$temp_name;
      require('inc/findclient.php');
      if (!isset($clientid)) { $clientid = 0; }
    }
    elseif ($temp_type == 'supplier') # TODO important refactor
    {
      $clientname = ''; $dp_no_suppliers = 0;
      require('inc/findclient.php');
      if (!isset($clientid)) { $clientid = 0; }
      $supplierid = $clientid; if (isset($clientname)) { $suppliername = $clientname; } else { $suppliername = ''; }
      unset ($clientid, $clientname);
    }
    elseif ($temp_type == 'in_list_int')
    {
      $temp = preg_split('/[\s]+/', $$temp_name);
      $temp = array_map('intval', $temp);
      $$temp_name = '(';
      foreach ($temp as $temp2)
      {
        if ($temp2 > 0)
        {
          $$temp_name .= $temp2;
          $$temp_name .= ',';
        }
      }
      $$temp_name = rtrim($$temp_name, ',');
      $$temp_name .= ')';
      if ($$temp_name == '()') { $$temp_name = '(-1)'; }
    }
  }
}

unset($PA,$temp_name,$temp_type);

?>