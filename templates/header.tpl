<html>
<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="Author" content="Bernd Kantoks bernd@kantoks.de">
        <meta name="Author" content="SkullCollector">
        <meta name="robots" content="index">
        <meta name="keywords" content="Skrupel,Online Browsergame">
    <meta http-equiv="imagetoolbar" content="no">
    <style type="text/css">
        body,p,td {
            font-family: Verdana;
            font-size: {$fontsize_small}px;
            color: #ffffff;
            scrollbar-darkshadow-color: #444444;
            scrollbar-3dlight-color: #444444;
            scrollbar-track-color: #444444;
            scrollbar-face-color: #555555;
            scrollbar-shadow-color: #222222;
            scrollbar-highlight-color: #888888;
            scrollbar-arrow-color: #555555;
        }
        td.weissklein {
            font-family: Verdana;
            font-size: {$fontsize_small}px;
            color: #ffffff;
        }
        td.weissgross {
            font-family: Verdana;
            font-size: {$fontsize_big}px;
            color: #ffffff;
        }
        a {
            color: #aaaaaa;
            font-weight: normal;
            text-decoration: none;
        }
        a:hover {
            font-weight: normal;
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
            Border-style: solid;
            Border-width: 1px;
            font-family: Verdana;
            font-size: {$fontsize_small}px;
        }
        input.nofunc {
            background-color: #555555;
            color: #777777;
            border-bottom-color: #222222;
            border-left-color: #888888;
            border-right-color: #222222;
            border-top-color: #888888;
            Border-style: solid;
            Border-width: 1px;
            font-family: Verdana;
            font-size: {$fontsize_small}px;
        }
        input.eingabe {
            background-color: #555555;
            color: #ffffff;
            border-bottom-color: #888888;
            border-left-color: #222222;
            border-right-color: #888888;
            border-top-color: #222222;
            Border-style: solid;
            Border-width: 1px;
            font-family: Verdana;
            font-size: {$fontsize_small}px;
        }
        textarea {
            background-color: #555555;
            color: #ffffff;
            border-bottom-color: #888888;
            border-left-color: #222222;
            border-right-color: #888888;
            border-top-color: #222222;
            Border-style: solid;
            Border-width: 1px;
            font-family: Verdana;
            font-size: {$fontsize_small}px;
        }
    </style>
    <script language="JavaScript">
        function hilfe(hid) {
            oben=100;
            links=Math.ceil((screen.width-480)/2);
            window.open('{$servername}pages/hilfe/hilfe.php?fu2='+hid+'&uid={$uid}&sid={$sid}','Hilfe','resizable=yes,scrollbars=no,width=480,height=180,top='+oben+',left='+links);
        }
    </script>
    <link href="{$servername}js/flexcroll/standard_grey.css" rel="stylesheet" type="text/css" />
    {$flexjs}
</head>