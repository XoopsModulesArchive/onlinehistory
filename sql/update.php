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
 * @version         $Id: update.php 1 2009-11-29 20:00:00 dhcst $
 */
 
function xoops_module_update_onlinehistory(&$module, $oldversion = null) 
{
      
    if ($oldversion < 110) 
    {
        $sql = "ALTER TABLE " . $GLOBALS['xoopsDB']->prefix("lastseen") . " ADD uagent text NOT NULL";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        }
    }   

    if ($oldversion < 120) 
    {
        $sql = "ALTER TABLE " . $GLOBALS['xoopsDB']->prefix("lastseen") . " ADD module int(10) NOT NULL DEFAULT '0'";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        }
        $sql = "ALTER TABLE " . $GLOBALS['xoopsDB']->prefix("lastseen") . " CHANGE uid uid INT( 10 ) NOT NULL DEFAULT '0'";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        }
        $sql = "ALTER TABLE " . $GLOBALS['xoopsDB']->prefix("lastseen") . " CHANGE username username VARCHAR( 255 ) NOT NULL";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        }
        $sql = "ALTER TABLE " . $GLOBALS['xoopsDB']->prefix("lastseen") . " CHANGE ip ip VARCHAR( 255 ) NOT NULL";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
        }
    }    
    
    return true;
}
?>