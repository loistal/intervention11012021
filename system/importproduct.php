<?php

#echo 'disabeled'; exit;

# config
$separator = ',';

echo '<h2>Product import TT 2020 12 22</h2>';

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
    for ($x=0; $x < 9; $x++)
    {
      if (isset($lineA[$x]))
      {
        echo '<td>',' [',$x,'] ',$lineA[$x];
      }
    }
    
    if ($i > 1)
    {
      $eancode = '';
      $productname = d_encode($lineA[4]);
      $productcomment = '';
      $productdetails = '';
      $netweight = $lineA[7];
      $quantity = $lineA[5];
      $prev = 0;
      $salesprice = $lineA[6];
      $dp_userid = $lineA[8];
      
      $productfamilyname = $lineA[3];
      $query = 'select productfamilyid from productfamily where productfamilyname=?';
      $query_prm = array($productfamilyname);
      require('inc/doquery.php');
      if ($num_results) { $productfamilyid = $query_result[0]['productfamilyid']; }
      else { $productfamilyid = 1; }

      $suppliercode = '';
      $taxcodeid = 4; # 16%
      $unittypeid = 1;
      $supplierid = 0;
      $npu = 1;
      $supplierunittypeid = 1;
      $detailsalesprice = 0;
      $margin = 0;
      $netweightlabel = '';
      $suppliercode2 = '';

      if (1==1)
      {
        # product insert
        $query = 'insert into product (productdetails,productcomment,netweight,supplierid,netweightlabel,eancode,productname,suppliercode,suppliercode2,productfamilyid,margin,salesprice,supplierunittypeid,unittypeid,taxcodeid,numberperunit,countryid,discontinued,creationdate,currentstock,generic,regulationtypeid,detailsalesprice) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,156,0,curdate(),0,0,1,?)';
        $query_prm = array($productdetails,$productcomment,$netweight,$supplierid,$netweightlabel,$eancode,$productname,$suppliercode,$suppliercode2,$productfamilyid,$margin,$salesprice,$supplierunittypeid,$unittypeid,$taxcodeid,$npu,$detailsalesprice);
        require('inc/doquery.php');
        $productid = $query_insert_id;
        
        # purchasebatch insert
        $query = 'insert into purchasebatch (productid,prev,placementid,userid,amount,origamount,arrivaldate) values (?,?,1,1,?,?,curdate())';
        $query_prm = array($productid,$prev,$quantity,$quantity);
        require('inc/doquery.php');
        
        $query = 'insert into endofyearstock_user (userid,productid,year) values (?,?,?)';
        $query_prm = array($dp_userid, $productid, 2020);
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