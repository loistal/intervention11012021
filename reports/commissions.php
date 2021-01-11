  <h2>Commissions:</h2>
  <p class="alert">Attention, les produits hors définition Commission ne figurent pas sur ce rapport.</p>
  <form method="post" action="reportwindow.php" target="_blank"><table>
  <tr><td colspan=2>De
  <?php
  $datename = 'startdate';
  require('inc/datepicker.php');
  echo ' à ';
  $datename = 'stopdate';
  require('inc/datepicker.php');
  ?></td></tr>
  <tr><td>Employé(e):
  <?php
  $dp_itemname = 'employee'; $dp_issales=1; $dp_allowall = 1; $dp_selectedid = -1;
  require('inc/selectitem.php');
  ?></td></tr>
  <tr><td>Catégorie employé(e):
  <?php
  $dp_itemname = 'employeecategory'; $dp_allowall = 1; $dp_selectedid = -1;
  require('inc/selectitem.php');
  ?></td></tr>
  <tr><td>Fournisseur:</td><td><input type=number min=0 name=supplierid>&nbsp;<input type=checkbox name=excludesupplier value=1> Exclure</td></tr>
  <tr><td colspan="2" align="center"><input type=hidden name="report" value="commissions"><input type="submit" value="Valider"></td></tr>
  </table></form>