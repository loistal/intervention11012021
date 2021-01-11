<?php
set_time_limit(600);
$uA[1] = 'update color_theme set themename="2015" where themeid=2';
$uA[2] = 'update color_theme set themename="Classique" where themeid=1';
$uA[3] = 'update color_theme set themename="Audrey" where themeid=3';
$uA[4] = 'update color_theme set themename="2017" where themename="Couleur CAGEST"';
$uA[5] = 'INSERT INTO layout (layoutid, layoutname) VALUES (4, "2017")';
$uA[6] = 'UPDATE layout SET layoutname="2015" WHERE layoutid=2';
$uA[7] = 'delete from color_theme where themename="2017"';
$uA[8] = "
INSERT INTO `color_theme` (`themename`, `bgcolor`, `fgcolor`, `linkcolor`, `menucolor`, `alertcolor`, `infocolor`, `formcolor`, `tablecolor`, `inputcolor`, `menubordercolor`, `menufontcolor`, `tablecolor1`, `tablecolor2`, `hovercolor`, `usehovercolor`, `nbtablecolors`, `usetablecolorsub`, `tablecolorsub`, `userid`) VALUES
('2017',	'ffffff',	'000000',	'232f3e',	'232f3e',	'ff0000',	'e8893c',	'e0ecff',	'9ac1df',	'ffffff',	'3b495c',	'ffffff',	'd5d7df',	'9ac1df',	'c7f060',	1,	2,	1,	'4d69d6',	0);
";
$uA[9] = "
ALTER TABLE `usertable`
CHANGE `linkcolor` `linkcolor` varchar(100) COLLATE 'utf8_unicode_ci' NULL DEFAULT '232f3e' AFTER `fgcolor`,
CHANGE `menucolor` `menucolor` varchar(100) COLLATE 'utf8_unicode_ci' NULL DEFAULT '232f3e' AFTER `linkcolor`,
CHANGE `infocolor` `infocolor` varchar(100) COLLATE 'utf8_unicode_ci' NULL DEFAULT 'e8893c' AFTER `alertcolor`,
CHANGE `formcolor` `formcolor` varchar(100) COLLATE 'utf8_unicode_ci' NULL DEFAULT 'e0ecff' AFTER `infocolor`,
CHANGE `tablecolor` `tablecolor` varchar(100) COLLATE 'utf8_unicode_ci' NULL DEFAULT '9ac1df' AFTER `definvoicetagid`,
CHANGE `menubordercolor` `menubordercolor` varchar(6) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '3b495c' AFTER `menustyle`,
CHANGE `tablecolor1` `tablecolor1` varchar(6) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'd5d7df',
CHANGE `tablecolor2` `tablecolor2` varchar(6) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '9ac1df' AFTER `tablecolor1`,
CHANGE `nbtablecolors` `nbtablecolors` tinyint(4) NOT NULL DEFAULT '2' AFTER `tablecolor2`,
CHANGE `usetablecolorsub` `usetablecolorsub` tinyint(4) NOT NULL DEFAULT '1' AFTER `nbtablecolors`,
CHANGE `tablecolorsub` `tablecolorsub` varchar(6) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '4d69d6' AFTER `usetablecolorsub`,
CHANGE `hovercolor` `hovercolor` varchar(6) COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT 'c7f060' AFTER `accountinglines`;
";
$uA[10] = "
ALTER TABLE `usertable`
CHANGE `menustyle` `menustyle` tinyint(3) unsigned NOT NULL DEFAULT '4' AFTER `deliveryaccessreturns`,
CHANGE `colorthemeid` `colorthemeid` smallint(6) NOT NULL DEFAULT '4' AFTER `usehovercolor`;
";
$uA[11] = 'alter table usertable add column warehouseaccesstype tinyint unsigned not null default 0';
$uA[12] = 'insert into vatindex (vatindexid,vatindexname) values (202,"02 [TVA sur encaissements 5%] Prestations de services TTC ")';
$uA[13] = '
alter table categorypricing add column startdate date default null;
alter table categorypricing add column stopdate date default null;
alter table categorypricing2 add column startdate date default null;
alter table categorypricing2 add column stopdate date default null;
alter table categorypricing3 add column startdate date default null;
alter table categorypricing3 add column stopdate date default null;
';
$uA[14] = 'delete from vatindex where vatindexid=105 or vatindexid=106 or vatindexid=107';
$uA[15] = 'insert into vatindex (vatindexid,vatindexname) values (105,"05 Taux réduit (5%) Préstations Service")';
$uA[16] = '
create table if not exists employee_month (
  employee_monthid int unsigned not null primary key auto_increment,
  month tinyint unsigned not null default 0,
  year smallint unsigned not null default 0,
  employeeid smallint unsigned not null default 0,
  minutes_worked smallint unsigned not null default 0,
  minutes_to_pay smallint unsigned not null default 0
);
create table if not exists employee_month_minutes (
  employee_month_minutesid int unsigned not null primary key auto_increment,
  employee_monthid int unsigned not null default 0,
  type tinyint unsigned not null default 0, /* 0 premium, 1 overtime */
  rate tinyint unsigned not null default 0,
  minutes smallint unsigned not null default 0
);
';
$uA[17] = '
alter table employee_month add column nonworked1 smallint unsigned not null default 0;
alter table employee_month add column nonworked2 smallint unsigned not null default 0;
alter table employee_month add column nonworked3 smallint unsigned not null default 0;
alter table employee_month add column nonworked4 smallint unsigned not null default 0;
alter table employee_month add column nonworked5 smallint unsigned not null default 0;
';
$uA[18] = '
create table if not exists employee_month_seq (
  employee_month_seq int unsigned not null primary key auto_increment,
  employeeid smallint unsigned not null default 0,
  sequencedate date default null,
  begin smallint unsigned not null default 0,
  end smallint unsigned not null default 0
);
';
$uA[19] = '
alter table globalvariables add column stockperuser tinyint unsigned not null default 0;
alter table usertable add column stockperthisuser tinyint unsigned not null default 0;
create table if not exists endofyearstock_user (
  endofyearstock_userid int unsigned not null primary key auto_increment,
  userid smallint unsigned not null default 0,
  productid int unsigned not null default 0,
  year smallint unsigned not null default 0,
  stock int unsigned not null default 0
);
create table if not exists modifiedstock_user (
  modifiedstockid int unsigned not null primary key auto_increment,
  productid int unsigned not null default 0,
  netchange int not null default 0, /* NOT unsigned */
  netvalue decimal(19,4) not null default 0, /* NOT unsigned */
  changedate date default null,
  changetime time default null,
  userid smallint unsigned not null default 0,
  foruserid smallint unsigned not null default 0,
  modifiedstockreasonid smallint unsigned not null default 0,
  modifiedstockcomment varchar(100) not null default ""
);
';
$uA[20] = 'alter table globalvariables add column globalise_vat tinyint unsigned not null default 0;';
$uA[21] = 'alter table shipment add column transitcost decimal(19,4) unsigned not null default 0;';
$uA[22] = 'alter table productaction add column competitorid smallint unsigned not null default 0;';
$uA[23] = '
alter table globalterms add column term_productactionfield1 varchar(100) not null default "";
alter table globalterms add column term_productactiontag varchar(100) not null default "";
create table if not exists productactiontag (
  productactiontagid smallint unsigned not null primary key auto_increment,
  productactiontagname varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
alter table productaction add column productactiontagid smallint unsigned not null default 0;
';
$uA[24] = '
alter table badge_employeemonth add column meal_allowance smallint unsigned not null default 0;
alter table employee_month add column meal_allowance smallint unsigned not null default 0;
';
$uA[25] = '
create table if not exists globalvariables_hr (
  primaryunique tinyint unsigned not null primary key auto_increment,
  employeenamedisplay tinyint unsigned not null default 0
);
insert into globalvariables_hr (primaryunique) values (1);
';
$uA[26] = '
alter table productaction add column imageid int unsigned not null default 0;
alter table clientaction add column imageid int unsigned not null default 0;
';
$uA[27] = '
UPDATE socialsecuritysector SET socialsecuritysectorname="Aquaculture - Agriculture" WHERE socialsecuritysectorid=2;
';
$uA[28] = '
ALTER TABLE `payslip`
CHANGE `vacationdays` `vacationdays` decimal(5,2) unsigned NOT NULL DEFAULT 0 AFTER `net_salary`,
CHANGE `vacationdays_added` `vacationdays_added` decimal(5,2) unsigned NOT NULL DEFAULT 0 AFTER `vacationdays`,
CHANGE `vacationdays_used` `vacationdays_used` decimal(5,2) unsigned NOT NULL DEFAULT 0 AFTER `calc_salary`;
';
$uA[29] = '
alter table deposit add column value decimal(19,4) unsigned not null default 0;
';
$uA[30] = '
alter table client add column clientfirstname varchar(100) not null default "";
alter table client add column town_name varchar(100) not null default "";
alter table client add column client_customdate1 date default null;
alter table client add column client_customdate2 date default null;
alter table client add column client_customdate3 date default null;
alter table client add column clientfield2 varchar(100) not null default "";
alter table client add column clientfield3 varchar(100) not null default "";
alter table client add column clientfield4 varchar(100) not null default "";
alter table client add column clientfield5 varchar(100) not null default "";
alter table client add column clientfield6 varchar(100) not null default "";
';
$uA[31] = '
alter table globalterms add column term_clientfield2 varchar(100) not null default "";
alter table globalterms add column term_clientfield3 varchar(100) not null default "";
alter table globalterms add column term_clientfield4 varchar(100) not null default "";
alter table globalterms add column term_clientfield5 varchar(100) not null default "";
alter table globalterms add column term_clientfield6 varchar(100) not null default "";
alter table globalterms add column term_client_customdate1 varchar(100) not null default "";
alter table globalterms add column term_client_customdate2 varchar(100) not null default "";
alter table globalterms add column term_client_customdate3 varchar(100) not null default "";
';
$uA[32] = '
alter table shipment add column total_invoiced decimal(19,4) unsigned not null default 0;
alter table shipment add column fenix_transmodeid tinyint unsigned not null default 0;
create table if not exists fenix_transmode (
  fenix_transmodeid tinyint unsigned not null primary key auto_increment,
  transmodename varchar(30) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into fenix_transmode (fenix_transmodeid,transmodename) values (1,"Transport maritime");
insert into fenix_transmode (fenix_transmodeid,transmodename) values (2,"Transport aérien");
insert into fenix_transmode (fenix_transmodeid,transmodename) values (3,"Transport postal");
insert into fenix_transmode (fenix_transmodeid,transmodename) values (4,"Voyageur");
insert into fenix_transmode (fenix_transmodeid,transmodename) values (5,"Par ses propres moyens");
insert into fenix_transmode (fenix_transmodeid,transmodename) values (6,"Sans titre de transport");
insert into fenix_transmode (fenix_transmodeid,transmodename) values (7,"Transport en fret express");
';
$uA[33] = '
alter table country add column fenixcode varchar(3) not null default "";
alter table client add column dossier tinyint unsigned not null default 0;
';
$uA[34] = '
create table if not exists fenix_req_procedure (
  fenix_req_procedureid tinyint unsigned not null primary key auto_increment,
  code varchar(2) not null default "",
  description varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into fenix_req_procedure (code,description) values ("40","40 MAC (MISE A LA CONSOMMATION DIRECTE)");
insert into fenix_req_procedure (code,description) values ("10","10 EXPORTATION DEFINITIVE");
insert into fenix_req_procedure (code,description) values ("23","23 EXPORTATION TEMPORAIRE SIMPLE EN VUE D’UN RETOUR EN L’ETAT");
insert into fenix_req_procedure (code,description) values ("21","21 EXPORTATION TEMPORAIRE DANS LE CADRE DU PERFECTIONNEMENT PASSIF");
insert into fenix_req_procedure (code,description) values ("51","51 PLACEMENT SOUS LE REGIME DU PERFECTIONNEMENT ACTIF");
insert into fenix_req_procedure (code,description) values ("53","53 PLACEMENT SOUS LE REGIME DE L’ADMISSION TEMPORAIRE");
insert into fenix_req_procedure (code,description) values ("71","71 ENTREE EN ENTREPOT DOUANIER");
insert into fenix_req_procedure (code,description) values ("75","75 ENTREE EN ENTREPOT INDUSTRIEL");
insert into fenix_req_procedure (code,description) values ("76","76 ENTREE EN ENTREPOT D’EXPORTATION");
insert into fenix_req_procedure (code,description) values ("80","80 MAC DE PRODUITS DU CRU");
insert into fenix_req_procedure (code,description) values ("90","90 MAC SIMPLIFIEE");
insert into fenix_req_procedure (code,description) values ("TR","TR TRANSBORDEMENT");
insert into fenix_req_procedure (code,description) values ("T1","T1 TRANSIT");
insert into fenix_req_procedure (code,description) values ("31","31 RE-EXPORTATION");
insert into fenix_req_procedure (code,description) values ("48","48 MAC SIMILTANEE DE PRODUITS DE REMPLACEMENT AVANT EXPORTATION TEMPORAIRE DES MARCHANDISES");
insert into fenix_req_procedure (code,description) values ("11","11 EXPORTATION ANTICIPEE");
create table if not exists fenix_prev_procedure (
  fenix_prev_procedureid tinyint unsigned not null primary key auto_increment,
  code varchar(2) not null default "",
  description varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into fenix_prev_procedure (code,description) values ("23","23 EXPORTATION TEMPORAIRE SIMPLE");
insert into fenix_prev_procedure (code,description) values ("21","21 PERFECTIONNEMENT PASSIF");
insert into fenix_prev_procedure (code,description) values ("51","51 PERFECTIONNEMENT ACTIF");
insert into fenix_prev_procedure (code,description) values ("53","53 ADMISSION TEMPORAIRE");
insert into fenix_prev_procedure (code,description) values ("71","71 ENTREE EN ENTREPOT DE STOCKAGE");
insert into fenix_prev_procedure (code,description) values ("75","75 ENTREE EN ENTREPOT INDUSTRIEL");
insert into fenix_prev_procedure (code,description) values ("76","76 ENTREE EN ENTREPOT D’EXPORTATION");
insert into fenix_prev_procedure (code,description) values ("48","48 MAC SIMILTANEE DE PRODUITS DE REMPLACEMENT AVANT EXPORTATION TEMPORAIRE DES MARCHANDISES");
insert into fenix_prev_procedure (code,description) values ("11","11 EXPORTATION ANTICIPEE");
insert into fenix_prev_procedure (code,description) values ("00","00 AUCUN REGIME PRECEDENT");
alter table purchase add column fenix_req_procedureid tinyint unsigned not null default 0;
alter table purchase add column fenix_prev_procedureid tinyint unsigned not null default 0;
';
$uA[35] = '
drop table incoterm;
update shipment set incotermid=2 where incotermid=5;
alter table shipment add column noimportvalue tinyint unsigned not null default 0;
update shipment set noimportvalue=1 where incotermid=7 or incotermid=8 or incotermid=9 or incotermid=10;
update shipment set incotermid=1 where incotermid=7;
update shipment set incotermid=2 where incotermid=8;
update shipment set incotermid=4 where incotermid=9;
update shipment set incotermid=6 where incotermid=10;
create table if not exists incoterm (
  incotermid smallint unsigned not null primary key auto_increment,
  incotermname varchar(3) not null default "",
  incotermdescription varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into incoterm (incotermid,incotermname,incotermdescription) values (1,"FOB","Franco bord");
insert into incoterm (incotermid,incotermname,incotermdescription) values (2,"CFR","Coût et fret (C et F)");
insert into incoterm (incotermid,incotermname,incotermdescription) values (3,"CIF","Coût, assurance, fret (CAF)");
insert into incoterm (incotermid,incotermname,incotermdescription) values (4,"EXW","A l’usine");
insert into incoterm (incotermid,incotermname,incotermdescription) values (6,"FCA","Franco Transporteur");
insert into incoterm (incotermid,incotermname,incotermdescription) values (11,"FAS","Franco le long du navire");
insert into incoterm (incotermid,incotermname,incotermdescription) values (12,"CPT","Port payé jusqu’à");
insert into incoterm (incotermid,incotermname,incotermdescription) values (13,"CIP","Port payé, assurance comprise jusqu’à");
insert into incoterm (incotermid,incotermname,incotermdescription) values (14,"DAF","Rendu frontière");
insert into incoterm (incotermid,incotermname,incotermdescription) values (15,"DES","Rendu EX SHIP");
insert into incoterm (incotermid,incotermname,incotermdescription) values (16,"DEQ","Rendu à quai");
insert into incoterm (incotermid,incotermname,incotermdescription) values (17,"DDU","Rendu droits non acquittés");
';
$uA[36] = '
ALTER TABLE usertable CHANGE accounting_accountbyselect accounting_accountbyselect tinyint(3) unsigned NOT NULL DEFAULT "0" AFTER colorthemeid;
';
$uA[37] = '
alter table product add column countstock tinyint not null default 1;
';
$uA[38] = '
insert into incoterm (incotermid,incotermname,incotermdescription) values (18,"DDP","Rendu droits acquittés");
insert into incoterm (incotermid,incotermname,incotermdescription) values (19,"DAT","Rendu au terminal");
insert into incoterm (incotermid,incotermname,incotermdescription) values (20,"DAP","Rendu au lieu de destination");
';
$uA[39] = '
alter table client add column telephone3 varchar(100) not null default "";
alter table client add column telephone4 varchar(100) not null default "";
alter table client add column email2 varchar(100) not null default "";
alter table client add column email3 varchar(100) not null default "";
alter table client add column email4 varchar(100) not null default "";
alter table globalterms add column term_client_telephone varchar(100) not null default "Téléphone";
alter table globalterms add column term_client_cellphone varchar(100) not null default "Vini";
alter table globalterms add column term_client_telephone3 varchar(100) not null default "";
alter table globalterms add column term_client_telephone4 varchar(100) not null default "";
alter table globalterms add column term_client_email varchar(100) not null default "Email";
alter table globalterms add column term_client_email2 varchar(100) not null default "";
alter table globalterms add column term_client_email3 varchar(100) not null default "";
alter table globalterms add column term_client_email4 varchar(100) not null default "";
';
$uA[40] = '
alter table product add column only_quantity_rebate tinyint unsigned not null default 0;
';
$uA[41] = '
alter table usertable add column show_hideprices_after_confirm tinyint unsigned not null default 0;
';
$uA[42] = '
alter table pallet add column orig_quantity int unsigned not null default 0;
';
$uA[43] = '
alter table product add column excludefromdelivery tinyint unsigned not null default 0;
';
$uA[44] = '
alter table globalvariables add column use_invoiceitemgroup tinyint unsigned not null default 0;
create table if not exists invoiceitemgroup (
  invoiceitemgroupid int unsigned not null primary key auto_increment,
  invoiceitemid int unsigned not null default 0,
  invoiceid int unsigned not null default 0,
  invoiceitemgroupnumber smallint unsigned not null default 0,
  invoiceitemgrouptitle varchar(100) not null default ""
);
';
$uA[45] = '
alter table companyinfo add column seniority_bonus_calc tinyint unsigned not null default 0;
';
$uA[46] = '
alter table log_salesprice add column old_salesprice decimal(19,4) unsigned not null default 0;
';
$uA[47] = '
alter table payslip add column hoursworked decimal(10,4) unsigned not null default 0;
';
$uA[48] = '
create table if not exists payslip_tax_bracket (
  payslip_tax_bracketid int unsigned not null primary key auto_increment,
  payslipid int unsigned not null default 0,
  bracket0 decimal(19,4) unsigned not null default 0,
  bracket1 decimal(19,4) unsigned not null default 0,
  bracket2 decimal(19,4) unsigned not null default 0,
  bracket3 decimal(19,4) unsigned not null default 0,
  bracket4 decimal(19,4) unsigned not null default 0,
  bracket5 decimal(19,4) unsigned not null default 0,
  bracket6 decimal(19,4) unsigned not null default 0,
  bracket7 decimal(19,4) unsigned not null default 0,
  bracket8 decimal(19,4) unsigned not null default 0,
  bracket9 decimal(19,4) unsigned not null default 0,
  bracket10 decimal(19,4) unsigned not null default 0
);
';
$uA[49] = '
alter table payslip_tax_bracket 
 add column bracket_base0 decimal(19,4) unsigned not null default 0,
 add column bracket_base1 decimal(19,4) unsigned not null default 0,
 add column bracket_base2 decimal(19,4) unsigned not null default 0,
 add column bracket_base3 decimal(19,4) unsigned not null default 0,
 add column bracket_base4 decimal(19,4) unsigned not null default 0,
 add column bracket_base5 decimal(19,4) unsigned not null default 0,
 add column bracket_base6 decimal(19,4) unsigned not null default 0,
 add column bracket_base7 decimal(19,4) unsigned not null default 0,
 add column bracket_base8 decimal(19,4) unsigned not null default 0,
 add column bracket_base9 decimal(19,4) unsigned not null default 0,
 add column bracket_base10 decimal(19,4) unsigned not null default 0;
';
$uA[50] = '
ALTER TABLE invoiceitemgroup CHANGE invoiceitemgroupnumber invoiceitemgroupnumber double(5,2) unsigned NOT NULL DEFAULT 0 AFTER invoiceid;
alter table invoiceitemgroup add column is_optional tinyint unsigned not null default 0;
';
$uA[51] = '
create table if not exists qr_location (
  qr_locationid int unsigned not null primary key auto_increment,
  qr_locationname varchar(100) not null default "",
  clientid int unsigned not null default 0,
  deleted tinyint unsigned not null default 0
);
create table if not exists qr_location_event (
  qr_location_eventid tinyint unsigned not null primary key auto_increment,
  qr_locationid int unsigned not null default 0,
  employeeid smallint unsigned not null default 0,
  eventdate date default null,
  eventtime time default null,
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listqr_location:", "Liste des lieus QR:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddqr_location:", "Lieus QR ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "qr_location", "Lieu QR", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodqr_location:", "Lieu QR modifié: ##1##" , 0);
';
$uA[52] = '
delete from trad where string="listqr_location:";
delete from trad where string="successaddqr_location:";
delete from trad where string="qr_location";
delete from trad where string="successmodqr_location:";
insert into trad (lang, string, tradstring, important) values ("fr", "listqr_location:", "Liste des sites QR:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddqr_location:", "Site QR ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "qr_location", "Site QR", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodqr_location:", "Site QR modifié: ##1##" , 0);
';
$uA[53] = '
alter table product add column quantity_convert decimal(15,10) unsigned not null default 0;
';
$uA[54] = '
alter table product add column min_lineprice decimal(19,4) unsigned not null default 0;
';
$uA[55] = '
alter table qr_location_event add column qr_location_text varchar(500) not null default "";
';
$uA[56] = '
alter table qr_location_event add column imageid int unsigned not null default 0;
';
$uA[57] = '
alter table usertable add column user_date_format tinyint unsigned not null default 0;
ALTER TABLE product CHANGE `min_lineprice` `min_invoiceprice` decimal(19,4) unsigned NOT NULL DEFAULT 0;
';
$uA[58] = '
alter table purchase add column pallet_list varchar(100) not null default "";
';
$uA[59] = '
alter table invoice add column clientid2 int unsigned not null default 0;
alter table invoice add column clientid3 int unsigned not null default 0;
alter table invoicehistory add column clientid2 int unsigned not null default 0;
alter table invoicehistory add column clientid3 int unsigned not null default 0;
';
$uA[60] = '
alter table usertable add column manage_qr_locations tinyint unsigned not null default 0;
';
$uA[61] = '
create table if not exists unittype_line (
  unittype_lineid smallint unsigned not null primary key auto_increment,
  unittype_linename varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
';
$uA[62] = '
alter table employee add column salary_account_title varchar(100) not null DEFAULT "";
alter table employee add column salary_account varchar(100) not null DEFAULT "";
alter table employee add column salary_bankid smallint unsigned not null DEFAULT 0;
';
$uA[63] = '
update payslip_line_net set `rank`=25 where `rank`=32;
';
$uA[64] = '
ALTER TABLE `qr_location_event`
CHANGE `qr_location_eventid` `qr_location_eventid` int unsigned NOT NULL AUTO_INCREMENT FIRST;
';
$uA[65] = '
alter table employee add column exitdate date NULL;
';
$uA[66] = '
alter table payslip add column bankaccountid int unsigned not null default 0;
alter table payslip add column paymenttypeid smallint unsigned not null default 0;
';
$uA[67] = '
alter table employee add column default_bankaccountid int unsigned not null default 0;
alter table employee add column default_paymenttypeid smallint unsigned not null default 0;
';
$uA[68] = '
ALTER TABLE `payslip`
CHANGE `vacationdays` `vacationdays` decimal(5,2) NOT NULL DEFAULT "0" AFTER `calc_salary`,
CHANGE `vacationdays_added` `vacationdays_added` decimal(5,2) NOT NULL DEFAULT "0" AFTER `vacationdays`,
CHANGE `vacationdays_used` `vacationdays_used` decimal(5,2) NOT NULL DEFAULT "0" AFTER `vacationdays_added`;
';
$uA[69] = '
ALTER TABLE `endofyearstock`
CHANGE `stock` `stock` int(10) NOT NULL DEFAULT "0" AFTER `year`;
';
$uA[70] = '
alter table usertable add column noreturns tinyint unsigned not null default 0;
';
$uA[71] = '
create table if not exists payslip_advance (
  payslip_advanceid int unsigned not null primary key auto_increment,
  employeeid int unsigned not null default 0,
  month tinyint unsigned not null default 0,
  year smallint unsigned not null default 0,
  advance decimal(19,4) unsigned not null default 0
);
';
$uA[72] = '
alter table payslip add column toacc tinyint unsigned not null default 0;
create table if not exists payslip_toacc (
  payslip_toaccid smallint unsigned not null primary key auto_increment,
  description varchar(100) not null default "",
  accountingnumberid int unsigned not null default 0
);
insert into payslip_toacc (payslip_toaccid,description,accountingnumberid) values
(1,	"Salaire brut soumis cotisation (Débit)", 658),
(2,	"Remboursement des avances d\'indemnités journalières (Débit)", 661),
(3,	"Ajout / déduction net (Débit/Crédit)", 368),
(4,	"CPS part salariale (Crédit)", 376),
(5,	"Salaire net (Crédit)", 26),
(6,	"CPS part patronale + CST (Débit)", 665),
(7,	"CPS part patronale (Crédit)", 376),
(8,	"CST (Crédit)", 387);
;
';
$uA[73] = '
truncate table payslip_toacc;
insert into payslip_toacc (payslip_toaccid,description,accountingnumberid) values
(1,	"Salaire brut soumis cotisation (Débit)", 658),
(2,	"Remboursement des avances d\'indemnités journalières (Débit)", 661),
(3,	"Ajout / déduction net (Débit/Crédit)", 368),
(4,	"CPS part salariale (Crédit)", 376),
(5,	"Salaire net (Crédit)", 26),
(6,	"CPS part patronale (Débit)", 665),
(7,	"CPS part patronale (Crédit)", 376),
(8,	"CST (Crédit)", 387);
;
';
$uA[74] = '
alter table adjustment add column adjustmentcomment_line varchar(100) not null default "";
';
$uA[75] = '
alter table globalterms add column term_invoice varchar(100) not null default "Facture";
';
$uA[76] = '
insert into payslip_toacc (payslip_toaccid,description,accountingnumberid) values (9,	"Congés payés (Débit)", 659);
';
$uA[77] = '
alter table logtable add column clientaccessid int unsigned not null default 0;
alter table log_query add column clientaccessid smallint unsigned not null default 0;
create table if not exists reason_payment_modify (
  reason_payment_modifyid smallint unsigned not null primary key auto_increment,
  reason_payment_modifyname varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listreason_payment_modify:", "Liste des Raisons modif paiement:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddreason_payment_modify:", "Raison modif paiement ajoutée: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "reason_payment_modify", "Raison modif paiement", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodreason_payment_modify:", "Raison modif paiement modifiée: ##1##" , 0);
';
$uA[78] = '
alter table payment add column payment_cardtypeid smallint unsigned not null default 0;
create table if not exists payment_cardtype (
  payment_cardtypeid smallint unsigned not null primary key auto_increment,
  payment_cardtypename varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into payment_cardtype (payment_cardtypeid,payment_cardtypename) values (1,"Locale");
insert into payment_cardtype (payment_cardtypeid,payment_cardtypename) values (2,"VISA");
insert into payment_cardtype (payment_cardtypeid,payment_cardtypename) values (3,"Mastercard");
insert into payment_cardtype (payment_cardtypeid,payment_cardtypename) values (4,"American Express");
insert into payment_cardtype (payment_cardtypeid,payment_cardtypename) values (5,"JCB");
';
$uA[79] = '
create table if not exists journal (
  journalid smallint unsigned not null primary key auto_increment,
  journalname varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
create table if not exists net_modif_account (
  net_modif_accountid smallint unsigned not null primary key auto_increment,
  net_modif_accountname varchar(100) not null default "",
  accountingnumberid int unsigned not null default 0,
  deleted tinyint unsigned not null default 0
);
alter table adjustmentgroup add column journalid smallint not null default 0;
insert into trad (lang, string, tradstring, important) values ("fr", "listjournal:", "Liste des Journeaux:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddjournal:", "Journal ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "journal", "Journal", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodjournal:", "Journal modifié: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "listnet_modif_account:", "Liste des Comptes modif net (Paie):", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddnet_modif_account:", "Compte modif net (Paie) ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "net_modif_account", "Compte modif net (Paie)", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodnet_modif_account:", "Compte modif net (Paie) modifié: ##1##" , 0);
';
$uA[80] = '
alter table shipment add column numberofcontainers20dooropen smallint unsigned not null default 0;
';
$uA[81] = '
create table if not exists fenix_lines (
  fenix_linesid int unsigned not null primary key auto_increment,
  shipmentid int unsigned not null default 0,
  linenr smallint unsigned not null default 0,
  sih varchar(100) not null default "",
  avantage varchar(10) not null default "",
  code_suffixe varchar(10) not null default "",
  fenixcode varchar(3) not null default "",
  case_j varchar(10) not null default "",
  net_mass double(10,4) unsigned not null default 0,
  gross_mass double(10,4) unsigned not null default 0,
  fenix_req_procedureid tinyint unsigned not null default 0,
  fenix_prev_procedureid tinyint unsigned not null default 0,
  b42_item_price double(10,4) unsigned not null default 0, /* purchaseprice */
  b44_declared_units double(10,4) unsigned not null default 0 /* amount */
);
';
$uA[82] = '
alter table payslip add column hours_text varchar(100) not null default "";
';
$uA[83] = '
alter table product add column fenix42 tinyint unsigned not null default 0;
alter table fenix_lines add column fenix42 tinyint unsigned not null default 0;
';
$uA[84] = '
alter table employee add column employee_is_clientid int unsigned not null default 0;
';
$uA[85] = '
alter table country add column traderegionid smallint not null default 0;
';
$uA[86] = '
alter table product add column tcp_gradient tinyint unsigned not null default 0;
';
$uA[87] = '
alter table companyinfo add column rc varchar(100) not null default "";
';
$uA[88] = '
alter table log_salesprice add column taxcodeid smallint unsigned not null default 0;
';
$uA[89] = '
alter table globalvariables add column use_clientaction_case tinyint unsigned not null default 1;
alter table clientaction add column clientaction_caseid int unsigned not null default 0;
update payslip_toacc set description="Salaire brut soumis à cotisations (Débit)" where payslip_toaccid=1;
update payslip_toacc set description="Ajout / Déduction net(te) (Débit/Crédit)" where payslip_toaccid=3;
create table if not exists clientaction_case (
  clientaction_caseid int unsigned not null primary key auto_increment,
  casename varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
';
# this must be done manually
# alter table usertable add column access_clientid int unsigned not null default 0;
$uA[90] = 'select companyname from companyinfo;'; # no empty query
$uA[91] = '
create table if not exists email_body (
  email_bodyid int unsigned not null primary key auto_increment,
  subject varchar(100) not null default "",
  deleted tinyint unsigned not null default 0,
  email_body text
);
alter table usertable add column can_send_emails tinyint unsigned not null default 1;
';
$uA[92] = '
alter table client add column batchemail varchar(100) not null default "";
update client set batchemail=email;
';
$uA[93] = '
alter table invoiceitemhistory add index invoiceid (invoiceid);
';
$uA[94] = '
alter table product add column on_behalf tinyint unsigned not null default 0;
';
$uA[95] = '
alter table globalvariables_accounting add column integrated_journalid smallint unsigned not null default 0;
alter table globalvariables_accounting add column onbehalf_anid int unsigned not null default 441;
';
$uA[96] = '
alter table image add column invoiceid int unsigned not null default 0 after productid;
';
$uA[97] = '
alter table globalvariables add column quote_info varchar(1000) not null default "";
';
$uA[98] = '
alter table product add column hide_price_on_invoice tinyint unsigned not null default 0;
';
$uA[99] = '
alter table product add column no_client_discount tinyint unsigned not null default 0;
';
$uA[100] = '
alter table employee add column hourly_pay tinyint unsigned not null default 0;
';
$uA[101] = '
alter table image add sig_invoiceid int unsigned not null default 0 after invoiceid;
alter table globalvariables add use_invoice_sig tinyint unsigned not null default 1;
';
$uA[102] = '
alter table usertable add column use_invoiceitemgroup tinyint unsigned not null default 0;
';
$uA[103] = '
create table if not exists select_itemcomment (
  select_itemcommentid smallint unsigned not null primary key auto_increment,
  select_itemcommentname varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listselect_itemcomment:", "Liste des Commentaire ligne:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddselect_itemcomment:", "Commentaire ligne ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "select_itemcomment", "Commentaire ligne", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodselect_itemcomment:", "Commentaire ligne modifié: ##1##" , 0);
';
$uA[104] = '
insert into payment_cardtype (payment_cardtypeid,payment_cardtypename) values (6,"OSB");
';
$uA[105] = '
alter table accounting_simplified add column journalid smallint unsigned not null default 0;
';
$uA[106] = '
alter table invoicehistory add index (clientid);
alter table invoicehistory add index (userid);
alter table invoicehistory add index (localvesselid);
alter table invoicehistory add index (invoicegroupid);
alter table invoicehistory add index (invoicetagid);
alter table invoicehistory add index (invoicetagid2);
alter table invoicehistory add index (deliverytypeid);
alter table invoicehistory add index (returnreasonid);
alter table invoiceitemhistory add index (productid);
';
$uA[107] = '
alter table log_salesprice add column log_salesprice_type tinyint unsigned not null default 0;
alter table log_salesprice add column exception_id smallint unsigned not null default 0;
';
$uA[108] = '
delete from vatindex where vatindexid=2;
delete from vatindex where vatindexid=202;
update vatindex set vatindexname="02 [TVA sur encaissements]" where vatindexid=102;
';
$uA[109] = '
alter table globalterms add column term_invoice_priceoption1 varchar(100) not null default "Option des Prix 1";
alter table globalterms add column term_invoice_priceoption2 varchar(100) not null default "Option des Prix 2";
alter table globalterms add column term_invoice_priceoption3 varchar(100) not null default "Option des Prix 3";
create table if not exists invoice_priceoption1 (
  invoice_priceoption1id smallint unsigned not null primary key auto_increment,
  invoice_priceoption1name varchar(100) not null default "",
  salesprice_mod decimal(19,4) not null default 0,
  `rank` smallint unsigned not null default 100,
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listinvoice_priceoption1:", "Liste des Options des Prix 1:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddinvoice_priceoption1:", "Options des Prix 1 ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "invoice_priceoption1", "Options des Prix 1", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodinvoice_priceoption1:", "Options des Prix 1 modifié: ##1##" , 0);
create table if not exists invoice_priceoption2 (
  invoice_priceoption2id smallint unsigned not null primary key auto_increment,
  invoice_priceoption2name varchar(100) not null default "",
  salesprice_mod decimal(19,4) not null default 0,
  `rank` smallint unsigned not null default 100,
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listinvoice_priceoption2:", "Liste des Options des Prix 2:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddinvoice_priceoption2:", "Options des Prix 2 ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "invoice_priceoption2", "Options des Prix 2", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodinvoice_priceoption2:", "Options des Prix 2 modifié: ##1##" , 0);
create table if not exists invoice_priceoption3 (
  invoice_priceoption3id smallint unsigned not null primary key auto_increment,
  invoice_priceoption3name varchar(100) not null default "",
  salesprice_mod decimal(19,4) not null default 0,
  `rank` smallint unsigned not null default 100,
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listinvoice_priceoption3:", "Liste des Options des Prix 3:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddinvoice_priceoption3:", "Options des Prix 3 ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "invoice_priceoption3", "Options des Prix 3", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodinvoice_priceoption3:", "Options des Prix 3 modifié: ##1##" , 0);
';
$uA[110] = '
alter table invoiceitem add column invoice_priceoption1id smallint unsigned not null default 0;
alter table invoiceitem add column invoice_priceoption2id smallint unsigned not null default 0;
alter table invoiceitem add column invoice_priceoption3id smallint unsigned not null default 0;
alter table invoiceitemhistory add column invoice_priceoption1id smallint unsigned not null default 0;
alter table invoiceitemhistory add column invoice_priceoption2id smallint unsigned not null default 0;
alter table invoiceitemhistory add column invoice_priceoption3id smallint unsigned not null default 0;
';
$uA[111] = '
alter table employee add column isdelivery tinyint unsigned not null default 0;
alter table invoicegroup add column employeeid smallint unsigned not null default 0;
';
$uA[112] = '
alter table usertable add column matching_extended_info tinyint unsigned not null default 1;
';
$uA[113] = '
insert into trad (lang, string, tradstring, important) values ("fr", "listpalette:", "Liste des Palettes:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddpalette:", "Palette ajoutée: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "palette", "Palette", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodpalette:", "Palette modifiée: ##1##" , 0);
create table if not exists palette (
  paletteid smallint unsigned not null primary key auto_increment,
  palettename varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listcolor:", "Liste des Couleurs:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddcolor:", "Couleur ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "color", "Couleur", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodcolor:", "Couleur modifié: ##1##" , 0);
create table if not exists advance (
  advanceid smallint unsigned not null primary key auto_increment,
  advancename varchar(100) not null default "",
  advance_percentage tinyint unsigned not null default 0,
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listadvance:", "Liste des Acomptes:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddadvance:", "Acompte ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "advance", "Acompte", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodadvance:", "Acompte modifié: ##1##" , 0);
alter table color add column deleted tinyint unsigned not null default 0;
create table if not exists palette_color_matrix (
  palette_color_matrixid int unsigned not null primary key auto_increment,
  paletteid smallint unsigned not null default 0,
  colorid int unsigned not null default 0
);
alter table product add column paletteid smallint unsigned not null default 0;
';
$uA[114] = '
alter table invoice add column advanceid smallint unsigned not null default 0;
alter table invoicehistory add column advanceid smallint unsigned not null default 0;
';
$uA[115] = '
create table if not exists invoice_priceoption2_filter (
  invoice_priceoption2_filterid smallint unsigned not null primary key auto_increment,
  invoice_priceoption2_filtername varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
create table if not exists invoice_priceoption2_filter_matrix (
  invoice_priceoption2_filter_matrixid int unsigned not null primary key auto_increment,
  invoice_priceoption2_filterid smallint unsigned not null default 0,
  invoice_priceoption2id smallint unsigned not null default 0
);
alter table product add column invoice_priceoption2_filterid smallint unsigned not null default 0;
';
$uA[116] = '
update paymenttype set paymenttypename="Espèces" where paymenttypeid=1;
';
$uA[117] = '
alter table employeequalification add column expiredate date default null after obtainingdate;
';
$uA[118] = '
alter table invoicegroup add column status tinyint unsigned not null default 0;
alter table invoicegroup add column picking_start datetime default null;
alter table invoicegroup add column picking_complete datetime default null;
create table if not exists picking (
  pickingid int unsigned not null primary key auto_increment,
  employeeid smallint unsigned not null default 0,
  pickingdate date default null,
  pickingtime time default null,
  palletid int unsigned not null default 0,
  quantity int unsigned not null default 0,
  productid int unsigned not null default 0,
  invoicegroupid int unsigned not null default 0
);
';
$uA[119] = '
alter table invoicegroup add column employee2id smallint unsigned not null default 0;
alter table employee add column ispicking tinyint unsigned not null default 0;
';
$uA[120] = '
alter table usertable add column invoicereport_menus tinyint unsigned not null default 1;
';
$uA[121] = '
alter table usertable add column user_datepicker tinyint unsigned not null default 0;
alter table accounting_simplified add column default_adjustmentcomment varchar(100) not null default "";
alter table accounting_simplified add column default_reference varchar(100) not null default "";
';
$uA[122] = '
alter table clientaction add column originid tinyint unsigned not null default 0;
alter table clientaction add column contact_typeid tinyint unsigned not null default 0;
';
$uA[123] = '
alter table product add column cartonweight decimal(10,4) unsigned not null default 0;
';
$uA[124] = '
create table if not exists bankstatement (
  bankstatementid int unsigned not null primary key auto_increment,
  bankaccountid int unsigned not null default 0,
  statementdate date default null,
  validitydate date default null,
  amount decimal(19,4) not null default 0, /* NOT unsigned */
  statementtext varchar(500) not null default "",
  statementcode char(3) not null default ""
);
';
$uA[125] = '
alter table invoicetag add column invoicetag_clientid int unsigned not null default 0;
ALTER TABLE purchase CHANGE amount amount decimal(19,4) unsigned NOT NULL DEFAULT 0;
';
$uA[126] = '
delete from vatindex where vatindexid=102;
update accountingnumber set vatindexid=0 where vatindexid=102 or vatindexid=2;
alter table bankstatement add column chequeno varchar(100) not null default "";
alter table bankstatement add column adjustmentgroupid int unsigned not null default 0 after bankaccountid;
alter table bankstatement add column adjustmentid int unsigned not null default 0 after adjustmentgroupid;
';
$uA[127] = '
truncate table layout;
INSERT INTO layout (layoutid, layoutname) VALUES (1, "Classique");
INSERT INTO layout (layoutid, layoutname) VALUES (2, "Anissa");
INSERT INTO layout (layoutid, layoutname) VALUES (3, "Audrey");
INSERT INTO layout (layoutid, layoutname) VALUES (4, "Frugal");
INSERT INTO layout (layoutid, layoutname) VALUES (5, "Ana");
delete from color_theme where themeid<6;
INSERT INTO `color_theme` (`themeid`, `themename`, `bgcolor`, `fgcolor`, `linkcolor`, `menucolor`, `alertcolor`, `infocolor`, `formcolor`, `tablecolor`, `inputcolor`, `menubordercolor`, `menufontcolor`, `tablecolor1`, `tablecolor2`, `hovercolor`, `usehovercolor`, `nbtablecolors`, `usetablecolorsub`, `tablecolorsub`, `userid`) VALUES
(1,	"Classique",	"ffffff",	"000000",	"00008b",	"87cefa",	"ff0000",	"f08080",	"ffe4b5",	"d5d7df",	"ffffff",	"133e40",	"ffffff",	"9ac1df",	"d5d7df",	"f8f8ff",	1,	1,	1,	"d5d7df",	0),
(2,	"Anissa",	"ffffff",	"000000",	"2a8a8f",	"36b0b6",	"ff0000",	"a9a9a9",	"f8f8ff",	"d5d7df",	"ffffff",	"133e40",	"ffffff",	"9ac1df",	"d5d7df",	"f8f8ff",	1,	2,	1,	"5384c0",	0),
(3,	"Audrey",	"ffffff",	"4682b4",	"4682b4",	"f8f8ff",	"ff0000",	"b0c4de",	"ffffff",	"d5d7df",	"ffffff",	"d3d3d3",	"ffffff",	"9ac1df",	"d5d7df",	"f8f8ff",	1,	2,	1,	"d5d7df",	0),
(4,	"Ana",	"ffffff",	"000000",	"232f3e",	"232f3e",	"ff0000",	"e8893c",	"e0ecff",	"9ac1df",	"ffffff",	"3b495c",	"ffffff",	"d5d7df",	"9ac1df",	"c7f060",	1,	2,	1,	"4d69d6",	0),
(5,	"Frugal",	"ffffff",	"000000",	"232f3e",	"232f3e",	"ff0000",	"e8893c",	"e0ecff",	"9ac1df",	"ffffff",	"3b495c",	"ffffff",	"d5d7df",	"9ac1df",	"c7f060",	1,	2,	1,	"4d69d6",	0);
';
$uA[128] = '
ALTER TABLE globalvariables CHANGE use_clientaction_case reconciliation_type tinyint(3) unsigned NOT NULL DEFAULT 0;
update globalvariables set reconciliation_type=1;
';
$uA[129] = '
truncate table layout;
INSERT INTO layout (layoutid, layoutname) VALUES (1, "Néon");
INSERT INTO layout (layoutid, layoutname) VALUES (2, "Courrier");
INSERT INTO layout (layoutid, layoutname) VALUES (3, "Organizer");
INSERT INTO layout (layoutid, layoutname) VALUES (4, "Factory");
INSERT INTO layout (layoutid, layoutname) VALUES (5, "Moderne");
update color_theme set themename="Néon" where themeid=1;
update color_theme set themename="Courrier" where themeid=2;
update color_theme set themename="Organizer" where themeid=3;
update color_theme set themename="Factory" where themeid=4;
update color_theme set themename="Moderne" where themeid=5;
alter table accounting_simplified add column for_bankstatement tinyint unsigned not null default 0;
';
$uA[130] = '
alter table payslip add column payroll_payment_date date default null after hours_text;
';
$uA[131] = '
alter table globalterms add column term_accounting_tag varchar(100) not null default "";
create table if not exists adjustmentgroup_tag (
  adjustmentgroup_tagid smallint unsigned not null primary key auto_increment,
  adjustmentgroup_tagname varchar(100) not null default "",
  deleted tinyint unsigned not null default 0
);
insert into trad (lang, string, tradstring, important) values ("fr", "listadjustmentgroup_tag:", "Liste des Tags Compta:", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successaddadjustmentgroup_tag:", "Tag Compta ajouté: ##1##" , 0);
insert into trad (lang, string, tradstring, important) values ("fr", "adjustmentgroup_tag", "Tag Compta", 0);
insert into trad (lang, string, tradstring, important) values ("fr", "successmodadjustmentgroup_tag:", "Tag Compta modifié: ##1##" , 0);
alter table adjustmentgroup add column adjustmentgroup_tagid smallint not null default 0;
alter table accounting_simplified add column use_adjustmentgroup_tag tinyint unsigned not null default 0;
';
$uA[132] = '
alter table usertable add column accounting_simplified_keepdate tinyint unsigned not null default 0;
';
$uA[133] = '
insert into balancesheetindex (balancesheetindexid,balancesheetindexname) values ("001","A définir selon nature de l\'activité");
';
$uA[134] = '
insert into temperature (temperatureid, temperaturename) values (3,"Climatisé");
';
$uA[135] = '
update paymenttype set paymenttypename="Espèces" where paymenttypeid=1;
';
$uA[136] = '
alter table globalterms add column term_interventionfield1 varchar(100) not null default "";
alter table globalterms add column term_interventionfield2 varchar(100) not null default "";
alter table globalterms add column term_interventionfield3 varchar(100) not null default "";
alter table globalterms add column term_interventionfield4 varchar(100) not null default "";
alter table globalterms add column term_intervention_tag1 varchar(100) not null default "";
alter table globalterms add column term_intervention_tag2 varchar(100) not null default "";
alter table globalterms add column term_intervention_value1 varchar(100) not null default "";
alter table globalterms add column term_intervention_value2 varchar(100) not null default "";
alter table globalterms add column term_intervention_value3 varchar(100) not null default "";
alter table globalterms add column term_intervention_value4 varchar(100) not null default "";
create table if not exists intervention (
  interventionid int unsigned not null primary key auto_increment ,
  interventiontitle varchar(100) not null default "" ,
  interventionemployeeid smallint unsigned not null default 0 ,
  interventionclientid smallint unsigned not null default 0 ,
  interventioncomment varchar(200) not null default "" ,
  interventiondate date null default null
);

create table if not exists interventionitem(
 interventionitemid int unsigned not null primary key auto_increment,
 interventionid int unsigned not null,
 employeeid smallint unsigned default 0,
 productid smallint unsigned not null default 0,
 timestart time not null default "00:00:00",
 timeend time not null default "00:00:00",
 interventiontagid smallint unsigned not null default 0,
 interventiontagid2 smallint unsigned not null default 0,
 field1 varchar(100) not null default "",
 field2 varchar(100) not null default "",
 field3 varchar(100) not null default "",
 field4 varchar(100) not null default "",
 value1 decimal(19, 4) not null default 0,
 value2 decimal(19, 4) not null default 0,
 value3 decimal(19, 4) not null default 0,
 value4 decimal(19, 4) not null default 0,
 description varchar(200) not null default ""
);

create table if not exists interventionitemtag1(
 interventionitemtag1id int unsigned not null primary key auto_increment,
 interventionitemtag1name varchar(100) not null default "",
 deleted tinyint unsigned not null default 0
);

create table if not exists interventionitemtag2(
 interventionitemtag2id int unsigned not null primary key auto_increment,
 interventionitemtag2name varchar(100) not null default "",
 deleted tinyint unsigned not null default 0
);
';
$uA[137] = '
alter table globalvariables add column use_interventions tinyint unsigned not null default 1;
';
?>