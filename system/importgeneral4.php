<?php

#echo 'disabeled'; exit;

# config
$separator = ';';

echo '<h2>Employee update JP 2020 02 18</h2>';

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
    for ($x=0; $x < 3; $x++)
    {
      echo '<td>',' [',$x,'] ',$lineA[$x];
    }
    
    if (1==1)
    {
      $new = trim($lineA[2]);
      $old = trim($lineA[0]);
      if ($old != '')
      {
        echo '<td>'.$old.' => '.$new;
        $query = 'update employee set referencenumber=? where referencenumber=?';
        $query_prm = array($new,$old);
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