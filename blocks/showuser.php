<?php
/**
 * OnlineHistory module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         onlinehistory
 * @since           2.4.0
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: showuser.php 1 2009-11-29 20:00:00 dhcst $
 */

include_once("../../../mainfile.php");
xoops_loadLanguage('misc');
xoops_loadLanguage('main','onlinehistory');

$action = isset($_GET['action']) ? strip_tags(trim($_GET['action'])) : '';
$action = isset($_POST['action']) ? strip_tags(trim($_POST['action'])) : $action;
$type = isset($_GET['type']) ? strip_tags(trim($_GET['type'])) : '';
$type = isset($_POST['type']) ? strip_tags(trim($_POST['type'])) : $type;

if ($action == "showpopups") {
    xoops_header();
    $closebutton = 1;    
    switch ($type) {    
        case 'online':
            require_once $GLOBALS['xoops']->path('class/template.php');
            require_once $GLOBALS['xoops']->path('class/theme.php');
            $xoopsThemeFactory = null;
            $xoopsThemeFactory = new xos_opal_ThemeFactory();
            $xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
            $xoopsThemeFactory->defaultTheme = $xoopsConfig['theme_set'];
            $xoTheme =& $xoopsThemeFactory->createInstance(array('plugins' => array()));
            $xoTheme->addScript('/include/xoops.js', array('type' => 'text/javascript'));
            $xoopsTpl =& $xoTheme->template;
            $xoopsTpl->assign(array(
                'xoops_theme' => $xoopsConfig['theme_set'] ,
                'xoops_imageurl' => XOOPS_THEME_URL . '/' . $xoopsConfig['theme_set'] . '/' ,
                'xoops_themecss' => xoops_getcss($xoopsConfig['theme_set']) ,
                'xoops_sitename' => htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES) ,
                'xoops_slogan' => htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES) ,
                'xoops_dirname' => @$xoopsModule ? $xoopsModule->getVar('dirname') : 'system' ,
                'xoops_pagetitle' => _MA_ONLINEHISTORY_NAME
            ));                
            $xoopsTpl->debugging = false;
            $xoopsTpl->debugging_ctrl = 'none';
            $xoopsTpl->caching = 0;
            
            $module_handler =& xoops_gethandler('module');            
            $maxlimit = 17;
            $start = isset($_GET['start']) ? intval($_GET['start']) : 0;                  
            
            $history_handler =& xoops_getModuleHandler('history','onlinehistory'); 
            $module_handler =& xoops_gethandler( 'module' );
            $config_handler =& xoops_gethandler('config');
            $olModule =& $module_handler->getByDirname('onlinehistory');
            $moduleid = $olModule->getVar('mid');
            unset($olModule);
            $olConfig = $config_handler->getConfigsByCat(0, $moduleid);            
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('online','0','>'));  
            if ($olConfig['viewsumaonline'] == 0) {
                $criteria->add(new Criteria('uid','0','>='));
            }
            $online_total = $history_handler->getCount($criteria);
            $xoopsTpl->assign('total_online',$online_total);
            
            unset($criteria);
            $limit = ($online_total > $maxlimit) ? $maxlimit : $online_total;
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('online','0','>'));
            if ($olConfig['viewsumaonline'] == 0) {
                $criteria->add(new Criteria('uid','0','>='));
            }
            $criteria->setLimit($limit);
            $criteria->setStart($start);
            $onlines = $history_handler->getOnline($criteria);
            $count = count($onlines);
            $modules = $module_handler->getList(new Criteria('isactive', 1));
            for ($i = 0; $i < $count; $i++) {
                if ($onlines[$i]['uid'] < 1) {
                    $onlineUsers[$i]['user'] = $onlines[$i]['name'];
                } else {
                    $onlineUsers[$i]['user'] =  "<a href=\"javascript:window.opener.location='" . XOOPS_URL . "/userinfo.php?uid=" . $onlines[$i]['uid'] . "';window.close();\">" . $onlines[$i]['name'] . "</a>";
                }
                $onlineUsers[$i]['ip'] = $onlines[$i]['ip'];
                $onlineUsers[$i]['updated'] = $onlines[$i]['time'];
                $onlineUsers[$i]['module'] = $onlines[$i]['modul'];
                $onlineUsers[$i]['time'] = $onlines[$i]['time'];
            }
            $xoopsTpl->assign('online_user',$onlineUsers);
            
            if ($online_total > $maxlimit) {
                include_once $GLOBALS['xoops']->path('class/pagenav.php');
                $nav = new XoopsPageNav($online_total, $maxlimit, $start, 'start', 'action=showpopups&amp;type=online');
                $xoopsTpl->assign('pagnav',$nav->renderNav());
            }
            
            $xoopsTpl->display('db:history_pop.html');
            break;   
                   
        default:
            break;
    }
    if ($closebutton) {
        echo '<div style="text-align:center;"><input class="formButton" value="' . _CLOSE . '" type="button" onclick="javascript:window.close();" /></div>';
    }
    
    xoops_footer();
}
?>