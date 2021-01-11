<?php

# TODO rename $datepicker_date to $temp_datepicker_date - needs to be tested thoroughly

# input: $datename
# optional input: $dp_allowempty
# output: $$datename

/*
example:
$datename = 'startdate'; require('inc/datepickerresult.php');
*/

$datepicker_date = NULL;
if (!isset($dp_allowempty)) { $dp_allowempty = 0; }

if ($_SESSION['ds_user_datepicker'] == 0)
{
  if (isset($_POST[$datename])) { $datepicker_date = $_POST[$datename]; }
  elseif (isset($_GET[$datename])) { $datepicker_date = $_GET[$datename]; }
  elseif (!isset($dp_allowempty) || $dp_allowempty != 1)
  {
    $datepicker_date = $_SESSION['ds_curdate'];
  }
}
elseif ($_SESSION['ds_user_datepicker'] == 1)
{
  if (isset($_POST[$datename.'day']) && !isset($_POST[$datename.'month']) && !isset($_POST[$datename.'year']))
  {
    $datepicker_date = d_builddate($_POST[$datename.'day'],$_POST[$datename.'month'],$_POST[$datename.'year']);
  }
  elseif (isset($_GET[$datename])) { $datepicker_date = $_GET[$datename]; }
  elseif (!isset($_POST[$datename.'year']) && $dp_allowempty != 1)
  {
    $datepicker_date = $_SESSION['ds_curdate'];
  }
}
elseif ($_SESSION['ds_user_datepicker'] == 2)
{
  if (isset($_POST[$datename])) { $datepicker_date = $_POST[$datename]; }
  elseif (isset($_GET[$datename])) { $datepicker_date = $_GET[$datename]; }
  elseif (!isset($_POST[$datename]) && $dp_allowempty != 1)
  {
    $datepicker_date = $_SESSION['ds_curdate'];
  }
}

if ($dp_allowempty == 1 && $datepicker_date == '') { $datepicker_date = NULL; }

$$datename = $datepicker_date;

# do not unset $datename
unset($dp_allowempty);

?>