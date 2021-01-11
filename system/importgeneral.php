<?php

# config
$separator = ';';

echo '<h2>Import employé EP 2019 01 13</h2>';

if ($_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    
    $referencenumber = $lineA[2];
    $employeename = $lineA[0];
    $employeefirstname = $lineA[1];
    $contractid = $lineA[3];
    $hiringdate = $lineA[4];
    $basesalary = (int) preg_replace('/\s+/', '', $lineA[5]); # new
    $hourspermonth = $lineA[6]; # new
    $dateofbirth = $lineA[7];
    $sex = $lineA[9]; #n
    $dn = $lineA[10]; #n
    $familysituation = $lineA[12]; #n id
    $numchildren = 0; #n
    $geoaddress = $lineA[13];
    
    $postaladdress1 = '';
    $postalcode = '';
    $townid = 1;
    
    $employeedepartmentid = 0; # employee [lookup id from text]
    $employeesectionid = 0; # employee [lookup id from text]
    $jobid = $lineA[14]; # employee [lookup id from text]
    $scheduleid = 0; # employee [lookup id from text] (Type horaire)
    $ismanager = 0; # [convert integer] (Manager)
    $employeeemail = ''; # employee
    
    $salary_account_title = $lineA[17];
    $salary_account = $lineA[18];
    $salary_bankid = $lineA[19];
    
    echo '<tr>';
    foreach ($lineA as $x => $value)
    {
      echo '<td>['.$x.']' . $value;
    }
    
    $error = 0;
/*
    $query = 'select employeedepartmentid from employeedepartment where employeedepartmentname=?';
    $query_prm = array($employeedepartmentid);
    require('inc/doquery.php');
    if ($query_result[0]['employeedepartmentid'] < 1) { echo 'employeedepartmentid: ',$employeedepartmentid; exit; }
    $employeedepartmentid = $query_result[0]['employeedepartmentid'];
    
    $query = 'select employeesectionid from employeesection where employeesectionname=?';
    $query_prm = array($employeesectionid);
    require('inc/doquery.php');
    if ($query_result[0]['employeesectionid'] < 1)
    {
      $query = 'insert into employeesection (employeesectionname,employeedepartmentid) values (?,2)';
      $query_prm = array($employeesectionid);
      require('inc/doquery.php');
      $employeesectionid = $query_insert_id;
    }
    else { $employeesectionid = $query_result[0]['employeesectionid']; }*/

    /*
    $query = 'select scheduleid from schedule where schedulename=?';
    $query_prm = array($scheduleid);
    require('inc/doquery.php');
    if ($query_result[0]['scheduleid'] < 1)
    {
      $scheduleid = 1;
    }
    else { $scheduleid = $query_result[0]['scheduleid']; }
    
    if ($ismanager == 'x') { $ismanager = 1; }
    else { $ismanager = 0; }
    */
    $pos1 = strpos($hiringdate, '/')+1;
    $pos2 = strpos($hiringdate, '/', $pos1+1)+1;
    $newhiringdate = mb_substr($hiringdate,$pos2,4);
    $kladd2 = mb_substr($hiringdate,0,$pos1-1);
    if (strlen($kladd2) < 2) { $kladd2 = '0'.$kladd2; }
    $kladd = mb_substr($hiringdate,$pos1,2);
    if ($kladd[1] == '/') { $kladd = '0'.$kladd[0]; }
    $newhiringdate = $newhiringdate . '-' . $kladd;
    $newhiringdate = $newhiringdate . '-' . $kladd2;  
    $hiringdate = $newhiringdate;
    
    $pos1 = strpos($dateofbirth, '/')+1;
    $pos2 = strpos($dateofbirth, '/', $pos1+1)+1;
    $newdateofbirth = mb_substr($dateofbirth,$pos2,4);
    $kladd2 = mb_substr($dateofbirth,0,$pos1-1);
    if (strlen($kladd2) < 2) { $kladd2 = '0'.$kladd2; }
    $kladd = mb_substr($dateofbirth,$pos1,2);
    if ($kladd[1] == '/') { $kladd = '0'.$kladd[0]; }
    $newdateofbirth = $newdateofbirth . '-' . $kladd;
    $newdateofbirth = $newdateofbirth . '-' . $kladd2;
    $dateofbirth = $newdateofbirth;
    
    /*$query = 'select townid from town where townname=?';
    $query_prm = array($townid);
    require('inc/doquery.php');
    if ($num_results == 0 || $query_result[0]['townid'] < 1)
    {
      $townid = 1;
    }
    else { $townid = $query_result[0]['townid']; }*/
    #$error=1;
    if ($error == 0 && $i>1)
    {
      $query = 'select bankid from bank where bankname=?';
      $query_prm = array($salary_bankid);
      require('inc/doquery.php');
      if ($query_result[0]['bankid'] < 1)
      {
        $query = 'insert into bank (bankname) values (?)';
        $query_prm = array($salary_bankid);
        require('inc/doquery.php');
        $salary_bankid = $query_insert_id;
      }
      else { $salary_bankid = $query_result[0]['bankid']; }
      
      $query = 'select jobid from job where jobname=?';
      $query_prm = array($jobid);
      require('inc/doquery.php');
      if ($query_result[0]['jobid'] < 1)
      {
        $query = 'insert into job (jobname) values (?)';
        $query_prm = array($jobid);
        require('inc/doquery.php');
        $jobid = $query_insert_id;
      }
      else { $jobid = $query_result[0]['jobid']; }
      
      $query = 'select contractid from contract where contractname=?';
      $query_prm = array($contractid);
      require('inc/doquery.php');
      if ($query_result[0]['contractid'] < 1)
      {
        $query = 'insert into job (contractname) values (?)';
        $query_prm = array($contractid);
        require('inc/doquery.php');
        $contractid = $query_insert_id;
      }
      else { $contractid = $query_result[0]['contractid']; }
      
      $query = 'select familysituationid from familysituation where familysituationname=?';
      $query_prm = array($familysituation);
      require('inc/doquery.php');
      if ($num_results == 0 || $query_result[0]['familysituationid'] < 1)
      {
        $query = 'insert into familysituation (familysituationname) values (?)';
        $query_prm = array($familysituation);
        require('inc/doquery.php');
        $familysituationid = $query_insert_id;
      }
      else { $familysituationid = $query_result[0]['familysituationid']; }

      $query = 'insert into employee (salary_bankid,salary_account,salary_account_title,hourspermonth,basesalary,referencenumber,employeename,employeefirstname,employeedepartmentid,employeesectionid,jobid,contractid,scheduleid,ismanager,hiringdate,employeeemail) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
      $query_prm = array($salary_bankid,$salary_account,$salary_account_title,$hourspermonth,$basesalary,$referencenumber,$employeename,$employeefirstname,$employeedepartmentid,$employeesectionid,$jobid,$contractid,$scheduleid,$ismanager,$hiringdate,$employeeemail);
      require('inc/doquery.php');
      $employeeid = $query_insert_id;
      $query = 'insert into employeepersoinfos (familysituationid,numchildren,employeeid,dateofbirth,dn,geoaddress,postaladdress1,postalcode) values (?,?,?,?,?,?,?,?)';
      $query_prm = array($familysituationid,$numchildren,$employeeid,$dateofbirth,$dn,$geoaddress,$postaladdress1,$postalcode);
      require('inc/doquery.php');
    }
    #else { echo '<td> NOT Inserting this line'; }

  }
  echo '</table>';
}
else
{
  ?>
  <form enctype="multipart/form-data" method="post" action="system.php">
  <table>
  <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
  <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
}

?>