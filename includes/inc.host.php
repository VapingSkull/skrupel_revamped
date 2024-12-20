<?php

/*
 * Da wir nur die deutsche Sprache unterstützen lesen wir auch nur die Deutsche Sprache ein.
 */
$lang = get_phrasen('de', 'host');
///////////////////////////////Sprachinclude(nur die benoetigten) Ende 
/*
 * Sprachphrasen werden aus der Datenbank gezogen. Dies geht um ein vielfaches schneller und stehen sofort zur Verfügung und müssen nicht noch zusammengestückelt werden.
 * SkullCollector
 */
srand((double)microtime()*1000000);
mt_srand(time());
$mt_randmax=mt_getrandmax();
$schiffverschollen=0;
$neuekolonie=0;
$neueschiffe=0;
$neuebasen=0;
$schiffevernichtet=0;
$planetenerobert=0;
$planetenerobertfehl=0;
$sqld = "DELETE FROM " . table_prefix . "kampf  WHERE spiel = ?";
$db->execute($sqld, array($spiel));
$qld = "DELETE FROM " . table_prefix . "nebel  WHERE spiel = ?";
$db->execute($sqld, array($spiel));
$sqld = "DELETE FROM " . table_prefix . "scan WHERE spiel = ?";
$db->execute($sqld, array($spiel));
$sqld = "DELETE FROM " . table_prefix . "neuigkeiten WHERE sicher = ? AND spiel_id = ? AND art IN (?, ?, ?, ?, ?, ?)";
$db->Execute($sqld, array(0, $spiel, 1, 2, 3, 4, 7, 8));
///////////////////////////////////////////////////////////////////////////////////////////////RASSENEIGENSCHAFTEN ANFANG
/*
 * Sämtliche Daten wie Rassen , Waffen usw. werden in naher Zukunft in die Datenbank verschoben. Denn dieses zusammensetzen von Daten aus Dateien ist zu zeitaufwendig 
 * und schwer überschaubar bei Fehlern.
 */
$handle = opendir(daten_dir);
if ($handle) {
    while ($rasse = readdir($handle)) {
        if (is_dir(daten_dir.$rasse) && is_file(daten_dir.$rasse.'/daten.txt')) {
            $rassendaten = rasse_laden(daten_dir.$rasse.'/daten.txt');
            if ($rassendaten) {
                $r_eigenschaften[$rasse] = $rassendaten;
            }
        }
    }
    closedir($handle);
}
///////////////////////////////////////////////////////////////////////////////////////////////RASSENEIGENSCHAFTEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SPIELEREIGENSCHAFTEN ANFANG
for ($k=1; $k<=10; $k++) {
    $s_eigenschaften[$k]['rasse']=$spieler_rasse_c[$k];
}
///////////////////////////////////////////////////////////////////////////////////////////////SPIELEREIGENSCHAFTEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////BEGEGNUNGEN ANFANG
if ($module[4]==1) {
    $begegnungen = array();
    $sql20 = "SELECT partei_a,partei_b FROM " . table_prefix . "begegnung where spiel='" . $spiel . "' order by spiel";
    $rows20 = $db->execute($sql20);    
    $polanzahl = $rows20->RecordCount();
    if ($polanzahl>=1) {
        $array20_out = $db->getArray($sql20);
        foreach ($array20_out as $array20) {                
        $partei_a=$array20["partei_a"];
        $partei_b=$array20["partei_b"];
        $begegnung[$partei_a][$partei_b]=1;
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////BEGEGNUNGEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////WAFFENWERTE ANFANG
$strahlenschaden = array ('0','3','7','10','15','12','29','35','37','18','45');
$strahlenschadencrew = array ('0','1','2','2','4','16','7','8','9','33','11');
$torpedoschaden = array ('0','5','8','10','6','15','30','35','12','48','55');
$torpedoschadencrew = array ('0','1','2','2','13','6','7','8','36','12','14');
///////////////////////////////////////////////////////////////////////////////////////////////WAFFENWERTE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////STATS INITIALISIEREN ANFANG
$stat_sieg = array_fill(1, 10, 0);
$stat_schlacht = array_fill(1, 10, 0);
$stat_schlacht_sieg = array_fill(1, 10, 0);
$stat_kol_erobert = array_fill(1, 10, 0);
$stat_lichtjahre = array_fill(1, 10, 0);
///////////////////////////////////////////////////////////////////////////////////////////////STATS INITIALISIEREN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////POLITIKSTATUS ANFANG
//tabelle initialisieren
$beziehung = array_fill(1, 10, array_fill(1, 10, array('status'=>0, 'optionen'=>0)));

$sql4 = "SELECT partei_a,partei_b,status,optionen FROM " . table_prefix . "politik WHERE spiel='" . $spiel . "' order by spiel";
$zeiger4 = $db->getArray($sql4);
 foreach($zeiger4 as $array4){
    $partei_a = $array4["partei_a"];
    $partei_b = $array4["partei_b"];
    $status = $array4["status"];
    $optionen = $array4["optionen"];    
    $beziehung[$partei_a][$partei_b]['status']   = $status;
    $beziehung[$partei_b][$partei_a]['status']   = $status;
    $beziehung[$partei_a][$partei_b]['optionen'] = $optionen;
    $beziehung[$partei_b][$partei_a]['optionen'] = $optionen;
}
///////////////////////////////////////////////////////////////////////////////////////////////POLITIKSTATUS ENDE
///////////////////////////////////////////////////////////////////////////////////////////////ROUTESTARTEN ANFANG
$sql5  = "SELECT * FROM " . table_prefix . "schiffe where flug='0' and status='2' and routing_status='2' and spiel='" . $spiel . "' order by id";
$rows5 = $db->execute($sql5);
$schiffanzahl = $rows5->RecordCount();
if ($schiffanzahl>=1) {
    $array5_out = $db->getArray($sql5);
    foreach ($array5_out as $array5) {                
        $besitzer=$array5["besitzer"];
        $volk=$array5["volk"];
        $shid=$array5["id"];
        $name=$array5["name"];
        $bild_gross=$array5["bild_gross"];
        $routing_id=$array5["routing_id"];
        $routing_koord=$array5["routing_koord"];
        $routing_schritt=$array5["routing_schritt"];
        $routing_warp=$array5["routing_warp"];
        $routing_mins=$array5["routing_mins"];
        $routing_mins_temp=explode(":",$routing_mins);
        $mins=$routing_mins_temp[$routing_schritt];
        $mins_cantox=substr($mins,0,1);
        $mins_vorrat=substr($mins,1,1);
        $mins_lemin=substr($mins,2,1);
        $mins_min1=substr($mins,3,1);
        $mins_min2=substr($mins,4,1);
        $mins_min3=substr($mins,5,1);
        $leuts_kol=(int)substr($mins,7,7);
        $leuts_lbt=(int)substr($mins,14,4);
        $leuts_sbt=(int)substr($mins,18,4);
        $frachtraum=$array5["frachtraum"];
        $leichtebt=$array5["leichtebt"];
        $schwerebt=$array5["schwerebt"];
        $fracht_leute=$array5["fracht_leute"];
        $fracht_cantox=$array5["fracht_cantox"];
        $fracht_vorrat=$array5["fracht_vorrat"];
        $fracht_min1 = $array5["fracht_min1"];
        $fracht_min2 = $array5["fracht_min2"];
        $fracht_min3 = $array5["fracht_min3"];
        $voll_laden=substr($mins,6,1);
        if (isset($routing_koord) && $routing_koord!="")
        {
             if(($voll_laden!=1) or
                 ($mins_vorrat==1) or 
                 ($mins_min1==1) or 
                 ($mins_min2==1) or 
                 ($mins_min3==1) or 
                 ($leuts_kol==1) or 
                 ($leuts_kol>2) or 
                 ($leuts_lbt==1) or 
                 ($leuts_lbt>2) or 
                 ($leuts_sbt==1) or 
                 ($leuts_sbt>2)){
                 if(($voll_laden!=1) or 
                     ((round(($fracht_leute/100)+($leichtebt*0.3)+($schwerebt*1.5)+0.5)+$fracht_vorrat+$fracht_min1+$fracht_min2+$fracht_min3)>=$frachtraum)){
                    $routing_points_temp=explode("::",$routing_koord);
                    if ($routing_schritt==count($routing_points_temp)-2) {
                        $routing_schritt=0;} else {$routing_schritt++;
                    }
                    $routing_points=explode(":",$routing_points_temp[$routing_schritt]);
                    $routing_id_temp=explode(":",$routing_id);
                    $zielx=$routing_points[0];
                    $ziely=$routing_points[1];
                    $warp=$routing_warp;
                    $zielid=$routing_id_temp[$routing_schritt];
                    $sqlu = "update " . table_prefix . "schiffe set flug = ?,
                                                                    warp = ?,
                                                                    zielx = ?,
                                                                    ziely = ?,
                                                                    zielid = ?,
                                                                    routing_schritt = ? 
                                                              where id = ?";
                    $db->execute($sqlu, array(2,$warp,$zielx,$ziely,$zielid,$routing_schritt,$shid));
                } else {
                    $sqlu = "update " . table_prefix . "schiffe set flug = ?, 
                                                                    warp = ?, 
                                                                    zielx = ?, 
                                                                    ziely = ?, 
                                                                    zielid = ? 
                                                              where id = ?";
                    $db->execute($sqlu,array(0,0,0,0,0,$shid));
                }
            } else {
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug9'],array($name));
                $sqlu = "update " . table_prefix . "schiffe set flug = ?, 
                                                                warp = ?, 
                                                                zielx = ?, 
                                                                ziely = ?, 
                                                                zielid = ?, 
                                                                routing_schritt = ?, 
                                                                routing_koord = ?, 
                                                                routing_warp = ?, 
                                                                routing_mins = ?, 
                                                                routing_id = ?, 
                                                                routing_tank = ?, 
                                                                routing_status = ? 
                                                          where id = ?";
                $db->execute($sqlu, array(0,0,0,0,0,0,'',0,'','',0,0,$shid));
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////ROUTESTARTEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////MINENAKTION LOESCHEN BEI BEWEGUNG ANFANG
if ($module[2]) {
    $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ? where spiel = ? and flug>=? and spezialmission in (? , ?)";
    $db->execute($sqlu, array(0,$spiel,1,24,25));
}
///////////////////////////////////////////////////////////////////////////////////////////////MINENAKTION LOESCHEN BEI BEWEGUNG ENDE
///////////////////////////////////////////////////////////////////////////////////////////////TRAKTORSTRAHL UEBERPRUEFEN ANFANG
$sql10 = "SELECT id,traktor_id,besitzer,warp FROM " . table_prefix . "schiffe where spezialmission='21' and spiel='".$spiel."' order by id";
$array_out10 = $db->getArray($sql10);
/*
 * Wenn obige Abfrage keine Ergebnis enthält, dann überspringe diesen Bereich.
 */
if(!empty ($array_out10)){
foreach ($array_out10 as $array10) {    
    $shid = $array10["id"];
    $warp = $array10["warp"];
    $besitzer  = $array10["beitzer"];
    $traktor_id = $array10["traktor_id"];
    if ($warp>7) {
        $sqlu = "UPDATE " . table_prefix . "schiffe SET warp = ? WHERE id = ? AND spiel = ?";
        $db->execute($sqlu, array(7,$shid,$spiel));
    }
}
    $zeiger2  = "SELECT flug,besitzer,spezialmission FROM " . table_prefix . "schiffe WHERE id='" . $traktor_id ."' AND spiel='" . $spiel ."' order by id";
    $array_out2 = $db->getArray($zeiger2);
    foreach ($array_out2 as $array2){
    $flug = $array2["flug"];
    $besitzer2 = $array2["besitzer"];
    $spezialmission = $array2["spezialmission"];    
    if ($flug>0 || $spezialmission>0 || $besitzer!=$besitzer2) {
        $sqlu = "UPDATE " . table_prefix . "schiffe SET spezialmission = ?, traktor_id = ? WHERE id = ? AND spiel = ?";
        $db->execute($sqlu, array(0,0,$shid,$spiel));
    }
 }
}
///////////////////////////////////////////////////////////////////////////////////////////////TRAKTORSTRAHL UEBERPRUEFEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSUEBERGABE ANFANG
$sql3 = "SELECT * FROM " . table_prefix . "schiffe where spezialmission>=31 and spezialmission<=40 and !(volk='unknown' and klasseid=1) and spiel='" . $spiel . "' order by id";
$array_out3 = $db->getArray($sql3);
foreach ($array_out3 as $array3) {
    $shid=$array3['id'];
    $name=$array3['name'];
    $volk=$array3['volk'];
    $besitzer=$array3['besitzer'];
    $bild_gross=$array3['bild_gross'];
    $spezialmission=$array3['spezialmission'];
    $neu_besitzer = $spezialmission-30;
    $neu_nick_besitzer = nick($spieler_id_c[$neu_besitzer]);
    $nick_besitzer = nick($spieler_id_c[$besitzer]);
    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['uebergabe0'],array($name,'<font color='.$spielerfarbe[$neu_besitzer].'>'.$neu_nick_besitzer.'</font>'));
    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$neu_besitzer,$lang['host']['uebergabe1'],array('<font color='.$spielerfarbe[$besitzer].'>'.$nick_besitzer.'</font>',$name));
    $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ?, 
                                                    besitzer = ?,
                                                    fracht_leute = ?,
                                                    schwerebt = ?, 
                                                    leichtebt = ?, 
                                                    ordner = ? 
                                                    where id = ?";
    $db->execute($sqlu, array(0,$neu_besitzer,0,0,0,0,$shid));
}
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSUBERGABE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////PLASMASTURM - SCHIFFE ANFANG
$sql6 = "SELECT * FROM " . table_prefix . "anomalien where spiel='" . $spiel . "' and art='4' order by id";
$rows6 = $db->execute($sql6);
$datensaetze = $rows6->RecordCount();
if ($datensaetze>=1) {
    $array6_out = $db->getArray($sql6);
    foreach  ($array6_out as $array6) {
        
        $x_pos=$array6["x_pos"];
        $y_pos=$array6["y_pos"];
        $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ? where 
                                                             spezialmission in (?,?,?,?,?,?,?) 
                                                             and kox>=? 
                                                             and kox<=?+10 
                                                             and koy>=? 
                                                             and koy<=?+10 
                                                             and zusatzmodul<>? 
                                                             and spiel = ?";
        $db->execute($sqlu, array(0,7,8,9,10,11,12,13,$x_pos,$x_pos,$y_pos,$y_pos,9,$spiel));
        
        $sql7 = "SELECT id,warp,plasmawarp FROM " . table_prefix . "schiffe where warp>5 and kox>=" . $x_pos . " and kox<=" . $x_pos . "+10 and koy>=" . $y_pos ." and koy<=" . $y_pos . "+10 and zusatzmodul<>9 and spiel='" . $spiel . "' order by id";
        $rows7 = $db->execute($sql7);
        $datensaetze_temp = $rows7->RecordCount();
        if ($datensaetze_temp>=1) {
            $array7_out = $db->getArray($sql7);
            foreach  ($array7_out as $array7) {                
                $shid = $array7["id"];
                $warp = $array7["warp"];
                $plasmawarp = $array7["plasmawarp"];
                $plasmawarp = max(0,$warp,$plasmawarp);
                $sqlu = "UPDATE " . table_prefix . "schiffe set plasmawarp = ?, warp = ? where spiel = ? and id = ?";
                $db->execute($sqlu, array($plasmawarp,5,$spiel,$shid));
            }
        }
    }
}
$sql8  = "SELECT * FROM " . table_prefix . "schiffe where spiel='" . $spiel . "' and plasmawarp<>0 order by id";
$rows8 = $db->execute($sql8);
$datensaetze = $rows8->RecordCount();
if ($datensaetze>=1) {
    $array8_out = $db->getArray($sql8);
    foreach ($array8_out as $array8) {
        $shid=$array8["id"];
        $kox=$array8["kox"];
        $koy=$array8["koy"];
        $plasmawarp=$array8["plasmawarp"];
        $sql9 = "SELECT * FROM " . table_prefix . "anomalien where art='4' and x_pos<=" . $kox . " and x_pos>=" . $kox . "-10 and y_pos<=". $koy ." and y_pos>=" . $koy ."-10 and spiel='" . $spiel . "' order by id";
        $rows9 = $db->execute($sql9);
        $ds = $rows9->RecordCount();        
        if ($ds >=1){
        } else {
            $sqlu = "UPDATE " . table_prefix . "schiffe set warp = ?, plasmawarp = ? where spiel = ? and id = ?";
            $db->execute($sqlu, array($plasmawarp,0,$spiel,$shid));
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////PLASMASTURM - SCHIFFE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////BODENKAMPF ANFANG
/*
 * inc.host_bodenkampf angepaßt
 */
$sql11 = "SELECT * FROM " . table_prefix . "planeten where kolonisten_spieler>=1 and ((kolonisten_new>=1) or (leichtebt_new>=1) or (schwerebt_new>=1)) and besitzer>=1 and spiel='" . $spiel . "' order by id";
$rows11 = $db->execute($sql11);
$planetenanzahl = $rows11->RecordCount();
if ($planetenanzahl>0) {
    include(includes . 'inc.host_bodenkampf.php');
}
///////////////////////////////////////////////////////////////////////////////////////////////BODENKAMPF ENDE
///////////////////////////////////////////////////////////////////////////////////////////////WELLENGENERATOR ANFANG
$reichweite = 65;
$sql12 =  "SELECT * FROM " . table_prefix . "schiffe WHERE spezialmission='70' and spiel='" . $spiel . "' order by id";
$array12_out = $db->getArray($sql12);
foreach ($array12_out as $array12) {
    $shid = $array12["id"];
    $name = $array12["name"];
    $kox = $array12["kox"];
    $koy = $array12["koy"];
    $volk = $array12["volk"];
    $besitzer = $array12["besitzer"];
    $bild_gross = $array12["bild_gross"];
    $vomisaan = $array12["fracht_min3"];
    $fertigkeiten=$array12["fertigkeiten"];
    $wellengenerator_fert=intval(substr($fertigkeiten,60,1));
    if ($wellengenerator_fert>=1 && $vomisaan >= $wellengenerator_fert) {
        $erfolg = false;
        $sql13 = "SELECT * FROM " . table_prefix . "schiffe WHERE (sqrt(((kox-" . $kox .")*(kox-" . $kox . "))+((koy-".$koy.")*(koy-".$koy.")))<=".$reichweite.") and spiel='" . $spiel . "' order by id";
        $array13_out = $db->getArray($sql13);
        foreach($array13_out as $array13) {
            $t_shid = $array13["id"];
            $t_name = $array13["name"];
            $t_volk = $array13["volk"];
            $t_besitzer = $array13["besitzer"];
            $t_bild_gross = $array13["bild_gross"];
            $t_spezialmission = $array13["spezialmission"];
            $t_warp = $array13["warp"];
            $zielx = $array13["kox"];
            $ziely = $array13["koy"];
            $lichtjahre2 = ($kox-$zielx)*($kox-$zielx)+($koy-$ziely)*($koy-$ziely);
            if($lichtjahre2 <= $reichweite*$reichweite) {
                if(($t_spezialmission!=7 && $t_spezialmission!=16) || $t_besitzer==$besitzer || ($beziehung[$besitzer][$t_besitzer]['status']>=3 && $beziehung[$besitzer][$t_besitzer]['status']<=5)) {
                    if($t_warp > 7) {
                        neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['wellengenerator2'],array($t_name));
                        $sqlu = "UPDATE " . table_prefix . "schiffe set warp = ? where id = ?";
                        $db->execute($sqlu , array(7,$t_shid));
                    }
                } else {
                    $erfolg = true;
                    neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['wellengenerator0'],array($t_name));
                    $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ?, 
                                                                         warp = ?, 
                                                                         flug = ?, 
                                                                         zielx = ?, 
                                                                         ziely = ?, 
                                                                         zielid = ? 
                                                                   where id = ?";
                    $db->execute($sqlu, array(0,0,0,0,0,0,$t_shid));
                }
            }
        }
        $vomisaan -= $wellengenerator_fert;
        $sqlu = "UPDATE " . table_prefix . "schiffe set fracht_min3 = ? where id = ?";
        $db->execute($sqlu, array($vormisaan, $shid));
        if ($erfolg) {
            neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['wellengenerator3'],array($name));
        }
    } else {
        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['wellengenerator1'],array($name));
        $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ? where id = ?";
        $db->execute($sqlu, array(0,$shid));
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////WELLENGENERATOR ENDE

///////////////////////////////////////////////////////////////////////////////////////////////SPRUNGTRIEBWERK ANFANG
$sql20 = "SELECT * FROM " . table_prefix . "schiffe where flug>=1 and flug<=2 and status>0 and spezialmission=7 and spiel='" . $spiel . "'order by id";
$rows20 = $db->execute($sql20);
$schiffanzahl = $rows20->RecordCount();
if ($schiffanzahl>=1) {
    $array20_out = $db->getArray($sql20);
    foreach ($array20_out as $array20) {        
        $shid=$array20["id"];
        $name=$array20["name"];
        $kox=$array20["kox"];
        $koy=$array20["koy"];
        $flug=$array20["flug"];
        $zielx=$array20["zielx"];
        $ziely=$array20["ziely"];
        $volk=$array20["volk"];
        $besitzer=$array20["besitzer"];
        $bild_gross=$array20["bild_gross"];
        $lemin=$array20["lemin"];
        $fertigkeiten=$array20["fertigkeiten"];
        $spezialmission=$array20["spezialmission"];
        $status=$array20["status"];
        $fert_sprung_kosten=intval(substr($fertigkeiten,11,3));
        $fert_sprung_min=intval(substr($fertigkeiten,14,4));
        $fert_sprung_max=intval(substr($fertigkeiten,18,4));
        if ($lemin>=$fert_sprung_kosten) {
            $lichtjahre=sqrt(($kox-$zielx)*($kox-$zielx)+($koy-$ziely)*($koy-$ziely));
            $reichweite=mt_rand($fert_sprung_min,$fert_sprung_max);
            $faktor=$reichweite/$lichtjahre;
            $strecke_x=($zielx-$kox)*$faktor;
            $strecke_y=($ziely-$koy)*$faktor;
            $kox_neu=$kox+$strecke_x;
            $koy_neu=$koy+$strecke_y;
            $lemin=$lemin-$fert_sprung_kosten;
            $rand_x_a = max(min($kox,$kox_neu),1);
            $rand_x_b = min(max($kox,$kox_neu),$umfang);
            $rand_y_a = max(min($koy,$koy_neu),1);
            $rand_y_b = min(max($koy,$koy_neu),$umfang);
            $sql21 =  "SELECT * FROM " . table_prefix . "schiffe WHERE kox>=" . $rand_x_a . " and kox<=" . $rand_x_b . " and koy>=" . $rand_y_a . " and koy<=" . $rand_y_b . " and spezialmission='70' and spiel='" . $spiel . "' order by kox ".($kox<$kox_neu?"asc":"desc").", koy ".($koy<$koy_neu?"asc":"desc");
            $array21_out = $db->getArray($sql21);
            foreach($array21_out as $array21) {
                $t_kox = $array21["kox"];
                $t_koy = $array21["koy"];
                $t_besitzer = $array21["besitzer"];
                $t_name = $array21["name"];
                $t_volk = $array21["volk"];
                $t_bild_gross = $array21["bild_gross"];
                if ($t_besitzer != $besitzer && $beziehung[$besitzer][$t_besitzer]['status'] < 3) {
                    $co1 = (($t_kox-$kox)*($kox_neu-$kox)+($t_koy-$koy)*($koy_neu-$koy));
                    $c_ = sqrt(($t_kox-$kox)*($t_kox-$kox)+($t_koy-$koy)*($t_koy-$koy));
                    $a_ = sin(acos($co1/(($c_*$reichweite)+1)))*$c_;
                    if ($a_ <= 65) {
                        $reichweite2 = sqrt($c_*$c_ - $a_*$a_);
                        $faktor = $reichweite2/$lichtjahre;
                        $kox_neu = intval($kox+($zielx-$kox)*$faktor);
                        $koy_neu = intval($koy+($ziely-$koy)*$faktor);
                        neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['wellengenerator4'],array($t_name));
                        neuigkeiten(2,servername . "images/news/sprung.jpg",$besitzer,$lang['host']['sprungtriebwerk3'],array($name,(int)$reichweite2));
                        $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ?, kox = ?, koy = ?, lemin = ?, flug = ?, status = ? where id = ?";
                        $db->execute($sqlu, array(0,$kox_neu,$koy_neu,$lemin,0,1,$shid));
                        continue(2);
                    }
                }
            }
            if (($kox_neu>=10) and ($kox_neu<=$umfang-13) and ($koy_neu>=10) and ($koy_neu<=$umfang-13)) {
                neuigkeiten(2, servername . "images/news/sprung.jpg",$besitzer,$lang['host']['sprungtriebwerk0'],array($name,$reichweite));
                $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ?,kox = ?, koy = ?, lemin = ?, flug = ?, status = ? where id = ?";
                $db->execute($sqlu, array(0,$kox_neu, $koy_neu, $lemin,0,1,$shid));
            } else {
                $schiffverschollen++;
                neuigkeiten(2, servername . "images/news/sprung.jpg",$besitzer,$lang['host']['sprungtriebwerk1'],array($name));
                $sqld = "DELETE FROM " . table_prefix . "schiffe where id = ?";
                $db->execute($sqld, array($shid));
                $shids = "s:".$shid.":%";
                $sqld ="DELETE FROM " . table_prefix . "anomalien where art = ? and extra like ?";
                $db->execute($sqld, array(3,$shids));
                $sqlu = "UPDATE " . table_prefix . "schiffe set flug = ?, 
                                                                warp = ?, 
                                                                zielx = ?, 
                                                                ziely = ?, 
                                                                zielid = ? 
                                                                where flug = ? 
                                                            and zielid = ?";
                $db->execute($sqlu, array(0,0,0,0,0,3,$shid));
            }
        } else {
            neuigkeiten(2,servername . "images/news/sprung.jpg",$besitzer,$lang['host']['sprungtriebwerk2'],array($name));
            $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ?, 
                                                            flug = ?  
                                                      where id = ?";
            $db->execute($sqlu , array(0,0,$shid));
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////SPRUNGTRIEBWERK ANFANG

///////////////////////////////////////////////////////////////////////////////////////////////SUBRAUMVERZERRUNG ANFANG
$sql22 = "SELECT * FROM " . table_prefix . "schiffe where spezialmission='9' and spiel='" . $spiel . "' order by id";
$rows22 = $db->execute($sql22);
$schiffanzahl = $rows22->RecordCount();
if ($schiffanzahl>=1) {
    $array22_out = $db->getArray($sql22);
    foreach ($array22_out as $array22) {
        $shid=$array22["id"];
        $name=$array22["name"];
        $klasse=$array22["klasse"];
        $antrieb=$array22["antrieb"];
        $klasseid=$array22["klasseid"];
        $kox=$array22["kox"];
        $koy=$array22["koy"];
        $volk=$array22["volk"];
        $besitzer=$array22["besitzer"];
        $bild_gross=$array22["bild_gross"];
        $fertigkeiten=$array22["fertigkeiten"];
        $fert_subver=intval(substr($fertigkeiten,23,1));
        $sub_schaden=$fert_subver*50;
        $sql23 = "SELECT * FROM " . table_prefix . "schiffe where (sqrt((($kox-kox)*($kox-kox))+(($koy-koy)*($koy-koy)))<=83) and spezialmission<>9 and spiel='" . $spiel . "' order by id";
        $rows23 = $db->execute($sql23);
        $treffschiff = $rows23->RecordCount();
        if ($treffschiff>=1) {
            $array23_out = $db->getArray($sql23);
            foreach ($array23_out as $array23) {                
                $t_shid=$array23["id"];
                $t_name=$array23["name"];
                $t_klasse=$array23["klasse"];
                $t_antrieb=$array23["antrieb"];
                $t_klasseid=$array23["klasseid"];
                $t_volk=$array23["volk"];
                $t_besitzer=$array23["besitzer"];
                $t_bild_gross=$array23["bild_gross"];
                $t_schaden=$array23["schaden"];
                $t_masse=$array23["masse"];
                $zielx=$array23["kox"];
                $ziely=$array23["koy"];
                $schaden=round($t_schaden+($sub_schaden*(80/($t_masse+1))*(80/($t_masse+1))+2));
                if ($schaden<100) {
                    neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['subraumverzerrer0'],array($t_name,$schaden));
                   $sqlu = "UPDATE" . table_prefix . "schiffe set schaden = ? where id = ?";
                   $db->execute($sqlu , array($shid,$t_shid));
                }
                if ($schaden>=100) {
                    neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['subraumverzerrer1'],array($t_name));
                    $sqld = "DELETE FROM " . table_prefix . "schiffe where id = ?";
                    $db->execute($sqld, array($t_shid));
                    $sqld = "DELETE FROM " . table_prefix . "anomalien where art = ? and extra like ?";
                    $t_shids = "s:".$_tshid.":%";
                    $db->execute(3, $t_shids);
                    $sqlu = "UPDATE " . table_prefix . "schiffe set flug = ?, 
                                                                    warp = ?, 
                                                                    zielx = ?, 
                                                                    ziely = ?, 
                                                                    zielid = ? 
                                                              where flug in (?,?) 
                                                             and zielid = ?";
                    $db->execute($sqlu, array(0,0,0,0,0,3,4,$t_shid));
                }
            }
        }
        $sql24 = "SELECT id FROM " . table_prefix . "anomalien where (sqrt(($kox-x_pos)*($kox-x_pos)+($koy-y_pos)*($koy-y_pos))<=83) and art='3' and spiel='" . $spiel . "' order by id";
        $rows24 = $db->execute($sql24);
        $trefffalte = $rows24->RecordCount();
        if ($trefffalte>=1) {
            $array24_out = $db->getArray($sql24);
            foreach ($array24_out as $array24) {                               
                $fid=$array24["id"];
                $war=mt_rand(1,10);
                if($war<=$fert_subver){
                    $sqld = "DELETE FROM " . table_prefix . "anomalien where id = ?";
                    $db->execute($sqld , array($fid));
                }
            }
        }
        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['subraumverzerrer2'],array($name));
        $sqld = "DELETE FROM " . table_prefix . "schiffe where id = ?";
        $db->execute($sqld, array($shid));
        $sqld = "DELETE FROM " . table_prefix . "anomalien where art = ? and extra like ?";
        $shids = "s:".$shid.":%";
        $db->execute($sqld, array(3,$shids));
        $sqlu = "UPDATE " . table_prefix . "schiffe set flug = ?, 
                                                        warp = ?, 
                                                        zielx = ?, 
                                                        ziely = ?, 
                                                        zielid = ? 
                                                 where flug in (?,?) 
                                                 and zielid = ?";
        $db->execute($sqlu, array(0,0,0,0,0,3,4,$shid));
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////SUBRAUMVERZERRUNG ENDE
///////////////////////////////////////////////////////////////////////////////////////////////LOYDS FLUCHTMANOEVER ANFANG
$sql25  = "SELECT * FROM " . table_prefix . "schiffe where spezialmission='16' and spiel='" . $spiel . "' order by spiel";
$rows25 = $db->execute($sql25);
$schiffanzahl = $rows25->RecordCount();
if ($schiffanzahl>=1) {
    $arra25_out = $db->getArray($sql25);
    foreach ($array25_out as $array25) {        
        $shid=$array25["id"];
        $name=$array25["name"];
        $volk=$array25["volk"];
        $schaden=$array25["schaden"];
        $besitzer=$array25["besitzer"];
        $bild_gross=$array25["bild_gross"];
        $spezialmission=$array25["spezialmission"];
        $fertigkeiten=$array25["fertigkeiten"];
        $s_x=$array25["s_x"];
        $s_y=$array25["s_y"];
        $fluchtmanoever=intval(substr($fertigkeiten,38,2));
        $kox=$s_x;
        $koy=$s_y;
        if ($fluchtmanoever==1) {
            $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ?, 
                                                                       kox = ?, 
                                                                       koy = ?, 
                                                                      flug = ?, 
                                                                      warp = ?, 
                                                                     zielx = ?, 
                                                                     ziely = ?,
                                                                    zielid = ? 
                                                        where id = ?";
            $db->execute($sqlu, array(0,$kox,$koy,0,0,0,0,0,$shid));
            $sqlu = "UPDATE " . table_prefix . "schiffe set flug = ?, 
                                                            warp = ?, 
                                                           zielx = ?, 
                                                           ziely = ?, 
                                                           zielid = ? 
                                                where flug in (?,?) and zielid = ?";
            $db->execute($sqlu, array(0,0,0,0,0,3,4,$shid));
            neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['fluchtmanoever0'],array($name));
        }
        if ($fluchtmanoever>=2) {
            $schadenbumm=mt_rand(1,$fluchtmanoever);
            $schaden=$schaden+$schadenbumm;
            if ($schaden<100) {
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['fluchtmanoever1'],array($name,$schadenbumm));
                $sqlu = "UPDATE " . table_prefix . "schiffe set flug = ?, 
                                                                warp = ?, 
                                                                zielx = ?, 
                                                                ziely = ?, 
                                                                zielid = ? 
                                                    where flug in (?,?) and zielid = ?";
                $db->execute($sqlu, array(0,0,0,0,0,3,4,$shid));
                $sqlu = "UPDATE " . table_prefix . "schiffe set schaden = ?, 
                                                                spezialmission = ?, 
                                                                kox = ?, 
                                                                koy = ?, 
                                                                flug = ?, 
                                                                warp = ?, 
                                                                zielx = ?, 
                                                                ziely = ?, 
                                                                zielid = ? 
                                                    where id = ?";
                $db->execute($sqlu, array($schaden,0,$kox,$koy,0,0,0,0,0,$shid));
            }
            if ($schaden>=100) {
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['fluchtmanoever2'],array($name));
                $sqld = "DELETE FROM " . table_prefix . "schiffe where id = ?";
                $db->execute($sqld, array($shid));
                $shids = "s:".$shid.":%";
                $sqld = "DELETE FROM " . table_prefix . "sanomalien where art = ? and extra like ?";
                $db->execute($sqld,array(3,$shids));
                $sqlu = "UPDATE " . table_prefix . "schiffe set flug = ?, 
                                                                warp = ?, 
                                                                zielx = ?, 
                                                                ziely = ?, 
                                                                zielid = ? 
                                                    where flug in (?,?) and zielid = ?";
                $db->execute($sqlu, array(0,0,0,0,0,3,4,$shid));
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////LOYDS FLUCHTMANOEVER ENDE
///////////////////////////////////////////////////////////////////////////////////////////////FLUG ANFANG
//alte Koordinaten und temp_verfolgt nullen
$sqlu = "UPDATE " . table_prefix . "schiffe SET kox_old = ?, koy_old = ?, temp_verfolgt = ? WHERE spiel = ?";
$db->execute($sqlu, array(0,0,0,$spiel));
//Verfolger auf 1 setzen
$sqlu = "UPDATE " . table_prefix . "schiffe SET temp_verfolgt = ? WHERE spiel = ? AND flug>?";
$db->execute($sqlu, array(1,$spiel,2));
//setze temp_verfolgt auf 1 bei allen Schiffen die verfolgt werden
$sql26 = "SELECT DISTINCT zielid FROM " . table_prefix . "schiffe use index (flug,spiel) where flug>2 and spiel='" . $spiel . "'";
$array26_out = $db->getArray($sql26);
foreach($array26_out as $array26) {
    $zid = $array26['zielid'];
    $sqlu = "UPDATE " . table_prefix . "schiffe SET temp_verfolgt = ? WHERE spiel = ? AND id = ?";
    $db->execute($sqlu, array(1,$spiel,$zid));
}
$sql27 = "SELECT id,zielid,flug,kox,koy FROM " . table_prefix . "schiffe use index (spiel,status) WHERE flug>0 AND status>0 AND spiel='" . $spiel . "' AND temp_verfolgt='1' ORDER BY zielid DESC";
$rows26 = $db->execute($sql26);
$schiffanzahl = $rows26->RecordCount();
if($schiffanzahl>0){
    $array26_out = $db->getArray($sql26);
    for  ($i=0; $i< $schiffanzahl;$i++) {
    foreach ($array26_out as $array26) {        
        $feld_shid[$i]=$array26["id"];
        $feld_zielid[$i]=$array26["zielid"];
        $feld_flug[$i]=$array26["flug"];
        $feld_kox[$i]=$array26["kox"];
        $feld_koy[$i]=$array26["koy"];
        $feld_flags[$i]=0;
        $feld_schlange[$i]=0;
    }    
    }
    //Beginn der Herstellung der Abarbeitungsreihenfolge fuer den Flug
    for  ($i=0; $i< $schiffanzahl;$i++) {
        //nur noch nicht bearbeitete Objekte bearbeiten
        if($feld_schlange[$i]==0){
            $j=$i;
            $anzahl_schlange=0;
            $abbruch_1=1;
            while($abbruch_1){
                $anzahl_schlange++;
                $feld_flags[$j]=$anzahl_schlange;
                $test_ob_zielid_vorhanden=array_search($feld_zielid[$j],$feld_shid);
                if($feld_shid[$test_ob_zielid_vorhanden]==$feld_zielid[$j]){
                    $test_ob_zielid_vorhanden++;
                }else{
                }
                //Test ob ein Schiff das Ende einer normalen Schlange ist
                if(($feld_flug[$j]>2)&&($test_ob_zielid_vorhanden!=false)){
                    $j=$test_ob_zielid_vorhanden-1;
                    //hier haben wir einen Kreis entdeckt
                    if($feld_flags[$j]) {
                        $zwischenwert=-$feld_flags[$j]-1;
                        $j=$i;
                        $abbruch_2=1;
                        do{
                            //wir setzen alle Schiffe in der Schlange die vor dem Kreis sind auf -1< je nach position in der Schlange und alle im Kreis auf -1
                            if($feld_flags[$j]==0){
                                $abbruch_2=0;
                            }else{
                                $feld_schlange[$j]=min(++$zwischenwert,-1);
                                $feld_flags[$j]=0;
                                $j=array_search($feld_zielid[$j],$feld_shid);
                            }
                        }while($abbruch_2);
                        $abbruch_1=0;
                    }else{
                        //hier treffen wir das Ende einer Schon bearbeiteten Schlange
                        if($feld_schlange[$j]!=0){
                            $abbruch_1=0;
                            if($feld_schlange[$j]< 0){
                                //hier treffen wir eine Minusschlange also eine die in einem Kreis endet
                                //wir fuegen also noch betragsmaessig groessere Minuswerte an
                                $zwischenwert=$feld_schlange[$j]-$anzahl_schlange;
                                $j=$i;
                                do{
                                    $abbruch_2=0;
                                    $feld_schlange[$j]=$zwischenwert++;
                                    if($feld_flags[$j]==0){
                                        $abbruch_2=0;
                                    }
                                    $feld_flags[$j]=0;
                                    $j=array_search($feld_zielid[$j],$feld_shid);
                                }while($abbruch_2);
                            }else{
                                //hier treffen wir das ende einer Ordentlichen Schlange wir merken uns wieder die Positionen erhoeht um die des Endes auf das wir trafen
                                $zwischenwert=$feld_schlange[$j]+$anzahl_schlange;
                                $j=$i;
                                for($k=0;$k<$anzahl_schlange;$k++){
                                    $feld_schlange[$j]=$zwischenwert-$k;
                                    $feld_flags[$j]=0;
                                    $j=array_search($feld_zielid[$j],$feld_shid);
                                }
                            }
                        }
                    }
                }else{
                    //hier haben wir also eine ordentlich schlange die mit eine Schiff endet das kein anderes verfolgt
                    $abbruch_1=0;
                    $j=$i;
                    //wir merken uns schonmal welche wir Schiffe bearbeitet haben und auch an welcehr position sie in der schlange waren
                    for($k=0;$k< $anzahl_schlange;$k++){
                        $feld_schlange[$j]=$anzahl_schlange-$k;
                        $feld_flags[$j]=0;
                        $j=array_search($feld_zielid[$j],$feld_shid);
                    }
                }
            }
        }
    }
}
//So wir haben jetzt folgende Situation:
//Alle Schiffe in Normalen schlangen dh die Mit einem Schiff beginnen das nix verfogt(wert1)und mit einem/mehreren Enden die nicht verfogt werden(groesserer schlangepositionswert) haben einen positiven schlangewert
//Alle Schiffe die in einem Kreis enden dh ein oder Mehrer Schiffe im Ringel die sich verfolgen haben  eine -1 wenn sie dierekt im Kreisel sind oder noch betragsmaessig groessere negative werte wenn sie zum Kreis hinfuehren auch hier kann es sich nach hinten spalten
//wir muessen jetzt noch diese Kreisel aufbrechen dies machen wir in dem wir fuer jeden Kreis(jeweil mit -1) das Geometrische Mittel MP der Koordinaten bestimmen und dann das schiff aus dem Kreis welches den Groessten abstand dazu hat als erstes Schiff einer Neuen ordentlichen Schlange nehmen
//mit Endzielpunk MP, so erhalten wir die Geringste abweichung von einem Tatsaechlichem stetigen resultat einer normalen bewegung, dabei muessen wir aber die Zielid der Schiffe in einem Array fuer alle Kreisel vom  ende der schlange speichern um sie nach dem Flug wieder einzusetzen
$kreisel_anzahl=0;
if($schiffanzahl>0){
    do{
        //wir suchen den ersten kreisel
        $i=array_search(-1,$feld_schlange);
        if($feld_schlange[$i]==-1){
            $schalter=1;
        }else{
            $schalter=0;
        }
        if($schalter){
            $kreisel_anzahl++;
            $j=$i;
            $anzahl_schlange=0;
            $MP_x=0;
            $MP_y=0;
            do{
                $feld_flags[$j]=1;
                $j=array_search($feld_zielid[$j],$feld_shid);
                $MP_x+=$feld_kox[$j];
                $MP_y+=$feld_koy[$j];
                $anzahl_schlange++;
            }while(!$feld_flags[$j]);
            $MP_x=round($MP_x/$anzahl_schlange);
            $MP_y=round($MP_y/$anzahl_schlange);
            //Suche aus schiffen von eben das schiff mit der Groessten entfernung zu MP heraus Speichere zielid shid des schiffes in zweitem array zwischenarray
            $entfernung_1=9;
            for($k=0;$k< $anzahl_schlange;$k++){
                $j=array_search($feld_zielid[$j],$feld_shid);
                $sql28 = "SELECT warp FROM " . table_prefix . "schiffe where id='" . $feld_shid[$j] . "' and spiel='" . $spiel . "'";                
                $entfernung_2=$db->getOne($sql28);                
                if($entfernung_2<=$entfernung_1){
                    $feldindex_maxentfernung=$j;
                    $entfernung_1=$entfernung_2;
                }
            }
            $zwischenarray_shid[$kreisel_anzahl-1]=$feld_shid[$feldindex_maxentfernung];
            $zwischenarray_zielid[$kreisel_anzahl-1]=$feld_zielid[$feldindex_maxentfernung];
            //neues Temporaeres ziel
            $sqlu = "UPDATE " . table_prefix . "schiffe set zielx = ?, 
                                                                 ziely = ?, 
                                                                 zielid = ? 
                                                where spiel = ?  and id = ?";
            $db->execute($sqlu,array($MP_x,$MP_y,'-1',$spiel,$feld_shid[$feldindex_maxentfernung]));
            //jetzt machen wir endlich aus dem Kreisel eine Schlange
            $ind=$feldindex_maxentfernung;
            for($k=$anzahl_schlange;$k>0;$k--){
                $ind=array_search($feld_zielid[$ind],$feld_shid);
                $feld_schlange[$ind]=$k;
            }
        }
    }while($schalter);
}
//jetzt muessen wir nur noch die Moegliche seitenbuschel als Aeste an die Schlangen anhaengen
//solange wie Schiff mit Schlange < -1 existiert{
//hier tragen wir ein ob kleiner minus eins oder nicht
for($i=0; $i<$schiffanzahl;$i++) {
    if($feld_schlange[$i]<-1) {
        $j=$i;
        do{
            $j=array_search($feld_zielid[$j],$feld_shid);
        }while($feld_schlange[$j]<1);
        $wert=$feld_schlange[$j]-$feld_schlange[$i];
        $j=$i;
        do{
            $feld_schlange[$j]=--$wert;
            $j=array_search($feld_zielid[$j],$feld_shid);
        }while($feld_schlange[$j]<1);
    }
}
//so jetzt schreiben wir noch die werte der Schlangenfelder auf die temp_verfolgt der DB und dann knnen wir danach sortiert auslesen
//e
for($i=0; $i<$schiffanzahl;$i++) {
    $sqlu = "UPDATE " . table_prefix . "schiffe set temp_verfolgt = ? where spiel = ? and id = ?";
    $db->execute($sqlu, array($feld_schlange[$i],$spiel,$feld_shid[$i]));
}
//so wir haben es geschaft alles ist wohlgeordnet jetzt kann geflogen werden
$sql29 = "SELECT * FROM " . table_prefix . "schiffe use index(flug,status,spiel) where flug>0 and status>0 and spiel='" . $spiel . "' order by temp_verfolgt";
$rows29 = $db->execute($sql29);
$schiffanzahl = $rows29->RecordCount();
if ($schiffanzahl>=1) {
    $array29_out = $db->getArray($sql29);
    foreach ($array29_out as $array29) {        
        $rauswurf=1;        
        $shid=$array29["id"];
        $name=$array29["name"];
        $klasse=$array29["klasse"];
        $antrieb=$array29["antrieb"];
        $klasseid=$array29["klasseid"];
        $kox=$array29["kox"];
        $koy=$array29["koy"];
        $flug=$array29["flug"];
        $zielx=$array29["zielx"];
        $ziely=$array29["ziely"];
        $zielid=$array29["zielid"];
        $warp=$array29["warp"];
        $volk=$array29["volk"];
        $masse=$array29["masse"];
        $masse_gesamt=$array29["masse_gesamt"];
        $besitzer=$array29["besitzer"];
        $bild_gross=$array29["bild_gross"];
        $status=$array29["status"];
        $fracht_min2=$array29["fracht_min2"];
        $routing_status=$array29["routing_status"];
        $zusatzmodul=$array29["zusatzmodul"];
        $crew=$array29["crew"];
        $crewmax=$array29["crewmax"];
        $lemin=$array29["lemin"];
        $leminmax=$array29["leminmax"];
        $schaden=$array29["schaden"];
        $flugbonus=1;
        $spritweniger=0;
        $erfahrung=$array29["erfahrung"];
        $energetik_anzahl=$array29["energetik_anzahl"];
        $projektile_anzahl=$array29["projektile_anzahl"];
        $hanger_anzahl=$array29["hanger_anzahl"];
        if (($energetik_anzahl==0) and ($projektile_anzahl==0) and ($hanger_anzahl==0)) { $spritweniger=$erfahrung*8; }
        if ($zusatzmodul==2) { $spritweniger=$spritweniger+11; }
        $kox_old=$kox;
        $koy_old=$koy;
        ////////////////////////////
        $spezialmission=$array29["spezialmission"];
        $traktor_id=$array29["traktor_id"];
        if ($spezialmission==21) {
            $sql30 = "SELECT masse FROM " . table_prefix . "schiffe where id='" . $traktor_id . "' and spiel='" . $spiel . "'";
            $rows30 = $db->execute($sql30);
            $trakanzahl = $rows30->RecordCount();
            if ($trakanzahl>=1) {                
                $masse2=$db->getOne($sql30);
                $masse_gesamt=round($masse+($masse2/2));
            } else {
                $sqlu = "UPDATE " . table_prefix . "schiffe set spezialmission = ?,traktor_id = ? where id = ? and spiel = ?";
                $db->execute($sqlu, array(0,0,$shid,$spiel));
            }
        }
        ////////////////////////////overdrive
        $overdrive=0;
        $overdrive_raus=0;
        if (($spezialmission>=61) and ($spezialmission<=69)) {
            $overdrive_stufe=$spezialmission-60;
            $temp=mt_rand(0,100);
            if ($temp<=($overdrive_stufe*10)) {
                $overdrive_raus=1;
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug0'],array($name));
            } else {
                $overdrive=1;
                $flugbonus=$flugbonus+($overdrive_stufe*0.1);
            }
        }
        ////////////////////////////
        $streckemehr=0;
        if ($antrieb==1) { $verbrauchpromonat = array ("0","0","0","0","0","0","0","0","0","0"); }
        if ($antrieb==2) { $verbrauchpromonat = array ("0","100","107.5","300","400","500","600","700","800","900"); }
        if ($antrieb==3) { $verbrauchpromonat = array ("0","100","106.25","107.78","337.5","500","600","700","800","900"); }
        if ($antrieb==4) { $verbrauchpromonat = array ("0","100","103.75","104.44","106.25","300","322.22","495.92","487.5","900"); }
        if ($antrieb==5) { $verbrauchpromonat = array ("0","100","103.75","104.44","106.25","104","291.67","291.84","366.41","900"); }
        if ($antrieb==6) { $verbrauchpromonat = array ("0","100","103.75","104.44","106.25","104","103.69","251.02","335.16","900"); }
        if ($antrieb==7) { $verbrauchpromonat = array ("0","100","103.75","104.44","106.25","104","103.69","108.16","303.91","529.63"); }
        if ($antrieb==8) { $verbrauchpromonat = array ("0","100","100","100","100","100","100","102.04","109.38","529.63"); }
        if ($antrieb==9) { $verbrauchpromonat = array ("0","100","100","100","100","100","100","100","100","100"); }
        if ((($flug==4) or ($flug==3))and ($zielid!=-1)) {
            $sql120 = "SELECT id,kox,koy,spezialmission,antrieb,tarnfeld,besitzer,name,bild_gross,volk FROM " . table_prefix . "schiffe where id='".$zielid."' order by id";
            $array120_out = $db->getArray($sql120);
            foreach ($array120_out as $array120){
            $zielxt=$zielx;
            $zielyt=$ziely;
            $name_2=$array120["name"];
            $volk_2=$array120["volk"];
            $bild_gross_2=$array120["bild_gross"];
            $besitzer_2=$array120["besitzer"];
            $tarnfeld_2=$array120["tarnfeld"];
            $antrieb_2=$array120["antrieb"];
            $spezialmission_2=$array120["spezialmission"];
            $zielx=$array120["kox"];
            $ziely=$array120["koy"];
            }
            if(($flug==3)and(($spezialmission_2==8)or($antrieb_2==2))and ($tarnfeld_2< 2)){
        $n_gescannt=1;
    $scan_temp_reichweite=(($spezialmission==11)?85:(($spezialmission==12)?116:47))+($warp*$warp);
    if((($zielx-$kox)*($zielx-$kox))+(($ziely-$koy)*($ziely-$koy))<=($scan_temp_reichweite*$scan_temp_reichweite)){
      $n_gescannt=0;
    }
                $sql121 = "SELECT besitzer FROM " . table_prefix . "schiffe where (
                    (sqrt(((kox-".$zielx.")*(kox-".$zielx."))+((koy-".$ziely.")*(koy-".$ziely.")))<=47) and ((spezialmission<>11) and (spezialmission<>12)))
                     or ((sqrt(((kox-".$zielx.")*(kox-".$zielx."))+((koy-".$ziely.")*(koy-".$ziely.")))<=85) and (spezialmission=11))
                     or  ((sqrt(((kox-".$zielx.")*(kox-".$zielx."))+((koy-".$ziely.")*(koy-".$ziely.")))<=116) and (spezialmission=12))
                     order by id";
                $rows121 = $db->execute($sql121);
                $anzahl_temp2 = $rows121->RecordCount();
                if($anzahl_temp2 > 0){
                    $array121_out  = $db->getArray($sql121);
                    for($j=0;$j< $anzahl_temp2; $j++){                        
                        foreach ($array121_out as $array121){
                        $besitzer_temp2=$array121["besitzer"];
                        if(($besitzer==$besitzer_temp2)or($beziehung[$besitzer][$besitzer_temp2]['status']> 3)){
                            $n_gescannt=0;
                        }
                        }
                    }
                }
                $sql122 = "SELECT besitzer FROM " . table_prefix . "planeten where (
                    (sqrt(((x_pos-".$zielx.")*(x_pos-".$zielx."))+((y_pos-".$ziely.")*(y_pos-".$ziely.")))<=53) and (sternenbasis_art<>3))
                    or ((sqrt(((x_pos-".$zielx.")*(x_pos-".$zielx."))+((y_pos-".$ziely.")*(y_pos-".$ziely.")))<=116) and (sternenbasis_art=3))
                    order by id";
                $rows122 = $db->execute($sql122);
                $anzahl_temp2 = $rows122->RecordCount();
                if($anzahl_temp2 > 0){
                    $array122_out = $db->getArray($sql122);
                    for($j=0;$j< $anzahl_temp2; $j++){
                        foreach ($array122_out as $array122){                        
                        $besitzer_temp2=$array122["besitzer"];
                        if(($besitzer==$besitzer_temp2)or($beziehung[$besitzer][$besitzer_temp2]['status']> 3)){
                            $n_gescannt=0;
                        }
                      }
                    }
                }
                if($n_gescannt==1){
                    $zielx=$zielxt;
                    $ziely=$zielyt;
                    $sektork=sektor($zielx,$ziely);
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug7'],array($spielerfarbe[$besitzer],$name,$sektork,$spielerfarbe[$besitzer_2],$name_2));
                    neuigkeiten(2,servername . "daten/$volk_2/bilder_schiffe/$bild_gross_2",$besitzer_2,$lang['host']['flug8'],array($spielerfarbe[$besitzer_2],$name_2,$sektork,$spielerfarbe[$besitzer],$name));
                }
            }
        }
        if ((($kox!=$zielx) or ($koy!=$ziely)) and ($overdrive_raus==0)) {
            $lichtjahre=sqrt(($kox-$zielx)*($kox-$zielx)+($koy-$ziely)*($koy-$ziely));
            $zeit=$lichtjahre/($warp*$warp*$flugbonus);
            if (($status==2) and ($warp<=3) and ($antrieb<=3)) {
                $zeit=$lichtjahre/(4*4);
            }
            if ($antrieb==1) {
                $zufall=mt_rand(1,100);
            if ($zufall<=11) {
                $zeit=$lichtjahre/(9*9);
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug1'],array($name));
            }
        }
        $verbrauch=$verbrauchpromonat[$warp];
        if ($zeit<=1) {
            $kox=$zielx;$koy=$ziely;
            $verbrauch=floor($lichtjahre*$verbrauch*$masse_gesamt/100000);
        } else {
            $kox=$kox+(($zielx-$kox)/$zeit);
            $koy=$koy+(($ziely-$koy)/$zeit);
            $verbrauch=floor($warp*$warp*$verbrauch*$masse_gesamt/100000);
        }
        $verbrauch=$verbrauch-($verbrauch/100*$spritweniger);
        if ($verbrauch==0) { $verbrauch=1; }
        if ($verbrauchpromonat[$warp]==0) { $verbrauch=0; }
        if (($antrieb==4) and ($verbrauch>=1)) {
            $zufall=mt_rand(1,100);
            if ($zufall<=17) {
                $verbrauchneu=floor(37*($verbrauch/100));
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug2'],array($name,$verbrauch,$verbrauchneu));
                $verbrauch=$verbrauchneu;
            }
        }
        if (($verbrauch>$lemin) and ($fracht_min2>=1) and ($fracht_min2+$lemin>=$verbrauch) and ($antrieb==6)) {
            $fehlt=$verbrauch-$lemin;
            $fracht_min2=$fracht_min2-$fehlt;
            $lemin=$verbrauch;
        }
        if ($verbrauch>$lemin) { $rauswurf=2; } else {
            $lemin=$lemin-$verbrauch;
            if ($zeit<=1) {
                $streckemehr=$lichtjahre;
                if ($flug==1) {
                    $flug_neu=0;
                    $status=1;
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug3'],array($name));
                }
                if ($flug==2) {
                    $flug_neu=0;
                    $status=2;
                    if ($routing_status>=1) {
                        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug4'],array($name));
                    } else {
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug5'],array($name));
                    }
                }
                if ($flug==4) {
                    $flug_neu=$flug;
                    $status=1;
                }
                if ($flug==3) {
                    $flug_neu=0;
                    $status=1;
                }
            } else {
                $streckemehr=$warp*$warp;
                $flug_neu=$flug;
                $status=1;
            }
        }
        if ($rauswurf==1) {
            if (($flug==1) or ($flug==2)) {
                $sqlu = "UPDATE " . table_prefix . "schiffe set kox_old = ?, 
                                                                koy_old = ?, 
                                                                strecke = strecke+?, 
                                                                fracht_min2 = ?, 
                                                                kox = ?, 
                                                                koy = ?, 
                                                                lemin = ?, 
                                                                flug = ?, 
                                                                status = ? 
                                                         where id = ?";
                $db->execute($sqlu, array($kox_old,$koy_old,$streckemehr,$fracht_min2,$kox,$koy,$lemin,$flug_neu,$status,$shid));
            }
            if (($flug==4) or ($flug==3)) {
                $sqlu = "UPDATE " . table_prefix . "schiffe set kox_old = ?, 
                                                                koy_old = ?, 
                                                                strecke = strecke+?, 
                                                                fracht_min2 = ?, 
                                                                kox = ?, 
                                                                koy = ?, 
                                                                zielx = ?, 
                                                                ziely = ?, 
                                                                lemin = ?, 
                                                                flug = ?, 
                                                                status = ? 
                                                    where id = ?";
                $db->execute($sqlu,array($kox_old,$koy_old,$streckemehr,$fracht_min2,$kox,$koy,$zielx,$ziely,$lemin,$flug_neu,$status,$shid));
            }
            $stat_lichtjahre[$besitzer]=$stat_lichtjahre[$besitzer]+$streckemehr;
            if ($spezialmission==21) {
               $db->execute("UPDATE " . table_prefix . "schiffe set kox='" . $kox . "', 
                                                                    koy='" . $koy . "', 
                                                                    status='" . $status  . "' 
                                                        where id='" . $traktor_id  . "' and spiel='" . $spiel . "'");
            }
        } else {
            $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where id='" . $shid . "'");
            $db->execute("UPDATE " . table_prefix . "schiffe set routing_schritt='0',routing_status='0',routing_koord='',routing_id='',routing_mins='',routing_warp='0',routing_tank='0',routing_rohstoff='0' where id='".$shid."'");
            neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug6'],array($name));
        }
    }elseif(($overdrive_raus==1)and(($flug==4) or ($flug==3))and ($zielid!=-1)){
        $db->execute("UPDATE " . table_prefix . "schiffe set zielx='".$zielx."',ziely='".$ziely."' where id='".$shid."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////FLUG ENDE
///////////////////////////////////////////////////////////////////////////////////////////////ZIELKORREKTUR ANFANG
if($kreisel_anzahl>0){
    for($i=0;$i< $kreisel_anzahl;$i++){
        $sql123 = "SELECT kox,koy FROM " . table_prefix . "schiffe where spiel='".$spiel."' and id='".$zwischenarray_zielid[$i]."'";
        $array123_out  = $db->getArray($sql123);
        foreach ($array123_out as $array123){
        $t_kox=$array123["kox"];
        $t_koy=$array123["koy"];
        $db->execute("UPDATE " . table_prefix . "schiffe set zielx='".$t_kox."',ziely='".$t_koy."',zielid='".$zwischenarray_zielid[$i]."' where spiel='".$spiel."' and id='".$zwischenarray_shid[$i]."'");            
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////ZIELKORREKTUR ENDE
///////////////////////////////////////////////////////////////////////////////////////////////ERFAHRUNG DURCH STRECKE ANFANG
$db->execute("UPDATE " . table_prefix . "schiffe set erfahrung=erfahrung+1,strecke=strecke-1000 where strecke>999 and erfahrung<5 and spiel='".$spiel."'");
///////////////////////////////////////////////////////////////////////////////////////////////ERFAHRUNG DURCH STRECKE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////WURMLOCH ANFANG
$sql = "SELECT * FROM " . table_prefix . "anomalien where spiel='".$spiel."' order by id";
$rows = $db->execute($sql);
$datensaetze = $rows->RecordCount();
if ($datensaetze>=1) {
    $array_out = $db->getArray($sql);
    foreach ($array_out as $array) {            
        $aid=$array["id"];
        $art=$array["art"];
        $x_pos=$array["x_pos"];
        $y_pos=$array["y_pos"];
        $extra=$array["extra"];
        $extras=explode(":",$extra);
        if (($art==1) or ($art==2)) {
            if ($art==1) { $reichweite=15; }elseif ($art==2) { $reichweite=10; }
           $sqltemp = "SELECT * FROM " . table_prefix . "schiffe where sqrt( (kox-".$x_pos.")*(kox-".$x_pos.")+(koy-".$y_pos.")*(koy-".$y_pos.") )<=".$reichweite." and spiel='".$spiel."' order by id";
           $rowstemp = $db->execute($sqltemp); 
           $schiffanzahl = $rowstemp->RecordCount();
            if ($schiffanzahl>=1) {
                $array_out = $db->getArray($sqltemp);
                foreach ($array_out as $array_temp) {                    
                    $shid=$array_temp["id"];
                    $bild_gross=$array_temp["bild_gross"];
                    $volk=$array_temp["volk"];
                    $s_x_pos=$array_temp["kox"];
                    $s_y_pos=$array_temp["koy"];
                    $antrieb=$array_temp["antrieb"];
                    $besitzer=$array_temp["besitzer"];
                    $name=$array_temp["name"];
                    $spezialmission=$array_temp["spezialmission"];
                    if ($extras[0]>=1) {
                        if ($spezialmission==29) {
                            $aid2=intval($extras[0]);
                            $db->execute("UPDATE " . table_prefix . "anomalien set extra='' where id in ('".$aid."', '". $aid2 ."')");
                        }
                        $alpha=(double)(6.28318530718*mt_rand(0,$mt_randmax)/$mt_randmax);
                        $y=max(0,min($umfang,$extras[2]+round(($reichweite+3)*sin($alpha))));
                        $x=max(0,min($umfang,$extras[1]+round(($reichweite+3)*cos($alpha))));
                        $db->execute("UPDATE " . table_prefix . "schiffe set kox='".$x."', koy='".$y."', zielx='0', ziely='0', flug='0', status='1' where id='".$shid."'");
                        $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$shid."'");
                        if ($art==1) {
                            if ($spezialmission==29) {
                                neuigkeiten(2,servername . "images/news/wurmloch.jpg",$besitzer,$lang['host']['wurmloch4'],array($name));
                            } else {
                                neuigkeiten(2,servername . "images/news/wurmloch.jpg",$besitzer,$lang['host']['wurmloch0'],array($name));
                            }
                        }elseif ($art==2) {
                            if ($spezialmission==29) {
                                neuigkeiten(2,servername . "images/news/sprungtor.jpg",$besitzer,$lang['host']['wurmloch5'],array($name));
                            } else {
                                neuigkeiten(2,servername . "images/news/sprungtor.jpg",$besitzer,$lang['host']['wurmloch1'],array($name));
                            }
                        }
                    } else {
                        $ok=1;
                        while ($ok==1) {
                            $x=mt_rand(50,$umfang-100);
                            $y=mt_rand(50,$umfang-100);
                            $ok=2;
                            
                            $nachbarn=0;
                            $zeiger2 ="SELECT count(*) as total from " . table_prefix . "planeten where sqrt( (x_pos-$x)*(x_pos-$x)+(x_pos-$x)*(x_pos-$x) )<=20 and spiel='".$spiel."'";                            
                            $nachbarn=$db->getOne($zeiger2);                            
                            if ($nachbarn>=1) {
                                $ok=1;                                
                            }
                        }
                        $db->execute("UPDATE " . table_prefix . "schiffe set kox='".$x."', koy='".$y."', zielx='0', ziely='0', flug='0', status='1' where id='".$shid."'");                        
                        $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$shid."'");
                        if ($art==1) {
                            neuigkeiten(2,servername . "images/news/wurmloch.jpg",$besitzer,$lang['host']['wurmloch2'],array($name));
                        }elseif ($art==2) {
                            neuigkeiten(2,servername . "images/news/sprungtor.jpg",$besitzer,$lang['host']['wurmloch3'],array($name));
                        }
                    }
                }
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////WURMLOCH ENDE
///////////////////////////////////////////////////////////////////////////////////////////////DRUGUNVERZERRER ANFANG
$zeiger3 = "SELECT * FROM " . table_prefix . "schiffe where spezialmission='30' and spiel='".$spiel."' order by id";
$rows3 = $db->execute($zeiger3);
$schiffanzahl = $rows3->RecordCount();
if ($schiffanzahl>=1) {
    $array3_out = $db->getArray($zeiger);
    foreach ($array3_out as $array) {        
        $shid=$array["id"];
        $name=$array["name"];
        $klasse=$array["klasse"];
        $klasseid=$array["klasseid"];
        $kox=$array["kox"];
        $koy=$array["koy"];
        $volk=$array["volk"];
        $besitzer=$array["besitzer"];
        $bild_gross=$array["bild_gross"];
        $reichweite=round(intval($array["masse"])/2);
        $zeiger3_temp = "SELECT * FROM " . table_prefix . "schiffe where (sqrt(((kox-$kox)*(kox-$kox))+((koy-$koy)*(koy-$koy)))<=$reichweite) and tarnfeld='1' and spiel='".$spiel."' order by id";
        $rows3_temp = $db->execute($zeiger3_temp);
        $treffschiff = $rows3_temp->RecordCount();
        if ($treffschiff>=1) {
            $array3temp_out = $db->getArray($zeiger3_temp);
            foreach ($array3temp_out as $array_temp) {                
                $t_shid=$array_temp["id"];
                $t_name=$array_temp["name"];
                $t_klasse=$array_temp["klasse"];
                $t_klasseid=$array_temp["klasseid"];
                $t_volk=$array_temp["volk"];
                $t_besitzer=$array_temp["besitzer"];
                $t_bild_gross=$array_temp["bild_gross"];
                neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['drugunverzerrer0'],array($t_name));
                $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='0' where id='".$t_shid."'");
            }
        }
        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['drugunverzerrer1'],array($name));
        $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$shid."'");
        $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 's:".$shid.":%'");
        $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$shid."'");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////DRUGUNVERZERRER ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SELFDESTRUCT ANFANG
$sql130 = "SELECT * FROM " . table_prefix . "schiffe where spezialmission='15' and spiel='".$spiel."' order by id";
$rows130 = $db->execute($sql130);
$schiffanzahl = $rows130->RecordCount();
if ($schiffanzahl>=1) {
    $array130_out = $db->getArray($sql130);
    foreach ($array130_out as $array130) {        
        $shid=$array130["id"];
        $name=$array130["name"];
        $klasse=$array130["klasse"];
        $antrieb=$array130["antrieb"];
        $klasseid=$array130["klasseid"];
        $kox=$array130["kox"];
        $koy=$array130["koy"];
        $volk=$array130["volk"];
        $besitzer=$array130["besitzer"];
        $bild_gross=$array130["bild_gross"];
        $fertigkeiten=$array130["fertigkeiten"];
        $sub_schaden=intval($array130["techlevel"])*50;
        $reichweite=83;
        $sql131 = "SELECT * FROM " . table_prefix . "schiffe where (sqrt(((kox-$kox)*(kox-$kox))+((koy-$koy)*(koy-$koy)))<=$reichweite) and spezialmission<>15 and spiel='".$spiel."' order by id";
        $rows131 = $db->execute($sql131);
        $treffschiff = $rows131->RecordCount();
        if ($treffschiff>=1) {
            $array131_out = $db->getArray($sql131);
            foreach ($array131_out as $array131) {                
                $t_shid=$array131["id"];
                $t_name=$array131["name"];
                $t_klasse=$array131["klasse"];
                $t_antrieb=$array131["antrieb"];
                $t_klasseid=$array131["klasseid"];
                $t_volk=$array131["volk"];
                $t_besitzer=$array131["besitzer"];
                $t_bild_gross=$array131["bild_gross"];
                $t_schaden=$array131["schaden"];
                $t_masse=$array131["masse"];
                $zielx=$array131["kox"];
                $ziely=$array131["koy"];
                $schaden=round($t_schaden+($sub_schaden*(80/($t_masse+1))*(80/($t_masse+1))+2));
                if ($schaden<100) {
                    neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['selfdestruct0'],array($t_name,$schaden));
                    $db->execute("UPDATE " . table_prefix . "schiffe set schaden='".$schaden."' where id='".$t_shid."'");
                }
                if ($schaden>=100) {
                    neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['selfdestruct1'],array($t_name));
                    $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$t_shid."'");
                    $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 's:$t_shid:%'");
                    $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$t_shid."'");
                }
            }
        }
        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['selfdestruct2'],array($name));
        $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$shid."'");
        $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 's:$shid:%'");
        $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$shid."'");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////SELFDESTRUCT ENDE
///////////////////////////////////////////////////////////////////////////////////////////////RAUMFALTEN ANFANG
$sql140 = "SELECT * FROM " . table_prefix . "anomalien where extra like 's:%' and art='3' and spiel='".$spiel."' order by id";
$rows140 = $db->execute($sql140);
$anoanzahl = $rows140->RecordCount();
if ($anoanzahl>=1) {
    $array140_out = $db->getArray($sql140);
    foreach ($array140_out as $array140) {
        $anoid=$array140["id"];
        $extra=$array140["extra"];
        $extras=explode(":",$extra);
        $sql141 = "SELECT id,kox,koy,spiel FROM " . table_prefix . "schiffe where spiel='".$spiel."' and id='".$extras[1]."' order by id";
        $array141_out = $db->getArray($sql141);
        foreach($array141_out as $array141){
            $kox=$array_temp["kox"];
            $koy=$array_temp["koy"];
            $optionen="s:".$extras[1].":$kox:$koy:".$extras[4].":".$extras[5].":".$extras[6].":".$extras[7].":".$extras[8].":".$extras[9];
            $db->execute("UPDATE " . table_prefix . "anomalien set extra='".$optionen."' where spiel='".$spiel."' and id='".$anoid."'");
        }
    }
}

$zeiger142 = "SELECT * FROM " . table_prefix . "anomalien where art='3' and spiel='".$spiel."' order by id";
$rows142 = $db->execute($zeiger142);
$anoanzahl = $rows142->RecordCount();
if ($anoanzahl>=1) {
    $array142_out = $db->getArray($zeiger142);
    foreach ($array142_out as $array) {        
        $anoid=$array["id"];
        $extra=$array["extra"];
        $kox=$array["x_pos"];
        $koy=$array["y_pos"];
        $extras=explode(":",$extra);
        $zielx=$extras[2];
        $ziely=$extras[3];
        $warp=12.67;
        $lichtjahre=sqrt(($kox-$zielx)*($kox-$zielx)+($koy-$ziely)*($koy-$ziely));
        $zeit=$lichtjahre/($warp*$warp);
        if ($zeit<=1) {
            if ($extras[0]=='p') {
                $db->execute("UPDATE " . table_prefix . "planeten set cantox=cantox+".$extras[4].", 
                                                                      vorrat=vorrat+".$extras[5].", 
                                                                      lemin=lemin+".$extras[6].", 
                                                                      min1=min1+".$extras[7].", 
                                                                      min2=min2+".$extras[8].", 
                                                                      min3=min3+".$extras[9]." 
                                                        where id='".$extras[1]."' and spiel='".$spiel."'");                
                $db->execute("DELETE FROM " . table_prefix . "anomalien  where id='".$anoid."' and spiel='".$spiel."'");
                $zeiger142_temp = "SELECT id, besitzer, name FROM " . table_prefix . "planeten where spiel='".$spiel."' and id='".$extras[1]."'";
                $array142_temp = $db->getArray($zeiger142_temp);
                foreach($array142_temp as $array_temp) {
                $name=$array_temp["name"];
                $besitzer=$array_temp["besitzer"];
                }
                if ($besitzer>=1)  {
                    neuigkeiten(1,servername . "images/news/raumfalte.jpg",$besitzer,$lang['host']['raumfalte0'],array($name,$extras[4],$extras[6],$extras[8],$extras[5],$extras[7],$extras[9]));
                }
            }elseif ($extras[0]=='s') {
                $zeiger143_temp = "SELECT * FROM " . table_prefix . "schiffe where id='".$extras[1]."' and spiel='".$spiel."'";
                $array143_temp = $db->getArray($zeiger143_temp);
                foreach ($array143_temp as $array_temp){
                $besitzer=$array_temp["besitzer"];
                $fracht_leute=$array_temp["fracht_leute"];
                $fracht_cantox=$array_temp["fracht_cantox"];
                $fracht_vorrat=$array_temp["fracht_vorrat"];
                $fracht_lemin=$array_temp["lemin"];
                $fracht_min1=$array_temp["fracht_min1"];
                $fracht_min2=$array_temp["fracht_min2"];
                $fracht_min3=$array_temp["fracht_min3"];
                $frachtraum=$array_temp["frachtraum"];
                $leminmax=$array_temp["leminmax"];
                $name=$array_temp["name"];
                $freiraum=$frachtraum-$fracht_min1-$fracht_min2-$fracht_min3-round($fracht_leute/100)-$fracht_vorrat;
                $freitank=$leminmax-$fracht_lemin;
                $p_min1=$extras[7];
                $p_min2=$extras[8];
                $p_min3=$extras[9];
                $p_vorrat=$extras[5];
                $p_cantox=$extras[4];
                $p_lemin=$extras[6];
                if ($p_min1<=$freiraum) { $freiraum=$freiraum-$p_min1;$fracht_min1=$fracht_min1+$p_min1; } else
                    {$fracht_min1=$fracht_min1+$freiraum;$freiraum=0; }
                if ($p_min2<=$freiraum) { $freiraum=$freiraum-$p_min2;$fracht_min2=$fracht_min2+$p_min2; } else
                    { $fracht_min2=$fracht_min2+$freiraum;$freiraum=0; }
                if ($p_min3<=$freiraum) { $freiraum=$freiraum-$p_min3;$fracht_min3=$fracht_min3+$p_min3; } else
                    { $fracht_min3=$fracht_min3+$freiraum;$freiraum=0; }
                if ($p_vorrat<=$freiraum) { $freiraum=$freiraum-$p_vorrat;$fracht_vorrat=$fracht_vorrat+$p_vorrat; } else
                    { $fracht_vorrat=$fracht_vorrat+$freiraum;$freiraum=0; }
                $fracht_cantox=$fracht_cantox+$p_cantox;
                if ($p_lemin<=$freitank){ $freitank=$freitank-$p_lemin;$fracht_lemin=$fracht_lemin+$p_lemin; } else
                                        { $fracht_lemin=$fracht_lemin+$freitank; }
                $db->execute("UPDATE " . table_prefix . "schiffe set lemin='".$fracht_lemin."', 
													 fracht_vorrat='".$fracht_vorrat."', 
													 fracht_cantox='".$fracht_cantox."', 
													 fracht_min1='".$fracht_min1."', 
													 fracht_min2='".$fracht_min2."', 
													 fracht_min3='".$fracht_min3."' 
										where id='".$extras[1]."' and spiel='".$spiel."'");
                $db->execute("DELETE FROM " . table_prefix . "anomalien  where id='".$anoid."' and spiel='".$spiel."'");
                neuigkeiten(2,servername . "images/news/raumfalte.jpg",$besitzer,$lang['host']['raumfalte1'],array($name,$extras[4],$extras[6],$extras[8],$extras[5],$extras[7],$extras[9]));
            }
         }
        } else {
            $kox=round($kox+(($zielx-$kox)/$zeit));
            $koy=round($koy+(($ziely-$koy)/$zeit));
            $db->execute("UPDATE " . table_prefix . "anomalien set x_pos='".$kox."', y_pos='".$koy."' where id='".$anoid."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////RAUMFALTEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////AUTOPROJEKTILE ANFANG
$zeiger144 = "SELECT * FROM " . table_prefix . "schiffe where projektile_anzahl>=1 and projektile_auto='1' and spiel='".$spiel."'";
$rows144 = $db->execute($zeiger144);
$anoanzahl = $rows144->RecordCount();
if ($anoanzahl>=1) {
    $array144_out = $db->getArray($zeiger144);
    foreach  ($array144_out as $array) {        
        $shid=$array["id"];
        $projektile=$array["projektile"];
        $projektile_auto=$array["projektile_auto"];
        $projektile_stufe=$array["projektile_stufe"];
        $projektile_anzahl=$array["projektile_anzahl"];
        $fracht_cantox=$array["fracht_cantox"];
        $fracht_min1=$array["fracht_min1"];
        $fracht_min2=$array["fracht_min2"];
        $max=$projektile_anzahl*5;
        $max_bau=$max-$projektile;
        $max_cantox=floor($fracht_cantox/35);
        if ($max_cantox<$max_bau) {$max_bau=$max_cantox;}
        $max_min1=floor($fracht_min1/2);
        if ($max_min1<$max_bau) {$max_bau=$max_min1;}
        if ($fracht_min2<$max_bau) {$max_bau=$fracht_min2;}
        if ($max_bau>=1) {
            $projektile=$projektile+$max_bau;
            $fracht_cantox=$fracht_cantox-($max_bau*35);
            $fracht_min1=$fracht_min1-($max_bau*2);
            $fracht_min2=$fracht_min2-$max_bau;
            $db->execute("UPDATE " . table_prefix . "schiffe set projektile='".$projektile."',
                                                                 fracht_cantox='".$fracht_cantox."',
                                                                 fracht_min1='".$fracht_min1."',
                                                                 fracht_min2'".$fracht_min2."' where id='".$shid."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////AUTOPROJEKTILE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSBAU ANFANG
$zeiger145 = "SELECT * FROM " . table_prefix . "sternenbasen where schiffbau_status='1' and status='1' and spiel='".$spiel."' order by id";
$rows145 = $db->execute($zeiger145);
$basenanzahl = $rows145->RecordCount();
if ($basenanzahl>=1) {
    $neueschiffe=$neueschiffe+$basenanzahl;
    $array145_out = $db->getArray($zeiger145);
    foreach ($array145_out as $array) {        
        $baid=$array["id"];
        $x_pos=$array["x_pos"];
        $y_pos=$array["y_pos"];
        $zeiger146 = "SELECT * FROM " . table_prefix . "planeten where x_pos='".$x_pos."' and y_pos='".$y_pos."' and spiel='".$spiel."' order by id";
        $array146_out = $db->getArray($zeiger146);        
        foreach ($array146_out as $array2) {
        $osys_1=$array2["osys_1"];
        $osys_2=$array2["osys_2"];
        $osys_3=$array2["osys_3"];
        $osys_4=$array2["osys_4"];
        $osys_5=$array2["osys_5"];
        $osys_6=$array2["osys_6"];
        }
        $besitzer=$array["besitzer"];
        $planetid=$array["planetid"];
        $schiffbau_klasse=$array["schiffbau_klasse"];
        $schiffbau_bild_gross=$array["schiffbau_bild_gross"];
        $schiffbau_bild_klein=$array["schiffbau_bild_klein"];
        $schiffbau_crew=$array["schiffbau_crew"];
        $schiffbau_masse=$array["schiffbau_masse"];
        $schiffbau_tank=$array["schiffbau_tank"];
        $schiffbau_fracht=$array["schiffbau_fracht"];
        $schiffbau_antriebe=$array["schiffbau_antriebe"];
        $schiffbau_energetik=$array["schiffbau_energetik"];
        $schiffbau_projektile=$array["schiffbau_projektile"];
        $schiffbau_hangar=$array["schiffbau_hangar"];
        $schiffbau_klasse_name=$array["schiffbau_klasse_name"];
        $schiffbau_rasse=$array["schiffbau_rasse"];
        $schiffbau_fertigkeiten=$array["schiffbau_fertigkeiten"];
        $schiffbau_energetik_stufe=$array["schiffbau_energetik_stufe"];
        $schiffbau_projektile_stufe=$array["schiffbau_projektile_stufe"];
        $schiffbau_techlevel=$array["schiffbau_techlevel"];
        $schiffbau_antriebe_stufe=$array["schiffbau_antriebe_stufe"];
        $schiffbau_name=$array["schiffbau_name"];
        $schiffbau_zusatz=$array["schiffbau_zusatz"];
        $schiffbau_extra=$array["schiffbau_extra"];
        $schalter=0;
        if(($osys_1==15) or ($osys_2==15) or ($osys_3==15) or ($osys_4==15) or ($osys_5==15) or ($osys_6==15)and $schiffbau_masse<100){
            $schalter=1;
            for($j=1;$j<=strlen($schiffbau_fertigkeiten);$j++){
                if(($j<53) or($j>55)){
                    if(intval(substr($schiffbau_fertigkeiten,$j,1))!=0){
                        $schalter=0;
                    }
                }
            }
        }
        if($schalter==1){
            if($schiffbau_energetik_stufe!=0){
                $vorrat_energetik_string='vorrat_energetik_'.$schiffbau_energetik_stufe;
            }else{
                $vorrat_energetik_string='vorrat_energetik_1';
            }
            if($schiffbau_projektile_stufe!=0){
                $vorrat_projektile_string='vorrat_projektile_'.$schiffbau_projektile_stufe;
            }else{
                $vorrat_projektile_string='vorrat_projektile_1';
            }
            if($schiffbau_antriebe_stufe!=0){
                $vorrat_antrieb_string='vorrat_antrieb_'.$schiffbau_antriebe_stufe;
            }else{
                $vorrat_antrieb_string='vorrat_antrieb_1';
            }
            $energetik=$array[$vorrat_energetik_string];
            $projektile=$array[$vorrat_projektile_string];
            $antrieb=$array[$vorrat_antrieb_string];
            if($energetik>=$schiffbau_energetik and $projektile>=$schiffbau_projektile and $antrieb>=$schiffbau_antriebe){
                $energetik=$energetik-$schiffbau_energetik;
                $projektile=$projektile-$schiffbau_projektile;
                $antrieb=$antrieb-$schiffbau_antriebe;
            }else{
                $schalter=0;
            }
            if($schalter==1){                
                $zeiger147 = "SELECT * FROM " . table_prefix . "huellen where baid='".$baid."' and spiel='".$spiel."' and klasse='".$schiffbau_klasse."' order by id";
                $rows147 = $db>execute($zeiger147);
                $huellenanzahl = $rows147->RecordCount();
                if($huellenanzahl>0){                    
                    $neueschiffe++;
                    $array147_out = $db->getArray($zeiger147);                    
                    foreach ($array147_out as $array147){
                    $hid=$array147["id"];
                    $schiffbau_name2=$schiffbau_name.'(2)';
                    $db->execute("INSERT INTO " . table_prefix . "schiffe (s_x,
                                                                           s_y, 
                                                                           besitzer,
									   status,
									   name,
									   klasse,
									   klasseid,
									   volk,
									   techlevel,
									   antrieb,
									   antrieb_anzahl,
									   kox,
									   koy,
									   crew,
									   crewmax,
									   lemin,
									   leminmax,
									   frachtraum,
									   masse,
									   masse_gesamt,
									   bild_gross,
									   bild_klein,
									   energetik_stufe,
									   energetik_anzahl,
									   projektile_stufe,
									   projektile_anzahl,
									   hanger_anzahl,
									   schild,
									   fertigkeiten,
									   spiel,
									   extra,
									   zusatzmodul) 
								values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                                                                array($x_pos,$y_pos,$besitzer,'2',$schiffbau_name2,$schiffbau_klasse_name,$schiffbau_klasse,$schiffbau_rasse,$schiffbau_techlevel,$schiffbau_antriebe_stufe,$schiffbau_antriebe,
                                                                      $x_pos,$y_pos,$schiffbau_crew,$schiffbau_crew,'0',$schiffbau_tank,$schiffbau_fracht,$schiffbau_masse,$schiffbau_masse,$schiffbau_bild_gross,$schiffbau_bild_klein,
                                                                      $schiffbau_energetik_stufe,$schiffbau_energetik,$schiffbau_projektile_stufe,$schiffbau_projektile,$schiffbau_hangar,'100',$schiffbau_fertigkeiten,$spiel,$schiffbau_extra,$schiffbau_zusatz));
$db->execute("UPDATE " . table_prefix . "sternenbasen set ".$vorrat_energetik_string." = ?,
							  ".$vorrat_projektile_string." = ?,
							  ".$vorrat_antrieb_string." = ?  
                                                      where spiel = ? and id = ?",
                                        array($energetik,$projektile,$antrieb,$spiel,$baid));
$db->execute("DELETE FROM " . table_prefix . "huellen where spiel = ? and id = ?",
                                              array($spiel,$hid));
                    neuigkeiten(2,servername . "daten/$schiffbau_rasse/bilder_schiffe/$schiffbau_bild_gross",$besitzer,$lang['host']['schiffbau0'],array($schiffbau_name2));
                    $schiffbau_name=$schiffbau_name.'(1)';
                    }                    
                }
            }
        }
        $db->execute("INSERT INTO " . table_prefix . "schiffe (s_x,s_y,besitzer,status,name,klasse,klasseid,volk,techlevel,antrieb,antrieb_anzahl,kox,koy,crew,crewmax,lemin,leminmax,frachtraum,masse,masse_gesamt,
                                                               bild_gross,bild_klein,energetik_stufe,energetik_anzahl,projektile_stufe,projektile_anzahl,hanger_anzahl,schild,fertigkeiten,spiel,extra,zusatzmodul) 
                                                      values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                                                      array ($x_pos,$y_pos,$besitzer,2,$schiffbau_name,$schiffbau_klasse_name,$schiffbau_klasse,$schiffbau_rasse,$schiffbau_techlevel,$schiffbau_antriebe_stufe,
                                                             $schiffbau_antriebe,$x_pos,$y_pos,$schiffbau_crew,$schiffbau_crew,0,$schiffbau_tank,$schiffbau_fracht,$schiffbau_masse,$schiffbau_masse,$schiffbau_bild_gross,
                                                             $schiffbau_bild_klein,$schiffbau_energetik_stufe,$schiffbau_energetik,$schiffbau_projektile_stufe,$schiffbau_projektile,$schiffbau_hangar,100,$schiffbau_fertigkeiten,
                                                             $spiel,$schiffbau_extra,$schiffbau_zusatz));
        neuigkeiten(2,servername . "daten/$schiffbau_rasse/bilder_schiffe/$schiffbau_bild_gross",$besitzer,$lang['host']['schiffbau0'],array($schiffbau_name));
    
 }
}
$db->execute("UPDATE " . table_prefix . "sternenbasen set schiffbau_status='0',schiffbau_extra='' where spiel = ?",array($spiel));
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSBAU ENDE
///////////////////////////////////////////////////////////////////////////////////////////////GRAVITATION ANFANG
$sql200 = "SELECT * FROM " . table_prefix . "schiffe use index (status,spiel) where status<>2 and spiel='".$spiel."' order by id";
$rows200 = $db->execute($sql200);
$schiffanzahl = $rows200->RecordCount();
if ($schiffanzahl>=1) {
    $array200_out = $db->getArray($sql200);
    foreach ($array200_out as $array200) {        
        $shid=$array200["id"];
        $kox=$array200["kox"];
        $koy=$array200["koy"];
        $flug=$array200["flug"];
        $zielid=$array200["zielid"];
        $volk=$array200["volk"];
        $bild_gross=$array200["bild_gross"];
        $besitzer=$array200["besitzer"];
        $name=$array200["name"];
        $reichweite=13;
        $sql201 = "SELECT * FROM " . table_prefix . "planeten where (sqrt(((x_pos-".$kox.")*(x_pos-".$kox."))+((y_pos-".$koy.")*(y_pos-".$koy.")))<=".$reichweite.") and spiel='".$spiel."' order by id";
        $rows201 = $db->execute($sql201);
        $planetenanzahl = $rows201->RecordCount();
        if ($planetenanzahl>=1) {
            $array201_out = $db->getArray($sql201);
            foreach ($array201_out as $array201) {                
                $pid=$array201["id"];
                $x_pos=$array201["x_pos"];
                $y_pos=$array201["y_pos"];
                if ($pid==$zielid) {
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['flug5'],array($name));
                    $db->execute("UPDATE " . table_prefix . "schiffe set flug=0 where id = ?",array($shid));
                }
                $db->execute("UPDATE " . table_prefix . "schiffe set kox = ?,koy = ?,status = ? where id = ?",array($x_pos,$y_pos,2,$shid));
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////GRAVITATION ENDE
///////////////////////////////////////////////////////////////////////////////////////////////MINENFELDER ANFANG
if($module[2]) {
    $sql202 = "SELECT id,besitzer,status,name,klasse,klasseid,volk,kox,koy,flug,crew,crewmax,leminmax,schaden,masse,bild_gross FROM " . table_prefix . "schiffe use index (status,klasseid,volk,spiel) where status='1' and not (klasseid='1' and volk='unknown') and spiel='".$spiel."' order by id";
    $rows202 = $db->execute($sql202);
    $schiffanzahl = $rows202->RecordCount();
    if ($schiffanzahl>=1) {
        $array202_out = $db->getArray($sql202);
        foreach ($array202_out as $array202) {            
            $shid=$array202["id"];
            $name=$array202["name"];
            $klasse=$array202["klasse"];
            $klasseid=$array202["klasseid"];
            $kox=$array202["kox"];
            $koy=$array202["koy"];
            $volk=$array202["volk"];
            $besitzer=$array202["besitzer"];
            $bild_gross=$array202["bild_gross"];
            $status=$array202["status"];
            $leminmax=$array202["leminmax"];
            $flug=$array202["flug"];
            $schaden=$array202["schaden"];
            $masse=$array202["masse"];
            $crew=$array202["crew"];
            $crewmax=$array202["crewmax"];
            $reichweite=85;
            $minenanzahl=0;
            $sql203 = "SELECT * FROM " . table_prefix . "anomalien where spiel='".$spiel."' and art='5' and (sqrt(((x_pos-".$kox.")*(x_pos-".$kox."))+((y_pos-".$koy.")*(y_pos-".$koy.")))<=".$reichweite.") order by id";
            $rows203 = $db->execute($sql203);
            $datensaetze2 = $rows203->RecordCount();
            if ($datensaetze2>=1) {
                $array203_out = $db->getArray($sql203);
                foreach ($array203_out as $array203) {                    
                    $aid=$array203["id"];
                    $x_pos=$array203["x_pos"];
                    $y_pos=$array203["y_pos"];
                    $mineextra=explode(":",$array203["extra"]);
                    if( ($mineextra[0]==$besitzer) or
                        ($beziehung[$besitzer][$mineextra[0]]['status']==3) or
                        ($beziehung[$besitzer][$mineextra[0]]['status']==4) or
                        ($beziehung[$besitzer][$mineextra[0]]['status']==5))
                    {
                        /*
                         * Weshalb wird hier ein else genutzt wenn es gar nichts zu tun gibt ?
                         */
                       
                    } else {
                        if (intval($mineextra[1])>=$minenanzahl) {
                            $minenanzahl=intval($mineextra[1]);
                            $aanomalie[0]=$aid; // id
                            $aanomalie[1]=$mineextra[0]; // besitzer
                            $aanomalie[2]=intval($mineextra[1]); // anzahl
                            $aanomalie[3]=$mineextra[2]; // stufe
                        }
                    }
                }
            }            
            if ($minenanzahl>=1) {
                $zufall=mt_rand(0,50);
                $minentreffer=round($zufall*$minenanzahl/100);
                if ($minenanzahl==1) { $minentreffer=1; }
                if ($minentreffer>=1) {
                    $aanomalie[2]=$aanomalie[2]-$minentreffer;
                    if ($aanomalie[2]<=0) {
                        $db->execute("DELETE FROM " . table_prefix . "anomalien where spiel = ? and id = ?",array($spiel,$aanomalie[0]));
                    } else {
                        $mineextra=$aanomalie[1].':'.$aanomalie[2].':'.$aanomalie[3];
                        $db->execute("UPDATE " . table_prefix . "anomalien set extra = ? where spiel = ? and id = ?",array($mineextra,$spiel,$aanomalie[0]));
                    }
                    $minen_schaden=$torpedoschaden["$aanomalie[3]"];
                    $minen_schaden_crew=$torpedoschadencrew["$aanomalie[3]"];                    
                    $schaden_rumpf=round(($minen_schaden*(80/($masse+1))*(80/($masse+1))+2))*$minentreffer;
                    $schaden=$schaden+$schaden_rumpf;
                    $schaden_crew=($minen_schaden_crew*(80/($masse+1))*(80/($masse+1))+2)*$minentreffer;
                    $crew=$crew-floor($crewmax*$schaden_crew/100);
                    $schaden_crewmen=floor($crewmax*$schaden_crew/100);
                    $sektork=sektor($kox,$koy);                    
                    if (($schaden>=100) or ($crew<1)) {
                        $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$shid."' and besitzer='".$besitzer."'");
                        $shids = "s:".$shid.":%";
                        $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like ?",array($shids));
                        $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid = ?",array($shid));
                        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['minenfelder0'],array($name,$sektork));
                    } else {
                        $db->execute("UPDATE " . table_prefix . "schiffe set crew = ?, schaden = ?,scanner='0' where id = ? and besitzer = ?",array($crew,$schaden,$shid,$besitzer));
                        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['minenfelder1'],array($name,$sektork,$minentreffer,$schaden_rumpf,$schaden_crewmen));
                    }
                }
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////MINENFELDER ENDE
///////////////////////////////////////////////////////////////////////////////////////////////MINENFELDER SCHRUMPFEN ANFANG
if($module[2]) {
    $sql204 = "SELECT id,extra,spiel FROM " . table_prefix . "anomalien use index (spiel,art) where spiel='".$spiel."' and art='5' order by id";
    $rows204 = $db->execute($sql204);
    $datensaetze2 = $rows204->RecordCount();
    if ($datensaetze2>=1) {
        $array204_out = $db->getArray($sql204);
        foreach ($array204_out as $array204) {            
            $aid=$array204["id"];
            $mineextra=explode(":",$array2["extra"]);
            $aanomalie[0]=$aid; // id
            $aanomalie[1]=$mineextra[0]; // besitzer
            $aanomalie[2]=intval($mineextra[1]); // anzahl
            $aanomalie[3]=$mineextra[2]; // stufe
            $zufall=mt_rand(0,100);
            if ($zufall<=80) {
                $aanomalie[2]=$aanomalie[2]-1;
                if ($aanomalie[2]<=0) {
                    $db->execute("DELETE FROM " . table_prefix . "anomalien where spiel='".$spiel."' and id='".$aanomalie[0]."'");
                } else {
                    $mineextra=$aanomalie[1].':'.$aanomalie[2].':'.$aanomalie[3];
                    $db->execute("UPDATE " . table_prefix . "anomalien set extra='".$mineextra."' where spiel='".$spiel."' and id='".$aanomalie[0]."'");
                }
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////MINENFELDER SCHRUMPFEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SPIONAGE ANFANG
if($module[0]) {
    /*
     * inc.host_spionage.php wurde noch nicht überarbeitet
     * SkullCollector
     */
    include(includes .'inc.host_spionage.php');
}
///////////////////////////////////////////////////////////////////////////////////////////////SPIONAGE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SUBRAUMVERZERRUNG BETA ANFANG
$sql205 = "SELECT * FROM " . table_prefix . "schiffe use index (spezialmission, spiel) where spezialmission='10' and spiel='".$spiel."' order by id";
$rows205= $db->execute($sql205);
$schiffanzahl = $rows205->RecordCount();
if ($schiffanzahl>=1) {
    $array205_out = $db->getArray($sql205);
    foreach ($array205_out as $array205) {        
        $shid=$array205["id"];
        $name=$array205["name"];
        $klasse=$array205["klasse"];
        $antrieb=$array205["antrieb"];
        $klasseid=$array205["klasseid"];
        $kox=$array205["kox"];
        $koy=$array205["koy"];
        $volk=$array205["volk"];
        $besitzer=$array205["besitzer"];
        $bild_gross=$array205["bild_gross"];
        $fertigkeiten=$array205["fertigkeiten"];
        $fert_subver=intval(substr($fertigkeiten,23,1));
        $sub_schaden=$fert_subver*50;
        $sql206 = "SELECT * FROM " . table_prefix . "schiffe where (sqrt(($kox-kox)*($kox-kox)+($koy-koy)*($koy-koy))<=83) and spezialmission<>10 and spiel='".$spiel."' order by id";
        $rows206 = $db->execute($sql206);
        $treffschiff = $rows206->RecordCount();
        if ($treffschiff>=1) {
            $array206_out = $db->getArray($sql206);
            foreach ($array206_out as $array_temp) {               
                $t_shid=$array_temp["id"];
                $t_name=$array_temp["name"];
                $t_klasse=$array_temp["klasse"];
                $t_antrieb=$array_temp["antrieb"];
                $t_klasseid=$array_temp["klasseid"];
                $t_volk=$array_temp["volk"];
                $t_besitzer=$array_temp["besitzer"];
                $t_bild_gross=$array_temp["bild_gross"];
                $t_schaden=$array_temp["schaden"];
                $t_masse=$array_temp["masse"];
                $zielx=$array_temp["kox"];
                $ziely=$array_temp["koy"];
                $schaden=round($t_schaden+($sub_schaden*(80/($t_masse+1))*(80/($t_masse+1))+2));
                if ($schaden<100) {
                    neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['subraumverzerrer0'],array($t_name,$schaden));
                    $db->execute("UPDATE " . table_prefix . "schiffe set schaden='".$schaden."' where id='".$t_shid."'");
                }
                if ($schaden>=100) {
                    neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['subraumverzerrer1'],array($t_name));
                    $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$t_shid."'");
                    $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 's:".$t_shid.":%'");
                    $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$t_shid."'");
                }
            }
        }
        $sql207 = "SELECT * FROM " . table_prefix . "anomalien where (sqrt(($kox-x_pos)*($kox-x_pos)+($koy-y_pos)*($koy-y_pos))<=83) and art='3' and spiel='".$spiel."' order by id";
        $rows207 = $db->execute($sql207);
        $trefffalte = $rows207->RecordCount();
        if ($trefffalte>=1) {
            $array207_out = $db->getArray($sql207);
            foreach ($array207_out as $array207) {                
                $fid=$array207["id"];
                $war=mt_rand(1,10);
                if($war<=$fert_subver){
                    $db->execute("DELETE FROM " . table_prefix . "anomalien where id='".$fid."'");
                }
            }
        }
        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['subraumverzerrer2'],array($name));
        $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$shid."'");
        $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 's:".$shid.":%'");
        $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$shid."'");
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////SUBRAUMVERZERRUNG BETA ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSKAMPF PLANET ANFANG
/* 
 * Noch nicht angepasst /* Sprachvariablen schon importiert
 */
include(includes . 'inc.host_orbitalkampf.php');
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSKAMPF PLANET ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSKAMPF ANFANG
/*
 * Noch nicht angepasst /* Sprachvariablen schon importiert
 */
include(includes . 'inc.host_raumkampf.php');
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFFSKAMPF ENDE
///////////////////////////////////////////////////////////////////////////////////////////////STERNENBASEN ANFANG
///////////////////////////////////////////////////////////////////////////////////////////////STERNENBASEN BAUEN ANFANG
$sql207 = "SELECT * FROM " . table_prefix . "sternenbasen where status='0' and spiel='".$spiel."' order by id";
$rows207 = $db->execute($sql207);
$basenanzahl = $rows207->RecordCount();

if ($basenanzahl>=1) {
    $array207_out = $db->getArray($sql207);
    foreach ($array207_out as $array207) {        
        $bid=$array207["id"];
        $name=$array207["name"];
        $rasse=$array207["rasse"];
        $planetid=$array207["planetid"];
        $besitzer=$array207["besitzer"];
        $db->execute("UPDATE " . table_prefix . "sternenbasen set status=1 where id='".$bid."'");
        $db->execute("UPDATE " . table_prefix . "planeten set sternenbasis=2 where id='".$planetid."'");
        $neuebasen++;
        neuigkeiten(3,servername . "daten/$rasse/bilder_basen/1.jpg",$besitzer,$lang['host']['basenbauen0'],array($name));
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////STERNENBASEN BAUEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////STERNENBASEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFF WEICHT PLANET AUS ANFANG
$sql208 = "SELECT id,status,besitzer,name,klasse,klasseid,kox,koy,volk,bild_gross FROM " . table_prefix . "schiffe use index (status,spiel) where status='2' and spiel='".$spiel."' order by id";
$rows208 = $db->execute($sql208);
$schiffanzahl = $rows208->RecordCount();
if ($schiffanzahl>=1) {
    $array208_out = $db->getArray($sql208);
    foreach ($array208_out as $array208) {        
        $shid=$array208["id"];
        $status=$array208["status"];
        $besitzer=$array208["besitzer"];
        $name=$array208["name"];
        $klasse=$array208["klasse"];
        $klasseid=$array208["klasseid"];
        $kox=$array208["kox"];
        $koy=$array208["koy"];
        $volk=$array208["volk"];
        $bild_gross=$array208["bild_gross"];
        $gemeinsam=0;
        $gemeinsam = $db->getOne("SELECT count(*) as gemeinsam FROM " . table_prefix . "planeten where x_pos='".$kox."' and y_pos='".$koy."' and besitzer<>".$besitzer." and besitzer>=1 and spiel='".$spiel."'");        
        if ($gemeinsam>=1) {
            $sql209 = "SELECT x_pos,y_pos,spiel,id,name,besitzer,bild,klasse FROM " . table_prefix . "planeten where x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'";
            $array209_out = $db->getArray($sql209);
            foreach ($array209_out as $array209){            
            $p_id=$array209["id"];
            $p_name=$array209["name"];
            $p_besitzer=$array209["besitzer"];
            $p_bild=$array209["bild"];
            $p_klasse=$array209["klasse"];
            if (($beziehung[$besitzer][$p_besitzer]['status']==3) or ($beziehung[$besitzer][$p_besitzer]['status']==4)) {
                $alpha=(double)(6.28318530718*mt_rand(0,$mt_randmax)/$mt_randmax);
                $koy=max(0,min($umfang,$koy+round(20*sin($alpha))));
                $kox=max(0,min($umfang,$kox+round(20*cos($alpha))));
                $db->execute("UPDATE " . table_prefix . "schiffe set status='1',kox='".$kox."', koy='".$koy."' where id='".$shid."' and spiel='".$spiel."'");
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['ausweichen0'],array($name,$spielerfarbe[$p_besitzer],$p_name));
            }
          }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFF WEICHT PLANET AUS ENDE
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFF WEICHT SCHIFF AUS ANFANG
$sql210 = "SELECT id,status,masse,besitzer,name,klasse,klasseid,kox,koy,volk,bild_gross FROM " . table_prefix . "schiffe use index (spiel) where spiel='".$spiel."' order by id";
$rows210 = $db->execute($sql210);
$schiffanzahl = $rows210->RecordCount();
$checkstring="";
if ($schiffanzahl>=1) {
    $array210_out = $db->getArray($sql210);
    foreach ($array210_out as $array210) {        
        $shid=$array210["id"];
        $status=$array210["status"];
        $besitzer=$array210["besitzer"];
        $name=$array210["name"];
        $klasse=$array210["klasse"];
        $klasseid=$array210["klasseid"];
        $kox=$array210["kox"];
        $koy=$array210["koy"];
        $masse=$array210["masse"];
        $volk=$array210["volk"];
        $bild_gross=$array210["bild_gross"];
        $code=":::".$shid.":::";
        if (strstr($checkstring,$code)) {} else {
            $gemeinsam=0;         
            $sql210_temp = "SELECT count(*) as gemeinsam FROM " . table_prefix . "schiffe use index (kox,koy,besitzer,spiel) where kox='".$kox."' and koy='".$koy."' and besitzer<>".$besitzer." and spiel='".$spiel."'";            
            $gemeinsam=$db->getOne($sql210_temp);
            if ($gemeinsam>=1) {
                $sql211 = "SELECT id,status,masse,besitzer,name,klasse,klasseid,kox,koy,volk,bild_gross FROM " . table_prefix . "schiffe use index (kox,koy,PRIMARY,besitzer,spiel) where kox='".$kox."' and koy='".$koy."' and id<>".$shid." and besitzer<>".$besitzer." and spiel='".$spiel."'";
                $array211_out = $db->getArray($sql211);
                foreach ($array211_out as $array211) {                   
                    $code=":::".$shid.":::";
                    if (strstr($checkstring,$code)) {                        
                    } else {                       
                        $shid_2=$array211["id"];
                        $status_2=$array211["status"];
                        $besitzer_2=$array211["besitzer"];
                        $name_2=$array211["name"];
                        $klasse_2=$array211["klasse"];
                        $klasseid_2=$array211["klasseid"];
                        $kox_2=$array211["kox"];
                        $koy_2=$array211["koy"];
                        $masse_2=$array211["masse"];
                        $volk_2=$array211["volk"];
                        $bild_gross_2=$array211["bild_gross"];
                        if ($status==2) {
                            $abstand=20;
                            $sql211_temp = "SELECT besitzer FROM " . table_prefix . "planeten use index (x_pos,y_pos,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'";                            
                            $p_besitzer=$db->getOne($sql211_temp);
                            if ($p_besitzer==$besitzer) { $springer=2; }
                            if ($p_besitzer==$besitzer_2) { $springer=1; }
                            if (($p_besitzer!=$besitzer) and ($p_besitzer!=$besitzer_2)) {
                                if ($masse==$masse_2) {
                                    $springer=mt_rand(1,2);
                                } else {
                                    if ($masse>$masse_2) {
                                        $springer=2;
                                    } else {
                                        $springer=1;
                                    }
                                }
                            }
                        } else {
                            $abstand=15;
                            if ($masse==$masse_2) {
                                $springer=mt_rand(1,2);
                            } else {
                                if ($masse>$masse_2) {
                                    $springer=2;
                                } else {
                                    $springer=1;
                                }
                            }
                        }
                        $alpha=(double)(6.28318530718*mt_rand(0,$mt_randmax)/$mt_randmax);
                        $koy=max(0,min($umfang,$koy+round(20*sin($alpha))));
                        $kox=max(0,min($umfang,$kox+round(20*cos($alpha))));
                        if ($springer==1) {
                            $db->execute("UPDATE " . table_prefix . "schiffe set status='1',kox='".$kox."', koy='".$koy."' where id='".$shid."' and spiel='".$spiel."'");
                            neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['ausweichen1'],array($name,$spielerfarbe[$besitzer_2],$name_2));
                            $checkstring=$checkstring.":::".$shid.":::";
                        } else {
                            $db->execute("UPDATE " . table_prefix . "schiffe set status=1,kox='".$kox."', koy='".$koy."' where id='".$shid_2."' and spiel='".$spiel."'");
                            neuigkeiten(2,servername . "daten/$volk_2/bilder_schiffe/$bild_gross_2",$besitzer_2,$lang['host']['ausweichen1'],array($name_2,$spielerfarbe[$besitzer],$name));
                            $checkstring=$checkstring.":::".$shid_2.":::";
                        }
                    }
                }
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////SCHIFF WEICHT SCHIFF AUS ENDE
/////////////////////////////////////////////////////////////////////////////////////////////SPEZIALMISSIONEN ANFANG
$sql212 ="SELECT * FROM " . table_prefix . "schiffe use index (spezialmission,spiel) where spezialmission>=1 and spiel='".$spiel."' order by id";
$rows212 = $db->execute($sql212);
$schiffanzahl = $rows212->RecordCount();
if ($schiffanzahl>=1) {
    $array212_out = $db->getArray($sql212);
    foreach ($array212_out as $array212) {       
        $name=$array212["name"];
        $masse=$array212["masse"];
        $klasse=$array212["klasse"];
        $antrieb=$array212["antrieb"];
        $klasseid=$array212["klasseid"];
        $kox=$array212["kox"];
        $koy=$array212["koy"];
        $volk=$array212["volk"];
        $besitzer=$array212["besitzer"];
        $bild_gross=$array212["bild_gross"];
        $frachtraum=$array212["frachtraum"];
        $lemin=$array212["lemin"];
        $leminmax=$array212["leminmax"];
        $crew=$array212["crew"];
        $crewmax=$array212["crewmax"];
        $flug=$array212["flug"];
        $schaden=$array212["schaden"];
        $leichtebt=$array212["leichtebt"];
        $schwerebt=$array212["schwerebt"];
        $erfahrung=$array212["erfahrung"];
        $energetik_stufe=$array212["energetik_stufe"];
        $energetik_anzahl=$array212["energetik_anzahl"];
        $projektile_stufe=$array212["projektile_stufe"];
        $projektile_anzahl=$array212["projektile_anzahl"];
        $projektile=$array212["projektile"];
        $hanger_anzahl=$array212["hanger_anzahl"];
        $sprungtorbauid=$array212["sprungtorbauid"];
        $fertigkeiten=$array212["fertigkeiten"];
        $spezialmission=$array212["spezialmission"];
        $status=$array212["status"];
        $extra = explode(":", trim($array['extra']));
        $fracht_leute=$array212["fracht_leute"];
        $fracht_cantox=$array212["fracht_cantox"];
        $fracht_vorrat=$array212["fracht_vorrat"];
        $fracht_lemin=$array212["lemin"];
        $fracht_min1=$array212["fracht_min1"];
        $fracht_min2=$array212["fracht_min2"];
        $fracht_min3=$array212["fracht_min3"];
        $zusatzmodul=$array212["zusatzmodul"];
        $frachtfrei=$frachtraum-$fracht_vorrat-$fracht_min1-$fracht_min2-$fracht_min3-floor($fracht_leute/100);
        $tankfrei=$leminmax-$fracht_lemin;
        $fert_sub_vorrat=intval(substr($fertigkeiten,0,2));
        $fert_sub_min1=intval(substr($fertigkeiten,2,1));
        $fert_sub_min2=intval(substr($fertigkeiten,3,1));
        $fert_sub_min3=intval(substr($fertigkeiten,4,1));
        $fert_terra_warm=intval(substr($fertigkeiten,5,1));
        $fert_terra_kalt=intval(substr($fertigkeiten,6,1));
        $fert_quark_vorrat=intval(substr($fertigkeiten,7,1));
        $fert_quark_min1=intval(substr($fertigkeiten,8,1));
        $fert_quark_min2=intval(substr($fertigkeiten,9,1));
        $fert_quark_min3=intval(substr($fertigkeiten,10,1));
        $fert_sprung_kosten=intval(substr($fertigkeiten,11,3));
        $fert_sprung_min=intval(substr($fertigkeiten,14,4));
        $fert_sprung_max=intval(substr($fertigkeiten,18,4));
        $fert_sprungtorbau_min1=intval(substr($fertigkeiten,25,3));
        $fert_sprungtorbau_min2=intval(substr($fertigkeiten,28,3));
        $fert_sprungtorbau_min3=intval(substr($fertigkeiten,31,3));
        $fert_sprungtorbau_lemin=intval(substr($fertigkeiten,34,3));
        $fert_reperatur=intval(substr($fertigkeiten,37,1));
        $viralmin=intval(substr($fertigkeiten,41,2));
        $viralmax=intval(substr($fertigkeiten,43,3));
        $erwtrans=intval(substr($fertigkeiten,46,2));
        $cybern=intval(substr($fertigkeiten,48,2));
        $destabi=intval(substr($fertigkeiten,50,2));
        /////////////////////////////////////////////////////////////////////////////////////////////MINENFELD RAEUMEN ANFANG
        if (($module[2]) and ($spezialmission==25) and ($hanger_anzahl>=1)) {
            if($status!=2){
                $erfolg=0;
                if ($hanger_anzahl==1) { $erfolg=1; }
                if ($hanger_anzahl>=2) {
                    $erfolg=mt_rand(round($hanger_anzahl/2),$hanger_anzahl);
                }
                //echo $erfolg;
                if ($erfolg>=1) {
                    $reichweite=100;
                    $minenanzahl=0;
                    $sql213 = "SELECT * FROM " . table_prefix . "anomalien where spiel='".$spiel."' and art='5' and (sqrt(((x_pos-".$kox.")*(x_pos-".$kox."))+((y_pos-".$koy.")*(y_pos-".$koy.")))<=".$reichweite.") order by id";
                    $rows213 = $db->execute($sq213);
                    $datensaetze2 = $rows213->RecordCount();
                    if ($datensaetze2>=1) {
                        $array213_out = $db->getArray($sql213);
                        foreach ($array213_out as $array213) {                            
                            $aid=$array213["id"];
                            $x_pos=$array213["x_pos"];
                            $y_pos=$array213["y_pos"];
                            $mineextra=explode(":",$array213["extra"]);
                            if(($mineextra[0]==$besitzer) or
                                ($beziehung[$besitzer][$mineextra[0]]['status']==3) or
                                ($beziehung[$besitzer][$mineextra[0]]['status']==4) or
                                ($beziehung[$besitzer][$mineextra[0]]['status']==5))
                            {} else {
                                if (intval($mineextra[1])>=$minenanzahl) {
                                    $minenanzahl=intval($mineextra[1]);
                                    $aanomalie[0]=$aid; // id
                                    $aanomalie[1]=$mineextra[0]; // besitzer
                                    $aanomalie[2]=intval($mineextra[1]); // anzahl
                                    $aanomalie[3]=$mineextra[2]; // stufe
                                }
                            }
                        }
                    }
                    if ($minenanzahl>=1) {
                        $aanomalie[2]=$aanomalie[2]-$erfolg;
                        if ($aanomalie[2]<=0) {
                            $db->execute("DELETE FROM " . table_prefix . "anomalien where spiel='".$spiel."' and id='".$aanomalie[0]."'");
                            neuigkeiten(4,servername . "images/news/minenfeld.jpg",$besitzer,$lang['host']['minenfelder2'],array($name));
                            neuigkeiten(4,servername . "images/news/minenfeld.jpg",$aanomalie[1],$lang['host']['minenfelder3']);
                        } else {
                            neuigkeiten(4,servername . "images/news/minenfeld.jpg",$besitzer,$lang['host']['minenfelder4'],array($name,$erfolg));
                            $mineextra=$aanomalie[1].':'.$aanomalie[2].':'.$aanomalie[3];
                            $db->execute("UPDATE " . table_prefix . "anomalien set extra='".$mineextra."' where spiel='".$spiel."' and id='".$aanomalie[0]."'");
                        }
                    }
                }
            }else{
                neuigkeiten(4,servername . "images/news/minenfeld.jpg",$besitzer,$lang['host']['minenfelder7'],array($name));
                $db->execute("UPDATE " . table_prefix . "schiffe set spezialmission='0' where id='".$shid."'");
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////MINENFELD RAEUMEN ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////MINENFELD LEGEN ANFANG
        if (($module[2]) and ($spezialmission==24)) {
            if($status!=2){
                $legen=intval($extra[2]);
                if ($legen>$projektile) {$legen=$projektile;}
                if ($legen>=1) {
                    $projektile=$projektile-$legen;
                    $extra[2]=0;
                    $extra_neu = implode(":", $extra);
                    $mineextra=$besitzer.':'.$legen.':'.$projektile_stufe;
                    $db->execute("UPDATE " . table_prefix . "schiffe set projektile='".$projektile."',spezialmission='0',extra='".$extra_neu."' where id='".$shid."'");
                    $db->execute("INSERT INTO " . table_prefix . "anomalien (art,x_pos,y_pos,extra,spiel) values ('5','".$kox."','".$koy."','".$mineextra."','".$spiel."')");
                    neuigkeiten(4,servername . "images/news/minenfeld.jpg",$besitzer,$lang['host']['minenfelder5'],array($name,$legen));
                } else {
                    $extra[2]=0;
                    $extra_neu = implode(":", $extra);
                    $db->execute("UPDATE " . table_prefix . "schiffe set spezialmission='0',extra='".$extra_neu."' where id='".$shid."'");
                }
            }else{
                neuigkeiten(4, servername . "images/news/minenfeld.jpg",$besitzer,$lang['host']['minenfelder6'],array($name));
                $db->execute("UPDATE " . table_prefix . "schiffe set spezialmission='0' where id='".$shid."'");
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////MINENFELD LEGEN ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////AUTOGRAPSCH ANFANG
        unset($a_planet);
        // Fuer alle Eventualitaeten:
        // Hole Datensatz des Planeten
        if(    (    ($spezialmission==26) or
                ($spezialmission==27) or
                ($spezialmission==28)
            ) and ($status==2))
        {
            // Hole Planeten, um den das aktuelle Schiff gerade kreist
            $sql214 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,spiel) WHERE x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'";
            $query_ret=$db->execute($sql214);
            // Mache nur was, wenn da auch nur ein Planet ist
            if($query_ret && ($query_ret->RecordCount()==1))
            {
                $a_planet=$db->getRow($sql214);
                $planet_id=$a_planet["id"];
            }
        }
        // bei Quarkern gehen wir vorsichtiger vor:
        // 113 Lemin runter, 113 Vorraete und 113 Material rauf
        if( ($spezialmission==26) && ($status==2) && $a_planet)
        {
            beam_s_p($shid,$planet_id,"lemin",113);
            $p_vorrat=$a_planet["vorrat"];
                $p_min1=$a_planet["min1"];
                $p_min2=$a_planet["min2"];
                $p_min3=$a_planet["min3"];
                $osys_1=$a_planet["osys_1"];
                $osys_2=$a_planet["osys_2"];
                $osys_3=$a_planet["osys_3"];
                $osys_4=$a_planet["osys_4"];
                $osys_5=$a_planet["osys_5"];
                $osys_6=$a_planet["osys_6"];
                $p_besitzer=$a_planet["besitzer"];
            if((($osys_1==7) or ($osys_2==7) or ($osys_3==7) or ($osys_4==7) or ($osys_5==7)or ($osys_6==7))and($p_besitzer!=$besitzer)){
                $p_min1=max(0,($p_min1-100));
                $p_min2=max(0,($p_min2-100));
                $p_min3=max(0,($p_min3-100));
            }
            $uber=113;
            if ($fert_quark_vorrat>=1){
                $uber=min(113,$uber,floor(($p_vorrat+$fracht_vorrat)/$fert_quark_vorrat));
            }
            if ($fert_quark_min1>=1){
                $uber=min(113,$uber,floor(($p_min1+$fracht_min1)/$fert_quark_min1));
            }
            if ($fert_quark_min2>=1){
                $uber=min(113,$uber,floor(($p_min2+$fracht_min2)/$fert_quark_min2));
            }
            if ($fert_quark_min3>=1){
                $uber=min(113,$uber,floor(($p_min3+$fracht_min3)/$fert_quark_min3));
            }
            if($fert_quark_vorrat>=1){
                $fert_quark_vorrat_t=($fert_quark_vorrat*$uber)-$fracht_vorrat;
                $fert_quark_vorrat_t=max(0,$fert_quark_vorrat_t);
                $fracht_vorrat+=beam_p_s($planet_id, $shid, "vorrat", $fert_quark_vorrat_t);
            }
            if ($fert_quark_min1>=1){
                $fert_quark_min1_t=($fert_quark_min1*$uber)-$fracht_min1;
                $fert_quark_min1_t=max(0,$fert_quark_min1_t);
                $fracht_min1+=beam_p_s($planet_id, $shid, "min1", $fert_quark_min1_t);
            }
            if ($fert_quark_min2>=1){
                $fert_quark_min2_t=($fert_quark_min2*$uber)-$fracht_min2;
                $fert_quark_min2_t=max(0,$fert_quark_min2_t);
                $fracht_min2+=beam_p_s($planet_id, $shid, "min2", $fert_quark_min2_t);
            }
            if ($fert_quark_min3>=1){
                $fert_quark_min3_t=($fert_quark_min3*$uber)-$fracht_min3;
                $fert_quark_min3_t=max(0,$fert_quark_min3_t);
                $fracht_min3+=beam_p_s($planet_id, $shid, "min3", $fert_quark_min3_t);
            }
        }
        // Wenn Subpartikelcluster an ist und das Schiff sich im Planetenorbit
        // befindet: alles abladen und Vorraete fassen
        if( ($spezialmission==27) && ($status==2) && $a_planet){
            $p_vorrat=$a_planet["vorrat"];
            $osys_1=$a_planet["osys_1"];
            $osys_2=$a_planet["osys_2"];
            $osys_3=$a_planet["osys_3"];
            $osys_4=$a_planet["osys_4"];
            $osys_5=$a_planet["osys_5"];
            $osys_6=$a_planet["osys_6"];
            $p_besitzer=$a_planet["besitzer"];
            if((($osys_1==7) or ($osys_2==7) or ($osys_3==7) or ($osys_4==7) or ($osys_5==7)or ($osys_6==7))and($p_besitzer!=$besitzer)){
                $p_vorrat=max(0,($p_vorrat-100));
            }
            $fert_sub_vorrat_t=min($fert_sub_vorrat*287,floor(($p_vorrat+$fracht_vorrat)/$fert_sub_vorrat)*$fert_sub_vorrat);
            // Erstmal alles runterbeamen
            beam_s_p($shid,$planet_id,"vorrat",$frachtraum);
            beam_s_p($shid,$planet_id,"min1",$frachtraum);
            beam_s_p($shid,$planet_id,"min2",$frachtraum);
            beam_s_p($shid,$planet_id,"min3",$frachtraum);
            // Dann ordentlich Vorraete rauf beamen
            $fracht_vorrat=beam_p_s($planet_id, $shid, "vorrat", $fert_sub_vorrat_t);
        }
        // Wenn Cybernrittnikk an ist und das Schiff sich im Planetenorbit
        // befindet: Kolos abladen und Vorraete fassen
        if( ($spezialmission==28) && ($status==2) && $a_planet){
            // Erstmal alles runterbeamen
            // Aber nur, wenn der Planet niemand wichtigem gehoert.
            if($beziehung[$a_planet["besitzer"]][$besitzer]['status']<3){
                beam_s_p($shid,$planet_id,"kolonisten",$frachtraum*100);
            }
            $p_vorrat=$a_planet["vorrat"];
            $osys_1=$a_planet["osys_1"];
            $osys_2=$a_planet["osys_2"];
            $osys_3=$a_planet["osys_3"];
            $osys_4=$a_planet["osys_4"];
            $osys_5=$a_planet["osys_5"];
            $osys_6=$a_planet["osys_6"];
            $p_besitzer=$a_planet["besitzer"];
            if((($osys_1==7) or ($osys_2==7) or ($osys_3==7) or ($osys_4==7) or ($osys_5==7)or ($osys_6==7))and($p_besitzer!=$besitzer)){
                $p_vorrat=max(0,($p_vorrat-100));
            }
            $p_vorrat=min(220,$p_vorrat);
            // Dann ordentlich Vorraete rauf beamen
            $fracht_vorrat+=beam_p_s($planet_id, $shid, "vorrat", $p_vorrat);
        }
        /////////////////////////////////////////////////////////////////////////////////////////////AUTOGRAPSCH ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////SUBPARTIKELVERZERRUNG ANFANG
        if (    ($fracht_vorrat>=$fert_sub_vorrat)
                and ($fert_sub_vorrat>=1)
                and ( ($spezialmission==4) or ($spezialmission==27) ) )
        {
            $max=floor($fracht_vorrat/$fert_sub_vorrat);           
            if (287<$max) {$max=287;}
            if ($max>=1) {
                $vorrat_verbrauch=$max*$fert_sub_vorrat;
                $min1_prod=$max*$fert_sub_min1;
                $min2_prod=$max*$fert_sub_min2;
                $min3_prod=$max*$fert_sub_min3;
                $db->execute("UPDATE " . table_prefix . "schiffe set fracht_vorrat=fracht_vorrat-".$vorrat_verbrauch.",
                                                                     fracht_min1=fracht_min1+".$min1_prod.",
                                                                     fracht_min2=fracht_min2+".$min2_prod.",
                                                                     fracht_min3=fracht_min3+".$min3_prod." 
                                                         where id='".$shid."'");
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////SUBPARTIKELVERZERRUNG ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////DESTABILISATOR ANFANG
        if (($spezialmission==20)and ($status==2)) {
            $zufall=mt_rand(1,100);
            if ($zufall<=$destabi) {
                $sql215 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and besitzer<>".$besitzer." and spiel='".$spiel."'";
                $rows215 = $db->execute($sql215);
                $planetenanzahl = $rows215->RecordCount();
                if ($planetenanzahl==1) {
                    $array2 = $db->getRow($sql215);
                    $p_id=$array2["id"];
                    $p_besitzer=$array2["besitzer"];
                    $p_name=$array2["name"];
                    $osys_1=$array2["osys_1"];
                    $osys_2=$array2["osys_2"];
                    $osys_3=$array2["osys_3"];
                    $osys_4=$array2["osys_4"];
                    $osys_5=$array2["osys_5"];
                    $osys_6=$array2["osys_6"];
                    if(($osys_1!=19) and ($osys_2!=19) and ($osys_3!=19) and ($osys_4!=19) and ($osys_5!=19) and ($osys_6!=19)){
                        if ($beziehung[$besitzer][$p_besitzer]['status']!=5) {
                            $sektork=sektor($kox,$koy);
                            $db->execute("DELETE FROM " . table_prefix . "planeten where id='".$p_id."' and besitzer='".$p_besitzer."'");
                            $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 'p:".$p_id.":%'");
                            $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug='2' and zielid='".$p_id."'");
                            $db->execute("UPDATE " . table_prefix . "schiffe set status='1' where kox='".$kox."' and koy='".$koy."' and spiel='".$spiel."'");
                            $db->execute("DELETE FROM " . table_prefix . "sternenbasen where x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'");
                            $suche=array('{1}','{2}');
                            $ersetzen=array($p_name,$sektork);
                            $text=str_replace($suche,$ersetzen,$text);
                            if (($spieler_1>=1) and ($p_besitzer<>1)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",1,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_2>=1) and ($p_besitzer<>2)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",2,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_3>=1) and ($p_besitzer<>3)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",3,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_4>=1) and ($p_besitzer<>4)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",4,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_5>=1) and ($p_besitzer<>5)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",5,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_6>=1) and ($p_besitzer<>6)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",6,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_7>=1) and ($p_besitzer<>7)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",7,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_8>=1) and ($p_besitzer<>8)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",8,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_9>=1) and ($p_besitzer<>9)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",9,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if (($spieler_10>=1) and ($p_besitzer<>10)) { neuigkeiten(4,servername . "images/news/star_explode.jpg",10,$lang['host']['destabilisator0'],array($p_name,$sektork)); }
                            if ($p_besitzer>=1) {
                                neuigkeiten(4,servername . "images/news/star_explode.jpg",$p_besitzer,$lang['host']['destabilisator1'],array($p_name,$sektork));
                            }
                        }
                    }else{
                        neuigkeiten(4,servername . "images/news/star_explode.jpg",$besitzer,$lang['host']['destabilisator2'],array($p_name,$sektork));
                    }
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////DESTABILISATOR ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////CYBERRITTNIKK ANFANG
        if ($fracht_vorrat>=220 && ($spezialmission==19 || $spezialmission==28) && $s_eigenschaften[$besitzer]['rasse']==$volk) {
            $kolonistengebaut = 220*$cybern;
            $fracht_leute += $kolonistengebaut;
            $db->execute("UPDATE " . table_prefix . "schiffe SET fracht_vorrat=fracht_vorrat-220, fracht_leute=fracht_leute+".$kolonistengebaut." WHERE id='".$shid."'");
        }
        /////////////////////////////////////////////////////////////////////////////////////////////CYBERRITTNIKK ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////QUARKREORGANISATOR ANFANG
        if ( ($spezialmission==6) || ($spezialmission==26) ){
            $max=$tankfrei;
            if ($fert_quark_vorrat>=1) {
                $max_vorrat=floor($fracht_vorrat/$fert_quark_vorrat);
                if ($max>$max_vorrat) {$max=$max_vorrat;}
            }
            if ($fert_quark_min1>=1) {
                $max_min1=floor($fracht_min1/$fert_quark_min1);
                if ($max>$max_min1) {$max=$max_min1;}
            }
            if ($fert_quark_min2>=1) {
                $max_min2=floor($fracht_min2/$fert_quark_min2);
                if ($max>$max_min2) {$max=$max_min2;}
            }
            if ($fert_quark_min3>=1) {
                $max_min3=floor($fracht_min3/$fert_quark_min3);
                if ($max>$max_min3) {$max=$max_min3;}
            }
            if (113<$max) {$max=113;}
            if ($max>=1) {
                $vorrat_verbrauch=$max*$fert_quark_vorrat;
                $min1_verbrauch=$max*$fert_quark_min1;
                $min2_verbrauch=$max*$fert_quark_min2;
                $min3_verbrauch=$max*$fert_quark_min3;
                $lemin_prod=$max;
                $db->execute("UPDATE " . table_prefix . "schiffe set fracht_vorrat=fracht_vorrat-".$vorrat_verbrauch.",
                                                                     fracht_min1=fracht_min1-".$min1_verbrauch.",
                                                                     fracht_min2=fracht_min2-".$min2_verbrauch.",
                                                                     fracht_min3=fracht_min3-".$min3_verbrauch.",
                                                                     lemin=lemin+".$lemin_prod." where id='".$shid."'");
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////QUARKREORGANISATOR ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////SCHIFF RECYCLEN ANFANG
        if (($spezialmission==2) and ($status==2)) {
            $sql216 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and besitzer='".$besitzer."' and spiel='".$spiel."'";
            $rows216 = $db->execute($sql216);
            $planetenanzahl = $rows216->RecordCount();
            if ($planetenanzahl==1) {
                $array2 = $db->getRow($sql216);
                $p_id=$array2["id"];
                $p_sternenbasis=$array2["sternenbasis"];
                $p_sternenbasis_id=$array2["sternenbasis_id"];
                $osys_1=$array2["osys_1"];
                $osys_2=$array2["osys_2"];
                $osys_3=$array2["osys_3"];
                $osys_4=$array2["osys_4"];
                $osys_5=$array2["osys_5"];
                $osys_6=$array2["osys_6"];
                if ($p_sternenbasis_id>=1 or $osys_1==17 or $osys_2==17 or $osys_3==17 or $osys_4==17 or $osys_5==17 or $osys_6==17) {
                    $neu_min1=0;
                    $neu_min2=0;
                    $neu_min3=0;
                    $file= daten_dir.$volk.'/schiffe.txt';
                    $fp = fopen(" . $file . ","r");
                    if ($fp) {
                        $zaehler=0;
                        while (!feof ($fp)) {
                            $buffer = fgets($fp, 4096);
                            $schiff[$zaehler]=$buffer;
                            $zaehler++;
                        }
                        fclose($fp);
                    }
                    for ($ik=0;$ik<$zaehler;$ik++) {
                        $schiffwert=explode(':',$schiff[$ik]);
                        if ($schiffwert[1]==$klasseid) {
                            $neu_min1=round($schiffwert[6]/100*85);
                            $neu_min2=round($schiffwert[7]/100*85);
                            $neu_min3=round($schiffwert[8]/100*85);
                        }
                    }
                    $db->execute("UPDATE " . table_prefix . "planeten set kolonisten=kolonisten+".$fracht_leute.", 
                                                                          lemin=lemin+".$fracht_lemin.", 
                                                                          min1=min1+".$fracht_min1.", 
                                                                          min2=min2+".$fracht_min2.", 
                                                                          min3=min3+".$fracht_min3.", 
                                                                          vorrat=vorrat+".$fracht_vorrat.", 
                                                                          cantox=cantox+".$fracht_cantox." 
                                                            where id='".$p_id."'");
                    $db->execute("UPDATE " . table_prefix . "planeten set min1=min1+".$neu_min1.",min2=min2+".$neu_min2.",min3=min3+".$neu_min3." where id='".$p_id."'");
                    $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$shid."'");
                    $db->execute("DELETE FROM " . table_prefix . "anomalien where art=3 and extra like 's:".$shid.":%'");
                    $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$shid."'");
                    neuigkeiten(3,servername . "daten/$volk/bilder_basen/1.jpg",$besitzer,$lang['host']['recycle0'],array($name,$neu_min1,$neu_min2,$neu_min3));
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////SCHIFF RECYCLEN ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////SCHIFF REPARATUR ANFANG
        if (($spezialmission==14) and ($status==2) and ($schaden>=1)) {
            $reperatur=0;
            $sql217 = "SELECT id,x_pos,y_pos,besitzer,spiel,sternenbasis,sternenbasis_id,sternenbasis_art FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) 
                                                                                                          where x_pos='".$kox."' 
                                                                                                            and y_pos='".$koy."' 
                                                                                                            and besitzer='".$besitzer."' 
                                                                                                            and spiel='".$spiel."'";
            $rows217 = $db->execute($sql217);
            $planetenanzahl = $rows217->RecordCount();
            if ($planetenanzahl==1) {
                $array2 = $db->getRow($sql217);
                $p_id=$array2["id"];
                $p_sternenbasis=$array2["sternenbasis"];
                $p_sternenbasis_id=$array2["sternenbasis_id"];
                $p_sternenbasis_art=$array2["sternenbasis_art"];
                if (($p_sternenbasis_id>=1) and ($p_sternenbasis_art==0)) {
                    $reperatur=11;
                }
                if (($p_sternenbasis_id>=1) and ($p_sternenbasis_art==3)) {
                    $reperatur=11;
                }
                if (($p_sternenbasis_id>=1) and ($p_sternenbasis_art==1)) {
                    $reperatur=19;
                }
                $sql218 = "SELECT id,kox,koy,besitzer,fertigkeiten,status FROM " . table_prefix . "schiffe use index (besitzer,kox,koy,status,spiel) where besitzer='".$besitzer."' and kox='".$kox."' and koy='".$koy."' and status='2' and spiel='".$spiel."' order by id";
                $rows218 = $db->execute($sql218);
                $schiffanzahl3 = $rows218->RecordCount();
                if ($schiffanzahl3>=1) {
                    $array218_out = $db->getArray($sql218);
                    foreach ($array218_out as $array3) {                        
                        $kox=$array3["kox"];
                        $koy=$array3["koy"];
                        $besitzer=$array3["besitzer"];
                        $fertigkeiten=$array3["fertigkeiten"];
                        $status=$array3["status"];
                        $fert_reperatur=intval(substr($fertigkeiten,37,1));
                        if (($fert_reperatur>=1) and ($fert_reperatur>$reperatur)) { $reperatur=$fert_reperatur; }
                    }
                }
                if ($reperatur>=1) {
                    $schaden=$schaden-$reperatur;
                    if ($schaden>=1) {
                        $db->execute("UPDATE " . table_prefix . "schiffe set schaden='".$schaden."' where id='".$shid."'");
                    } else {
                        $schaden=0;
                        $db->execute("UPDATE " . table_prefix . "schiffe set schaden=0 where id='".$shid."'");
                        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['reparatur0'],array($name));
                    }
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////SCHIFF REPARATUR ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////CREW ANHEUERN ANFANG
        if (($spezialmission==23) and ($status==2)) {
            $reperatur=0;
            $sql220 = "SELECT id,kolonisten,x_pos,y_pos,besitzer,spiel,sternenbasis,sternenbasis_id FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and besitzer='".$besitzer."' and spiel='".$spiel."'";
            $rows220 = $db->execute($sql220);
            $planetenanzahl = $rows220->RecordCount();
            if ($planetenanzahl==1) {
                $array220 = $db->getRow($sql220);
                $p_id=$array220["id"];
                $p_sternenbasis=$array220["sternenbasis"];
                $p_sternenbasis_id=$array220["sternenbasis_id"];
                $p_kolonisten=$array220["kolonisten"];
                if ($p_sternenbasis_id>=1) {
                    $leute_neu=intval($extra[1]);
                    if ($leute_neu>$p_kolonisten) { $leute_neu=$p_kolonisten; }
                    $p_kolonisten=$p_kolonisten-$leute_neu;
                    $crew=$crew+$leute_neu;
                    $extra[1]='';
                    $extra_neu = implode(":", $extra);
                    $db->execute("UPDATE " . table_prefix . "schiffe set crew='".$crew."', 
                                                                         extra='".$extra_neu."', 
                                                                         spezialmission='0' where id='".$shid."'");
                    $db->execute("UPDATE " . table_prefix . "planeten set kolonisten='".$p_kolonisten."' where id='".$p_id."'");
                    
                    if($crew==$crewmax){
                        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['crew0'],array($name,$leute_neu));
                    }else{
                        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['crew0'],array($name,$leute_neu,$crew));
                    }
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////CREW ANHEUERN ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////TANKEN ANFANG
        if (($spezialmission==1) and ($status==2)) {
            $sql221 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'";
            $rows221 = $db->execute($sql221);
            $planetenanzahl = $rows221->RecordCount();
            if ($planetenanzahl==1) {
                $array221 = $db->getRow($sql221);
                $p_id=$array221["id"];
                $osys_1=$array221["osys_1"];
                $osys_2=$array221["osys_2"];
                $osys_3=$array221["osys_3"];
                $osys_4=$array221["osys_4"];
                $osys_5=$array221["osys_5"];
                $osys_6=$array221["osys_6"];
                $p_lemin=$array221["lemin"];
                $p_besitzer=$array221["besitzer"];
                if((($osys_1==7) or ($osys_2==7) or ($osys_3==7) or ($osys_4==7) or ($osys_5==7)or ($osys_6==7))and($p_besitzer!=$besitzer)){
                    $p_lemin=max(0,($p_lemin-100));
                }
                $lemin_tanken=$leminmax-$lemin;
                if ($lemin_tanken>$p_lemin) {$lemin_tanken=$p_lemin;}
                $db->execute("UPDATE " . table_prefix . "planeten set lemin=lemin-".$lemin_tanken." where id='".$p_id."'");
                $db->execute("UPDATE " . table_prefix . "schiffe set lemin=lemin+".$lemin_tanken." where id='".$shid."'");
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////TANKEN ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////PLANETENBOMBARDEMENT ANFANG
        if (($spezialmission==3) and ($status==2)) {
            $sql222 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and besitzer<>".$besitzer." and besitzer>=1 and spiel='".$spiel."'";
            $rows222 = $db->execute($sql222);
            $planetenanzahl = $rows222->RecordCount();
            if ($planetenanzahl==1) {
                $array222 = $db->getRow($sql222);
                $p_id=$array222["id"];
                $osys_1=$array222["osys_1"];
                $osys_2=$array222["osys_2"];
                $osys_3=$array222["osys_3"];
                $osys_4=$array222["osys_4"];
                $osys_5=$array222["osys_5"];
                $osys_6=$array222["osys_6"];
                $p_kolonisten=$array222["kolonisten"];
                $p_minen=$array222["minen"];
                $p_fabriken=$array222["fabriken"];
                $p_abwehr=$array222["abwehr"];
                $p_name=$array222["name"];
                $p_bild=$array222["bild"];
                $p_klasse=$array222["klasse"];
                $p_besitzer=$array222["besitzer"];
                $native_id=$array222["native_id"];
                $native_abgabe=$array222["native_abgabe"];
                $native_fert=$array222["native_fert"];
                $native_kol=$array222["native_kol"];
                $native_fert_schutz=intval(substr($native_fert,21,2));
                if ($beziehung[$besitzer][$p_besitzer]['status']!=5) {
                    $maxcol=100;
                    if (($native_id>=1) and ($native_kol>1)) { $maxcol=$maxcol-$native_fert_schutz; }
                    $staerke_angriff=round(($hanger_anzahl*35)+($torpedoschaden[$projektile_stufe]*$projektile_anzahl)+($strahlenschaden[$energetik_stufe]*$energetik_anzahl));
                    $prozent=round($staerke_angriff/4);
                    $prozente[0]=mt_rand(0,$prozent);
                    $prozente[1]=mt_rand(0,($prozent-$prozente[0]));
                    $prozente[2]=mt_rand(0,($prozent-$prozente[0]-$prozente[1]));
                    $prozente[3]=($prozent-$prozente[0]-$prozente[1]-$prozente[2]);
                    shuffle($prozente);
                    $prozent_kolonisten=$prozente[0];if ($prozent_kolonisten>100) { $prozent_kolonisten=100; }
                    $prozent_minen=$prozente[1];if ($prozent_minen>100) { $prozent_minen=100; }
                    $prozent_fabriken=$prozente[2];if ($prozent_fabriken>100) { $prozent_fabriken=100; }
                    $prozent_abwehr=$prozente[3];if ($prozent_abwehr>100) { $prozent_abwehr=100; }
                    if ($prozent_kolonisten>$maxcol) {$prozent_kolonisten=$maxcol;}
                    $vernichtet_kolonisten=round($p_kolonisten/100*$prozent_kolonisten);
                    $o_kolonisten=$p_kolonisten;
                    $p_kolonisten=$p_kolonisten-$vernichtet_kolonisten;
                    if(($osys_1==7) or ($osys_2==7) or ($osys_3==7) or ($osys_4==7) or ($osys_5==7)or ($osys_6==7)){
                        $p_kolonisten=max(1000,$p_kolonisten);
                        $vernichtet_kolonisten=$o_kolonisten-$p_kolonisten;
                        $prozent_kolonisten=round($vernichtet_kolonisten/$o_kolonisten*100);
                    }
                    $vernichtet_minen=round($p_minen/100*$prozent_minen);
                    $vernichtet_fabriken=round($p_fabriken/100*$prozent_fabriken);
                    $vernichtet_abwehr=round($p_abwehr/100*$prozent_abwehr);
                    $db->execute("UPDATE " . table_prefix . "planeten set kolonisten='".$p_kolonisten."',minen=minen-".$vernichtet_minen.",fabriken=fabriken-".$vernichtet_fabriken.",abwehr=abwehr-".$vernichtet_abwehr." where id='".$p_id."'");
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['bombardement0'],array($name,$p_name,$vernichtet_minen,$prozent_minen,$vernichtet_fabriken,$prozent_fabriken,$vernichtet_abwehr,$prozent_abwehr,$vernichtet_kolonisten,$prozent_kolonisten));
                    neuigkeiten(1,servername . "images/planeten/$p_klasse"."_"."$p_bild.jpg",$p_besitzer,$lang['host']['bombardement1'],array($p_name,$vernichtet_minen,$prozent_minen,$vernichtet_fabriken,$prozent_fabriken,$vernichtet_abwehr,$prozent_abwehr,$vernichtet_kolonisten,$prozent_kolonisten));
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////PLANETENBOMBARDEMENT ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////VIRALER ANGRIFF ANFANG
        if (($spezialmission==17) and ($status==2)) {
            $sql223 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and besitzer<>".$besitzer." and besitzer>=1 and spiel='".$spiel."'";
            $rows223 = $db->execute($sql223);
            $planetenanzahl = $rows223->RecordCount();
            if ($planetenanzahl==1) {
                $array223 = $db->getRow($sql223);
                $p_id=$array223["id"];
                $p_kolonisten=$array223["kolonisten"];
                $p_name=$array223["name"];
                $p_bild=$array223["bild"];
                $p_klasse=$array223["klasse"];
                $p_besitzer=$array223["besitzer"];
                $osys_1=$array223["osys_1"];
                $osys_2=$array223["osys_2"];
                $osys_3=$array223["osys_3"];
                $osys_4=$array223["osys_4"];
                $osys_5=$array223["osys_5"];
                $osys_6=$array223["osys_6"];
                if(($osys_1!=20) and ($osys_2!=20) and ($osys_3!=20) and ($osys_4!=20) and ($osys_5!=20) and ($osys_6!=20)){
                    if ($beziehung[$besitzer][$p_besitzer]['status']!=5) {
                        $prozent_kolonisten=mt_rand($viralmin,$viralmax);
                        $vernichtet_kolonisten=round($p_kolonisten/100*$prozent_kolonisten);
                        $db->execute("UPDATE " . table_prefix . "planeten set kolonisten=kolonisten-".$vernichtet_kolonisten." where id='".$p_id."'");
                        neuigkeiten(2,servername . "images/news/epedemie.jpg",$besitzer,$lang['host']['viral0'],array($name,$p_name,$vernichtet_kolonisten,$prozent_kolonisten));
                        neuigkeiten(1,servername . "images/news/epedemie.jpg",$p_besitzer,$lang['host']['viral1'],array($p_name,$vernichtet_kolonisten,$prozent_kolonisten));
                    }
                }else{
                    neuigkeiten(2,servername . "images/news/epedemie.jpg",$besitzer,$lang['host']['viral4'],array($name,$p_name));
                    neuigkeiten(1,servername . "images/news/epedemie.jpg",$p_besitzer,$lang['host']['viral5'],array($p_name));
                }
            }
        }
        if (($spezialmission==18) and ($status==2)) {
            $sql224 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,native_id,native_kol) where x_pos='".$kox."' and y_pos='".$koy."' and native_id>=1 and native_kol>0 and spiel='".$spiel."'";
            $rows224 = $db->execute($sql224);
            
            $planetenanzahl = $rows224->RecordCount();
            if ($planetenanzahl==1) {
                $array224 = $db->getRow($sql224);
                $p_id=$array224["id"];
                $p_name=$array224["name"];
                $p_bild=$array224["bild"];
                $p_klasse=$array224["klasse"];
                $native_id=$array224["native_id"];
                $native_kol=$array224["native_kol"];
                $p_besitzer=$array224["besitzer"];
                $osys_1=$array224["osys_1"];
                $osys_2=$array224["osys_2"];
                $osys_3=$array224["osys_3"];
                $osys_4=$array224["osys_4"];
                $osys_5=$array224["osys_5"];
                $osys_6=$array224["osys_6"];
                if(($osys_1!=20) and ($osys_2!=20) and ($osys_3!=20) and ($osys_4!=20) and ($osys_5!=20) and ($osys_6!=20)){
                    if ($beziehung[$besitzer][$p_besitzer]['status']!=5) {
                        $prozent_native=mt_rand($viralmin,$viralmax);
                        $vernichtet_native=round($native_kol/100*$prozent_native);
                        $db->execute("UPDATE " . table_prefix . "planeten set native_kol=native_kol-".$vernichtet_native." where id='".$p_id."'");
                        neuigkeiten(2,servername . "images/news/epedemie.jpg",$besitzer,$lang['host']['viral2'],array($name,$p_name,$vernichtet_native,$prozent_native));
                        if ($p_besitzer>=1) {
                            neuigkeiten(1,servername . "images/news/epedemie.jpg",$p_besitzer,$lang['host']['viral3'],array($p_name,$vernichtet_native,$prozent_native));
                        }
                    }
                }else{
                    neuigkeiten(2,servername . "images/news/epedemie.jpg",$besitzer,$lang['host']['viral4'],array($name,$p_name));
                    if ($p_besitzer>=1) {
                        neuigkeiten(1,servername . "images/news/epedemie.jpg",$p_besitzer,$lang['host']['viral6'],array($p_name));
                    }
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////VIRALER ANGRIFF ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////TERRAFORMING ANFANG
        if (($spezialmission==5) and ($status==2)) {
            $sql225 = "SELECT * FROM " . table_prefix . "planeten where x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'";
            $rows225 = $db->exceute($sql225);            
            $planetenanzahl = $rows225->RecordCount();
            if ($planetenanzahl==1) {
                $array225 = $db->getRow($sql225);
                $p_id=$array225["id"];
                $p_temp=$array225["temp"];
                $p_name=$array225["name"];
                if ($fert_terra_warm>=1) {
                    $p_temp=$p_temp+$fert_terra_warm;
                    $tempschreib=$p_temp-35;
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['terraforming0'],array($p_name,$fert_terra_warm,$tempschreib));
                }
                if ($fert_terra_kalt>=1) {
                    $p_temp=$p_temp-$fert_terra_kalt;
                    $tempschreib=$p_temp-35;
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['terraforming1'],array($p_name,$fert_terra_kalt,$tempschreib));
                }
                $db->execute("UPDATE " . table_prefix . "planeten set temp='".$p_temp."' where id='".$p_id."'");
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////TERRAFORMING ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////SPRUNGTOR ANFANG
        if (($spezialmission==13) and ($status==1) and ($flug==0)) {
            $ok=2;
            $sql226 = "SELECT y_pos,x_pos,spiel from " . table_prefix . "planeten where (sqrt(((x_pos-".$kox.")*(x_pos-".$kox."))+((y_pos-".$koy.")*(y_pos-".$koy.")))<=30) and spiel='".$spiel."'";
            $rows226 = $db->execute($sql226);
            $p2anzahl = $rows226->RecordCount();
            if ($p2anzahl>=1) {
                $ok=1;
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['sprungtor0']);
            }else{
                $sql227 = "SELECT y_pos,x_pos,spiel from " . table_prefix . "anomalien where (sqrt(((x_pos-".$kox.")*(x_pos-".$kox."))+((y_pos-".$koy.")*(y_pos-".$koy.")))<=30) and spiel='".$spiel."'";
                $rows227 = $db->execute($sql227);
                $a2anzahl = $rows227->RecordCont();
                if ($a2anzahl>=1) {
                    $ok=1;
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['sprungtor1']);
                }else{
                    if (($fert_sprungtorbau_min1>$fracht_min1) or ($fert_sprungtorbau_min2>$fracht_min2) or ($fert_sprungtorbau_min3>$fracht_min3) or ($fert_sprungtorbau_lemin>$fracht_lemin)) {
                        $ok=1;
                        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['sprungtor2']);
                    }
                }
            }
            if ($ok==2) {
                $db->execute("UPDATE " . table_prefix . "schiffe set fracht_min1=fracht_min1-".$fert_sprungtorbau_min1.", 
                                                                     fracht_min2=fracht_min2-".$fert_sprungtorbau_min2.", 
                                                                     fracht_min3=fracht_min3-".$fert_sprungtorbau_min3.", 
                                                                     lemin=lemin-".$fert_sprungtorbau_lemin." where id='".$shid."'");
                if ($sprungtorbauid>=1) {
                    $sql_temp = "SELECT * FROM " . table_prefix . "anomalien where id='".$sprungtorbauid."'";
                    $array_temp = $db->getRow($sql_temp);
                    $aid=$array_temp["id"];
                    $x_pos_eins=$array_temp["x_pos"];
                    $y_pos_eins=$array_temp["y_pos"];
                    $extra=$sprungtorbauid.":".$x_pos_eins.":".$y_pos_eins;
                    $db->execute("INSERT INTO " . table_prefix . "anomalien (art,x_pos,y_pos,extra,spiel) values ('2','".$kox."','".$koy."','".$extra."','".$spiel."')");
                    $sql228 = "SELECT * FROM " . table_prefix . "anomalien where x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'";
                    $array228 = $db->getRow($sql228);
                    $aid_zwei=$array228["id"];
                    $x_pos_zwei=$array228["x_pos"];
                    $y_pos_zwei=$array228["y_pos"];
                    $extra=$aid_zwei.":".$x_pos_zwei.":".$y_pos_zwei;
                    $db->execute("UPDATE " . table_prefix . "anomalien set extra='".$extra."' where id='".$sprungtorbauid."'");
                    $db->execute("UPDATE " . table_prefix . "schiffe set sprungtorbauid='0',spezialmission='0' where id='".$shid."'");
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['sprungtor3']);
                } else {
                    $db->execute("INSERT INTO " . table_prefix . "anomalien (art,x_pos,y_pos,spiel) values ('2','".$kox."','".$koy."','".$spiel."')");
                    $tempsql = "SELECT id FROM " . table_prefix . "anomalien where x_pos='".$kox."' and y_pos='".$koy."' and spiel='".$spiel."'";
                    $aid=$db->geOne($tempsql);
                    $db->execute("UPDATE " . table_prefix . "schiffe set sprungtorbauid='".$aid."',spezialmission='0' where id='".$shid."'");
                    neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['sprungtor4']);
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////SPRUNGTOR ENDE
        /////////////////////////////////////////////////////////////////////////////////////////////Akademieausbildung Anfang
        if(($spezialmission>71) and ($spezialmission<77) and ($masse<100)){
            if($flug==0){
                $sql230 = "SELECT * FROM " . table_prefix . "planeten use index (besitzer,spiel,x_pos,y_pos) where besitzer='".$spieler."' and spiel='".$spiel."' and x_pos='".$kox."' and y_pos='".$koy."'";
                $rows230 = $db->execute($sql230);
                $planeten_anzahl = $rows230->RecordCount();
                $fert_akademie=0;
                if($planeten_anzahl==1){                    
                    $array230 = $db->getRow($sql230);
                    $osys_1=$array230["osys_1"];
                    $osys_2=$array230["osys_2"];
                    $osys_3=$array230["osys_3"];
                    $osys_4=$array230["osys_4"];
                    $osys_5=$array230["osys_5"];
                    $osys_6=$array230["osys_6"];
                    $sternenbasis_art=$array230["sternenbasis_art"];
                    if((($osys_1==16) or ($osys_2==16) or ($osys_3==16) or ($osys_4==16) or ($osys_5==16) or ($osys_6==16))and($sternenbasis_art==2)and$erfahrung<5){
                        $fert_akademie=1;
                    }
                    for($j=1;$j<=strlen($fertigkeiten);$j++){
                        if(($j<53) or($j>55)){
                            if(intval(substr($fertigkeiten,$j,1))!=0){
                                $fert_akademie=0;
                            }
                        }
                    }
                    if($fert_akademie==1){
                        if($spezialmission==72 and $fracht_cantox>=100 and $fracht_vorrat>=10 and $fracht_lemin>=50){
                            $db->execute("UPDATE " . table_prefix . "schiffe set fracht_cantox=fracht_cantox-100, 
                                                                                 fracht_vorrat=fracht_vorrat-10, 
                                                                                 lemin=lemin-50, 
                                                                                 erfahrung=erfahrung+1, 
                                                                                 spezialmission='0' 
                                                                     where id='".$shid."' and erfahrung<5 and spiel='".$spiel."'");
                            
                            neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['akademie2'],array($name));
                        }elseif($spezialmission>72 and $spezialmission<77){
                            $spezialmission--;
                            $db->execute("UPDATE " . table_prefix . "schiffe set spezialmission='".$spezialmission."' where id='".$shid."' and spiel='".$spiel."'");
                            if($spezialmission>72){
                                neuigkeiten(2, servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['akademie0'],array($name,$spezialmission-71));
                            }else{
                                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['akademie1'],array($name));
                            }
                        }
                    }
                }
            }else{
                $db->execute("UPDATE " . table_prefix . "schiffe set spezialmission='0' where id='".$shid."' and spiel='".$spiel."'");
                neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['akademie3'],array($name));
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////Akademieausbildung Ende
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////TARNFELD ANFANG
$db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='1' where spezialmission='8' and spiel='".$spiel."'");
if($module[0]) {
    $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='0' where spezialmission<>8 and !(volk='unknown' and klasseid='1') and spiel='".$spiel."'");
    $sql235 = "SELECT * FROM " . table_prefix . "schiffe where tarnfeld='1' and !(volk='unknown' and klasseid='1') and spiel='".$spiel."' order by id";
}else {
    $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='0' where spezialmission<>8 and spiel='".$spiel."'");        
    $sql235 = "SELECT * FROM " . table_prefix . "schiffe where tarnfeld='1' and spiel='".$spiel."' order by id";
}
$rows235 = $db->execute($sql235);
$schiffanzahl = $rows235->RecordCount();
if ($schiffanzahl>=1) {
    $array_out235 = $db->getArray($sql235);
    foreach ($array235_out as $array235) {       
        $shid=$array235["id"];
        $masse=$array235["masse"];
        $fracht_min2=$array235["fracht_min2"];
        $min2_brauch=round(($masse/100)+0.5);
        if ($min2_brauch<=$fracht_min2) {
            $fracht_min2=$fracht_min2-$min2_brauch;
            $db->execute("UPDATE " . table_prefix . "schiffe set fracht_min2='".$fracht_min2."' where id='".$shid."'");
        } else {
            $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='0' where id='".$shid."'");
        }
    }
}
$sql240 = "SELECT name,volk,besitzer,bild_gross,id,tarnfeld,spiel,antrieb FROM " . table_prefix . "schiffe where tarnfeld='1' and spiel='".$spiel."' and antrieb='7' order by id";
$rows240 = $db->execute($sql240);
$schiffanzahl = $rows240->RecordCount();
if ($schiffanzahl>=1) {
    $array240_out = $db->getArray($sql240);
    foreach ($array240_out as $array240) {        
        $shid=$array240["id"];
        $name=$array240["name"];
        $volk=$array240["volk"];
        $besitzer=$array240["besitzer"];
        $bild_gross=$array240["bild_gross"];
        $zuzahl=mt_rand(1,100);
        if ($zuzahl<=19) {
            $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='0' where id='".$shid."'");
        neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['tarnfeld0'],array($name));
        }
    }
}
$sql250 = "SELECT id,spiel,antrieb,antrieb_anzahl,kox,koy FROM " . table_prefix . "schiffe where spiel='".$spiel."' and antrieb='3' order by id";
$rows250 = $db->execute($sql250);
$schiffanzahl = $rows250->RecordCount();
if ($schiffanzahl>=1) {
    $array250_out = $db->getArray($sql250);
    foreach ($array250_out as $array250) {        
        $shid=$array250["id"];
        $kox=$array250["kox"];
        $koy=$array250["koy"];
        $antrieb_anzahl=$array250["antrieb_anzahl"];
        $zuzahl=mt_rand(1,100);
        if ($zuzahl<=($antrieb_anzahl*2)) {
            neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['drugun2'],array($name));
            $reichweite=117;
            if($module[0]) {
                $sql260 = "SELECT id, besitzer, name, volk, bild_gross FROM " . table_prefix . "schiffe where (sqrt(((kox-".$kox.")*(kox-".$kox."))+((koy-".$koy.")*(koy-".$koy.")))<=".$reichweite.") and tarnfeld='1' and antrieb<>2 and spiel='".$spiel."' order by id";
                $rows260 = $db->execute($sql260);
                $treffschiff = $rows260->RecordCount();
                if ($treffschiff>=1) {
                    $array260_out = $db->getArray($sql260);
                    foreach ($array260_out as $array260) {                        
                        $t_shid=$array260["id"];
                        $t_besitzer=$array260["besitzer"];
                        $t_name=$array260["name"];
                        $t_volk=$array260["volk"];
                        $t_bild_gross=$array260["bild_gross"];
                        $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld=0 where id='".$t_shid."' and spiel='".$spiel."'");
                        neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['drugun0'],array($t_name));
                    }
                }
                //spion tarner nur dekrementieren
                $sql270 = "SELECT id,tarnfeld, besitzer, name, volk, bild_gross FROM " . table_prefix . "schiffe where (sqrt(((kox-$kox)*(kox-$kox))+((koy-$koy)*(koy-$koy)))<=$reichweite) and volk='unknown' and klasseid='1' and spiel='".$spiel."'";
                $rows270 = $db->execute($sql270);
                $schiffanzahl = $rows270->RecordCount();
                if($schiffanzahl>=1) {
                    $array270_out = $db->getArray($sql270);
                    foreach($array270_out as $array270) {                        
                        $t_shid=$array270["id"];
                        $t_besitzer=$array270["besitzer"];
                        $t_name=$array270["name"];
                        $t_volk=$array270["volk"];
                        $t_bild_gross=$array270["bild_gross"];
                        $tarnfeld=$array270["tarnfeld"];
                        $tarnfeld--;
                        if($tarnfeld<=0) {
                            $tarnfeld = 0;
                            neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['drugun1'],array($t_name));
                        }else{
                            neuigkeiten(2, servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['drugun0'],array($t_name));
                        }
                        $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='".$tarnfeld."' where id='".$t_shid."'");
                    }
                }
            }else {
                $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld=0 where (sqrt(((kox-$kox)*(kox-$kox))+((koy-$koy)*(koy-$koy)))<=$reichweite) and spiel='".$spiel."' and tarnfeld='1'");
            }
        }
    }
}
$db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='1' where antrieb='2' and spiel='".$spiel."'");
/////////////////////////////////////////////////////////////////////////////////////////////TARNFELD ENDE
/////////////////////////////////////////////////////////////////////////////////////////////DRUGUNVERZERRER ANFANG
$sql280 = "SELECT fracht_leute,id,name,klasse,kox,koy,volk,besitzer,bild_gross,crew,leichtebt,schwerebt,zusatzmodul,spezialmission,status FROM " . table_prefix . "schiffe use index (spezialmission,zusatzmodul,status,spiel) where spezialmission='30' and zusatzmodul='6' and status='2' and spiel='".$spiel."' order by id";
$rows280 = $db->execute($sql280);
$schiffanzahl = $rows280->RecordCount();
if ($schiffanzahl>=1) {
    $array280_out = $db->getArray($sql280);
    foreach ($array280_out as $array280) {        
        $shid=$array280["id"];
        $name=$array280["name"];
        $klasse=$array280["klasse"];
        $kox=$array280["kox"];
        $koy=$array280["koy"];
        $volk=$array280["volk"];
        $besitzer=$array280["besitzer"];
        $bild_gross=$array280["bild_gross"];
        $crew=$array280["crew"];
        $leichtebt=$array280["leichtebt"];
        $schwerebt=$array280["schwerebt"];
        $zusatzmodul=$array280["zusatzmodul"];
        $spezialmission=$array280["spezialmission"];
        $status=$array280["status"];
        $fracht_leute=$array280["fracht_leute"];
        $zufall=rand(1,100);
        if ($zufall<=67) {
            $reichweite=round($masse/2);
            neuigkeiten(2,servername . "daten/$volk/bilder_schiffe/$bild_gross",$besitzer,$lang['host']['drugun5'],array($name,$reichweite));
            $sql290 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and besitzer='".$besitzer."' and spiel='".$spiel."'";           
            $rows290 = $db->execute($sql290);
            $planetenanzahl = $rows290->RecordCount();
            if ($planetenanzahl==1) {
                $array290 = $db->getRow($sql290);
                $p_id=$array290["id"];
                $p_kolonisten=$array290["kolonisten"];
                $p_leichtebt=$array290["leichtebt"];
                $p_schwerebt=$array290["schwerebt"];
                $p_kolonisten=$p_kolonisten+$crew+$fracht_leute;
                $p_leichtebt+=$leichtebt;
                $p_schwerebt+=$schwerebt;
                $db->execute("UPDATE " . table_prefix . "planeten set kolonisten='".$p_kolonisten."',leichtebt='".$p_leichtebt."',schwererbt='".$p_schwerebt."' where x_pos='".$kox."' and y_pos='".$koy."' and besitzer='".$besitzer."' and spiel='".$spiel."'");
                $sql300 = "SELECT id,tarnfeld, besitzer, name, volk, bild_gross FROM " . table_prefix . "schiffe where (sqrt(((kox-$kox)*(kox-$kox))+((koy-$koy)*(koy-$koy)))<=$reichweite) and tarnfeld>0 and spiel='".$spiel."' order by id";
                $rows300 = $db->execute($sql300);
                $treffschiff = $rows300->RecordCount();
                if ($treffschiff>=1) {
                    $array300_out = $db->getArray($sql300);
                    foreach($array300_out as $array300) {                        
                        $t_shid=$array300["id"];
                        $t_besitzer=$array300["besitzer"];
                        $t_name=$array300["name"];
                        $t_volk=$array300["volk"];
                        $t_bild_gross=$array300["bild_gross"];
                        $tarnfeld=$array300["tarnfeld"];
                        $tarnfeld--;
                        if($tarnfeld==0){
                            neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['drugun3'],array($t_name));
                        }else{
                            neuigkeiten(2,servername . "daten/$t_volk/bilder_schiffe/$t_bild_gross",$t_besitzer,$lang['host']['drugun4'],array($t_name));
                        }
                        $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='".$tarnfeld."' where id='".$t_shid."'");
                    }
                }
            }
            $db->execute("DELETE FROM " . table_prefix . "schiffe where id='".$shid."'");
            $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 's:".$shid.":%'");
            $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug in ('3','4') and zielid='".$shid."'");
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////DRUGUNVERZERRER ENDE
/////////////////////////////////////////////////////////////////////////////////////////////SENSORPHALANX UND LABOR ANFANG
$db->execute("UPDATE " . table_prefix . "schiffe set scanner='0' where spezialmission<>11 and spezialmission<>12 and spiel='".$spiel."'");
$db->execute("UPDATE " . table_prefix . "schiffe set scanner='1' where spezialmission='11' and spiel='".$spiel."'");
$db->execute("UPDATE " . table_prefix . "schiffe set scanner='2' where spezialmission='12' and spiel='".$spiel."'");
/////////////////////////////////////////////////////////////////////////////////////////////SENSORPHALANX UND LABOR ENDE
/////////////////////////////////////////////////////////////////////////////////////////////SPEZIALMISSIONEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////PLANETEN ANFANG
///////////////////////////////////////////////////////////////////////////////////////////////KOLONISTEN UND TRUPPEN SCHRUMPFEN ANFANG
$sql310 = "SELECT id,name,bild,sternenbasis_id,kolonisten,besitzer,spiel,leichtebt,schwerebt,vorrat FROM " . table_prefix . "planeten where besitzer>=1 and kolonisten<1000 and spiel='".$spiel."' order by id";
$rows310 = $db->execute($sql310);
$planetenanzahl = $rows310->RecordCount();
if ($planetenanzahl>=1) {
    $array310_out = $db->getArray($sql310);
    foreach ($array310_out as $array310) {        
        $pid=$array310["id"];
        $name=$array310["name"];
        $bild=$array310["bild"];
        $sternenbasis_id=$array310["sternenbasis_id"];
        $leichtebt=$array310["leichtebt"];
        $schwerebt=$array310["schwerebt"];
        $vorrat=$array310["vorrat"];
        $kolonisten=$array310["kolonisten"];
        $kolonisten=$kolonisten-mt_rand(50,200);
        if ($kolonisten<1) {
            $weg=0;
            $bodentruppen=$leichtebt+$schwerebt;
            if (($bodentruppen==0) or (($bodentruppen>=1) and ($vorrat==0))) { $weg=1; } else {
                $notwendig=round($bodentruppen*0.15);
                if ($notwendig<15) { $notwendig=15; }
                if ($notwendig>$vorrat) {
                    $zuwenig=$notwendig-$vorrat;
                    $vorrat=0;
                    $draufgehen=round($zuwenig/0.15);
                    $bodentruppen=$bodentruppen-$draufgehen;
                    if ($bodentruppen<1) { $weg=1; } else {
                        if ($draufgehen<=$schwerebt) {
                            $schwerebt=$schwerebt-$draufgehen;
                        } else {
                            $draufgehen=$draufgehen-$schwerebt;
                            $leichtebt=$leichtebt-$draufgehen;
                        }
                    }
                } else {
                    $vorrat=$vorrat-$notwendig;
                }
            }
            if ($weg==1) {
                $db->execute("UPDATE " . table_prefix . "planeten set leichtebt='0',schwerebt='0',kolonisten='0',besitzer='0',auto_minen='0',auto_fabriken='0',abwehr='0',auto_abwehr='0',auto_vorrat='0',vorrat='0',logbuch='' where id='".$pid."'");
                if ($sternenbasis_id>=1) {
                    $db->execute("UPDATE " . table_prefix . "sternenbasen set besitzer='0' where id='".$sternenbasis_id."'");
                }
            } else {
                $db->execute("UPDATE " . table_prefix . "planeten set vorrat='".$vorrat."',leichtebt='".$leichtebt."',schwerebt='".$schwerebt."' where id='".$pid."'");
            }
        } else {
            $db->execute("update " . table_prefix . "planeten set kolonisten='".$kolonisten."' where id='".$pid."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////KOLONISTEN SCHRUMPFEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////PLANETEN NEU BESETZEN ANFANG
$sql320 = "SELECT * FROM " . table_prefix . "planeten use index (besitzer,spiel) where besitzer='0' and (kolonisten_new>0 or leichtebt_new>0 or schwerebt>0) and spiel='".$spiel."' order by id";
$rows320 = $db->execute($sql320);
$planetenanzahl = $rows320->RecordCount();
if ($planetenanzahl>=1) {
    $array320_out = $db->getArray($sql320);
    foreach ($array320_out as $array320) {        
        $pid=$array320["id"];
        $name=$array320["name"];
        $bild=$array320["bild"];
        $klasse=$array320["klasse"];
        $kolonisten=$array320["kolonisten"];
        $kolonisten_new=$array320["kolonisten_new"];
        $kolonisten_spieler=$array320["kolonisten_spieler"];
        $leichtebt_new=$array320["leichtebt_new"];
        $schwerebt_new=$array320["schwerebt_new"];
        $sternenbasis_id=$array320["sternenbasis_id"];
        $db->execute("update " . table_prefix . "planeten set leichtebt='".$leichtebt_new."', 
                                                              schwerebt='".$schwerebt_new."', 
                                                              leichtebt_new='0', 
                                                              schwerebt_new='0', 
                                                              besitzer='".$kolonisten_spieler."', 
                                                              kolonisten='".$kolonisten_new."', 
                                                              kolonisten_new='0',
                                                              kolonisten_spieler='0'
                                                       where id='".$pid."'");
        if ($sternenbasis_id>=1) { 
            $db->execute("UPDATE " . table_prefix . "sternenbasen set besitzer='".$kolonisten_spieler."' where id='".$sternenbasis_id."'");
        }
        if ($kolonisten_new>0) {
            $neuekolonie++;
            neuigkeiten(1, servername . "images/planeten/$klasse"."_"."$bild.jpg",$kolonisten_spieler,$lang['host']['besetzen0'],array($name));
        } else {
            neuigkeiten(1, servername . "images/planeten/$klasse"."_"."$bild.jpg",$kolonisten_spieler,$lang['host']['besetzen1'],array($name));
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////PLANETEN NEU BESETZEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////GROSSER METEORITEN ANFANG
$meteor=mt_rand(1,200);
if ($meteor==1) {
    $sql330 = "SELECT id,spiel,besitzer,name,x_pos,y_pos,sternenbasis FROM " . table_prefix . "planeten where spiel='".$spiel."' and sternenbasis='0' order by rand() limit 0,1";
    $array330 = $db->getRow($sql330);
    $pid=$array330["id"];
    $name=$array330["name"];
    $besitzer=$array330["besitzer"];
    $x_pos=$array330["x_pos"];
    $y_pos=$array330["y_pos"];
    $rohstoff_met=mt_rand(7500,10000);
    $rohstoffe[0]=mt_rand(0,$rohstoff_met);
    $rohstoffe[1]=mt_rand(0,($rohstoff_met-$rohstoffe[0]));
    $rohstoffe[2]=mt_rand(0,($rohstoff_met-$rohstoffe[0]-$rohstoffe[1]));
    $rohstoffe[3]=($rohstoff_met-$rohstoffe[0]-$rohstoffe[1]-$rohstoffe[2]);
    shuffle($rohstoffe);
    $lemin=$rohstoffe[0];
    $min1=$rohstoffe[1];
    $min2=$rohstoffe[2];
    $min3=$rohstoffe[3];
    if ($besitzer>=1) {
        $db->execute("UPDATE " . table_prefix . "planeten set planet_lemin=planet_lemin+".$lemin.", 
                                                              planet_min1=planet_min1+".$min1.", 
                                                              planet_min2=planet_min2+".$min2.", 
                                                              planet_min3=planet_min3+".$min3.", 
                                                              native_id='0', 
                                                              native_name ='', 
                                                              native_art='0', 
                                                              native_art_name='', 
                                                              native_abgabe='0', 
                                                              native_bild='', 
                                                              native_text='', 
                                                              native_fert='', 
                                                              native_kol='0', 
                                                              kolonisten='0', 
                                                              besitzer='0', 
                                                              minen='0', 
                                                              vorrat='0', 
                                                              cantox='0', 
                                                              auto_minen='0', 
                                                              fabriken='0', 
                                                              auto_fabriken='0', 
                                                              abwehr='0', 
                                                              auto_abwehr='0', 
                                                              auto_vorrat='0', 
                                                              logbuch='' 
                                                 where id='".$pid."'");
        for ($k=1;$k<11;$k++) {
            if (($spieler_id_c[$k]>=1) and ($spieler_raus_c[$k]!=1) and ($besitzer!=$k)) { neuigkeiten(4,servername . "images/news/meteor_gross.jpg",$k,$lang['host']['meteoriten0'],array($name,$x_pos,$y_pos,$rohstoffe[0],$rohstoffe[2],$rohstoffe[1],$rohstoffe[3])); }
        }
        neuigkeiten(4, servername . "images/news/meteor_gross.jpg",$besitzer,$lang['host']['meteoriten1'],array($name,$x_pos,$y_pos,$rohstoffe[0],$rohstoffe[2],$rohstoffe[1],$rohstoffe[3]));
    } else {
        $db->execute("UPDATE " . table_prefix . "planeten set planet_lemin=planet_lemin+".$lemin.", 
                                                              planet_min1=planet_min1+".$min1.", 
                                                              planet_min2=planet_min2+".$min2.", 
                                                              planet_min3=planet_min3+".$min3.", 
                                                              native_id='0', 
                                                              native_name ='', 
                                                              native_art='0', 
                                                              native_art_name='', 
                                                              native_abgabe='0', 
                                                              native_bild='', 
                                                              native_text='', 
                                                              native_fert='', 
                                                              native_kol='0' 
                                                  where id='".$pid."'");
        for ($k=1;$k<11;$k++) {
            if (($spieler_id_c[$k]>=1) and ($spieler_raus_c[$k]!=1)) { neuigkeiten(4,servername . "images/news/meteor_gross.jpg",$k,$lang['host']['meteoriten0'],array($name,$x_pos,$y_pos,$rohstoffe[0],$rohstoffe[2],$rohstoffe[1],$rohstoffe[3])); }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////GROSSER METEORITEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////KLEINE METEORITEN ANFANG
$meteore=mt_rand(0,15);
for ($i=0; $i<$meteore;$i++) {
    $sql340 = "SELECT id,spiel,besitzer,name,lemin,min1,min2,min3 FROM " . table_prefix . "planeten use index (spiel) where spiel='".$spiel."' order by rand() limit 0,1";
    $array = $db->getRow($sql340);
    $pid=$array["id"];
    $name=$array["name"];
    $besitzer=$array["besitzer"];
    $lemin=$array["lemin"];
    $min1=$array["min1"];
    $min2=$array["min2"];
    $min3=$array["min3"];
    $rohstoff_met=mt_rand(50,200);
    $rohstoffe[0]=mt_rand(0,$rohstoff_met);
    $rohstoffe[1]=mt_rand(0,($rohstoff_met-$rohstoffe[0]));
    $rohstoffe[2]=mt_rand(0,($rohstoff_met-$rohstoffe[0]-$rohstoffe[1]));
    $rohstoffe[3]=($rohstoff_met-$rohstoffe[0]-$rohstoffe[1]-$rohstoffe[2]);
    shuffle($rohstoffe);
    $lemin=$lemin+$rohstoffe[0];
    $min1=$min1+$rohstoffe[1];
    $min2=$min2+$rohstoffe[2];
    $min3=$min3+$rohstoffe[3];
    $db->execute("UPDATE " . table_prefix . "planeten set lemin='".$lemin."',min1='".$min1."',min2='".$min2."',min3='".$min3."' where id='".$pid."'");
    if ($besitzer>=1) {
        neuigkeiten(2, servername . "images/news/meteor_klein.jpg",$besitzer,$lang['host']['meteoriten2'],array($name,$rohstoffe[0],$rohstoffe[2],$rohstoffe[1],$rohstoffe[3]));
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////KLEINE METEORITEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////PIRATEN ANFANG
if (($piraten_mitte>=1) or ($piraten_aussen>=1)) {
    $sql350 = "SELECT zusatzmodul,spiel,id,erfahrung,energetik_anzahl,projektile_anzahl,hanger_anzahl,kox,koy,besitzer,status,name,fracht_cantox,fracht_vorrat,fracht_min1,fracht_min2,fracht_min3,techlevel FROM " . table_prefix . "schiffe use index (status, spiel) where status<>2 and techlevel>3 and energetik_anzahl='0' and projektile_anzahl='0' and hanger_anzahl='0' and spiel='".$spiel."' order by id";
    $rows350 = $db->execute($sql350);
    $schiffanzahl = $rows350->RecordCount();
    if ($schiffanzahl>=1) {
        $array350_out = $db->getArray($sql350);
        foreach ($array350_out as $array350) {            
            $shid=$array350["id"];
            $kox=$array350["kox"];
            $koy=$array350["koy"];
            $fracht_min1=$array350["fracht_min1"];
            $fracht_min2=$array350["fracht_min2"];
            $fracht_min3=$array350["fracht_min3"];
            $fracht_cantox=$array350["fracht_cantox"];
            $fracht_vorrat=$array350["fracht_vorrat"];
            $besitzer=$array350["besitzer"];
            $name=$array350["name"];
            $erfahrung=$array350["erfahrung"];
            $zusatzmodul=$array350["zusatzmodul"];
            if (($fracht_min1>=1) or ($fracht_min2>=1) or ($fracht_min3>=1) or ($fracht_cantox>=1) or ($fracht_vorrat>=1)) {
                $abstand=(sqrt(((($umfang/2)-$kox)*(($umfang/2)-$kox))+((($umfang/2)-$koy)*(($umfang/2)-$koy))));
                $wahrscheinlichkeit=0;
                if ($piraten_aussen>$piraten_mitte) {
                    $prozent_abstand=100-($abstand*100/($umfang/2));
                    $differenz=$piraten_aussen-$piraten_mitte;
                    $ein_prozent=$differenz/100;
                    $wahrscheinlichkeit=round(($prozent_abstand*$ein_prozent)+$piraten_mitte);
                }
                if ($piraten_aussen<$piraten_mitte) {
                    $prozent_abstand=$abstand*100/($umfang/2);
                    $differenz=$piraten_mitte-$piraten_aussen;
                    $ein_prozent=$differenz/100;
                    $wahrscheinlichkeit=round(($prozent_abstand*$ein_prozent)+$piraten_aussen);
                }
                if ($piraten_aussen==$piraten_mitte) { $wahrscheinlichkeit=$piraten_aussen; }
                $wahrscheinlichkeit=$wahrscheinlichkeit-($erfahrung*5);
                $tech_stark=0;
                $sql360 = "SELECT techlevel,spiel,id,energetik_anzahl,projektile_anzahl,hanger_anzahl,kox,koy,besitzer,flug,zielid FROM " . table_prefix . "schiffe use index (flug,zielid,kox,kloy,spiel) where flug='4' and zielid='".$shid."' and kox='".$kox."' and koy='".$koy."' and (energetik_anzahl>=1 or projektile_anzahl>=1 or hanger_anzahl>=1) and spiel='".$spiel."' order by id";
                $rows360 = $db->execute($sql360);
                $schiffanzahl2 = $rows360->RecordCount();
                if ($schiffanzahl2>=1) {
                    $arra360_out  = $db->getArray($sql360);
                    foreach ($array360_out as $array360) {
                        $techlevel=$array2["techlevel"];
                        if ($techlevel>$tech_stark) {
                            $tech_stark=$techlevel;                            
                        }
                    }
                }
                if ($tech_stark>=1) {$wahrscheinlichkeit=$wahrscheinlichkeit-($tech_stark*$tech_stark);}
                $zufall=mt_rand(1,100);
                if ($zufall<=$wahrscheinlichkeit) {
                    $prozent_ganz=mt_rand($piraten_min,$piraten_max);
                    if ($erfahrung>=1) {
                        $prozent_ganz=$prozent_ganz-($erfahrung*5);
                    }
                    if ($zusatzmodul==8) { $prozent_ganz=round($prozent_ganz*0.27); }
                    if ($prozent_ganz>=1) {
                        $prozent_ganz=$prozent_ganz*5;
                        $prozente[0]=mt_rand(0,$prozent_ganz);
                        $prozente[1]=mt_rand(0,($prozent_ganz-$prozente[0]));
                        $prozente[2]=mt_rand(0,($prozent_ganz-$prozente[0]-$prozente[1]));
                        $prozente[3]=mt_rand(0,($prozent_ganz-$prozente[0]-$prozente[1]-$prozente[2]));
                        $prozente[4]=($prozent_ganz-$prozente[0]-$prozente[1]-$prozente[2]-$prozente[3]);
                        if ($prozente[0]>round($prozent_ganz/5)) { $prozente[0]=round($prozent_ganz/5); }
                        if ($prozente[1]>round($prozent_ganz/5)) { $prozente[1]=round($prozent_ganz/5); }
                        if ($prozente[2]>round($prozent_ganz/5)) { $prozente[2]=round($prozent_ganz/5); }
                        if ($prozente[3]>round($prozent_ganz/5)) { $prozente[3]=round($prozent_ganz/5); }
                        if ($prozente[4]>round($prozent_ganz/5)) { $prozente[4]=round($prozent_ganz/5); }
                        shuffle($prozente);
                        $fracht_min1_weg=ceil($prozente[0]*$fracht_min1/100);
                        $fracht_min2_weg=ceil($prozente[1]*$fracht_min2/100);
                        $fracht_min3_weg=ceil($prozente[2]*$fracht_min3/100);
                        $fracht_cantox_weg=ceil($prozente[3]*$fracht_cantox/100);
                        $fracht_vorrat_weg=ceil($prozente[4]*$fracht_vorrat/100);
                        if (($fracht_min1_weg>=1) or ($fracht_min2_weg>=1) or ($fracht_min3_weg>=1) or ($fracht_cantox_weg>=1) or ($fracht_vorrat_weg>=1)) {
                            $db->execute("UPDATE " . table_prefix . "schiffe set fracht_min1=fracht_min1-".$fracht_min1_weg.", 
                                                                                 fracht_min2=fracht_min2-".$fracht_min2_weg.", 
                                                                                 fracht_min3=fracht_min3-".$fracht_min3_weg.", 
                                                                                 fracht_cantox=fracht_cantox-".$fracht_cantox_weg.", 
                                                                                 fracht_vorrat=fracht_vorrat-".$fracht_vorrat_weg." 
                                                                    where id='".$shid."'");
                            neuigkeiten(1,servername . "images/news/piraten.jpg",$besitzer,$lang['host']['piraten0'],array($name,$fracht_cantox_weg,$fracht_vorrat_weg,$fracht_min1_weg,$fracht_min2_weg,$fracht_min3_weg));
                        }
                    } else {
                        neuigkeiten(1, servername . "images/news/piraten.jpg",$besitzer,$lang['host']['piraten1'],array($name));
                    }
                } else {
                    if (($zufall<=$wahrscheinlichkeit+($tech_stark*$tech_stark)) and ($tech_stark>=1)) {
                        neuigkeiten(1, servername. "images/news/piraten.jpg",$besitzer,$lang['host']['piraten1'],array($name));
                    }
                }
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////PIRATEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////HANDELSABKOMMEN ANFANG
$bonus=18;
for ($zaehler=1;$zaehler<=10;$zaehler++) {
    if ($spieler_id_c[$zaehler]>=1) {
        $sql370 = "SELECT count(*) as total FROM " . table_prefix . "planeten where besitzer='".$zaehler."' and spiel='".$spiel."'";        
        $spieler_planetenanzahl[$zaehler]=$db->getOne($sql370);
        $spieler_handelbonus[$zaehler]=100;
    }
}
for ($zaehler=1;$zaehler<=10;$zaehler++) {
    if ($spieler_id_c[$zaehler]>=1) {
        for ($zaehler2=1;$zaehler2<=10;$zaehler2++) {
            if (($spieler_id_c[$zaehler2]>=1) and ($zaehler!=$zaehler2)) {
                if (($beziehung[$zaehler][$zaehler2]['status']==2) && ($spieler_planetenanzahl[$zaehler]>0)) {
                    $spieler_handelbonus[$zaehler]=$spieler_handelbonus[$zaehler]+($spieler_planetenanzahl[$zaehler2]/$spieler_planetenanzahl[$zaehler]*$bonus);
                }
            }
        }
    $spieler_handelbonus[$zaehler]=(round($spieler_handelbonus[$zaehler]))/100;
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////HANDELSABKOMMEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////PLANETEN START
$sql380 = "SELECT * FROM " . table_prefix . "planeten where besitzer>=1 and spiel='".$spiel."' order by id";
$rows380 = $db->execute($sql380);
$planetenanzahl = $rows380->RecordCount();
if ($planetenanzahl>=1) {
    $array380_out = $rows380->getArray();
    foreach ($array380_out as $array380) {        
        $pid=$array380["id"];
        $name=$array380["name"];
        $x_pos=$array380["x_pos"];
        $y_pos=$array380["y_pos"];
        $bild=$array380["bild"];
        $temp=$array380["temp"];
        $klasse=$array380["klasse"];
        $minen=$array380["minen"];
        $cantox=$array380["cantox"];
        $vorrat=$array380["vorrat"];
        $fabriken=$array380["fabriken"];
        $abwehr=$array380["abwehr"];
        $besitzer=$array380["besitzer"];
        $leichtebt=$array380["leichtebt"];
        $schwerebt=$array380["schwerebt"];
        $leichtebt_bau=$array380["leichtebt_bau"];
        $schwerebt_bau=$array380["schwerebt_bau"];
        $auto_minen=$array380["auto_minen"];
        $auto_fabriken=$array380["auto_fabriken"];
        $auto_vorrat=$array380["auto_vorrat"];
        $auto_abwehr=$array380["auto_abwehr"];
        $kolonisten=$array380["kolonisten"];
        $lemin=$array380["lemin"];
        $min1=$array380["min1"];
        $min2=$array380["min2"];
        $min3=$array380["min3"];
        $artefakt=$array380["artefakt"];
        $planet_lemin=$array380["planet_lemin"];
        $planet_min1=$array380["planet_min1"];
        $planet_min2=$array380["planet_min2"];
        $planet_min3=$array380["planet_min3"];
        $konz_lemin=$array380["konz_lemin"];
        $konz_min1=$array380["konz_min1"];
        $konz_min2=$array380["konz_min2"];
        $konz_min3=$array380["konz_min3"];
        $native_id=$array380["native_id"];
        $native_abgabe=$array380["native_abgabe"];
        $native_fert=$array380["native_fert"];
        $native_kol=$array380["native_kol"];
        $native_art=$array380["native_art"];
        $osys_anzahl=$array380["osys_anzahl"];
        $osys_1=$array380["osys_1"];
        $osys_2=$array380["osys_2"];
        $osys_3=$array380["osys_3"];
        $osys_4=$array380["osys_4"];
        $osys_5=$array380["osys_5"];
        $osys_6=$array380["osys_6"];
        $osys = array();
        $osys[1]=$array380["osys_1"];
        $osys[2]=$array380["osys_2"];
        $osys[3]=$array380["osys_3"];
        $osys[4]=$array380["osys_4"];
        $osys[5]=$array380["osys_5"];
        $osys[6]=$array380["osys_6"];
        $native_fert_minen=intval(substr($native_fert,0,3))/100;
        $native_fert_fabriken=intval(substr($native_fert,3,3))/100;
        $native_fert_wachstum=intval(substr($native_fert,23,3))/100;
        $native_fert_prod_vorrat=intval(substr($native_fert,16,1));
        $native_fert_prod_lemin=intval(substr($native_fert,17,1));
        $native_fert_attacke=intval(substr($native_fert,18,3))/100;
        $native_fert_intens=intval(substr($native_fert,26,1));
        $native_fert_klau=intval(substr($native_fert,27,1));
        $native_fert_angriffswarnung=intval(substr($native_fert,29,1));
        if ($native_fert_intens==1) {
            $konz_lemin=5;
            $konz_min1=5;
            $konz_min2=5;
            $konz_min3=5;
        }
        if ($native_fert_intens==2) {
            $konz_lemin=1;
            $konz_min1=1;
            $konz_min2=1;
            $konz_min3=1;
        }
        if (($native_id>=1) and ($native_kol>1) and ($native_fert_prod_vorrat>0)) {
            $vorrat=$vorrat+round($native_kol/10000*$native_fert_prod_vorrat);
            $db->execute("UPDATE " . table_prefix . "planeten set vorrat='".$vorrat."' where id='".$pid."'");                
        }
        if (($native_id>=1) and ($native_kol>1) and ($native_fert_prod_lemin>0)) {
            $lemin=$lemin+round($native_kol/10000*$native_fert_prod_lemin);
            $db->execute("UPDATE " . table_prefix . "planeten set lemin='".$lemin."' where id='".$pid."'");
        }
        $zufall=mt_rand(1,100);
        if ($zufall<=18) {
            if ($artefakt==1) {
                $db->execute("UPDATE " . table_prefix . "planeten set artefakt='0' where id='".$pid."'");
                $zufall2=mt_rand(500,1500);
                $planet_lemin=$planet_lemin+$zufall2;
                $db->execute("UPDATE " . table_prefix . "planeten set planet_lemin='".$planet_lemin."' where id='".$pid."'");
                neuigkeiten(1,servername. "images/news/vorkommen_lemin.jpg",$besitzer,$lang['host']['mineralader'],array($name,'Lemin'));
            }
            if ($artefakt==2) {
                $db->execute("UPDATE " . table_prefix . "planeten set artefakt='0' where id='".$pid."'");
                $zufall2=mt_rand(500,1500);
                $planet_min1=$planet_min1+$zufall2;
                $db->execute("UPDATE " . table_prefix . "planeten set planet_min1='".$planet_min1."' where id='".$pid."'");
                neuigkeiten(1,servername. "images/news/vorkommen_min1.jpg",$besitzer,$lang['host']['mineralader'],array($name,'Baxterium'));
            }
            if ($artefakt==3) {
                $db->execute("UPDATE " . table_prefix . "planeten set artefakt='0' where id='".$pid."'");
                $zufall2=mt_rand(500,1500);
                $planet_min2=$planet_min2+$zufall2;
                $db->execute("UPDATE " . table_prefix . "planeten set planet_min2='".$planet_min2."' where id='".$pid."'");
                neuigkeiten(1,servername. "images/news/vorkommen_min2.jpg",$besitzer,$lang['host']['mineralader'],array($name,'Rennurbin'));
            }
            if ($artefakt==4) {
                $db->execute("UPDATE " . table_prefix . "planeten set artefakt=0 where id=$pid");
                $zufall2=mt_rand(500,1500);
                $planet_min3=$planet_min3+$zufall2;
                $db->execute("UPDATE " . table_prefix . "planeten set planet_min3='".$planet_min3."' where id='".$pid."'");
                neuigkeiten(1,servername. "images/news/vorkommen_min3.jpg",$besitzer,$lang['host']['mineralader'],array($name,'Vomisaan'));
            }
        }
        if ($zufall<=33) {
            if (($artefakt==6) and ($osys_anzahl<6)) {
                $db->execute("UPDATE " . table_prefix . "planeten set artefakt='0' where id='".$pid."'");
                $osys_anzahl++;
                $osys[$osys_anzahl]=14;
                if ($osys_anzahl==1) { $osys_1==14;$spalte='osys_1'; }
                if ($osys_anzahl==2) { $osys_2==14;$spalte='osys_2'; }
                if ($osys_anzahl==3) { $osys_3==14;$spalte='osys_3'; }
                if ($osys_anzahl==4) { $osys_4==14;$spalte='osys_4'; }
                if ($osys_anzahl==5) { $osys_5==14;$spalte='osys_5'; }
                if ($osys_anzahl==6) { $osys_6==14;$spalte='osys_6'; }
                $db->execute("UPDATE " . table_prefix . "planeten set ".$spalte."='14',osys_anzahl='".$osys_anzahl."' where id='".$pid."'");
                neuigkeiten(1,servername. "images/news/wetterstation.jpg",$besitzer,$lang['host']['wetterstation'],array($name));
            }
        }
        if ($zufall<=50) {
            if ($artefakt==5) {
                $db->execute("UPDATE " . table_prefix . "planeten set artefakt='0' where id='".$pid."'");
                $zufall2=mt_rand(2000,10000);
                $kolonisten=$kolonisten+$zufall2;
                $db->execute("UPDATE " . table_prefix . "planeten set kolonisten=$kolonisten where id=$pid");
                neuigkeiten(1,servername. "images/news/splitterkolonie.jpg",$besitzer,$lang['host']['kolonisten'],array($name,$zufall2));
            }
        }
        if ($vorrat<0) {$vorrat=0;}
        if ($fabriken<0) {$fabriken=0;}
        if ($minen<0) {$minen=0;}
        if ($cantox<0) {$cantox=0;}
        $rasse = $s_eigenschaften[$besitzer]['rasse'];
        // TEMPERATURANPASSUNG DURCH WETTERSTATION
        if (in_array (14, $osys)) {
            if ($temp!=$r_eigenschaften[$rasse]['temperatur']) {
                    if ($r_eigenschaften[$rasse]['temperatur']==0) {
                        if ($klasse==1) { $temp=mt_rand(40,60);
                        }elseif ($klasse==2) { $temp=mt_rand(30,50);
                        }elseif ($klasse==3) { $temp=mt_rand(0,10);
                        }elseif ($klasse==4) { $temp=mt_rand(50,75);
                        }elseif ($klasse==5) { $temp=mt_rand(86,100);
                        }elseif ($klasse==6) { $temp=mt_rand(70,95);
                        }elseif ($klasse==7) { $temp=mt_rand(75,90);
                        }elseif ($klasse==8) { $temp=mt_rand(20,35);
                        }elseif ($klasse==9) { $temp=mt_rand(25,45);}
                    } else {
                        $temp=$r_eigenschaften[$rasse]['temperatur'];
                    }
                $db->execute("UPDATE " . table_prefix . "planeten set temp='".$temp."' where id='".$pid."'");
            }
        }
        //BAUEN VON BODENTRUPPEN ANFANG
        if (($leichtebt_bau>=1) or ($schwerebt_bau>=1)) {
            $leichtebt=$leichtebt+$leichtebt_bau;
            $schwerebt=$schwerebt+$schwerebt_bau;
            $db->execute("UPDATE " . table_prefix . "planeten set leichtebt='".$leichtebt."',leichtebt_bau='0',schwerebt='".$schwerebt."',schwerebt_bau='0' where id='".$pid."'");
        }
        //BAUEN VON BODENTRUPPEN ENDE
        //SCHIFFE IM ORBIT TARNEN ANFANG
        if (($osys_1==5) or ($osys_2==5) or ($osys_3==5) or ($osys_4==5) or ($osys_5==5) or ($osys_6==5)) {
            $db->execute("UPDATE " . table_prefix . "schiffe set tarnfeld='1' where status='2' and kox='".$x_pos."' and koy='".$y_pos."' and spiel=$spiel");
        }
        //SCHIFFE IM ORBIT TARNEN ENDE
        //NATIVE ANFANG KAMPF
        if (($native_id>=1) and ($native_kol>1) and ($native_fert_attacke>0)) {
            $native_leute=mt_rand(50,5000);
            if ($native_leute>$native_kol) {
                $native_leute=$native_kol;
            }
            $besitzer_stark=$r_eigenschaften[$rasse]['bodenverteidigung'];
            $verteidiger=($kolonisten+($leichtebt*16)+($schwerebt*60))*$besitzer_stark;
            $angreifer=$native_leute*$native_fert_attacke;
            if ($verteidiger>=$angreifer) {
                $kolonisten=round($kolonisten-($angreifer*$kolonisten/$verteidiger));
                $leichtebt=round($leichtebt-($angreifer*$leichtebt/$verteidiger));
                $schwerebt=round($schwerebt-($angreifer*$schwerebt/$verteidiger));
                $native_kol=$native_kol-$native_leute;
            }
            if ($angreifer>$verteidiger) {
                $native_kol=$native_kol-round($verteidiger/$native_fert_attacke);
                $kolonisten=0;
                $leichtebt=0;
                $schwerebt=0;
            }
            $db->execute("UPDATE " . table_prefix . "planeten set native_kol='".$native_kol."',kolonisten='".$kolonisten."',leichtebt='".$leichtebt."',schwerebt='".$schwerebt."' where id=$pid");
        }
        //NATIVE ENDE KAMPF
        $Bankbonus=1;
        if($osys_1==3 or $osys_2==3 or $osys_3==3 or $osys_4==3 or $osys_5==3 or $osys_6==3){$Bankbonus=1.075;}
        $reservat=0;
        if($osys_1==23 or $osys_2==23 or $osys_3==23 or $osys_4==23 or $osys_5==23 or $osys_6==23){$reservat=10000;}
        //NATIVE ANGRIFFSWARNUNG ANFANG
        if (($native_id>=1) and ($native_kol>1) and ($native_fert_angriffswarnung==1)) {
            $anzahl_angreifer=0;
            $sql390 = "SELECT besitzer FROM " . table_prefix . "schiffe use index (flug,zielid,besitzer) where flug='2' and zielid='".$pid."' and besitzer!='".$besitzer."'";
            $rows390 = $db->execute($sql390);
            $anzahl_temp = $rows390->RecordCount();
            if ($anzahl_temp>0) {
                for ($zaehler=0; $zaehler<$anzahl_temp; $zaehler++) {                                        
                    $s_besitzer = $db->getOne($sql390);
                    if ($beziehung[$besitzer][$s_besitzer]['status']<2) {
                        $anzahl_angreifer++;
                    }
                }
            }
            if ($anzahl_angreifer>0) {
                neuigkeiten(1,servername. "images/planeten/$klasse"."_"."$bild.jpg",$besitzer,$lang['host']['angriffswarnung'],array($name));
            }
        }
        //NATIVE ANGRIFFSWARNUNG ENDE
        //NATIVE ANFANG
        if (($native_id>=1) and ($native_kol<1000000)) {
            $schwank=mt_rand(1,2);
            if($reservat and($native_kol>$reservat)){$schwank=1;}
            if ($schwank==1) {
                $native_kol=round($native_kol-($native_kol*0.01745));
                if ($native_kol<0) {$native_kol=0;}
            } else {
                $native_kol=round(($native_kol*0.01745)+$native_kol);
            }
            $db->execute("UPDATE " . table_prefix . "planeten set native_kol='".$native_kol."' where id='".$pid."'");
        }
        if (($native_id>=1) and ($native_kol>1)) {
            $cantox=$cantox+round($native_kol*0.008*$native_abgabe*$Bankbonus);
        }
        //NATIVE ENDE
        //NATIVE ASSIMILIEREN ANFANG
        if (($native_id>=1) and ($native_kol>1) and ($r_eigenschaften[$rasse]['assgrad']>=1) and ($kolonisten>1)) {
            if (($r_eigenschaften[$rasse]['assart']==$native_art) or ($r_eigenschaften[$rasse]['assart']==0)) {
                $ueberlauf=round($kolonisten/100*$r_eigenschaften[$rasse]['assgrad']);
                if ($ueberlauf>=1 and $native_kol>$reservat) {
                    $ueberlauf=min($ueberlauf,$native_kol-$reservat);
                    $kolonisten=$kolonisten+$ueberlauf;
                    $native_kol=$native_kol-$ueberlauf;
                    if ($native_kol==0) {$native_id=0;}
                    $db->execute("UPDATE " . table_prefix . "planeten set kolonisten='".$kolonisten."',native_id='".$native_id."',native_kol='".$native_kol."' where id='".$pid."'");
                }
            }
        }
        //NATIVE ASSIMILIEREN ENDE
        //KOLONISTEN ANFANG
        if (($kolonisten>=1000) and ($kolonisten<10000000)) {
            $temp_unterschied=$temp-$r_eigenschaften[$rasse]['temperatur'];
            if ($temp_unterschied<0) { $temp_unterschied=$temp_unterschied*(-1); }
            if ($r_eigenschaften[$rasse]['temperatur']==0) { $temp_unterschied=0; }
            if ($temp_unterschied<=30) {
                $wachstum=(0.1745-($temp_unterschied*0.004886666666666));
                if ($r_eigenschaften[$rasse]['pklasse']==$klasse) { $wachstum=$wachstum*1.20;}
                if($native_id>0 && $native_kol>1 && $native_fert_wachstum>0) { $wachstum *= $native_fert_wachstum; }
                if($osys_1==6 or $osys_2==6 or $osys_3==6 or $osys_4==6 or $osys_5==6 or $osys_6==6){$wachstum=0.1+$wachstum;}
                $kolonisten=round(($kolonisten/10*$wachstum)+$kolonisten);
                $db->execute("UPDATE " . table_prefix . "planeten set kolonisten='".$kolonisten."' where id='".$pid."'");
            }
        }
        $cantox=$cantox+round($kolonisten*0.008*$r_eigenschaften[$rasse]['steuern']*$spieler_handelbonus[$besitzer]*$Bankbonus);
        $db->execute("update " . table_prefix . "planeten set cantox=$cantox where id=$pid");
        //KOLONISTEN ENDE
        //FABRIKEN BAUEN VORRAT ANFANG
        $fabriken_fert_temp=$fabriken;
        if (($native_id>=1) and ($native_kol>1) and ($native_fert_fabriken>0)) {
            $fabriken_fert_temp=round(($fabriken*$native_fert_fabriken));
        }
        if (($osys_1==1) or ($osys_2==1) or ($osys_3==1) or ($osys_4==1) or ($osys_5==1) or ($osys_6==1)) { $fabriken_fert_temp=round($fabriken_fert_temp*1.15); }
        $vorrat_neu=round($fabriken_fert_temp*$r_eigenschaften[$rasse]['fabriken']);
        $vorrat_klau=0;
        if ($native_fert_klau==5) {
            $vorrat_klau=round($vorrat_neu/100*mt_rand(30,80));
        }
        $vorrat=$vorrat+$vorrat_neu-$vorrat_klau;
        $db->execute("update " . table_prefix . "planeten set vorrat='".$vorrat."' where id='".$pid."'");
        //FABRIKEN BAUEN VORRAT ENDE
        //MINEN PRODUZIEREN ANFANG
        if ($minen>0) {
            $mineralgesamt=$planet_lemin+$planet_min1+$planet_min2+$planet_min3;
            $minen_fert_temp=$minen;
            if (($native_id>=1) and ($native_kol>1) and ($native_fert_minen>0)) {
                $minen_fert_temp=round(($minen*$native_fert_minen)+0.5);
            }
            if (($osys_1==2) or ($osys_2==2) or ($osys_3==2) or ($osys_4==2) or ($osys_5==2) or ($osys_6==2)) { $minen_fert_temp=round($minen_fert_temp*1.09); }
            if ($mineralgesamt>=1) {
                //Feld gibt an wieviel Minen je Kt Mineral benoetigt werden.
                $minen_je_kt_mineral=array(10,6,4,2,1);
                //Lemin Anfang
                $minen_lemin=$planet_lemin*$minen_fert_temp*$r_eigenschaften[$rasse]['minen']/$mineralgesamt;
                $lemin_neu=min($planet_lemin,floor($minen_lemin/max($minen_je_kt_mineral[$konz_lemin-1],1)));
                $mineral_klau=0;
                if ($native_fert_klau==1) {
                    $mineral_klau=round($lemin_neu/100*mt_rand(30,80));
                }
                $lemin=$lemin+$lemin_neu-$mineral_klau;
                $planet_lemin=$planet_lemin-$lemin_neu;
                //Lemin Ende
                //Baxterium Anfang
                $minen_min1=$planet_min1*$minen_fert_temp*$r_eigenschaften[$rasse]['minen']/$mineralgesamt;
                $min1_neu=min($planet_min1,floor($minen_min1/max($minen_je_kt_mineral[$konz_min1-1],1)));
                $mineral_klau=0;
                if ($native_fert_klau==2) {
                    $mineral_klau=round($min1_neu/100*mt_rand(30,80));
                }
                $min1=$min1+$min1_neu-$mineral_klau;
                $planet_min1=$planet_min1-$min1_neu;
                //Baxterium Ende
                //Rennurbin Anfang
                $minen_min2=$planet_min2*$minen_fert_temp*$r_eigenschaften[$rasse]['minen']/$mineralgesamt;
                $min2_neu=min($planet_min2,floor($minen_min2/max($minen_je_kt_mineral[$konz_min2-1],1)));
                $mineral_klau=0;
                if ($native_fert_klau==3) {
                    $mineral_klau=round($min2_neu/100*mt_rand(30,80));
                }
                $min2=$min2+$min2_neu-$mineral_klau;
                $planet_min2=$planet_min2-$min2_neu;
                //Rennurbin Ende
                //Vormissan Anfang
                $minen_min3=$planet_min3*$minen_fert_temp*$r_eigenschaften[$rasse]['minen']/$mineralgesamt;
                $min3_neu=min($planet_min3,floor($minen_min3/max($minen_je_kt_mineral[$konz_min3-1],1)));
                $mineral_klau=0;
                if ($native_fert_klau==4) {
                    $mineral_klau=round($min3_neu/100*mt_rand(30,80));
                }
                $min3=$min3+$min3_neu-$mineral_klau;
                $planet_min3=$planet_min3-$min3_neu;
                //Vormissan Ende
                $db->execute("UPDATE " . table_prefix . "planeten set lemin='".$lemin."', 
                                                                      min1='".$min1."', 
                                                                      min2='".$min2."', 
                                                                      min3='".$min3."', 
                                                                      planet_lemin='".$planet_lemin."', 
                                                                      planet_min1='".$planet_min1."', 
                                                                      planet_min2='".$planet_min2."', 
                                                                      planet_min3='".$planet_min3."' 
                                                        where id='".$pid."'");
            }
        }
        //MINEN PRODUZIEREN ENDE
        $metro_fabriken_plus=0;
        $metro_minen_plus=0;
        if(($osys_1==9) or ($osys_2==9) or ($osys_3==9) or ($osys_4==9) or ($osys_5==9) or ($osys_6==9)){
            $metro_fabriken_plus=12;
            $metro_minen_plus=24;
        }
        //AUTOMATISCHES FABRIKENBAUEN ANFANG
        if ($auto_fabriken==1) {
            $max_cantox=floor($cantox/3);
            $max_vorrat=$vorrat;
            if ($max_cantox<=$max_vorrat) { $max_bau=$max_cantox; }
            if ($max_vorrat<=$max_cantox) { $max_bau=$max_vorrat; }
            if (($kolonisten/100)<=100) { 
                $max_col=floor($kolonisten/100)+$metro_fabriken_plus;                 
            } else { 
                $max_col=100+floor(sqrt($kolonisten/100))+$metro_fabriken_plus;                 
            }
            $max_fabriken=$fabriken+$max_bau;
            if ($max_fabriken>$max_col) {
                $max_fabriken = $max_col;
                $max_bau = max(0, $max_col-$fabriken);
            }
            if ($max_fabriken>200+$metro_fabriken_plus) {
                $max_fabriken=200+$metro_fabriken_plus;
                $max_bau=200-$fabriken+$metro_fabriken_plus;
            }
            $fabriken=$fabriken+$max_bau;
            $cantox=$cantox-($max_bau*3);
            $vorrat=$vorrat-$max_bau;
            $db->execute("update " . table_prefix . "planeten set fabriken='".$fabriken."',cantox='".$cantox."',vorrat='".$vorrat."' where id='".$pid."'");
        }
        //AUTOMATISCHES FABRIKENBAUEN ENDE
        //FABRIKENABBAU ANFANG
        if (($kolonisten/100)<=100) { 
            $max_col=floor($kolonisten/100)+$metro_fabriken_plus;             
        } else { 
            $max_col=100+floor(sqrt($kolonisten/100))+$metro_fabriken_plus;             
        }
        if ($fabriken>$max_col) {
            $prozent=round($fabriken-($fabriken/10));
        if ($prozent>$max_col) { 
            $fabriken=$prozent;             
        } else { $fabriken=$max_col;         
        }
            $db->execute("update " . table_prefix . "planeten set fabriken='".$fabriken."' where id='".$pid."'");
        }
        //FABRIKENABBAU ENDE
        //AUTOMATISCHES MINENBAUEN ANFANG
        if ($auto_minen==1) {
            $max_cantox=floor($cantox/4);
            $max_vorrat=$vorrat;
            if ($max_cantox<=$max_vorrat) { 
                $max_bau=$max_cantox;                 
            }
            if ($max_vorrat<=$max_cantox) { 
                $max_bau=$max_vorrat;                 
            }
            if (($kolonisten/100)<=200) { 
                $max_col=floor($kolonisten/100)+$metro_minen_plus;                 
            } else { 
                $max_col=200+floor(sqrt($kolonisten/100))+$metro_minen_plus;                 
            }
            $max_minen=$minen+$max_bau;
            if ($max_minen>$max_col) {
                $max_minen = $max_col;
                $max_bau = max(0, $max_col-$minen);
            }
            if ($max_minen>400+$metro_minen_plus) {
                $max_minen=400+$metro_minen_plus;
                $max_bau=400-$minen+$metro_minen_plus;
            }
            $minen=$minen+$max_bau;
            $cantox=$cantox-($max_bau*4);
            $vorrat=$vorrat-$max_bau;
            $db->execute("update " . table_prefix . "planeten set minen='".$minen."',cantox='".$cantox."',vorrat='".$vorrat."' where id='".$pid."'");
        }
        //AUTOMATISCHES MINENBAUEN ENDE
        //MINENABBAU ANFANG
        if (($kolonisten/100)<=200) { 
            $max_col=floor($kolonisten/100)+$metro_minen_plus;             
        } else { 
            $max_col=200+floor(sqrt($kolonisten/100))+$metro_minen_plus;            
        }
        if ($minen>$max_col) {
            $prozent=round($minen-($minen/10));
            if ($prozent>$max_col) { 
                $minen=$prozent;                 
            } else { 
                $minen=$max_col;                 
            }
            $db->execute("update " . table_prefix . "planeten set minen='".$minen."' where id='".$pid."'");
        }
        //MINENABBAU ENDE
        //AUTOMATISCHES ABWEHRANLAGENBAUEN ANFANG
        if ($auto_abwehr==1) {
            $max_cantox=floor($cantox/10);
            $max_vorrat=$vorrat;
            if ($max_cantox<=$max_vorrat) { $max_bau=$max_cantox; }
            if ($max_vorrat<=$max_cantox) { $max_bau=$max_vorrat; }
            if (($kolonisten/100)<=50) { $max_col=floor($kolonisten/100); } else { $max_col=50+floor(sqrt($kolonisten/100)); }
            if (in_array(11,$osys)) { $max_col=floor($max_col*1.5); }
            $max_abwehr=$abwehr+$max_bau;
            if ($max_abwehr>$max_col) {
                $max_abwehr = $max_col;
                $max_bau = max(0, $max_col-$abwehr);
            }
            if ($max_abwehr>300) {
                $max_abwehr=300;
                $max_bau=300-$abwehr;
            }
            $abwehr=$abwehr+$max_bau;
            $cantox=$cantox-($max_bau*10);
            $vorrat=$vorrat-$max_bau;
            $db->execute("update " . table_prefix . "planeten set abwehr='".$abwehr."',cantox='".$cantox."',vorrat='".$vorrat."' where id='".$pid."'");
        }
        //AUTOMATISCHES ABWEHRANLAGENBAUEN ENDE
        //ABWEHRANLAGENABBAU ANFANG
        if (($kolonisten/100)<=50) { $max_col=floor($kolonisten/100); } else { $max_col=50+floor(sqrt($kolonisten/100)); }
        if (in_array(11,$osys)) { $max_col=floor($max_col*1.5); }
        if ($abwehr>$max_col) {
            $prozent=round($abwehr-($abwehr/10));
            if ($prozent>$max_col) { $abwehr=$prozent; } else { $abwehr=$max_col; }
            $db->execute("update " . table_prefix . "planeten set abwehr='".$abwehr."' where id='".$pid."'");
        }
        //ABWEHRANLAGENABBAU ENDE
        //AUTOMATISCHER VORRATVERKAUF ANFANG
        if ($auto_vorrat==1) {
            $cantox=$cantox+$vorrat;
            $vorrat=0;
            $db->execute("update " . table_prefix . "planeten set vorrat='".$vorrat."',cantox='".$cantox."' where id='".$pid."'");
        }
        //AUTOMATISCHER VORRATVERKAUF ENDE
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////PLANETEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////ROUTEBEAMEN ANFANG
$sql400 = "SELECT * FROM " . table_prefix . "schiffe use index (status,routing_status,spiel) where status='2' and routing_status='2' and spiel='".$spiel."' order by id";
$rows400 = $db->execute($sql400);
$schiffanzahl = $rows400->RecordCount();
if ($schiffanzahl>=1) {
    $array400_out = $rows400->getArray();
    foreach ($array400_out as $array400) {        
        $shid=$array400["id"];
        $besitzer=$array400["besitzer"];
        $routing_id=$array400["routing_id"];
        $routing_tank=$array400["routing_tank"];
        $routing_schritt=$array400["routing_schritt"];
        $routing_mins=$array400["routing_mins"];
        $routing_rohstoff=$array400["routing_rohstoff"];
        $routing_id_temp=explode(":",$routing_id);
        $zid=$routing_id_temp[$routing_schritt];
        $routing_mins_temp=explode(":",$routing_mins);
        $mins=$routing_mins_temp[$routing_schritt];
        $r_option[0]=(int)substr($mins,0,1);
        $r_option[1]=(int)substr($mins,1,1);
        $r_option[2]=(int)substr($mins,3,1);
        $r_option[3]=(int)substr($mins,4,1);
        $r_option[4]=(int)substr($mins,5,1);
        $r_option[5]=(int)substr($mins,7,7);
        $r_option[6]=(int)substr($mins,14,4);
        $r_option[7]=(int)substr($mins,18,4);
        $mins_lemin=substr($mins,2,1);
        $voll_laden=substr($mins,6,1);
        $r_fracht[0]=$array400["fracht_cantox"];
        $r_fracht[1]=$array400["fracht_vorrat"];
        $r_fracht[2]=$array400["fracht_min1"];
        $r_fracht[3]=$array400["fracht_min2"];
        $r_fracht[4]=$array400["fracht_min3"];
        $r_fracht[5]=$array400["fracht_leute"];
        $r_fracht[6]=$array400["leichtebt"];
        $r_fracht[7]=$array400["schwerebt"];
        $fracht_lemin=$array400["lemin"];
        $frachtraum=$array400["frachtraum"];
        $leminmax=$array400["leminmax"];
        $kox=$array400["kox"];
        $koy=$array400["koy"];
        $r_faktor[0]=0; //cantox
        $r_faktor[1]=100; //vorrat
        $r_faktor[2]=100; //bax
        $r_faktor[3]=100; //ren
        $r_faktor[4]=100; //vor
        $r_faktor[5]=1; //kol
        $r_faktor[6]=30;  //lbt
        $r_faktor[7]=150; //sbt
        $freiraum=$frachtraum*100;
        $freitank=$leminmax-$fracht_lemin;
        for($zaehler=0;$zaehler<8;$zaehler++){
            $freiraum-=$r_fracht[$zaehler]*$r_faktor[$zaehler];
        }
        $sql410 = "SELECT * FROM " . table_prefix . "planeten use index (x_pos,y_pos,besitzer,spiel) where x_pos='".$kox."' and y_pos='".$koy."' and besitzer='".$besitzer."' and id='".$zid."' and spiel='".$spiel."'";
        $rows410 = $db->execute($sql410);
        $planetenanzahl = $rows410->RecordCount();
        if ($planetenanzahl==1) {
            $array2 = $rows410->getArray();
            $p_id=$array2["id"];
            $p_lemin=$array2["lemin"];
            $r_planet[0]=$array2["cantox"];
            $r_planet[1]=$array2["vorrat"];
            $r_planet[2]=$array2["min1"];
            $r_planet[3]=$array2["min2"];
            $r_planet[4]=$array2["min3"];
            $r_planet[5]=$array2["kolonisten"];
            $r_planet[6]=$array2["leichtebt"];
            $r_planet[7]=$array2["schwerebt"];
            //ausladen
            for($zaehler=0;$zaehler<8;$zaehler++){
                if ($r_option[$zaehler]==2) {
                    $r_planet[$zaehler]+=$r_fracht[$zaehler];
                    $freiraum+=$r_fracht[$zaehler]*$r_faktor[$zaehler];
                    $r_fracht[$zaehler]=0;
                }
            }
            //ausladen relativ
            for($zaehler=5;$zaehler<8;$zaehler++){
                if (($r_option[$zaehler]>2)and($r_option[$zaehler]>$r_planet[$zaehler])){
                    $zwischen=min($r_option[$zaehler]-$r_planet[$zaehler],$r_fracht[$zaehler]);
                    $r_planet[$zaehler]+=$zwischen;
                    $r_fracht[$zaehler]-=$zwischen;
                    $freiraum+=$zwischen*$r_faktor[$zaehler];
                }
            }
            //einladen wichtigstes Gut
            if ($r_option[$routing_rohstoff]==1){
                if (($r_planet[$routing_rohstoff]*$r_faktor[$routing_rohstoff])<=$freiraum) {
                    $freiraum=$freiraum-($r_planet[$routing_rohstoff]*$r_faktor[$routing_rohstoff]);
                    $r_fracht[$routing_rohstoff]+=$r_planet[$routing_rohstoff];
                    $r_planet[$routing_rohstoff]=0;
                }else{
                    $r_planet[$routing_rohstoff]=$r_planet[$routing_rohstoff]-(int)floor($freiraum/$r_faktor[$routing_rohstoff]);
                    $r_fracht[$routing_rohstoff]+=(int)floor($freiraum/$r_faktor[$routing_rohstoff]);
                    $freiraum-=(int)floor($freiraum/$r_faktor[$routing_rohstoff])*$r_faktor[$routing_rohstoff];
                }
            }elseif(($r_option[$routing_rohstoff]>2)and($r_option[$routing_rohstoff]<$r_planet[$routing_rohstoff])){
                    $zwischen=min($r_planet[$routing_rohstoff]-$r_option[$routing_rohstoff],(int)floor($freiraum/$r_faktor[$routing_rohstoff]));
                    $r_planet[$routing_rohstoff]-=$zwischen;
                    $r_fracht[$routing_rohstoff]+=$zwischen;
                    $freiraum-=$zwischen*$r_faktor[$routing_rohstoff];
            }
            //einladen relativ
            for($zaehler=5;$zaehler<8;$zaehler++){
                if (($r_option[$zaehler]>2)and($r_option[$zaehler]<$r_planet[$zaehler])){
                    $zwischen=min($r_planet[$zaehler]-$r_option[$zaehler],(int)floor($freiraum/$r_faktor[$zaehler]));
                    $r_planet[$zaehler]-=$zwischen;
                    $r_fracht[$zaehler]+=$zwischen;
                    $freiraum-=$zwischen*$r_faktor[$zaehler];
                }
            }
            //einladen cantox(da sonst divison durch null)
            if ($r_option[0]==1){
                $r_fracht[0]+=$r_planet[0];
                $r_planet[0]=0;
            }
            //einladen(Vorraete zum schluss)
            $ztest=1;
            $zaehler=2;
            while($ztest==1){
                if($zaehler==1){$ztest=0;}
                if ($r_option[$zaehler]==1){
                    if (($r_planet[$zaehler]*$r_faktor[$zaehler])<=$freiraum) {
                        $freiraum=$freiraum-($r_planet[$zaehler]*$r_faktor[$zaehler]);
                        $r_fracht[$zaehler]+=$r_planet[$zaehler];
                        $r_planet[$zaehler]=0;
                    }else{
                        $r_planet[$zaehler]=$r_planet[$zaehler]-(int)floor($freiraum/$r_faktor[$zaehler]);
                        $r_fracht[$zaehler]+=(int)floor($freiraum/$r_faktor[$zaehler]);
                        $freiraum-=(int)floor($freiraum/$r_faktor[$zaehler])*$r_faktor[$zaehler];
                    }
                }
                $zaehler=($zaehler==7)?1:$zaehler+1;
            }
            //rest
            if ($mins_lemin==1) {
                if ($p_lemin<=$freitank) {
                    $freitank=$freitank-$p_lemin;
                    $fracht_lemin=$fracht_lemin+$p_lemin;
                    $p_lemin=0;
                }else{
                    $p_lemin=$p_lemin-$freitank;
                    $fracht_lemin=$fracht_lemin+$freitank;
                    $freitank=0;
                }
            }
            if ($mins_lemin==2) {
                $p_lemin=$p_lemin+$fracht_lemin;
                $fracht_lemin=0;
            }
            if (($fracht_lemin<$routing_tank) and ($p_lemin>0)) {
                $fehlt=$routing_tank-$fracht_lemin;
                if ($fehlt<=$p_lemin) {
                    $p_lemin=$p_lemin-$fehlt;$fracht_lemin=$routing_tank;
                }else{
                    $fracht_lemin=$fracht_lemin+$p_lemin;$p_lemin=0;
                }
            }
            $s_cantox=$r_planet[0];
            $s_vorrat=$r_planet[1];
            $s_bax=$r_planet[2];
            $s_ren=$r_planet[3];
            $s_vor=$r_planet[4];
            $s_kol=$r_planet[5];
            $s_lbt=$r_planet[6];
            $s_sbt=$r_planet[7];
            $db->execute("UPDATE " . table_prefix . "planeten set lemin='".$p_lemin."', 
                                                                  cantox='".$s_cantox."', 
                                                                  vorrat='".$s_vorrat."', 
                                                                  min1='".$s_bax."', 
                                                                  min2='".$s_ren."', 
                                                                  min3='".$s_vor."', 
                                                                  kolonisten='".$s_kol."', 
                                                                  leichtebt='".$s_lbt."', 
                                                                  schwerebt='".$s_sbt."' 
                                                     where id='".$p_id."'");
            $s_cantox=$r_fracht[0];
            $s_vorrat=$r_fracht[1];
            $s_bax=$r_fracht[2];
            $s_ren=$r_fracht[3];
            $s_vor=$r_fracht[4];
            $s_kol=$r_fracht[5];
            $s_lbt=$r_fracht[6];
            $s_sbt=$r_fracht[7];
            $db->execute("UPDATE " . table_prefix . "schiffe set fracht_leute='".$s_kol."', 
                                                                 leichtebt='".$s_lbt."', 
                                                                 schwerebt='".$s_sbt."', 
                                                                 lemin='".$fracht_lemin."', 
                                                                 fracht_vorrat='".$s_vorrat."', 
                                                                 fracht_cantox='".$s_cantox."', 
                                                                 fracht_min1='".$s_bax."', 
                                                                 fracht_min2='".$s_ren."', 
                                                                 fracht_min3='".$s_vor."' 
                                                    where id='".$shid."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////ROUTEBEAMEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////TRAKTORSTRAHL UEBERPRUEFEN ANFANG
$sql420 = "SELECT id,traktor_id,kox,koy,besitzer,spezialmission,spiel FROM " . table_prefix . "schiffe use index (spezialmission,spiel) where spezialmission='21' and spiel='".$spiel."' order by id";
$rows420 = $db->execute($sql420);
$schiffanzahl = $rows420->RecordCount();
if ($schiffanzahl>=1) {
    $array420_out = $rows420->getArray();
    foreach ($array420_out as $array420) {                
        $shid=$array420["id"];
        $traktor_id=$array420["traktor_id"];
        $kox=$array420["kox"];
        $koy=$array420["koy"];
        $besitzer=$array420["besitzer"];
        $sql430 = "SELECT id,kox,koy,besitzer,spiel FROM " . table_prefix . "schiffe use index (kox,koy,spiel) where id='".$traktor_id."' and kox='".$kox."' and koy='".$koy."' and spiel='".$spiel."'";
        $rows430 = $db->execute($sql430);
        $datensaetze = $rows430->RecordCount();
        if (!$datensaetze==1) {
            $db->execute("UPDATE " . table_prefix . "schiffe set spezialmission='0',traktor_id='0' where id='".$shid."' and spiel='".$spiel."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////TRAKTORSTRAHL UEBERPRUEFEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////POLITIKENDE ANFANG
$db->execute("DELETE FROM " . table_prefix . "politik where optionen='1' and spiel='".$spiel."'");
$db->execute("UPDATE " . table_prefix . "politik set optionen=optionen-1 where optionen>1 and spiel='".$spiel."'");
///////////////////////////////////////////////////////////////////////////////////////////////POLITIKENDE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////PLASMASTURM VERSCHWINDEN ANFANG
$sql440 = "SELECT * FROM " . table_prefix . "anomalien use index (spiel,art) where spiel='".$spiel."' and art in ('4','6') order by id";
$rows440 = $db ->execute($sql440);
$datensaetze = $rows440->RecordCount();
if ($datensaetze>=1) {
    $array440_out = $$rows440->getArray();
    foreach ($array440_out as $array440) {        
        $aid=$array440["id"];
        $art=$array440["art"];
        $zeit=$array440["extra"];
        $zeit--;
        if ($zeit>=1) {
            $db->execute("UPDATE " . table_prefix . "anomalien set extra='".$zeit."' where id='".$aid."'");
        } else {
            if($zeit==0){
                $db->execute("DELETE FROM " . table_prefix . "anomalien where id='".$aid."'");
            }else{
                /*
                 * Auch hier wieder ein else ohne Funktion ?
                 */
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////PLASMASTURM VERSCHWINDEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////PLASMASTURM ENSTEHUNG ANFANG
$sql450 = "SELECT count(*) as total FROM " . table_prefix . "anomalien where art='6' and spiel='".$spiel."'";
$sturm=$db->getOne($sql450);
$zufall=mt_rand(1,100);
if (($sturm<$plasma_max) and ($zufall<=$plasma_wahr)) {
    $x=mt_rand(1,(($umfang-310)/10));
    $y=mt_rand(1,(($umfang-310)/10));
    $plasma_lang_max = 0;
    for($i=0;$i< 31;$i++){
        for($j=0;$j< 31;$j++){
            $abstand=round(sqrt(((15-$i)*(15-$i))+((15-$j)*(15-$j))));
            $zufall=mt_rand(1,100);
            if($zufall<=(100-($abstand*5))){
                $sql460 = "SELECT extra from " . table_prefix . "anomalien use index (x_pos,y_pos,art,spiel) where x_pos=(".$x."+".$i.")*10 and y_pos=(".$y."+".$j.")*10 and art='4' and spiel='".$spiel."'";
                $rows460 = $db->execute($sql460);
                $reihen = $rows460->RecordCount();
                if($reihen>=1){                    
                    $zeit=$db->getOne($sql460);
                    if($zeit==-1){
                    }else{
                        $runden=mt_rand(3,$plasma_lang);
                        $plasma_lang_max=max($runden,$plasma_lang_max);
                        $runden=max(3,$runden,$zeit);
                        $db->execute("INSERT INTO " . table_prefix . "anomalien (art,x_pos,y_pos,extra,spiel)
                                                                     values ('4',(".$x."+".$i.")*10,(".$y."+".$j.")*10,'".$runden."','".$spiel."')");
                    }
                }else{
                    $runden=mt_rand(3,$plasma_lang);
                    $plasma_lang_max=max($runden,$plasma_lang_max);
                    $db->execute("INSERT INTO " . table_prefix . "anomalien (art,x_pos,y_pos,extra,spiel)
                                                                     values ('4',(".$x."+".$i.")*10,(".$y."+".$j.")*10,'".$runden."','".$spiel."')");
                }
            }
        }
    }
    $db->execute("INSERT INTO " . table_prefix . "anomalien (art, extra, spiel)
                                                     values ('6', '".$plasma_lang_max."', '".$spiel."')");
}
///////////////////////////////////////////////////////////////////////////////////////////////PLASMASTURM ENSTEHUNG ENDE
///////////////////////////////////////////////////////////////////////////////////////////////NEBELSEKTOREN ANFANG
$besitzer_recht[1]='1000000000';
$besitzer_recht[2]='0100000000';
$besitzer_recht[3]='0010000000';
$besitzer_recht[4]='0001000000';
$besitzer_recht[5]='0000100000';
$besitzer_recht[6]='0000010000';
$besitzer_recht[7]='0000001000';
$besitzer_recht[8]='0000000100';
$besitzer_recht[9]='0000000010';
$besitzer_recht[10]='0000000001';
/*
 * inc.host_nebel bereits angepaßt.
 */
include(includes . 'inc.host_nebel.php');
///////////////////////////////////////////////////////////////////////////////////////////////NEBELSEKTOREN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////TARNER VERFOLGEN ANFANG
$sql500 = "SELECT besitzer,zielid,id FROM " . table_prefix . "schiffe use index (flug,spiel) where flug='3' and spiel='".$spiel."' order by id";
$rows500 = $db->execute($sql500);
$schiffanzahl = $rows500->RecordCount();
if ($schiffanzahl>=1) {
    $array500_out = $rows500->getArray();
    foreach ($array500_out as $array500) {        
        $ssid=$array500["id"];
        $besitzer=$array500["besitzer"];
        $zielid=$array500["zielid"];
        $spalte='sicht_'.$besitzer.'_beta';
        $sql510 = "SELECT id FROM " . table_prefix . "schiffe where id='".$zielid."' and tarnfeld>=1 and ".$spalte."='0'";
        $rows510 = $db->execute($sql510);
        $zielanzahl = $rows510->RecordCount();
        if ($zielanzahl==1) {
            $db->execute("update " . table_prefix . "schiffe set flug='0',zielx='0',ziely='0',zielid='0' where id='".$ssid."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////TARNER VERFOLGEN ENDE
///////////////////////////////////////////////////////////////////////////////////////////////MINEN SICHTBAR ANFANG
if ($module[2]) {
    $db->execute("UPDATE " . table_prefix . "anomalien set sicht='0000000000' where (art='5') and spiel='".$spiel."'");
    $sql520 = "SELECT * FROM " . table_prefix . "anomalien use index (spiel,art) where spiel='".$spiel."' and art='5' order by id";
    $rows520 = $db->execute($sql520);
    $datensaetze = $rows520->RecordCount();
    if ($datensaetze>=1) {
        $array520_out = $rows520->getArray();
        foreach ($array530_aout as $array530) {            
            $aid=$array530["id"];
            $kox=$array530["x_pos"];
            $koy=$array530["y_pos"];
            $extra=$array530["extra"];
            $extrab=explode(":",$extra);
            $sicht='0000000000';
            for ($xn=1;$xn<=10;$xn++) {
                if ($spieler_id_c[$xn]>=1) {
                    $ja[$xn]=0;
                    if ($extrab[0]==$xn) { $ja[$xn]=1; }
                    if (($beziehung[$xn][$extrab[0]]['status']==4) or ($beziehung[$xn][$extrab[0]]['status']==5)) { $ja[$xn]=1; }
                }
            }
            /////////////////////
             $reichweite=161;
            $zeiger_temp1 = "SELECT kox,koy,id,scanner,spiel,besitzer FROM " . table_prefix . "schiffe use index (besitzer,spiel) where besitzer!='".$extrab[0]."' and (sqrt(((kox-".$kox.")*(kox-".$kox."))+((koy-".$koy.")*(koy-".$koy.")))<=".$reichweite.") and spiel='".$spiel."' order by id";
            $rows_temp1 = $db->execute($zeiger_temp1);
            $scanschiff = $rows_temp1->RecordCount();
            if ($scanschiff>=1) {
                $rows_temparrayout = $db->getArray($zeiger_temp1);
                foreach ($rows_temparrayout as $array_temp) {                    
                    $t_shid=$array_temp["id"];
                    $t_scanner=$array_temp["scanner"];
                    $t_zielx=$array_temp["kox"];
                    $t_ziely=$array_temp["koy"];
                    $t_besitzer=$array_temp["besitzer"];
                    $lichtjahre=sqrt(($kox-$t_zielx)*($kox-$t_zielx)+($koy-$t_ziely)*($koy-$t_ziely));
                    if ((($lichtjahre<=93) and (t_scanner==0)) or (($lichtjahre<=130) and (t_scanner==1)) or (($lichtjahre<=161) and (t_scanner==2))) {
                        $ja[$t_besitzer]=1;
                        for ($xn=1;$xn<=10;$xn++) {
                            if ($spieler_id_c[$xn]>=1) {
                                if (($beziehung[$xn][$t_besitzer]['status']==4) or ($beziehung[$xn][$t_besitzer]['status']==5)) { $ja[$xn]=1; }
                            }
                        }
                    }
                }
            }
            ////////////////
            for ($xn=1;$xn<=10;$xn++) {
                if ($spieler_id_c[$xn]>=1) {
                    if ($ja[$xn]==1) { 
                        $sicht=sichtaddieren($sicht,$besitzer_recht[$xn]);                         
                    }
                }
            }
            $db->execute("UPDATE " . table_prefix . "anomalien set sicht='".$sicht."' where id='".$aid."' and spiel='".$spiel."'");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////MINEN SICHTBAR ENDE
///////////////////////////////////////////////////////////////////////////////////////////////RANGLISTE ANFANG
for ($m=1;$m<11;$m++) {
    $spieler_basen_c[$m]=0;
    $spieler_planeten_c[$m]=0;
    $spieler_schiffe_c[$m]=0;
    $spieler_basen_c_wert[$m]=0;
    $spieler_planeten_c_wert[$m]=0;
    $spieler_schiffe_c_wert[$m]=0;
    $spieler_raus_c_old[$m]=$spieler_raus_c[$m];
    $spieler_raus_c[$m]=1;
    if ($spieler_id_c[$m]==0) { $spieler_raus_c[$m]=0; }
    $heimatplaneten[$m]=0;
}
$zeiger7 = "SELECT id,besitzer,spiel,heimatplanet FROM " . table_prefix . "planeten where spiel='".$spiel."' and besitzer>=1 order by id";
$rows7 = $db->execute($zeiger7);
$planetenanzahl = $rows7->RecordCount();
if ($planetenanzahl>=1) {
    $rows7_arrayout = $db->getArray($zeiger7);
    foreach ($rows7_arrayout as $rows7_array) {
        $besitzer=$rows7_array["besitzer"];
        $heimatplanet=$rows7_array["heimatplanet"];
        if (($heimatplanet>=1) and ($besitzer==$heimatplanet)) { $heimatplaneten[$heimatplanet]=1;}
        $spieler_planeten_c[$besitzer]=$spieler_planeten_c[$besitzer]+5;
    }
}
$zeiger6 = "SELECT id,besitzer,spiel FROM " . table_prefix . "sternenbasen where spiel='".$spiel."' and besitzer>=1 order by id";
$rows6 = $db->execute($zeiger6);
$planetenanzahl = $rows6->RecordCount();
if ($planetenanzahl>=1) {
    $rows6_arrayout = $db->getArray($zeiger6);
    foreach ($rows6_arrayout as $rows6_array) {        
        $besitzer=$rows6_array["besitzer"];
        $spieler_basen_c[$besitzer]=$spieler_basen_c[$besitzer]+10;
    }
}
$zeiger4 = "SELECT id,besitzer,techlevel,spiel FROM " . table_prefix . "schiffe use index (spiel,besitzer) where spiel='".$spiel."' and besitzer>=1 order by id";
$rows4 = $db->execute($zeiger4);
$planetenanzahl = $rows4->RecordCount();
if ($planetenanzahl>=1) {
    $rows4_arrayout = $db->getArray($zeiger4);
    foreach ($rows4_arrayout as $rows4_array) {
        $besitzer=$rows4_array["besitzer"];
        $techlevel=$rows4_array["techlevel"];
        $spieler_schiffe_c[$besitzer]=$spieler_schiffe_c[$besitzer]+$techlevel;
    }
}
for ($m=1;$m<11;$m++) {
    $spieler_schiffe_platz_c[$m]=platz_schiffe($spieler_schiffe_c[$m]);
}
for ($m=1;$m<11;$m++) {
    $spieler_schiffe_c_wert[$m]=$spieler_schiffe_c[$m];
    $spieler_schiffe_c[$m]=$spieler_schiffe_platz_c[$m];
    $spieler_basen_platz_c[$m]=platz_basen($spieler_basen_c[$m]);
}
for ($m=1;$m<11;$m++) {
    $spieler_basen_c_wert[$m]=$spieler_basen_c[$m];
    $spieler_basen_c[$m]=$spieler_basen_platz_c[$m];
    $spieler_planeten_platz_c[$m]=platz_planeten($spieler_planeten_c[$m]);
}
for ($m=1;$m<11;$m++) {
    $spieler_planeten_c_wert[$m]=$spieler_planeten_c[$m];
    $spieler_planeten_c[$m]=$spieler_planeten_platz_c[$m];
    $spieler_gesamt_c[$m]=$spieler_schiffe_c[$m]+$spieler_basen_c[$m]+$spieler_planeten_c[$m];
}
for ($m=1;$m<11;$m++) {
    $spieler_platz_c[$m]=platz($spieler_gesamt_c[$m]);
}
for ($m=1;$m<11;$m++) {
    if ($spiel_out==0) {
        if (($spieler_planeten_c_wert[$m]>=1) or ($spieler_schiffe_c_wert[$m]>=1)) {
            $spieler_raus_c[$m]=0;
        }
    }
    if ($spiel_out==1) {
        if ($spieler_planeten_c_wert[$m]>=1) {
            $spieler_raus_c[$m]=0;
        }
    }
    if ($spiel_out==2) {
        if ($spieler_basen_c_wert[$m]>=1) {
            $spieler_raus_c[$m]=0;
        }
    }
    if ($spiel_out==3) {
        if ($heimatplaneten[$m]==1) {
            $spieler_raus_c[$m]=0;
        }
    }
}
$spieleranzahl=0;
for ($m=1;$m<11;$m++) {
    if (($spieler_id_c[$m]>=1) and ($spieler_raus_c[$m]==0)) { $spieleranzahl++; }
}
for ($m=1;$m<11;$m++) {
    if (($spieler_raus_c[$m]==1) and ($spieler_raus_c_old[$m]==0)) {
        /*
         * Verstehe nicht weshalb hier Felder wie besitzer und spiel selected werden obwohl nur die id benötig wird . Abfrage geändert
         */
        $zeiger3 = "SELECT id FROM " . table_prefix . "sternenbasen use index(besitzer,spiel) where besitzer='".$m."' and spiel='".$spiel."' order by id";
        $rows3 = $db->execute($zeiger3);
        $basenanzahl = $rows3->RecordCount();
        if ($basenanzahl>=1) {
            $rows3_out = $db->getArray($zeiger3);
            foreach ($rows3_out as $rows3_array) {                
                $baid=$rows3_array["id"];
                $db->execute("DELETE FROM " . table_prefix . "huellen where baid='".$baid."'");
            }
        }
        $db->execute("UPDATE " . table_prefix . "sternenbasen set besitzer='0' where besitzer='".$m."' and spiel='".$spiel."'");
        
        $zeiger2 = "SELECT * FROM " . table_prefix . "schiffe use index (besitzer,spiel) where besitzer='".$m."' and spiel='".$spiel."'";
        $rows2 = $db->execute($zeiger2);
        $schiffanzahl = $rows2->RecordCount();
        if ($schiffanzahl>=1) {
            $rows2_out = $db->getArray($zeiger2);
            foreach ($rows2_out as $rows2_array) {                
                $shid=$rows2_array["id"];
                $db->execute("DELETE FROM " . table_prefix . "anomalien where art='3' and extra like 's:".$shid.":%'");
                $db->execute("UPDATE " . table_prefix . "schiffe set flug='0',warp='0',zielx='0',ziely='0',zielid='0' where flug='3' and zielid='".$shid."'");
            }
        }
        $db->execute("DELETE FROM " . table_prefix . "schiffe where besitzer='".$m."' and spiel='".$spiel."'");
        $db->execute("DELETE FROM " . table_prefix . "politik where spiel='".$spiel."' and (partei_a='".$m."' or partei_b='".$m."')");
        $db->execute("UPDATE " . table_prefix . "planeten set kolonisten='0',besitzer='0', auto_minen='0',auto_fabriken='0',abwehr='0',auto_abwehr='0',auto_vorrat='0',logbuch='' where besitzer='".$m."' and spiel='".$spiel."'");
        $db->execute("UPDATE " . table_prefix . "planeten set kolonisten_new='0', schwerebt_new='0', leichtebt_new='0',kolonisten_spieler='0' where kolonisten_spieler='".$m."' and spiel='".$spiel."'");
        $db->execute("DELETE FROM " . table_prefix . "neuigkeiten where spieler_id='".$m."' and spiel_id='".$spiel."'");
    }
}
$db->execute("UPDATE " . table_prefix . "spiele set spieleranzahl='".$spieleranzahl."', 
                                                    spieler_1_raus='".$spieler_raus_c[1]."', 
                                                    spieler_2_raus='".$spieler_raus_c[2]."', 
						    spieler_3_raus='".$spieler_raus_c[3]."', 
                                                    spieler_4_raus='".$spieler_raus_c[4]."', 
                                                    spieler_5_raus='".$spieler_raus_c[5]."', 
                                                    spieler_6_raus='".$spieler_raus_c[6]."', 
                                                    spieler_7_raus='".$spieler_raus_c[7]."', 
                                                    spieler_8_raus='".$spieler_raus_c[8]."', 
                                                    spieler_9_raus='".$spieler_raus_c[9]."', 
                                                    spieler_10_raus='".$spieler_raus_c[10]."', 
                                                    spieler_1_basen='".$spieler_basen_c[1]."', 
                                                    spieler_1_planeten='".$spieler_planeten_c[1]."', 
                                                    spieler_1_schiffe='".$spieler_schiffe_c[1]."', 
                                                    spieler_2_basen='".$spieler_basen_c[2]."', 
                                                    spieler_2_planeten='".$spieler_planeten_c[2]."', 
                                                    spieler_2_schiffe='".$spieler_schiffe_c[2]."', 
                                                    spieler_3_basen='".$spieler_basen_c[3]."', 
                                                    spieler_3_planeten='".$spieler_planeten_c[3]."', 
                                                    spieler_3_schiffe='".$spieler_schiffe_c[3]."', 
                                                    spieler_4_basen='".$spieler_basen_c[4]."', 
                                                    spieler_4_planeten='".$spieler_planeten_c[4]."', 
                                                    spieler_4_schiffe='".$spieler_schiffe_c[4]."', 
                                                    spieler_5_basen='".$spieler_basen_c[5]."', 
                                                    spieler_5_planeten='".$spieler_planeten_c[5]."', 
                                                    spieler_5_schiffe='".$spieler_schiffe_c[5]."', 
                                                    spieler_6_basen='".$spieler_basen_c[6]."', 
                                                    spieler_6_planeten='".$spieler_planeten_c[6]."', 
                                                    spieler_6_schiffe='".$spieler_schiffe_c[6]."', 
                                                    spieler_7_basen='".$spieler_basen_c[7]."', 
                                                    spieler_7_planeten='".$spieler_planeten_c[7]."', 
                                                    spieler_7_schiffe='".$spieler_schiffe_c[7]."', 
                                                    spieler_8_basen='".$spieler_basen_c[8]."', 
                                                    spieler_8_planeten='".$spieler_planeten_c[8]."', 
                                                    spieler_8_schiffe='".$spieler_schiffe_c[8]."', 
                                                    spieler_9_basen='".$spieler_basen_c[9]."', 
                                                    spieler_9_planeten='".$spieler_planeten_c[9]."', 
                                                    spieler_9_schiffe='".$spieler_schiffe_c[9]."', 
                                                    spieler_10_basen='".$spieler_basen_c[10]."', 
                                                    spieler_10_planeten='".$spieler_planeten_c[10]."', 
                                                    spieler_10_schiffe='".$spieler_schiffe_c[10]."', 
                                                    spieler_1_platz='".$spieler_platz_c[1]."', 
                                                    spieler_2_platz='".$spieler_platz_c[2]."', 
                                                    spieler_3_platz='".$spieler_platz_c[3]."', 
                                                    spieler_4_platz='".$spieler_platz_c[4]."', 
                                                    spieler_5_platz='".$spieler_platz_c[5]."', 
                                                    spieler_6_platz='".$spieler_platz_c[6]."', 
                                                    spieler_7_platz='".$spieler_platz_c[7]."', 
                                                    spieler_8_platz='".$spieler_platz_c[8]."', 
                                                    spieler_9_platz='".$spieler_platz_c[9]."', 
                                                    spieler_10_platz='".$spieler_platz_c[10]."' 
					where id='".$spiel."'");
///////////////////////////////////////////////////////////////////////////////////////////////RANGLISTE ENDE
///////////////////////////////////////////////////////////////////////////////////////////////HASH ANFANG
for ($m=1;$m<11;$m++) {
    $hash=zufallstring();
    $db->execute("UPDATE " . table_prefix . "spiele set spieler_".$m."_hash = '".$hash."' where id='".$spiel."'");
    $spieler_hash[$m]=$hash;
}
///////////////////////////////////////////////////////////////////////////////////////////////HASH ENDE
///////////////////////////////////////////////////////////////////////////////////////////////LETZTER MONAT ENDE
if ($neuekolonie==0) {$neuekolonie=$lang['host']['letztermonat0'];}
if ($neuekolonie==1) {$neuekolonie=$lang['host']['letztermonat1'];}
if ($neuekolonie>=2) {$neuekolonie=str_replace(array('{1}'),array($neuekolonie),$lang['host']['letztermonat2']);}
if ($neueschiffe==0) {$neueschiffe=$lang['host']['letztermonat3'];}
if ($neueschiffe==1) {$neueschiffe=$lang['host']['letztermonat4'];}
if ($neueschiffe>=2) {$neueschiffe=str_replace(array('{1}'),array($neueschiffe),$lang['host']['letztermonat5']);}
if ($neuebasen==0) {$neuebasen=$lang['host']['letztermonat6'];}
if ($neuebasen==1) {$neuebasen=$lang['host']['letztermonat7'];}
if ($neuebasen>=2) {$neuebasen=str_replace(array('{1}'),array($neuebasen),$lang['host']['letztermonat8']);}
if ($schiffevernichtet==0) {$schiffevernichtet=$lang['host']['letztermonat9'];}
if ($schiffevernichtet==1) {$schiffevernichtet=$lang['host']['letztermonat10'];}
if ($schiffevernichtet>=2) {$schiffevernichtet=str_replace(array('{1}'),array($schiffevernichtet),$lang['host']['letztermonat11']);}
if ($planetenerobert==0) {$planetenerobert=$lang['host']['letztermonat12'];}
if ($planetenerobert==1) {$planetenerobert=$lang['host']['letztermonat13'];}
if ($planetenerobert>=2) {$planetenerobert=str_replace(array('{1}'),array($planetenerobert),$lang['host']['letztermonat14']);}
if ($planetenerobertfehl==0) {$planetenerobertfehl="";}
if ($planetenerobertfehl==1) {$planetenerobertfehl=$lang['host']['letztermonat15'];}
if ($planetenerobertfehl>=2) {$planetenerobertfehl=str_replace(array('{1}'),array($planetenerobertfehl),$lang['host']['letztermonat16']);}
if ($schiffverschollen==0) {$schiffverschollen=$lang['host']['letztermonat21'];}
if ($schiffverschollen==1) {$schiffverschollen=$lang['host']['letztermonat22'];}
if ($schiffverschollen>=2) {$schiffverschollen=str_replace(array('{1}'),array($schiffverschollen),$lang['host']['letztermonat23']);}
$letztermonat=str_replace(array('{1}','{2}','{3}','{4}','{5}','{6}','{7}'),array($neuekolonie,$neueschiffe,$neuebasen,$schiffevernichtet,$planetenerobert,$planetenerobertfehl,$schiffverschollen),$lang['host']['letztermonat17']);
$db->execute("UPDATE " . table_prefix . "spiele set letztermonat='$letztermonat', runde=runde+1 where id=$spiel;");
///////////////////////////////////////////////////////////////////////////////////////////////ZIEL ANFANG
$endejetzt=0;
if ($ziel_id==1) {
    if ($spieleranzahl<=intval($ziel_info)) {
        $endejetzt=1;
    }
}
if ($ziel_id==2) {
    if (($spieler_raus_c[1]==1) or ($spieler_raus_c[5]==1) or ($spieler_raus_c[8]==1) or ($spieler_raus_c[2]==1) or ($spieler_raus_c[6]==1) or ($spieler_raus_c[9]==1) or ($spieler_raus_c[3]==1) or ($spieler_raus_c[7]==1) or ($spieler_raus_c[10]==1) or ($spieler_raus_c[4]==1)) {
        $endejetzt=1;
    }
}
if ($ziel_id==5) {
    for ($k=1;$k<11;$k++) {
        $spieler_ziel_t_c[$k]=0;
    }
    $zeiger1 = "SELECT status,spiel,id,besitzer,fracht_min3 FROM " . table_prefix . "schiffe use index (status,spiel) where status<>2 and spiel='".$spiel."' order by id";
    $rows1 = $db->execute($zeiger1);
    $schiffanzahl = $rows1->RecordCount();
    if ($schiffanzahl>=1) {
        $barray_out = $db->getArray($zeiger1);
        foreach ($barray_out as $barray) {            
            $besitzer=$barray["besitzer"];
            $fracht_min3=$barray["fracht_min3"];
            if ($fracht_min3>=1) { $spieler_ziel_t_c[$besitzer]=$spieler_ziel_t_c[$besitzer]+$fracht_min3; }
        }
    }
    $db->execute("UPDATE " . table_prefix ."spiele set spieler_1_ziel='".$spieler_ziel_t_c[1]."', 
                                                       spieler_2_ziel='".$spieler_ziel_t_c[2]."', 
                                                       spieler_3_ziel='".$spieler_ziel_t_c[3]."', 
                                                       spieler_4_ziel='".$spieler_ziel_t_c[4]."', 
                                                       spieler_5_ziel='".$spieler_ziel_t_c[5]."', 
                                                       spieler_6_ziel='".$spieler_ziel_t_c[6]."', 
                                                       spieler_7_ziel='".$spieler_ziel_t_c[7]."', 
                                                       spieler_8_ziel='".$spieler_ziel_t_c[8]."', 
                                                       spieler_9_ziel='".$spieler_ziel_t_c[9]."'
                                                       spieler_10_ziel='".$spieler_ziel_t_c[10]."' 
                                            where id='".$spiel."'");
    $temp=intval($ziel_info);
    if (($spieler_ziel_t_c[1]>=$temp) or ($spieler_ziel_t_c[2]>=$temp) or ($spieler_ziel_t_c[3]>=$temp) or ($spieler_ziel_t_c[4]>=$temp) or ($spieler_ziel_t_c[5]>=$temp) or ($spieler_ziel_t_c[6]>=$temp) or ($spieler_ziel_t_c[7]>=$temp) or ($spieler_ziel_t_c[8]>=$temp) or ($spieler_ziel_t_c[9]>=$temp) or ($spieler_ziel_t_c[10]>=$temp)) {
        $endejetzt=1;
    }
}
if ($ziel_id==6) {
    if (($spieler_raus_c[1]==1) or ($spieler_raus_c[5]==1) or ($spieler_raus_c[8]==1) or ($spieler_raus_c[2]==1) or ($spieler_raus_c[6]==1) or ($spieler_raus_c[9]==1) or ($spieler_raus_c[3]==1) or ($spieler_raus_c[7]==1) or ($spieler_raus_c[10]==1) or ($spieler_raus_c[4]==1)) {
        $endejetzt=1;
    }
} 
if ($endejetzt==1) {
    include( includes . 'inc.host_spielende.php');
}
///////////////////////////////////////////////////////////////////////////////////////////////ZIEL ENDE
///////////////////////////////////////////////////////////////////////////////////////////////STATS AUSWERTUNG ANFANG
for ($m=1;$m<11;$m++) {
    if ($spieler_id_c[$m]>=1) {
                               $db->execute("UPDATE " . table_prefix ."user set stat_sieg=stat_sieg+" . $stat_sieg[$m] .",
                                                                                         stat_schlacht=stat_schlacht+" . $stat_schlacht[$m] .",
                                                                                         stat_schlacht_sieg=stat_schlacht_sieg+" . $stat_schlacht_sieg[$m] . ",
                                                                                         stat_kol_erobert=stat_kol_erobert+" . $stat_kol_erobert[$m] .",
                                                                                         stat_lichtjahre=stat_lichtjahre+" . $stat_lichtjahre[$m] .",
                                                                                         stat_monate=stat_monate+1 where id='" . $spieler_id_c[$m] ."'"); 
    }
}
/*
if ((@file_exists(extend_dir . "xstats")) and (intval(substr($spiel_extend,2,1))==1)) {
    xstats_collectAndStore( $sid, $stat_schlacht,$stat_schlacht_sieg,$stat_kol_erobert,$stat_lichtjahre);
}
*/
///////////////////////////////////////////////////////////////////////////////////////////////STATS AUSWERTUNG ENDE
/////////////////////////////////////////////////////////////////////////////////////////////// BENACHRICHTIGUNG ANFANG
      /*
         * Wir verschicken keinerlei E-Mails, Jabber oder ICQ NAchrichten .  Letzteren Beiden MEssanger nutzt keiner mehr bzw. Dienste eingstellt
         */
/*for ($k=1; $k<=10; $k++) {
    if ($spieler_id_c[$k]>0 and $spieler_raus_c[$k]==0) {
        $nachrichtemail=str_replace('{1}',$spiel_name,$lang['host']['letztermonat18']);
        $nachrichticq=str_replace('{1}',$spiel_name,$lang['host']['letztermonat24']);
        $emailtopic=str_replace(array('{1}'),array($spiel_name),$lang['host']['letztermonat20']);
        $zeiger = mysql_query("SELECT * FROM " . table_prefix ."user WHERE id='" . $spieler_id_c[$k] . "'");
        $array = mysql_fetch_array($zeiger);
        $emailadresse=$array['email'];
        $icqnummer=$array['icq'];
        $optionen=$array['optionen'];
        $emailicq=$icqnummer."@pager.icq.com";
        $url="http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
        $url=substr($url,0,strlen($url)-19);
        $url="http://".$_SERVER['SERVER_NAME'];
        $folders = explode('/', $_SERVER['SCRIPT_NAME']);
        $count = 0;
        $url .= '/';
        foreach ($folders as $value) {
            if ((0 < $count) and (count($folders) > $count+1) and ('inhalt' != $value)){
                $url .= $value . '/';
            }
            $count++;
        }       
        $hash=$spieler_hash[$k];
  
        $nachricht_fertig = $nachrichtemail."\n\n".$url.'index.php?hash='.$hash;
        if (substr($optionen,0,1)=='1') {
        @mail($emailadresse,$emailtopic, $nachricht_fertig,
            "From: $absenderemail\r\n"
            ."Reply-To: $absenderemail\r\n"
            ."X-Mailer: PHP/" . phpversion());
        }
        /*
        if (substr($optionen,1,1)=='1') {
            $header="From $absenderemail\nReply-To:$absenderemail\n";
            @mail($emailicq,$emailtopic,"$nachrichticq",$header);
        }
        
   }
}*/
/////////////////////////////////////////////////////////////////////////////////////////////// BENACHRICHTIGUNG ENDE
///////////////////////////////////////////////////////////////////////////////////////////////LETZTER MONAT ENDE
$nachricht=str_replace('{1}',$spiel_name,$lang['host']['letztermonat19']);
$aktuell=time();
$db->execute("INSERT INTO " . table_prefix ."chat (spiel,datum,text,an,von,farbe) values ('" . $spiel . "','" . $aktuell ."','" . $nachricht ."','0','System','000000')");

///////////////////////////////////////////////////////////////////////////////////////////////MOVIEGIF OPTIONAL ANFANG
/*
 * shot.php angepasst
 */
if ((@file_exists(extend_dir . "moviegif")) and (intval(substr($spiel_extend,0,1))==1)) {
    include(extend_dir.'moviegif/shot.php');
}
///////////////////////////////////////////////////////////////////////////////////////////////MOVIEGIF OPTIONAL END
