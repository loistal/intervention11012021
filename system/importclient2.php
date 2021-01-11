<?php

#echo 'disabeled'; exit;

# config
$separator = ';';

echo '<h2>Client Import Tahiti Nui Incendie 2017 07 25 FIXED WIDTH  MOD FOR SUPPLIERS</h2>';

if ($_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;

  require('preload/town.php');
  require('preload/island.php');
  
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    echo '<tr>';
    $lineA[0] = substr($line,0,35);
    $lineA[1] = substr($line,35,69);
    $lineA[2] = substr($line,104,21);
    $lineA[3] = substr($line,125,21);
    $lineA[4] = substr($line,146,35);#address
    $lineA[5] = substr($line,181,9);#postal code
    $lineA[6] = substr($line,190,35);#address 2
    $lineA[7] = substr($line,285,35);#contact
    foreach ($lineA as $x => $kladd)
    {
        echo '<td>',' [',$x,'] ',$kladd;
    }
    
    if (1==1)
    {
      $clientcode = '';
      $clientname = d_encode($lineA[0]);
      
      $use_loyalty_points = 0;
      
      $postalcode = $lineA[5];
      $address = $lineA[4];
      $postaladdress = $lineA[6];
      
      $loyalty_start = 0;
      
      $notahiti = '';
      $contact = $lineA[7];
      $telephone = $lineA[3];
      $fax = '';
      $cellphone = $lineA[2];
      $email = $lineA[1];
      
      $townid = 1;
      /*
      $townname = $lineA[6];
      $query = 'select townid from town where townname=?';
      $query_prm = array($townname);
      require('inc/doquery.php');
      if ($num_results) { $townid = $query_result[0]['townid']; }
      else
      {
        echo 'cannot find town';
      }
      */
      
      $clienttermid = 1;
      $countryid = 156;
      
      $clientcategoryid = 1;
      $companytypename = '';
      
      $isclient = 0;
      $issupplier = 1;
      
      if ($clientname != '')
      {
        $query = 'insert into client (isclient,issupplier,cellphone,fax,companytypename,clientname,tahitinumber,contact,telephone,email,townid,clientcode,postalcode,address,postaladdress,loyalty_start,clienttermid,countryid,clientcategoryid) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $query_prm = array($isclient,$issupplier,$cellphone,$fax,$companytypename,$clientname,$notahiti,$contact,$telephone,$email,$townid,$clientcode,$postalcode,$address,$postaladdress,$loyalty_start,$clienttermid,$countryid,$clientcategoryid);
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
  <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
}

?>