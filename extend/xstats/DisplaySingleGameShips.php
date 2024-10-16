<?php
$gameId=$_GET["gameid"];
if( is_null($gameId)) {
    die("Parameter gameid erwartet.");
}
$xstatsGraphOutputType=$_GET["outputtype"];
if( is_null($xstatsGraphOutputType)) {
    $xstatsGraphOutputType = 'IMAGE';
}
if( !$xstatsGraphOutputType == 'IMAGE' && !$xstatsGraphOutputType == 'FLASH' ) {
    die( "outputtype muss eines von IMAGE, FLASH sein.");
}


//contains also the player color
include (dirname(__FILE__).'/../../inc.conf.php');
include (dirname(__FILE__).'/../../inhalt/inc.hilfsfunktionen.php');
open_db();

include ('xstatsUtil.php');
include ('DisplaySingleGameUtil.php');
include ('xstatsVersion.php');

$gameSize = xstats_getGameSize($gameId);
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
    <head>
        <title>Skrupel XStats - Schiffe</title>
        <meta name="language" content="de" />
        <meta http-equiv="Content-Language" content="de_DE" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta http-equiv="X-UA-Compatible" content="IE=7" />
        <link type="text/css" rel="stylesheet" href="xstats.css" media="screen" />
    </head>
    <body>

        <div id="logo">
            <?php echo( xstats_getVersionStr() ); ?>
            <img src="images/skrupel_logo.png" alt="" />
        </div>

        <div id="Tabs">
            <ul>
                <?php echo('<li><a href="DisplaySingleGame.php?gameid='.$gameId.'&outputtype='.$xstatsGraphOutputType.'"><span>Allgemeine &Uuml;bersicht</span></a></li>'); ?>
                <li class="active"><a href="#"><span>&Uuml;bersicht Schiffe</span></a></li>
                <?php echo('<li><a href="DisplaySingleGameShipVSShip.php?gameid='.$gameId.'&outputtype='.$xstatsGraphOutputType.'"><span>&Uuml;bersicht Raumk&auml;mpfe</span></a></li>'); ?>
            </ul>
        </div>
        <br class="clear" />

        <div class="borderedBoxWrap">
            <div class="borderedBox">
                <div class="borderedBoxInner">
                    <?php
                    //find out the name of the game
                    echo( '<H2>&Uuml;bersicht &uuml;ber die Schiffe</H2>');
                    echo( "<H2>Spiel: ".xstats_getGameName($gameId).' ('.$gameSize.'x'.$gameSize.')</H2>');
                    echo( "Spielziel: ".xstats_getGameType($gameId).'<br>');
                    if( xstats_gameIsFinished($gameId)) {
                        echo( "Spielende: ".xstats_getGameEnd($gameId).'<br>');
                        echo( "Anzahl der Runden: ".xstats_getMaxTurn($gameId).'<br>');
                        echo( "<br><br>");
                        xstats_displayMaxValueListShips($gameId);
                        echo( "<br><br>");
                        echo( "<H3>Schiffe nach Spielende</H3>");
                        xstats_displayShipMaxLJ($gameId, 10);
                        echo( "<br>");
                        xstats_displayShipsExpFreighter($gameId, 10);
                        echo( "<br>");
                        xstats_displayShipsExpBattleships($gameId, 10);
                        echo( "<br>");
                        xstats_displayFreigtherMostCargo($gameId, 10);
                        echo( "<br>");
                        xstats_displayShipProduction($gameId, 100);
                    }
                    ?>
                </div>
            </div>
        </div><!-- /end .borderedBoxWrap -->
        <div id="top" class="borderedBoxWrap thumbs">
            <div class="borderedBox">
                <div class="borderedBoxInner">
                    <?php
                    if( xstats_gameIsFinished($gameId)) {
                        $width = 252;
                        $height = 188;
                        $displayLegend = "FALSE";
                        $statsPerRow = 3;
                        $isThumb = "TRUE";
                        $graphArray = array( 
                                "lj", "shipcount", "all_shipcount", "freightercount", "all_freightercount", "fightercount",
                                "all_fightercount", "shipmasscount", "all_shipmasscount", 
                                "sumcargohold", "all_sumcargohold", "usedcargohold", "cargoholdpercent", "average_cargoholdpercent"
                        );
                        xstats_displayGraphsAsImage($gameId,$graphArray,$width,$height,$displayLegend,$statsPerRow,$isThumb);
                    }else {
                        echo( "Spiel ist noch nicht beendet, Stats nicht verf&uuml;gbar.");
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="borderedBoxWrap">
            <div class="borderedBox">
                <div class="borderedBoxInner">
                    <?php
                    if( xstats_gameIsFinished($gameId)) {
                        xstats_displayShipList($gameId);
                    }
                    ?>
                </div>
            </div>
        </div><!-- /end .borderedBoxWrap -->



        <?php
        if( xstats_gameIsFinished($gameId)) {
            $width = 800;
            $height = 600;
            $displayLegend = "TRUE";
            $statsPerRow = 1;
            $isThumb = "FALSE";
            $graphArray = array(
                                "lj", "shipcount", "all_shipcount", "freightercount", "all_freightercount", "fightercount",
                                "all_fightercount", "shipmasscount", "all_shipmasscount",
                                "sumcargohold", "all_sumcargohold", "usedcargohold", "cargoholdpercent", "average_cargoholdpercent"
                        );
            if( $xstatsGraphOutputType == 'IMAGE') {
                xstats_displayGraphsAsImage($gameId,$graphArray,$width,$height,$displayLegend,$statsPerRow,$isThumb);
            }else {
                xstats_displayGraphsAsFlash($gameId,$graphArray,$width,$height,$displayLegend,$statsPerRow,$isThumb);
            }
        }else {
            echo( "Spiel ist noch nicht beendet, Stats nicht verf&uuml;gbar.");
        }
        ?>
    </body>
</html>
