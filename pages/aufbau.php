<?php
include ('../inc.conf.php');
include_once  (inhalt_dir . "inc.hilfsfunktionen.php");
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST), filter_struct_utf8(1, $_REQUEST));
$fuid = intval($params["fu"]);
if ($fuid==0) { ?>
    <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="Author" content="Bernd Kantoks bernd@kantoks.de">
        <meta name="Author" content="SkullCollector">
        <meta name="robots" content="index">
        <meta name="keywords" content="Skrupel,Online Browsergame">
        </head>
        <body text="#000000" bgcolor="#000000" background="<?php echo servername;?>bilder/hintergrund.gif" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        </body>
    </html>
<?php }
if (($fuid>=1) and ($fuid<=99)) {
    if ($fuid==8) {
        $url="https://".$_SERVER['SERVER_NAME'];
        $folders = explode('/', $_SERVER['SCRIPT_NAME']);
        $count = 0;
        $url .= '/';
        foreach ($folders as $value) {
            if ((0 < $count) and (count($folders) > $count+1) and ('inhalt' != $value)){
                $url .= $value . '/';
            }
            $count++;
        }
        ?>
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="Author" content="Bernd Kantoks bernd@kantoks.de">
        <meta name="Author" content="SkullCollector">
        <meta name="robots" content="index">
        <meta name="keywords" content="Skrupel,Online Browsergame">
            </head>
            <body text="#000000" bgcolor="#afafaf"  link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
            </body>
        </html>
    <?php } else { ?>
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        		<meta name="Author" content="Bernd Kantoks bernd@kantoks.de">
        		<meta name="Author" content="SkullCollector">
        		<meta name="robots" content="index">
        		<meta name="keywords" content="Skrupel,Online Browsergame">
            </head>
            <body text="#000000" bgcolor="#444444" background="<?php echo servername;?>bilder/aufbau/<?php echo $fuid; ?>.gif" link="#000000" vlink="#000000" alink="#000000" leftmargin="0" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0">
            </body>
        </html>
    <?php } ?>
<?php }
if ($fuid==100) {
    include (inhalt_dir . "inc.header.php");
    ?>
    <frameset framespacing="0" border="false" frameborder="0" rows="50,18,*,16,50">
        <frame name="rahmenk" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
        <frameset framespacing="0" border="false" frameborder="0" cols="60,114,*,114,60">
            <frame name="rahmen0" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
            <frame name="rahmen1" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=19&" target="_self">
            <frame name="rahmen2" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=20&" target="_self">
            <frame name="rahmen3" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=21&" target="_self">
            <frame name="rahmen4" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
        </frameset>
        <frameset framespacing="0" border="false" frameborder="0" cols="60,18,*,18,60">
            <frame name="rahmen10" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
            <frameset framespacing="0" border="false" frameborder="0" rows="80,*,92">
                <frame name="rahmen15" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=25&" target="_self">
                <frame name="rahmen16" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=26&" target="_self">
                <frame name="rahmen17" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=27&" target="_self">
            </frameset>
          <frame name="rahmen12" scrolling="auto" marginwidth="0" marginheight="0" noresize src="<?php echo substr($_SERVER['QUERY_STRING'],13,strlen($_SERVER['QUERY_STRING'])-13); ?>" target="_self">
            <frameset framespacing="0" border="false" frameborder="0" rows="80,*,92">
                <frame name="rahmen18" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=28&" target="_self">
                <frame name="rahmen19" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=29&" target="_self">
                <frame name="rahmen20" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=30&" target="_self">
            </frameset>
            <frame name="rahmen14" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
        </frameset>
        <frameset framespacing="0" border="false" frameborder="0" cols="60,114,*,114,60">
            <frame name="rahmen5" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
            <frame name="rahmen6" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=22&" target="_self">
            <frame name="rahmen7" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=23&" target="_self">
            <frame name="rahmen8" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=24&" target="_self">
            <frame name="rahmen9" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
        </frameset>
        <frame name="rahmenk" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername;?>inhalt/aufbau.php?fu=0&" target="_self">
    </frameset>
    <noframes>
    <body>
    </body>
<?php
}
