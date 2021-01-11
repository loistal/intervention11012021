<?php

switch($currentstep)
{

  # Which regulationtype
  case 0:
  ?><h2>Modifier valuers/regulation</h2>
  <form method="post" action="admin.php"><table>
  <tr><td>Price regulation:</td>
  <td><select name="regulationtypeid"><?php
  
  $query = 'select regulationtypeid,regulationtypename from regulationtype order by regulationtypename';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<option value="' . $row['regulationtypeid'] . '">' . $row['regulationtypename'] . '</option>';
  }
  ?></select></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Enter data
  case 1:
  $regulationtypeid = $_POST['regulationtypeid'];
  
  ### TODO make sure each regulationzoneid exists for this regulationtypeid
  # not optimised, no need
  $query = 'select regulationzoneid from regulationzone order by regulationzoneid';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $query = 'select regulationzoneid from regulationmatrix where regulationtypeid=? and regulationzoneid=?';
    $query_prm = array($regulationtypeid, $main_result[$i]['regulationzoneid']);
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      $query = 'insert into regulationmatrix (regulationtypeid, regulationzoneid) values (?,?)';
      $query_prm = array($regulationtypeid, $main_result[$i]['regulationzoneid']);
      require('inc/doquery.php');
    }
  }
  
  ###
  
  $query = 'select regulationtypename,regulationcode from regulationtype where regulationtypeid="' . $regulationtypeid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row2 = $query_result[0];
  $query = 'select regulationmatrix.regulationzoneid as regulationzoneid,regulationzonename,freightpriceperkilo,regulationmargin from regulationmatrix,regulationzone where regulationmatrix.regulationzoneid=regulationzone.regulationzoneid and regulationtypeid="' . $regulationtypeid . '" order by regulationzoneid';
  $query_prm = array();
  require('inc/doquery.php');
  echo '<h2>Modifier ' . $row2['regulationcode'] . ' ' . $row2['regulationtypename'] . '</h2>';
  ?><form method="post" action="admin.php"><table border=1 cellspacing=1 cellpadding=1>
  <tr><td><b>Zone</b></td><td><b>Multiplicatif (Deuxième)</b></td><td><b>Additif (Première)</b></td></tr><?php
  for ($i=0; $i < $num_results; $i++)
  {
    $row = $query_result[$i];
    echo '<tr><td>' . $row['regulationzonename'] . '</td><td>';
    echo '<input type="text" STYLE="text-align:right" name="regulationmargin' . $row['regulationzoneid'] . '" value="' . $row['regulationmargin'] . '" size=8>';
    echo '</td><td>';
    echo '<input type="text" STYLE="text-align:right" name="freightpriceperkilo' . $row['regulationzoneid'] . '" value="' . $row['freightpriceperkilo'] . '" size=8>';
    echo '</td></tr>';
  }
  ?>
  <tr><td colspan="3" align="center">
  <input type=hidden name="regulationtypeid" value=' . $regulationtypeid . '>
  <input type=hidden name="step" value="2"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>"><input type=hidden name="regulationtypeid" value="<?php echo $regulationtypeid; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  # Save data
  case 2:
  $regulationtypeid = $_POST['regulationtypeid'];

  $query = 'select regulationzoneid from regulationmatrix where regulationtypeid="' . $regulationtypeid . '" order by regulationzoneid';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $regulationzoneid = $row['regulationzoneid'];
    $regulationmargin = "regulationmargin" . $regulationzoneid;
    $freightpriceperkilo = "freightpriceperkilo" . $regulationzoneid;
    $query = 'update regulationmatrix set regulationmargin="' . $_POST[$regulationmargin] . '",freightpriceperkilo="' . $_POST[$freightpriceperkilo] . '" where regulationzoneid="' . $regulationzoneid . '" and regulationtypeid="' . $regulationtypeid . '"';
    $query_prm = array();
    require('inc/doquery.php');
  }
  echo 'Enregistré.';
  break;

}

?>