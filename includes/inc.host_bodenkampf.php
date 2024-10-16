<?php

$sql11_prep = "SELECT * FROM " . table_prefix . "planeten where kolonisten_spieler>=1 and ((kolonisten_new>=1) or (leichtebt_new>=1) or (schwerebt_new>=1)) and besitzer>=1 and spiel='" . $spiel . "' order by id";
$array11_out = $db->getArray($sql11_prep);
foreach ($array11_out as $array11) {    
    $pid=$array11["id"];
    $p_bild=$array11["bild"];
    $p_klasse=$array11["klasse"];
    $name=$array11["name"];
    $besitzer=$array11["besitzer"];
    $kolonisten=$array11["kolonisten"];
    $kolonisten_spieler=$array11["kolonisten_spieler"];
    $kolonisten_new=$array11["kolonisten_new"];
    $leichtebt=$array11["leichtebt"];
    $schwerebt=$array11["schwerebt"];
    $leichtebt_new=$array11["leichtebt_new"];
    $schwerebt_new=$array11["schwerebt_new"];
    $sternenbasis_id=$array11["sternenbasis_id"];
    $native_id=$array11["native_id"];
    $native_fert=$array11["native_fert"];
    $native_kol=$array11["native_kol"];
    $native_fert_kampf=intval(substr($native_fert,9,3))/100;
    $rasse=$spieler_rasse_c[$besitzer];
    $besitzer_stark=$r_eigenschaften[$rasse]['bodenverteidigung'];
    $rasse=$spieler_rasse_c[$kolonisten_spieler];
    $kolonisten_spieler_stark=$r_eigenschaften[$rasse]['bodenangriff'];
    if (($native_id>=1) and ($native_kol>1) and ($native_fert_kampf>0)) {
        $besitzer_stark=round(($besitzer_stark*$native_fert_kampf)+0.5);
    }
    //$verteidiger=$kolonisten*$besitzer_stark;
    //$angreifer=$kolonisten_new*$kolonisten_spieler_stark;
    /////////////////////////////////////////////////////////////////
    $verteidiger_st=$besitzer_stark;
    $angreifer_st=$kolonisten_spieler_stark;
    $verteidiger_kol=$kolonisten;
    $angreifer_kol=$kolonisten_new;
    $verteidiger_leichtebt=$leichtebt;
    $angreifer_leichtebt=$leichtebt_new;
    $verteidiger_schwerebt=$schwerebt;
    $angreifer_schwerebt=$schwerebt_new;
    ///////////////////SCHWER GEGEN SCHWER
    $angreifer_staerke=$angreifer_st*$angreifer_schwerebt*100;
    $verteidiger_staerke=$verteidiger_st*$verteidiger_schwerebt*80;
    if ($angreifer_staerke==$verteidiger_staerke) {
        $verteidiger_schwerebt=0;
        $angreifer_schwerebt=0;
    } else {
        if ($angreifer_staerke>$verteidiger_staerke) {
            $angreifer_schwerebt=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st*100));
            $verteidiger_schwerebt=0;
        } else {
            $verteidiger_schwerebt=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st*80));
            $angreifer_schwerebt=0;
        }
    }
    ///////////////////LEICHT GEGEN LEICHT
    $angreifer_staerke=$angreifer_st*$angreifer_leichtebt*16;
    $verteidiger_staerke=$verteidiger_st*$verteidiger_leichtebt*16;
    if ($angreifer_staerke==$verteidiger_staerke) {
        $verteidiger_leichtebt=0;
        $angreifer_leichtebt=0;
    } else {
        if ($angreifer_staerke>$verteidiger_staerke) {
            $angreifer_leichtebt=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st*16));
            $verteidiger_leichtebt=0;
        } else {
            $verteidiger_leichtebt=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st*16));
            $angreifer_leichtebt=0;
        }
    }
    ///////////////////LEICHT GEGEN SCHWER
    if (($angreifer_schwerebt>=1) and ($verteidiger_leichtebt>=1)) {
        $angreifer_staerke=$angreifer_st*$angreifer_schwerebt*100;
        $verteidiger_staerke=$verteidiger_st*$verteidiger_leichtebt*16;
        if ($angreifer_staerke==$verteidiger_staerke) {
            $verteidiger_leichtebt=0;
            $angreifer_schwerebt=0;
        } else {
            if ($angreifer_staerke>$verteidiger_staerke) {
                $angreifer_schwerebt=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st*100));
                $verteidiger_leichtebt=0;
            } else {
                $verteidiger_leichtebt=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st*16));
                $angreifer_schwerebt=0;
            }
        }
    }
    if (($verteidiger_schwerebt>=1) and ($angreifer_leichtebt>=1)) {
        $verteidiger_staerke=$verteidiger_st*$verteidiger_schwerebt*80;
        $angreifer_staerke=$angreifer_st*$angreifer_leichtebt*16;
        if ($angreifer_staerke==$verteidiger_staerke) {
            $verteidiger_schwerebt=0;
            $angreifer_leichtebt=0;
        } else {
            if ($angreifer_staerke>$verteidiger_staerke) {
                $angreifer_leichtebt=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st*16));
                $verteidiger_schwerebt=0;
            } else {
                $verteidiger_schwerebt=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st*80));
                $angreifer_leichtebt=0;
            }
        }
    }
    ///////////////////KOLS GEGEN LEICHTSCHWER
    if (($angreifer_schwerebt>=1) and ($verteidiger_kol>=1)) {
        $angreifer_staerke=$angreifer_st*$angreifer_schwerebt*100;
        $verteidiger_staerke=$verteidiger_st*$verteidiger_kol;
        if ($angreifer_staerke==$verteidiger_staerke) {
            $verteidiger_kol=0;
            $angreifer_schwerebt=0;
        } else {
            if ($angreifer_staerke>$verteidiger_staerke) {
                $angreifer_schwerebt=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st*100));
                $verteidiger_kol=0;
            } else {
                $verteidiger_kol=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st));
                $angreifer_schwerebt=0;
            }
        }
    }
    if (($verteidiger_schwerebt>=1) and ($angreifer_kol>=1)) {
        $verteidiger_staerke=$verteidiger_st*$verteidiger_schwerebt*80;
        $angreifer_staerke=$angreifer_st*$angreifer_kol;
        if ($angreifer_staerke==$verteidiger_staerke) {
            $verteidiger_schwerebt=0;
            $angreifer_kol=0;
        } else {
            if ($angreifer_staerke>$verteidiger_staerke) {
                $angreifer_kol=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st));
                $verteidiger_schwerebt=0;
            } else {
                $verteidiger_schwerebt=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st*80));
                $angreifer_kol=0;
            }
        }
    }
    if (($angreifer_leichtebt>=1) and ($verteidiger_kol>=1)) {
        $angreifer_staerke=$angreifer_st*$angreifer_leichtebt*16;
        $verteidiger_staerke=$verteidiger_st*$verteidiger_kol;
        if ($angreifer_staerke==$verteidiger_staerke) {
            $verteidiger_kol=0;
            $angreifer_leichtebt=0;
        } else {
            if ($angreifer_staerke>$verteidiger_staerke) {
                $angreifer_leichtebt=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st*16));
                $verteidiger_kol=0;
            } else {
                $verteidiger_kol=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st));
                $angreifer_leichtebt=0;
            }
        }
    }
    if (($verteidiger_leichtebt>=1) and ($angreifer_kol>=1)) {
        $verteidiger_staerke=$verteidiger_st*$verteidiger_leichtebt*16;
        $angreifer_staerke=$angreifer_st*$angreifer_kol;
        if ($angreifer_staerke==$verteidiger_staerke) {
            $verteidiger_leichtebt=0;
            $angreifer_kol=0;
        } else {
            if ($angreifer_staerke>$verteidiger_staerke) {
                $angreifer_kol=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st));
                $verteidiger_leichtebt=0;
            } else {
                $verteidiger_leichtebt=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st*16));
                $angreifer_kol=0;
            }
        }
    }
    if (($angreifer_kol>=1) and ($verteidiger_kol>=1)) {
        $angreifer_staerke=$angreifer_st*$angreifer_kol;
        $verteidiger_staerke=$verteidiger_st*$verteidiger_kol;
        if ($angreifer_staerke==$verteidiger_staerke) {
            $verteidiger_kol=0;
            $angreifer_kol=0;
        } else {
            if ($angreifer_staerke>$verteidiger_staerke) {
                $angreifer_kol=round(($angreifer_staerke-$verteidiger_staerke)/($angreifer_st));
                $verteidiger_kol=0;
            } else {
                $verteidiger_kol=round(($verteidiger_staerke-$angreifer_staerke)/($verteidiger_st));
                $angreifer_kol=0;
            }
        }
    }
    if (($verteidiger_kol>=1) or ($verteidiger_leichtebt>=1) or ($verteidiger_schwerebt>=1)) {
        $planetenerobertfehl++;
        $db->execute("UPDATE " . table_prefix . "planeten set kolonisten='".$verteidiger_kol."',
                                                              leichtebt='".$verteidiger_leichtebt."', 
                                                              schwerebt='".$verteidiger_schwerebt."', 
                                                              kolonisten_spieler='0', 
                                                              kolonisten_new='0', 
                                                              leichtebt_new='0', 
                                                              schwerebt_new='0' 
                                                              where id='".$pid."'");
        $datum=time();
        neuigkeiten(1,servername . "images/planeten/$p_klasse"."_"."$p_bild.jpg",$besitzer,$lang['hostbodenkampf']['btruppen1'],array($name));
        neuigkeiten(1,servername . "images/planeten/$p_klasse"."_"."$p_bild.jpg",$kolonisten_spieler,$lang['hostbodenkampf']['btruppen2'],array($name));
    }
    if (($angreifer_kol>=1) or ($angreifer_leichtebt>=1) or ($angreifer_schwerebt>=1)) {
        $planetenerobert++;
        $db->execute("UPDATE " . table_prefix . "planeten set besitzer='".$kolonisten_spieler."', 
                                                              kolonisten='".$angreifer_kol."', 
                                                              leichtebt='".$angreifer_leichtebt."', 
                                                              schwerebt='".$angreifer_schwerebt."', 
                                                              kolonisten_spieler='0', 
                                                              kolonisten_new='0', 
                                                              leichtebt_new='0', 
                                                              schwerebt_new='0' 
                                                              where id='".$pid."'");
        if ($sternenbasis_id>=1) { 
                                    $db->execute("UPDATE " . table_prefix . "sternenbasen set besitzer='".$kolonisten_spieler."' where id='".$sternenbasis_id."'");                                     
        }
        $datum=time();
        $stat_kol_erobert[$kolonisten_spieler]++;
        neuigkeiten(1,servername . "images/planeten/$p_klasse"."_"."$p_bild.jpg",$besitzer,$lang['hostbodenkampf']['btruppen3'],array($name));
        neuigkeiten(1,servername . "images/planeten/$p_klasse"."_"."$p_bild.jpg",$kolonisten_spieler,$lang['hostbodenkampf']['btruppen4'],array($name));
    }
    if (($angreifer_kol==0) and ($angreifer_leichtebt==0) and ($angreifer_schwerebt==0) and ($verteidiger_kol==0) and ($verteidiger_leichtebt==0) and ($verteidiger_schwerebt==0)) {
        $datum=time();
        neuigkeiten(1,servername . "images/planeten/$p_klasse"."_"."$p_bild.jpg",$besitzer,$lang['hostbodenkampf']['btruppen3'],array($name));
        neuigkeiten(1,servername . "images/planeten/$p_klasse"."_"."$p_bild.jpg",$kolonisten_spieler,$lang['hostbodenkampf']['btruppen2'],array($name));
        $zeiger_temp = $db->execute("UPDATE " . table_prefix . "planeten set leichtebt='0', 
                                                                             schwerebt='0', 
                                                                             kolonisten='0', 
                                                                             besitzer='0', 
                                                                             auto_minen='0', 
                                                                             auto_fabriken='0', 
                                                                             abwehr='0', 
                                                                             auto_abwehr='0', 
                                                                             auto_vorrat='0', 
                                                                             logbuch='' where id='".$pid."'");
        if ($sternenbasis_id>=1) {
            $zeiger_temp = $db->execute("UPDATE " . table_prefix . "sternenbasen set besitzer='0' where id='".$sternenbasis_id."'");
        }
    }
    ////////////////////////////////////////////////////////////////
}
/*
 * Sämtliche Datenbank Querys angepasst und Sprachvariablen aus der Datenbank integriert.
 */