{include file='header.tpl'}
<script language="JavaScript">
        function link(url) {
            if (parent.mittelinksoben.document.globals.map.value==1) {
                parent.mittelinksoben.document.globals.map.value=0;
                parent.mittemitte.window.location='{$servername}pages/aufbau.php?fu=100&query='+url;
            } else  {
                parent.mittemitte.rahmen12.window.location=url;
            }
        }
        function redir() {
            link('{$servername}pages/uebersicht/uebersicht_uebersicht.php?fu=1&uid={$uid}&sid={$sid}');
            window.location='{$servername}pages/uebersicht/uebersicht.php?fu=1&uid={$uid}&sid={$sid}';
        }
    </script>
        <body onload="javascript:redir();" text="#000000" bgcolor="#444444" style="background-image:url('{$servername}images/aufbau/14.gif'); background-attachment:fixed;" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" height="100%" cellspacing="0" cellpadding="0">
                <tr>
                <td><nobr><center>{$wurdenausgewertet}</center></nobr></td>
                </tr>
            </table>
        </center>
{include file='footer.tpl'}