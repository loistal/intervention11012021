<?php 

$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$PA['employeeid'] = 'uint';
require('inc/readpost.php');

$ds_term_manager = $_SESSION['ds_term_manager'];
$ds_term_interimmanager = $_SESSION['ds_term_interimmanager'];
$ds_curdate = $_SESSION['ds_curdate'];

if (isset($_POST['add']) && $_POST['add'] == 1) { $currentstep = $STEP_FORM_ADD; }

$itemname = d_trad('employee');
$title = d_trad('addparam:',$itemname);
if ($currentstep == $STEP_FORM_MODIFY || $currentstep == $STEP_FORM_VALIDATE_MOD ) {$title = d_trad('modifyparam:',$itemname);}
echo '<h2>' . $title . '</h2>';

$employeeid_query = NULL;
if ($employeeid > 0) {$employeeid_query = $employeeid;}

if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
{    
  $step = $STEP_FORM_VALIDATE_MOD;
  if ($currentstep == $STEP_FORM_ADD ) { $step = $STEP_FORM_VALIDATE_ADD; }
  $employeename = $_POST['employeename'];
  $employeefirstname = $_POST['employeefirstname'];
  $employeemiddlename = $_POST['employeemiddlename'];
	$employeecompletename = d_output($employeename) . ' ' . d_output($employeefirstname). ' ' . d_output($employeemiddlename);
	$employeetotallycompletename = d_output($employeename) . ' ' . d_output($employeefirstname). ' ' . d_output($employeemiddlename);
  $issales = $_POST['issales']+0;
  $isdelivery = $_POST['isdelivery']+0;
  $ispicking = $_POST['ispicking']+0;
  $iscashier = $_POST['iscashier']+0;
  $jobid = $_POST['jobid']+0;
  $hourly_pay = $_POST['hourly_pay']+0;
  $contractid = $_POST['contractid']+0;
  $employeecategoryid = $_POST['employeecategoryid']+0;
  #$scheduleid = $_POST['scheduleid']+0;
  #$scheduleid_old = $_POST['scheduleid_old'] +0;    
  $ismanager = $_POST['team1id']+0;
  #$interimmanagerid = $_POST['employeeinterimid']+0;
  $referencenumber = $_POST['referencenumber'];
  $badgenumber = $_POST['badgenumber'];
  $teamid = (int) $_POST['teamid'];
  #$employeedepartmentid = $_POST['employeedepartmentid']+0;
  #$employeesectionid = $_POST['employeesectionid']+0;
  $employeeemail = $_POST['employeeemail'];
  $datename = 'hiringdate'; require('inc/datepickerresult.php');
  $datename = 'exitdate'; require('inc/datepickerresult.php');
  $deleted = $_POST['deleted']+0;
  $unionrep = $_POST['unionrep']+0;
  $weeklyhoursid = $_POST['weeklyhoursid']+0;
  $basesalary = $_POST['basesalary']+0;
  $hourspermonth = $_POST['hourspermonth']+0;
  $payslipinfo = $_POST['payslipinfo'];
  $salary_account_title = $_POST['salary_account_title'];
  $salary_account = $_POST['salary_account'];
  $salary_bankid = $_POST['bankid']+0;
  $default_paymenttypeid = $_POST['default_paymenttypeid']+0;
  $default_bankaccountid = $_POST['bankaccountid']+0;
  require('inc/findclient.php');
  $employee_is_clientid = $clientid;
 
  if ($currentstep == $STEP_FORM_VALIDATE_ADD)
  {
    $query_replace = 'INSERT INTO employee (hourly_pay,employee_is_clientid,default_paymenttypeid,default_bankaccountid,exitdate,salary_account_title,salary_account,salary_bankid,basesalary,hourspermonth,payslipinfo,weeklyhoursid,unionrep,teamid,employeeid,employeename,employeefirstname,employeemiddlename,issales,isdelivery,ispicking,iscashier,jobid,contractid,employeecategoryid,ismanager,referencenumber,badgenumber,employeeemail,hiringdate,deleted) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
    $query_replace_prm = array($hourly_pay,$employee_is_clientid,$default_paymenttypeid,$default_bankaccountid,$exitdate,$salary_account_title,$salary_account,$salary_bankid,$basesalary,$hourspermonth,$payslipinfo,$weeklyhoursid,$unionrep,$teamid,$employeeid_query,$employeename,$employeefirstname,$employeemiddlename,$issales,$isdelivery,$ispicking,$iscashier,$jobid,$contractid,$employeecategoryid,$ismanager,$referencenumber,$badgenumber,$employeeemail,$hiringdate,$deleted);
  }
  else
  {
    $query_replace = 'UPDATE employee set hourly_pay=?,employee_is_clientid=?,default_paymenttypeid=?,default_bankaccountid=?,exitdate=?,salary_account_title=?,salary_account=?,salary_bankid=?,basesalary=?,hourspermonth=?,payslipinfo=?,weeklyhoursid=?,unionrep=?,teamid=?,employeename=?,employeefirstname=?,employeemiddlename=?,issales=?,isdelivery=?,ispicking=?,iscashier=?,jobid=?,contractid=?,employeecategoryid=?,ismanager=?,referencenumber=?,badgenumber=?,employeeemail=?,hiringdate=?,deleted=? where employeeid=?';
    $query_replace_prm = array($hourly_pay,$employee_is_clientid,$default_paymenttypeid,$default_bankaccountid,$exitdate,$salary_account_title,$salary_account,$salary_bankid,$basesalary,$hourspermonth,$payslipinfo,$weeklyhoursid,$unionrep,$teamid,$employeename,$employeefirstname,$employeemiddlename,$issales,$isdelivery,$ispicking,$iscashier,$jobid,$contractid,$employeecategoryid,$ismanager,$referencenumber,$badgenumber,$employeeemail,$hiringdate,$deleted,$employeeid_query);
  }
   
  if($employeename == '' && $employeefirstname == '')
  {
    echo '<p class="alert">' . d_trad('employeenamemustnotbeempty') . '<p>';      
  }
  else 
  {
    $nodupe = 1;        
    
    if ($currentstep == $STEP_FORM_VALIDATE_ADD)
    {
      #check if employee already exist with this name    
      $query = 'select employeeid from employee where employeename=? and employeefirstname=? and employeemiddlename=? and deleted=0';
      $query_prm = array($employeename,$employeefirstname,$employeemiddlename);    
      if ($employeeid_query > 0) { $query .= ' and employeeid <> ?'; array_push($query_prm,$employeeid_query);}
      require('inc/doquery.php');

      if ($num_results > 0)
      {
        echo '<p class="alert">' . d_trad('employeenamealreadyexists',$employeetotallycompletename) . '<p>';
        $nodupe = 0;
      }
    }
    
    if ($nodupe)
    {
      $query = $query_replace;
      $query_prm = $query_replace_prm;
      require('inc/doquery.php');
      if ($employeeid_query > 0) { echo '<p>' . d_trad('successmodemployee:',$employeecompletename) . '</p>';  }
      else {echo '<p>' . d_trad('successaddemployee:',$employeecompletename) . '</p>'; }
      if ($currentstep == $STEP_FORM_VALIDATE_ADD) { $employeeid_query = $query_insert_id;}
    }      
  }
}//update

$nodata = 0;
if ($currentstep == $STEP_FORM_MODIFY)
{
  $step = $STEP_FORM_VALIDATE_MOD;
  $query = 'select * from employee where employeeid=?';
  $query_prm = array($employeeid_query);
  require ('inc/doquery.php');
  $item_query = $query_result;
  if ($num_results > 0)
  {
    $employeename = $item_query[0]['employeename'];
    $employeefirstname = $item_query[0]['employeefirstname'];
    $employeemiddlename = $item_query[0]['employeemiddlename'];
    $issales = $item_query[0]['issales']+0;
    $isdelivery = $item_query[0]['isdelivery']+0;
    $ispicking = $item_query[0]['ispicking']+0;
    $hourly_pay = $item_query[0]['hourly_pay']+0;
    $iscashier = $item_query[0]['iscashier']+0;
    $jobid = $item_query[0]['jobid']+0;
    $contractid = $item_query[0]['contractid']+0;
    $employeecategoryid = $item_query[0]['employeecategoryid']+0;
    #$scheduleid = $item_query[0]['scheduleid']+0; 
    $ismanager = $item_query[0]['ismanager']+0;
    #$interimmanagerid = $item_query[0]['interimmanagerid']+0;
    $referencenumber = $item_query[0]['referencenumber'];
    $badgenumber = $item_query[0]['badgenumber'];
    $teamid = $item_query[0]['teamid'];
    #$employeedepartmentid = $item_query[0]['employeedepartmentid']+0;
    #$employeesectionid = $item_query[0]['employeesectionid']+0;
    $employeeemail = $item_query[0]['employeeemail'];    
    $hiringdate = $item_query[0]['hiringdate'];
    $exitdate = $item_query[0]['exitdate'];
    $deleted = $item_query[0]['deleted']+0;
    $unionrep = $item_query[0]['unionrep']+0;
    $weeklyhoursid = $item_query[0]['weeklyhoursid']+0;
    $basesalary = $item_query[0]['basesalary']+0;
    $hourspermonth = $item_query[0]['hourspermonth']+0;
    $payslipinfo = $item_query[0]['payslipinfo'];
    $salary_account_title = $item_query[0]['salary_account_title'];
    $salary_account = $item_query[0]['salary_account'];
    $salary_bankid = $item_query[0]['salary_bankid']+0;
    $default_paymenttypeid = $item_query[0]['default_paymenttypeid']+0;
    $default_bankaccountid = $item_query[0]['default_bankaccountid']+0;
    $employee_is_clientid = $item_query[0]['employee_is_clientid'];
  }
  else 
  { 
    echo '<p>' . d_trad('nodata',d_trad('employee')) . '<p>'; 
    $nodata = 1;    
  }
}
else if ($currentstep == $STEP_FORM_ADD)
{
  $step = $STEP_FORM_VALIDATE_ADD; 
  $name = ''; $employeename = '';$employeefirstname = '';$employeemiddlename = '';
  $issales = 0; $isdelivery = 0; $ispicking = 0; $iscashier= 0; $employeecategoryid = 0; $ismanager = 0;
  $referencenumber = '';$badgenumber = ''; $exitdate = NULL; $hiringdate = NULL; $teamid = 0; $basesalary = 0; $hourspermonth = 0; $payslipinfo = '';
  $employeeid_query = -1;
}

if ($nodata == 0)
{
  echo '<form method="post" action="hr.php"><table>';
  echo '<tr><td>' . d_trad('lastname:') . '</td><td><input autofocus type="text" name="employeename" value="' . d_input($employeename) . '" size=50></td></tr>';    
  echo '<tr><td>' . d_trad('firstname:') . '</td><td><input autofocus type="text" name="employeefirstname" value="' . d_input($employeefirstname) . '" size=50></td></tr>';    
  echo '<tr><td>' . d_trad('middlename:') . '</td><td><input autofocus type="text" name="employeemiddlename" value="' . d_input($employeemiddlename) . '" size=50></td></tr>';    
  echo '<tr><td>' . d_trad('referencenumber:') . '</td><td><input type="text" name="referencenumber" value="' . d_input($referencenumber) . '"></td></tr>';       
  echo '<tr><td>' . d_trad('badgenumber:') . '</td><td><input type="text" name="badgenumber" value="' . d_input($badgenumber) . '">';
  echo '<tr><td>'; $clientid = $employee_is_clientid; $dp_description = 'Correspond au client:'; require('inc/selectclient.php');
  if ($_SESSION['ds_time_management'] == 1) { echo ' <span class="alert">Pour votre badgeuse BioStar, ce numéro doit être unique et renseigné.</span>'; }
  
  $dp_itemname = 'team'; $dp_description = d_trad('team');$dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $teamid;
  require('inc/selectitem.php');
  /*
  echo ' &nbsp; ' . $ds_term_manager . ' <input type="checkbox" name="ismanager" value="1"';
  if ($ismanager) { echo ' CHECKED'; }
  echo '>';
  */
  $dp_itemname = 'team'; $dp_addtoid = 1; $dp_description = 'Manager pour équipe (RH)'; $dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $ismanager;
  require('inc/selectitem.php');

  $dp_itemname = 'employeecategory'; $dp_description = d_trad('employeecategory'); $dp_selectedid = $employeecategoryid;
  require('inc/selectitem.php');
  $dp_itemname = 'weeklyhours'; $dp_description = 'Horaires par défaut'; $dp_selectedid = $weeklyhoursid;
  require('inc/selectitem.php');
  echo '<tr><td>Délégué du personnel<td><input type=checkbox name="unionrep" value=1';
  if ($unionrep) { echo ' checked'; }
  echo '>';
  $dp_itemname = 'job'; $dp_description = d_trad('job'); $dp_selectedid = $jobid;
  require('inc/selectitem.php');  
  $dp_itemname = 'contract'; $dp_description = d_trad('contract'); $dp_selectedid = $contractid;
  require('inc/selectitem.php'); 
  echo '<tr><td>' . d_trad('hiringdate:') . '</td><td>';
  $datename = 'hiringdate'; $dp_datepicker_min = '1950-01-01';
  $dp_setempty=1;$selecteddate=$hiringdate;require('inc/datepicker.php');
  
  echo '<tr><td>Date sortie:</td><td>';
  $datename = 'exitdate';
  $dp_setempty=1;$selecteddate=$exitdate;require('inc/datepicker.php');

  echo '<tr><td>Salaire de base :<td><input type="text" STYLE="text-align:right" name="basesalary" value="'.$basesalary.'" size=20>
  &nbsp; Payé à l\'heure : <input type="checkbox" name="hourly_pay" value=1';
  if ($hourly_pay) { echo ' checked'; }
  echo '>
  <tr><td>Horaire de référence :<td><input type="text" STYLE="text-align:right" name="hourspermonth" value="'.$hourspermonth.'" size=20>
  <tr><td>Compte salaire intitulé :<td><input type="text" STYLE="text-align:right" name="salary_account_title" value="'.$salary_account_title.'" size=20>
  <tr><td>Compte salaire :<td><input type="text" STYLE="text-align:right" name="salary_account" value="'.$salary_account.'" size=20>';
  
  $dp_itemname = 'bank'; $dp_description = 'Compte salaire banque'; $dp_selectedid = $salary_bankid;
  require('inc/selectitem.php');
  
  echo '<tr><td>Paiement par défaut:<td colspan=3><select name="default_paymenttypeid">
  <option value=0></option>
  <option value=3'; if ($default_paymenttypeid==3) { echo ' selected'; } echo '>Virement</option>
  <option value=2'; if ($default_paymenttypeid==2) { echo ' selected'; } echo '>Cheque</option>
  </select>
   &nbsp; ';
  $dp_itemname = 'bankaccount'; $dp_notable=1; $dp_selectedid=$default_bankaccountid;
  require('inc/selectitem.php');
  
  echo '<tr><td>Infos salaire:<td><textarea autofocus type="textarea" name="payslipinfo" cols=80 rows=5>' . d_input($payslipinfo) . '</textarea>';
  
  echo '<tr><td>' . d_trad('email:'). '</td><td><input type=text size=50 name=employeeemail value=' . d_input($employeeemail). '></td></tr>';
  echo '<tr><td>&nbsp;</td></tr>';
  echo '<tr><td>Afficher sur facture/paiement/évènement
  <td><input type="checkbox" name="issales" value="1"'; if ($issales) echo ' CHECKED'; echo '>';
  echo '<tr><td>' . d_trad('clientlink:') . '</td><td><input type="checkbox" name="iscashier" value="1"'; if ($iscashier) echo ' CHECKED'; echo '></td></tr>';
  echo '<tr><td>Afficher sur livraison
  <td><input type="checkbox" name="isdelivery" value="1"'; if ($isdelivery) echo ' CHECKED'; echo '>';
  echo '<tr><td>Afficher sur picking
  <td><input type="checkbox" name="ispicking" value="1"'; if ($ispicking) echo ' CHECKED'; echo '>';
  echo '<tr><td>&nbsp;</td></tr>';
  ### 2015 07 10 do not remove the possibility to delete users (there is a "Mes Options" for showing deleted employees)
  echo '<tr><td>Supprimé:</td><td><input type="checkbox" name="deleted" value="1"';
  if ($deleted) { echo ' CHECKED'; }
  echo '></td></tr>';
  ###
  #echo '<input type="hidden" name="scheduleid_old" value="' .$scheduleid . '">'; 
  echo '<tr><td colspan="2" align="center"><input type=hidden name="employeeid" value="' . $employeeid_query . '"><input type=hidden name="hrmenu" value="' . $hrmenu . '"><input type=hidden name="step" value="' . $step . '"><input type="submit" value="' . d_trad('validate') . '"></td></tr>';
  echo '</table></form>';
}
    

?>