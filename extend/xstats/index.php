<?php
include (dirname(__FILE__).'/../../inc.conf.php');
include (dirname(__FILE__).'/../../inhalt/inc.hilfsfunktionen.php');
open_db();
include ('xstatsUtil.php');
include ('xstatsVersion.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
    <head>
        <title>Skrupel XStats - Liste der Spiele</title>
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

        <div class="borderedBoxWrap">
            <div class="borderedBox">
                <div class="borderedBoxInner">
                    <ul class="listGames">
                        <?php
                        $result = @mysql_query("SELECT * FROM skrupel_spiele") or die(mysql_error());
                        $gameCount = 0;
                        while ($row = mysql_fetch_array($result)) {
                            echo( '<li>' );
                            $statsGameId = $row['id'];
                            $gameCount++;
                            $statsGameDisplay = '<strong>' . $row['name'] . '</strong>';
                            $statsAvailableIds = xstats_getAvailablePlayerIndicies($statsGameId);
                            $statsSize = $row['umfang'] . 'x' . $row['umfang'];
                            $statsGameDisplay = $statsGameDisplay . ' (' . count($statsAvailableIds) . ' Spieler, ' . $statsSize . ')';
                            if (xstats_gameIsFinished($statsGameId)) {
                                if (xstats_gameExistsInStats($statsGameId)) {
                                    echo( $statsGameDisplay . ' <a href="DisplaySingleGame.php?gameid=' . $statsGameId . '&outputtype=IMAGE" target="_new">Bildversion</a>' .
                                    ' <a href="DisplaySingleGame.php?gameid=' . $statsGameId . '&outputtype=FLASH" target="_new">Flashversion</a>');
                                }else{
                                    echo( $statsGameDisplay . ' (Keine Statistikaufzeichnungen verf&uuml;gbar)');
                                }
                            } else {
                                echo( $statsGameDisplay . "<i> (Keine Statistik verf&uuml;gbar, Spiel ist noch nicht beendet)</i>" );
                            }
                            echo( '</li>');
                        }
                        if ($gameCount == 0) {
                            echo( "Es ist leider kein Spiel verf&uuml;gbar.");
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div><!-- /end .borderedBoxWrap -->

    </body>
</html>