<?php
$db->execute("update " . table_prefix . "anomalien set
                                                       sicht_1='0',
                                                       sicht_2='0', 
                                                       sicht_3='0', 
                                                       sicht_4='0', 
                                                       sicht_5='0', 
                                                       sicht_6='0', 
                                                       sicht_7='0', 
                                                       sicht_8='0',
                                                       sicht_9='0', 
                                                       sicht_10='0'
                                                 where spiel='" . $spiel . "'");

$db->execute("update " . table_prefix . "sternenbasen set sicht_1='0', 
                                                          sicht_2='0', 
                                                          sicht_3='0', 
                                                          sicht_4='0', 
                                                          sicht_5='0', 
                                                          sicht_6='0', 
                                                          sicht_7='0', 
                                                          sicht_8='0', 
                                                          sicht_9='0', 
                                                          sicht_10='0'
                                         where spiel='" . $spiel . "'");

$db->execute("update " . table_prefix . "planeten set sicht_1='0', 
                                                      sicht_2='0', 
                                                      sicht_3='0', 
                                                      sicht_4='0', 
                                                      sicht_5='0', 
                                                      sicht_6='0', 
                                                      sicht_7='0', 
                                                      sicht_8='0', 
                                                      sicht_9='0', 
                                                      sicht_10='0'
                                         where spiel='" . $spiel . "'");
$db->execute("update " . table_prefix . "schiffe set sicht_1='0', 
                                                     sicht_2='0', 
                                                     sicht_3='0', 
                                                     sicht_4='0', 
                                                     sicht_5='0', 
                                                     sicht_6='0', 
                                                     sicht_7='0', 
                                                     sicht_8='0', 
                                                     sicht_9='0', 
                                                     sicht_10='0',
                                                     sicht_1_beta='0', 
                                                     sicht_2_beta='0', 
                                                     sicht_3_beta='0', 
                                                     sicht_4_beta='0', 
                                                     sicht_5_beta='0', 
                                                     sicht_6_beta='0', 
                                                     sicht_7_beta='0', 
                                                     sicht_8_beta='0', 
                                                     sicht_9_beta='0', 
                                                     sicht_10_beta='0'
                                         where spiel='" . $spiel . "'");
$scans = array();
$scanhash = array();
$scantelepat = array();
$sql90 = "SELECT id,spiel,y_pos,x_pos,besitzer,sternenbasis_art,sternenbasis,osys_1,osys_2,osys_3,osys_4,osys_5,osys_6,native_fert,native_kol,native_id FROM " . table_prefix . "planeten where spiel='".$spiel."'";
$array90_out = $db->getArray($sql90);
foreach ($array90_out as $array90) {
    $p_id = $array90['id'];
    $x_pos = $array90['x_pos'];
    $y_pos = $array90['y_pos'];
    $osys_1 = $array90['osys_1'];
    $osys_2 = $array90['osys_2'];
    $osys_3 = $array90['osys_3'];
    $osys_4 = $array90['osys_4'];
    $osys_5 = $array90['osys_5'];
    $osys_6 = $array90['osys_6'];
    $native_id = $array90['native_id'];
    $native_kol = $array90['native_kol'];
    $native_fert = $array90['native_fert'];
    $besitzer = $array90['besitzer'];
    $sternenbasis_art = $array90['sternenbasis_art'];
    $sternenbasis = $array90['sternenbasis'];
    $scanner_r = 53;
    if ($osys_1==13 or 
        $osys_2==13 or 
        $osys_3==13 or 
        $osys_4==13 or 
        $osys_5==13 or 
        $osys_6==13)
        { 
            $scanner_r=90;
        }
    if (($sternenbasis_art==3) and ($sternenbasis==2)) { $scanner_r=116;}
    if ($besitzer>=1) {
        //telepat prepare
        $native_fert_telepat = intval(substr($native_fert,31,1));
        if ((1 == $native_fert_telepat) && (0 < $native_kol)) {
            $scantelepat[$native_id][] = $besitzer;
        }
       
        $db->execute("INSERT INTO " . table_prefix . "scan (spiel,besitzer,x,y) values ('" . $spiel . "', '" . $besitzer . "', '" . $x_pos . "', '" . $y_pos . "')");
        $scans[] = array(
            'besitzer' => $besitzer,
            'x' => $x_pos,
            'y' => $y_pos
        );
        $scanhash[$besitzer.'_'.$x_pos.'_'.$y_pos] = $scanner_r;
        for ($f=1;$f<=10;$f++) {
            if ((isset($beziehung[$besitzer][$f]['status']) && (($beziehung[$besitzer][$f]['status']==4) or 
                      ($beziehung[$besitzer][$f]['status']==5))) or 
                ((isset($sicht_spionage[$besitzer][$f]) && $sicht_spionage[$besitzer][$f]==1) && $module[0])) {
                $db->execute("INSERT INTO " . table_prefix . "scan (spiel,besitzer,x,y) values ('" . $spiel . "', '" . $f . "', '" . $x_pos . "', '" . $y_pos . "')");
                $scans[] = array(
                    'besitzer' => $f,
                    'x' => $x_pos,
                    'y' => $y_pos
                );
                $scanhash[$f.'_'.$x_pos.'_'.$y_pos] = $scanner_r;
            }
        }
    }
}
//telepatic connection
$sql91 = "SELECT y_pos,x_pos,native_kol,native_id,besitzer FROM " . table_prefix . "planeten where spiel='" . $spiel . "' order by spiel";
$array91_out = $db->getArray($sql91);
foreach ($array91_out as $array91) {
    $x_pos = $array91['x_pos'];
    $y_pos = $array91['y_pos'];
    $native_id = $array91['native_id'];
    $native_kol = $array91['native_kol'];
    $besitzer = $array91['besitzer'];
    $scanner_r = 53;
    if ((isset($scantelepat[$native_id])) && (0 < $native_kol)) {
        foreach ($scantelepat[$native_id] as $owner) {
            $db->execute("INSERT INTO " . table_prefix . "scan (spiel,besitzer,x,y) values ('" . $spiel . "', '". $owner . "', '" . $x_pos . "', '" . $y_pos . "')");
            $scans[] = array(
                'besitzer' => $owner,
                'x' => $x_pos,
                'y' => $y_pos
            );
            $scanhash[$owner.'_'.$x_pos.'_'.$y_pos] = $scanner_r;
            for ($f=1;$f<=10;$f++) {
                if (($beziehung[$owner][$f]['status']==4) or ($beziehung[$owner][$f]['status']==5) or ((isset($sicht_spionage[$owner][$f]) && $sicht_spionage[$owner][$f]==1) && $module[0])) {
                    $db->execute("INSERT INTO " . table_prefix . "scan (spiel,besitzer,x,y) values ($spiel,$f,$x_pos,$y_pos)");
                    $scans[] = array(
                        'besitzer' => $f,
                        'x' => $x_pos,
                        'y' => $y_pos
                    );
                    $scanhash[$f.'_'.$x_pos.'_'.$y_pos] = $scanner_r;
                }
            }
        } 
    }
}
$sql92 = "SELECT id,spiel,kox,koy,besitzer,scanner FROM " . table_prefix . "schiffe where spiel='" . $spiel ."' order by spiel";
$sql92_out = $db->getArray($sql92);
foreach ($array92_out as $array92) {
    $s_id = $array92['id'];
    $kox = $array92['kox'];
    $koy = $array92['koy'];
    $scanner = $array92['scanner'];
    $besitzer = $array92['besitzer'];
    $scanner_r = 47;
    if ($scanner==1) { $scanner_r=85;}
    if ($scanner==2) { $scanner_r=116;}
    if ($besitzer>=1) {
        $scanhashindex = $besitzer.'_'.$kox.'_'.$koy;
        if (!array_key_exists($scanhashindex, $scanhash) or $scanner_r>$scanhash[$scanhashindex]) {
            $db->execute("INSERT INTO " . table_prefix . "scan (spiel,besitzer,x,y) values ('" . $spiel . "','" . $besitzer . "', '" . $kox . "', '" . $koy . "')");
            $scans[] = array(
                'besitzer' => $besitzer,
                'x' => $kox,
                'y' => $koy
            );
            $scanhash[$scanhashindex] = $scanner_r;
        }
        for ($f=1;$f<=10;$f++) {
            $scanhashindex = $f.'_'.$kox.'_'.$koy;
            if (($beziehung[$besitzer][$f]['status']==4) or 
                ($beziehung[$besitzer][$f]['status']==5) or 
                ($beziehung[$f][$besitzer]['status']==4) or 
                ($beziehung[$f][$besitzer]['status']==5) or 
                ((isset($sicht_spionage[$besitzer][$f]) && $sicht_spionage[$besitzer][$f]==1) && $module[0])) {
                if (!array_key_exists($scanhashindex, $scanhash) or $scanner_r>$scanhash[$scanhashindex]) {
                    $db->execute("INSERT INTO " . table_prefix . "scan (spiel,besitzer,x,y) values ('" . $spiel . "','" . $f . "', '" . $kox . "', '" . $koy . "')");
                    $scans[] = array(
                        'besitzer' => $f,
                        'x' => $kox,
                        'y' => $koy
                    );
                    $scanhash[$scanhashindex] = $scanner_r;
                }
            }
        }
    }
}
foreach ($scans as $scantupel) {
    $spalte = 'sicht_'.$scantupel['besitzer'];
    $spalte_beta = $spalte.'_beta';
    $xx = $scantupel['x'];
    $yy = $scantupel['y'];
    $chef = $scantupel['besitzer'];
    $scanhashindex = $chef.'_'.$xx.'_'.$yy;
    $scanreichweite = $scanhash[$scanhashindex];
    //kleiner hack, da in $scans für jedes schiff einer flotte ein eintrag ist
    if($scanreichweite > 0) {
        $scanhash[$scanhashindex] = 0;
        $db->execute("update " . table_prefix . "planeten set " . $spalte_beta . " = '1'
                                        where (
                                            (sqrt((x_pos-".$xx.")*(x_pos-".$xx.")+(y_pos-".$yy.")*(y_pos-".$yy.")))<=225
                                            ) and spiel='" . $spiel . "' and " . $spalte_beta . " = '0'");
        $db->execute("update " . table_prefix . "planeten set " . $spalte . " = '1'
                                        where (
                                            (sqrt((x_pos-".$xx.")*(x_pos-".$xx.")+(y_pos-".$yy.")*(y_pos-".$yy.")))<=125
                                            ) and spiel='" . $spiel ."' and " . $spalte . " = '0'");
        $db->execute("update " . table_prefix . "schiffe set " . $spalte . " = '1'
                                        where (
                                            (sqrt((kox-".$xx.")*(kox-".$xx.")+(koy-".$yy.")*(koy-".$yy.")))<=125
                                            ) and spiel='".$spiel."' and ".$spalte." = '0'");
        $db->execute("update " . table_prefix . "schiffe set ".$spalte_beta." = '1'
                                        where (
                                            (sqrt((kox-".$xx.")*(kox-".$xx.")+(koy-".$yy.")*(koy-".$yy.")))<=".$scanreichweite."
                                            ) and spiel='".$spiel."'");
        $db->execute("update " . table_prefix . "anomalien set ".$spalte." = '1'
                                        where (
                                            (sqrt((x_pos-".$xx.")*(x_pos-".$xx.")+(y_pos-".$yy.")*(y_pos-".$yy.")))<=125
                                            ) and spiel='".$spiel."' and ".$spalte." = '0' and (art!=5) ");
        $db->execute("update " . table_prefix . "anomalien set ".$spalte." = '1'
                                        where (
                                            (sqrt((x_pos-".$xx.")*(x_pos-".$xx.")+(y_pos-".$yy.")*(y_pos-".$yy.")))<=100
                                            ) and spiel='" . $spiel ."' and " .$spalte." = '0' and (art=5) ");
        $db->execute("update " . table_prefix . "sternenbasen set ".$spalte." = '1'
                                        where (
                                            (sqrt((x_pos-".$xx.")*(x_pos-".$xx.")+(y_pos-".$yy.")*(y_pos-".$yy.")))<=125
                                            ) and spiel='" . $spiel . "' and ". $spalte ." = '0'");
    }
}
//neue tarnungsarten:
for($i=1;$i<11;$i++){
    $spalte="sicht_".$i;
    $spalte_beta=$spalte."_beta";
    $sql93 = "SELECT id,kox,koy,besitzer,fertigkeiten FROM " . table_prefix . "schiffe where spiel='" . $spiel . "' and ".$spalte." = '1' and ".$spalte_beta." = '0' and tarnfeld='1' and besitzer<>".$i."";
    $rows93 = $db->execute($sql93);
    $schiffanzahl = $rows93->RecordCount();
    $array93_out = $db->getArray($sql93);
    foreach ($array93_out as $array93) {
        $s_id=$array93["id"];
        $kox=$array93["kox"];
        $koy=$array93["koy"];
        $besitzer=$array93["besitzer"];
        $fertigkeiten=$array93["fertigkeiten"];
        $tarnfeldgen=intval(substr($fertigkeiten,22,1));
        //normale Tarnung
        if($tarnfeldgen==1){
            $war=rand(1,10);
            if($war==10){
                $db->execute("update " . table_prefix . "schiffe set ".$spalte_beta." = '1' where id='".$s_id."' and spiel='".$spiel."'");
            }
        //klingonen tarnung
        }elseif($tarnfeldgen==3){
            $sql_temp1 = "SELECT count(id) as count FROM " . table_prefix . "schiffe use index (spiel,besitzer) where spiel='".$spiel."' and besitzer='".$i."' and (sqrt((kox-$kox)*(kox-$kox)+(koy-$koy)*(koy-$koy)))<=125";
            $anzahl_temp1 = $db->getOne($sql_temp);
            $sql_temp2 = "SELECT count(id) as count FROM " . table_prefix . "planeten use index (spiel,besitzer) where spiel='".$spiel."' and besitzer='".$i."' and (sqrt((x_pos-$kox)*(x_pos-$kox)+(y_pos-$koy)*(y_pos-$koy)))<=125";
            $anzahl_temp2 = $db->getOne($sql_temp2);
            $anzahl_temp = $anzahl_temp1+$anzahl_temp2;
            $war=rand(1,20);
            if($war<=(2+$anzahl_temp)){
                $db->execute("update " . table_prefix . "schiffe set ".$spalte_beta." = '1' where id='".$s_id."' and spiel='".$spiel."'");
            }
        }
    }
}
//begegnungen fuer modul wysiwyg
if ($module[4]==1) {
    $test = array();
    for($n=1;$n<11;$n++){
        if (isset($spieler_id_c[$n]) && $spieler_id_c[$n]>=1) {
            for ($m=1;$m<11;$m++){
                if (($spieler_id_c[$m]>=1) and ($n<>$m)) {
                    if ((!isset($begegnung[$n][$m])) and (!isset($test[$n][$m]))) {
                        $test[$n][$m] = 1;
                        $spalte='sicht_'.$n;
                        $totals=0;
                        $sql94 = "SELECT count(id) as total FROM " . table_prefix . "schiffe where spiel='".$spiel."' and ".$spalte." = '1' and besitzer='".$m."'";                        
                        $totals=$db->getOne($sql94);
                        $totalp=0;
                        $sql95 = "SELECT count(id) as total FROM " . table_prefix . "planeten where spiel='".$spiel."' and ".$spalte." = '1' and besitzer='".$m."'";                        
                        $totalp=$db->getOne($sql95);
                        if (($totals>=1) or ($totalp>=1)) {
                            $begegnung[$n][$m]=1;
                            $begegnung[$m][$n]=1;
                            $db->execute("INSERT INTO " . table_prefix . "begegnung (partei_a,partei_b,spiel) values ('".$n."','".$m."','".$spiel."')");
                            $db->execute("INSERT INTO " . table_prefix . "begegnung (partei_a,partei_b,spiel) values ('".$m."','".$n."','".$spiel."')");
                        }
                    }
                }
            }
        }
    }
}