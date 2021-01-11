<?php

# input: $datename
# optional input: $selecteddate $dp_setempty $dp_datepicker_min $dp_datepicker_max $dp_isdisabled
if (!isset($dp_isdisabled))
{
  $dp_isdisabled = 0;
}

if ($_SESSION['ds_user_datepicker'] == 0 || $_SESSION['ds_user_datepicker'] == 2)
{
  if (isset($selecteddate))
  {
    $datepicker_date = $selecteddate;
  }
  elseif (isset($_POST[$datename]))
  {
    $datepicker_date = $_POST[$datename];
  }
  elseif (isset($$datename))
  {
    $datepicker_date = $$datename;
  }
  else
  {
    $datepicker_date = $_SESSION['ds_curdate'];
  }
  
  if ($datepicker_date == "") { $datepicker_date = $_SESSION['ds_curdate']; }
  
  if (isset($dp_datepicker_min))
  {
    if ($datepicker_date < $dp_datepicker_min) { $datepicker_date = $dp_datepicker_min; }
  }
  else { $dp_datepicker_min = $_SESSION['ds_startyear'] . '-01-01'; }
  
  if (isset($dp_datepicker_max)) { $datepicker_max = $dp_datepicker_max; }
  else { $datepicker_max = $_SESSION['ds_endyear'] . '-01-01'; }
  
  $valuestring_temp = ' value="' . $datepicker_date . '"';
  if ((isset($dp_setempty) && $dp_setempty) && (!isset($selecteddate) || ($selecteddate == ''))){ $valuestring_temp = ''; }

  if ($_SESSION['ds_user_datepicker'] == 0)
  {
    echo '<input type=date name="' . $datename . '"'.$valuestring_temp.' min="' . $dp_datepicker_min . '" max="' . $datepicker_max . '"';
    if ($dp_isdisabled) { echo ' READONLY';}
    echo '>';
  }
  else
  {
    echo '<input type=text name="' . $datename . '"'.$valuestring_temp;
    if ($dp_isdisabled) { echo ' READONLY';}
    echo ' size=10>';
  }
}
else
{
  if(isset($selecteddate) && ($selecteddate != ''))
  {
    $datepicker_day = mb_substr($selecteddate,8,2);
    $datepicker_month = mb_substr($selecteddate,5,2);
    $datepicker_year = mb_substr($selecteddate,0,4);
  }
  elseif (isset($_POST[$datename . 'day']))
  {
    $datepicker_day = $_POST[$datename . 'day'];
    $datepicker_month = $_POST[$datename . 'month'];
    $datepicker_year = $_POST[$datename . 'year'];  
    $datepicker_date = d_builddate($datepicker_day,$datepicker_month,$datepicker_year);   
  }
  elseif (isset($$datename))
  {
    $datepicker_date = $$datename;
    $datepicker_day = mb_substr($datepicker_date,8,2);    
    $datepicker_month = mb_substr($datepicker_date,5,2);
    $datepicker_year = mb_substr($datepicker_date,0,4);
  }
  else
  {
    $datepicker_day = mb_substr($_SESSION['ds_curdate'],8,2);
    $datepicker_month = mb_substr($_SESSION['ds_curdate'],5,2);
    $datepicker_year = mb_substr($_SESSION['ds_curdate'],0,4);    
  }

  echo '<select name="' . $datename . 'day"';
  if($dp_isdisabled)
  {
    echo ' DISABLED';
  }
  echo '>';
  if (isset($dp_setempty) && $dp_setempty)
  {
    $selected = '';
    if (!isset($selecteddate) || ($selecteddate == '')) { $selected = ' SELECTED'; }  
    echo '<option value=""' . $selected . '></option>';
  }  
  for ($i_temp=1; $i_temp <= 31; $i_temp++)
  { 
    if ($i_temp == $datepicker_day) { echo '<option value="' . $i_temp . '" SELECTED>' . $i_temp . '</option>'; }
    else { echo '<option value="' . $i_temp . '">' . $i_temp . '</option>'; }
  }
  echo '</select><select name="' . $datename . 'month"';
  if($dp_isdisabled)
  {
    echo ' DISABLED';
  }
  echo '>';
  if (isset($dp_setempty) && $dp_setempty)
  {
    $selected = '';
    if (!isset($selecteddate) || ($selecteddate == '')) { $selected = ' SELECTED'; }    
    echo '<option value=""' . $selected . '></option>';
  }  
  for ($i_temp=1; $i_temp <= 12; $i_temp++)
  {
    if ($i_temp == $datepicker_month) { echo '<option value="' . $i_temp . '" SELECTED>' . $i_temp . '</option>'; }
    else { echo '<option value="' . $i_temp . '">' . $i_temp . '</option>'; }
  }
  echo '</select><select name="' . $datename . 'year"';
  if($dp_isdisabled)
  {
    echo ' DISABLED';
  }
  echo '>';
  if (isset($dp_setempty) && $dp_setempty)
  {
    $selected = '';
    if (!isset($selecteddate) || ($selecteddate == '')) { $selected = ' SELECTED'; }    
    echo '<option value=""' . $selected . '></option>';
  }
  if (isset($dp_datepicker_min)) { $datepickermin_year = mb_substr($dp_datepicker_min,0,4);  }
  else { $datepickermin_year = $_SESSION['ds_startyear']; }  
  if (isset($dp_datepicker_max)) { $datepickermax_year = mb_substr($dp_datepicker_max,0,4);  }
  else { $datepickermax_year = $_SESSION['ds_endyear']; }
  for ($i_temp=$datepickermin_year; $i_temp <= $datepickermax_year; $i_temp++)
  {
    if ($i_temp == $datepicker_year) { echo '<option value="' . $i_temp . '" SELECTED>' . $i_temp . '</option>'; }
    else { echo '<option value="' . $i_temp . '">' . $i_temp . '</option>'; }
  }
  echo '</select>';
}

unset ($datename, $selecteddate, $dp_setempty, $dp_datepicker_min, $dp_datepicker_max, $dp_isdisabled);
?>