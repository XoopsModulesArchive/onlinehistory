<?php
/**
 * OnlineHistory
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
 * @package         profile
 * @since           2.4.0
 * @author          Alfred <alfred@myxoops.org>
 * @version         $Id: core.php 3333 2010-01-18 10:46:15Z dhcst $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
  *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 */
class OnlinehistoryCorePreload extends XoopsPreloadItem
{
    function eventCoreUserLogout($args)
    {
        if (OnlinehistoryCorePreload::isActive()) {
            if ($GLOBALS['xoopsUser']->getVar('uid') > 0) {
                $sql = "UPDATE ".$GLOBALS['xoopsDB']->prefix("lastseen")." SET time = " . time() . ", online=0 WHERE uid=".$GLOBALS['xoopsUser']->getVar('uid')."";
                $GLOBALS['xoopsDB']->queryF($sql);
            }
        }
    }

    function isActive()
    {
        $module_handler =& xoops_getHandler('module');
        $module = $module_handler->getByDirname('profile');
        return ($module && $module->getVar('isactive')) ? true : false;
    }
}
?>