<?php

#echo 'disabeled'; exit;

$separator = ';';

echo '<h2>Stock import Animalice 2020 08 14</h2>';

if (isset($_POST['importme']) && $_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    
    if ($lineA[0] != '')
    {
      $temp_output = '<tr>';
      for ($x=0; $x < 7; $x++)
      {
        if (strlen($lineA[$x]) > 50) { $lineA[$x] = substr($lineA[$x],0,50); }
        $temp_output .= '<td>'.' ['.$x.'] '.$lineA[$x];
      }
    }
  
    if ($i > 1)
    {
      $suppliercode = $lineA[0];
      $query = 'select productid from product where suppliercode=?';
      $query_prm = array($suppliercode);
      require('inc/doquery.php');
      if ($num_results) { $productid = $query_result[0]['productid']; echo $temp_output; }
      else { $productid = 0; }

      if ($productid > 0)
      {
        $prev = $lineA[6];
        $quantity = $lineA[2];
        $query = 'insert into purchasebatch (productid,prev,placementid,userid,amount,origamount,arrivaldate)
        values (?,?,1,1,?,?,curdate())';
        $query_prm = array($productid,$prev,$quantity,$quantity);
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