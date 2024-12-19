<?php 
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST));
$fuid = $params["fu"];
$langueberkolonien = array_merge(get_phrasen('de', 'orbitalesysteme'), get_phrasen('de','uebersichtkolonien'));

switch ($fuid){
    case 1:
        set_header();
        
        $smarty->assign('fuid',$fuid);
        $smarty->assign('uid',$params["uid"]);
        $smarty->assign('sid',$params["sid"]);
        $smarty->assign('servername', servername);
        $kolfu1html = "";
        $kolfu1html .= "<body text=\"#ffffff\" bgcolor=\"#444444\"  link=\"#000000\" vlink=\"#000000\" alink=\"#000000\" leftmargin=\"0\" rightmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
        $kolfu1html .= "<script language=\"JavaScript\">";
        $kolfu1html .= "    function nativedetail(shid) {";
        $kolfu1html .= "        oben=100;";
        $kolfu1html .= "        links=Math.ceil((screen.width-580)/2);";
        $kolfu1html .= "        window.open('".servername."pages/hilfe/hilfe_native.php?fu2='+shid+'&uid=".$uid."&sid=".$sid."','domspezien','resizable=yes,scrollbars=no,width=580,height=180,top='+oben+',left='+links);";
        $kolfu1html .= "    }";
        $kolfu1html .= "</script>";
        $kolfu1html .= "<div id=\"bodybody\" class=\"flexcroll\" onfocus=\"this.blur();\">";
        $kolfu1html .= "<center><img src=\"".servername."/lang/de/topics/kolonien.gif\" border=\"0\" width=\"162\" height=\"52\"></center>";
        $kolfu1html .= "<center>";
        $kolfu1html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        $zeiger = "SELECT * FROM " . table_prefix ."planeten where besitzer='".$spieler."' and spiel='".$spiel."' order by name";
        $rows = $db->execute($zeiger);
        $planetenanzahl = $rows->RecordCount();
        if ($planetenanzahl>=1) {
            while ($array = $rows->fetchRow()){
              $pid=$array["id"];
                        $name=$array["name"];
                        $x_pos=$array["x_pos"];
                        $y_pos=$array["y_pos"];
                        $bild=$array["bild"];
                        $kolonisten=$array["kolonisten"];
                        $schwerebt=$array["schwerebt"];
                        $leichtebt=$array["leichtebt"];
                        $lemin=$array["lemin"];
                        $min1=$array["min1"];
                        $min2=$array["min2"];
                        $min3=$array["min3"];
                        $minen=$array["minen"];
                        $cantox=$array["cantox"];
                        $vorrat=$array["vorrat"];
                        $fabriken=$array["fabriken"];
                        $abwehr=$array["abwehr"];
                        $logbuch=$array["logbuch"];
                        $auto_minen=$array["auto_minen"];
                        $auto_fabriken=$array["auto_fabriken"];
                        $auto_abwehr=$array["auto_abwehr"];
                        $auto_vorrat=$array["auto_vorrat"];
                        $sternenbasis=$array["sternenbasis"];
                        $sternenbasis_id=$array["sternenbasis_id"];
                        $sternenbasis_art=$array["sternenbasis_art"];
                        $native_id=$array["native_id"];
                        $native_name=$array["native_name"];
                        $native_art_name=$array["native_art_name"];
                        $native_abgabe=$array["native_abgabe"];
                        $native_bild=$array["native_bild"];
                        $native_text=$array["native_text"];
                        $native_kol=$array["native_kol"];
                        $osys_anzahl=$array["osys_anzahl"];
                        $osys[1]=$array["osys_1"];
                        $osys[2]=$array["osys_2"];
                        $osys[3]=$array["osys_3"];
                        $osys[4]=$array["osys_4"];
                        $osys[5]=$array["osys_5"];
                        $osys[6]=$array["osys_6"];
                        for($i2=1; $i2<=$osys_anzahl; $i2++) {
                            $orbname = "name".$osys[$i2];
                            if ($osys[$i2]>=1) {
                                $osys[$i2] = "<img src=\"../bilder/osysteme/".$osys[$i2].".gif\" border=\"0\" width=\"32\" height=\"30\" title=\"".$langueberkolonien['orbitalesysteme'].$orbname."\">";
                                $kolfu1html .=  $osys[$i2];
                            } else {
                                $osys[$i2] = "<img src=\"../bilder/osysteme/blank.gif\" border=\"0\" width=\"32\" height=\"30\">";
                                $kolfu1html .= $osys[$i2];
                            }
                           
                        }
                        for($i2=6; $i2>$osys_anzahl; $i2--) {
                            $osys[$i2] = "<img src=\"../bilder/empty.gif\" border=\"0\" width=\"32\" height=\"30\">";
                            $kolfu1html .= $osys[$i2];
                        }
                        $temp=$array["temp"];
                        $klasse=$array["klasse"];
                        if ($klasse==1) {
                            $klassename="M";
                        }elseif ($klasse==2) {
                            $klassename="N";
                        }elseif ($klasse==3) {
                            $klassename="J";
                        }elseif ($klasse==4) {
                            $klassename="L";
                        }elseif ($klasse==5) {
                            $klassename="G";
                        }elseif ($klasse==6) {
                            $klassename="I";
                        }elseif ($klasse==7) {
                            $klassename="C";
                        }elseif ($klasse==8) {
                            $klassename="K";
                        }elseif ($klasse==9) {
                            $klassename="F";
                        }
                        $planet_lemin=$array["planet_lemin"];
                        $planet_min1=$array["planet_min1"];
                        $planet_min2=$array["planet_min2"];
                        $planet_min3=$array["planet_min3"];
                        $konz_lemin=$array["konz_lemin"];
                        $konz_min1=$array["konz_min1"];
                        $konz_min2=$array["konz_min2"];
                        $konz_min3=$array["konz_min3"];
                        if ($konz_lemin==1) {
                            $konz_lemin="fl&uuml;chtig";
                        }elseif ($konz_lemin==2) {
                            $konz_lemin="weit gestreut";
                        }elseif ($konz_lemin==3) {
                            $konz_lemin="verteilt";
                        }elseif ($konz_lemin==4) {
                            $konz_lemin="konzentriert";
                        }elseif ($konz_lemin==5) {
                            $konz_lemin="hochkonz.";
                        }
                        if ($konz_min1==1) {
                            $konz_min1="fl&uuml;chtig";
                        }elseif ($konz_min1==2) {
                            $konz_min1="weit gestreut";
                        }elseif ($konz_min1==3) {
                            $konz_min1="verteilt";
                        }elseif ($konz_min1==4) {
                            $konz_min1="konzentriert";
                        }elseif ($konz_min1==5) {
                            $konz_min1="hochkonz.";
                        }
                        if ($konz_min2==1) {
                            $konz_min2="fl&uuml;chtig";
                        }elseif ($konz_min2==2) {
                            $konz_min2="weit gestreut";
                        }elseif ($konz_min2==3) {
                            $konz_min2="verteilt";
                        }elseif ($konz_min2==4) {
                            $konz_min2="konzentriert";
                        }elseif ($konz_min2==5) {
                            $konz_min2="hochkonz.";
                        }
                        if ($konz_min3==1) {
                            $konz_min3="fl&uuml;chtig";
                        }elseif ($konz_min3==2) {
                            $konz_min3="weit gestreut";
                        }elseif ($konz_min3==3) {
                            $konz_min3="verteilt";
                        }elseif ($konz_min3==4) {
                            $konz_min3="konzentriert";
                        }elseif ($konz_min3==5) {
                            $konz_min3="hochkonz.";
                        }
            $kolfu1html .= "<tr>";
            $kolfu1html .= "    <td>";
            $kolfu1html .= "        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "            <tr>";
            $kolfu1html .= "                <td bgcolor=\"#aaaaaa\" colspan=\"3\"><img src=\"" . image_dir . "empty.gif\" border=\"0\" width=\"1\" height=\"1\"></td>";
            $kolfu1html .= "            </tr>";
            $kolfu1html .= "            <tr>";
            $kolfu1html .= "                <td bgcolor=\"#aaaaaa\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"1\"></td>";
            $kolfu1html .= "                <td><a href=\"pages/planeten.php?fu=2&pid=".$pid."&uid=".$uid."&sid=".$sid."\" target=\"untenmitte\"><img src=\"".image_dir."planeten/".$klasse."_".$bild.".jpg\" border=\"0\" title=\"".$logbuch."\"></a></td>";
            $kolfu1html .= "                <td bgcolor=\"#aaaaaa\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"1\"></td>";
            $kolfu1html .= "            </tr>";
            $kolfu1html .= "            <tr>";
            $kolfu1html .= "                <td bgcolor=\"#aaaaaa\" colspan=\"3\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"1\"></td>";
            $kolfu1html .= "            </tr>";
            $kolfu1html .= "        </table>";             
            $kolfu1html .= "   </td>";            
            $kolfu1html .= "   <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"8\" height=\"1\"></td>";
            $kolfu1html .= "   <td>";
            $kolfu1html .= "    <center>";
            $kolfu1html .= "        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "            <tr>";
            $kolfu1html .= "                <td><nobr><a href=\"".servername."pages/planeten.php?fu=2&pid=".$pid."&uid=".$uid."&sid=".$sid."\" target=\"untenmitte\">".$name."</a></nobr></td>";
            $kolfu1html .= "                <td style=\"color:#aaaaaa;\"><nobr>&nbsp;(".$x_pos."/".$y_pos.")</nobr></td>";
            $kolfu1html .= "            </tr>";
            $kolfu1html .= "            <tr>";
            $kolfu1html .= "                <td colspan=\"2\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"3\"></td>";
            $kolfu1html .= "            </tr>";
            $kolfu1html .= "         </table>";
            $kolfu1html .= "    </center>";
            $kolfu1html .= "    <center>";
            $kolfu1html .= "        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "            <tr>";
            $kolfu1html .= "                <td>";
            $kolfu1html .= "                <center>";
            $kolfu1html .= "                <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "                    <tr>";
            $kolfu1html .= "                        <td style=\"color:#aaaaaa;\">" . $langueberkolonien['uebersichtkolonien']['klasse'] ."&nbsp;</td>";
            $kolfu1html .= "                        <td>" . $klassename ."</td>";
            $kolfu1html .= "                        <td style=\"color:#aaaaaa;\">&nbsp;".$langueberkolonien['uebersichtkolonien']['planet']."</td>";
            $kolfu1html .= "                    </tr>";
            $kolfu1html .= "                    <tr>";
            $kolfu1html .= "                        <td colspan=\"3\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"4\"></td>";
            $kolfu1html .= "                    </tr>";
            $kolfu1html .= "                </table>";
            $kolfu1html .= "                                    </center>";
            $kolfu1html .= "                                </td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"140\" height=\"1\"></td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td background=\"".image_dir."skalen/temperatur.gif\">";
            $kolfu1html .= "                                    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "                                        <tr>";
            $kolfu1html .= "                                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"".($temp*1.4)-1;"\" height=\"25\"></td>";
            $kolfu1html .= "                                            <td bgcolor=\"#528ECE\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"25\"></td>";
            $kolfu1html .= "                                        </tr>";
            $kolfu1html .= "                                    </table>";
            $kolfu1html .= "                                </td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"140\" height=\"1\"></td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td>";
            $kolfu1html .= "                                    <center>";
            $kolfu1html .= "                                        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "                                            <tr>";
            $kolfu1html .= "                                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"3\"></td>";
            $kolfu1html .= "                                            </tr>";
            $kolfu1html .= "                                            <tr>";
            $kolfu1html .= "                                                <td  style=\"color:#aaaaaa;\"><center>".$langueberkolonien['uebersichtkolonien']['durchtemperatur']."<br></center></td>";
            $kolfu1html .= "                                            </tr>";
            $kolfu1html .= "                                            <tr>";
            $kolfu1html .= "                                                <td><center>".$temp-35;"&nbsp;".$langueberkolonien['uebersichtkolonien']['grad']."</center></td>";
            $kolfu1html .= "                                            </tr>";
            $kolfu1html .= "                                        </table>";
            $kolfu1html .= "                                    </center>";
            $kolfu1html .= "                                </td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                        </table>";
            $kolfu1html .= "                    </center>";
            $kolfu1html .= "                </td>";
            $kolfu1html .= "                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"8\" height=\"1\"></td>";
            $kolfu1html .= "                <td>";
            $kolfu1html .= "                    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/alleleute.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['kolonisten']."</td>";
            $kolfu1html .= "                       </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><nobr>".$kolonisten."/".$leichtebt."/".$schwerebt."</nobr></td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['cantox']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                           <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><nobr>".$cantox."</nobr></td>";
            $kolfu1html .= "                        </tr>";
            if (($native_id>=1) and ($native_kol>=1)) {
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                                <td rowspan=\"2\"><img src=\"".image_dir."icons/native_1.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['domspezies']."</td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><nobr><a href=\"javascript:nativedetail(".$native_id.");\" style=\"color:#ffffff\">".$native_name."</a></nobr></td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td rowspan=\"2\"><img src=\"".image_dir."icons/native_2.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['population']."</td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><nobr>".$native_kol."</nobr></td>";
            $kolfu1html .= "                            </tr>                ";
            } else {
            $kolfu1html .= "<tr>";
            $kolfu1html .= "                                <td rowspan=\"2\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td colspan=\"2\" style=\"color:#aaaaaa;\">&nbsp;</td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><nobr>&nbsp;</nobr></td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td rowspan=\"2\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td colspan=\"2\" style=\"color:#aaaaaa;\">&nbsp;</td>";
            $kolfu1html .= "                            </tr>";
            $kolfu1html .= "                            <tr>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                                <td><nobr>&nbsp;</nobr></td>";
            $kolfu1html .= "                            </tr>";
            }
            $kolfu1html .= "    </table>";
            $kolfu1html .= "                </td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"8\" height=\"1\"></td>";
            $kolfu1html .= "                <td>";
            $kolfu1html .= "                    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/minen.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['minen']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><nobr>".$minen."&nbsp;";
                                                            if ($auto_minen==1) { 
                                                            $kolfu1html .= " <i>(".$langueberkolonien['uebersichtkolonien']['auto'].")</i>";                                                            
                                                            }
            $kolfu1html .= "                                </nobr></td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/fabrik.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['fabriken']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                           <td><nobr>".$fabriken."&nbsp;";
                                                       if ($auto_fabriken==1) { 
            $kolfu1html .= "                            <i>(".$langueberkolonien['uebersichtkolonien']['auto'].")</i>";
                                                        } 
            $kolfu1html .= "                            </nobr></td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                           <td rowspan=\"2\"><img src=\"".image_dir."icons/abwehr.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['pds']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                           <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><nobr>".$abwehr."&nbsp;";
                                                            if ($auto_abwehr==1) { 
            $kolfu1html .= "                                <i>(".$langueberkolonien['uebersichtkolonien']['auto'].")</i>";
                                                           } 
            $kolfu1html .= "                            </nobr></td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/vorrat.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['vorraete']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><nobr>".str_replace('{1}',$vorrat,$langueberkolonien['uebersichtkolonien']['kt']); 
                                                            if ($auto_vorrat==1) { 
                                                                $kolfu1html .= " <i>(".$langueberkolonien['uebersichtkolonien']['auto'].")</i>";                                                                
                                                            } 
            $kolfu1html .= "</nobr></td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                    </table>";
            $kolfu1html .= "</td>";
            $kolfu1html .= "                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"8\" height=\"1\"></td>";
            $kolfu1html .= "                <td>";
            $kolfu1html .= "                    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "                       <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/lemin.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['lemin']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td>".str_replace('{1}',$lemin.'/'.$planet_lemin,$langueberkolonien['uebersichtkolonien']['kt'])."</td>";
            $kolfu1html .= "                            <td>&nbsp;(".$konz_lemin.")</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/mineral_1.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['baxterium']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td>".str_replace('{1}',$min1.'/'.$planet_min1,$langueberkolonien['uebersichtkolonien']['kt'])."</td>";
            $kolfu1html .= "                            <td>&nbsp;(".$konz_min1.")</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/mineral_2.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['rennurbin']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td>".str_replace('{1}',$min2.'/'.$planet_min2,$langueberkolonien['uebersichtkolonien']['kt'])."</td>";
            $kolfu1html .= "                            <td>&nbsp;(".$konz_min2.")</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td rowspan=\"2\"><img src=\"".image_dir."icons/mineral_3.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td colspan=\"2\" style=\"color:#aaaaaa;\">".$langueberkolonien['uebersichtkolonien']['vomisaan']."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"5\" height=\"1\"></td>";
            $kolfu1html .= "                            <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"15\" height=\"1\"></td>";
            $kolfu1html .= "                            <td>". str_replace('{1}',$min3.'/'.$planet_min3,$langueberkolonien['uebersichtkolonien']['kt'])."</td>";
            $kolfu1html .= "                            <td>&nbsp;(".$konz_min3.")</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                    </table>";
            $kolfu1html .= "                </td>";
            $kolfu1html .= "                <td><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"4\" height=\"1\"></td>";
            $kolfu1html .= "                <td>";
            $kolfu1html .= "                    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td>".$osys[1]."</td>";
            $kolfu1html .= "                            <td>".$osys[2]."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td>".$osys[3]."</td>";
            $kolfu1html .= "                            <td>".$osys[4]."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                        <tr>";
            $kolfu1html .= "                            <td>".$osys[5]."</td>";
            $kolfu1html .= "                            <td>".$osys[6]."</td>";
            $kolfu1html .= "                        </tr>";
            $kolfu1html .= "                    </table>";
            $kolfu1html .= "                </td>";
            $kolfu1html .= "                <td>";
            if ($sternenbasis==2) {
                                    if ($sternenbasis_art==1) {
                                        $icon='erf_1.gif';
                                        $artname=$langueberkolonien['uebersichtkolonien']['raumwerft'];
                                    }elseif ($sternenbasis_art==2) {
                                        $icon='erf_2.gif';
                                        $artname=$langueberkolonien['uebersichtkolonien']['kampfstation'];
                                    }elseif ($sternenbasis_art==0) {
                                        $icon='erf_3.gif';
                                        $artname=$langueberkolonien['uebersichtkolonien']['sternenbasis'];
                                    }elseif ($sternenbasis_art==3) {
                                        $icon='erf_5.gif';
                                        $artname=$langueberkolonien['uebersichtkolonien']['Kriegsbasis'];
                                    }
            $kolfu1html .= "<center>";
            $kolfu1html .= "                            <a href=\"".servername."pages/basen.php?fu=2&baid=".$sternenbasis_id."&uid=".$uid."&sid=".$sid."\" target=\"untenmitte\">";
            $kolfu1html .= "                                <img src=\"".image_dir."icons/".$icon."\" border=\"0\" title=\"".$artname."\">";
            $kolfu1html .= "                                <br>";
            $kolfu1html .= "                                <img src=\"".image_dir."icons/basis.gif\" border=\"0\" width=\"36\" height=\"36\" title=\"".$artname."\"></a>";            
            $kolfu1html .= "                        </center>";
                                } else {
                                    $kolfu1html .= "&nbsp;";
                                }
            $kolfu1html .= "                            </td>";
            $kolfu1html .= "            </tr>";
            $kolfu1html .= "            <tr>";
            $kolfu1html .= "                <td colspan=\"11\"><img src=\"".image_dir."empty.gif\" border=\"0\" width=\"1\" height=\"17\"></td>";
            $kolfu1html .= "            </tr>";            
                    }
            
            $kolfu1html .= "    </table>";
            $kolfu1html .= "</center>";
            $kolfu1html .= "</div>";      
        }
        $smarty->display('uebersicht/uebersicht_kolfu1.tpl');
    break;
}
