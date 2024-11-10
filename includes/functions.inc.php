<?php 
/**
 * Wir Filtern nun die SuperGlobals wie $_GET, $_POST,$_ENV, $_REQUEST, um die korrektheit der gesendeten Daten zu überprüfen
 * Aufruf der Funktion : filter_struct_utf8(1,$_GET oder $_POST);
 *
 * @param  integer $type    Constant like INPUT_XXX.
 * @param  array   $default Default structure of the specified super global var.
 *                          Following bitmasks are available:
 *  + FILTER_STRUCT_FORCE_ARRAY - Force 1 dimensional array.
 *  + FILTER_STRUCT_TRIM        - Trim by ASCII control chars.
 *  + FILTER_STRUCT_FULL_TRIM   - Trim by ASCII control chars,
 *                                full-width and no-break space.
 * @return array            The value of the filtered super global var.
 */
define('FILTER_STRUCT_FORCE_ARRAY', 1);
define('FILTER_STRUCT_TRIM', 2);
define('FILTER_STRUCT_FULL_TRIM', 4);

function filter_struct_utf8($type, array $default) {
    static $func = __FUNCTION__;
    static $trim = "[\\x0-\x20\x7f]";
    static $ftrim = "[\\x0-\x20\x7f\xc2\xa0\xe3\x80\x80]";
    static $recursive_static = false;
    if (!$recursive = $recursive_static) {
        $types = array(
            INPUT_GET => $_GET,
            INPUT_POST => $_POST,
            INPUT_COOKIE => $_COOKIE,
           // INPUT_REQUEST => $_REQUEST,
        );
        if (!isset($types[(int) $type])) {
            throw new LogicException('unknown super global var type');
        }
        $var = $types[(int) $type];
        $recursive_static = true;
    } else {
        $var = $type;
    }
    $ret = array();
    foreach ($default as $key => $value) {
        if ($is_int = is_int($value)) {
            if (!($value | (
                FILTER_STRUCT_FORCE_ARRAY |
                FILTER_STRUCT_FULL_TRIM |
                FILTER_STRUCT_TRIM
                ))) {
                    $recursive_static = false;
                    throw new LogicException('unknown bitmask');
                }
                if ($value & FILTER_STRUCT_FORCE_ARRAY) {
                    $tmp = array();
                    if (isset($var[$key])) {
                        foreach ((array) $var[$key] as $k => $v) {
                            if (!preg_match('//u', $k)) {
                                continue;
                            }
                            $value &= FILTER_STRUCT_FULL_TRIM | FILTER_STRUCT_TRIM;
                            $tmp += array($k => $value ? $value : '');
                        }
                    }
                    $value = $tmp;
                }
        }
        if ($isset = isset($var[$key]) and is_array($value)) {
            $ret[$key] = $func($var[$key], $value);
        } elseif (!$isset || is_array($var[$key])) {
            $ret[$key] = null;
        } elseif ($is_int && $value & FILTER_STRUCT_FULL_TRIM) {
            $ret[$key] = preg_replace("/\A{$ftrim}++|{$ftrim}++\z/u", '', $var[$key]);
        } elseif ($is_int && $value & FILTER_STRUCT_TRIM) {
            $ret[$key] = preg_replace("/\A{$trim}++|{$trim}++\z/u", '', $var[$key]);
        } else {
            $ret[$key] = preg_replace('//u', '', $var[$key]);
        }
        if ($ret[$key] === null) {
            $ret[$key] = $is_int ? '' : $value;
        }
    }
    if (!$recursive) {
        $recursive_static = false;
    }
    return $ret;
}

function set_header () {    
global $db,$smarty,$params;

include (includes . 'inc.check.php');
$zeiger = "SELECT * FROM " . table_prefix . "info";
$array = $db->getRow($zeiger);
$spiel_chat      = $array['chat'];
$spiel_anleitung = $array['anleitung'];
$spiel_forum     = $array['forum'];
$spiel_forum_url = $array['forum_url'];
$spiel_version   = $array['version'];
$spiel_extend    = $array['extend'];
$spiel_serial    = $array['serial'];

$useragent = getEnv("HTTP_USER_AGENT");
$firefox = preg_match("=firefox=i", $useragent);
$linux = preg_match("=linux=i", $useragent);
$plus=0;
if ($linux) { 
    $plus=1;     
}
$fontsize_small = 10-$plus;
$fontsize_big = 12-$plus;
$smarty->assign('fontsize_small', $fontsize_small);
$smarty->assign('fontsize_big', $fontsize_big);
$smarty->assign('servername', servername);
$smarty->assign('uid', $params['uid']);
$smarty->assign('sid', $params['sid']);

$flexjs = "";
$showNot = array('meta_simulation.php', 'flotte_beta.php', 'basen_alpha.php');
        if ((@intval(substr($spieler_optionen,17,1))!=1) and (!in_array(basename($_SERVER['PHP_SELF']), $showNot))) { 
        $flexjs = '<script type="text/javascript" src="' . servername. 'js/flexcroll/flexcroll.js"></script>';
   }
$smarty->assign('flexjs', $flexjs);
}

function get_phrasen($language, $page) {
    
    global $db;
    
    // SQL-Abfrage
    $sql = "SELECT phrase, text FROM " . table_prefix . "languages WHERE language = ? AND page = ?";
    
    // Abfrage ausführen und alle Ergebnisse holen
    $result = $db->Execute($sql, array($language, $page));
    
    // Leeres Array erstellen
    $array = array();
    
    // Ergebnisse durchgehen und in das Array packen
    while (!$result->EOF) {
        $phrase = $result->fields['phrase'];
        $text = $result->fields['text'];
        
        // Array im gewünschten Format erstellen
        $array[$page][$phrase] = $text;
        
        // Nächsten Datensatz abrufen
        $result->MoveNext();
    }
    
    // Ergebnis-Array zurückgeben
    return $array;
}

/*function cryptPasswd($passwd, $salt = '')
{
    if (strlen($salt) < 16) {
        $salt = zufallstring(16, WITH_NUMBERS | WITH_SPECIAL_CHARACTERS);
    }
    $passwd = hash('sha256', $passwd.$salt).':'.$salt;    
    return $passwd;
}
*/
/*
 * Umgeschriebene Function
 */
function cryptPasswd(string $passwd, string $salt = ''): string
{
    if (strlen($salt) < 16) {
        $salt = zufallstring(16, WITH_NUMBERS | WITH_SPECIAL_CHARACTERS);
    }
    
    // Der Passwort-Hash wird generiert und mit dem Salt kombiniert
    $hashedPasswd = hash('sha256', $passwd . $salt) . ':' . $salt;
    
    return $hashedPasswd;
}
/*
if (!defined('ONLY_LETTERS')) { define('ONLY_LETTERS',0); }
if (!defined('WITH_NUMBERS')) { define('WITH_NUMBERS', 1); }
if (!defined('WITH_SPECIAL_CHARACTERS')) { define('WITH_SPECIAL_CHARACTERS', 2); }*/
/**
 * Erzeugt einen Zufallsstring
 *
 * Erzeugt aus Vorgaben einen Zufallsstring
 *@autor finke
 *@return string Zufalsstring
 */
/*
function zufallstring($size = 20, $url = ONLY_LETTERS){
    mt_srand();
    $pool = 'abcdefghijklmnopqrstuvwxyz';
    $pool .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if($url & WITH_SPECIAL_CHARACTERS){
        $pool .= ',.-;:_#+*~!$%&/()=?';
    }
    if($url & WITH_NUMBERS){
        $pool .='0123456789';
    }
    $pool_size = strlen($pool);
    $salt ='';
    for($i = 0;$i<$size; $i++){
        $salt .= $pool[mt_rand(0, $pool_size - 1)];
    }
    return $salt;
}*/

if (!defined('ONLY_LETTERS')) { define('ONLY_LETTERS', 0); }
if (!defined('WITH_NUMBERS')) { define('WITH_NUMBERS', 1); }
if (!defined('WITH_SPECIAL_CHARACTERS')) { define('WITH_SPECIAL_CHARACTERS', 2); }

function zufallstring(int $size = 20, int $url = ONLY_LETTERS): string
{
    mt_srand();
    $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    if ($url & WITH_SPECIAL_CHARACTERS) {
        $pool .= ',.-;:_#+*~!$%&/()=?';
    }
    if ($url & WITH_NUMBERS) {
        $pool .= '0123456789';
    }

    $pool_size = strlen($pool);
    $salt = '';

    for ($i = 0; $i < $size; $i++) {
        $salt .= $pool[mt_rand(0, $pool_size - 1)];
    }

    return $salt;
}

function neuigkeit($art, $icon, $spieler_id, $inhalt)
{
    global $db, $spiel;
    $datum = time();
    return $db->execute("INSERT INTO " . table_prefix . "neuigkeiten (datum,
                                                           art,
                                                           icon,
                                                           inhalt,
                                                           spieler_id,
                                                           spiel_id,
                                                           sicher) 
                                                           values 
                                                          ('". $datum ."',
                                                           '" . $art . "',
                                                           '" . $icon ."',
                                                           '" . $inhalt . "',
                                                           '" . $spieler_id . "',
                                                           '". $spiel . "',
                                                           '1')");
}

function nick(int $userid): string
{
    global $db;
    $nickname = $db->getOne("SELECT nick FROM " . table_prefix ."user where id = ? order by id",array(intval($userid)));
    return $nickname;    
}

function int_post($key)
{
    if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
        return intval($_POST[$key]);
    }

    return false;
}

function int_get($key)
{
    if (isset($_GET[$key]) && is_numeric($_GET[$key])) {
        return intval($_GET[$key]);
    }

    return false;
}

function str_post($key, $mode)
{
    if (isset($_POST[$key]) && !is_array($_POST[$key])) {
        $nl2br = $mode == 'SQLSAFE' && ($key == 'thema' || $key == 'beitrag' || $key == 'offenbarung');

        return safe_strval($_POST[$key], $mode, $nl2br);
    }

    return false;
}

function str_get($key, $mode)
{
    if (isset($_GET[$key]) && !is_array($_GET[$key])) {
        $nl2br = $mode == 'SQLSAFE' && ($key == 'thema' || $key == 'beitrag' || $key == 'offenbarung');

        return safe_strval($_GET[$key], $mode, $nl2br);
    }

    return false;
}
/*
 * Function bleibt nur noch so lange bis alles überarbeitet wurde
 */
function safe_strval($value, $mode, $nl2br = false)
{
    switch ($mode){
        case 'NONE':
            return $value;
            break;

        case 'SQLSAFE':
            //$retvar = stripslashes($value);
            if ($nl2br) {
                $retvar = $retvar;
            }
            //$retvar = strtr($retvar, array("\x00" => "\\x00", "\x1a" => "\\x1a", "\n" => "\\n", "\r" => "\\r", "\\" => "\\\\", "'" => "\'", "\"" => "\\\"")); // nur escapen
            //$retvar = strtr($retvar, array("\x00" => '\\x00', "\x1a" => '\\x1a', "\n" => '\\n', "\r" => '\\r', '\\' => '', "'" => '', '"' => '')); // entfernt: " ' \
            return $retvar;
            break;

        case 'SHORTNAME':
            //if (!preg_match('/[^0-9A-Za-z_]/', $value)) {
                return $value;
            //}
            break;

        case 'PATHNAME':
            //if (!preg_match('/[^0-9A-Za-z_\/\.:\-]/', $value)) {
                return $value;
            //}
            break;

        default:
            //if (!preg_match('/[^0-9A-Za-z_&:;\-]/', $value)) {
                return $value;
           // }
    }

    return false;
}

function compressed_output()
{
    $encoding = getenv('HTTP_ACCEPT_ENCODING');
    $useragent = getenv('HTTP_USER_AGENT');
    $method = trim(getenv('REQUEST_METHOD'));
    $msie = preg_match('=msie=i', $useragent);
    $gzip = preg_match('=gzip=i', $encoding);
    if ($gzip && ($method != 'POST' or !$msie)) {
        ob_start('ob_gzhandler');
    } else {
        ob_start();
    }
}


function sektor($x, $y) {
    $sektor_x = round(($x/250)+0.5);
    $sektor_y = round(($y/250)+0.5);
    return chr(64+$sektor_x).$sektor_y;
}

function rasse_laden($filename) {
    $data = file_get_contents($filename);
    if($data) {
        $rows = explode("\n", $data);
        $attribute1 = explode(":", $rows[2]);
        $attribute2 = explode(":", $rows[4]);
        return array(
            'temperatur' => intval($attribute1[0]),
            'steuern' => (float)$attribute1[1],
            'minen' => (float)$attribute1[2],
            'bodenangriff' => (float)$attribute1[3],
            'bodenverteidigung' => (float)$attribute1[4],
            'fabriken' => (float)$attribute1[5],
            'pklasse' => intval($attribute1[6]),
            'assgrad' => intval($attribute2[0]),
            'assart' => intval($attribute2[1])
        );
    } else {
        return false;
    }
}
function neuigkeiten($art,$icon,$spieler_id,$inhalt,$werte) {
    global $db,$spiel;	
    $datum=time();    
    $search=array();
    $anzahl=sizeof($werte);
    for ($n=1;$n<=$anzahl;$n++) {
        $platzhalter='{'.$n.'}';
        $search[]=$platzhalter;        
    }
    $inhalt=str_replace($search,$werte,$inhalt);
    $sqli = "INSERT INTO " . table_prefix . "neuigkeiten (datum,art,icon,inhalt,spieler_id,spiel_id) values 
                              (?, ?, ?, ?, ?, ?)";
    $db->execute($sqli, array($datum, $art, $icon, $inhalt, $spieler_id, $spiel));
}
function sichtaddieren($sicht_alt,$sicht_neu) {
    if ((substr($sicht_alt,0,1)=="1") or (substr($sicht_neu,0,1)=="1")) { $s1="1"; } else { $s1="0"; }
    if ((substr($sicht_alt,1,1)=="1") or (substr($sicht_neu,1,1)=="1")) { $s2="1"; } else { $s2="0"; }
    if ((substr($sicht_alt,2,1)=="1") or (substr($sicht_neu,2,1)=="1")) { $s3="1"; } else { $s3="0"; }
    if ((substr($sicht_alt,3,1)=="1") or (substr($sicht_neu,3,1)=="1")) { $s4="1"; } else { $s4="0"; }
    if ((substr($sicht_alt,4,1)=="1") or (substr($sicht_neu,4,1)=="1")) { $s5="1"; } else { $s5="0"; }
    if ((substr($sicht_alt,5,1)=="1") or (substr($sicht_neu,5,1)=="1")) { $s6="1"; } else { $s6="0"; }
    if ((substr($sicht_alt,6,1)=="1") or (substr($sicht_neu,6,1)=="1")) { $s7="1"; } else { $s7="0"; }
    if ((substr($sicht_alt,7,1)=="1") or (substr($sicht_neu,7,1)=="1")) { $s8="1"; } else { $s8="0"; }
    if ((substr($sicht_alt,8,1)=="1") or (substr($sicht_neu,8,1)=="1")) { $s9="1"; } else { $s9="0"; }
    if ((substr($sicht_alt,9,1)=="1") or (substr($sicht_neu,9,1)=="1")) { $s10="1"; } else { $s10="0"; }
    $sicht=$s1.$s2.$s3.$s4.$s5.$s6.$s7.$s8.$s9.$s10;
    return $sicht;
}
function platz_schiffe($wert) {
  global $spieler_schiffe_c;
  $zahl=1;
  for ($mn=1;$mn<11;$mn++) {
    if ($spieler_schiffe_c[$mn]>$wert) { $zahl++; }
  }
  return $zahl;
}
function platz_basen($wert) {
  global $spieler_basen_c;
  $zahl=1;
  for ($mn=1;$mn<11;$mn++) {
    if ($spieler_basen_c[$mn]>$wert) { $zahl++; }
  }
  return $zahl;
}
function platz_planeten($wert) {
  global $spieler_planeten_c;
  $zahl=1;
  for ($mn=1;$mn<11;$mn++) {
    if ($spieler_planeten_c[$mn]>$wert) { $zahl++; }
  }
  return $zahl;
}
function platz($wert) {
  global $spieler_gesamt_c;
  $zahl=1;
  for ($mn=1;$mn<11;$mn++) {
    if ($spieler_gesamt_c[$mn]<$wert) { $zahl++; }
  }
  return $zahl;
}
// Function: beam_das
// Declaration: function beam_das($id_a_p,$typ_a_p,$id_b_p,$typ_b_p,$was_p,$wieviel_p)
// Description:
//   Beamt angegebene Ware in angegebener von A nach B
//   Dabei wird jedoch nie mehr gebeamt, als auf A vorhanden ist
//   und auf B passt
//   Es wird nicht ueberprueft, ob die Objekte nah genug beieinander sind
// Parameters:
//   $id_a_p    Id des Objekts, von dem gebeamt wird
//   $typ_b_p    Typ des Objekts, von dem gebeamt wird ("s" oder "p")
//   $id_b_p    Id des Objekts, auf das gebeamt wird
//   $typ_b_p    Typ des Objekts, auf das gebeamt wird ("s" oder "p")
//   $was_p    Ware, die gebeamt werden soll
//       Planet    Schiff
//       lemin    fracht_lemin
//       min1    fracht_min1
//       min2    fracht_min2
//       min3    fracht_min3
//       vorrat    fracht_vorrat
//       cantox    fracht_cantox
//       kolonisten  fracht_leute
//      Es reicht aus, die Bezeichnungen aus der Planeten-
//      Tabelle zu benutzen
//   $wieviel_p    Anzahl Einheiten, die gebeamt werden sollen.
// Return values:
//   0-n  Beamen erfolgreich, returnwert ist tatsaechlich gebeamte Menge
//   -1   id von A nicht eindeutig oder nicht gefunden
//   -2   id von B nicht eindeutig oder nicht gefunden
//
function beam_das($id_a_p,$typ_a_p,$id_b_p,$typ_b_p,$was_p,$wieviel_p)
{
    global $db,$debug_beamen;
    if($debug_beamen)
    {
        print "ID von A: $id_a_p<br>\n";
        print "Typ von A: $typ_a_p<br>\n";
        print "ID von B: $id_b_p<br>\n";
        print "Typ von B: $typ_b_p<br>\n";
        print "Was: $was_p<br>\n";
        print "Wieviel: $wieviel_p<br>\n";
    }
    // Diesen Trivialfall loesen wir ohne Datenbank-Zugriff
    if(!$wieviel_p || ($wieviel_p==0)) { return 0; }
    // Datensatz von A holen
    if($typ_a_p=="p") { 
        $table_a = table_prefix."planeten";         
    } else { 
        $table_a= table_preix . "schiffe";         
    }
    if(!($query_ret=$db->execute("SELECT * FROM " . $table_a . " WHERE id='" . $id_a_p . "' ")))
    { return -1; }
    if($query_ret->RecordCount() !=1) { 
        return -1;         
    }
    $array_a=$db->getArray($query_ret);
    // Datensatz von B holen
    if($typ_b_p=="p") { 
        $table_b = table_prefix ."planeten";         
    } else { 
        $table_b = table_prefix . "schiffe";         
    }
    if(!($query_ret=$db->execute("SELECT * FROM " . $table_b . " WHERE id='" . $id_b_p . "' "))) { 
        return -1;         
    }
    if($query_ret->RecordCount() !=1) { return -2; }
    $array_b=$db->getArray($query_ret);
    // Variablenfummelei: die Parameter sind die Spaltenbezeichnungen fuer
    //          die Planetentabelle
    //        fuer Schiffe muss umgesetzt werden.
    if($typ_a_p=="p") { 
        $was_auf_a=$was_p; 
    } else {
      switch($was_p)
      {
      case "lemin":  $was_auf_a="lemin";
      break;
      case "min1":  $was_auf_a="fracht_min1";
      break;
      case "min2":  $was_auf_a="fracht_min2";
      break;
      case "min3":  $was_auf_a="fracht_min3";
      break;
      case "vorrat":  $was_auf_a="fracht_vorrat";
      break;
      case "cantox":  $was_auf_a="fracht_cantox";
      break;
      case "kolonisten":$was_auf_a="fracht_leute";
      break;
      }
    }
    if($typ_b_p=="p") { 
        $was_auf_b=$was_p;         
    } else {
      switch($was_p)
      {
      case "lemin":  $was_auf_b="lemin";
      break;
      case "min1":  $was_auf_b="fracht_min1";
      break;
      case "min2":  $was_auf_b="fracht_min2";
      break;
      case "min3":  $was_auf_b="fracht_min3";
      break;
      case "vorrat":  $was_auf_b="fracht_vorrat";
      break;
      case "cantox":  $was_auf_b="fracht_cantox";
      break;
      case "kolonisten":$was_auf_b="fracht_leute";
      break;
      }
    }
    $wieviel=$wieviel_p;
    // Ueberpruefen, ob genug $was_p auf A vorhanden ist
    // ggf. Beam-Menge anpassen
    if($array_a[$was_auf_a]<$wieviel_p)
    {
        $wieviel=$array_a[$was_auf_a];
    }
    if($debug_beamen) { 
        print "wieviel: $wieviel<br>\n";         
    }
    // Sinnlos, ohne was Beambares weiter zu machen
    if(!$wieviel || ($wieviel==0)) { 
        return 0;         
    }
    // Hier vielleicht eine Warnung ausgeben
    // Ueberpruefen, ob $was_p noch in B rein passt
    // ggf. Menge anpassen
    //
    // Fuer Planeten muss man nix pruefen, da passt alles drauf.
    // Bei Schiffen den Frachtraum mit der Gesamtfracht vergleichen
    // bzw die Menge mit dem maximalen Tankinhalt
    if($typ_b_p=="s")
    {
    if($was_p=="lemin")
    {
        $passt_max=$array_b["leminmax"]-$array_b["lemin"];
        if($wieviel>$passt_max)
        {
            $wieviel=$passt_max;
        }
    }
    elseif($was_p=="cantox")
    { // Nix machen, Cantox passt immer
    } else {
        $gesamtfracht=0;
        // Cantox wiegt nix, Leute muss man anders behandeln
        foreach(array("fracht_min1","fracht_min2","fracht_min3","fracht_vorrat")
            as $fracht)
        { $gesamtfracht+=$array_b[$fracht]; }
        $gesamtfracht+=round($array_b["fracht_leute"]/100);
        // Bei Kolonisten muss man den aufgewandten Frachtraum
        // durch 100 teilen
        if($was_p=="kolonisten")
        { $gewicht=round($wieviel/100); }
        else
        { $gewicht=$wieviel; }
            if(($gesamtfracht+$gewicht)>$array_b["frachtraum"])
        {
            $wieviel=$array_b["frachtraum"]-$gesamtfracht;
        // Bei Kolonisten dieses Ergebnis mal hundert nehmen
        if($was_p=="kolonisten") { $wieviel*=100; }
        }
    }
    }
    if($debug_beamen) { print "wieviel: $wieviel<br>\n"; }
    // Datenbank updaten
    $query_str= "UPDATE $table_a SET " . $was_auf_a . "= " . $was_auf_a . "-" . $wieviel . " WHERE id='" . $id_a_p ."'";
    if($debug_beamen)
    { 
        print "<p>$query_str<br>\n"; 
    }
    else
    { 
        $db->execute($query_str); 
    }
    if($typ_b_p=="p"){
        $besitzera=$array_a["besitzer"];
        $besitzerb=$array_b["besitzer"];
        if($besitzera==$besitzerb){
            $query_str= "UPDATE ". $table_b . " SET " . $was_auf_b ."=" . $was_auf_b ."+" . $wieviel . " WHERE id='" . $id_b_p ."'";
            }else{
                if($was_auf_b=="kolonisten"){
                        $query_str= "UPDATE $table_b SET kolonisten_new=kolonisten_new+" . $wieviel . ", kolonisten_spieler='" . $besitzera ."' WHERE id='" . $id_b_p . "'";
                    }else{
                        $query_str= "UPDATE $table_b SET " . $was_auf_b . "=" . $was_auf_b . "+" . $wieviel . " WHERE id='" . $id_b_p . "'";
                    }
                }
        }else{
        $query_str= "UPDATE " . $table_b . " SET " . $was_auf_b . "=" . $was_auf_b . "+" . $wieviel. " WHERE id='" . $id_b_p . "'";
        }
    if($debug_beamen){ 
        print "$query_str<br>\n";         
    } else { 
        $db->execute($query_str);         
    }
    return $wieviel;
}

// Beamen von Schiff nach Planet
function beam_s_p($id_a_p,$id_b_p,$was_p,$wieviel_p)
{
    return beam_das($id_a_p,"s",$id_b_p,"p",$was_p,$wieviel_p);
}

// Beamen von Planet nach Schiff
function beam_p_s($id_a_p,$id_b_p,$was_p,$wieviel_p)
{
    return beam_das($id_a_p,"p",$id_b_p,"s",$was_p,$wieviel_p);
}
// Beamen von Schiff nach Schiff
function beam_s_s($id_a_p,$id_b_p,$was_p,$wieviel_p)
{
    return beam_das($id_a_p,"s",$id_b_p,"s",$was_p,$wieviel_p);
}

//kleine funktionen fuer wahrscheinlichkeitsberechung
function spionerfolg($wahrscheinlichkeit, $stufe) {
  $erg = mt_rand(0,100) + $stufe * 5;
    if($erg > 100) { 
        $erg = 100; 
    }
    if($erg > (100 - $wahrscheinlichkeit)) { 
        return true; 
    }
    return false;
}

function spionerfahrung($wahrscheinlichkeit, $stufe) {
    $xp = floor(((100 - $wahrscheinlichkeit) - ($stufe * 5)) * 1.2);
    if($xp < 5) { 
        $xp = 5; 
    }
    return $xp;
}
function spionstufe($xp) {
    $xp_benoetigt = 0;
    for($stufe=0;$stufe<10;$stufe++) {
        $xp_benoetigt += (($stufe+1)*100);
        if($xp < $xp_benoetigt) { 
            return $stufe; 
        }
    }
    return 10;
}

function tlquad($tl){
        return $tl*$tl*100;
    }