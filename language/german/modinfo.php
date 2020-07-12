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
define("_MI_ONLINEHISTORY_NAME","Online Historie");

// A brief description of this module
define("_MI_ONLINEHISTORY_DESC","Zeigt User die gerade Online sind, sowie die Historie der eingeloggten User.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_ONLINEHISTORY_BNAME1","Online Historie");
define("_MI_ONLINEHISTORY_BNAME1_DESC","zeigt an, wer wann zuletzt online war");
define("_MI_ONLINEHISTORY_BNAME2","Online Historie Zählblock");
define("_MI_ONLINEHISTORY_BNAME2_DESC","ist unsichtbar, zählt die Besucher");
define("_MI_ONLINEHISTORY_TIMELIFE","Anzeige der letzten xx Stunden");
define("_MI_ONLINEHISTORY_TIMELIFE_DESC","zeigt an, wer die letzten xx Stunden online war");
define("_MI_ONLINEHISTORY_VIEWLIMIT","Anzeige von xx Usern");
define("_MI_ONLINEHISTORY_VIEWLIMIT_DESC","zeigt in der Übersicht die genannte Anzahl User je Seite an.");

define('_MI_ONLINEHISTORY_DISPLAYSUMA','Anzeige von Suchmaschinen oder Bots');
define('_MI_ONLINEHISTORY_DISPLAYSUMA_DESC','');
define('_MI_ONLINEHISTORY_DISPLAYMAXUSER','Anzeige der max. User die gleichzeitig online waren');
define('_MI_ONLINEHISTORY_DISPLAYMAXUSER_DESC','');
?>