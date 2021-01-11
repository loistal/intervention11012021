<?php

# NOT USED
  switch($currentstep)
  {

    case 0:
    ?><h2>Support client:</h2>
    <form method="post" action="clients.php"><table>
    <tr><td>Client:</td><td><input autofocus type="text" STYLE="text-align:right" name="client" size=20></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
    <input type="submit" value="Valider"></td></tr></table></form><?php
    break;

    case 1:
    
    $client = $_POST['client'];
    require('inc/findclient.php');
    
      if ($clientid < 1)
  {
    echo '<form method="post" action="clients.php"><table><tr><td>';
    require ('inc/selectclient.php');
    echo '</td></tr><tr><td colspan="2" align="center"><input type=hidden name="step" value="1">';
    echo '<input type=hidden name="step" value="1"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type="submit" value="Valider"></td></tr></table></form>';
  }

  else
  {
    
    echo '<h2>Support client ' . $clientid . ': ' . $clientname . '</h2>';
    $companyname = $_SESSION['ds_customname'];
    if ($_SESSION['ds_customname'] == "" || !isset($_SESSION['ds_customname'])) { $companyname = ' entreprise'; }
    $query = 'select supportid,supporttitle,closed,clientdate,companydate,lastcompany from support';
    $query = $query . ' where support.clientid="' . $clientid . '"';
    $query = $query . ' order by supportid desc';
    $query_prm = array();
    require('inc/doquery.php');
    echo '<form method="post" action="clients.php"><table border=1 cellspacing=2 cellpadding=2><tr><td><b>&nbsp;</td><td><b>Titre</td><td><b>Date client</td><td><b>Date ' . $companyname . '</td><td><b>Status</td><td><b>Dèrnier modif</td></tr>';
    echo '<tr><td> &nbsp; <input type="radio" name="supportid" value="-1" checked></td><td colspan=5>Nouveau cas</td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      $closed = 'Ouvert'; if ($row['closed'] == 1) { $closed = 'Fermé'; }
      $clientdate = datefix2($row['clientdate']);
      if (!isset($row['clientdate'])) { $clientdate = '&nbsp;'; }
      $companydate = datefix2($row['companydate']);
      if (!isset($row['companydate'])) { $companydate = '&nbsp;'; }
      if ($row['lastcompany'] == 1) { $modif = $companyname; }
      else { $modif = 'Client'; }
      echo '<tr><td> &nbsp; <input type="radio" name="supportid" value="' . $row['supportid'] . '"></td><td align=right>' . $row['supporttitle'] . '</td><td>' . $clientdate . '</td><td>' . $companydate . '</td><td align=right>' . $closed . '</td><td align=right>' . $modif . '</td></tr>';
    }
    echo '<tr><td colspan="7" align="center"><input type=hidden name="step" value="2"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type=hidden name="clientid" value="' . $clientid . '"><input type="submit" value="Valider"></td></tr>';
    echo '</table></form>';
  }
    break;

    case 2:
$linecolor[0] = 'black';
$linecolor[1] = 'blue';
$ourcolor = 0;
    
    $companyname = $_SESSION['ds_customname'];
    if ($_SESSION['ds_customname'] == "" || !isset($_SESSION['ds_customname'])) { $companyname = 'Entreprise'; }
    $supportid = $_POST['supportid'];
$query = 'select clientname from client where clientid="' . $_POST['clientid'] . '"';
$query_prm = array();
  require('inc/doquery.php');
$row = $query_result[0];
$clientname = $_POST['clientid'] . ': ' . $row['clientname'];
    if ($supportid == -1) { echo '<h2>Nouvelle entré support client</h2>'; }
    else { echo '<h2>Support client ' . $clientname . '</h2>'; }
    if ($_POST['save'] == 1)
    {
      if ($supportid == -1)
      {
        $query = 'insert into support (clientid,companydate,companytext,clienttext,closed,supporttitle,lastcompany) values (' . $_POST['clientid'] . ',curdate(),"' . $_POST['companytext'] . '","",0,"' . $_POST['supporttitle'] . '",1)';
        $query_prm = array();
        require('inc/doquery.php');
        $supportid = $query_insert_id;
      }
      else
      {
        $query = 'update support set companydate=curdate(),companytext=CONCAT(companytext,"' . '§' . $_POST['companytext'] . '"),lastcompany=1 where supportid="' . $supportid . '"';
        $query_prm = array();
        require('inc/doquery.php');
      }
      if ($_POST['close'] == 1)
      {
        $query = 'update support set closed=1 where supportid="' . $supportid . '"';
        $query_prm = array();
        require('inc/doquery.php');
      }
    }
    # read entries+closed
    $query = 'select clienttext,companytext,closed,supporttitle,clientdate,companydate from support where supportid="' . $supportid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $clienttextA = explode("§", $row['clienttext']);
    $companytextA = explode("§", $row['companytext']);
    if (isset($row['clientdate'])) { $clientdate = '(' . datefix2($row['clientdate']) . ')'; }
    if (isset($row['companydate'])) { $companydate = '(' . datefix2($row['companydate']) . ')'; }
    echo '<form method="post" action="clients.php"><table border=1 cellspacing=2 cellpadding=2><tr><td align=center colspan=2> &nbsp; Titre: ';
    if ($supportid == -1) { echo '<input type="text" name="supporttitle" id="myfocus" size=80>'; }
    else { echo '<b>' . $row['supporttitle'] . '</b>'; }
    echo ' &nbsp; </td></tr>';
    echo '<tr><td width=50%><i>Client ' . $clientdate . '</td><td width=50%><i>' . $companyname . ' ' . $companydate . '</td></tr>';
    echo '<tr><td valign=top>';
    foreach ($clienttextA as $clienttextline)
    {
      echo '<font color="' . $linecolor[$ourcolor] . '">' . $clienttextline . '</font><br>';
      if ($ourcolor == 0) { $ourcolor = 1; }
      else { $ourcolor = 0; }
    }
    if ($row['clienttext'] != "") { echo '<br><br>'; }
    echo '</td>';
    echo '<td valign=top>';
    foreach ($companytextA as $companytextline)
    {
      echo '<font color="' . $linecolor[$ourcolor] . '">' . $companytextline . '</font><br>';
      if ($ourcolor == 0) { $ourcolor = 1; }
      else { $ourcolor = 0; }
    }
    if ($row['closed'] == 0) { echo '<textarea name="companytext" cols=40 rows=10></textarea></td></tr>'; }
    if ($row['closed'] == 1) { echo '<tr><td colspan="2" align="center">Fermé</td></tr>'; }
    else { echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="2"><input type=hidden name="clientsmenu" value="' . $clientsmenu . '"><input type=hidden name="save" value="1"><input type=hidden name="supportid" value="' . $supportid . '"><input type=hidden name="clientid" value="' . $_POST['clientid'] . '"><input type="checkbox" name="close" value="1"> Fermer &nbsp; <input type="submit" value="Valider"></td></tr>'; }
    echo '</table>';
    break;

  }
?>