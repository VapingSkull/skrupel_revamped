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
        <title>Skrupel XStats - Allgemein</title>
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
            <img src="images/skrupel_logo.png" alt="xstats"/>            
        </div>        
        <div id="Tabs">
            <ul>
                <li class="active"><a href="#"><span>Allgemeine &Uuml;bersicht</span></a></li>
                <?php echo('<li><a href="DisplaySingleGameShips.php?gameid='.$gameId.'&outputtype='.$xstatsGraphOutputType.'"><span>&Uuml;bersicht Schiffe</span></a></li>'); ?>
                <?php echo('<li><a href="DisplaySingleGameShipVSShip.php?gameid='.$gameId.'&outputtype='.$xstatsGraphOutputType.'"><span>&Uuml;bersicht Raumk&auml;mpfe</span></a></li>'); ?>
            </ul>
        </div>
        <br class="clear" />

        <div class="borderedBoxWrap">
            <div class="borderedBox">
                <div class="borderedBoxInner">
                    <?php                    
                    //find out the name of the game
                    echo( '<H2>Allgemeine &Uuml;bersicht</H2>');
                    echo( "<H2>Spiel: ".xstats_getGameName($gameId).' ('.$gameSize.'x'.$gameSize.')</H2>');
                    echo( "Ziel: ".xstats_getGameType($gameId).'<br>');
                    echo( "Start: ".xstats_getGameStart($gameId).'<br>');
                    echo( "Ende: ".xstats_getGameEnd($gameId).'<br>');
                    echo( "Dauer: ".xstats_getGameDuration($gameId).'<br>');
                    echo( "Runden: ".xstats_getMaxTurn($gameId).'<br>');
                    xstats_displayMovieGif( $gameId );
                    if( xstats_gameIsFinished($gameId)) {
                        echo( "<br><br>");
                        xstats_displayRanking($gameId);
                        echo( "<br><br>");
                        xstats_displayMaxValueListMisc($gameId);
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
                        $graphArray = array( "rank",
                                "coloniestakencount", "planetcount",
                                "planetcountpercent", "colonistcount", "stationcount", "stationcountstd",
                                "stationcountrep","stationcountdef","stationcountstdextra","lemincount",
                                "min1count", "min2count", "min3count", "cantoxcount", "factorycount",
                                "boxcount", "minescount",
                        );
                        xstats_displayGraphsAsImage($gameId,$graphArray,$width,$height,$displayLegend,$statsPerRow,$isThumb);
                        echo( "<br>");
                        xstats_displayTurnList( $gameId );
                    }else {
                        echo( "Spiel ist noch nicht beendet, XStats nicht verf&uuml;gbar.");
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
        if( xstats_gameIsFinished($gameId)) {
            $width = 800;
            $height = 600;
            $displayLegend = "TRUE";
            $statsPerRow = 1;
            $isThumb = "FALSE";
            $graphArray = array( "rank",
                    "coloniestakencount", "planetcount",
                    "planetcountpercent", "colonistcount", "stationcount", "stationcountstd",
                    "stationcountrep","stationcountdef","stationcountstdextra","lemincount",
                    "min1count", "min2count", "min3count", "cantoxcount", "factorycount",
                    "boxcount", "minescount",
            );
            if( $xstatsGraphOutputType == 'IMAGE') {
                xstats_displayGraphsAsImage($gameId,$graphArray,$width,$height,$displayLegend,$statsPerRow,$isThumb);
            }else {
                xstats_displayGraphsAsFlash($gameId,$graphArray,$width,$height,$displayLegend,$statsPerRow,$isThumb);
            }
        }else {
            echo( "Spiel ist noch nicht beendet, XStats nicht verf&uuml;gbar.");
        }
        ?>

    </body>
</html>
