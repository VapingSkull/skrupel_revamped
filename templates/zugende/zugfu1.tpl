{include file='header.tpl'}
<body text="#000000" bgcolor="#444444" style="background-image:url('{$servername}images/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <center>
        <table border="0" height="100%" cellspacing="0" cellpadding="0">
            <tr>
            {if $weitere > 0}                
                <td>
                    <center><a href="{$servername}zugende.php?fu=7&uid={$uid}&sid={$sid}" target="_self"><img src="{$servername}images/menu/gsprung.gif" width="75" height="75" border="0"><br><nobr>{$galaxiesprung}</nobr></a></center>
                </td>
            {/if}
                <td>
                    <center><a href="{$servername}zugende.php?fu=2&uid={$uid}&sid={$sid}" target="_top"><img src="{$servername}images/menu/logout.gif" width="75" height="75" border="0"><br><nobr>{$logout}</nobr></a></center>
                </td>
            {if $zug_abgeschlossen == 0 and $spieler_raus ==0}
                <td>
                    <center><a href="{$servername}ugende.php?fu=3&uid={$uid}&sid={$sid}" target="_self"><img src="{$servername}images/menu/abschliessen.gif" width="75" height="75" border="0"><br><nobr>{$zugabschliessen}</nobr></a></center>
                </td>
            {/if}
                </tr>
            </table>
        </center>
{include file='footer.tpl'}