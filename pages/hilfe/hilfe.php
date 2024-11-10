<?php
$langhilfe = get_phrasen ('de', 'hilfe');
$params = array_merge(filter_struct_utf8(1, $_GET), filter_struct_utf8(1, $_POST));
$fuid = $params['fu'];
$smarty->assign('servername', servername);
set_header();
switch ($fuid)
{
   case ($fuid>=1):
        $smarty->assign('fuid', $fuid);
        $ueberschrift = "ueberschrift".$fuid;
        $text = "text".$fuid;
        $smarty->assign($ueberschrift, $langhilfe['hilfe'][$ueberschrift]);
        $smarty->assign($text, $langhilfe['hilfe'][$text]);
        $smarty->assign('servername', servername);
        $smarty->display('hilfe/hilfe.tpl');
    break; 
    case 1:
        $cantox = array(100,200,300,800,1000,1200,2500,5000,7500,10000);
        $fu1html = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">";
        for($i=1;$i<6;$i++){                               
            $fu1html .= "   <tr>";
            $fu1html .= "       <td>" . str_replace('{1}',$i, $langhilfe['hilfe']['stufe']) ."</td>";
            $fu1html .= "       <td style=\"color:#aaaaaa;\">" . str_replace('{1}',$cantox[$i-1], $langhilfe['hilfe']['cx']) ."</td>";
            $fu1html .= "       <td>" . str_replace('{1}',$i+5, $langhilfe['hilfe']['stufe']) ."</td>";
            $fu1html .= "       <td style=\"color:#aaaaaa;\">". str_replace('{1}',$cantox[$i+4], $langhilfe['hilfe']['cx']) . "</td>";
            $fu1html .= "   </tr>";                                
         }
            $fu1html .= "</table>";
            $smarty->assign('fuid', 1);
            $smarty->assign('fu1html', $fu1html);
            $smarty->display('hilfe/hilfe.tpl');
       break;
    
    case 2:
        $cantox = array(100,200,300,400,500,600,700,4000,7000,10000);
        $fu2html = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">";
        for($i=1;$i<6;$i++){
            $fu2html .= "   <tr>";
            $fu2html .= "       <td>".str_replace('{1}',$i, $langhilfe['hilfe']['stufe'])."</td>";
            $fu2html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',$cantox[$i-1], $langhilfe['hilfe']['cx'])."</td>";
            $fu2html .= "       <td>".str_replace('{1}',$i+5, $langhilfe['hilfe']['stufe'])."</td>";
            $fu2html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',$cantox[$i+4], $langhilfe['hilfe']['cx'])."</td>";
            $fu2html .= "   </tr>";
        }
            $fu2html .= "</table>";
            $smarty->assign('fuid', 2);
            $smarty->assign('fu2html', $fu2html);
            $smarty->display('hilfe/hilfe.tpl');
            break;
    case 3:
        $fu3html = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">";
        for($i=1;$i<6;$i++){
            $fu3html .= "   <tr>";
            $fu3html .= "       <td>".str_replace('{1}',$i, $langhilfe['hilfe']['stufe'])."</td>";
            $fu3html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',tlquad($i), $langhilfe['hilfe']['cx'])."</td>";
            $fu3html .= "       <td>".str_replace('{1}',$i+5, $langhilfe['hilfe']['stufe'])."</td>";
            $fu3html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',tlquad($i+5), $langhilfe['hilfe']['cx'])."</td>";
            $fu3html .= "   </tr>";
        }
        $fu3html .= "</table>";
        $smarty->assign('fuid', 3);
        $smarty->assign('fu3html', $fu3html);
        $smarty->display('hilfe/hilfe.tpl');
        break;
    case 4:
        $fu4html = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">";
        for($i=1;$i<6;$i++){
            $fu4html .= "   <tr>";
            $fu4html .= "       <td>".str_replace('{1}',$i, $langhilfe['hilfe']['stufe'])."</td>";
            $fu4html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',tlquad($i), $langhilfe['hilfe']['cx'])."</td>";
            $fu4html .= "       <td>".str_replace('{1}',$i+5, $langhilfe['hilfe']['stufe'])."</td>";
            $fu4html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',tlquad($i+5), $langhilfe['hilfe']['cx'])."</td>";
            $fu4html .= "   </tr>";
        }
        $fu4html .= "</table>";
        $smarty->assign('fuid', 4);
        $smarty->assign('fu4html', $fu4html);
        $smarty->display('hilfe/hilfe.tpl');
        break;
    case 5:
        $fu5html  = "<center>";
        $fu5html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu5html .= "   <tr>";
        $fu5html .= "       <td>".$langhilfe['hilfe']['5-0']."</td>";
        $fu5html .= "   </tr>";
        $fu5html .= "</table>";
        $fu5html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu5html .= "   <tr>";
        $fu5html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\17\" height=\"17\"></td>";
        $fu5html .= "       <td>10</td>";
        $fu5html .= "   </tr>";
        $fu5html .= "   <tr>";
        $fu5html .= "       <td><img src=\"".servername."images/icons/vorrat.gif\" border=\"0\" width=\17\" height=\"17\"></td>";
        $fu5html .= "       <td>1</td>";
        $fu5html .= "   </tr>";
        $fu5html .= "</table>";
        $fu5html .= "</center>";
        $fu5html .= $langhilfe['hilfe']['5-1'];
        $smarty->assign('fuid', 5);
        $smarty->assign('fu5html', $fu5html);
        $smarty->display('hilfe/hilfe.tpl');
        break;
    
    case 6:
        $fu6html  = "<center>";
        $fu6html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu6html .= "   <tr>";
        $fu6html .= "       <td>".$langhilfe['hilfe']['6-0']."</td>";
        $fu6html .= "   </tr>";
        $fu6html .= "</table>";
        $fu6html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu6html .= "   <tr>";
        $fu6html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
        $fu6html .= "       <td>3</td>";
        $fu6html .= "   </tr>";
        $fu6html .= "   <tr>";
        $fu6html .= "       <td><img src=\"".servername."images/icons/vorrat.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
        $fu6html .= "       <td>1</td>";
        $fu6html .= "   </tr>";
        $fu6html .= "</table>";
        $fu6html .= "</center>";
        $fu6html .= $langhilfe['hilfe']['hilfe']['6-1'];
        $smarty->assign('fuid', 6);
        $smarty->assign('fu6html', $fu6html);
        $smarty->display('hilfe/hilfe.tpl');                                                        
        break;
    
    case 8:
        $fu8html  = "<center>";
        $fu8html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td>".$langhilfe['hilfe']['8-0']."</td>";
        $fu8html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',1,$langhilfe['hilfe']['8-5'])."</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td>".$langhilfe['hilfe']['8-1']."</td>";
        $fu8html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',2,$langhilfe['hilfe']['8-6'])."</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td>".$langhilfe['hilfe']['8-2']."</td>";
        $fu8html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',4,$langhilfe['hilfe']['8-6'])."</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td>".$langhilfe['hilfe']['8-3']."</td>";
        $fu8html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',6,$langhilfe['hilfe']['8-6'])."</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td>".$langhilfe['hilfe']['8-4']."</td>";
        $fu8html .= "       <td style=\"color:#aaaaaa;\">".str_replace('{1}',10,$langhilfe['hilfe']['8-6'])."</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "</table>";
        $fu8html .= "</center>";
        $fu8html .= $langhilfe['hilfe']['8-7'];
        $fu8html .= "<center>";
        $fu8html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td>".$langhilfe['hilfe']['8-8']."</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "</table>";
        $fu8html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
        $fu8html .= "       <td>4</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "   <tr>";
        $fu8html .= "       <td><img src=\"".servername."images/icons/vorrat.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
        $fu8html .= "       <td>1</td>";
        $fu8html .= "   </tr>";
        $fu8html .= "</table>";
        $fu8html .= "</center>";
        $fu8html .= $langhilfe['hilfe']['8-9'];
        $smarty->assign('fuid', 8);
        $smarty->assign('fu8html', $fu8html);
        $smarty->display('hilfe/hilfe.tpl');
        break;
        
    case 10:
        $fu10html  = "<center>";
        $fu10html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu10html .= "   <tr>";
        $fu10html .= "      <td>".$langhilfe['hilfe']['10-0']."</td>";
        $fu10html .= "  </tr>";
        $fu10html .= "</table>";
        $fu10html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
        $fu10html .= "  <tr>";
        $fu10html .= "      <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
        $fu10html .= "      <td>10</td>";
        $fu10html .= "  </tr>";
        $fu10html .= "  <tr>";
        $fu10html .= "      <td><img src=\"".servername."images/icons/mineral_2.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
        $fu10html .= "      <td>1</td>";
        $fu10html .= "  </tr>";
        $fu10html .= "</table>";
        $fu10html .= "</center>";
        $smarty->assign('fuid', 10);
        $smarty->assign('fu10html', $fu10html);
        $smarty->display('hilfe/hilfe.tpl');
        break;
    case 12:
        $fu12html =  "<center>";
        $fu12html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        $fu12html .= "  <tr>";
        $fu12html .= "      <td><b>".$langhilfe['hilfe']['12-0']."</b></td>";
        $fu12html .= "  </tr>";
        $fu12html .= "</table>";
        $fu12html .= "</center>";
        $fu12html .= "<center>";
        $fu12html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        $fu12html .= "  <tr>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-7']."</b></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-8']."</b></td>";
        $fu12html .= "  </tr>";
        $fu12html .= "</table>";
        $fu12html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        $fu12html .= "  <tr>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-2']."</b></td>";
        $fu12html .= "      <td>&nbsp;&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-3']."&nbsp;</b></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>&nbsp;".$langhilfe['hilfe']['12-2']."</b></td>";
        $fu12html .= "      <td>&nbsp;&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-3']."</b></td>";
        $fu12html .= "  </tr>";
        $schaden=array(array(3,1,2,1),
                 array(7,2,4,3),
                 array(10,2,5,3),
                 array(15,4,8,5),
                 array(12,16,6,20),
                 array(29,7,15,9),
                 array(35,8,18,10),
                 array(37,9,19,11),
                 array(18,33,9,41),
                 array(45,11,23,14));
        for($i=0;$i<10;$i++){
        $fu12html .= "  <tr>";
        $fu12html .= "      <td><center>".$schaden[$i][0]."</center></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $fu12html .= "      <td><center>".$schaden[$i][1]."</center></td>";
        $hilfe_5i = "12-5-".$i;
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><center>".$langhilfe['hilfe'][$hilfe_5i]."</center></td>";
        $fu12html .= "      <td><center>".$schaden[$i][2]."</center></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $fu12html .= "      <td><center>".$schaden[$i][3]."</center></td>";
        $fu12html .= "  </tr>";
        }
        $fu12html .= "</table>";
        $fu12html .= "</center>";
        $fu12html .= "<center>".$langhilfe['hilfe']['12-4']."</center>";
        $fu12html .= "<center>";
        $fu12html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        $fu12html .= "  <tr>";
        $fu12html .= "      <td><b>".$langhilfe['hilfe']['12-1']."</b></td>";
        $fu12html .= "  </tr>";
        $fu12html .= "</table>";
        $fu12html .= "</center>";
        $fu12html .= "<center>";
        $fu12html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        $fu12html .= "  <tr>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-7']."</b></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-8']."</b></td>";
        $fu12html .= "  </tr>";
        $fu12html .= "</table>";
        $fu12html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
        $fu12html .= "  <tr>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-2']."</b></td>";
        $fu12html .= "      <td>&nbsp;&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\">".$langhilfe['hilfe']['12-3']."&nbsp;</b></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>&nbsp;".$langhilfe['hilfe']['12-2']."</b></td>";
        $fu12html .= "      <td>&nbsp;&nbsp;</td>";
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><b>".$langhilfe['hilfe']['12-3']."</b></td>";
        $fu12html .= "  </tr>";
        $schaden=array(array(5,1,3,1),
                       array(8,2,4,3),
                       array(10,2,5,3),
                       array(6,13,3,16),
                       array(15,6,8,8),
                       array(30,7,15,9),
                       array(35,8,18,10),
                       array(12,36,6,45),
                       array(48,12,24,15),
                       array(55,14,28,18));
        for($i=0;$i<10;$i++){
        $fu12html .= "  <tr>";
        $fu12html .= "      <td><center>".$schaden[$i][0]."</center></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $fu12html .= "      <td><center>".$schaden[$i][1]."</center></td>";
        $fu12html .= "      <td>&nbsp;</td>";
        $hilfe_6i = "12-6-".$i;
        $fu12html .= "      <td style=\"color:#aaaaaa;\"><center>".$langhilfe['hilfe'][$hilfe_6i]."</center></td>";
        $fu12html .= "      <td><center>".$schaden[$i][2]."</center></td>";
        $fu12html .= "      <td><center>".$schaden[$i][3]."</center></td>";
        $fu12html .= "  </tr>";
        }
        $fu12html .= "</table>";
        $fu12html .= "</center>";
        $fu12html .= "<center>".$langhilfe['hilfe']['12-9']."</center>";
        $smarty->assign('fuid', 12);
        $smarty->assign('fu12html', $fu12html);
        $smarty->display('hilfe/hilfe.tpl');
        break;
        case 16:
            $fu16html  = "<ul>";
            for($i=0;$i<6;$i++){
                $hilfe_0i = "16-0-".$i;
                $hilfe_1i = "16-1-".$i;
            $fu16html .= "<li><b>".$langhilfe['hilfe'][$hilfe_0i]."</b></li>";
            $fu16html .= "<li><b>".$langhilfe['hilfe'][$hilfe_1i]."</b></li>";
            }
            $fu16html .= "</ul>";
            $fu16html .= $langhife['hilfe'][16-2];
            $smarty->assign('fuid', 16);
            $smarty->assign('fu16html', $fu16html);
            $smarty->display('hilfe/hilfe.tpl');
        break;
   case 33:
       $fu33html  = "<center>";
       $fu33html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">";
       $fu33html .= "   <tr>";
       $fu33html .= "       <td>1</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "       <td><nobr>&nbsp;:&nbsp;</nobr></td>";
       $fu33html .= "       <td>0</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "   </tr>";       
       $fu33html .= "   <tr>";
       $fu33html .= "       <td>".str_replace('{1}',1,$langhilfe['hilfe']['kt'])."</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/lemin.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "       <td><nobr>&nbsp;:&nbsp;</nobr></td>";
       $fu33html .= "       <td>8</td>";
       $fu33html .= "       <td><img src=\"".servername."iamges/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "   </tr>";
       $fu33html .= "   <tr>";
       $fu33html .= "       <td>".str_replace('{1}',1,$langhilfe['hilfe']['kt'])."</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/vorrat.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "       <td><nobr>&nbsp;:&nbsp;</nobr></td>";
       $fu33html .= "       <td>8</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "   </tr>";
       $fu33html .= "   <tr>";
       $fu33html .= "       <td>".str_replace('{1}',1,$langhilfe['hilfe']['kt'])."</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/mineral_1.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "       <td><nobr>&nbsp;:&nbsp;</nobr></td>";
       $fu33html .= "       <td>8</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "   </tr>";
       $fu33html .= "   <tr>";
       $fu33html .= "       <td>".str_replace('{1}',1,$langhilfe['hilfe']['kt'])."</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/mineral_2.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "       <td><nobr>&nbsp;:&nbsp;</nobr></td>";
       $fu33html .= "       <td>8</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "   </tr>";
       $fu33html .= "   <tr>";
       $fu33html .= "       <td>".str_replace('{1}',1,$langhilfe['hilfe']['kt'])."</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/mineral_3.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "       <td><nobr>&nbsp;:&nbsp;</nobr></td>";
       $fu33html .= "       <td>8</td>";
       $fu33html .= "       <td><img src=\"".servername."images/icons/cantox.gif\" border=\"0\" width=\"17\" height=\"17\"></td>";
       $fu33html .= "   </tr>";
       $fu33html .= "</table>";
       $fu33html .= "</center>";
       $fu33html .= $langhilfe['hilfe'][33-0];
       $smarty->assign('fuid', 33);
       $smarty->assign('fu33html', $fu33html);
       $smarty->display('hilfe/hilfe.tpl');
       
   case 9999:
       $fu9999html = "Sorry Keine Hilfe vorhanden";
       $smarty->assign('fuid', 9999);
       $smarty->assign('fu9999html', $fu9999html);
       $smarty->display('hilfe/hilfe.tpl');
       break;
    default:
        $smarty->assign('fu2', $params['fu2']);
        $smarty->assign('sid', $params['sid']);
        $smarty->assign('uid', $params['uid']);
        $smarty->display('hilfe/hilfeframe.tpl');
        break;
}        
