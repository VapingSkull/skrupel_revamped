<?php
require_once inhalt_dir . 'mysql.php'; // THIS IS ONLY A TEMPORARY WORKAROUND
include_once inhalt_dir . 'inc.common.php';
/*
:noTabs=false:indentSize=4:tabSize=4:folding=explicit:collapseFolds=1:
*/
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
            INPUT_REQUEST => $_REQUEST,
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
function get_phrasen($language, $page) {
    
    global $Db;
    
    // SQL-Abfrage
    $sql = "SELECT phrase, text FROM " . table_prefix . "languages WHERE language = ? AND page = ?";
    
    // Abfrage ausführen und alle Ergebnisse holen
    $result = $Db->Execute($sql, array($language, $page));
    
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

function get_execution_time()
{
    static $time_start;
    
    $time = microtime(true);
    
    // Just starting timer, init and return
    if(!$time_start)
    {
        $time_start = $time;
        return;
    }
    // Timer has run, return execution time
    else
    {
        $total = $time-$time_start;
        if($total < 0) $total = 0;
        $time_start = 0;
        return $total;
    }
}

/*
 * Geniert das $lang array mit den gewünschten phrasen.
 */
function get_langphrase ($language, $phrase, $page){
    global $Db;
    $sql = "select text from skrupel_languages use index (language, page, phrase) where language = '". $language . "' and page='".$page."' and phrase = '".$phrase."'"; 
    $text = $Db->getOne($sql);
    return $text;
}



function nick($userid)
{
    /*global $Db, $db, $skrupel_user, $spiel;

    $zeiger3 = @mysql_query("SELECT nick, id FROM $skrupel_user WHERE id= '" . $userid . "' order by id");
    $array3 = @mysql_fetch_array($zeiger3);

    return $array3['nick'];*/
    global $Db;
    $nickname = $Db->getOne("SELECT nick FROM " . table_prefix ."user where id = '". intval($userid) ."' order by id");
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

function safe_strval($value, $mode, $nl2br = false)
{
    switch ($mode){
        case 'NONE':
            return $value;
            break;

        case 'SQLSAFE':
            $retvar = stripslashes($value);
            if ($nl2br) {
                $retvar = nl2br($retvar);
            }
            //$retvar = strtr($retvar, array("\x00" => "\\x00", "\x1a" => "\\x1a", "\n" => "\\n", "\r" => "\\r", "\\" => "\\\\", "'" => "\'", "\"" => "\\\"")); // nur escapen
            $retvar = strtr($retvar, array("\x00" => '\\x00', "\x1a" => '\\x1a', "\n" => '\\n', "\r" => '\\r', '\\' => '', "'" => '', '"' => '')); // entfernt: " ' \
            return $retvar;
            break;

        case 'SHORTNAME':
            if (!preg_match('/[^0-9A-Za-z_]/', $value)) {
                return $value;
            }
            break;

        case 'PATHNAME':
            if (!preg_match('/[^0-9A-Za-z_\/\.:\-]/', $value)) {
                return $value;
            }
            break;

        default:
            if (!preg_match('/[^0-9A-Za-z_&:;\-]/', $value)) {
                return $value;
            }
    }

    return false;
}

/**
 * Erzeugt einen Passworthash aus password + salt mit SHA256
 * Beim Erstellen eines Hash zur Speicherung leer lassen, beim Abgleich muss der Salt aus der DB genommen werden, um identiche Ergebnisse zu erhalten.
 *
 * @author finke
 * @param string $passwd Zu hashendes Passwort
 * @param string $salt der zum hashen verwendet werden soll
 * @return string Passwort hash und salt durch ein ":" getrennt; Achtung: immer nur nach dem ersten ":" trennen. Im Hash selber kann keines vorkommen,im Salt schon. Z.B.: explode(':',cryptPasswd('Mein Passwort'), 2);
 */
function cryptPasswd($passwd, $salt = '')
{
    if (strlen($salt) < 16) {
        $salt = zufallstring(16, WITH_NUMBERS | WITH_SPECIAL_CHARACTERS);
    }
    $passwd = hash('sha256', $passwd.$salt).':'.$salt;

    return $passwd;
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

/**
 * Oeffnet eine Datenbankverbindung
 *
 * @param bool new_link erzwingt das Oeffnen einer neuen Verbindung
 * @param int client_flags Kombination aus MYSQL_CLIENT_SSL | MYSQL_CLIENT_COMPRESS |  MYSQL_CLIENT_IGNORE_SPACE | MYSQL_CLIENT_INTERACTIVE
 * @return resource|bool Gibt im Erfolgsfall eine MySQL Verbindungs-Kennung zurueck oder FALSE im Fehlerfall.
 */
function open_db($new_link = false, $client_flags = 0)
{
    global $db_server, $db_name, $db_port, $db_login, $db_password;

    if (empty($db_name) || empty($db_server) || empty($db_login)) {
        return false;
    }

    $conn = mysql_connect($db_server.':'.$db_port, $db_login, $db_password, $new_link, $client_flags);

    if ($conn !== false && mysql_select_db($db_name, $conn)) {
        $GLOBALS['db'] = $conn;

        return $conn;
    } else {
        return false;
    }
}
