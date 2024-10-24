<?php
require_once ('../inc.conf.php'); 
require_once (inhalt_dir . 'inc.hilfsfunktionen.php');
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST), filter_struct_utf8(1, $_REQUEST));
$langfile_1 = 'zugende';
$fuid = intval($params["fu"]);

//fu:1 Zugende Hauptmenu {{{
if ($fuid==1) {
    include (inhalt_dir . "inc.header.php");
    $weitere = $Db->getOne("SELECT count(*) AS total FROM " . table_prefix . "spiele WHERE (spieler_1='" . $spieler_id . "' 
                                                                                          or spieler_2='" . $spieler_id . "' 
                                                                                          or spieler_3='" . $spieler_id . "' 
                                                                                          or spieler_4='" . $spieler_id . "' 
                                                                                          or spieler_5='" . $spieler_id . "' 
                                                                                          or spieler_6='" . $spieler_id . "' 
                                                                                          or spieler_7='" . $spieler_id . "' 
                                                                                          or spieler_8='" . $spieler_id . "' 
                                                                                          or spieler_9='" . $spieler_id . "' 
                                                                                          or spieler_10='" . $spieler_id . "') 
                                                                                          and id<>" . $spiel . " 
                                                                                          and phase='0'");    
    ?>
    <body text="#000000" bgcolor="#444444" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" height="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <?php
                    if($weitere > 0) {
                        ?>
                        <td>
                            <center><a href="<?php echo servername;?>inhalt/zugende.php?fu=7&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self"><img src="<?php echo servername;?>bilder/menu/gsprung.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['zugende']['galaxiesprung']?></nobr></a></center>
                        </td>
                        <?php
                    }
                    ?>
                    <td>
                        <center><a href="<?php echo servername;?>inhalt/zugende.php?fu=2&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_top"><img src="<?php echo servername;?>bilder/menu/logout.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['zugende']['logout']?></nobr></a></center>
                    </td>
                    <?php
                    if ($zug_abgeschlossen==0 and $spieler_raus==0){
                        ?>
                        <td>
                            <center><a href="<?php echo servername;?>inhalt/zugende.php?fu=3&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self"><img src="<?php echo servername;?>bilder/menu/abschliessen.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['zugende']['zugabschliessen']?></nobr></a></center>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            </table>
        </center>
        <?php
    include (inhalt_dir . "inc.footer.php");
}
//}}}
//fu:2 Spiel verlassen {{{
if ($fuid==2) {
   // open_db();
    include (inhalt_dir . "inc.check.php");
    include (lang_dir.$spieler_sprache.'/lang.zugende.php');
    $Db->execute("UPDATE " . table_prefix . "user set uid='' where id='" . $spieler_id . "'");
    $nachricht = $spieler_name.' '.$lang['zugende']['verlassen'];
    $aktuell = time();
    $Db->execute("INSERT INTO " . table_prefix . "chat (spiel,datum,text,an,von,farbe) VALUES ('" . $spiel . "','" . $aktuell ."', '" . $nachricht ."','0','System','000000')");
    $backlink = servername . "index.php?sprache=". $spieler_sprache;
    header ("Location: $backlink");
}
//}}}
//fu:3 Zug abschliessen {{{
if ($fuid==3) {
    //open_db();
    include (inhalt_dir . 'inc.check.php');
    include (lang_dir . $spieler_sprache.'/lang.zugende.php');
    $spalte = "spieler_{$spieler}_zug";
    $spieler_zug_c[$spieler] = 1;
    $Db->execute("UPDATE " . table_prefix ."spiele SET " . $spalte . " = '1' WHERE sid='" . $sid . "'");
    $spiel_extend = $Db->getOne("SELECT extend FROM ". table_prefix ."info");    
    if (@intval(substr($spiel_extend,1,1))==1) {
        //Wird nur bei installierter, aktiver KI ausgefuehrt. Es wird zunaechst ueberprueft, ob alle
        //menschlichen Spieler ihren Zug beendet haben, damit die KI ihren Zug berechnen kann. Ist dies
        //der Fall, so wird fuer jeden KI-Spieler im aktuellen Spiel ein KI-Objekt erstellt, welches dann
        //den Zug des jeweiligen Spielers berechnet. 
        include(extend_dir . "ki/ki_basis/zugendeKI.php");
    }
    
    $fertig = 0;
    for($i=1; $i<=10; $i++) {
        if($spieler_zug_c[$i]==1) $fertig++;
    }    
    if($fertig>=$spieleranzahl) {
        $backlink = servername . "inhalt/zugende.php?fu=6&uid=" . $uid . "&sid=" . $sid;
    } else {
        $backlink = servername . "inhalt/zugende.php?fu=9&uid=" . $uid . "&sid=" . $sid;
    }
    header ("Location: $backlink");
}
//}}}
//fu:4 Nachricht Zug abgeschlossen {{{
if ($fuid==4) {
    include (inhalt_dir . 'inc.header.php');
    ?>
        <body text="#000000" bgcolor="#444444" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" height="100%" cellspacing="0" cellpadding="0">
                <tr>
                        <td><nobr><center><?php echo $lang['zugende']['abgeschlossen']?></center></nobr></td>
                </tr>
            </table>
        </center>
        <?php
    include ('inc.footer.php');
}
//}}}
//fu:5 Zug berechnen {{{
if ($fuid==5) {
    include (inhalt_dir . 'inc.header.php');
    $fertig = 0;
    for($i=1; $i<=10; $i++) {
        if($spieler_zug_c[$i]==1) $fertig++;
    }
    if($fertig>=$spieleranzahl) {
        $lasttick = time();
        $Db->execute("UPDATE " . table_prefix . "spiele SET lasttick='" . $lasttick . "',
                                                 spieler_1_zug='0',
                                                 spieler_2_zug='0',
                                                 spieler_3_zug='0',
                                                 spieler_4_zug='0',
                                                 spieler_5_zug='0',
                                                 spieler_6_zug='0',
                                                 spieler_7_zug='0',
                                                 spieler_8_zug='0',
                                                 spieler_9_zug='0',
                                                 spieler_10_zug='0' 
                                             WHERE sid='" . $sid . "'");
        
        include (inhalt_dir .'inc.host.php');
    }
    ?>
    <script language="JavaScript">
        function link(url) {
            if (parent.mittelinksoben.document.globals.map.value==1) {
                parent.mittelinksoben.document.globals.map.value=0;
                parent.mittemitte.window.location='<?php echo servername;?>inhalt/aufbau.php?fu=100&query='+url;
            } else  {
                parent.mittemitte.rahmen12.window.location=url;
            }
        }
        function redir() {
            link('<?php echo servername;?>inhalt/uebersicht_uebersicht.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>');
            window.location='<?php echo servername;?>inhalt/uebersicht.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>';
        }
    </script>
        <body onload="javascript:redir();" text="#000000" bgcolor="#444444" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" height="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td><nobr><center><?php echo $lang['zugende']['wurdenausgewertet']?></center></nobr></td>
                </tr>
            </table>
        </center>
        <?php
        $fuu=1;
        include (inhalt_dir . 'inc.host_messenger.php');
        include (inhalt_dir . 'inc.footer.php');
}
//}}}
//fu:6 Zug wird berechnet Nachricht und Redirect {{{
if ($fuid==6) {
    include (inhalt_dir . 'inc.header.php');
    ?>
    <body onLoad="window.location='<?php echo servername;?>inhalt/zugende.php?fu=5&uid=<?php echo $uid?>&sid=<?php echo $sid?>';" text="#000000" bgcolor="#444444" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;"  link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" cellspacing="0" cellpadding="0" height="100%">
                <tr>
                    <td>
                        <center>
                        <img src="<?php echo servername;?>bilder/radd.gif" height="46" width="51">
                            <br><br>
                            <?php echo $lang['zugende']['wirdberechnet']?>
                        </center>
                    </td>
                </tr>
            </table>
        </center>
        <?php
    include (inhalt_dir . 'inc.footer.php');
}
//}}}
//fu:7 Galaxiesprung, Galaxiewahl {{{
if ($fuid==7) {
    include (inhalt_dir . 'inc.header.php');
    ?>
    <body text="#ffffff" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" bgcolor="#000000" link="#ffffff" vlink="#ffffff" alink="#ffffff" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" cellspacing="0" cellpadding="1">
                <tr>
                    <td colspan="3"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="4"></td>
                </tr>
                <tr>
                    <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="17" height="17"></td>
                    <td><center><?php echo $lang['zugende']['galaxiesprung']?></center></td>
                    <td><a href="javascript:hilfe();"><img src="<?php echo servername;?>bilder/icons/hilfe.gif" border="0" width="17" height="17"></a></td>
                </tr>
            </table>
        </center>
        <center>
            <table border="0" cellspacing="0" cellpadding="3">
                <tr>
                    <td colspan="3"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="1"></td>
                </tr>
                <tr>
                    <td><form name="formular" method="post" action="<?php echo servername;?>inhalt/zugende.php?fu=8&uid=<?php echo $uid?>&sid=<?php echo $sid?>"></td>
                    <td><center><?php $lang['zugende']['sprungwohin']?></center></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <center>
                            <select name="neuesspiel">
                                <?php
                                $zeiger2 = @mysql_query("SELECT * FROM " . table_prefix . "spiele WHERE (spieler_1='" . $spieler_id . "' 
                                                                                                      or spieler_2='" . $spieler_id . "' 
                                                                                                      or spieler_3='" . $spieler_id . "' 
                                                                                                      or spieler_4='" . $spieler_id . "' 
                                                                                                      or spieler_5='" . $spieler_id . "' 
                                                                                                      or spieler_6='" . $spieler_id . "' 
                                                                                                      or spieler_7='" . $spieler_id . "' 
                                                                                                      or spieler_8='" . $spieler_id . "' 
                                                                                                      or spieler_9='" . $spieler_id . "' 
                                                                                                      or spieler_10='" . $spieler_id . "') 
                                                                                                  and id<>".$spiel." 
                                                                                                  and phase='0'");
                                if (@mysql_num_rows($zeiger2)>0) {
                                    while ($array = @mysql_fetch_array($zeiger2)) {
                                        $spielneuid=$array["id"];
                                        $spielneuname=$array["name"];
                                        $farbe = '#444444';
                                        for($i=1; $i<=10; $i++) {
                                            $tmpstr = 'spieler_'.$i;
                                            if($spieler_id == $array[$tmpstr] && $array[$tmpstr.'_zug']==0 && $array[$tmpstr.'_raus']==0) $farbe = '#aa0000';
                                        }
                                        ?>
                                        <option value="<?php echo $spielneuid?>" style="background-color:<?php echo $farbe?>"><?php echo $spielneuname?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </center>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="1"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><center><input type="submit" name="bla" value="<?php echo $lang['zugende']['sprungdurchfuehren']?>" style="width:250px;"></center></td>
                    <td></form></td>
                </tr>
            </table>
        </center>
        <?php
    include (inhalt_dir . "inc.footer.php");
}
//}}}
//fu:8 Galaxiesprung durchfuehren {{{
if ($fuid==8) {
    include (inhalt_dir . "inc.header.php");
    $neuesspiel = int_post('neuesspiel');
    $zeiger2 = @mysql_query("SELECT id,sid FROM " . table_prefix . "spiele WHERE id='" . $neuesspiel . "'");
    if (@mysql_num_rows($zeiger2)==1) {
        $array2 = @mysql_fetch_array($zeiger2);
        $sidneu = $array2['sid'];
    }
    ?>
    <script language="JavaScript">
        function link(url) {
            if (parent.mittelinksoben.document.globals.map.value==1) {
                parent.mittelinksoben.document.globals.map.value = 0;
                parent.mittemitte.window.location = '<?php echo servername;?>inhalt/aufbau.php?fu=100&query=' + url;
            }  else  {
                parent.mittemitte.rahmen12.window.location = url;
            }
        }
        function galaxiewechsel() {
            parent.mittelinksoben.window.location = '<?php echo servername;?>inhalt/menu.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sidneu?>';
            parent.untenlinks.window.location     = '<?php echo servername;?>inhalt/menu.php?fu=2&uid=<?php echo $uid?>&sid=<?php echo $sidneu?>';
            link('<?php echo servername;?>inhalt/uebersicht_uebersicht.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sidneu?>');
            window.location = '<?php echo servername;?>inhalt/uebersicht.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sidneu?>';
        }
    </script>
    <body onload="javascript:galaxiewechsel();" text="#000000" bgcolor="#444444" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" cellspacing="0" cellpadding="0" height="100%">
                <tr>
                    <td><center><?php echo $lang['zugende']['spunginitialisiert']?></center></td>
                </tr>
            </table>
        </center>
        <?php
    include (inhalt_dir ."inc.footer.php");
}
//}}}
//fu:9 Zug abschliessen zwischenschritt fuer langsame server oO {{{
if ($fuid==9) {
    open_db();
    include (inhalt_dir .'inc.check.php');
    include (lang_dir . $spieler_sprache.'/lang.zugende.php');
    $spalte = "spieler_{$spieler}_zug";
    $spieler_zug_c[$spieler] = 1;
    @mysql_query("UPDATE " . table_prefix . "spiele SET $spalte = '1' WHERE sid = '" . $sid ."' order by sid");
    
    $fertig = 0;
    for($i=1; $i<=10; $i++) {
        if($spieler_zug_c[$i]==1) $fertig++;
    }
    if($fertig>=$spieleranzahl) {
        $backlink = servername . "inhalt/zugende.php?fu=6&uid=". $uid . "&sid=" . $sid;
    } else {
        $backlink = servername . "inhalt/zugende.php?fu=4&uid=" . $uid . "&sid=" .$sid;
    }
    header ("Location: $backlink");
}
//}}}