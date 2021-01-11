<?php

#echo 'disabeled'; exit;

# config
$separator = ';';

echo '<h2>Product update Pro Peinture Tahiti 2020 02 06 - 10 feb fix</h2>';

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
    for ($x=0; $x < 6; $x++)
    {
      echo '<td>',' [',$x,'] ',$lineA[$x];
    }
    
    if (1==1)
    {
      $productid = $lineA[0]+0;
      $productname = $lineA[1];
      $productfamilyid = $lineA[2]+0;
      $suppplierid = $lineA[3]+0;
      $taxcodeid = $lineA[4]+0;
      $unittypeid = $lineA[5]+0;
      
      if ($productid>0)
      {
        /*
        $query = 'update product set productname=?,productfamilyid=?,supplierid=?,taxcodeid=?,unittypeid=? where productid=?';
        $query_prm = array(d_encode($productname),$productfamilyid,$suppplierid,$taxcodeid,$unittypeid,$productid);
        require('inc/doquery.php');
        */
        $query = 'update product set productname=? where productid=?';
        $query_prm = array(d_encode($productname),$productid);
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