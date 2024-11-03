{include file='header.tpl'}
 <body onLoad="window.location='{$servername}zugende.php?fu=5&uid={$uid}&sid={$sid}';" text="#000000" bgcolor="#444444" style="background-image:url('{$servername}images/aufbau/14.gif'); background-attachment:fixed;"  link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" cellspacing="0" cellpadding="0" height="100%">
                <tr>
                    <td>
                        <center>
                        <img src="{$servername}images/radd.gif" height="46" width="51" border="0">
                            <br><br>
                            {$wirdberechnet}
                        </center>
                    </td>
                </tr>
            </table>
        </center>
{include file='footer.tpl'}
