{include file='header.tpl');
{foreach item="phrase" from=$languebersicht}
<body text="#000000" bgcolor="#444444" style="background-image:url('{$servername}images/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" height="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td><center><a href="{$servername}pages/uebersicht_uebersicht.php?fu=1&uid={$uid}&sid={$sid}" target="rahmen12" onclick="self.focus();"><img src="{$servername}images/menu/uebersicht.gif" width="75" height="75" border="0"><br><nobr>{$phrase.uebersicht}</nobr></a></center></td>
                    <td><center><a href="{$servername}pages/uebersicht_imperien.php?fu=1&uid={$uid}&sid={$sid}" target="rahmen12" onclick="self.focus();"><img src="{$servername}images/menu/imperien.gif" width="75" height="75" border="0"><br><nobr>{$phrase.imperien}</nobr></a></center></td>
                    {if $spieler_raus == 0}
                        <td><center><a href="{$servername}pages/uebersicht_neuigkeiten.php?fu=1&uid={$uid}&sid={$sid}" target="rahmen12" onclick="self.focus();"><img src="{$servername}images/menu/neuigkeiten.gif" width="75" height="75" border="0"><br><nobr>{$phrase.neuigkeiten}</nobr></a></center></td>
                        <td><center><a href="{$servername}pages/uebersicht_kolonien.php?fu=1&uid={$uid}&sid={$sid}" target="rahmen12" onclick="self.focus();"><img src="{$servername}images/planeten.gif" width="75" height="75" border="0"><br><nobr>{$phrase.kolonien}</nobr></a></center></td>
                    {/if}
                    <td><center><a href="{$servername}pages/uebersicht_konplaene.php?fu=1&uid={$uid}&sid={$sid}" target="rahmen12" onclick="self.focus();"><img src="{$servername}images/empty.gif" width="75" height="75" border="0"><br><nobr>{$phrasen.konplaene}</nobr></a></center></td>
                </tr>
            </table>
        </center>
{/foreach}
{include file='footer.tpl');
