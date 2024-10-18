<?php
/////////////////////////////////////////////////////////////////////////////////

//Datenbankzugangsdaten

$db_server=""; // Databaseserver
$db_port="3306"; //Databaseport
$db_login=""; // Databaseusername
$db_password=""; // Databasepassword
$db_name="skrupel";

//Adminzugangsdaten

$admin_login="admin";
$admin_pass=""; // Adminpassword

//Absenderemail des Servers

$absenderemail=""; // Aus kompatibilitätsgründen noch vorhanden

date_default_timezone_set('Europe/Berlin');

/////////////////////////////////////////////////////////////////////////////////

//Tabellen
/* 
 * Noch stehen die Tabellennamen hier, werden aber eigentlich nicht mehr gebraucht.
 */
$skrupel_planeten='skrupel_planeten';
$skrupel_spiele='skrupel_spiele';
$skrupel_schiffe='skrupel_schiffe';
$skrupel_kampf='skrupel_kampf';
$skrupel_user='skrupel_user';
$skrupel_sternenbasen='skrupel_sternenbasen';
$skrupel_neuigkeiten='skrupel_neuigkeiten';
$skrupel_chat='skrupel_chat';
$skrupel_forum_thema='skrupel_forum_thema';
$skrupel_forum_beitrag='skrupel_forum_beitrag';
$skrupel_huellen='skrupel_huellen';
$skrupel_anomalien='skrupel_anomalien';
$skrupel_nebel='skrupel_nebel';
$skrupel_politik='skrupel_politik';
$skrupel_politik_anfrage='skrupel_politik_anfrage';
$skrupel_konplaene='skrupel_konplaene';
$skrupel_info='skrupel_info';
$skrupel_ordner='skrupel_ordner';
$skrupel_scan='skrupel_scan';
$skrupel_begegnung='skrupel_begegnung';


/////////////////////////////////////////////////////////////////////////////////

$language='de';
$ping_off=0;

/////////////////////////////////////////////////////////////////////////////////

$spielerfarbe[1]="#1DC710";    //gruen
$spielerfarbe[2]="#E5E203";    //gelb
$spielerfarbe[3]="#EAA500";    //orange
$spielerfarbe[4]="#875F00";    //braun
$spielerfarbe[5]="#bb0000";    //rot
$spielerfarbe[6]="#D700C1";    //rosa
$spielerfarbe[7]="#7D10C7";    //lila
$spielerfarbe[8]="#101DC7";    //blau
$spielerfarbe[9]="#049EEF";    //hellblau
$spielerfarbe[10]="#10C79B";   //tuerkis

/////////////////////////////////////////////////////////////////////////////////

//Error Behandlung, bei bedarf aktivieren

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('ignore_repeated_errors', 0);
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('error_log', 'e:/xampp/htdocs/logs/error.log');

/* 
 * 
 * Servername 
 */
define ('servername', 'http://localhost/'); //for debugging
define ('image_dir' , servername . 'images/');
define ('main_dir', $_SERVER["DOCUMENT_ROOT"] . '/');
define ('extend_dir', main_dir . 'extend/'); // nur noch aus kompatiblität hier
define ('daten_dir' , main_dir . 'daten/'); // nur noch aus kompatiblität hier
define ('inhalt_dir' , main_dir . 'inhalt/'); // nur noch aus kompatiblität hier
define ('lang_dir' , main_dir . 'lang/'); // nur noch aus kompatiblität hier
define ('includes', main_dir . 'includes/'); // hier landen alle Dateien die von irgwelchen Skripten includiert werden
define ('extensions' , main_dir . 'extend/'); // Extensions 
define ('table_prefix', "skrupel_"); // hier kann man seinen Tabellenpräfix anpassen
define ('sprache', 'de');

define ('sversion' , 'V0.1.0 Optimized Version &copy; by SkullCollector 2024'); //kann nach belieben geändert werden
include(main_dir . 'libs/adodb/vendor/autoload.php');

$db = adoNewConnection('mysqli'); # eg. 'mysqli' or 'oci8'
$db->debug = false;
$db->connect($db_server, $db_login, $db_password, $db_name);

$smartytemplates = main_dir . "templates";
$smartyconfig =    main_dir . "config";
$smartycompile =   main_dir . "templates_c";
$smartycache =     main_dir . "cache";

require_once(main_dir . "libs/smarty/vendor/autoload.php");
$smarty = new Smarty\Smarty;
$smarty->setTemplateDir($smartytemplates);
$smarty->setConfigDir($smartyconfig);
$smarty->setCompileDir($smartycompile);
$smarty->setCacheDir($smartycache);
$smarty->debugging = true;	 

$smarty->assign('image_dir', image_dir);
$smarty->assign('servername', servername);
include(main_dir . "includes/functions.inc.php");
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST));

$smarty->assign('sversion' , sversion);

/*
 * $db , $smarty , $params werden zentral erreichbar gemacht. config.inc wird nur noch einmal aufgerufen werden müssen und das von der index.php aus. Somit ist alles wichtige erreichbar.
 * 
 */
global $db,$smarty,$params;
