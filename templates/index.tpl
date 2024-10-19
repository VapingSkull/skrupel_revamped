{foreach item=lang from=$lang_out}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Skrupel - Tribute Compilation V{$spiel_version} optimized by SkullCollector</title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
        <link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon">
         <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />         
        <meta name="Author" content="Bernd Kantoks bernd@kantoks.de">
        <meta name="Author" content="SkullCollector">
        <meta name="robots" content="index">
        <meta name="keywords" content="Skrupel,Online Browsergame">
        
        <style type="text/css">
            
            html,body,p,td {
            height: 100%; 
            margin: 0;
            font-family: Verdana;
            font-size: 10px;
            color: #ffffff;
            scrollbar-darkshadow-color: #444444;
            scrollbar-3dlight-color: #444444;
            scrollbar-track-color: #444444;
            scrollbar-face-color: #555555;
            scrollbar-shadow-color: #222222;
            scrollbar-highlight-color: #888888;
            scrollbar-arrow-color: #555555;
            
          }
          
 

#top {
  float: left; 
  width: 1px; height: 50%;
  margin-bottom: -12em;
}

#container {
  clear: left;
  margin: 0 auto;
  width: 32em; height: 24em;
  background-color: #E0FFE0;
}
  
          a {
            color: #aaaaaa;
            font-weight: bold;
            text-decoration: underline;
          }
          a:hover {
            font-weight: bold;
            text-decoration: underline;
            color: #ffffff;
          }
          input,select {
            background-color: #555555;
            color: #ffffff;
            border-bottom-color: #222222;
            border-left-color: #888888;
            border-right-color: #222222;
            border-top-color: #888888;
            border-style: solid;
            border-width: 1px;
            font-family: Verdana;
            font-size: 10px;
          }
          input.eingabe {
            background-color: #555555;
            color: #ffffff;
            border-bottom-color: #888888;
            border-left-color: #222222;
            border-right-color: #888888;
            border-top-color: #222222;
            border-style: solid;
            border-width: 1px;
            font-family: Verdana;
            font-size: 10px;
          }
          textarea {
            background-color: #555555;
            color: #ffffff;
            border-bottom-color: #888888;
            border-left-color: #222222;
            border-right-color: #888888;
            border-top-color: #222222;
            border-style: solid;
            border-width: 1px;
            font-family: Verdana;
            font-size: 10px;
          }
        </style>
        
</head>    
<body text="#000000" bgcolor="#000000" scroll="no" background="{$image_dir}hintergrund.gif" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <div class="top container">
        <center>{$fehler}</center>
          <table  border="0" height="100%" cellspacing="0" cellpadding="0">
              <tr>
              <td>
                <table border="0" cellspacing="0" cellpadding="0" background="{$image_dir}login.gif">
                  <tr>
                    <td><img src="{$image_dir}empty.gif" border="0" width="1" height="1"></td>
                    <td><img src="{$image_dir}empty.gif" border="0" width="628" height="1"></td>
                    <td><img src="{$image_dir}empty.gif" border="0" width="1" height="1"></td>
                  </tr>
                  <tr>
                    <td><img src="{$image_dir}empty.gif" border="0" width="1" height="347"></td>
                    <td valign="top">
                      <center>
                        <img src="{$image_dir}empty.gif" border="0" width="1" height="30">
                        <br>
                        <img src="{$image_dir}logo_login.gif" width="329" height="208">
                        <br>
                        <table border="0" cellspacing="0" cellpadding="4">
                            <tr>
                                 <td>                               
                               		<form action="{$servername}index.php?sprache=de" method="post" name="formular">
                                 </td>
                               	<td align="right">{$lang.benutzername}&nbsp;</td>
                               <td><input type="text" name="login_f" class="eingabe" maxlength="50" style="width:350px;" value=""></td>
                              <td>
                                &nbsp;
                                </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td align="right">{$lang.passwort}&nbsp;</td>
                              <td><input type="password" name="passwort_f" class="eingabe" maxlength="50" style="width:350px;" value=""></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td></td>
                              <td align="right">{$lang.spiel}&nbsp;</td>
                              <td>
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <td>
                                    <select name="spiel_slot" style="width:250px;">
                                      <option value="0" style="background-color:#444444;">{$lang.spielwaehlen}</option>
                                      {$option}
                                      </select>
                                  </td>
                                  <td><img src="{$image_dir}empty.gif" border="0" width="8" height="1"></td>
                                  <td align="right">&nbsp;</td>
                                  <td><img src="{$image_dir}empty.gif" border="0" width="7" height="1"></td>
                                      <td><input type="submit" name="submit" value="{$lang.login}" style="width:65px;"></td>
                                </table>
                              </td>
                              <td></form></td>
                            </tr>
                            <tr> <b>{$sversion}</b></tr>
                          </table>
                               </center>
                    </td>
                    <td><img src="{$image_dir}empty.gif" border="0" width="1" height="347"></td>
                  </tr>
                  <tr>
                    <td><img src="{$image_dir}empty.gif" border="0" width="1" height="1"></td>
                    <td><img src="{$image_dir}empty.gif" border="0" width="628" height="1"></td>
                    <td><img src="{$image_dir}empty.gif" border="0" width="1" height="1"></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
    </div>               
        
           </body>
    </html>
    {/foreach}