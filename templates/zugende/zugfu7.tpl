{include file='header.tpl'}
<body text="#ffffff" style="background-image:url('{$servername}images/aufbau/14.gif'); background-attachment:fixed;" bgcolor="#000000" link="#ffffff" vlink="#ffffff" alink="#ffffff" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <center>
            <table border="0" cellspacing="0" cellpadding="1">
                <tr>
                    <td colspan="3"><img src="{$servername}images/empty.gif" border="0" width="1" height="4"></td>
                </tr>
                <tr>
                    <td><img src="{$servername}images/empty.gif" border="0" width="17" height="17"></td>
                    <td><center>{$galaxiesprung}</center></td>
                    <td><a href="javascript:hilfe();"><img src="{$servername}images/icons/hilfe.gif" border="0" width="17" height="17"></a></td>
                </tr>
            </table>
        </center>
        <center>
            <table border="0" cellspacing="0" cellpadding="3">
                <tr>
                    <td colspan="3"><img src={$servername}images/empty.gif" border="0" width="1" height="1"></td>
                </tr>
                <tr>
                    <td><form name="formular" method="post" action="{$servername}zugende.php?fu=8&uid={$uid}&sid={$sid}"></td>
                    <td><center>{$sprungwohin}</center></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <center>{$selneuesspiel}</center>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3"><img src="{$servername}images/empty.gif" border="0" width="1" height="1"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><center><input type="submit" name="bla" value="{$sprungdurchfuehren}" style="width:250px;"></center></td>
                    <td></form></td>
                </tr>
            </table>
        </center>

{include file='footer.tpl'}
