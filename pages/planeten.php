<?php
require_once ('../inc.conf.php'); 
require_once (inhalt_dir . 'inc.hilfsfunktionen.php');
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST), filter_struct_utf8(1, $_REQUEST));
$lang = get_phrasen('de', 'planeten');

//$langfile_1 = 'planeten_gamma';
$fuid = @intval($params["fu"]);
$pid = @intval($params["pid"]); 

if ($fuid==1) {
    include (inhalt_dir . "inc.header.php");
    ?>
    <script language="JavaScript">
        function fensterbreit() {
            if (parent.parent.mittemitte.window.innerWidth) return parent.parent.mittemitte.window.innerWidth;
            else if (parent.parent.mittemitte.document.body && parent.parent.mittemitte.document.body.offsetWidth) return parent.parent.mittemitte.document.body.offsetWidth;
            else return 0;
        }
        function fensterhoch(){
            if (parent.parent.mittemitte.window.innerHeight) return parent.parent.mittemitte.window.innerHeight;
            else if (parent.parent.mittemitte.document.body && parent.parent.mittemitte.document.body.offsetHeight) return parent.parent.mittemitte.document.body.offsetHeight;
            else return 0;
        }
        function movemap(wertx,werty) {
            breite=fensterbreit();
            hoch=fensterhoch();
            aktuellx=wertx-15;
            aktuelly=werty-15;
            wertx=wertx-(breite/2);
            werty=werty-(hoch/2);
            <?php if (@intval(substr($spieler_optionen,17,1))!=1) { ?>
                var scrollDiv = parent.parent.mittemitte.document.getElementById("complete");
                if(scrollDiv.contentScroll) scrollDiv.contentScroll(wertx,werty,false);
            <?php } else { ?>
                parent.parent.mittemitte.document.getElementById("complete").scrollLeft = wertx;
                parent.parent.mittemitte.document.getElementById("complete").scrollTop = werty; 
            <?php } ?>
            parent.parent.mittemitte.document.getElementById("aktuell").style.visibility='visible';
            parent.parent.mittemitte.document.getElementById("aktuell").style.left=aktuellx;
            parent.parent.mittemitte.document.getElementById("aktuell").style.top=aktuelly;
        }
    </script>
    <body text="#000000" <?php if (@intval(substr($spieler_optionen,17,1))!=1) { ?>onload="document.getElementById('bodycontent').style.width = (document.getElementById('bodytable').offsetWidth) + 'px';CSBfleXcroll('bodybody');"<?php } ?> style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" bgcolor="#000000" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <div id="bodybody" class="flexcrollafter" onfocus="this.blur()">
        <div id="bodycontent" style="margin:0 auto;">
        <?php
        $zeiger = @mysql_query("SELECT * FROM " . table_prefix . "planeten where besitzer=$spieler and spiel=$spiel order by name");
        $planetenanzahl = @mysql_num_rows($zeiger);
        if ($planetenanzahl>=1) {
        ?>
        <center>
            <table border="0" cellspacing="0" cellpadding="0" id="bodytable">
                <tr>
                    <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="5"></td>
                </tr>
                <tr>
                    <td>
                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <?php
                                for ($i=0; $i<$planetenanzahl;$i++) {
                                    $ok = @mysql_data_seek($zeiger,$i);
                                    $array = @mysql_fetch_array($zeiger);
                                    $pid=$array["id"];
                                    $name=$array["name"];
                                    $klasse=$array["klasse"];
                                    $x_pos=$array["x_pos"];
                                    $y_pos=$array["y_pos"];
                                    $bild=$array["bild"];
                                    $temp=$array["temp"];
                                    $minen=$array["minen"];
                                    $fabriken=$array["fabriken"];
                                    $abwehr=$array["abwehr"];
                                    $logbuch=$array["logbuch"];
                                    $tip="[$name]";
                                    if (strlen($logbuch)>=1) {
                                        $tip=$tip."&#10;$logbuch";
                                    }
                                    ?>
                                    <td>
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td bgcolor="#aaaaaa" colspan="3"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="1"></td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#aaaaaa"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="1"></td>
                                                <td><a href="<?php echo servername;?>inhalt/planeten.php?fu=2&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><img src="<?php echo servername;?>bilder/planeten/<?php echo $klasse?>_<?php echo $bild?>.jpg" border="0" height="80" width="93" title="<?php echo $tip?>"></a></td>
                                                <td bgcolor="#aaaaaa"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="1"></td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#aaaaaa" colspan="3"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="1"></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="82"></td>
                                                <td bgcolor="#990000" valign="bottom"><img src="<?php echo servername;?>bilder/skalen/gruen_1.gif" border="0" width="10" height="<?php echo ($temp*0.82); ?>"></td>
                                                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="6" height="82"></td>
                                                <td>
                                                    <table border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td><center><a href="javascript:;" onclick="movemap(<?php echo $x_pos.",".$y_pos; ?>);"><img src="<?php echo servername;?>bilder/karte/farben/schiff_aktiv_<?php echo $spieler?>.gif" border="0" width="15" height="15"></a></center></td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="10" height="5"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><nobr><center><?php echo $name?></center></nobr></td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="10" height="5"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="color:#aaaaaa;"><nobr><center><?php echo $x_pos?> / <?php echo $y_pos?></center></nobr></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="6" height="80"></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
        </div></div>
        <?php
        }
    include (inhalt_dir . "inc.footer.php");
}
if ($fuid==2) {
    include (inhalt_dir ."inc.header.php");
    $pid = intval($params["pid"]);
    ?>
    <frameset framespacing="0" border="false" frameborder="0" cols="*,26,270,208,400,26,*">
        <frame name="randlinks" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=14" target="_self">
        <frame name="pfeillinks" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/planeten.php?fu=6&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
        <frame name="planeten" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/planeten.php?fu=3&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
        <?php if ($zug_abgeschlossen==0) { ?>
        <frame name="planetenmenu" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/planeten.php?fu=4&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
        <?php } else { ?>
        <frame name="planetenmenu" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/planeten.php?fu=5&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
        <?php } ?>
        <frame name="planetendetails" scrolling="auto" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/planeten_alpha.php?fu=1&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
        <frame name="pfeilrechts" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/planeten.php?fu=7&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self">
        <frame name="randrechts" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=14" target="_self">
    </frameset>
    <noframes>
    <body>
        <?php
    include (inhalt_dir . "inc.footer.php");
}
if ($fuid==3) {
    include (inhalt_dir . "inc.header.php");
    $zeiger = "SELECT * FROM " . table_prefix . "planeten use index (besitzer,PRIMARY) where besitzer='".$spieler."' and id='".$pid."'";
    $array =$Db->getRow($zeiger);
    $pid=$array["id"];
    $name=$array["name"];
    $x_pos=$array["x_pos"];
    $y_pos=$array["y_pos"];
    $bild=$array["bild"];
    $temp=$array["temp"];
    $kolonisten=$array["kolonisten"];
    $lemin=$array["lemin"];
    $min1=$array["min1"];
    $min2=$array["min2"];
    $min3=$array["min3"];
    $minen=$array["minen"];
    $cantox=$array["cantox"];
    $vorrat=$array["vorrat"];
    $fabriken=$array["fabriken"];
    $abwehr=$array["abwehr"];
    ?>
    <script language="JavaScript">
        function fensterbreit(){
            if (parent.parent.mittemitte.window.innerWidth) return parent.parent.mittemitte.window.innerWidth;
            else if (parent.parent.mittemitte.document.body && parent.parent.mittemitte.document.body.offsetWidth) return parent.parent.mittemitte.document.body.offsetWidth;
            else return 0;
        }
        function fensterhoch(){
            if (parent.parent.mittemitte.window.innerHeight) return parent.parent.mittemitte.window.innerHeight;
            else if (parent.parent.mittemitte.document.body && parent.parent.mittemitte.document.body.offsetHeight) return parent.parent.mittemitte.document.body.offsetHeight;
            else return 0;
        }
        function movemap(wertx,werty) {
            if (parent.parent.mittelinksoben.document.globals.map.value==1) {
                breite=fensterbreit();
                hoch=fensterhoch();
                aktuellx=wertx-15;
                aktuelly=werty-15;
                wertx=wertx-(breite/2);
                werty=werty-(hoch/2);
                parent.parent.mittemitte.window.scrollTo(wertx,werty);
                parent.parent.mittemitte.document.getElementById("aktuell").style.visibility='visible';
                parent.parent.mittemitte.document.getElementById("aktuell").style.left=aktuellx;
                parent.parent.mittemitte.document.getElementById("aktuell").style.top=aktuelly;
            }
        }
        function linksub(url) {
            parent.planetendetails.window.location=url;
        }
    </script>
    <body text="#000000" background="<?php echo servername;?>bilder/aufbau/14.gif" bgcolor="#000000" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="movemap(<?php echo $x_pos.",".$y_pos; ?>);">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="20"><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="1" height="5"></td>
            </tr>
            <tr>
                <td colspan="10"><?php echo $name?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/lemin.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['lemin']?></td>
            </tr>
            <tr>
                <td colspan="10" style="color:#aaaaaa;"><?php echo $lang['planeten']['koordinaten']?>: <?php echo $x_pos?> / <?php echo $y_pos?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><div id="lemin"><?php echo $lemin?></div></td>
                            <td>&nbsp;<?php echo $lang['planeten']['kt']?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/crew.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['kolonisten']?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/minen.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['minen']?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/mineral_1.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['baxterium']?></td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td><div id="kolonisten"><?php echo $kolonisten?></div></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td><div id="minen"><?php echo $minen?></div></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><div id="min1"><?php echo $min1?></div></td>
                            <td>&nbsp;<?php echo $lang['planeten']['kt']?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/cantox.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['cantox']?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/fabrik.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['fabriken']?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/mineral_2.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['rennurbin']?></td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td><div id="cantox"><?php echo $cantox?></div></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td><div id="fabriken"><?php echo $fabriken?></div></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><div id="min2"><?php echo $min2?></div></td>
                            <td>&nbsp;<?php echo $lang['planeten']['kt']?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/vorrat.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['vorraete']?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/abwehr.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['pds']?></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td rowspan="2"><img src="<?php echo servername;?>bilder/icons/mineral_3.gif" border="0" width="17" height="17"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td colspan="2" style="color:#aaaaaa;"><?php echo $lang['planeten']['vomisaan']?></td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><div id="vorrat"><?php echo $vorrat?></div></td>
                            <td>&nbsp;<?php echo $lang['planeten']['kt']?></td>
                        </tr>
                    </table>
                </td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td><div id="abwehr"><?php echo $abwehr?></div></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="5" height="1"></td>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="15" height="1"></td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><div id="min3"><?php echo $min3?></div></td>
                            <td>&nbsp;<?php echo $lang['planeten']['kt']?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <?php
    include ("inc.footer.php");
}
if ($fuid==4) {
    include ("inc.header.php");
    $pid = intval($params["pid"]);
    ?>
    <script language="JavaScript">
        function linksub(url) {
            parent.planetendetails.window.location=url;
        }
    </script>
    <body text="#000000" background="<?php echo servername;?>bilder/aufbau/14.gif" bgcolor="#000000" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="208" height="6"></td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/konsole/planeten.gif" border="0" width="208" height="95" usemap="#konsole"></td>
            </tr>
        </table>
        <map name="konsole">
            <area shape="rect" coords="26,15,52,40" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_alpha.php?fu=1&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['details']?>">
            <area shape="rect" coords="74,15,97,40" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_alpha.php?fu=4&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['globalesysteme']?>">
            <area shape="rect" coords="120,15,143,40" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_alpha.php?fu=2&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['sternenbasis']?>">
            <area shape="rect" coords="69,62,103,86" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_beta.php?fu=2&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['logbuch']?>">
            <area shape="rect" coords="26,58,53,84" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_beta.php?fu=1&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['dominantespezies']?>">
            <area shape="rect" coords="120,62,143,86" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_beta.php?fu=4&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['schiffe']?>">
            <area shape="rect" coords="163,11,184,33" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_gamma.php?fu=1&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['minen']?>">
            <area shape="rect" coords="163,40,184,60" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_gamma.php?fu=4&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['fabriken']?>">
            <area shape="rect" coords="163,65,184,87" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_gamma.php?fu=9&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['pds']?>">
        </map>
        <?php
    include (inhalt_dir ."inc.footer.php");
}
if ($fuid==5) {
    include (inhalt_dir ."inc.header.php");
    $pid = intval($params["pid"]);
    ?>
    <script language="JavaScript">
        function linksub(url) {
            parent.planetendetails.window.location=url;
        }
    </script>
    <body text="#000000" background="<?php echo servername;?>bilder/aufbau/14.gif" bgcolor="#000000" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="208" height="6"></td>
            </tr>
            <tr>
                <td><img src="<?php echo servername;?>bilder/konsole/planeten_no.gif" border="0" width="208" height="95" usemap="#konsole"></td>
            </tr>
        </table>
        <map name="konsole">
            <area shape="rect" coords="26,15,52,40" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_alpha.php?fu=1&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['details']?>">
            <area shape="rect" coords="69,62,103,86" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_beta.php?fu=2&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['logbuch']?>">
            <area shape="rect" coords="26,58,53,84" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_beta.php?fu=1&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['dominantespezies']?>">
            <area shape="rect" coords="120,62,143,86" href="javascript:linksub('<?php echo servername;?>inhalt/planeten_beta.php?fu=4&pid=<?php echo $pid?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();" title="<?php echo $lang['planeten']['schiffe']?>">
        </map>
        <?php
    include (inhalt_dir . "inc.footer.php");
}
if ($fuid==6) {
    include (inhalt_dir . "inc.header.php");
    ?>
    <script language="JavaScript">
        function linksub(url) {
            parent.window.location=url;
        }
    </script>
    <body text="#000000" scroll="no" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" bgcolor="#444444" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <?php
        /*$zeiger = @mysql_query("SELECT id FROM " . table_prefix . "planeten where besitzer=$spieler and spiel=$spiel order by name");
        $planetenanzahl = @mysql_num_rows($zeiger);
        if ($planetenanzahl>=1) {
            for ($i=0; $i<$planetenanzahl;$i++) {
                $ok = @mysql_data_seek($zeiger,$i);
                $array = @mysql_fetch_array($zeiger);
                $pid_t=$array["id"];
                if (($pid==$pid_t) and ($i>=1)) {
                    ?>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="26" height="8"></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:linksub('<?php echo servername;?>inhalt/planeten.php?fu=2&pid=<?php echo $pid_back?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/konsole/pfeil_links.gif" border="0" width="26" height="90"></a></td>
                        </tr>
                    </table>
                    <?php
                }
                $pid_back=$pid_t;
            }
        }*/
        $sql = "SELECT id FROM " . table_prefix . "planeten use index (besitzer) WHERE besitzer = ? AND spiel = ? ORDER BY name";
        $spit = array($spieler, $spiel);
        $zeiger = $Db->execute($sql, $spit);
        
        $planetenanzahl = $zeiger->RecordCount();
        
        if ($planetenanzahl >= 1) {
            for ($i = 0; $i < $planetenanzahl; $i++) {
                // Daten der aktuellen Zeile abrufen
                $array = $zeiger->FetchRow();
                $pid_t = $array["id"];
                
                if (($pid == $pid_t) && ($i >= 1)) {
                    ?>
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="26" height="8"></td>
                </tr>
                <tr>
                    <td><a href="javascript:linksub('<?php echo servername;?>inhalt/planeten.php?fu=2&pid=<?php echo $pid_back?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/konsole/pfeil_links.gif" border="0" width="26" height="90"></a></td>
                </tr>
            </table>
            <?php
        }
        $pid_back = $pid_t;
    }
}
    include (inhalt_dir . "inc.footer.php");
}
if ($fuid==7) {
    include (inhalt_dir . "inc.header.php");
    ?>
    <script language="JavaScript">
    function linksub(url) {
        parent.window.location=url;
    }
    </script>
    <body text="#000000" scroll="no" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" bgcolor="#444444" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <?php
        /*$zeiger = @mysql_query("SELECT id FROM " . table_prefix . "planeten where besitzer=$spieler and spiel=$spiel order by name");
        $planetenanzahl = @mysql_num_rows($zeiger);
        $pid_back = 0;
        if ($planetenanzahl>=1) {
            for ($i=0; $i<$planetenanzahl;$i++) {
                $ok = @mysql_data_seek($zeiger,$i);
                $array = @mysql_fetch_array($zeiger);
                $pid_t=$array["id"];
                if ($pid==$pid_back) {
                    ?>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="26" height="8"></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:linksub('<?php echo servername;?>inhalt/planeten.php?fu=2&pid=<?php echo $pid_t?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/konsole/pfeil_rechts.gif" border="0" width="26" height="90"></a></td>
                        </tr>
                    </table>
                    <?php
                }
                $pid_back=$pid_t;
            }
        }*/
        $sql = "SELECT id FROM " . table_prefix . "planeten use index (besitzer) where besitzer = ? and spiel = ? order by name";
        $spit = array($spieler,$spiel);
        $zeiger = $Db->execute($sql, $spit);
        $planetenanzahl = $zeiger->RecordCount();
        $pid_back = 0;
        if ($planetenanzahl>=1) {
            for ($i = 0; $i < $planetenanzahl; $i++) {                
                $array = $zeiger->FetchRow();
                $pid_t=$array["id"];
                if ($pid==$pid_back) {
                    ?>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><img src="<?php echo servername;?>bilder/empty.gif" border="0" width="26" height="8"></td>
                        </tr>
                        <tr>
                            <td><a href="javascript:linksub('<?php echo servername;?>inhalt/planeten.php?fu=2&pid=<?php echo $pid_t?>&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/konsole/pfeil_rechts.gif" border="0" width="26" height="90"></a></td>
                        </tr>
                    </table>
                    <?php
                }
                $pid_back=$pid_t;
            }
        }
    include (inhalt_dir . "inc.footer.php");
}
