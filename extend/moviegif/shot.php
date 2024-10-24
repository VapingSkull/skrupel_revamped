<?php

$moviegif_files_path = dirname(__FILE__).'/../../files/moviegif/';

$bild_position_x = 0;
$bild_position_y = 0;

$hoehe = 350;
$breite = 350;

$hoehe_komplett = 375;
$breite_komplett = 350;

////////vorbereitungen

$bild = imagecreate($breite_komplett,$hoehe_komplett);
$hintergrundbild = ImageCreateFromGIF(dirname(__FILE__).'/img/map350x375.gif');
$scanbild = ImageCreateFromGIF(dirname(__FILE__).'/img/scan.gif');

$color['background'] = imagecolorallocate($bild, 44, 44, 44);
$color['linien'] = imagecolorallocate($bild, 30, 30, 30);

$color['grey']  = imagecolorallocate($bild, 69, 69, 69);
$color['black']  = imagecolorallocate($bild, 0, 0, 0);
$color['blue']  = imagecolorallocate($bild, 0, 0, 255);
$color['white']  = imagecolorallocate($bild, 255, 255, 255);

$color['spieler'][0] = imagecolorallocate($bild, 170, 170, 170);
$color['spieler'][1] = imagecolorallocate($bild, 29, 199, 16);
$color['spieler'][2] = imagecolorallocate($bild, 229, 226, 3);
$color['spieler'][3] = imagecolorallocate($bild, 234, 165, 0);
$color['spieler'][4] = imagecolorallocate($bild, 135, 95, 0);
$color['spieler'][5] = imagecolorallocate($bild, 187, 0, 0);
$color['spieler'][6] = imagecolorallocate($bild, 215, 0, 193);
$color['spieler'][7] = imagecolorallocate($bild, 125, 16, 199);
$color['spieler'][8] = imagecolorallocate($bild, 16, 29, 199);
$color['spieler'][9] = imagecolorallocate($bild, 4, 158, 239);
$color['spieler'][10] = imagecolorallocate($bild, 16, 199, 155);

////////hintergrund einfuegen

Imagecopy($bild,$hintergrundbild,0,0,0,0,$breite_komplett,$hoehe_komplett);

////////aktuelle karte einfuegen

$sql600 = "SELECT * FROM skrupel_spiele use index (PRIMARY) where id='".$spiel."'";
$rows600 = $db->execute($sql600);
$datensaetze = $rows600->RecordCount();

if ($datensaetze==1) {
	$array = $db->getRow($sql600);
	$umfang=$array['umfang'];
	$spiel_runde=$array['runde'];
	$spiel_name=$array['name'];
}

////////linien zeichnen

$sektoranzahl=round($umfang/250)-1;

for ($n=1;$n<=$sektoranzahl;$n++) {
	$x=(250*$n)/$umfang*$breite;
	$y=(250*$n)/$umfang*$hoehe;
		imageline ($bild, $x, 0, $x, $hoehe-1, $color['linien']);
		imageline ($bild, 0, $y, $breite-1, $y, $color['linien']);
}

////////schiffe und planeten ziehen

$zeiger_planeten = "SELECT id,x_pos,y_pos,besitzer,sternenbasis FROM " . table_prefix ."planeten use index (spiel) where spiel='".$spiel."' order by id";
$rows_planeten = $db->execute($zeiger_planeten);
$datensaetze_planeten = $rows_planeten->RecordCount();

$zeiger_schiffe = "SELECT volk,bild_klein,masse,kox_old,koy_old,klasse,schaden,antrieb,frachtraum,fracht_leute,fracht_cantox,fracht_vorrat,fracht_min1,fracht_min2,fracht_min3,lemin,leminmax,logbuch,routing_status,routing_id,routing_koord,besitzer,id,name,kox,koy,flug,zielx,ziely,zielid,techlevel,masse_gesamt,status,spezialmission,tarnfeld,extra FROM " . table_prefix . "schiffe use index (status,spiel) where status>0 and spiel='".$spiel."' order by masse desc";
$rows_schiffe = $db->execute($zeiger_schiffe);
$datensaetze_schiffe = $rows_schiffe->RecordCount();

////////scankreise

if ($datensaetze_planeten>=1) {
        $array_planeten = $rows_planet->getArray();
	foreach ($array_planeten as $array) {    
      $id=$array["id"];
      $x_pos=$array["x_pos"];
      $y_pos=$array["y_pos"];
      $besitzer=$array["besitzer"];
	  $x_position=round($x_pos/$umfang*$breite);
      $y_position=round($y_pos/$umfang*$hoehe);

		if ($besitzer>=1) {
			Imagecopy($bild,$scanbild,$x_position-12,$y_position-12,0,0,25,25);
		}

	}
}

if ($datensaetze_schiffe>=1) {
    $array_schiffe = $rows_schiffe->getArray();
	foreach ($array_schiffe as $array) {    
      $id=$array["id"];
      $x_pos=$array["kox"];
      $y_pos=$array["koy"];
      $besitzer=$array["besitzer"];
	  $x_position=round($x_pos/$umfang*$breite);
      $y_position=round($y_pos/$umfang*$hoehe);
		if ($besitzer>=1) {
			Imagecopy($bild,$scanbild,$x_position-12,$y_position-12,0,0,25,25);
		}
}
                }

////wurmloecher etc

$zeiger_anomalie = @mysql_query("SELECT * FROM " . table_prefix . "anomalien use index (spiel) where spiel='".$spiel."' order by id");
$rows_anomalie = $db->ewxecute($zeiger_anomalie);
$datensaetze_anomalie = $rows_anomalie->RecordCount();

if ($datensaetze_anomalie>=1) {
        $array_anomalie = $rows_anomalie->getArray();
	foreach  ($array_anomalie as $array) {    
      $aid=$array["id"];
      $art=$array["art"];
      $x_pos=$array["x_pos"];
      $y_pos=$array["y_pos"];
      $extra=$array["extra"];
	  $x_position=round($x_pos/$umfang*$breite);
      $y_position=round($y_pos/$umfang*$hoehe);
	if (($art==1) or ($art==2)) {
		imagesetpixel($bild,$x_position,$y_position,$color['white']);
		imagesetpixel($bild,$x_position+1,$y_position+1,$color['blue']);
		imagesetpixel($bild,$x_position-1,$y_position-1,$color['blue']);
		imagesetpixel($bild,$x_position-1,$y_position+1,$color['blue']);
		imagesetpixel($bild,$x_position+1,$y_position-1,$color['blue']);
	}

	if ($art==3) {
		imagesetpixel($bild,$x_position,$y_position,$color['white']);
	}
}
        }

////planeten

if ($datensaetze_planeten>=1) {
    $array_planeten = $rows_planeten->getArray();
	foreach ($array_planeten as $array) {    
      $id=$array["id"];
      $x_pos=$array["x_pos"];
      $y_pos=$array["y_pos"];
      $besitzer=$array["besitzer"];
      $sternenbasis=$array["sternenbasis"];
      $x_position=round($x_pos/$umfang*$breite);
      $y_position=round($y_pos/$umfang*$hoehe);
	  imagesetpixel($bild,$x_position,$y_position,$color['spieler'][$besitzer]);
	if ($besitzer>=1) {
	  imagesetpixel($bild,$x_position-1,$y_position,$color['spieler'][$besitzer]);
	  imagesetpixel($bild,$x_position+1,$y_position,$color['spieler'][$besitzer]);
	  imagesetpixel($bild,$x_position,$y_position-1,$color['spieler'][$besitzer]);
	  imagesetpixel($bild,$x_position,$y_position+1,$color['spieler'][$besitzer]);
	if ($sternenbasis==2) {
		imagesetpixel($bild,$x_position,$y_position-3,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position,$y_position-2,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position,$y_position+2,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position,$y_position+3,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position-3,$y_position,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position-2,$y_position,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position+2,$y_position,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position+3,$y_position,$color['spieler'][$besitzer]);
	}        
        }
	}
}

////schiffe

if ($datensaetze_schiffe>=1) {
    $array_schiffe = $rows_schiffe->getArray();
    foreach ($array_schiffe as $array) {
      $id=$array["id"];
      $x_pos=$array["kox"];
      $y_pos=$array["koy"];
      $besitzer=$array["besitzer"];
      $status=$array["status"];
      $x_position=round($x_pos/$umfang*$breite);
      $y_position=round($y_pos/$umfang*$hoehe);
	if ($status==2)  {
		imagesetpixel($bild,$x_position-1,$y_position-1,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position-1,$y_position+1,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position+1,$y_position-1,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position+1,$y_position+1,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position,$y_position-2,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position,$y_position+2,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position-2,$y_position,$color['spieler'][$besitzer]);
		imagesetpixel($bild,$x_position+2,$y_position,$color['spieler'][$besitzer]);
	} else {
		imagesetpixel($bild,$x_position,$y_position,$color['spieler'][$besitzer]);
	}

}}

//////////infos

ImageString($bild,2,10,356,$spiel_name,$color['white']);
ImageString($bild,2,287,356,'Round '.sprintf("%03d",$spiel_runde),$color['white']);

////////schreiben der datei

$runde_anzeige = sprintf("%04d", $spiel_runde);

$scenes_dir = $moviegif_files_path . 'temp/' . $spiel . '/';
if (!file_exists($scenes_dir)) {
    mkdir($scenes_dir, 0777, true);
}
$scene_file = $scenes_dir . 'scene_' . $runde_anzeige . '.gif';
@ImageGif($bild, $scene_file);
@chmod($scene_file, 0777);
////////ende
ImageDestroy($bild);
ImageDestroy($hintergrundbild);
ImageDestroy($scanbild);
