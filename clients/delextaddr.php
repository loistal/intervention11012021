<?php
  switch($currentstep)
  {

    # Enter data
    case 0:
    ?><h2>Supprimer une adresse supplémentaire:</h2>
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
      <h2>Supprimer adresse supplémentaire pour client <?php echo $clientid; ?></h2>
      <form method="post" action="clients.php">
      <table><tr><td>Adresse supplémentaire:</td>
      <td><select name="extraaddressid"><?php
      for ($i=0; $i < $num_results; $i++)
      {
        $row = mysql_fetch_array($result);
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
    
    $query = 'update extraaddress set deleted=1 where extraaddressid="' . $extraaddressid . '"';
    $query_prm = array();
        require('inc/doquery.php');
    echo '<p>Adresse supprimée.</p>';
    break;

  }
?>