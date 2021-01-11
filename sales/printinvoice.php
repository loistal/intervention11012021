<?php
  switch($currentstep)
  {

    # mod invoice
    case 0:
    ?>
    <form method="post" action="sales.php">
    <fieldset><legend>Imprimer facture/avoir</legend>
    <ul><li>
    <li><label>Numéro:</label> <input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=8><input name="modify" type="submit" value="Modifier">
    </ul></fieldset>
    <input type=hidden name="step" value="1"><input type=hidden name="salesmenu" value="printinvoice">
    </fieldset></form>
    <?php
    break;
    
    case 1:
    $invoiceid = $_POST['invoiceid'];
    require ('phpscripts/print_invoice.php');
    break;

  }
?>