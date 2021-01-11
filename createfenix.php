<?php

if ($_SESSION['ds_purchaseaccess'] != 1) { require('logout.php'); exit; }
require ('inc/standard.php');

require('preload/fenix_req_procedure.php');
require('preload/fenix_prev_procedure.php');
require('preload/vessel.php');

$PA['b31'] = 'uint';
require('inc/readpost.php');

$shipmentid = (int) $_POST['shipmentid'];
$temperature = 0;

$query = 'select reg_sta,sofixprocedure,sofixrf,sofixdf,shipmentid,incotermname,currencyacronym,freightcost,insurance,origincountryid,fromcountryid,
total_invoiced,fenix_transmodeid,arrivaldate,shipmentcomment,vesselid,numberofcontainers20cold,numberofcontainers40cold,numberofcontainers20dooropen
from shipment,incoterm,currency where shipment.incotermid=incoterm.incotermid and shipment.currencyid=currency.currencyid and shipmentid=?';
$query_prm = array($shipmentid);
require ('inc/doquery.php');
$incotermname = $query_result[0]['incotermname'];
$currencyacronym = $query_result[0]['currencyacronym'];
$freightcost = $query_result[0]['freightcost']+0;
$insurance = $query_result[0]['insurance']+0;
$origincountryid = $query_result[0]['origincountryid'];
$fromcountryid = $query_result[0]['fromcountryid'];
$sofixdf = $query_result[0]['sofixdf'];
$reg_fin = $query_result[0]['sofixrf'];
$reg_sta = $query_result[0]['reg_sta'];
$sofixprocedure = $query_result[0]['sofixprocedure'];
$total_invoiced = 0; # 2019 07 31 recalc instead
$fenix_transmodeid = $query_result[0]['fenix_transmodeid'];
$arrivaldate = $query_result[0]['arrivaldate'];
$shipmentcomment = $query_result[0]['shipmentcomment'];
$vesselname = $vesselA[$query_result[0]['vesselid']];
if ($query_result[0]['numberofcontainers20cold'] > 0
|| $query_result[0]['numberofcontainers40cold'] > 0
|| $query_result[0]['numberofcontainers20dooropen'] > 0
) { $temperature = 1; }

$query = 'select idtahiti,infoaddress1 from companyinfo where companyinfoid=1';
$query_prm = array();
require ('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];
$infoaddress1 = $query_result[0]['infoaddress1'];

$query = 'select clientname as suppliername,postaladdress,postalcode,address,fenixcode
from client,product,purchase,country
where client.clientid=product.supplierid and client.countryid=country.countryid and product.productid=purchase.productid
and shipmentid=? limit 1';
$query_prm = array($shipmentid);
require('inc/doquery.php');
$suppliername = $query_result[0]['suppliername'];
$supplier_countryname = $query_result[0]['fenixcode'];
$supplier_address = $query_result[0]['address'] . ' ' . $query_result[0]['postalcode'] . ' ' . $query_result[0]['postaladdress'];

$query = 'select purchaseid,avantage,supplierid,purchase.productid as productid,sih,weight,netweight,amount,amountcartons,numberperunit
,purchaseprice,fenixcode,case_j,fenix_req_procedureid,fenix_prev_procedureid,brand,temperatureid,code_suffixe,fenix42
from purchase,product,country
where purchase.productid=product.productid and product.countryid=country.countryid and purchase.shipmentid=? order by purchaseid';
$query_prm = array($shipmentid);
require('inc/doquery.php');
$items_result = $query_result;
$num_items = $num_results;
$gross_mass = 0;
for($i=0; $i < $num_items; $i++)
{
  $total_invoiced += $items_result[$i]['purchaseprice'];
  $gross_mass += round($items_result[$i]['weight'] * $items_result[$i]['amount'] / 1000,3);
  if ($items_result[$i]['temperatureid'] > $temperature) { $temperature = $items_result[$i]['temperatureid']; }
}

# 2019 08 06 read lines from Note de Detail instead (regrouped by SIH)
$query = 'select * from fenix_lines where shipmentid=? order by fenix_linesid'; # linenr
$query_prm = array($shipmentid);
require('inc/doquery.php');
$sih_result = $query_result;
$num_sih = $num_results;

$query = 'select currencyacronym from shipment,currency where shipment.freightcostcurrencyid=currency.currencyid and shipmentid=?';
$query_prm = array($shipmentid);
require ('inc/doquery.php');
if ($num_results) { $fccurrencyacronym = $query_result[0]['currencyacronym']; }
if ($num_results == 0 || $fccurrencyacronym == '') { $fccurrencyacronym = 'XPF'; }

$query = 'select currencyacronym from shipment,currency where shipment.insurancecurrencyid=currency.currencyid and shipmentid=?';
$query_prm = array($shipmentid);
require ('inc/doquery.php');
if ($num_results) { $icurrencyacronym = $query_result[0]['currencyacronym']; }
if ($num_results == 0 || $icurrencyacronym == '') { $icurrencyacronym = 'XPF'; }

$query = 'select fenixcode from country where countryid=?';
$query_prm = array($origincountryid);
require ('inc/doquery.php');
if ($num_results) { $origincountry = $query_result[0]['fenixcode']; }
else { $origincountry = ''; }

$query = 'select fenixcode from country where countryid=?';
$query_prm = array($fromcountryid);
require ('inc/doquery.php');
if ($num_results) { $fromcountry = $query_result[0]['fenixcode']; }
else { $fromcountry = ''; }

try
{
$xml = new SimpleXMLElement("<message></message>");
  $header = $xml->addChild('header');
    $module = $header->addChild('module');
      $module[0] = 'Clearance';
    $action = $header->addChild('action');
      $action[0] = 'X12';
    $direction = $header->addChild('direction');
      $direction[0] = 'in';
    $user_id = $header->addChild('user_id');
      $user_id[0] = 'Oooo57'; # TODO
    $information = $header->addChild('information');
      $information[0] = 'Load and register CD from DTI server';
  $data = $xml->addChild('data');
    $data_in = $data->addChild('data_in');
      $sad = $data_in->addChild('sad');
        $gs = $sad->addChild('gs');
          #$boxa_office_country_code = $gs->addChild('boxa_office_country_code');
          $boxa_office_code = $gs->addChild('boxa_office_code');
            # PFPPT ou PFFAA (TODO)
            if ($sofixdf == 'FAA') { $boxa_office_code[0] = 'PFFAA'; }
            else { $boxa_office_code[0] = 'PFPPT'; }
          $boxa_office_sub_code = $gs->addChild('boxa_office_sub_code');
            $boxa_office_sub_code[0] = 'AUT'; # NATSUB # email 2019 07 30 AQP a été remplacer par AUT
          $b1_decla_sub1 = $gs->addChild('b1_decla_sub1');
            $b1_decla_sub1[0] = 'IM';
          $b1_decla_sub2 = $gs->addChild('b1_decla_sub2');
            $b1_decla_sub2[0] = 'A'; # A complete B incomplete
          $b2_consignor = $gs->addChild('b2_consignor');
            $registered = $b2_consignor->addChild('registered');
              $registered[0] = 0;
            $trnumber = $b2_consignor->addChild('trnumber');
            $businessname = $b2_consignor->addChild('businessname');
              $businessname[0] = $suppliername;
            $businessnation = $b2_consignor->addChild('businessnation');
              $businessnation[0] = $supplier_countryname;
            $businessaddr = $b2_consignor->addChild('businessaddr');
              $businessaddr[0] = $supplier_address;
          #b5_items N3 Nombre article dans le DAU
          $b7_ref_num = $gs->addChild('b7_ref_num');
            $b7_ref_num[0] = $shipmentid;
          $b8_consignee = $gs->addChild('b8_consignee');
            $registered = $b8_consignee->addChild('registered');
              $registered[0] = 1;
            $trnumber = $b8_consignee->addChild('trnumber');
              $trnumber[0] = $idtahiti;
          $b9_currency_code_freight = $gs->addChild('b9_currency_code_freight');
            $b9_currency_code_freight[0] = $fccurrencyacronym;
          $b9_currency_code_insurance = $gs->addChild('b9_currency_code_insurance');
            $b9_currency_code_insurance[0] = $icurrencyacronym;
          $b9_total_insurance = $gs->addChild('b9_total_insurance');
            $b9_total_insurance[0] = $insurance;
          $b9_total_freight = $gs->addChild('b9_total_freight');
            $b9_total_freight[0] = $freightcost;
          $b14_declarant = $gs->addChild('b14_declarant');
            $registered = $b14_declarant->addChild('registered');
              $registered[0] = 1;
            $trnumber = $b14_declarant->addChild('trnumber');
              $trnumber[0] = $idtahiti;
          $b14_declarant_type = $gs->addChild('b14_declarant_type');
            $b14_declarant_type[0] = 1;
          $b15_dispatch_ctr = $gs->addChild('b15_dispatch_ctr');
            $b15_dispatch_ctr[0] = $origincountry;
          #b17_dest_country PF
          $b18_trans_id = $gs->addChild('b18_trans_id'); # Identité du moyen de transport au départ/à l'arrivée ???
          $b18_trans_nat = $gs->addChild('b18_trans_nat');
          $b20_deliv_terms_sub1 = $gs->addChild('b20_deliv_terms_sub1'); # Incoterm
            $b20_deliv_terms_sub1[0] = $incotermname;
          $b20_deliv_terms_sub2 = $gs->addChild('b20_deliv_terms_sub2'); # Endroit nommé ???
            if ($incotermname == 'CFR' || $incotermname == 'CIF') { $b20_deliv_terms_sub2[0] = 'PAPEETE'; }
            else { $b20_deliv_terms_sub2[0] = $supplier_countryname; }
          $b21_trans_id = $gs->addChild('b21_trans_id');
            $b21_trans_id[0] = $vesselname;
          $b21_trans_nat = $gs->addChild('b21_trans_nat'); # Nationalité compagnie de transport ???
          $b22_currency_code = $gs->addChild('b22_currency_code'); # currency of invoice
            $b22_currency_code[0] = $currencyacronym;
          $b22_total_amount = $gs->addChild('b22_total_amount'); # total of invoice
            $b22_total_amount[0] = round($total_invoiced,4);
          if ($temperature > 0) #surgeles, refrigeres, et famille epicerie/legumes frais
          {
            $b24_particular_procedure = $gs->addChild('b24_particular_procedure'); # total of invoice
            $b24_particular_procedure[0] = 'PR';
          }
          $b25_border_trans = $gs->addChild('b25_border_trans');
            $b25_border_trans[0] = $fenix_transmodeid;
          $b26_arrival_date = $gs->addChild('b26_arrival_date');
            $b26_arrival_date[0] = $arrivaldate;
          $b27_place_of_loading = $gs->addChild('b27_place_of_loading'); # text
            $b27_place_of_loading[0] = 'PFPPT'; # PFPPT ou PFFAA (TODO)
          #$b28_garantee = $gs->addChild('b28_garantee');
          $b29_exit_office_sub1 = $gs->addChild('b29_exit_office_sub1');
          $b29_exit_office_sub2 = $gs->addChild('b29_exit_office_sub2');
          $b29_exit_office_sub3 = $gs->addChild('b29_exit_office_sub3');
          $b30_location = $gs->addChild('b30_location');
            #$b30_location[0] = 'PPT'; #LOCATION 2019 08 02 took off at request
          $box47_cd_pay_method = $gs->addChild('box47_cd_pay_method');
            $box47_cd_pay_method[0] = 'F'; #PAYMENTMETHOD
          $b48_deferred_pay = $gs->addChild('b48_deferred_pay');
            $b48_deferred_pay[0] = 'CE-'.$idtahiti; #Numéro compte CE   (apprently equal to no tahiti)
          $boxd_comments_declarant = $gs->addChild('boxd_comments_declarant'); # description of items
          $boxd_seals_num_declaration = $gs->addChild('boxd_seals_num_declaration'); #Scellés présents pour la déclaration ???
          $container_segment = $gs->addChild('container_segment');
            $containerA = explode(" ", $shipmentcomment);
            foreach ($containerA as $container_num)
            {
            $container_segment_item = $container_segment->addChild('container_segment_item');
              $num = $container_segment_item->addChild('num');
                $num[0] = $container_num; # container number  max 11 chars
            }
        $is = $sad->addChild('is');
        # (2019 08 02 TODO read lines from Note de Detail instead (regrouped by SIH)) still needed???
        for($i=0; $i < $num_sih; $i++)
        {
          $item = $is->addChild('item');
            $item_num = $item->addChild('item_num');
              $item_num[0] = ($i + 1);
            $b31_pack_in_item = $item->addChild('b31_pack_in_item'); # Indique dans quel article la description des colis est présente ???
              if ($b31 == 0) { $b31_pack_in_item[0] = '001'; }
              else
              {
                $b31_pack_in_item[0] = ($i + 1); # "001"
                if (strlen($b31_pack_in_item[0]) == 1) { $b31_pack_in_item[0] = '00'.$b31_pack_in_item[0]; }
                if (strlen($b31_pack_in_item[0]) == 2) { $b31_pack_in_item[0] = '0'.$b31_pack_in_item[0]; }
              }
            $b33_comb_nomen_code = $item->addChild('b33_comb_nomen_code'); # SIH, only numbers
              $b33_comb_nomen_code[0] = preg_replace('/\D/', '', $sih_result[$i]['sih']);
            $b33_taric_code_a = $item->addChild('b33_taric_code_a');
              $b33_taric_code_a[0] = $sih_result[$i]['avantage'];
            $b33_taric_code_c = $item->addChild('b33_taric_code_c');
              $b33_taric_code_c[0] = $sih_result[$i]['code_suffixe'];
            $b34_origin_country = $item->addChild('b34_origin_country');
              $b34_origin_country[0] = $sih_result[$i]['fenixcode'];
            $b35_gross_mass = $item->addChild('b35_gross_mass'); # N6,3 kg
              $b35_gross_mass[0] = round($sih_result[$i]['gross_mass'],3);
            $b36_preference = $item->addChild('b36_preference'); # maybe TODO now that we use sih_result
              if ($sih_result[$i]['case_j'] == '') { $sih_result[$i]['case_j'] = 100; }
              elseif ($sih_result[$i]['case_j'] == 'EU') { $sih_result[$i]['case_j'] = 300; }
              $b36_preference[0] = $sih_result[$i]['case_j'];
            $b37_requested_pro = $item->addChild('b37_requested_pro');
              if (isset($fenix_req_procedure_codeA[$sih_result[$i]['fenix_req_procedureid']]))
              {
                $b37_requested_pro[0] = $fenix_req_procedure_codeA[$sih_result[$i]['fenix_req_procedureid']];
              }
              else
              {
                $b37_requested_pro[0] = '00';
              }
            $b37_previous_pro = $item->addChild('b37_previous_pro');
              if (isset($fenix_prev_procedure_codeA[$sih_result[$i]['fenix_prev_procedureid']])
                && $_SESSION['ds_customname'] != 'Wing Chong') # Wing Chong always wants 00
              {
                $b37_previous_pro[0] = $fenix_prev_procedure_codeA[$sih_result[$i]['fenix_prev_procedureid']];
              }
              else
              {
                $b37_previous_pro[0] = '00';
              }
            $b37_cat_type = $item->addChild('b37_cat_type'); # not mandatory
            $b37_subcat = $item->addChild('b37_subcat');
            $b38_net_mass = $item->addChild('b38_net_mass'); # mandatory N..6,3
              $b38_net_mass[0] = round(($sih_result[$i]['net_mass']),3); # 2019 08 21, removed " / 1000 "
            $b39_beneficiary = $item->addChild('b39_beneficiary');
              if ($sih_result[$i]['case_j'] != '') { $b39_beneficiary[0] = $idtahiti; }
            if ($sih_result[$i]['fenix42']) # only for products like "sucre"
            {
            $b41_unit_num = $item->addChild('b41_unit_num');
              $b41_unit_num[0] = round($sih_result[$i]['net_mass'],0); # needs decimals but error on 3 decimals...
            }
            $b42_item_price = $item->addChild('b42_item_price');
              $b42_item_price[0] = $sih_result[$i]['b42_item_price'];
            $b43_valuation_method = $item->addChild('b43_valuation_method');
              $b43_valuation_method[0] = '1';
            $b44_declared_units = $item->addChild('b44_declared_units');
              $b44_declared_units[0] = $sih_result[$i]['b44_declared_units'];
            $b44_declared_code = $item->addChild('b44_declared_code');
              $b44_declared_code[0] = 'COL';
            # package segment is mandatory and quantity has to match, TODO split from SIH to each line
            $package_segment = $item->addChild('package_segment');
              $package_segment_item = $package_segment->addChild('package_segment_item');
                $ptype = $package_segment_item->addChild('ptype');
                  $ptype[0] = 'PK'; # CN = container, PK = colis
                $lp_ref = $package_segment_item->addChild('lp_ref');
                  $lp_ref[0] = ($i+1);
                $quantity = $package_segment_item->addChild('quantity');
                  $quantity[0] = (int) $sih_result[$i]['b44_declared_units'];
                $marks = $package_segment_item->addChild('marks');
                  $marks[0] = ($i + 1); # 2020 12 01 see emails
                $gross_weight = $package_segment_item->addChild('gross_weight');
                  $gross_weight[0] = round($sih_result[$i]['gross_mass'],3);
                $description = $package_segment_item->addChild('description');
                  $description[0] = ($i + 1); # 2020 07 23 quick test to get around error message
        }
  header('Content-type: text/xml');
  if ($_POST['display'] != 1) { header("Content-Disposition: attachment; filename=tofenix.xml"); }
  echo $xml->asXML();
}
catch (Exception $e)
{
  echo '<p>Erreur: ',  $e->getMessage();
}

?>