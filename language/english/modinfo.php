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
 * @version         $Id: modinfo.php 1 2009-11-29 20:00:00 dhcst $
 */

// The name of this module
define("_MI_ONLINEHISTORY_NAME","Online History");

// A brief description of this module
define("_MI_ONLINEHISTORY_DESC","Shows users that are currently online, and the amount of time since users were recently logged on.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_ONLINEHISTORY_BNAME1","Online History");
define("_MI_ONLINEHISTORY_BNAME1_DESC","shows who was last online when");
define("_MI_ONLINEHISTORY_BNAME2","Online Historie Counter");
define("_MI_ONLINEHISTORY_BNAME2_DESC","is invisible, is counting the visitors");
define("_MI_ONLINEHISTORY_TIMELIFE","Showing the last xx hours");
define("_MI_ONLINEHISTORY_TIMELIFE_DESC","shows who the last xx hours online");
define("_MI_ONLINEHISTORY_VIEWLIMIT","Showing xx users per side");
define("_MI_ONLINEHISTORY_VIEWLIMIT_DESC","appears in the list referred to the number of users per page.");

define('_MI_ONLINEHISTORY_DISPLAYSUMA','Display of search engines or bots');
define('_MI_ONLINEHISTORY_DISPLAYSUMA_DESC','');
define('_MI_ONLINEHISTORY_DISPLAYMAXUSER','Display of max. Users were online at the same time');
define('_MI_ONLINEHISTORY_DISPLAYMAXUSER_DESC','');
?>