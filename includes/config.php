<?php
/////////////////////////////////////////////////////////////////////////////////

//Datenbankzugangsdaten

$db_server="localhost";
$db_port="3306";
$db_login="skrupel";
$db_password="Nasen.ba1$";
$db_name="skrupel";

//Adminzugangsdaten

$admin_login="admin";
$admin_pass="Nasen.ba1$";

//Absenderemail des Servers

$absenderemail="cash4read@gmx.de";

date_default_timezone_set('Europe/Berlin');

/////////////////////////////////////////////////////////////////////////////////

//Tabellen
/* 
 * Skrupel Tabellen entfernt. Werden nicht mehr in der Config benötigt.
 */



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
define ('servername', 'http://entwicklung.fritz.box/'); //for debugging
define ('image_dir' , servername . 'images/');
define ('main_dir', $_SERVER["DOCUMENT_ROOT"] . '/');
define ('extend_dir', main_dir . 'extend/');
define ('daten_dir' , main_dir . 'daten/');
define ('inhalt_dir' , main_dir . 'inhalt/');
define ('lang_dir' , main_dir . 'lang/');
define ('includes', main_dir . 'includes/');
define ('extensions' , main_dir . 'extend/');
define ('table_prefix', "skrupel_");
define ('sprache', 'de');

define ('sversion' , 'V0.1.0 Optimized Version &copy; by SkullCollector 2024');
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

global $db,$smarty,$params;
