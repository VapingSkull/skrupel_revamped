<?php

$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST), filter_struct_utf8(1, $_REQUEST));
$fuid = intval($params["fu"]);
switch ($fuid){
    case 0:
        
        $smarty->assign('fuid',$fuid);
        $smarty->assign('servername', servername);
        $smarty->display('aufbau/aufbaufu0.tpl');
        break;
    case (($fuid>=1) and ($fuid<=99)):
        if ($fuid==8) {
        $url="https://".$_SERVER['SERVER_NAME'];
        $folders = explode('/', $_SERVER['SCRIPT_NAME']);
        $count = 0;
        $url .= '/';
        foreach ($folders as $value) {
            if ((0 < $count) and (count($folders) > $count+1) and ('pages' != $value)){
                $url .= $value . '/';
            }
            $count++;
            }
        }
        
        $smarty->assign('fuid', $fuid);
        $samrty->assign('servername', servername);
        $smarty->display('aufbau/aufbau0-99.tpl');
        break;
        
    case 100:
        set_header();
        $smarty->assign('fuid',$fuid);
        $smarty->assign('servername', servername);
        $squery = substr($_SERVER['QUERY_STRING'],13,strlen($_SERVER['QUERY_STRING'])-13);
        $smarty->assign('squery', $squery);
        $smarty->display('framesets/aufbau_frame.tpl');        
        break;
}
