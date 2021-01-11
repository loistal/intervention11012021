<?php
  switch($currentstep)
  {

    case 0:
    ?><h2>Stock par entrepôt:</h2>
    <form method="post" action="reportwindow.php" target=_blank><table>
    <tr><td>Ranger par:</td><td><select name="orderby">
<?php
    if ($_SESSION['ds_useproductcode'] == 1) { echo '<option value=1>Code</option>'; }
    else { echo '<option value=1>Numéro</option>'; }
?>
    <option value=2>Nom</option>
    </select></td></tr>
    <tr><td colspan="2" align="center"><input type=hidden name="report" value="warehousestock"><input type="submit" value="Valider"></td></tr>
    </table></form><?php
    break;

  }
?>