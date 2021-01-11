<?php

# Build web page
require ('inc/standard.php');
require ('inc/top.php');
require ('inc/logo.php');
require ('inc/menu.php');

# table
?>
</div><div id="wrapper">
<title>Jurion Protection</title>
<div id="leftmenu">
  <div id="selectactionbar">
    <div class="selectaction">
      <?php
      echo '&nbsp; <a href="custom.php?custommenu=import">Import CSV</a><br>';
      ?>
      <br>
    </div>
  </div>

<?php
require ('inc/searchbox.php');
require ('inc/copyright.php');
?>
</div><div id="mainprogram">
<?php

$custommenu = "";
if (isset($_GET['custommenu'])) { $custommenu = $_GET['custommenu']; }
if (isset($_POST['custommenu'])) { $custommenu = $_POST['custommenu']; }
$custommenu = d_safebasename($custommenu);

$currentstep = 0;
if (isset($_POST['step'])) { $currentstep = $_POST['step']+0; }

# Go to the menuitem
switch($custommenu)
{
  
  case 'import':
  
  $PA['month'] = 'uint';
  $PA['year'] = 'uint';
  require('inc/readpost.php');

  # config
  $separator = ';';

  echo '<h2>Import CSV (a vérifier)</h2>';

  if (isset($_POST['importme']) && $_POST['importme'] == 1)
  {
    require('preload/employee.php');
    $payslipdate = d_builddate(1,$month,$year);
    $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
    $i = 0;
    echo '<table class=report>';
    while ($line=fgets($fp))
    {
      $i++;
      $lineA = explode($separator, $line);
      echo '<tr>';
      foreach ($lineA as $x => $value)
      {
        echo '<td>['.$x.']' . $value;
      }
      
      $error = 0;
      $referencenumber = trim($lineA[2]);
      $employeeid = array_search($referencenumber, $employee_referencenumberA);
      if (!$employeeid) { $error = 1; }

      if ($error == 0 && $i>1)
      {
        $comp = $lineA[3]; # Heures complémentaires 25%
        $sup = $lineA[4]; # Heures supplémentaires
        $m_ferie = $lineA[5]; # Majoration férié
        $m_night = $lineA[6]; # Majoration nuit
        $m_sunday = $lineA[7]; # Majoration dimanche
        $advance = $lineA[8]; # Avance
        $hours_vacation = $lineA[9]; # Heure congés
        $comment_vacation = $lineA[10]; # Date congés
        $vacation_days = $lineA[11]; # Jours congés pris
        $absence = $lineA[12]; # Absences
        $bonus = $lineA[13]; # Prime

        $query = 'select payslipid from payslip where employeeid=? and payslipdate=?';
        $query_prm = array($employeeid,$payslipdate);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'select default_paymenttypeid,default_bankaccountid from employee where employeeid=?';
          $query_prm = array($employeeid);
          require('inc/doquery.php');
          $paymenttypeid = $query_result[0]['default_paymenttypeid'];
          $bankaccountid = $query_result[0]['default_bankaccountid'];
          # adding 2.5 days by default, option???
          $query = 'insert into payslip (employeeid,payslipdate,paymenttypeid,bankaccountid,vacationdays_added) values (?,?,?,?,2.5)';
          $query_prm = array($employeeid,$payslipdate,$paymenttypeid,$bankaccountid);
          require('inc/doquery.php');
          $query = 'select payslipid from payslip where employeeid=? and payslipdate=?';
          $query_prm = array($employeeid,$payslipdate);
          require('inc/doquery.php');
          $payslipid = $query_result[0]['payslipid'];
        }
        else { $payslipid = $query_result[0]['payslipid']; }
        
        $query = 'select bankaccountid,paymenttypeid,employeeid,payslipdate,year(payslipdate) as year,month(payslipdate) as month,base_salary
        ,hourspermonth,payslipcomment,vacationdays_added,vacationdays_used,status
        from payslip where payslipid=?';
        $query_prm = array($payslipid);
        require('inc/doquery.php');
        $payslipdate = $query_result[0]['payslipdate'];
        $year = $query_result[0]['year'];
        $month = $query_result[0]['month'];
        $base_salary = $query_result[0]['base_salary']+0;
        $hourspermonth = $query_result[0]['hourspermonth']+0;
        if ($hourspermonth == 0) { $hourspermonth = 1; }
        
        $query = 'select * from employee where employeeid=?';
        $query_prm = array($employeeid);
        require('inc/doquery.php');
        $e_base_salary = $query_result[0]['basesalary']+0;
        $e_hourspermonth = $query_result[0]['hourspermonth']+0;
        $e_payslipinfo = $query_result[0]['payslipinfo'];
        $jobid = $query_result[0]['jobid'];
        $hiringdate = $query_result[0]['hiringdate'];
        
        ### find seniority
        $query = 'select value from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array(10,$payslipid);
        require('inc/doquery.php');
        if ($num_results) { $seniority_bonus = $query_result[0]['value']; }
        else
        {
          $value = 0;
          $duration_years = substr($payslipdate,0,4) - substr($hiringdate,0,4);
          $duration_months = substr($payslipdate,5,2) - substr($hiringdate,5,2);
          if ($duration_months < 0)
          {
            $duration_years--;
            $duration_months += 12;
          }
          if ($duration_years >= 3)
          {
            $value = $duration_years;
          }
          if ($_SESSION['seniority_bonus_calc'] == 2 && $duration_years >= 10)
          {
            $value += ($duration_years-10)*0.5;
          }
          if ($value > 25) { $value = 25; }
          $seniority_bonus_percent = $value; # need to mod value of absences
          $value = ($value * $base_salary)/100;
          $seniority_bonus = myround($value);
        }
        ###
        
        $rank = 25; $override = 0; $negative = 0; $value_e = 0;
        $name = ''; $comment = ''; $comment_e = '';
        $rate = myround(($base_salary+$seniority_bonus)/$hourspermonth,2);
        $value = myround(d_multiply($comp,$rate));
        $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
        }
        else
        {
          $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
        }
        $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
        require('inc/doquery.php');
        
        $rank = 31; $override = 0; $negative = 0; $value_e = 0;
        $name = ''; $comment = ''; $comment_e = 'Majoration férié';
        $value = $m_ferie;
        $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
        }
        else
        {
          $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
        }
        $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
        require('inc/doquery.php');
        
        $rank = 32; $override = 0; $negative = 0; $value_e = 0;
        $name = ''; $comment = ''; $comment_e = 'Majoration nuit';
        $value = $m_night;
        $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
        }
        else
        {
          $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
        }
        $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
        require('inc/doquery.php');
        
        $rank = 33; $override = 0; $negative = 0; $value_e = 0;
        $name = ''; $comment = ''; $comment_e = 'Majoration dimanche';
        $value = $m_sunday;
        $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
        }
        else
        {
          $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
        }
        $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
        require('inc/doquery.php');
        
        $rank = 50; $negative = 1; $value_e = 0;
        $name = ''; $comment = ''; $comment_e = $comment_vacation;
        $rate = myround(($base_salary+$seniority_bonus)/$hourspermonth,2);
        $value = d_multiply($hours_vacation,$rate);
        $override = $rate;
        $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
        }
        else
        {
          $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
        }
        $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
        require('inc/doquery.php');
        
        $rank = 35; $negative = 1; $value_e = 0;
        $name = ''; $comment = ''; $comment_e = '';
        $rate = myround($base_salary/$hourspermonth,2);
        $value = d_multiply($absence,$rate);
        $override = 0;
        $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
        }
        else
        {
          $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
        }
        $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
        require('inc/doquery.php');
        
        $rank = 20; $negative = 0; $value_e = 0;
        $name = ''; $comment = ''; $comment_e = '';
        $value = $bonus;
        $override = 0;
        $query = 'select payslip_line_netid from payslip_line_net where `rank`=? and payslipid=?';
        $query_prm = array($rank,$payslipid);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into payslip_line_net (negative,value,override,value_employer,payslip_line_name,payslip_line_comment,payslip_line_comment_employer,`rank`,payslipid) values (?,?,?,?,?,?,?,?,?)';
        }
        else
        {
          $query = 'update payslip_line_net set negative=?,value=?,override=?,value_employer=?,payslip_line_name=?,payslip_line_comment=?,payslip_line_comment_employer=? where `rank`=? and payslipid=?';
        }
        $query_prm = array($negative,$value,$override,$value_e,$name,$comment,$comment_e,$rank,$payslipid);
        require('inc/doquery.php');
        
      }
      
      if ($error && $i>1) { echo '<td><span class="alert">Employé non trouvé</span>'; }

    }
    echo '</table>';
  }
  else
  {
    ?>
    <form enctype="multipart/form-data" method="post" action="custom.php">
    <table>
    <?php
    if ($month == 0)
    {
      $month = mb_substr($_SESSION['ds_curdate'],5,2);
      $year = mb_substr($_SESSION['ds_curdate'],0,4);
    }
    ?><tr><td>Mois:</td><td><select name="month"><?php
    for ($i=1; $i <= 12; $i++)
    {
      if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select><select name="year"><?php
    for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
    {
      if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
      else { echo '<option value="' . $i . '">' . $i . '</option>'; }
    }
    ?></select>
    <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
    <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="custommenu" value="<?php echo $custommenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
  }
  
  
  break;

  default:

  break;
}
?>

<?php
require ('inc/bottom.php');
?>