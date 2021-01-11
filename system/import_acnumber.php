<?php

# TODO make accessible to accounting users

$separator = ';';

echo '<h2>Basic ACNUMBER import 2020 02 11</h2>
<p>ACNUMBER;NAME(acgid=first char)</p>';

if (isset($_POST['importme']) && $_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    
    echo '<tr>';
    for ($x=0; $x <= 1; $x++)
    {
      echo '<td>',' [',$x,'] ',$lineA[$x];
    }
    
    $acnumber = trim($lineA[0]);
    $acname = trim($lineA[1]);
    $accountinggroupid = (int) substr($acnumber,0,1);
    if ($acnumber != '')
    {
      echo '<td>'.$accountinggroupid;
      $query = 'select accountingnumberid from accountingnumber where acnumber=?';
      $query_prm = array($acnumber);
      require('inc/doquery.php');
      if ($num_results)
      {
        $query = 'update accountingnumber set acname=?,accountinggroupid=? where acnumber=?';
        $query_prm = array($acname,$accountinggroupid,$acnumber);
        require('inc/doquery.php');
        if ($num_results) { echo '<td>updated'; }
      }
      else
      {
        $query = 'insert into accountingnumber (acnumber,acname,accountinggroupid) values (?,?,?)';
        $query_prm = array($acnumber,$acname,$accountinggroupid);
        require('inc/doquery.php');
        if ($num_results) { echo '<td>inserted'; }
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