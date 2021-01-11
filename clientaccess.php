<?php

# TODO refacter, verify

# Security check
if ($_SESSION['ds_clientaccess'] != 1)
{
  require('logout.php');
  exit;
}

# Build web page
require('inc/standard.php');
require('inc/top.php');
echo '<title>'.d_output($_SESSION['ds_customname']).' Accès Client</title>';
if ($_SESSION['ds_menustyle'] != 5) { require ('inc/logo.php'); }
$dauphin_currentmenu = '';
require('inc/menu.php');

# table
echo '</div><div id="wrapper"><div id="leftmenu"><div id="selectactionbar">';
echo '<h5>Accès client</h5>';
?>
<br>
<div class="selectaction">
<?php
if ($_SESSION['ds_issupplier'])
{
  echo '<b>Fournisseur</b><br>';
  #echo 'rapport1<br>';
  #echo 'rapport2<br>';
  echo '<br>';

  ###
  $customfilename = 'custom/' . d_safebasename(strtolower($_SESSION['ds_customname'])) . 'casuppliertable.php';
  if (file_exists($customfilename))
  {
    require($customfilename);
  }
  ###
}
else
{
  #echo '<b><a href="clientaccess.php?clientaccessmenu=showinv">Mes factures</a></b><br>
  #<br>';
}

/* support not used
<b><a href="clientaccess.php?clientaccessmenu=support">Support</a></b><br>
<br>
*/

?>

</div><br>
<?php
require('inc/copyright.php');
echo '</div></div><div id="mainprogram">';

$clientaccessmenu = '';
if (isset($_GET['clientaccessmenu']))
{
  $clientaccessmenu = $_GET['clientaccessmenu'];
}
if (isset($_POST['clientaccessmenu']))
{
  $clientaccessmenu = $_POST['clientaccessmenu'];
}
switch ($clientaccessmenu)
{

  case 'support':
    $step = 0;
    if (isset($_POST['step']))
    {
      $step = (int) $_POST['step'];
    }
    switch ($step) # TODO fix this, check all code for 'support'
    {
      case 1:
        $linecolor[0] = 'black';
        $linecolor[1] = 'blue';
        $ourcolor = 0;

        $companyname = $_SESSION['ds_customname'];
        if ($_SESSION['ds_customname'] == "" || !isset($_SESSION['ds_customname']))
        {
          $companyname = 'Entreprise';
        }
        $supportid = $_POST['supportid'];
        if ($supportid == -1)
        {
          echo '<h2>Nouvelle entré support client</h2>';
        }
        else
        {
          echo '<h2>Support client</h2>';
        }
        if ($_POST['save'] == 1)
        {
          if ($supportid == -1)
          {
            $query = 'insert into support (clientid,clientdate,clienttext,companytext,closed,supporttitle,lastcompany) values (?,curdate(),?,"",0,?,0)';
            $query_prm = array(
              $_SESSION['ds_userid'],
              $_POST['clienttext'],
              $_POST['supporttitle']
            );
            require('inc/doquery.php');
            $supportid = $query_insert_id;
          }
          else
          {
            $query = 'update support set clientdate=curdate(),clienttext=CONCAT(clienttext,?),lastcompany=0 where supportid=?';
            $query_prm = array('§' . $_POST['clienttext'], $supportid);
            require('inc/doquery.php');
          }
        }
        # read entries+closed
        $query = 'select clienttext,companytext,closed,supporttitle,clientdate,companydate from support where supportid=?';
        $query_prm = array($supportid);
        require('inc/doquery.php');
        $row = $query_result[0];
        $clienttextA = explode("§", $row['clienttext']);
        $companytextA = explode("§", $row['companytext']);
        if (isset($row['clientdate']))
        {
          $clientdate = '(' . datefix2($row['clientdate']) . ')';
        }
        if (isset($row['companydate']))
        {
          $companydate = '(' . datefix2($row['companydate']) . ')';
        }
        echo '<form method="post" action="clientaccess.php"><table border=1 cellspacing=2 cellpadding=2><tr><td align=center colspan=2> &nbsp; Titre: ';
        if ($supportid == -1)
        {
          echo '<input type="text" name="supporttitle" id="myfocus" size=80>';
        }
        else
        {
          echo '<b>' . $row['supporttitle'] . '</b>';
        }
        echo ' &nbsp; </td></tr>';
        echo '<tr><td width=50%><i>Client ' . $clientdate . '</td><td width=50%><i>' . $companyname . ' ' . $companydate . '</td></tr>';
        echo '<tr><td valign=top>';
        foreach ($clienttextA as $clienttextline)
        {
          echo '<font color="' . $linecolor[$ourcolor] . '">' . $clienttextline . '</font><br>';
          if ($ourcolor == 0)
          {
            $ourcolor = 1;
          }
          else
          {
            $ourcolor = 0;
          }
        }
        if ($row['clienttext'] != "")
        {
          echo '<br><br>';
        }
        if ($row['closed'] == 0)
        {
          echo '<textarea name="clienttext" cols=40 rows=10></textarea></td>';
        }
        echo '<td valign=top>';
        foreach ($companytextA as $companytextline)
        {
          echo '<font color="' . $linecolor[$ourcolor] . '">' . $companytextline . '</font><br>';
          if ($ourcolor == 0)
          {
            $ourcolor = 1;
          }
          else
          {
            $ourcolor = 0;
          }
        }
        echo '</td></tr>';
        if ($row['closed'] == 1)
        {
          echo '<tr><td colspan="2" align="center">Fermé</td></tr>';
        }
        else
        {
          echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="save" value="1"><input type=hidden name="supportid" value="' . $supportid . '"><input type="submit" value="Valider"></td></tr>';
        }
        echo '<input type=hidden name="clientaccessmenu" value="' . $clientaccessmenu . '"></table>';
        break;

      default:
        echo '<h2>Support client</h2>';
        $companyname = $_SESSION['ds_customname'];
        if ($_SESSION['ds_customname'] == "" || !isset($_SESSION['ds_customname']))
        {
          $companyname = ' entreprise';
        }
        $query = 'select supportid,supporttitle,closed,clientdate,companydate,lastcompany from support';
        $query = $query . ' where support.clientid="' . $_SESSION['ds_userid'] . '"';
        $query = $query . ' order by supportid desc';
        $query_prm = array();
        require('inc/doquery.php');
        echo '<form method="post" action="clientaccess.php"><table border=1 cellspacing=2 cellpadding=2><tr><td><b>&nbsp;</td><td><b>Titre</td><td><b>Date client</td><td><b>Date ' . $companyname . '</td><td><b>Status</td><td><b>Dèrnier modif</td></tr>';
        echo '<tr><td> &nbsp; <input type="radio" name="supportid" value="-1" checked></td><td colspan=5>Nouveau cas</td></tr>';
        for ($i = 0; $i < $num_results; $i++)
        {
          $row = $query_result[$i];
          $closed = 'Ouvert';
          if ($row['closed'] == 1)
          {
            $closed = 'Fermé';
          }
          $clientdate = datefix2($row['clientdate']);
          if (!isset($row['clientdate']))
          {
            $clientdate = '&nbsp;';
          }
          $companydate = datefix2($row['companydate']);
          if (!isset($row['companydate']))
          {
            $companydate = '&nbsp;';
          }
          if ($row['lastcompany'] == 1)
          {
            $modif = $companyname;
          }
          else
          {
            $modif = 'Client';
          }
          echo '<tr><td> &nbsp; <input type="radio" name="supportid" value="' . $row['supportid'] . '"></td><td align=right>' . $row['supporttitle'] . '</td><td>' . $clientdate . '</td><td>' . $companydate . '</td><td align=right>' . $closed . '</td><td align=right>' . $modif . '</td></tr>';
        }
        echo '<tr><td colspan="7" align="center"><input type=hidden name="step" value="1"><input type=hidden name="clientaccessmenu" value="' . $clientaccessmenu . '"><input type="submit" value="Valider"></td></tr>';
        echo '</table></form>';
        break;
    }
    break;

  case 'showinv':
    ?>
    <h2>Mes factures:</h2>
    <form method="post" action="reportwindow.php" target="_blank">
    <table><?php
      echo '<tr><td>De:</td><td>';
      $datename = 'startdate';
      require('inc/datepicker.php');
      echo '</td></tr>';
      echo '<tr><td>A:</td><td>';
      $datename = 'stopdate';
      require('inc/datepicker.php');
      echo '</td></tr>';
      ?>
      <tr>
        <td colspan="2" align="center"><input type=hidden name="step" value="1">
          <input type=hidden name="report" value="invoicereport">
          <input type="submit" value="Valider"></td>
      </tr>
    </table></form><?php
    break;
}

if ($_SESSION['ds_issupplier'])
{
  $customfilename = 'custom/' . d_safebasename(strtolower($_SESSION['ds_customname'])) . 'casupplier.php';
  if (file_exists($customfilename))
  {
    require($customfilename);
  }
}
?>
</td></tr></table>

<?php
require('inc/bottom.php');
?>