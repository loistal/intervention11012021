<?php

# TODO move this to custom folder

$alg_value = 0; $algorithm = 0;

$query = 'select algorithm from calcpricing where productid=?';
$query_prm = array($productidA['productid' . $i]);
require('inc/doquery.php');
if ($num_results) { $algorithm = (int) $query_result[0]['algorithm']; }

switch ($algorithm)
{
  # freight GC MG to French Polynesian Islands from Papeete (pid 4206 Wing Chong)
  case 1:
  
  # find destination island/region
  $performcalc_temp = 1;
  $minprice_temp = 609;
  switch ($island_freightzoneidA[$islandid])
  {
    case 2: # ISLV
    if ($islandid == 2)
    {
      # Moorea
      $performcalc_temp = 0;
      $quantity_temp = 0;
      $volume_temp = 0;
      for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
      {
        if (isset($productidA['productid' . $i_temp]))
        {
          if ($unitorcartonA['unitorcarton' . $i_temp]) { $quantity_temp += $quantityA['quantity' . $i_temp]; }
          else { $quantity_temp += ($quantityA['quantity' . $i_temp] * $npuA[$productidA['productid' . $i_temp]]); }
          $volume_temp += myround($volumeA[$productidA['productid' . $i_temp]],3);
        }
      }
      if (($clientcategoryid >= 26 && $clientcategoryid <= 30) || $clientcategoryid == 23 || $clientcategoryid == 22 || $clientcategoryid == 40) # magasin
      {
        # see email 2018 11 26 by MTC from Wing Chong
        $alg_value = 1180 * ($volume_temp * $quantity_temp);
        if ($alg_value < $minprice_temp) { $alg_value = $minprice_temp; }
        $alg_value += ($volume_temp * $quantity_temp) * 100;
      }
      else
      {
        # see email 2018 11 26 by MTC from Wing Chong
        # 100fcp/colis  + TPA  fret minimum 609fcp
        # par contre, si les colis dépassent 6 quantité, pas de TPA
        $alg_value = 100 * $quantity_temp;
        if ($alg_value < $minprice_temp) { $alg_value = $minprice_temp; }
        # ex le volume 0.032 revient a 3fcp , 0.185 revient a 18fcp, 0.028 revient a 3fcp etc...
        # donc le total des 2 riz et 3 sucre= 609+ 4= 613fcp par contre, si les colis sont a partir de 7
        if ($quantity_temp < 7) { $alg_value += round($volume_temp * 100); }
        # apparently not correct...
        $alg_value = 0;
      }
    }
    else
    {
      # other ISLV
      $gc_temp = 2872;
      $mg_temp = 3350;
      $frigo_temp = 0;
    }
    break;
    
    case 3: # Maupiti
    $gc_temp = 5146;
    $mg_temp = 5982;
    $frigo_temp = 0;
    break;
    
    case 4: # Mopelia Scilly Bel. Tupai
    $gc_temp = 10290;
    $mg_temp = 11896;
    $frigo_temp = 0;
    break;
    
    case 5: # Maiao
    $gc_temp = 3111;
    $mg_temp = 3531;
    $frigo_temp = 0;
    break;
    
    case 6: # Australes
    $gc_temp = 12863;
    $mg_temp = 14819;
    $frigo_temp = 0;
    if ($localvesselid == 6) # Tahiti Nui 6
    {
      $gc_temp = 14792;
      $mg_temp = 17042;
      $frigo_temp = 0;
    }
    break;
    
    case 7: # Marquises
    $gc_temp = 15269;
    $mg_temp = 16397;
    $frigo_temp = 0;
    break;
    
    case 8: # Tuam Ouest
    $gc_temp = 12830;
    $mg_temp = 13142;
    $frigo_temp = 0;
    break;
    
    case 9: # Tuam Centre
    $gc_temp = 14518;
    $mg_temp = 15422;
    $frigo_temp = 0;
    break;
    
    case 10: # Tuam N-Est
    $gc_temp = 14644;
    $mg_temp = 15960;
    $frigo_temp = 0;
    break;
    
    case 11: # Tuam Est
    $gc_temp = 16211;
    $mg_temp = 17398;
    $frigo_temp = 0;
    break;
    
    case 12: # Gambier
    $gc_temp = 16772;
    $mg_temp = 18676;
    $frigo_temp = 0;
    break;

    default:
    $performcalc_temp = 0;
    break;
  }
  
  if ($performcalc_temp == 1)
  {
    $gcvolume_temp = 0;
    $mgvolume_temp = 0;
    $weight_temp = 0;
    # define MG/GC/frigo
    if (!isset($regroupA))
    {
      $query = 'select productid,regroupnumber,volume,weight,numberperunit from product,regulationtype where product.regulationtypeid=regulationtype.regulationtypeid';
      $query_prm = array();
      require('inc/doquery.php');
      for ($y_temp=1; $y_temp <= $num_results; $y_temp++)
      {
        $regroupA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['regroupnumber']; # 1,2 PPN 3 GC 4 MG 5,6,7,8 frigo 9,10 autre (1,2 MG for "non-magasin")
        $volumeA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['volume'];
        $weightA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['weight'];
        $npuA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['numberperunit'];
      }
    }
    for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
    {
      if ($productidA['productid' . $i_temp] > 0)
      {
        if ($unitorcartonA['unitorcarton' . $i_temp]) { $quantity_temp = $quantityA['quantity' . $i_temp] / $npuA[$productidA['productid' . $i_temp]]; }
        else { $quantity_temp = $quantityA['quantity' . $i_temp]; }
        # not separating GC from PPN at the moment
        if ($regroupA[$productidA['productid' . $i_temp]] == 1 || $regroupA[$productidA['productid' . $i_temp]] == 2)
        {
          if (($clientcategoryid >= 26 && $clientcategoryid <= 30) || $clientcategoryid == 23 || $clientcategoryid == 22)
          {
            # PPN, no freight
          }
          else
          {
            $gcvolume_temp += myround($quantity_temp * $volumeA[$productidA['productid' . $i_temp]],3);
          }
        }
        elseif ($regroupA[$productidA['productid' . $i_temp]] == 3)
        {
          $gcvolume_temp += myround($quantity_temp * $volumeA[$productidA['productid' . $i_temp]],3);
#echo myround($quantity_temp * $volumeA[$productidA['productid' . $i_temp]],3);
        }
        elseif ($regroupA[$productidA['productid' . $i_temp]] == 4)
        {
          $mgvolume_temp += myround($quantity_temp * $volumeA[$productidA['productid' . $i_temp]],3);
        }
        elseif ($regroupA[$productidA['productid' . $i_temp]] == 5 || $regroupA[$productidA['productid' . $i_temp]] == 6 || $regroupA[$productidA['productid' . $i_temp]] == 7 || $regroupA[$productidA['productid' . $i_temp]] == 8)
        {
          $weight_temp += myround($quantity_temp * $weightA[$productidA['productid' . $i_temp]],2);
        }
      }
    }
    $alg_value = ($gcvolume_temp * $gc_temp) + ($mgvolume_temp * $mg_temp) + ($weight_temp * $frigo_temp);
    if ($alg_value < $minprice_temp) { $alg_value = $minprice_temp; }
  }
  
  break;
  
  case 2: # insurance to French Polynesian Islands from Papeete pid4204
  
  $performcalc_temp = 1;
  $percent_temp = 0;
  switch ($island_freightzoneidA[$islandid])
  {
    case 6: # Australes
    if ($localvesselid == 6) # Tahiti Nui 6
    {
      $percent_temp = 0.0024;
    }
    else
    {
      $percent_temp = 0.01;
    }
    break;
    
    #case 7: # Marquises
    # 0.0065 GC MG
    # 0.0125 frigo
    #break;
    
    case 8: # Tuam
    case 9:
    case 10:
    case 11:
    case 12:
    $percent_temp = 0.0024;
    break;
    
    default:
    $performcalc_temp = 0;
    break;
  }
  
  if ($performcalc_temp == 1)
  {
    $alg_value = 0;
    
    if ($percent_temp != 0.0024)
    {
      #read freight calc pids, to exlude from total
      $query = 'select productid from calcpricing where algorithm=1';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results)
      {
        $pidA_temp = array($productidA['productid' . $i]);
        for ($i_temp = 0; $i_temp < $num_results; $i_temp++)
        {
          array_push($pidA_temp, $query_result[$i_temp]['productid']);
        }
      }
    }
    else { $pidA_temp = array(); }
    
    for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++) # current alogrithm limited to product lines ABOVE the product
    {
      if (!in_array($productidA['productid' . $i_temp], $pidA_temp))
      {/* 2020 10 12 from paper delivered from Wing Chong
        if (isset($discountA['discount' . $i_temp]))
        {
          $alg_value += (double) $discountA['discount' . $i_temp];
        }*/
        if (isset($lineprice[$i_temp]))
        {
          $alg_value += (double) $lineprice[$i_temp];
        }
      }
    }
    #echo $alg_value;echo 'here';
    $alg_value *= $percent_temp;
  }
  
  break;
  
  # freight FRIGO to French Polynesian Islands from Papeete (pid 4203 Wing Chong)
  case 3:
  
  # find destination island/region
  $performcalc_temp = 1;
  $minprice_temp = 609;
  switch ($island_freightzoneidA[$islandid])
  {
    case 2: # ISLV
    if ($islandid == 2)
    {
      # Moorea
      $performcalc_temp = 0;
      $volume_temp = 0;
      for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
      {
        $quantity_temp = $quantityA['quantity' . $i_temp] * $npuA[$query_result[$i_temp]['productid']];
        if ($unitorcartonA['unitorcarton' . $i]) { $quantity_temp = $quantityA['quantity' . $i_temp]; }
        $volume_temp += myround($volumeA[$productidA['productid' . $i_temp]],3);
      }
      $alg_value = (1180 * ($volume_temp * $quantity_temp) + ($volume_temp * $quantity_temp) * 100); # as specified 2014 10 14 by M-C from Wing Chong (this only for WC?)
    }
    else
    {
      # other ISLV
      $gc_temp = 0;
      $mg_temp = 0;
      $frigo_temp = 0.02394;
    }
    break;
    
    case 3: # Maupiti
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.04070;
    break;
    
    case 4: # Mopelia Scilly Bel. Tupai
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.05388;
    break;
    
    case 5: # Maiao
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.04312;
    break;
    
    case 6: # Australes
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.05709;
    if ($localvesselid == 6) # Tahiti Nui 6
    {
      $gc_temp = 0;
      $mg_temp = 0;
      $frigo_temp = 0.06565;
    }
    break;
    
    case 7: # Marquises
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.06012;
    break;
    
    case 8: # Tuam Ouest
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.04509;
    break;
    
    case 9: # Tuam Centre
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.05943;
    break;
    
    case 10: # Tuam N-Est
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.05883;
    break;
    
    case 11: # Tuam Est
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.05883;
    break;
    
    case 12: # Gambier
    $gc_temp = 0;
    $mg_temp = 0;
    $frigo_temp = 0.05971;
    break;

    default:
    $performcalc_temp = 0;
    break;
  }
  
  if ($performcalc_temp == 1)
  {
    $gcvolume_temp = 0;
    $mgvolume_temp = 0;
    $weight_temp = 0;
    # define MG/GC/frigo TODO optimize to only needed products
    if (!isset($regroupA))
    {
      $query = 'select productid,regroupnumber,volume,weight,numberperunit from product,regulationtype where product.regulationtypeid=regulationtype.regulationtypeid';
      $query_prm = array();
      require('inc/doquery.php');
      for ($y_temp=0; $y_temp < $num_results; $y_temp++)
      {
        $regroupA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['regroupnumber']; # 1,2 PPN 3 GC 4 MG 5,6,7,8 frigo 9,10 autre (1,2 MG for "non-magasin")
        $volumeA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['volume'];
        $weightA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['weight'];
        $npuA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['numberperunit'];
      }
    }
    for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
    {
      if ($productidA['productid' . $i_temp] > 0)
      {
        if ($unitorcartonA['unitorcarton' . $i_temp])
        { $quantity_temp = $quantityA['quantity' . $i_temp] / $npuA[$productidA['productid' . $i_temp]]; }
        else { $quantity_temp = $quantityA['quantity' . $i_temp]; }
        # not separating GC from PPN at the moment
        if ($regroupA[$productidA['productid' . $i_temp]] == 1 || $regroupA[$productidA['productid' . $i_temp]] == 2)
        {
          if (($clientcategoryid >= 26 && $clientcategoryid <= 30) || $clientcategoryid == 23 || $clientcategoryid == 22)
          {
            # PPN, no freight
          }
          else
          {
            $gcvolume_temp += myround($quantity_temp * $volumeA[$productidA['productid' . $i_temp]],3);
          }
        }
        elseif ($regroupA[$productidA['productid' . $i_temp]] == 3)
        {
          $gcvolume_temp += myround($quantity_temp * $volumeA[$productidA['productid' . $i_temp]],3);
        }
        elseif ($regroupA[$productidA['productid' . $i_temp]] == 4)
        {
          $mgvolume_temp += myround($quantity_temp * $volumeA[$productidA['productid' . $i_temp]],3);
        }
        elseif ($regroupA[$productidA['productid' . $i_temp]] == 7)
        {
          #see emails 2020 02 24/25
          #Pour le connaissement uniquement,
          #Ce produit est type. PPN
          #Car le frêt maritime est gratuit.
          if (($clientcategoryid >= 26 && $clientcategoryid <= 30) || $clientcategoryid == 23 || $clientcategoryid == 22)
          {
            # PPN, no freight for "magasin"
          }
          else
          {
            $weight_temp += myround($quantity_temp * $weightA[$productidA['productid' . $i_temp]],2);
          }
        }
        elseif ($regroupA[$productidA['productid' . $i_temp]] == 5
        || $regroupA[$productidA['productid' . $i_temp]] == 6
        || $regroupA[$productidA['productid' . $i_temp]] == 8)
        {
          if ($producttypeid[$i_temp] != 1)
          {
            $weight_temp += myround($quantity_temp * $weightA[$productidA['productid' . $i_temp]],2);
          }
          else
          {
            if (($clientcategoryid >= 26 && $clientcategoryid <= 30) || $clientcategoryid == 23 || $clientcategoryid == 22)
            {
              # PPN, no freight for "magasin"
            }
            else
            {
              $weight_temp += myround($quantity_temp * $weightA[$productidA['productid' . $i_temp]],2);
            }
          }
        }
      }
    }
    $alg_value = ($gcvolume_temp * $gc_temp) + ($mgvolume_temp * $mg_temp) + ($weight_temp * $frigo_temp); #echo $weight_temp.'*'.$frigo_temp.'=';
    if ($alg_value < $minprice_temp) { $alg_value = $minprice_temp; }
  }
  
  break;
  
  case 4:
  $quantity_temp = 0;
  if (!isset($regroupA))
  {
    $query = 'select productid,regroupnumber,volume,weight,numberperunit from product,regulationtype where product.regulationtypeid=regulationtype.regulationtypeid';
    $query_prm = array();
    require('inc/doquery.php');
    for ($y_temp=1; $y_temp <= $num_results; $y_temp++)
    {
      $regroupA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['regroupnumber']; # 1,2 PPN 3 GC 4 MG 5,6,7,8 frigo 9,10 autre (1,2 MG for "non-magasin")
      $volumeA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['volume'];
      $weightA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['weight'];
      $npuA[$query_result[$y_temp]['productid']] = $query_result[$y_temp]['numberperunit'];
    }
  }
  
  $pidA_temp = array();
  if (($clientcategoryid >= 26 && $clientcategoryid <= 30) || $clientcategoryid == 23 || $clientcategoryid == 22)
  {
    #$pidA_temp = array(3507,3530,4200,4516,3148,2837,4146,3033,4342,3472,3926);
    $pidA_temp = array(3530,4200,1216,1217,1218,1219,2912,3033,3462,3814,4458,3148,4146); # excluded products per email MTC 2015 01 27
  }
  
  for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
  {
    if ($productidA['productid' . $i_temp] > 0 && !in_array($productidA['productid' . $i_temp], $pidA_temp))
    {
      if ($unitorcartonA['unitorcarton' . $i_temp]) { $quantity_temp += $quantityA['quantity' . $i_temp] / $npuA[$productidA['productid' . $i_temp]]; }
      else { $quantity_temp += $quantityA['quantity' . $i_temp]; }
    }
  }
  echo 'Quantité= ' . $quantity_temp;
  break;
  
  case 5:
  #2% sur '.$productfamilyA[1]
  for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
  {
    if ($productfamilyidA[$i_temp] == 1)
    {
      $alg_value += $lineprice[$i_temp];
    }
  }
  $alg_value = d_multiply(0.02,$alg_value);
  break;
  
  case 6:
  #10% sur '.$productfamilyA[2]
  for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
  {
    if ($productfamilyidA[$i_temp] == 2)
    {
      $alg_value += $lineprice[$i_temp];
    }
  }
  $alg_value = d_multiply(0.1,$alg_value);
  break;
  
  case 7:
  #20% sur '.$productfamilyA[3]
  for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
  {
    if ($productfamilyidA[$i_temp] == 3)
    {
      $alg_value += $lineprice[$i_temp];
    }
  }
  $alg_value = d_multiply(0.2,$alg_value);
  break;
  
  case 8:
  #1.5% sur '.$productfamilyA[1]
  for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
  {
    if ($productfamilyidA[$i_temp] == 1)
    {
      $alg_value += $lineprice[$i_temp];
    }
  }
  $alg_value = d_multiply(0.015,$alg_value);
  break;
  
  case 9:
  #5% sur '.$productfamilyA[1]
  for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
  {
    if ($productfamilyidA[$i_temp] == 1)
    {
      $alg_value += $lineprice[$i_temp];
    }
  }
  $alg_value = d_multiply(0.05,$alg_value);
  break;
  
  case 10:
  #10% sur '.$productfamilyA[3]
  for ($i_temp=1; $i_temp <= $invoicelines; $i_temp++)
  {
    if ($productfamilyidA[$i_temp] == 3)
    {
      $alg_value += $lineprice[$i_temp];
    }
  }
  $alg_value = d_multiply(0.1,$alg_value);
  break;
  
}

$alg_value = myround($alg_value); # XPF

?>