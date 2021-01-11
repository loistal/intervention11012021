<?php
$query = 'select * from usertable where deleted=0 order by username';
$query_prm = array();
require('inc/doquery.php');
echo '<h2>Liste des ' . $num_results . ' utilisateurs</h2>';
echo '<table class="report"><thead><th>Login</th><th>Nom</th><th>Initiales</th><th>Mot de passe</th>';
echo '<th>Vente</th><th>Livraison</th><th>Clients</th><th>Produits</th><th>Achat</th><th>Compta</th><th>Rapports</th>';
echo '<th>Admin</th><th>Système</th><th>Options</th><th>Super-utilisateur RH</th></thead>';
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];

  $passwordok = $row['password'];
  $passwordstrength = "Extrèmement faible";
  if ($passwordok == 1) { $passwordstrength = "Très faible"; }
  if ($passwordok == 2) { $passwordstrength = "Faible"; }
  if ($passwordok == 3) { $passwordstrength = "Moyen"; }
  if ($passwordok == 4) { $passwordstrength = "Fort"; }
  if ($passwordok == 5) { $passwordstrength = "Très fort"; }
  if ($passwordok < 3) { $passwordstrength = '<span class="alert">' . $passwordstrength . '</span>'; }
  if ($row['deleted'] == 1) { $passwordstrength = '<span class="info">Supprimé</span>'; }
  if ($row['password_hash'] == '') { $passwordstrength = '<span class="alert">A CHANGER</span>'; }
  echo d_tr() .'<td>' . $row['username'] . '</td><td>' . $row['name'] . '</td><td>' . $row['initials'] . '</td>';
  echo '<td>' . $passwordstrength . '</td>';
  if ($row['salesaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['deliveryaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['clientsaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['usebyaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['purchaseaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['accountingaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['reportsaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['adminaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['systemaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['optionsaccess']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  if ($row['ishrsuperuser']) { echo '<td align=center>&radic;</td>'; }
  else { echo '<td>&nbsp;</td>'; }
  echo '</tr>';
}
echo '</table>';

echo '<br><h2>Utilisateurs supprimés</h2>';
echo '<table class="report"><thead><th>Login</th><th>Nom</th><th>Initiales</th></thead>';
$query = 'select username,name,initials from usertable where deleted=1 order by username';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $passwordstrength = '<span class="info">Supprimé</span>';
  echo d_tr() . '<td>' . $row['username'] . '</td><td>' . $row['name'] . '</td><td>' . $row['initials'] . '</td>';
}
echo '</table>';



?>