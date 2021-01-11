<?php

# config
$separator = chr(9);

echo '<h2>Import pré-paies COMÈTE</h2>';

if (isset($_POST['importme']) && $_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    if ($i > 1)
    {
      echo '<tr>';
      if ($lineA[0] != '')
      {
        for ($x=0; $x < 11; $x++)
        {
          if (isset($lineA[$x]))
          {
            $lineA[$x] = iconv('ascii', 'UTF-8//IGNORE', $lineA[$x]);
            if (strlen($lineA[$x]) > 50) { $lineA[$x] = substr($lineA[$x],0,50); }
            echo '<td>',' [',$x,'] ',$lineA[$x];
          }
        }
      }
    
      $referencenumber = $lineA[0];
      $employeename = $lineA[1];
      $geoaddress = $lineA[2];
      $postaladdress1 = $lineA[3];
      $postalcode = $lineA[4];
      $postaladdress2 = $lineA[5];
      $hiringdate = substr($lineA[6],6,4).'-'.substr($lineA[6],3,2).'-'.substr($lineA[6],0,2);
      $dn = $lineA[7];
      $hourspermonth = $lineA[8];
      $contractname = $lineA[9]; # TODO id
      $basesalary = $lineA[10];
      $query = 'select contractid from contract where contractname=?';
      $query_prm = array($contractname);
      require('inc/doquery.php');
      if ($num_results) { $contractid = $query_result[0]['contractid']; }
      else { $contractid = 1; }
      
      $query = 'select employeeid from employee where referencenumber=?';
      $query_prm = array($referencenumber);
      require('inc/doquery.php');
      if ($num_results)
      {
        $employeeid = $query_result[0]['employeeid'];
        $query = 'update employee set referencenumber=?,employeename=?,hiringdate=?,hourspermonth=?,contractid=?,basesalary=? where employeeid=?';
        $query_prm = array($referencenumber,$employeename,$hiringdate,$hourspermonth,$contractid,$basesalary,$employeeid);
        require('inc/doquery.php');
      }
      else
      {
        $query = 'insert into employee (referencenumber,employeename,hiringdate,hourspermonth,contractid,basesalary) values (?,?,?,?,?,?)';
        $query_prm = array($referencenumber,$employeename,$hiringdate,$hourspermonth,$contractid,$basesalary);
        require('inc/doquery.php');
      }
      
      $query = 'select employeepersoinfosid from employeepersoinfos where employeeid=?';
      $query_prm = array($employeeid);
      require('inc/doquery.php');
      if ($num_results)
      {
        $employeepersoinfosid = $query_result[0]['employeepersoinfosid'];
        $query = 'update employeepersoinfos set geoaddress=?,postaladdress1=?,postalcode=?,postaladdress2=?,dn=? where employeepersoinfosid=?';
        $query_prm = array($geoaddress,$postaladdress1,$postalcode,$postaladdress2,$dn,$employeepersoinfosid);
        require('inc/doquery.php');
      }
      else
      {
        $query = 'insert into employeepersoinfos (geoaddress,postaladdress1,postalcode,postaladdress2,dn,employeeid) values (?,?,?,?,?,?)';
        $query_prm = array($geoaddress,$postaladdress1,$postalcode,$postaladdress2,$dn,$employeeid);
        require('inc/doquery.php');
      }
    }
  }
  echo '</table>';
}
else
{
  ?>
  <form enctype="multipart/form-data" method="post" action="system.php">
  <table>
  <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
  <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Import"></td></tr></table></form>
  <br>
  <br>
  <p>Les champs importés sont:</p>
  <ol start=0>
  <li>MATRICULE &nbsp; <span class="alert">Identifiant</span>
  <li>NOM_PREN
  <li>ADR1
  <li>ADR2
  <li>CP
  <li>VILLE
  <li>DATE_ENTREE
  <li>NUMER_SECU
  <li>HRS_CONTRAT
  <li>STATUT
  <li>BRUT_MENSUEL
  </ol>
  <p>Veuillez nous contacter pour d'autres champs.</p>
  <?php
}

?>