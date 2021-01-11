<?php

if (!isset($simple_form)) { $simple_form = 0; $action = 'admin.php'; }
else { $action = 'sales.php'; }

$modplanningid = $_POST['modplanningid'];
if(!isset($modplanningid)){$modplanningid = $_GET['modplanningid'];}

if ($modplanningid > 0)
{
  $planningid = $modplanningid+0;
  $saveme = 0;
  $query = 'select * from planning where planningid=?';
  $query_prm = array($planningid);
  require('inc/doquery.php');
  $planningstart = $query_result[0]['planningstart'];
  $planningstop = $query_result[0]['planningstop'];
  $planningtimestart = $query_result[0]['planningtimestart'];
  $planningtimestop = $query_result[0]['planningtimestop'];
  $planningdate = $query_result[0]['planningdate'];
  $periodic = $query_result[0]['periodic'];
  $planningname = $query_result[0]['planningname'];
  $planningcomment = $query_result[0]['planningcomment'];
  $periodic_spec_weekly = $query_result[0]['periodic_spec'];
  $periodic_spec_monthly = $query_result[0]['periodic_spec'];
  $dayofweek = $query_result[0]['dayofweek'];
  $day_monthly = mb_substr($planningdate,8,2)+0;
  $day_yearly = mb_substr($planningdate,8,2)+0;
  $month_yearly = mb_substr($planningdate,5,2)+0;
  $deleted = $query_result[0]['deleted'];
  if ($simple_form)
  {
    $query = 'select clientid from planning_client where linenr=1 and planningid=?';
    $query_prm = array($planningid);
    require('inc/doquery.php');
    $simple_form_clientid = $query_result[0]['clientid'];
  }
}
else
{
  $planningid = $_POST['planningid']+0;
  $saveme = $_POST['saveme']+0;
  $datename = 'planningstart';
  require('inc/datepickerresult.php');
  if (!isset($_POST[$datename])) { $$datename = $_SESSION['ds_startyear'].'-01-01'; }
  $planningtimestart = $_POST['planningtimestart'];
  $datename = 'planningstop';
  require('inc/datepickerresult.php');
  if (!isset($_POST[$datename])) { $$datename = $_SESSION['ds_endyear'].'-01-01'; }
  $planningtimestop = $_POST['planningtimestop']; if ($planningtimestop < $planningtimestart) { $planningtimestop = $planningtimestart; }
  $datename = 'planningdate';
  require('inc/datepickerresult.php');
  $periodic = $_POST['periodic']+0;
  $planningname = $_POST['planningname'];
  $planningcomment = $_POST['planningcomment'];
  $periodic_spec_weekly = $_POST['periodic_spec_weekly']+0;
  $dayofweek = $_POST['dayofweek']+0;
  $day_monthly = $_POST['day_monthly']+0;
  $periodic_spec_monthly = $_POST['periodic_spec_monthly']+0;
  $day_yearly = $_POST['day_yearly']+0;
  $month_yearly = $_POST['month_yearly']+0;
  $deleted = $_POST['deleted']+0;
}

if ($saveme)
{
  if ($periodic == 1) { $periodic_spec = $periodic_spec_weekly;}
  else { $periodic_spec = $periodic_spec_monthly; }
  if ($periodic == 2 || $periodic == 3)
  {
    $planningdate_year = mb_substr($planningdate,0,4);
    $planningdate_month = mb_substr($planningdate,5,2);
    if ($periodic == 2)
    {
      $planningdate_day = $day_monthly;
    }
    elseif ($periodic == 3)
    {
      $planningdate_month = $month_yearly;
      $planningdate_day = $day_yearly;
    }
    $planningdate = d_builddate($planningdate_day,$planningdate_month,$planningdate_year);
  }
  if ($planningtimestart == '') {$planningtimestart = NULL;}
	if ($planningtimestop == '') { $planningtimestop = NULL; }
  
  if ($planningid > 0)
  {
    $query = 'update planning set deleted=?,planningdate=?,planningstart=?,planningstop=?,planningtimestart=?,planningtimestop=?,planningname=?,planningcomment=?,dayofweek=?,periodic=?,periodic_spec=?';
    $query_prm = array($deleted,$planningdate,$planningstart,$planningstop,$planningtimestart,$planningtimestop,$planningname,$planningcomment,$dayofweek,$periodic,$periodic_spec);
    $query = $query . ' where planningid=?'; array_push($query_prm,$planningid);
    require('inc/doquery.php');
    echo '<p>' . d_trad('planningmodified') . '</p><br>';
  }
  else
  {
    $savetime = 1;
    $query = 'insert into planning (planningdate,planningstart,planningstop,planningname,planningcomment,dayofweek,periodic,periodic_spec';
    if ($savetime) { $query = $query . ',planningtimestart,planningtimestop'; }
    $query = $query . ') values (?,?,?,?,?,?,?,?';
    if ($savetime) { $query = $query . ',?,?'; }
    $query = $query . ')';
    $query_prm = array($planningdate,$planningstart,$planningstop,$planningname,$planningcomment,$dayofweek,$periodic,$periodic_spec);
    if ($savetime) { array_push($query_prm,$planningtimestart,$planningtimestop); }
    require('inc/doquery.php');
    $planningid = $query_insert_id;
    echo '<p>' . d_trad('planningadded') . '</p><br>';
  }
  if ($simple_form)
  {
    $query = 'select planning_employeeid from planning_employee where linenr=1 and planningid=?';
    $query_prm = array($planningid);
    require('inc/doquery.php');
    $planning_employeeid = $query_result[0]['planning_employeeid'];
    if ($planning_employeeid > 0)
    {
      $query = 'update planning_employee set employeeid=? where planning_employeeid=?';
      $query_prm = array($employee1id,$planning_employeeid);
      require('inc/doquery.php');
    }
    elseif ($employee1id > 0 && $planningid > 0)
    {
      $query = 'insert into planning_employee (planningid,employeeid,linenr) values (?,?,1)';
      $query_prm = array($planningid,$employee1id);
      require('inc/doquery.php');
    }
    
    require('inc/findclient.php'); # getting $clientid from $client
    $query = 'select planning_clientid from planning_client where linenr=1 and planningid=?';
    $query_prm = array($planningid);
    require('inc/doquery.php');
    $planning_clientid = $query_result[0]['planning_clientid'];
    if ($planning_clientid > 0)
    {
      $query = 'update planning_client set clientid=? where planning_clientid=?';
      $query_prm = array($clientid,$planning_clientid);
      require('inc/doquery.php');
    }
    elseif ($clientid > 0 && $planningid > 0)
    {
      $query = 'insert into planning_client (planningid,clientid,linenr) values (?,?,1)';
      $query_prm = array($planningid,$clientid);
      require('inc/doquery.php');
    }
  }
}

if ($planningid > 0)
{ 
	if ($simple_form) { echo '<br><h2>Modifier RDV</h2>'; }
	else { echo '<h2>' . d_trad('modplanning:') . '</h2>'; }
}
else 
{
  if ($simple_form) { echo '<br><h2>Prendre RDV</h2>'; }
	else { echo '<h2>' . d_trad('addplanning:') . '</h2>'; }
}

echo '<form method="post" action="'.$action.'"><table>';
echo '<tr><td>' . d_trad('planningname:') . '</td><td colspan=2>';
echo '<input autofocus type="text" name="planningname" value="'.$planningname.'" size=40 ></td></tr>';

if ($simple_form)
{
  echo '<input type=hidden name="planningstart" value="2000-01-01"><input type=hidden name="planningstop" value="3000-01-01">';
}
else
{
  echo '<tr><td>' . d_trad('validity:') . '</td><td colspan=2>';
  $datename = 'planningstart'; $selecteddate = $$datename;
  require('inc/datepicker.php');
  echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
  $datename = 'planningstop'; $selecteddate = $$datename;
  require('inc/datepicker.php');
  $planningtimestart = mb_substr($planningtimestart,0,5);
}

if ($simple_form)
{
  echo '<input type=hidden name="periodic" value=0><input type=hidden name="employee1id" value="'.$_SESSION['ds_myemployeeid'].'">';
  $client = $simple_form_clientid;
  echo '<tr><td>'; require('inc/selectclient.php');
  echo '<tr><td>Date:<td>';
  $datename = 'planningdate'; $selecteddate = $$datename;
  require('inc/datepicker.php');
}
else
{
  echo '<tr><td>' . d_trad('planningtype') . ':</td><td>';
  echo '<input type=radio name=periodic value=0'; if ($periodic == 0) { echo ' checked'; }

  echo '>'.d_trad('punctual') .'</td><td>';
  $datename = 'planningdate'; $selecteddate = $$datename;
  require('inc/datepicker.php');
  echo '</td></tr><tr><td></td><td>';

  echo '<input type=radio name=periodic value=1'; if ($periodic == 1) { echo ' checked'; }
  echo '>'.d_trad('weekly') .'</td><td>';
  echo '<select name="periodic_spec_weekly">';
  #Every week
  echo '<option value=0'; if ($periodic_spec_weekly == 0) { echo ' selected'; } echo '>'.d_trad('allweeks') .'</option>';
  #Every odd week
  echo '<option value=1'; if ($periodic_spec_weekly == 1) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_1').'</option>';
  #Every even week
  echo '<option value=2'; if ($periodic_spec_weekly == 2) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_2').'</option>';
  #Every 1st week of month
  echo '<option value=3'; if ($periodic_spec_weekly == 3) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_3').'</option>';
  #Every 2nd week of month
  echo '<option value=4'; if ($periodic_spec_weekly == 4) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_4').'</option>';
  #Every 3rd week of month
  echo '<option value=5'; if ($periodic_spec_weekly == 5) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_5').'</option>';
  #Every 4th week of month
  echo '<option value=6'; if ($periodic_spec_weekly == 6) { echo ' selected'; } echo '>'.d_trad('periodic_spec_weekly_6').'</option>';
  echo '</select>';
  echo ' <select name="dayofweek">';
  for ($i=1;$i<=7;$i++)
  {
    echo '<option value='.$i; if ($dayofweek == $i) { echo ' selected'; }
    echo '>'. d_trad('dayofweek' . $i) .'</option>';
  }
  echo '</select>';
  
  echo '<tr><td></td><td>';

  echo '<input type=radio name=periodic value=2'; if ($periodic == 2) { echo ' checked'; }
  echo '>'.d_trad('monthly').'</td><td>';
  echo '<select name="periodic_spec_monthly">';
  #Every month
  echo '<option value=0'; if ($periodic_spec_monthly == 0) { echo ' selected'; } echo '>'.d_trad('allmonths').'</option>';
  #Every odd month
  echo '<option value=1'; if ($periodic_spec_monthly == 1) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly1').'</option>';
  #Every even month
  echo '<option value=2'; if ($periodic_spec_monthly == 2) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly2').'</option>';
  #Every quarter
  echo '<option value=3'; if ($periodic_spec_monthly == 3) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly3').'</option>';
  #Every semester
  echo '<option value=4'; if ($periodic_spec_monthly == 4) { echo ' selected'; } echo '>'.d_trad('periodic_spec_monthly4').'</option>';

  echo '</select>';
  echo ' ' . d_trad('prefix_specificdate') . ' <select name="day_monthly">';
  for ($i=1;$i<=31;$i++)
  {
    echo '<option value='.$i; if ($day_monthly == $i) { echo ' selected'; }
    echo '>'.$i.'</option>';
  }
  echo '</select>';
  echo '</td></tr><tr><td></td><td>';

  echo '<input type=radio name=periodic value=3'; if ($periodic == 3) { echo ' checked'; }
  echo '>'.d_trad('yearly').'</td><td>';
  echo '<select name="day_yearly">';
  for ($i=1;$i<=31;$i++)
  {
    echo '<option value='.$i; if ($day_yearly == $i) { echo ' selected'; }
    echo '>'.$i.'</option>';
  }
  echo '</select>';
  echo '  <select name="month_yearly">';
  for ($i=1;$i<=12;$i++)
  {
    echo '<option value='.$i; if ($month_yearly == $i) { echo ' selected'; }
    echo '>'. d_trad('month2_' . $i ) . '</option>';
  }
  echo '</select>';
  echo '</td></tr>';
}

echo '<tr><td>'.d_trad('time:').'</td><td colspan=2>';
echo '<input type=time name=planningtimestart value="' . $planningtimestart . '" size=5> &nbsp; '.d_trad('time_to').' &nbsp; <input type=time name=planningtimestop value="' . $planningtimestop . '" size=5></td></tr>';

echo '<tr><td>' . d_trad('planningcomment') . ':</td><td colspan=2>';
echo '<input type=text name="planningcomment" value="'.$planningcomment.'" size=80></td></tr>';

if ($planningid > 0)
{
  echo '<tr><td>' . d_trad('deleted:') . '</td><td colspan=2><input type=checkbox name="deleted"';
  if ($deleted == 1) { echo ' checked'; }
  echo ' value=1 ></td></tr>';
}
echo '<tr><td colspan="2" align="center"><input type=hidden name="saveme" value="1"><input type=hidden name="planningid" value="' . $planningid . '">
<input type=hidden name="adminmenu" value="' . $adminmenu . '"><input type=hidden name="salesmenu" value="' . $salesmenu . '">';
echo '<tr><td colspan="3" align="center"><input type="submit" value="' . d_trad('save') . '"></td></tr>';
echo '</table>';

if (!$simple_form)
{
  echo '<br><br><table border=0 cellspacing=1 cellpadding=1>';
  echo '<tr><td><b>' . d_trad('employee') . '</b></td><td>&nbsp;&nbsp;&nbsp;</td><td><b>' . d_trad('client') . '</b></td><td>&nbsp;&nbsp;&nbsp;</td><td><b>' . d_trad('resource') . '</b></td></tr>';

  $num_resources = $_SESSION['ds_num_resources'];
  $query = 'select planning_employeeid,employeeid,linenr from planning_employee where planningid=?';
  $query_prm = array($planningid);
  require('inc/doquery.php');
  if ($num_results > $num_resources) { $num_resources = $num_results; }
  for ($i=0;$i<$num_results;$i++)
  {
    $linenr = $query_result[$i]['linenr'];
    $p_eidA[$linenr] = $query_result[$i]['planning_employeeid'];
    $eidA[$linenr] = $query_result[$i]['employeeid'];
  }
  $query = 'select planning_clientid,clientid,linenr from planning_client where planningid=?';
  $query_prm = array($planningid);
  require('inc/doquery.php');
  if ($num_results > $num_resources) { $num_resources = $num_results; }
  for ($i=0;$i<$num_results;$i++)
  {
    $linenr = $query_result[$i]['linenr'];
    $p_cidA[$linenr] = $query_result[$i]['planning_clientid'];
    $cidA[$linenr] = $query_result[$i]['clientid'];
  }
  $query = 'select planning_resourceid,resourceid,linenr from planning_resource where planningid=?';
  $query_prm = array($planningid);
  require('inc/doquery.php');
  if ($num_results > $num_resources) { $num_resources = $num_results; }
  for ($i=0;$i<$num_results;$i++)
  {
    $linenr = $query_result[$i]['linenr'];
    $p_ridA[$linenr] = $query_result[$i]['planning_resourceid'];
    $ridA[$linenr] = $query_result[$i]['resourceid'];
  }

  for ($i=1;$i<=$num_resources;$i++)
  {
    echo '<tr><td>';
    $dp_itemname = 'employee'; $dp_addtoid = $i;
    $dp_selectedid = $_POST[$dp_itemname . $i . 'id'];

    $planning_employeeid = $p_eidA[$i];
    if ($modplanningid > 0) { $dp_selectedid = $eidA[$i]; }
    if ($saveme)
    {
      if ($planning_employeeid > 0)
      {
        $query = 'update planning_employee set employeeid=? where planning_employeeid=?';
        $query_prm = array($dp_selectedid,$planning_employeeid);
        require('inc/doquery.php');
      }
      elseif ($dp_selectedid > 0 && $planningid > 0)
      {
        $query = 'insert into planning_employee (planningid,employeeid,linenr) values (?,?,?)';
        $query_prm = array($planningid,$dp_selectedid,$i);
        require('inc/doquery.php');
      }
    }
    require('inc/selectitem.php');
    echo '</td><td></td>';
    
    echo '<td>';
    $client = $_POST['client'.$i];
    $noautofocus = 1; $dp_addtoid = $i;

    $planning_clientid = $p_cidA[$i];
    if ($modplanningid > 0) { $client = $cidA[$i];if ($client == 0) { $client = ''; } }
    if ($saveme)
    {
      if ($clientid < 1) { $clientid = 0; }
      require('inc/findclient.php'); # getting $clientid from $client
      if ($planning_clientid > 0)
      {
        $query = 'update planning_client set clientid=? where planning_clientid=?';
        $query_prm = array($clientid,$planning_clientid);
        require('inc/doquery.php');
      }
      elseif ($clientid > 0 && $planningid > 0)
      {
        $query = 'insert into planning_client (planningid,clientid,linenr) values (?,?,?)';
        $query_prm = array($planningid,$clientid,$i);
        require('inc/doquery.php');
      }
    }
    $dp_nodescription = 1;$dp_addtoid=$i;  
    require('inc/selectclient.php');
    echo '</td><td></td><td>';
    
    $dp_itemname = 'resource'; $dp_addtoid = $i;
    $dp_selectedid = $_POST[$dp_itemname . $i . 'id'];
    /*
    $query = 'select planning_resourceid,resourceid from planning_resource where planningid=? and linenr=?';
    $query_prm = array($planningid,$i);
    require('inc/doquery.php');
    */
    $planning_resourceid = $p_ridA[$i];
    if ($modplanningid > 0) { $dp_selectedid = $ridA[$i]; }
    if ($saveme)
    {
      if ($planning_resourceid > 0)
      {
        $query = 'update planning_resource set resourceid=? where planning_resourceid=?';
        $query_prm = array($dp_selectedid,$planning_resourceid);
        require('inc/doquery.php');
      }
      elseif ($dp_selectedid > 0 && $planningid > 0)
      {
        $query = 'insert into planning_resource (planningid,resourceid,linenr) values (?,?,?)';
        $query_prm = array($planningid,$dp_selectedid,$i);
        require('inc/doquery.php');
      }
    }
    require('inc/selectitem.php');
    echo '</td><td></td>';
    echo '</tr>';
  }
  echo '</table>';
}
echo '</form>';

?>