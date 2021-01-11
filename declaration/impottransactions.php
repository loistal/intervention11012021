<?php

$year = (int) $_POST['year'];
$startdate = d_builddate(1, 1, $year);
$stopdate = d_builddate(31, 12, $year);

$query = 'select * from companyinfo where companyinfoid=1';
$query_prm = array();
require('inc/doquery.php');
$numero_tahiti = $query_result[0]['idtahiti'];
$raison_sociale = $query_result[0]['companyname'];
$telephone = $query_result[0]['infophonenumber'];
$adresse = $query_result[0]['infoaddress1'].' '.$query_result[0]['infoaddress2'];
$postalcode = $query_result[0]['postalcode'];
$email = $query_result[0]['infoemail'];

?>
<!DOCTYPE html >
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
</head>

<body style="margin: 0;">

<div id="p1" style="overflow: hidden; position: relative; width: 909px; height: 1286px;">

<!-- Begin shared CSS values -->
<style class="shared-css" type="text/css" >
.t {
	-webkit-transform-origin: top left;
	-moz-transform-origin: top left;
	-o-transform-origin: top left;
	-ms-transform-origin: top left;
	-webkit-transform: scale(0.25);
	-moz-transform: scale(0.25);
	-o-transform: scale(0.25);
	-ms-transform: scale(0.25);
	z-index: 2;
	position: absolute;
	white-space: pre;
	overflow: visible;
}
</style>
<!-- End shared CSS values -->


<!-- Begin inline CSS -->
<style type="text/css" >

#t1_1{left:68px;top:1221px;word-spacing:-1.3px;}
#t2_1{left:206px;top:1235px;word-spacing:-1.5px;}
#t3_1{left:452px;top:1249px;letter-spacing:-0.4px;}
#t4_1{left:314px;top:191px;letter-spacing:-0.1px;word-spacing:-2px;}
#t5_1{left:269px;top:216px;word-spacing:-1.4px;}
#t6_1{left:251px;top:237px;letter-spacing:0.1px;word-spacing:-1.7px;}
#t7_1{left:241px;top:276px;word-spacing:-1.7px;}
#t8_1{left:288px;top:296px;word-spacing:-1.7px;}
#t9_1{left:366px;top:327px;word-spacing:-1.4px;}
#ta_1{left:475px;top:327px;letter-spacing:-0.1px;}
#tb_1{left:536px;top:327px;letter-spacing:-0.1px;}
#tc_1{left:120px;top:353px;letter-spacing:0.1px;word-spacing:-1.7px;}
#td_1{left:435px;top:353px;word-spacing:0.6px;}
#te_1{left:250px;top:374px;letter-spacing:0.1px;word-spacing:-1.2px;}
#tf_1{left:306px;top:407px;word-spacing:-1.5px;}
#tg_1{left:43px;top:469px;word-spacing:-1.3px;}
#th_1{left:43px;top:508px;letter-spacing:0.1px;word-spacing:-1.2px;}
#ti_1{left:43px;top:547px;word-spacing:-1.2px;}
#tj_1{left:43px;top:587px;letter-spacing:0.1px;word-spacing:-1.2px;}
#tk_1{left:468px;top:587px;letter-spacing:0.1px;word-spacing:-1.2px;}
#tl_1{left:43px;top:626px;word-spacing:-1.2px;}
#tm_1{left:43px;top:666px;letter-spacing:0.1px;word-spacing:-1.3px;}
#tn_1{left:468px;top:656px;letter-spacing:0.1px;word-spacing:-1.2px;}
#to_1{left:43px;top:693px;letter-spacing:-0.1px;word-spacing:-0.1px;}
#tp_1{left:43px;top:707px;letter-spacing:-0.1px;word-spacing:-0.1px;}
#tq_1{left:43px;top:720px;}
#tr_1{left:57px;top:720px;letter-spacing:-0.1px;word-spacing:0.1px;}
#ts_1{left:43px;top:735px;}
#tt_1{left:57px;top:735px;letter-spacing:-0.1px;word-spacing:0.2px;}
#tu_1{left:60px;top:748px;letter-spacing:-0.1px;word-spacing:0.1px;}
#tv_1{left:44px;top:763px;letter-spacing:0.2px;}
#tw_1{left:43px;top:777px;}
#tx_1{left:57px;top:777px;letter-spacing:-0.1px;word-spacing:0.3px;}
#ty_1{left:43px;top:802px;word-spacing:-0.9px;}
#tz_1{left:49px;top:855px;word-spacing:-1.4px;}
#t10_1{left:49px;top:873px;letter-spacing:0.1px;word-spacing:-1.4px;}
#t11_1{left:49px;top:891px;letter-spacing:0.1px;word-spacing:-1.5px;}
#t12_1{left:805px;top:873px;letter-spacing:0.3px;}
#t13_1{left:840px;top:873px;letter-spacing:0.3px;}
#t14_1{left:97px;top:941px;}
#t15_1{left:273px;top:941px;letter-spacing:0.3px;word-spacing:-1.2px;}
#t16_1{left:476px;top:941px;letter-spacing:0.1px;}
#t17_1{left:639px;top:941px;letter-spacing:0.1px;}
#t18_1{left:61px;top:986px;letter-spacing:0.1px;word-spacing:-1.3px;}
#t19_1{left:47px;top:1004px;letter-spacing:0.1px;word-spacing:-1.3px;}
#t1a_1{left:362px;top:1021px;letter-spacing:0.1px;word-spacing:-1.2px;}
#t1b_1{left:80px;top:1048px;letter-spacing:0.1px;word-spacing:-1.4px;}
#t1c_1{left:313px;top:1081px;letter-spacing:0.1px;word-spacing:-1.2px;}
#t1d_1{left:346px;top:1099px;letter-spacing:0.1px;word-spacing:-1.3px;}
#t1e_1{left:357px;top:1116px;letter-spacing:0.1px;word-spacing:-1.2px;}
#t1f_1{left:314px;top:1134px;letter-spacing:0.1px;word-spacing:-1.3px;}
#t1g_1{left:339px;top:1151px;letter-spacing:0.1px;word-spacing:-1.4px;}
#t1h_1{left:338px;top:1178px;letter-spacing:0.1px;word-spacing:-1.2px;}
#t1i_1{left:214px;top:1196px;letter-spacing:0.1px;word-spacing:-1.2px;}
#t1j_1{left:606px;top:38px;letter-spacing:-0.1px;word-spacing:0.6px;}
#t1k_1{left:34px;top:1216px;word-spacing:-1.7px;}

.s1_1{
	FONT-SIZE: 49.1px;
	FONT-FAMILY: ArialNarrow_h;
	color: rgb(35,31,32);
}

.s2_1{
	FONT-SIZE: 49.1px;
	FONT-FAMILY: TimesNewRomanPSMT_k;
	color: rgb(35,31,32);
}

.s3_1{
	FONT-SIZE: 85.8px;
	FONT-FAMILY: ArialNarrow-Bold_o;
	color: rgb(35,31,32);
}

.s4_1{
	FONT-SIZE: 73.3px;
	FONT-FAMILY: ArialNarrow_h;
	color: rgb(35,31,32);
}

.s5_1{
	FONT-SIZE: 55px;
	FONT-FAMILY: ArialNarrow-Italic_r;
	color: rgb(35,31,32);
}

.s6_1{
	FONT-SIZE: 73.3px;
	FONT-FAMILY: ArialNarrow-Bold_o;
	color: rgb(35,31,32);
}

.s7_1{
	FONT-SIZE: 60.9px;
	FONT-FAMILY: ArialNarrow-BoldItalic_u;
	color: rgb(35,31,32);
}

.s8_1{
	FONT-SIZE: 60.9px;
	FONT-FAMILY: ArialNarrow_h;
	color: rgb(35,31,32);
}

.s9_1{
	FONT-SIZE: 49.1px;
	FONT-FAMILY: TimesNewRomanPS-BoldItalicMT_x;
	color: rgb(35,31,32);
}

.s10_1{
	FONT-SIZE: 49.1px;
	FONT-FAMILY: Wingdings__;
	color: rgb(35,31,32);
}

.s11_1{
	FONT-SIZE: 55px;
	FONT-FAMILY: ArialNarrow_h;
	color: rgb(35,31,32);
}

.s12_1{
	FONT-SIZE: 60.9px;
	FONT-FAMILY: ArialNarrow-Bold_o;
	color: rgb(35,31,32);
}

.s13_1{
	FONT-SIZE: 36.7px;
	FONT-FAMILY: TimesNewRomanPSMT_k;
	color: rgb(35,31,32);
}

.s14_1{
	FONT-SIZE: 30.8px;
	FONT-FAMILY: Tahoma_12;
	color: rgb(35,31,32);
}

.t.m1_1{
	-webkit-transform: matrix(0,-1,1,0,0, 0) scale(0.25);
	-ms-transform: matrix(0,-1,1,0,0, 0) scale(0.25);
	-moz-transform: matrix(0,-1,1,0,0, 0) scale(0.25);
	-o-transform: matrix(0,-1,1,0,0, 0) scale(0.25);
}

.normal{
  FONT-FAMILY: Arial;
}

</style>
<!-- End inline CSS -->

<!-- Begin embedded font definitions -->
<style id="fonts1" type="text/css" >

@font-face {
	font-family: ArialNarrow-Italic_r;
	src: url("declaration/fonts/ArialNarrow-Italic_r.woff") format("woff");
}

@font-face {
	font-family: TimesNewRomanPS-BoldItalicMT_x;
	src: url("declaration/fonts/TimesNewRomanPS-BoldItalicMT_x.woff") format("woff");
}

@font-face {
	font-family: TimesNewRomanPSMT_k;
	src: url("declaration/fonts/TimesNewRomanPSMT_k.woff") format("woff");
}

@font-face {
	font-family: ArialNarrow-BoldItalic_u;
	src: url("declaration/fonts/ArialNarrow-BoldItalic_u.woff") format("woff");
}

@font-face {
	font-family: Wingdings__;
	src: url("declaration/fonts/Wingdings__.woff") format("woff");
}

@font-face {
	font-family: ArialNarrow-Bold_o;
	src: url("declaration/fonts/ArialNarrow-Bold_o.woff") format("woff");
}

@font-face {
	font-family: ArialNarrow_h;
	src: url("declaration/fonts/ArialNarrow_h.woff") format("woff");
}

@font-face {
	font-family: Tahoma_12;
	src: url("declaration/fonts/Tahoma_12.woff") format("woff");
}

</style>
<!-- End embedded font definitions -->

<!-- Begin page background -->
<div id="pg1Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg1" style="-webkit-user-select: none;"><object width="909" height="1286" data="declaration/1/1.svg" type="image/svg+xml" id="pdf1" style="width:909px; height:1286px; background-color:white; -moz-transform:scale(1); z-index: 0;"></object></div>
<!-- End page background -->


<!-- Begin text definitions (Positioned/styled in CSS) -->
<div id="t1_1" class="t s1_1">° ±²³ ´µ³¶·³µ¸µ·¹³ ´²³ º»¸µ¼½²³ ¾¿ ²¸ ÀÁ ´² ½º ½·µ ¹Â ÃÄÅÆÃ ´Ç È Éº¹Êµ²» Æ¿ÃÄ »²½º¸µÊ² Ë ½Ìµ¹Í·»Îº¸µ!Ç²" ºÇ# Íµ¼$µ²»³ ²¸ ºÇ# ½µ%²»¸&amp;³" Î·´µÍµ&amp;² ¶º» ½º ½·µ ¹Â ’ÁÁÀÅÄÁÆ ´Ç È º·(¸ ’ÁÁÀ"</div>
<div id="t2_1" class="t s1_1">)º»º¹¸µ³³²¹¸ ½²³ ´»·µ¸³ ´²³ ¶²»³·¹¹²³ ¶$*³µ!Ç²³ Ë ½Ì&amp;)º»´ ´²³ ¸»ºµ¸²Î²¹¸³ ´²³ ´·¹¹&amp;²³ Ë ¼º»º¼¸+»² ¶²»³·¹¹²½, -</div>
<div id="t3_1" class="t s2_1">1 </div>
<div id="t4_1" class="t s3_1">°±²³´ µ¶· ¸¹µ ´·º»µº¼´°½»µ</div>
<div id="t5_1" class="t s4_1">./ 012/3456/412 7. 81±47934/. /.334/1349±.</div>
<div id="t6_1" class="t s4_1">863 ±.8 :31;.884128 ./ 90/4&lt;4/.8 212 89±934..8</div>
<div id="t7_1" class="t s4_1">7&amp;¼½º»º¸µ·¹ ´Ç ¼$µÍÍ»² ´ÌºÍÍºµ»²³ ·Ç ´²³ »²¼²¸¸²³ %»Ç¸²³ $·»³ /&lt;9</div>
<div id="t8_1" class="t s4_1">»&amp;º½µ³&amp;³ ºÇ ¼·Ç»³ ´² ½Ìº¹¹&amp;² = ´² ½Ì²#²»¼µ¼²</div>
<div id="t9_1" class="t s5_1">°±²³´µ¶· ¶¸</div>
<div id="ta_1" class="t s5_1">¹¸</div>
<div id="tb_1" class="t s5_1">º»</div>
<div id="tc_1" class="t s6_1">º ¾¿ÀÁÂÃÄ ÁÅ Æ ÀÁÂÇÃÄ ÈÅ ÀÉÅÂ ÇÈÄ¾ ÉÃ</div>
<div id="td_1" class="t s6_1">ÁÅ ¾ÈÊÂ ÉÃÂ ÇÄÁËÂ ÌÁËÂ ¾Ã ÉÈ ÍÉÎÇÅÄÃ ¾Ã É!Ã"ÃÄÍËÍÃ#</div>
<div id="te_1" class="t s7_1">°±² ³´µ ¶· ¶¸µµ¹º»¼¸¹² ¹» ¶· ³·µµ´¼¸¹²½ ³· ¶¾º´¸ ·µ¼ ¿¾¶»¸¼ À ÁÂ Ã¹»¿µÄ</div>
<div id="tf_1" class="t s6_1">²ÈÄÇËÃ Æ ÄÃÊÂÃË$ÊÃÄ ÀÈÄ ÇÁÅÂ ÉÃÂ ÈÂÂÅ%ÃÇÇËÂ</div>
<div id="tg_1" class="t s8_1">2Â /9&gt;4/4 ?<?php echo ' <span class="normal">',d_output($numero_tahiti),'</span>'; ?></div>
<div id="th_1" class="t s8_1">21@" :»&amp;¹·Î ?</div>
<div id="ti_1" class="t s8_1">3ºµ³·¹ ³·¼µº½² ?<?php echo ' <span class="normal">',d_output($raison_sociale),'</span>'; ?></div>
<div id="tj_1" class="t s8_1">2&amp;A²B ½² ?</div>
<div id="tk_1" class="t s8_1">9´»²³³² )&amp;·)»º¶$µ!Ç² ?<?php echo ' <span class="normal">',d_output($adresse),'</span>'; ?></div>
<div id="tl_1" class="t s8_1">5·C¸² ¶·³¸º½² ?<?php echo ' <span class="normal">',d_output($postalcode),'</span>'; ?></div>
<div id="tm_1" class="t s8_1">/&amp;½&amp;¶$·¹² ?<?php echo ' <span class="normal">',d_output($telephone),'</span>'; ?></div>
<div id="tn_1" class="t s8_1">9´»²³³² &amp;½²¼¸»·¹µ!Ç² ?<?php echo ' <span class="normal">',d_output($email),'</span>'; ?></div>
<div id="to_1" class="t s9_1">L’adresse e-mail indiquée dans ce formulaire est susceptible d’être utilisée pour l’envoi de lettres d’information, newsletters et actualités de la Direction des impôts </div>
<div id="tp_1" class="t s9_1">et des contributions publiques. Vous avez la possibilité de vous y opposer en cochant les cases indiquées ci-après : </div>
<div id="tq_1" class="t s10_1">°</div>
<div id="tr_1" class="t s2_1">Je ne souhaite pas que mon adresse e-mail soit utilisée aux fins d’envoi des documents d’information ci-dessus. </div>
<div id="ts_1" class="t s10_1">°</div>
<div id="tt_1" class="t s2_1">Je ne souhaite pas recevoir sur cette adresse e-mail des messages de rappel d’échéances déclaratives, de paiement et de relance amiable. </div>
<div id="tu_1" class="t s2_1">Néanmoins, je souhaite recevoir ces documents sur l’adresse e-mail, indiquée ci-après : </div>
<div id="tv_1" class="t s2_1">....................................................................................................................................................................................................................................................................</div>
<div id="tw_1" class="t s10_1">°</div>
<div id="tx_1" class="t s2_1">Je ne souhaite pas recevoir de SMS sur le numéro de téléphone portable indiqué dans ce formulaire. </div>
<div id="ty_1" class="t s8_1">9¼¸µÊµ¸&amp;³ ²#²»¼&amp;²³ ?</div>
<div id="tz_1" class="t s11_1">0·¼$²D ½º ¼º³² ¼·»»²³¶·¹´º¹¸ Ë Ê·¸»² ³µ¸Çº¸µ·¹ ?</div>
<div id="t10_1" class="t s12_1">&amp;ÁÅÂ ’¿ÊÃ(ËÍËÃ) ¾!ÅÊ ÍÄ¿¾ËÇ ¾!ËÌÀÎÇ ÀÁÅÄ ËÊ*ÃÂÇËÂÂÃÌÃÊÇ +¼°°,- ¾!ÅÊÃ Ä¿¾ÅÍÇËÁÊ ¾!ËÌÀÎÇ ÀÁÅÄ ËÊ*ÃÂÇËÂÂÃÌÃÊÇ +·°°, ÃÇ . ÁÅ ¾!ÅÊÃ</div>
<div id="t11_1" class="t s12_1">ËÊÍËÇÈÇËÁÊ (ËÂÍÈÉÃ ÀÁÅÄ É!ÃÌÀÉÁË ¾ÅÄÈ’ÉÃ +°/¹0,# ¸Ã ÍÈÂ ¿Í1¿ÈÊÇ- *ÃÅËÉÉÃ) %ÁËÊ¾ÄÃ ÉÃÂ %ÅÂÇË(ËÍÈÇË(Â#</div>
<div id="t12_1" class="t s12_1">ÁÅË</div>
<div id="t13_1" class="t s12_1">ÊÁÊ</div>
<div id="t14_1" class="t s12_1">º</div>
<div id="t15_1" class="t s12_1">- ÉÃ</div>
<div id="t16_1" class="t s12_1">234</div>
<div id="t17_1" class="t s12_1">µË$ÊÈÇÅÄÃ</div>
<div id="t18_1" class="t s12_1">¸ÃÂ ÌÁÊÇÈÊÇÂ Æ ¾¿ÍÉÈÄÃÄ ÂÁÊÇ ÍÃÅ" ¾ÃÂ ÄÃÍÃÇÇÃÂ ’ÄÅÇÃÂ 1ÁÄÂ ÇÈ"ÃÂ ÃÇ ÊÁÊ ÉÃ ’¿Ê¿(ËÍÃ# 5ÅÃÉ 6ÅÃ ÂÁËÇ ÉÃ ÌÁÊÇÈÊÇ ¾ÃÂ ÄÃÍÃÇÇÃÂ- Ì7ÌÃ ÂË</div>
<div id="t19_1" class="t s12_1">ÈÅÍÅÊÃ ÄÃÍÃÇÇÃ Ê!È ¿Ç¿ ÃÊÄÃ$ËÂÇÄ¿Ã +Í1Ë((ÄÃ ¾!È((ÈËÄÃÂ ¿$ÈÉ Æ )¿ÄÁ,- ÅÊÃ ¾¿ÍÉÈÄÈÇËÁÊ ¾ÁËÇ 7ÇÄÃ È¾ÄÃÂÂ¿Ã ÁÅ ¾¿ÀÁÂ¿Ã Æ ÉÈ 0ËÄÃÍÇËÁÊ ¾ÃÂ ËÌÀÎÇÂ</div>
<div id="t1a_1" class="t s12_1">ÃÇ ¾ÃÂ ÍÁÊÇÄË’ÅÇËÁÊÂ ÀÅ’ÉË6ÅÃÂ#</div>
<div id="t1b_1" class="t s12_1">´ÁÅÇÃ ¾¿ÍÉÈÄÈÇËÁÊ 1ÁÄÂ ¾¿ÉÈË 8 ÉÃ ÍÈÍ1ÃÇ ¾Ã ÉÈ ÀÁÂÇÃ (ÈËÂÈÊÇ (ÁË 8 ¾ÁÊÊÃ ÉËÃÅ Æ É!ÈÀÀÉËÍÈÇËÁÊ ¾!ÅÊÃ À¿ÊÈÉËÇ¿ ¾Ã 439 ¾Ã É!ËÌÀÎÇ ¾:#</div>
<div id="t1c_1" class="t s8_1">7µ»²¼¸µ·¹ ´²³ µÎ¶E¸³ ²¸ ´²³ ¼·¹¸»µ%Ç¸µ·¹³ ¶Ç%½µ!Ç²³</div>
<div id="t1d_1" class="t s8_1">ÆÆ" »Ç² ´Ç 0·ÎÎº¹´º¹¸ 7.8/3.@96</div>
<div id="t1e_1" class="t s8_1">5·C¸² :·³¸º½² ÄÁ F ¿Ä,ÃÆ¾ :º¶²²¸²</div>
<div id="t1f_1" class="t s8_1">/&amp;½&amp;¶$·¹² ? ÀÁ ÀÈ Æ¾ Æ¾ F /&amp;½&amp;¼·¶µ² ? ÀÁ ÀÈ Æ¾ ÁÆ</div>
<div id="t1g_1" class="t s8_1">0·Ç»»µ²½ ? ´µ»²¼¸µ·¹´²³µÎ¶·¸³G´µ¼¶,)·Ê,¶Í</div>
<div id="t1h_1" class="t s8_1">8µ¸² 4¹¸²»¹²¸ ? HHH,µÎ¶·¸Å¶·½*¹²³µ²,)·Ê,¶Í</div>
<div id="t1i_1" class="t s8_1">1ÇÊ²»¸ ºÇ ¶Ç%½µ¼ ´Ç ½Ç¹´µ ºÇ É²Ç´µ ´² Ã$¾Á $ Ë ÆÀ$¾Á ²¸ ½² Ê²¹´»²´µ ´² Ã$¾Á $ Ë Æ¾$¾Á</div>
<div id="t1j_1" class="t s13_1">Cadre réservé à la Direction des impôts et des contributions publiques</div>
<div id="t1k_1" class="t m1_1 s14_1">° ± ² ³ ´ ± µ ´ ¶ · ´ · ¸ ¸ ¹ ´ º » ´ » ¼</div>

<!-- End text definitions -->


</div>
</body>
</html>
