{include file='header.tpl'}
<body text="#000000" bgcolor="#444444" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <script language=JavaScript>parent.document.title='{$ueberschrift}';</script>
        <center>
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><img src="{$servername}images/empty.gif" border="0" width="1" height="5"></td>
                </tr>
                <tr>
                    <td style="font-size:12px;"><b>{$ueberschrift}</b></td>
                </tr>
                <tr>
                    <td><img src="{$servername}images/empty.gif" border="0" width="1" height="7"></td>
                </tr>
            </table>
        </center>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="color:#aaaaaa;">
                {$text}
                {if $fuid == 1}{$fu1html}{/if}
                {if $fuid == 2}{$fu2html}{/if}
                {if $fuid == 3}{$fu3html}{/if}
                {if $fuid == 4}{$fu4html}{/if}
                {if $fuid == 5}{$fu5html}{/if}
                {if $fuid == 6}{$fu6html}{/if}
                {if $fuid == 8}{$fu8html}{/if}
                {if $fuid == 10}{$fu10html}{/if}
                {if $fuid == 12}{$fu12html}{/if}
                {if $fuid == 16}{$fu16html}{/if}
                {if $fuid == 33}{$fu33html}{/if}
                {if $fuid == 9999}{$fu9999html}{/if}
                
                </td>
            </tr>
        </table>

{include file='footer.tpl'}