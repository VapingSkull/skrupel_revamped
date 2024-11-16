<?php
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST));
$fuid = intval($params["fu"]);
$languebersicht = get_phrasen('de', 'uebersicht');
switch ($fuid)
{
    case 1:
           set_header();
           $smarty->assign('servername', servername);
           $smarty->assign('sid', $params["sid"]);
           $smarty->assign('uid', $params["uid"]);
           $smarty->assign('fuid', $fuid);
           $smarty->assign('spieler_raus', $spieler_raus);
           $smarty->assign('languebersicht', $languebersicht);
           $smarty->display('uebersicht/uebersicht.tpl');
    break ;        
}
