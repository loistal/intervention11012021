<?php

$separator = ';';

$PA['importme'] = 'uint';
require('inc/readpost.php');

echo '<h2>Client import MVB 2020 12 29</h2>';

if ($importme == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    echo '<tr>';
    for ($x=0; $x < 8; $x++)
    {
      echo '<td>',' [',$x,'] ',$lineA[$x];
    }
    
    if ($i > 3)
    {
      $clientcode = '';
      $clientcomment = $lineA[7];
      $clientname = d_encode($lineA[1]);
      $use_loyalty_points = 0;
      $postalcode = '';
      $address = $lineA[4];
      $postaladdress = '';
      $loyalty_start = 0;
      $notahiti = '';
      $contact = '';
      $telephone = $lineA[2];
      $fax = '';
      $cellphone = $lineA[3];
      $email = $lineA[5];
      $townid = 1;
      
      $countryid = 140;
      
      $companytypename = '';
      $issupplier = 0;
      
      $clienttermid = 1;
      if ($lineA[6] == '30 jours') { $clienttermid = 2; }
      elseif ($lineA[6] == '90 jours') { $clienttermid = 3; }
      
      $clientcategoryid = $lineA[7];
      
      $debit = (int) 0;
      $credit = (int) 0;
      
      if ($clientname != '')
      {
        $query = 'insert into client (issupplier,clientcomment,cellphone,fax,companytypename,clientname,tahitinumber,contact,telephone,email,townid,clientcode,postalcode,address,postaladdress,loyalty_start,clienttermid,countryid,clientcategoryid) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $query_prm = array($issupplier,$clientcomment,$cellphone,$fax,$companytypename,$clientname,$notahiti,$contact,$telephone,$email,$townid,$clientcode,$postalcode,$address,$postaladdress,$loyalty_start,$clienttermid,$countryid,$clientcategoryid);
        require('inc/doquery.php');
        
        $clientid = $query_insert_id;

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
  <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
}

?>