<?php
  switch($currentstep)
  {

    # Enter data
    case 0:
    ?><h2>Modifier une adresse supplémentaire:</h2>
    <form method="post" action="clients.php">
    <table>
    <tr><td>Compte client:</td>
    <td><input type="text" name="clientid" size=20></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1"><input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
    <input type="submit" value="Valider"></td></tr></table>
    <?php
    break;

    #
    case 1:
    $clientid = (int) $_POST['clientid'];
    
    $query = 'select extraaddressid,address,postaladdress from extraaddress where clientid="' . $clientid . '" and deleted<>1';
    $query_prm = array();
        require('inc/doquery.php');
    if ($num_results == 0)
    {
      echo '<p>Aucune adresse trouvé.</p>';
    }
    else
    {
      ?>
      <h2>Modifier adresse supplémentaire pour client <?php echo $clientid; ?></h2>
      <form method="post" action="clients.php">
      <table><tr><td>Adresse supplémentaire:</td>
      <td><select name="extraaddressid"><?php
      for ($i=0; $i < $num_results; $i++)
      {
        $row = $query_result[$i];
        echo '<option value="' . $row['extraaddressid'] . '">' . $row['address'] . ' ' . $row['postaladdress'] . '</option>';
      }
      ?></select></td></tr>
      <tr><td colspan="2" align="center">
      <input type=hidden name="step" value="2"><input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
      <input type="submit" value="Valider"></td></tr>
      </table></form>
      <?php
    }
    break;

    #
    case 2:
    $extraaddressid = $_POST['extraaddressid'];
    
    $query = 'select clientsectorid,quarter,clientid,address,postaladdress,postalcode,telephone,townid,employeeid from extraaddress where extraaddressid="' . $extraaddressid . '"';
    $query_prm = array();
        require('inc/doquery.php');
    $row = $query_result[0];
    $clientsectorid = $row['clientsectorid'];
    ?><h2>Modifier l'adresse supplémentaire:</h2>
    <form method="post" action="clients.php">
    <table>
    <tr><td>Compte client:</td>
    <td><input type="text" name="clientid" value="<?php echo $row['clientid']; ?>" size=20></td></tr>
    <tr><td>Adresse géo:</td>
    <?php echo '<td><input type="text" name="quarter" value="' . d_input($row['quarter']) . '"  size=50> (comment trouver ce client)</td></tr>'; ?>
    <tr><td>Adresse ligne 1:</td>
    <td><input type="text" name="address" value="<?php echo $row['address']; ?>" size=50></td></tr>
    <tr><td>Adresse ligne 2:</td>
    <td><input type="text" name="postaladdress" value="<?php echo $row['postaladdress']; ?>" size=50></td></tr>
    <tr><td>Code postal:</td>
    <td><input type="text" name="postalcode" value="<?php echo $row['postalcode']; ?>" size=50></td></tr>
    <tr><td>Téléphone:</td>
    <td><input type="text" name="telephone" value="<?php echo $row['telephone']; ?>" size=50></td></tr>
    <tr><td>Ile/Commune:</td>
    <td><select name="townid"><?php
    
    $query = 'select townid,townname,islandname from town,island where town.islandid=island.islandid order by islandname,townname';
    $query_prm = array();
        require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['townid'] == $row2['townid']) { echo '<option value="' . $row2['townid'] . '" SELECTED>' . $row2['islandname'] . '/' . $row2['townname'] . '</option>'; }
      else { echo '<option value="' . $row2['townid'] . '">' . $row2['islandname'] . '/' . $row2['townname'] . '</option>'; }
    }
    ?></select></td></tr>
    <tr><td>Employee:</td>
    <td><select name="employeeid"><option value="0"> </option><?php
    
    $query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where iscashier=1 order by employeename';
    $query_prm = array();
        require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row['employeeid'] == $row2['employeeid']) { echo '<option value="' . $row2['employeeid'] . '" SELECTED>' . $row2['employeename'] . '</option>'; }
      else { echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; }
    }
    ?></select></td></tr>
    <?php
    $dp_itemname = 'clientsector'; $dp_description = 'Secteur'; $dp_selectedid = $clientsectorid; $dp_noblank = 1;
    require('inc/selectitem.php');
    ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="3"><input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
    <input type=hidden name="extraaddressid" value="<?php echo $extraaddressid; ?>">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

    # Save data
    case 3:
    $extraaddressid = $_POST['extraaddressid'];
    $clientid = (int) $_POST['clientid'];
    $quarter = $_POST['quarter'];
    $address = $_POST['address'];
    $postaladdress = $_POST['postaladdress'];
    $postalcode = $_POST['postalcode'];
    $telephone = $_POST['telephone'];
    $townid = $_POST['townid']+0;
    $employeeid = $_POST['employeeid']+0;
    $clientsectorid = $_POST['clientsectorid']+0;
    $query = 'update extraaddress set clientsectorid="' . $clientsectorid . '",quarter="' . $quarter . '",employeeid="' . $employeeid . '",clientid="' . $clientid . '",address="' . $address . '",postaladdress="' . $postaladdress . '",postalcode="' . $postalcode . '",telephone="' . $telephone . '",townid="' . $townid . '" where extraaddressid="' . $extraaddressid . '"';
    $query_prm = array();
        require('inc/doquery.php');
    echo '<p>Adresse modifiée.</p>';
    break;
  }
?>