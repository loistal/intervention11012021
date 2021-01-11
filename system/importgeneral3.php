<?php

# config
$separator = ';';

echo '<h2>Import salary_account EP 2019 01 24</h2>';

if ($_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    
    $referencenumber = trim($lineA[0]);
    
    echo '<tr>';
    foreach ($lineA as $x => $value)
    {
      echo '<td>['.$x.']' . $value;
    }
    
    $query = 'select employeeid from employee where referencenumber=?';
    $query_prm = array($referencenumber);
    require('inc/doquery.php');
    if ($num_results)
    {
      $employeeid = $query_result[0]['employeeid'];
      
      $salary_account = trim($lineA[2]).' '.trim($lineA[3]).' '.trim($lineA[4]).' '.trim($lineA[5]);
      $query = 'update employee set salary_account=? where employeeid=?';
      $query_prm = array($salary_account,$employeeid);
      require('inc/doquery.php');
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