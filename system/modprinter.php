<?php
  switch($currentstep)
  {

    case 0:
    echo '<form method="post" action="system.php">';
    echo '<fieldset><legend>Modifier imprimante</legend>';
    echo '<label>Imprimante:</label>';
    echo '<select autofocus name="printerid">';
    $query = 'select printerid,printername from printer order by printername';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0;$i<$num_results;$i++)
    {
      echo '<option value="' . htmlentities($query_result[$i]['printerid']) . '">' . htmlentities($query_result[$i]['printername']) . '</option>';
    }
    echo '</select><br>';
    echo '<label><input type="checkbox" name="addprinter" value="1"> Ajouter imprimante:</label> <input type="text" STYLE="text-align:right" name="printername" size=20>';
    echo '<br><input type=hidden name="step" value="1"><input type=hidden name="systemmenu" value="' . $systemmenu . '">';
    echo '<input type="submit" value="Valider">';
    echo '</fieldset></form>';
    break;
    
    case 1:
    $printerid = $_POST['printerid'];
    if ($_POST['addprinter'] == 1)
    {
      $query = 'insert into printer (printername) values (?)';
      $query_prm = array($_POST['printername']);
      require('inc/doquery.php');
      $printerid = $query_insert_id;
    }
    $query = 'select printername from printer where printerid=?';
    $query_prm = array($printerid);
    require('inc/doquery.php');
    echo '<form method="post" action="system.php">';
    echo '<fieldset><legend>Modifier imprimante</legend>';
    echo '<label>Imprimante:</label> <input autofocus type="text" STYLE="text-align:right" name="printername" value="' . $query_result[0]['printername'] . '" size=20>';
    echo '<br><input type=hidden name="step" value="2"><input type=hidden name="printerid" value="' . $printerid . '"><input type=hidden name="systemmenu" value="' . $systemmenu . '">';
    echo '<input type="submit" value="Valider">';
    echo '</fieldset></form>';
    break;
    
    case 2:
    $query = 'update printer set printername=? where printerid=?';
    $query_prm = array($_POST['printername'],$_POST['printerid']);
    require('inc/doquery.php');
    echo '<p>Imprimante ' . $_POST['printername'] . ' modifiée.</p>';
    break;
    
  }
?>