<?php
require_once ('../inc.conf.php'); 
require_once ('../inhalt/inc.hilfsfunktionen.php');
include ("../lang/".$language."/lang.admin.mitspieler_alpha.php");
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST), filter_struct_utf8(1, $_REQUEST));
$fuid = intval($params["fu"]);

if ($fuid==1) {
include ("inc.header.php");
if (($ftploginname==$admin_login) and ($ftploginpass==$admin_pass)) {
?>
<body text="#ffffff" bgcolor="#444444" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center><table border="0" cellspacing="0" cellpadding="4"><tr>
<td style="font-size:20px; font-weight:bold; filter:DropShadow(color=black, offx=2, offy=2)"><?php echo $lang['admin']['mitspieler']['alpha']['neuspieler']?></td>
</tr></table></center>
<form name="formular" method="post" action="<?php echo servername;?>admin/mitspieler_alpha.php?fu=2">
<br><br><br>
<center><table border="0" cellspacing="0" cellpadding="2">
   <tr><td><?php echo $lang['admin']['mitspieler']['alpha']['nick']?></td></tr>
   <tr><td><input type="text" name="nick" class="eingabe" value="" maxlength="30" style="width:250px;"></td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td><?php echo $lang['admin']['mitspieler']['alpha']['passwort']?></td></tr>
   <tr><td><input type="text" name="passwort" class="eingabe" value="" maxlength="30" style="width:250px;"></td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td><?php echo $lang['admin']['mitspieler']['alpha']['email']?></td></tr>
   <tr><td><input type="text" name="email" class="eingabe" value="" maxlength="255" style="width:250px;"></td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td><input type="submit" name="dumdidum" value="<?php echo $lang['admin']['mitspieler']['alpha']['anlegen']?>" style="width:250px;"></td></tr>
   </table></form></center>
<?php } include ("inc.footer.php");
 }
if ($fuid==2) {
include ("inc.header.php");
if (($ftploginname==$admin_login) and ($ftploginpass==$admin_pass)) {
$nick=str_post('nick','SQLSAFE');
$email=str_post('email','SQLSAFE');
$passwort=cryptPasswd(str_post('passwort','NONE'));	
$passwort = explode(':',$passwort, 2);
$zeiger = @mysql_query("INSERT INTO $skrupel_user (nick,
                                                   passwort, 
                                                   salt, 
                                                   email,
                                                   optionen,
                                                   sprache) 
                                           values ('" . $nick ."',
                                                   '" . $passwort[0] . "',
                                                   '" . $passwort[1] ."',
                                                   '" . $email . "',
                                                   '00111111111000', 
                                                   '" . $language . "')");
?>
<body text="#ffffff" bgcolor="#444444" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center><table border="0" height="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $lang['admin']['mitspieler']['alpha']['erfolgreich']?></td>
  </tr>
</table></center>
<?php
} include ("inc.footer.php");
}
if ($fuid==10){
    include ("inc.header.php");
    if (($ftploginname==$admin_login) and ($ftploginpass==$admin_pass)) {
        ?>
       <body text="#ffffff" bgcolor="#444444" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center><table border="0" cellspacing="0" cellpadding="4"><tr>
<td style="font-size:20px; font-weight:bold; filter:DropShadow(color=black, offx=2, offy=2)"><?php echo $lang['admin']['mitspieler']['alpha']['neuspieler']?></td>
</tr></table></center>
<form name="formular" method="post" action="<?php echo servername;?>admin/mitspieler_alpha.php?fu=11">
<input type="hidden" name="id" value="<?php echo $params["id"];?>">
<br><br><br>
<center><table border="0" cellspacing="0" cellpadding="2">
<tr><td><?php echo $lang['admin']['mitspieler']['alpha']['nick']?></td></tr>
   <tr><td><input type="text" name="nick" class="eingabe" value="<?php echo $params["nick"];?>" maxlength="30" style="width:250px;"></td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td><?php echo $lang['admin']['mitspieler']['alpha']['passwort']?></td></tr>
   <tr><td><input type="text" name="passwort" class="eingabe" value="" maxlength="30" style="width:250px;"></td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td><?php echo $lang['admin']['mitspieler']['alpha']['email']?></td></tr>
   <tr><td><input type="text" name="email" class="eingabe" value="<?php echo $params["email"];?>" maxlength="255" style="width:250px;"></td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td><input type="submit" name="submit" value="Änderung speichern" style="width:250px;"></td></tr>
   </table></form></center>
   
<?php
    }

include("inc.footer.php");
}

if ($fuid==11){
    include ("inc.header.php");
    if (($ftploginname==$admin_login) and ($ftploginpass==$admin_pass)) {
        $nick = $params["nick"];
        $email = $params["email"];
        $id = intval($params["id"]);
        $passwort=cryptPasswd($params["passwort"]);
        $passwort = explode(':',$passwort, 2);
        $sql = "UPDATE " . table_prefix . "user set nick='".$nick."', passwort='". $passwort[0] ."' , salt='".$passwort[1]."',email='".$email."' where id='".$id."'";

        $Db->execute($sql);
    }    
    $site = servername . "admin/mitspieler_beta.php?fu=1";
    header("Location: $site");
}


