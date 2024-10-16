<?php
include ("includes/config.php");

//require_once ("inc.conf.php");
//require_once (inhalt_dir . "inc.hilfsfunktionen.php");

/*
 * Language Phrase werden aus der Datenbank geholt
 */
/*
 * Vorerst nur deutsche Sprache. Integration weitere Sprachen ohne Probleme möglich. 
 * Es muß hier nur die Sprache aus der Datenbank geholt werden, díe der Spieler dann in seinem Profil einstellen kann. (wenn es soweit ist)
 */
$lang = get_phrasen('de', 'index');
$smarty->assign('lang_out',$lang);


if (isset($params)){
    //compressed_output();
    $zeiger = "SELECT version, extend, serial FROM " . table_prefix . "info";
    $array = $db->getRow($zeiger);
    $spiel_version = $array['version'];
    $spiel_extend  = $array['extend'];
    $spiel_serial  = $array['serial'];
    $smarty->assign('spiel_version', $spiel_version);
    $smarty->assign('spiel_serial', $spiel_serial);
    $spieler=0;
    
    $login_f  = @$params["login_f"];
    $pass_f    = @$params["passwort_f"];
    $spiel_slot = @$params["spiel_slot"];
    
    /*
     * Login über direkten Link deaktiviert ! Da keine E-Mails mehr vom Skript werden, ist das unötig geworden
     */
    ///////////////////////////////login ueber link
    /*if (($hash_f = $params["hash"]) !== false) {
        $zeiger = "SELECT id,
                                   spieler_1,
                                   spieler_2,
                                   spieler_3,
                                   spieler_4,
                                   spieler_5,
                                   spieler_6,
                                   spieler_7,
                                   spieler_8,
                                   spieler_9,
                                   spieler_10,
                                   spieler_1_hash,
                                   spieler_2_hash,
                                   spieler_3_hash,
                                   spieler_4_hash,
                                   spieler_5_hash,
                                   spieler_6_hash,
                                   spieler_7_hash,
                                   spieler_8_hash,
                                   spieler_9_hash,
                                   spieler_10_hash
	                       FROM " . table_prefix . "spiele WHERE
                               spieler_1_hash = '" . $hash_f . "' or
                               spieler_2_hash = '" . $hash_f . "' or
                               spieler_3_hash = '" . $hash_f . "' or
                               spieler_4_hash = '" . $hash_f . "' or
                               spieler_5_hash = '" . $hash_f . "' or
                               spieler_6_hash = '" . $hash_f . "' or
                               spieler_7_hash = '" . $hash_f . "' or
                               spieler_8_hash = '" . $hash_f . "' or
                               spieler_9_hash = '" . $hash_f . "' or
                               spieler_10_hash = '" . $hash_f . "'";
        $zeiger = $db->execute($zeiger);
        $row = $zeiger->RecordCount();
        if ($row ==1) {
            $array = $db->getArray($zeiger);
            $spiel_slot = $array['id'];
            for ($m=1; $m<=10; $m++) {
                $tmpstr = 'spieler_'.$m;
                if ($array[$tmpstr.'_hash']==$hash_f) {
                    $benutzer_id = $array[$tmpstr];
                    $zeiger = "SELECT nick,passwort FROM " . table_prefix . "user where id = '" . $benutzer_id . "' order by id";
                    $array = $db->getRow($zeiger);
                    $login_f = $array['nick'];
                    $pass = $array['passwort'];
                    break;
                }
            }
        }
    }*/
    
    ///////////////////////////////login
    $fehler = "";
    if (!(empty($login_f) || (empty($pass_f) && empty($pass)))) {
        if(empty($pass)){
            $sql_zeiger = "SELECT salt FROM " . table_prefix . "user WHERE nick = '" . $login_f . "' order by nick";
            $zeiger = $db->execute($sql_zeiger);
            
            if($zeiger = $db->getRow($sql_zeiger)) {
                $salt = $zeiger['salt'];
                $pass_f = cryptPasswd($pass_f, $salt);
                $pass_f = explode(':',$pass_f, 2);
                $pass = $pass_f[0];                
            } else {                
                $fehler = $lang['index']['falscheZugangsdaten'];
            }
        }
        $zeigersql = "SELECT * FROM " . table_prefix . "user WHERE nick='" . $login_f . "' and passwort='" . $pass . "' order by nick";
        $rows = $db->execute($zeigersql);
        $anzahl = $rows->RecordCount();
        if ($anzahl==1) {
            $array = $db->getRow($zeigersql);
            $spieler_id = $array['id'];
            $spieler_name = $array['nick'];
            $spieler_sprache = $array['sprache'];
            if ($spieler_sprache=='') {
                $spieler_sprache='de';
            }
            $zeiger2sql = "SELECT * FROM " . table_prefix . "spiele WHERE (spieler_1 = '" . $spieler_id . "'
                                                                 or spieler_2 = '" . $spieler_id . "'
                                                                 or spieler_3 = '" . $spieler_id . "'
                                                                 or spieler_4 = '" . $spieler_id . "'
                                                                 or spieler_5 = '" . $spieler_id . "'
                                                                 or spieler_6 = '" . $spieler_id . "'
                                                                 or spieler_7 = '" . $spieler_id . "'
                                                                 or spieler_8 = '" . $spieler_id . "'
                                                                 or spieler_9 = '" . $spieler_id . "'
                                                                 or spieler_10 = '" . $spieler_id . "')
                                                                 and id = '" . $spiel_slot ."'";
            $rows = $db->execute($zeiger2sql);
            $anzahl2 = $rows->RecordCount();
            if ($anzahl2==1) {
                $array2 = $db->getRow($zeiger2sql);
                $sid   = $array2['sid'];
                $phase = $array2['phase'];
                $spiel = $array2['id'];
                for ($sp=1; $sp<=10; $sp++) {
                    if($spieler_id == $array2['spieler_'.$sp]) {
                        $spieler = $sp;
                    }
                }
                $uid = zufallstring();
                $db->execute("UPDATE " . table_prefix . "user SET uid = '" . $uid . "' WHERE id= '" . $spieler_id ."'");
            } else {
                $fehler = $lang['index']['spielnichtfuerdich'];
            }
        } else {
            $fehler = $lang['index']['falscheZugangsdaten'];
        }
        
    }
    if ($spieler>0)  {
        if ($phase==1) {
            header("Location: " . servername ."inhalt/runde_ende.php?fu=1&spiel=" . $spiel);
            exit;
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $zeiger_temp ="SELECT * FROM " . table_prefix . "spiele WHERE phase = '0' AND id = '" . $spiel_slot . "' ORDER BY id";
        $array22 = $db->getRow($zeiger_temp);
        $autozug  = $array22['autozug'];
        $nebel    = $array22['nebel'];
        $spiel    = $array22['id'];
        $module   = @explode(':', $array22['module']);
        $lasttick = $array22['lasttick'];
        $spieleranzahl = $array22['spieleranzahl'];
        $ziel_id   = $array22['ziel_id'];
        $ziel_info = $array22['ziel_info'];
        $aktuell = time();
        $spieler_1=$array22["spieler_1"];
        $spieler_2=$array22["spieler_2"];
        $spieler_3=$array22["spieler_3"];
        $spieler_4=$array22["spieler_4"];
        $spieler_5=$array22["spieler_5"];
        $spieler_6=$array22["spieler_6"];
        $spieler_7=$array22["spieler_7"];
        $spieler_8=$array22["spieler_8"];
        $spieler_9=$array22["spieler_9"];
        $spieler_10=$array22["spieler_10"];
        for($sp=1; $sp<=10; $sp++) {
            $tmpstr = 'spieler_'.$sp;
            $spieler_id_c[$sp]    = $array22[$tmpstr];
            $spieler_ziel_c[$sp]  = $array22[$tmpstr.'_ziel'];
            $spieler_rasse_c[$sp] = $array22[$tmpstr.'_rasse'];
            $spieler_raus_c[$sp]  = $array22[$tmpstr.'_raus'];
        }
        $plasma_wahr = $array22['plasma_wahr'];
        $plasma_max = $array22['plasma_max'];
        $plasma_lang = $array22['plasma_lang'];
        $piraten_mitte = $array22['piraten_mitte'];
        $piraten_aussen = $array22['piraten_aussen'];
        $piraten_min = $array22['piraten_min'];
        $piraten_max = $array22['piraten_max'];
        $spiel_name = $array22['name'];
        $nebel = $array22['nebel'];
        $runde = $array22['runde'];
        $spieleranzahl = $array22['spieleranzahl'];
        $umfang = $array22['umfang'];
        $aufloesung = $array22['aufloesung'];
        $spiel_out = $array22['oput'];
        if (($autozug>0) and ($runde>1)) {
            $interval = 3600*$autozug;
            if ($aktuell>=$lasttick+$interval) {
                $lasttick=time();
                $db->execute("UPDATE " . table_prefix . "spiele SET lasttick = '" . $lasttick ."' WHERE id = '" . $spiel . "'");
                $main_verzeichnis = '';
                include (includes . 'inc.host.php');
                $db->execute("UPDATE " . table_prefix . "spiele set spieler_1_zug = '0',
                                                          spieler_2_zug = '0',
                                                          spieler_3_zug = '0',
                                                          spieler_4_zug = '0',
                                                          spieler_5_zug = '0',
                                                          spieler_6_zug = '0',
                                                          spieler_7_zug = '0',
                                                          spieler_8_zug = '0',
                                                          spieler_9_zug = '0',
                                                          spieler_10_zug = '0'
                              where id = '" . $spiel . "'");
            }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $nachricht = $spieler_name . " hat das Spiel betreten.";
        $aktuell=time();
        $db->execute("INSERT INTO " . table_prefix . "chat (spiel, datum, text, an, von, farbe) values ('" . $spiel_slot . "', '" . $aktuell . "','" . $nachricht . "', '0', 'System', '000000')");
        $db->execute("UPDATE " . table_prefix . "user set bildpfad = '" . servername . "bilder' where id = '" . $spieler_id . "'");
        ?>
   <!-- <html>
      <head>
        <title>Skrupel - Tribute Compilation V<?php echo $spiel_version?> optimized by SkullCollector</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="Author" content="Bernd Kantoks bernd@kantoks.de">
        <meta name="Author" content="SkullCollector">
        <meta name="robots" content="index">
        <meta name="keywords" content="Skrupel,Online Browsergame">
    	<meta http-equiv="imagetoolbar" content="no">
        <link rel="shortcut icon" href="<?php echo servername;?>favicon.ico" type="image/x-icon"> 
        <link rel="shortcut icon" href="<?php echo servername;?>favicon.ico" type="image/vnd.microsoft.icon">
      </head>
      <frameset framespacing="0" border="false" frameborder="0" rows="41,*,13,107,10">
        <frameset framespacing="0" border="false" frameborder="0" cols="348,*,402">
          <frame name="obenlinks" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=1" target="_self">
          <frame name="obenmitte" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=2" target="_self">
          <frame name="obenrechts" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=3" target="_self">
        </frameset>
        <frameset framespacing="0" border="false" frameborder="0" cols="57,*,7">
          <frameset framespacing="0" border="false" frameborder="0" rows="339,*,40">
            <frame name="mittelinksoben" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/menu.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
            <frame name="mittelinksmitte" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=4" target="_self">
            <frame name="mittelinksunten" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=5" target="_self">
          </frameset>
          <frame name="mittemitte" scrolling="auto" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=100&query=uebersicht_uebersicht.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
          <frameset framespacing="0" border="false" frameborder="0" rows="233,*,146">
            <frame name="mitterechtsoben" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=7" target="_self">
            <frame name="mitterechtssmitte" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=8" target="_self">
            <frame name="mitterechtsunten" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=9" target="_self">
          </frameset>
        </frameset>
        <frameset framespacing="0" border="false" frameborder="0" cols="387,*,364">
          <frame name="mitte2links" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=10" target="_self">
          <frame name="mitte2mitte" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=11" target="_self">
          <frame name="mitte2mitte" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=12" target="_self">
        </frameset>
        <frameset framespacing="0" border="false" frameborder="0" cols="56,*,19">
          <frame name="untenlinks" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/menu.php?fu=2&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
          <frame name="untenmitte" scrolling="auto" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/uebersicht.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
          <frame name="untenrechts" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=15" target="_self">
        </frameset>
        <frameset framespacing="0" border="false" frameborder="0" cols="389,*,361">
          <frame name="unten2links" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=16" target="_self">
          <frame name="unten2mitte" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=17" target="_self">
          <frame name="unten2rechts" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=18" target="_self">
        </frameset>
      </frameset>
      <body>
      </body>
    </html> -->
   <?php
   /* Steht nur noch zu debugzwecken hier. Wenn die Dateien angepaßt sind, erfolgt dann hier alles weitere im Skript  */
   echo "Login erfolgreich";
    
  } else {
    ?>
    <?php
            $zeiger = "SELECT * FROM " . table_prefix . "spiele order by name";            
              $array_out = $db->getArray($zeiger) ;
              foreach ($array_out as $array){
                  $slot_id = $array['id'];
              }
            
                        $aktuell = time();
                        $zeiger2 = "SELECT * FROM " . table_prefix . "spiele order by name";
                        $temp2 = $db->execute($zeiger2);
                        $rowcount = $temp2->RecordCount();
                        if ($rowcount >0) {                          
                         
                                $ar_spiele=array();
                                $array_out = $db->getArray($zeiger2);
                                foreach ($array_out as $array2) {
                               
                                  $str_spielinfo = "";
                                  $slot_id = $array2['id'];                                  
                                  $ar_spiele[] = array(
                                    'id'=>$slot_id,
                                    'name'=>$array2['name'],
                                    'runde'=>$array2['runde'],
                                    'phase'=>$array2['phase']
                                  );
                                  $letztermonat = $array2['letztermonat'];
                                  $lasttick = $array2['lasttick'];
                                  $autozug = $array2['autozug'];
                                  if (strlen($lasttick)==10) { //todo
                                    $datum = date('d.m.y G:i', $lasttick);
                                    $str_spielinfo .= str_replace('{1}',$datum,$lang['index']['letzteauswertung']);
                                    if ($autozug>0) {
                                      $datum_auto = $lasttick+(3600*$autozug);
                                      $datum_auto = date('d.m.y G:i',$datum_auto);
                                      if ($aktuell>=(3600*$autozug)+$lasttick) {
                                        $datum_auto = $lang['index']['nlogin'];
                                      }
                                      $str_spielinfo .= str_replace('{1}',$datum_auto,$lang['index']['autotick']);
                                    }
                                    $str_spielinfo .= "\n";
                                  }
                                  $ar_fehlende = array();
                                  for ($n=1; $n<=10; $n++) {
                                    $tmpstr = 'spieler_'.$n;
                                    if($array2[$tmpstr]>0 && $array2[$tmpstr.'_zug']==0 && $array2[$tmpstr.'_raus']==0) {
                                      $ar_fehlende[] = $array2[$tmpstr];
                                    }
                                  }
                                  if(count($ar_fehlende)>0) {
                                    $str_spielinfo .= $lang['index']['fehlendezuege'];
                                    $qrystr = "SELECT nick FROM " . table_prefix . "user WHERE";
                                    $first = true;
                                    foreach($ar_fehlende as $userid) {
                                      if($first) {
                                        $first = false;
                                        $qrystr .= " id = '" . $userid. "' order by id";
                                      } else {
                                        $qrystr .= " or id = '" . $userid ."' order by id";
                                      }
                                    }
                                    $query_spieler = $db->execute($qrystr);
                                    
                                      while($result_spieler = $db->getArray($query_spieler)) {
                                        $str_spielinfo .= $result_spieler['nick']."\n";
                                      }
                                    
                                  }
                                  $smarty->assign('slot_id', $slot_id);
                                  $smarty->assign('str_spielinfo', $str_spielinfo);
                                  
                                }
                                      $option = "";
                                      foreach($ar_spiele as $spieldaten) {
                                        if ($spieldaten['phase']==0) {
                                          
                                            $option .= '<option value="' . $spieldaten['id'] .'" style="background-color:#444444;">' . $spieldaten['name'] . str_replace('{1}',$spieldaten['runde'],$lang['index']['runde']) .'</option>';
                                          
                                          
                                        } else {
                                            $option .= '<option value="' . $spieldaten['id']. '" style="background-color:#444444;">' . $spieldaten['name'] . $lang['index']['beendet'] .'</option>';
                                          
                                          
                                        }
                                      }
                                      $smarty->assign('option' ,$option);
                                      ?>
                                    
                          <?php
                        } else {
                          ?>
                 
                          <?php
                          echo $lang['index']['keinspiel'];
                        }
                        $smarty->display('index.tpl');
        
                 
        
  
  }
  
} else {
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html>
    <head>
      <title>Skrupel - Tribute Compilation</title>      
      <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
      <link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon">
       <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="Author" content="Bernd Kantoks bernd@kantoks.de">
        <meta name="Author" content="SkullCollector">
        <meta name="robots" content="index">
        <meta name="keywords" content="Skrupel,Online Browsergame">
      <meta http-equiv="imagetoolbar" content="no">
    </head>
    <body text="#000000" scroll="no" bgcolor="#000000" background="<?php echo servername;?>bilder/hintergrund.gif" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
      <center>
        <table border="0" height="100%" cellspacing="0" cellpadding="0">
          <tr><td style="font-family:Verdana;font-size:10px;color:#ffffff;"><nobr><?php echo $lang['index']['fehler']?></nobr></td></tr>
        </table>
      </center>
    </body>
  </html>
  <?php
}
