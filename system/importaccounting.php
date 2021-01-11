<?php

require('preload/accountingnumber.php');

# config
$separator = ';';

echo '<h2>Import plan comptable</h2>';  # currently accountingnumber - use file 'Plan_Comptable.csv'

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
    /*
    foreach ($lineA as $value)
    {
      echo '<td>' . $value;
    }
    */
    
    $error = 0;
    /*
    $acnumber = preg_replace('/\s+/', '', $lineA[0]);
    if (strlen($acnumber) == 0) { $error = 1; }
    if (strlen($acnumber) == 3) { $acnumber .= '000'; }
    if (strlen($acnumber) == 4) { $acnumber .= '00'; }
    if (strlen($acnumber) == 5) { $acnumber .= '0'; }
    */
    $acnumber = $lineA[0];
    echo '<td>' . $acnumber;
    
    $acname = $lineA[1];
    $agname = $lineA[2];
    echo '<td>' . $acname;
    echo '<td>' . $agname;
    
    $query = 'select accountinggroupid from accountinggroup where agname=?';
    $query_prm = array($agname);
    require('inc/doquery.php');
    if ($num_results) { $agid = $query_result[0]['accountinggroupid']; }
    else
    {
      $query = 'insert into accountinggroup (agname) values (?)';
      $query_prm = array($agname);
      require('inc/doquery.php');
      $agid = $query_insert_id;
    }
    echo '<td>'.$agid;

    $error=0;
    if ($error == 0)
    {
      $query = 'insert into accountingnumber (acnumber,acname,accountinggroupid) values (?,?,?)';
      $query_prm = array($acnumber,$acname,$agid);
      require('inc/doquery.php');
    }
    else { echo '<td> NOT Inserting this line'; }
    
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