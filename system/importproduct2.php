<?php

#echo 'disabeled'; exit;

# config
$separator = ';';

echo '<h2>Product import Tahiti Crew 2020 02 12</h2>';

if ($_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    
    echo '<tr>';
    for ($x=0; $x < 4; $x++)
    {
      echo '<td>',' [',$x,'] ',$lineA[$x];
    }
    
    if ($i > 0)
    {

      $productfamilyid = 3;

      $productname = d_encode($lineA[2]).' ';
      $clientname = d_encode($lineA[0]);
      $words = explode(" ", $clientname);
      foreach ($words as $w) {
        $productname .= $w[0];
      }
      echo '<td>',$productname;
      
      $email = $lineA[1];
      $telephone = $lineA[3];
      $on_behalf = 1;
      
      $supplierid = 0; # TODO insert
      $query = 'insert into client (clientname,issupplier,telephone,email) values (?,1,?,?)';
      $query_prm = array($clientname,$telephone,$email);
      require('inc/doquery.php');
      $supplierid = $query_insert_id;
      
      $weight = 0;
      $quantity = 0;
      
      $prev = 0;
      
      $suppliercode = '';
      
      $taxcodeid = 1;
      
      $salesprice = 0;
      
      $generic = 1;
      #if ($salesprice == 0) { $generic = 1; }

      $unittypeid = 1;
      
      $eancode = '';
      
      $npu = 1;
      
      $supplierunittypeid = 1;
      
      $detailsalesprice = 0;

      $margin = 0;
      
      $netweight = 0;    
      
      $netweightlabel = '';
      
      $suppliercode2 = '';
      
      if ($productname != '')
      {

        $query = 'insert into product (producttypeid,on_behalf,weight,netweight,supplierid,netweightlabel,eancode,productname,suppliercode,suppliercode2,productfamilyid,margin,salesprice,supplierunittypeid,unittypeid,taxcodeid,numberperunit,countryid,discontinued,creationdate,currentstock,generic,regulationtypeid,detailsalesprice) values (1,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,156,0,curdate(),0,?,1,?)';
        $query_prm = array($on_behalf,$weight,$netweight,$supplierid,$netweightlabel,$eancode,$productname,$suppliercode,$suppliercode2,$productfamilyid,$margin,$salesprice,$supplierunittypeid,$unittypeid,$taxcodeid,$npu,$generic,$detailsalesprice);
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