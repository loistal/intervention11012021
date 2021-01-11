<?php

function validate_EAN13Barcode($barcode)
{
  // check to see if barcode is 13 digits long
  if(!preg_match("/^[0-9]{13}$/",$barcode)) { return 0; }

  $digits = $barcode;

  // 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
  $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
  // 2. Multiply this result by 3.
  $even_sum_three = $even_sum * 3;
  // 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
  $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
  // 4. Sum the results of steps 2 and 3.
  $total_sum = $even_sum_three + $odd_sum;
  // 5. The check character is the smallest number which, when added to the result in step 4, 
  //    produces a multiple of 10.
  $next_ten = (ceil($total_sum/10))*10;
  $check_digit = $next_ten - $total_sum;

  // if the check digit and the last digit of the barcode are OK return true;
  if($check_digit == $digits[12]) { return 1; }

  return 0;
}

require('preload/warehouse.php');
require('preload/palette.php');
require('preload/invoice_priceoption2_filter.php');

$was_added = 0;
$was_modified = 0;
$showmainform = 1;
$err_suppliercode = 0;
$err_suppliercode_dup = 0;
$err_suppliercode_empty = 0;
$err_eancode = 0;

$PA['saveme'] = 'uint';
$PA['productid'] = 'uint';
$PA['paletteid'] = 'uint';
$PA['quantity_convert'] = 'udecimal';
$PA['invoice_priceoption2_filterid'] = 'uint';
$PA['cartonweight'] = 'udecimal';
$PA['calccartonweight'] = 'uint';
$PA['suppliercode'] = '';
$PA['fenix42'] = 'uint';
$PA['suppliercode2'] = '';
$PA['productname'] = '';
$PA['margin'] = 'udecimal';
$PA['margintype'] = 'uint';
$PA['brand'] = '';
$PA['supplierid'] = 'uint';
$PA['taxcodeid'] = 'uint';
$PA['unittypeid'] = 'uint';
$PA['transportpricepercarton'] = 'udecimal';
$PA['stickerpricepercarton'] = 'udecimal';
$PA['palletpricepercarton'] = 'udecimal';
$PA['numberperunit'] = 'uint';
$PA['sih'] = '';
$PA['avantage'] = '';
$PA['tcp_gradient'] = 'uint';
$PA['weight'] = 'udecimal';
$PA['netweight'] = 'udecimal';
$PA['netweightlabel'] = '';
$PA['volume'] = 'udecimal';
$PA['countryid'] = 'uint';
$PA['productfamilyid'] = 'uint';
$PA['productcomment'] = '';
$PA['regulationcomment'] = '';
$PA['batchalert'] = 'uint';
$PA['orderalert'] = 'uint';
$PA['eancode'] = '';
$PA['eancode2'] = '';
$PA['supplierpackaging'] = '';
$PA['productdetails'] = '';
$PA['generic'] = 'uint';
$PA['only_quantity_rebate'] = 'uint';
$PA['no_client_discount'] = 'uint';
$PA['notforsale'] = 'uint';
$PA['discontinued'] = 'uint';
$PA['producttypeid'] = 'uint';
$PA['temperatureid'] = 'uint';
$PA['regulationtypeid'] = 'uint';
$PA['warehouseid'] = 'uint';
$PA['promotext'] = '';
$PA['commissionrateid'] = 'uint';
$PA['defaultitemcomment'] = '';
$PA['exludefromvatreport'] = 'uint';
$PA['avgmonthlyspec'] = 'udecimal';
$PA['avgmonthly'] = 'udecimal';
$PA['ti'] = 'uint';
$PA['hi'] = 'uint';
$PA['accountingnumberid'] = 'uint';
$PA['countstock'] = 'int';
$PA['excludefromdelivery'] = 'uint';
$PA['code_suffixe'] = '';
$PA['on_behalf'] = 'uint';
$PA['hide_price_on_invoice'] = 'uint';
$PA['modify'] = 'int';
$PA['saveme'] = 'uint';
$PA['supplierproductfamilyid'] = 'uint';
require('inc/readpost.php');

if ($numberperunit < 1) { $numberperunit = 1; }

if($avgmonthlyspec !== '') { $avgmonthlyspec *= (double) $numberperunit; }
else { $avgmonthlyspec = 0; }

if ($saveme === 0) { $countstock = 1; }

if ($calccartonweight && $productid > 0)
{
  $query = 'select avg(quantity) as avg from invoiceitemhistory where productid=?';
  $query_prm = array($productid);
  require('inc/doquery.php');

  $avg = $query_result[0]['avg'] / 1000;
  $query = 'update product set cartonweight=? where productid=? limit 1';
  $query_prm = array($avg,$productid);
  require('inc/doquery.php');
}

if ($modify === 1)
{
  $PA['product'] = 'product';
  require('inc/readpost.php');
  require('inc/findproduct.php');

  if($num_products != 1)
  {
    require('modproduct.php');
    $showmainform = 0;
  }
}

if ($productid > 0 && $saveme === 0)
{
  $query = 'select * from product where productid=? limit 1';
  $query_prm = array($productid);
  require('inc/doquery.php');
  $result =  $query_result;

  $i = 0;
  $productid = $result[$i]['productid'];
  $paletteid = $result[$i]['paletteid'];
  $suppliercode = $result[$i]['suppliercode'];
  $suppliercode2 = $result[$i]['suppliercode2'];
  $productname = d_decode($result[$i]['productname']);
  $quantity_convert = $result[$i]['quantity_convert'];
  $margin = $result[$i]['margin']+0;
  $margintype = $result[$i]['margintype']+0;
  $brand = $result[$i]['brand'];
  $supplierid = $result[$i]['supplierid'];
  $taxcodeid = $result[$i]['taxcodeid'];
  $unittypeid = $result[$i]['unittypeid'];
  $transportpricepercarton = $result[$i]['transportpricepercarton'];
  $stickerpricepercarton = $result[$i]['stickerpricepercarton'];
  $palletpricepercarton = $result[$i]['palletpricepercarton'];
  $numberperunit = $result[$i]['numberperunit'];
  $sih = $result[$i]['sih'];
  $tcp_gradient = $result[$i]['tcp_gradient'];
  $fenix42 = $result[$i]['fenix42'];
  $avantage = $result[$i]['avantage'];
  $weight = $result[$i]['weight'];
  $netweight = $result[$i]['netweight'];
  $netweightlabel = $result[$i]['netweightlabel'];
  $volume = $result[$i]['volume'];
  $cartonweight = $result[$i]['cartonweight'];
  $countryid = $result[$i]['countryid'];
  $productfamilyid = $result[$i]['productfamilyid'];
  $productcomment = $result[$i]['productcomment'];
  $regulationcomment = $result[$i]['regulationcomment'];
  $batchalert = $result[$i]['batchalert'];
  $orderalert = $result[$i]['orderalert'];
  $eancode = $result[$i]['eancode'];
  $eancode2 = $result[$i]['eancode2'];
  $supplierpackaging = $result[$i]['supplierpackaging'];
  $productdetails = $result[$i]['productdetails'];
  $generic = $result[$i]['generic'];
  $only_quantity_rebate = $result[$i]['only_quantity_rebate'];
  $no_client_discount = $result[$i]['no_client_discount'];
  $notforsale = $result[$i]['notforsale'];
  $discontinued = $result[$i]['discontinued'];
  $producttypeid = $result[$i]['producttypeid'];
  $temperatureid = $result[$i]['temperatureid'];
  $regulationtypeid = $result[$i]['regulationtypeid'];
  $warehouseid = $result[$i]['warehouseid'];
  $promotext = $result[$i]['promotext'];
  $commissionrateid = $result[$i]['commissionrateid'];
  $defaultitemcomment = $result[$i]['defaultitemcomment'];
  $exludefromvatreport = (int) $result[$i]['exludefromvatreport'];
  $avgmonthly = $result[$i]['avgmonthly'];
  $avgmonthlyspec = $result[$i]['avgmonthlyspec'];
  $ti = $result[$i]['ti'];
  $hi = $result[$i]['hi'];
  $accountingnumberid = $result[$i]['accountingnumberid'];
  $countstock = $result[0]['countstock'];
  $excludefromdelivery = $result[0]['excludefromdelivery'];
  $code_suffixe = $result[0]['code_suffixe'];
  $on_behalf = $result[0]['on_behalf'];
  $hide_price_on_invoice = $result[0]['hide_price_on_invoice'];
  $invoice_priceoption2_filterid = $result[0]['invoice_priceoption2_filterid'];
}

if($showmainform)
{
  if($saveme === 1 && $productid === 0)
  {
    $query = 'insert into product (creationdate) values (CURDATE())';
    $query_prm = array();
    require('inc/doquery.php');
    $productid = $query_insert_id;
    $was_added = 1;
  }

  # We are updating a product
  if($saveme === 1 && $productid > 0)
  {
    # If we are using product codes, check for errors
    if(($_SESSION['ds_useproductcode'] == 1) || 
       ($suppliercode != '' && $_SESSION['ds_customname'] != 'Wing Chong'))
    {
      $query = 'select productid from product where suppliercode=? and productid<>?';
      $query_prm = array($suppliercode,$productid);
      require('inc/doquery.php');
      
      if ($num_results > 0) 
      {
        $err_suppliercode = 1;
        if($suppliercode == '') { $err_suppliercode_empty = 1; }
        else { $err_suppliercode_dup = 1; }
      }
    
      if ($eancode != '')
      {
        $query = 'select productid from product where eancode=? and productid<>?';
        $query_prm = array($eancode,$productid);
        require('inc/doquery.php');
        if ($num_results > 0) { $err_eancode = 1; }
      }
    }

    $query_vars = array('invoice_priceoption2_filterid', 'paletteid', 'fenix42', 'quantity_convert',
                        'suppliercode2', 'ti', 'hi', 'avgmonthlyspec', 'exludefromvatreport',
                        'margintype', 'commissionrateid', 'defaultitemcomment', 'accountingnumberid',
                        'promotext', 'regulationtypeid', 'warehouseid', 'temperatureid', 
                        'producttypeid', 'productdetails', 'generic', 'only_quantity_rebate',
                        'discontinued', 'eancode2', 'supplierpackaging', 'notforsale', 
                        'supplierproductfamilyid', 'margin', 'netweightlabel', 'batchalert', 
                        'orderalert', 'regulationcomment', 'transportpricepercarton', 
                        'stickerpricepercarton', 'palletpricepercarton', 'productname', 'brand',
                        'sih', 'tcp_gradient', 'productcomment', 'productfamilyid', 'countryid',
                        'volume', 'cartonweight', 'netweight', 'weight', 'numberperunit', 
                        'unittypeid', 'taxcodeid', 'supplierid', 'avantage', 'countstock',
                        'excludefromdelivery', 'code_suffixe', 'on_behalf', 'hide_price_on_invoice',
                        'no_client_discount');
    
    $query_prm = array($invoice_priceoption2_filterid,$paletteid,$fenix42,$quantity_convert,
                        $suppliercode2,$ti,$hi,$avgmonthlyspec,$exludefromvatreport,$margintype,
                        $commissionrateid,$defaultitemcomment,$accountingnumberid,$promotext,
                        $regulationtypeid,$warehouseid,$temperatureid,$producttypeid,
                        $productdetails,$generic,$only_quantity_rebate,$discontinued,$eancode2,
                        $supplierpackaging,$notforsale,$supplierproductfamilyid,$margin,
                        $netweightlabel,$batchalert,$orderalert,$regulationcomment,
                        $transportpricepercarton,$stickerpricepercarton,$palletpricepercarton,
                        d_encode($productname),$brand,$sih,$tcp_gradient,$productcomment,
                        $productfamilyid,$countryid,$volume,$cartonweight,$netweight,$weight,
                        $numberperunit,$unittypeid,$taxcodeid,$supplierid,$avantage,$countstock,
                        $excludefromdelivery,$code_suffixe,$on_behalf,$hide_price_on_invoice,
                        $no_client_discount);
     
     if(!$err_suppliercode)
     {
       array_push($query_vars, 'suppliercode');
       array_push ($query_prm, d_encode($suppliercode));
     }
     if(!$err_eancode)
     {
       array_push($query_vars, 'eancode');
       array_push ($query_prm, $eancode);
     }

     # Build the query
     $query = 'update product set ';
     $last_index = count($query_vars) - 1;
     foreach ($query_vars as $index => $variable) 
     {
       $query .= $variable . '=?';

       if ($index !== $last_index) {
         $query .= ',';
       }
     }
     $query .= ' where productid=?';
     array_push($query_prm, $productid);

     require('inc/doquery.php');
     if($num_results === 1) { $was_modified = 1; }
     elseif($num_results === 0) { $was_modified = 0; }
     elseif($num_results > 1) 
     {
      echo '<p class="alert">Problème dans la base de données. Plusieurs produits ont le 
            même identifiant.</p>';
     }
     
     if ($countstock != 1)
     {
       $query = 'update product set currentstock=0,currentstockrest=0 where productid=?';
       $query_prm = array($productid);
       require('inc/doquery.php');
     }
  }

  $showproductname = d_output($productname);
  if ($_SESSION['ds_useproductcode'] == 1 && !$err_suppliercode_empty && !$err_suppliercode_dup)
  { 
    $showproductname .= ' (' . d_output($suppliercode) . ')';
  }
  else
  {
    $showproductname .= ' (' . $productid . ')';
  }

  if($was_added === 1)
  {
    echo '<p>Produit ' . $showproductname . ' ajouté.</p>';
  }
  if($was_added !== 1 && $was_modified === 1)
  {
    echo '<p>Produit ' . $showproductname . ' modifié.</p>';
  }
  if($err_suppliercode_dup)
  {
    echo '<p class="alert">Le code produit "' . d_output($suppliercode) . '" est déjà utilisé.</p>';
  }
  if($err_eancode)
  {
    echo '<p class="alert">Le code EAN "' . d_output($eancode) . '" est déjà utilisé.</p>';
  }
  if($err_suppliercode_empty)
  {
    echo '<p class="alert">Le code produit doit être renseigné.</p>';
  }

  if ($productid > 0)
  {
    echo '<h2>Modifier produit: ' . $showproductname . '</h2>';
    
    $query = 'select imageid,imagetext,imageorder 
              from image 
              where productid=? 
              order by imageorder,imageid';
    $query_prm = array($productid);
    require('inc/doquery.php');

    for ($i=0; $i < $num_results; $i++) { $imagerow[$i] = $query_result[$i]; }
    $num_images = $num_results;
  }
  else { echo '<h2>Ajouter produit</h2>'; }

?>

  <form method="post" action="products.php">
    <table>
      <tr>
        <td>À vendre:</td>
        <td>
          <select name="notforsale">
            <?php if ($notforsale == 1) { ?>
              <option value="0">Oui</option>
              <option value="1" SELECTED>Non</option>
            <?php } else { ?>
              <option value="0" SELECTED>Oui</option>
              <option value="1">Non</option>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo $_SESSION['ds_term_discontinued']; ?>:
        </td>
        <td>
          <select name="discontinued">
            <?php if ($discontinued == 1) { ?>
              <option value="0">Non</option>
              <option value="1" SELECTED>Oui</option>
            <?php } else { ?>
              <option value="0" SELECTED>Non</option>
              <option value="1">Oui</option>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Nom du produit : </td>
        <td>
          <input type="text" name="productname" value="<?php echo d_input($productname) ?>" size=80>
        </td>
      </tr>
        <?php
          if (isset($num_images) && $num_images > 0)
          {
            echo '<tr><td>Images:</td><td>';
            for ($i=0; $i < $num_images; $i++)
            {
              if ($i > 0) { echo ', '; }
              if ($imagerow[$i]['imagetext'] == '') { $imagerow[$i]['imagetext'] = '(sans nom)'; }
              
              echo '<a href="reportwindow.php?report=productimages&productid=' . $productid 
                    . '&imageid=' . $imagerow[$i]['imageid'] . '" target=_blank>' 
                    . $imagerow[$i]['imagetext'] . '</a>';
            }
          }
        ?>
      <tr>
        <td>Code produit:</td>
        <td>
          <input type="text" name="suppliercode" 
                 value="<?php echo d_input($suppliercode); ?>" size=20 
                 <?php if($err_suppliercode){echo 'class="alert";';} ?>>
        </td>
      </tr>
      <tr>
        <td>Code fournisseur:</td>
        <td>
          <input type="text" name="suppliercode2" value="<?php echo d_input($suppliercode2); ?>" 
                 size=20>
        </td>
      </tr>
      <tr>
        <td>Code EAN unité:</td>
        <td>
          <input type="text" name="eancode" value="<?php echo d_input($eancode); ?>" size=20
                 <?php if($err_eancode){ echo 'class="alert";'; } ?>>
          <?php
            if ($eancode != '' && !validate_EAN13Barcode($eancode)) 
            { 
              echo ' &nbsp; <span class="alert">Code EAN13 Invalide</span>'; 
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>Code EAN carton:</td>
        <td>
          <input type="text" name="eancode2" value="<?php echo d_input($eancode2); ?>" size=20>
          <?php
            if ($eancode != '' && !validate_EAN13Barcode($eancode2)) 
            { 
              echo ' &nbsp; <span class="alert">Code EAN13 Invalide</span>'; 
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Unité de vente:
          <?php
            $dp_itemname = "unittype"; $dp_noblank = 1;
            $dp_selectedid = $unittypeid;
            require('inc/selectitem.php');
          ?>
        </td>
      </tr>
      <tr>
        <td>Unités par carton:</td>
        <td>
          <input STYLE="text-align:right" size=5 type="number" name="numberperunit" min="1" 
                 value="<?php echo $numberperunit?>">
          &nbsp; <span class="alert">À changer pour produits nouveaux uniquement</span>
        </td>
      </tr>
      <tr>
        <td>Description unité:</td>
        <td>
          <input type="text" STYLE="text-align:right" name="netweightlabel" 
                 value="<?php echo d_input($netweightlabel); ?>" size=20>
        </td>
      </tr>
      <?php
        if (isset($paletteA))
        {
          echo '<tr><td>Palette:'; 
          $dp_itemname = 'palette'; 
          $dp_selectedid = $paletteid; 
          require('inc/selectitem.php');
        }
        if (isset($invoice_priceoption2_filterA))
        {
          echo '<tr><td>Filtre '.d_output($_SESSION['ds_term_invoice_priceoption2']).':'; 
          $dp_itemname = 'invoice_priceoption2_filter';
          $dp_selectedid = $invoice_priceoption2_filterid; 
          require('inc/selectitem.php');
        }
      ?>
      <tr>
        <td>Compter stock pour ce produit:</td>
        <td>
          <input type=checkbox name="countstock" value=1 
                 <?php if ($countstock == 1) { echo 'checked'; } ?>>
        </td>
      </tr>
      <tr>
        <td>Ne pas afficher les prix (sur facture):</td>
        <td>
          <input type=checkbox name="hide_price_on_invoice" value=1 
                 <?php if ($hide_price_on_invoice == 1) { echo 'checked'; } ?>>
        </td>
      </tr>
      <tr>
        <td>Pas de livraison pour ce produit:</td>
        <td>
          <input type=checkbox name="excludefromdelivery" value=1 
                  <?php if ($excludefromdelivery == 1) { echo 'checked'; } ?>>
        </td>
      </tr>
      <tr>
        <td>Convertir quantité par rapport aux cartons:</td>
        <td>
          <?php if ($quantity_convert == 0) { $quantity_convert = ''; } ?>
          <input STYLE="text-align:right" size=5 type="number" name="quantity_convert" step="0.0001" 
                 value="<?php echo $quantity_convert ?>"> (optionnel) 
          <span class="alert">Uniquement pour conversion des quantités en nombre de cartons</span>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Code TVA:
          <?php
            $dp_itemname = "taxcode";
            $dp_noblank = 1;
            $dp_selectedid = $taxcodeid;
            require('inc/selectitem.php');
            
            echo '&nbsp; &nbsp; <input type=checkbox name="exludefromvatreport" value=1';
            if ($exludefromvatreport) { echo ' checked'; }
            echo '> Exclure du rapport TVA';
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?php
            $dp_description = 'Exception comptable'; 
            $dp_itemname = 'accountingnumber'; 
            $dp_selectedid = $accountingnumberid;
            require('inc/selectitem.php'); 
          ?>
          &nbsp; Attribuée à un autre compte que celui par défaut
        </td>
      </tr>
      <tr>
        <td>Remisable:</td>
        <td>
          <select name="only_quantity_rebate">
            <option value="0" <?php if ($only_quantity_rebate === 0) { echo 'selected'; } ?>>
              Oui
            </option>
            <option value="2" <?php if ($only_quantity_rebate === 2) { echo 'selected'; } ?>>
              Non
            </option>
            <option value="1" <?php if ($only_quantity_rebate === 1) { echo 'selected'; } ?>>
              Uniquement en quantité
            </option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Ne pas appliquer la Remise par défaut (Client):</td>
        <td>
          <input type=checkbox name="no_client_discount" value=1
                <?php if ($no_client_discount) { echo ' checked'; } ?>>
        </td>
      <tr>
        <td>Générique:</td>
        <td>
          <select name="generic">
            <?php
              if ($generic == 1) 
              { 
                echo '<option value="0">Non</option>
                      <option value="1" SELECTED>Oui</option>
                      <option value="2">Oui, quantité libre</option>'; 
              }
              elseif ($generic == 2) 
              { 
                echo '<option value="0">Non</option>
                      <option value="1">Oui</option>
                      <option value="2" SELECTED>Oui, quantité libre</option>'; 
              }
              else 
              { 
                echo '<option value="0" SELECTED>Non</option>
                      <option value="1">Oui</option>
                      <option value="2">Oui, quantité libre</option>'; 
              }
            ?>
          </select> 
          &nbsp; Les produits génériques n'ont pas de prix fixe
        </td>
      </tr>
      <tr>
        <td>
          <?php
            $dp_description = 'Taux commission'; 
            $dp_itemname = 'commissionrate'; 
            $dp_selectedid = $commissionrateid;
            require('inc/selectitem.php');
          ?>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Fournisseur:</td>
        <td>
          <select name="supplierid">
            <option value=0></option>
            <?php
              $query = 'select clientid,clientname from client where deleted=0 and issupplier=1 
                        order by clientname';
              $query_prm = array();
              require('inc/doquery.php');

              for ($i=0; $i < $num_results; $i++)
              {
                echo '<option value="' . $query_result[$i]['clientid'] . '"';
                if ($supplierid == $query_result[$i]['clientid']) { echo ' selected'; }
                echo '>' . d_output(d_decode($query_result[$i]['clientname'])) . '</option>';
              }
            ?>
          </select>
          &nbsp; <input type="checkbox" name="on_behalf" value=1 
                        <?php if ($on_behalf) { echo ' checked'; } ?>> Débours
        </td>
      </tr>
      <tr>
        <td>Marque:</td>
        <td>
          <input type="text" name="brand" value="<?php echo d_input($brand); ?>" size=20>
        </td>
      </tr>
      <tr>
        <td>Pays d'origine:
          <?php
            $dp_itemname = "country";
            $dp_noblank = 1;
            $dp_selectedid = $countryid;
            require('inc/selectitem.php');
          ?>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Type de produit:
          <?php
            $dp_itemname = "producttype";
            $dp_selectedid = $producttypeid;
            require('inc/selectitem.php');
          ?>
        </td>
      </tr>
      <tr>
        <td>Température:
          <?php
            $dp_itemname = "temperature";
            $dp_selectedid = $temperatureid;
            require('inc/selectitem.php');
          ?>
        </td>
      </tr>
      <tr>
        <td>Famille de produit:</td>
        <td>
          <select name="productfamilyid">
            <?php
              $query = 'select productfamilyid,productfamilyname,productfamilygroupname,
                        productdepartmentname from productfamily,productfamilygroup,
                        productdepartment where productfamilygroup.productdepartmentid=
                        productdepartment.productdepartmentid 
                        and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid 
                        order by productdepartmentname,productfamilygroupname,productfamilyname';
              $query_prm = array();
              require('inc/doquery.php');

              for ($i=0; $i < $num_results; $i++)
              {
                $row = $query_result[$i];
                if ($row['productfamilyid'] == $productfamilyid)
                { 
                  echo '<option value="' . $row['productfamilyid'] . '" SELECTED>' 
                       . $row['productdepartmentname'] . '/' . $row['productfamilygroupname'] . '/' 
                       . $row['productfamilyname'] . '</option>'; 
                }
                else 
                { 
                  echo '<option value="' . $row['productfamilyid'] . '">' 
                       . $row['productdepartmentname'] . '/' . $row['productfamilygroupname'] . '/' 
                       . $row['productfamilyname'] . '</option>'; 
                }
              }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Réglementation:
          <?php
            $dp_itemname = "regulationtype"; 
            $dp_noblank = 1;
            $dp_selectedid = $regulationtypeid;
            require('inc/selectitem.php');
          ?>
          &nbsp; FENIX case 41 obligatoire: 
          <input type=checkbox name=fenix42 value=1 <?php if ($fenix42) { echo ' checked'; } ?>>
        </td>
      </tr>
      <tr>
        <td>Numéro SIH:</td>
        <td>
          <input type="text" name="sih" value="<?php echo d_input($sih); ?>" size=20> &nbsp; &nbsp; 
          
          Avantage: <input type="text" name="avantage" value="<?php echo d_input($avantage); ?>" 
                           size=20> &nbsp; &nbsp; 
          Suffixe: <input type="text" name="code_suffixe" 
                          value="<?php echo d_input($code_suffixe); ?>" size=20>
        </td>
      </tr>
      <tr>
        <td>TCP Gradient:</td>
        <td>
          <select name="tcp_gradient">
            <option value=0>NON TAXABLE</option>
            <option value=1 <?php if ($tcp_gradient==1) echo ' selected';?>>
              GRADIENT1 Teneur du produit en sucre de 0 à 4,99 g
            </option>
            <option value=2 <?php if ($tcp_gradient==2) echo ' selected';?>>
              GRADIENT2 Teneur du produit en sucre de 5 à 9,99 g
            </option>
            <option value=3 <?php if ($tcp_gradient==3) echo ' selected';?>>
              GRADIENT3 Teneur du produit en sucre de 10 à 29,99 g
            </option>
            <option value=4 <?php if ($tcp_gradient==4) echo ' selected';?>>
              GRADIENT4 Teneur du produit en sucre de 30 à 39,99 g
            </option>
            <option value=5<?php if ($tcp_gradient==5) echo ' selected';?>>
              GRADIENT5 Teneur du produit en sucre de 40 g et +
            </option>
          </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Consommation (ventes et pertes) moyenne par mois:</td>
        <td>
          <input type="text" name="avgmonthlyspec" value="<?php 
                 if($avgmonthlyspec == 0){echo "";}
                 else{ echo d_input(round($avgmonthlyspec/$numberperunit,2)); } ?>" 
                 size=10 STYLE="text-align:right">
          <input type=hidden name="avgmonthly" value="<?php echo $avgmonthly; ?>">
          &nbsp; Calculé = <?php echo round((double)$avgmonthly/(double)$numberperunit,2); ?>
        </td>
      </tr>
      <tr>
        <td>Frais transport / carton:</td>
        <td>
          <input type="text" name="transportpricepercarton" value="<?php 
                 if($transportpricepercarton == 0){ echo ""; }
                 else{ echo d_input($transportpricepercarton); } ?>" 
                 size=10 STYLE="text-align:right">
        </td>
      </tr>
      <tr>
        <td>Frais étiquetage / carton:</td>
        <td>
          <input type="text" name="stickerpricepercarton" value="<?php 
                 if($stickerpricepercarton == 0) { echo ""; }
                 else{ echo d_input($stickerpricepercarton); }?>" 
                 size=10 STYLE="text-align:right">
        </td>
      </tr>
      <tr>
        <td>Frais A/R Palette:</td>
        <td>
          <input type="text" name="palletpricepercarton" value="<?php 
                 if($palletpricepercarton == 0) { echo ""; } 
                 else { echo d_input($palletpricepercarton);} ?>" 
                 size=10 STYLE="text-align:right">
        </td>
      </tr>
      <tr>
        <td>Poids Brut / carton:</td>
        <td>
          <input type="text" name="weight" value="<?php 
                 if($weight == 0) { echo ""; }
                 else { echo d_input($weight); } ?>" 
                 size=10 STYLE="text-align:right"> grammes
        </td>
      </tr>
      <tr>
        <td>Poids Net / unité:</td>
        <td>
          <input type="text" name="netweight" value="<?php 
                 if($netweight == 0) { echo "";} 
                 else{echo d_input($netweight);} ?>" 
                 size=10 STYLE="text-align:right"> grammes
        </td>
      </tr>
      <tr>
        <td>Volume / carton:</td>
        <td>
          <input type="text" name="volume" value="<?php 
                 if($volume == 0) { echo ""; }
                 else { echo d_input($volume); } ?>" 
                 size=10 STYLE="text-align:right"> m<sup>3</sup>
        </td>
        <?php
          if ($unittype_dmpA[$unittypeid] = 1000)
          {
            echo '<tr><td>Poids / carton (pour produits en KG):</td>
                  <td><input type="text" name="cartonweight" value="';
            if($cartonweight == 0) { echo ''; } else { echo d_input($cartonweight); }
            echo '" size=10 STYLE="text-align:right"> KG</sup>';
            echo ' &nbsp; <a href="products.php?productsmenu=addproduct&productid=' . $productid
                 . '&calccartonweight=1">Calculer</a>';
          }
          if (isset($warehouseA))
          {
            echo '<tr><td>Entrepôt par defaut:';
            $dp_itemname = "warehouse";
            $dp_selectedid = $warehouseid;
            require('inc/selectitem.php');
          }
        ?>
      <tr>
        <td>TI x HI</td>
        <td>
          <?php
            if ($ti == 0) { $ti = ''; }
            if ($hi == 0) { $hi = ''; }
          ?>
          <input type=number min=0 name="ti" value="<?php echo d_input($ti); ?>" size=10> x
          <input type=number min=0 name="hi" value="<?php echo d_input($hi); ?>" size=10>
        </td>
      </tr>
      <tr>
        <td colspan=2>&nbsp;</td>
      </tr>
      <tr>
        <td>Marge:</td>
        <td>
          <input type="text" name="margin" value="<?php 
                 if($margin == 0) { echo ""; } 
                 else {echo d_input($margin);} ?>" size=20 STYLE="text-align:right">
          <select name="margintype">
            <?php
              echo '<option value=0>Pourcentage</option>';
              echo '<option value=1'; if ($margintype == 1) { echo ' selected'; }
              echo '>Valeur</option><option value=2'; if ($margintype == 2) { echo ' selected'; }
              echo '>Prix Entr. Réel/Carton</option>';
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Seuil d'alerte / Lots:</td>
        <td>
          <input type="text" name="batchalert" value="<?php 
                 if($batchalert == 0) { echo ""; }
                 else{ echo d_input($batchalert);} ?>" 
                 size=10 STYLE="text-align:right"> Quand faut-il changer le prix? 
        </td>
      </tr> 
      <tr>
        <td>Seuil d'alerte / Commande:</td>
        <td>
          <input type="text" name="orderalert" value="<?php 
                 if($orderalert == 0) { echo ""; }
                 else { echo d_input($orderalert); } ?>" 
                 size=10 STYLE="text-align:right"> Quand faut-il commander?
        </td>
      </tr>
      <tr>
        <td>Commentaire:</td>
        <td>
          <input type="text" name="productcomment" value="<?php echo d_input($productcomment); ?>" 
                 size=100>
        </td>
      </tr>
      <tr>
        <td>Description détaillée:</td>
        <td>
          <input type="text" name="productdetails" value="<?php echo d_input($productdetails); ?>" 
                 size=100>
        </td>
      </tr>
      <tr>
        <td>Infos promo:</td>
        <td>
          <input type="text" name="promotext" value="<?php echo d_input($promotext); ?>" size=100>
        </td>
      </tr>
      <tr>
        <td>Réglementation pour Prix de Detail:</td>
        <td>
          <input type="text" name="regulationcomment" 
                 value="<?php echo d_input($regulationcomment); ?>" size=100>
        </td>
      </tr>
      <tr>
        <td>Conditionnement fournisseur:</td>
        <td>
          <input type="text" name="supplierpackaging" 
                 value="<?php echo d_input($supplierpackaging); ?>" size=20>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign=top>Commentaire par défaut:</td>
        <td>
          <textarea name="defaultitemcomment" rows=6 cols=80><?php echo d_input($defaultitemcomment)?></textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type=hidden name="saveme" value="1">
          <input type=hidden name="productid" value="<?php echo $productid; ?>">
          <input type=hidden name="productsmenu" value="<?php echo $productsmenu; ?>">
          <input type="submit" name="valider" value="Valider">
        </td>
      </tr>
    </table>
  </form>
<?php } ?>