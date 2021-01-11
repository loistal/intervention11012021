<?php

# TODO add d_output

# input: $i $row $num_rows $subtfield_count_descr
# don't forget to modify also showsubtotal 
$usesubunits = false;if($_SESSION['ds_useunits']){$usesubunits = true;}
$i++;
if (isset($showgrandtotal))
{
  echo '<tr class=trtablecolorsub>'; 
  
  $showline_temp = '';
  $iscolspan_temp = true;  
  $colspan_temp = 0; 
  
  for ($y=1;$y <= $fieldnum;$y++)
  {
    if ($y == 1)
    {
      $showline_temp .= '<td colspan=##cs## class=subtotal>';
      $showgrandtotal_temp = d_trad('grandtotal');
      $showgrandtotal_temp .= ' (' . $num_rows . ')';
      if (isset($showgrandtotal[$y]))
      {
        $showline_temp = mb_ereg_replace("##cs##", 1 , $showline_temp); 
        if(isset($percentagefield) && $y == $percentagefield)
        {
          //to avoid 0.0% and 100.0%
          if(myround($showgrandtotal[$y],1) <=0 ){$showgrandtotal_temp .= ': < 0.1%';}  
          elseif($showgrandtotal[$y] < 100){$showgrandtotal_temp .= ': ' . myfix($showgrandtotal[$y], 1) . '%';}
          else{$showgrandtotal_temp .= ': ' . myfix($showgrandtotal[$y], 0) . '%'; } 
        }
        elseif($usesubunits && ($y == $stockfield))
        {
          $showgrandtotal_temp .= ': ' . $showgrandtotal[$y];
        }
        else
        {
          #$showgrandtotal_temp .= ': ' . myfix($showgrandtotal[$y], 0);
          $showgrandtotal_temp .= ': ' . $showgrandtotal[$y];
        }
        $iscolspan_temp = false;
      }
      $showline_temp .= $showgrandtotal_temp;
      //optional: number of rows for grandtotal and label      
      if(isset($subtfield1_count_descr))
      {
        $showline_temp .= ':&nbsp;' . d_trad($subtfield1_count_descr,($num_rows));
      }
      $showline_temp .='</td>';
    }
    elseif (isset($showgrandtotal[$y]))
    {
      if(isset($percentagefield) && $y == $percentagefield)
      {
        //to avoid 0.0% and 100.0%
        if(myround($showgrandtotal[$y],1) <= 0){$showline_temp .= '<td class=subtotal align=right>< 0.1%';}        
        elseif($showgrandtotal[$y] < 100){$showline_temp .= '<td class=subtotal align=right> ' . myfix($showgrandtotal[$y], 1) . '%';}
        else{$showline_temp .= '<td class=subtotal align=right> ' . myfix($showgrandtotal[$y], 0) . '%';}         
      }
      elseif($usesubunits && (($y == $stockfield)))
      {
        $showline_temp .= '<td class=subtotal align=right> ' . $showgrandtotal[$y];
      }
      else
      {
        if (fmod((float)$showgrandtotal[$y], 1) == 0) # TODO use for subtotal too, and clean up this mess!
        {
          if (isset($npu_for_total[$y]) && $npu_for_total[$y] > 1)
          {
            $showline_temp .= '<td class=subtotal align=right>' . d_showquantity($showgrandtotal[$y], $npu_for_total[$y]);
          }
          else
          {
            $showline_temp .= '<td class=subtotal align=right>' . myfix($showgrandtotal[$y]);
          }
        }
        else { $showline_temp .= '<td class=subtotal align=right>' . $showgrandtotal[$y]; }
      }
      $showline_temp .= '</td>';
      //colspan only for title
      if ($iscolspan_temp && $colspan_temp >= 1)
      { 
        $showline_temp = mb_ereg_replace("##cs##", $colspan_temp , $showline_temp);
        $iscolspan_temp = false;
      }      
    }
    else if (!$iscolspan_temp)
    {
      $showline_temp .= '<td class=subtotal>&nbsp;</td>';    
    }
    $colspan_temp ++;
  }
  echo $showline_temp;
}

unset ($showline_temp, $colspan_temp,$iscolspan_temp,$showgrandtotal_temp);

?>