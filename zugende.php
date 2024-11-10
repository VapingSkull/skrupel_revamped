<?php
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST));
$langzugende = get_phrasen('de','zugende');
$fuid = intval($params["fu"]);
$sid = $params['sid'];
$uid = $params['uid'];
$smarty->assign('uid', $uid);
$smarty->assign('sid', $sid);
$smarty->assign('servername', servername);
set_header();
switch ($fuid)
{
    case 1:
       /*
        * fu:1 Zugende Hauptmenu
        */       
       $weitere = $db->getOne("SELECT count(*) AS total FROM skrupel_spiele WHERE ? IN (spieler_1,spieler_2,spieler_3,spieler_4,spieler_5,spieler_6,spieler_7,spieler_8,spieler_9,spieler_10) and id<>? and phase = '0'",array($spieler_id,$spiel));
       $smarty->assign('weitere', $weitere);              
       $smarty->assign('zug_abgeschlossen', $zug_abgeschlossen);
       $smarty->assign('spieler_raus', $spieler_raus);
       $smarty->assign('galaxiesprung', $langzugende['zugende']['galaxiesprung']);
       $smarty->assign('logout', $langzugende['zugende']['logout']);
       $smarty->assign('zugabschliessen', $langzugende['zugende']['zugabschliessen']);
       $smarty->display('zugende/zugfu1.tpl');       
       break;
    case 2:
        /*
         * fu:2 Spiel verlassen
         */
        include (includes . "inc.check.php");    
        $Db->execute("UPDATE " . table_prefix . "user set uid='' where id = ?", array($spieler_id));
        $nachricht = $spieler_name.' '.$langzugende['zugende']['verlassen'];
        $aktuell = time();
        $Db->execute("INSERT INTO " . table_prefix . "chat (spiel,datum,text,an,von,farbe) VALUES (?,?,?,'0','System','000000')",array($sapiel,$aktuell,$nachricht));
        $backlink = servername . "index.php?sprache=". $spieler_sprache;
        header ("Location: $backlink");
        break;
    case 3:
        /*
         * fu:3 Zug abschliessen
         */
        include (includes . 'inc.check.php');    
        $spalte = "spieler_{$spieler}_zug";
        $spieler_zug_c[$spieler] = 1;
        $Db->execute("UPDATE " . table_prefix ."spiele SET " . $spalte . " = '1' WHERE sid = ?",array($sid));
        $spiel_extend = $Db->getOne("SELECT extend FROM ". table_prefix ."info");    
        $fertig = 0;
        for($i=1; $i<=10; $i++) {
            if($spieler_zug_c[$i]==1) { 
                $fertig++;
            }
        }    
        if($fertig>=$spieleranzahl) {
            $backlink = servername . "inhalt/zugende.php?fu=6&uid=" . $uid . "&sid=" . $sid;
        } else {
            $backlink = servername . "inhalt/zugende.php?fu=9&uid=" . $uid . "&sid=" . $sid;
        }
        header ("Location: $backlink");
        break;
    case 4:
        /*
         * fu:4 Nachricht Zug abgeschlossen
         */               
        $smarty->assign('abgeschlossen', $langzugende['zugende']['abgeschlossen']);
        $smarty->display('zugende/zugfu4.tpl');
        break;
    case 5:
         /*
          * fu:5 Zug berechnen
          */         
         $fertig = 0;
         for($i=1; $i<=10; $i++) {
            if($spieler_zug_c[$i]==1) {
            $fertig++;
            }
         }
         if($fertig>=$spieleranzahl) {
         $lasttick = time();
         $Db->execute("UPDATE " . table_prefix . "spiele SET lasttick = ?, spieler_1_zug='0', spieler_2_zug='0', spieler_3_zug='0', spieler_4_zug='0', spieler_5_zug='0', spieler_6_zug='0', spieler_7_zug='0', spieler_8_zug='0', spieler_9_zug='0', spieler_10_zug='0' WHERE sid = ?",
                                                 array($lasttick,$sid));
         include (includes .'inc.host.php');
        }
        $smarty->assign('servername', servername);
        $smarty->assign('wurdenausgewertet', $langzugende['zugende']['wurdenausgewertet']);        
        $fuu=1;        
        $smarty->diesplay('zugende/zugfu5.tpl');
        break;
    case 6:
        /*
         * fu:6 Zug wird berechnet Nachricht und Redirect {{{
         */        
        $smarty->assign('wirdberechnet', $langzugende['zugende']['wirdberechnet']);
        $smarty->display('zugende/zugfu6.tpl');
        break;
    case 7:
         /*
          *fu:7 Galaxiesprung, Galaxiewahl
          */        
        $smarty->assign('galaxiesprung', $langzugende['zugende']['galaxiesprung']);
        $smarty->assign('sprungwohin', $langzugende['zugende']['sprungwohin']);
        $smarty->assign('sprungdurchfuehren', $langzugende['zugende']['sprungdurchfuehren']);
        $selneuesspiel = "<select name=\"neuesspiel\">";
        $zeiger2 = "SELECT * FROM " . table_prefix . "spiele use index (id_spieler_index) WHERE ? IN (spieler_1,spieler_2,spieler_3,spieler_4,spieler_5,spieler_6,spieler_7,spieler_8,spieler_9,spieler_10) 
                                                                                                  and id<>? and phase='0'";
        $rows = $db->execute($zeiger2,array($spieler_id,$spiel));
        if ($rows->RecordCount()>0) {
            while ($array = $rows->FetchRow()) {
                   $spielneuid=$array["id"];
                   $spielneuname=$array["name"];
                   $farbe = '#444444';
                   for($i=1; $i<=10; $i++) {
                       $tmpstr = 'spieler_'.$i;
                       if($spieler_id == $array[$tmpstr] && $array[$tmpstr.'_zug']==0 && $array[$tmpstr.'_raus']==0) {
                          $farbe = '#aa0000';
                          }
                        }                                        
                    $selneuesspiel .= "\n<option value=\"".$spielneuid."\" style=\"background-color: ".$farbe."\">".$spielneuname."</option>";
                   }
                 }
        $selneuesspiel .="</select>";
        $smarty->assign('selneuesspiel', $selneuesspiel);
        $smart->display('zugende/zugfu7.tpl');
        break;
    case 8:
        /*
         * //fu:8 Galaxiesprung durchfuehren 
         */
        $neuesspiel = $params['neuesspiel'];
        $sql = "SELECT sid FROM " . table_prefix . "spiele WHERE id = ?";
        $rows = $db->execute($sql,array($neuesspiel));    
        if ($rows->RecordCount()==1) {
        $sidneu = $db->getOne($sql);          
        }
        $smarty->assign('sidneu', $sidneu);
        $smarty->assign('spunginitialisiert', $langzugende['zugende']['spunginitialisiert']);
        $smarty->display('zugende/zugfu8.tpl');
    case 9:
        /*
         * fu:9 Zug abschliessen zwischenschritt fuer langsame server oO 
         */
        include (includes .'inc.check.php');    
        $spalte = "spieler_{$spieler}_zug";
        $spieler_zug_c[$spieler] = 1;
        $db->execute("UPDATE " . table_prefix . "spiele SET $spalte = '1' WHERE sid = ? order by sid",array($sid));
        $fertig = 0;
        for($i=1; $i<=10; $i++) {
            if($spieler_zug_c[$i]==1) {
                $fertig++;
            }
        }
        if($fertig>=$spieleranzahl) {
        $backlink = servername . "zugende.php?fu=6&uid=". $uid . "&sid=" . $sid;
    } else {
        $backlink = servername . "zugende.php?fu=4&uid=" . $uid . "&sid=" .$sid;
    }
    header ("Location: $backlink");
}
unset($langzugende);
