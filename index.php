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
 * @version         $Id: index.php 1 2009-11-29 20:00:00 dhcst $
 */

include_once '../../mainfile.php';
$xoopsOption['template_main'] = "history_index.html";
include $GLOBALS['xoops']->path('header.php');

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

$history_handler =& xoops_getModuleHandler('history','onlinehistory');
$criteria = new CriteriaCompo();
$criteria->add( new criteria('time',time() - (60 * 60 * $xoopsModuleConfig['timelife']) ,'>=') );
$criteria->add(new criteria('uid','0','>'));
$history_total = $history_handler->getCount($criteria);
unset($criteria);
$limit = ($history_total > $xoopsModuleConfig['viewlimit']) ? $xoopsModuleConfig['viewlimit'] : $history_total;
$criteria = new CriteriaCompo();
$criteria->setLimit($limit);
$criteria->setStart($start);
$criteria->setOrder('time');
$criteria->add(new criteria('uid','0','>'));
$criteria->add(new criteria('time',time() - (60 * 60 * $xoopsModuleConfig['timelife']) ,'>='));
$criteria->setSort('time');
$criteria->setOrder('DESC');
$history = $history_handler->getOnline($criteria);
$count = count($history);
$xoopsTpl->assign('count', $count);
$xoopsTpl->assign('onlines', $history);
if ( $history_total > $count ) {
    include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
	$pagenav = new XoopsPageNav($history_total, $xoopsModuleConfig['viewlimit'], $start, 'start', '');
	$xoopsTpl->assign('pagenav', $pagenav->renderNav());  	
} else {
    $xoopsTpl->assign('pagenav', '');
}
$xoopsTpl->assign('timelife', sprintf(_MA_ONLINEHISTORY_TIMELIFE,$xoopsModuleConfig['timelife']));
include $GLOBALS['xoops']->path('footer.php');
?>