<?php
switch($currentstep)
{
  # Enter data
  case 0: ?>
    <h2>Ajouter une adresse supplémentaire:</h2>
    <form method="post" action="clients.php">

     <table>
        <tr>
          <td>Compte client :</td>
          <td><input type="text" name="clientid" size="20"></td>
        </tr>

        <tr>
          <td>Adresse géographique :</td>
          <td><input type="text" name="quarter" size="50">(Comment trouver ce client)</td>
        </tr>

        <tr>
          <td>Adresse ligne 1 :</td>
          <td><input type="text" name="address" size="50"></td>
        </tr>

        <tr>
          <td>Adresse ligne 2 :</td>
          <td><input type="text" name="postaladdress" size="50"></td>
        </tr>

        <tr>
          <td>Code postal :</td>
          <td><input type="text" name="postalcode" size="50"></td>
        </tr>

        <tr>
          <td>Téléphone :</td>
          <td><input type="text" name="telephone" size="50"></td>
        </tr>

        <tr>
          <td>Ile/Commune :</td>
          <td>
            <select name="townid">
              <?php
                $query = 'SELECT townid, townname, islandname FROM town,island WHERE town.islandid = island.islandid ORDER BY islandname, townname';
                $query_prm = array();

                require('inc/doquery.php');

                for ($i=0; $i < $num_results; $i++)
                {
                  $row = $query_result[$i];
                  echo '<option value="' . $row['townid'] . '">' . $row['islandname'] . '/' . $row['townname'] . '</option>';
                }
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td>Employé:</td>
          <td>
            <select name="employeeid">
                <option value="0"> </option>
                <?php
                  $query = 'SELECT employeeid, concat(employeename," ",employeefirstname) as employeename FROM employee WHERE iscashier = 1 ORDER BY employeename';
                  $query_prm = array();

                  require('inc/doquery.php');
                  
                  for ($i=0; $i < $num_results; $i++)
                  {
                    $row2 = $query_result[$i];
                    if ($row['employeeid'] == $row2['employeeid']) 
                    { 
                      echo '<option value="' . $row2['employeeid'] . '" selected>' . $row2['employeename'] . '</option>'; 
                    }
                    else { 
                      echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>'; 
                    }
                  }
                ?>
            </select>
          </td>
        </tr>

        <?php
          $dp_itemname = 'clientsector'; 
          $dp_description = 'Secteur'; 
          $dp_noblank = 1;

          require('inc/selectitem.php');
        ?>

        <tr>
          <td colspan="2" align="center">
            <input type=hidden name="step" value="1">
            <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
            <input type="submit" value="Valider">
          </td>
        </tr>
      </table>
    </form>
<?php 
  break; 
?>

<?php  
  # Save data
  case 1:
    $clientid = $_POST['clientid'];

    if(!is_numeric($clientid))
    {
      echo '<p class="alert">Veuillez saisir un numéro de compte client.</p>';
      exit;
    }

    $quarter = $_POST['quarter'];
    $address = $_POST['address'];
    $postaladdress = $_POST['postaladdress'];
    $postalcode = $_POST['postalcode'];
    $telephone = $_POST['telephone'];
    $townid = (int) $_POST['townid'];
    $employeeid = (int) $_POST['employeeid'];
    $clientsectorid = (int) $_POST['clientsectorid'];

    $query = 'INSERT INTO extraaddress (clientsectorid, quarter, clientid, address, postaladdress, postalcode, telephone, townid, employeeid, deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'; 
    $query_prm = array($clientsectorid, $quarter, $clientid, $address, $postaladdress, $postalcode, $telephone, $townid, $employeeid, 0);

    require('inc/doquery.php');
    echo '<p>Adresse ajoutée.</p>';
  break;
}
#end switch
?>