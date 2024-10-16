<?php
require_once ('../inc.conf.php'); 
require_once (inhalt_dir . 'inc.hilfsfunktionen.php');
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST), filter_struct_utf8(1, $_REQUEST));
$langfile_1 = 'meta';

$fuid = intval($params["fu"]);

if ($fuid==1) {
    include (inhalt_dir . "inc.header.php");
        ?>
        <body text="#000000" bgcolor="#444444" style="background-image:url('<?php echo servername;?>bilder/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
            <script language="JavaScript">
                function metalink(url) {
                        if (parent.mittelinksoben.document.globals.map.value==1) {
                             parent.mittelinksoben.document.globals.map.value=0;
                             parent.mittemitte.window.location='<?php echo servername;?>inhalt/aufbau.php?fu=100&query='+url;
                        }  else  {
                             parent.mittemitte.rahmen12.window.location=url;
                        }
                }
            </script>
            <center>
                <table border="0" height="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_optionen.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/optionen.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['optionen']?></nobr></a></center></td>
                        <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_fordner.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/flotte.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['fordner']?></nobr></a></center></td>
                        <?php if (($spieleranzahl>=2) and ($spieler_raus==0)) { ?>
                            <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_aufgabe.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/aufgabe.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['aufgabe']?></nobr></a></center></td>
                        <?php } ?>
                        <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_simulation.php?fu=2&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/simss.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['sim1']?></nobr></a></center></td>
                        <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_simulation.php?fu=3&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/simsp.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['sim2']?></nobr></a></center></td>
                        <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_simulation.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/simbb.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['sim3']?></nobr></a></center></td>
                        <td><center><a href="<?php echo servername;?>inhalt/meta_rassen.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>" target="_self"><img src="<?php echo servername;?>bilder/menu/rassen.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['voelker']?></nobr></a></center></td>
                        <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_spezien.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/dspezies.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['dspezien']?></nobr></a></center></td>
                        <td><center><a href="javascript:;" onclick="metalink('<?php echo servername;?>inhalt/meta_pklassen.php?fu=1&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo servername;?>bilder/menu/pklassen.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['pklassen']?></nobr></a></center></td>
                        <?php /* 
                        <td><center><a href="javascript:;" onclick="metalink('wisdom.php?fu=8&uid=<?php echo $uid?>&sid=<?php echo $sid?>');self.focus();"><img src="<?php echo $bildpfad?>/menu/galaxiedruck.gif" width="75" height="75" border="0"><br><nobr>Galaxiedruck</nobr></a></center></td>
                        */ ?>
                        <?php if ($spiel_anleitung==0) { ?>  
                            <td><center><a href="http://wiki.skrupel.de" target="_blank"><img src="<?php echo $bildpfad?>/menu/anleitung.gif" width="75" height="75" border="0"><br><nobr><?php echo $lang['meta']['anleitung']?></nobr></a></center></td>
                        <?php } ?>
                    </tr>
                </table>
            </center>
            <?php
    include (inhalt_dir . "inc.footer.php");
}
