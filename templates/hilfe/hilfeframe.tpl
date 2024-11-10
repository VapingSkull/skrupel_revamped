{include file='header.tpl'}

<frameset framespacing="0" border="false" frameborder="0" rows="18,*,16">

        <frameset framespacing="0" border="false" frameborder="0" cols="114,*,114">
             <frame name="rahmen1" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=34" target="_self">
             <frame name="rahmen2" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=20" target="_self">
             <frame name="rahmen3" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=35" target="_self">
        </frameset>

        <frameset framespacing="0" border="false" frameborder="0" cols="18,*,18">

            <frameset framespacing="0" border="false" frameborder="0" rows="80,*,92">
                <frame name="rahmen15" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=25" target="_self">
                <frame name="rahmen16" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=26" target="_self">
                <frame name="rahmen17" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=27" target="_self">
            </frameset>

            <frame name="rahmen12" scrolling="auto" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/hilfe.php?fu={if $fu2>=1}{$fu2}{else}9999{/if}&uid={$uid}&sid={$sid}" target="_self">

            <frameset framespacing="0" border="false" frameborder="0" rows="80,*,92">
                <frame name="rahmen18" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=28" target="_self">
                <frame name="rahmen19" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=29" target="_self">
                <frame name="rahmen20" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=30" target="_self">
            </frameset>

        </frameset>

        <frameset framespacing="0" border="false" frameborder="0" cols="114,*,114">
             <frame name="rahmen6" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=36" target="_self">
             <frame name="rahmen7" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=23" target="_self">
             <frame name="rahmen8" scrolling="no" marginwidth="0" marginheight="0" noresize src="<?php echo servername?>inhalt/aufbau.php?fu=37" target="_self">
        </frameset>

    </frameset>


    <noframes>
    <body>
        
{include file='footer.tpl'}
