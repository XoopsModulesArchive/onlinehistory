<?php
/**
 * Online history module
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
 * @author          Dirk Herrmann <alfred@myxoops.org>
 * @version         $Id: history.php 1 2009-11-29 20:30:00Z dhcst $
 */
 
class OnlinehistoryHistory extends XoopsObject
{
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('username', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('time', XOBJ_DTYPE_INT, null, true);
        $this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, true, 15);
        $this->initVar('online', XOBJ_DTYPE_INT, null, true);
        $this->initVar('uagent', XOBJ_DTYPE_TXTAREA, null, true);  
        $this->initVar('module', XOBJ_DTYPE_INT, null, true);
    }
    
    function OnlinehistoryHistory()
    {
        $this->__construct();
    }
}

class OnlinehistoryHistoryHandler extends XoopsPersistableObjectHandler
{
    var $table  = "lastseen";     

    function __construct(&$db) 
    {
        parent::__construct($db, $this->table, 'OnlinehistoryHistory', 'uid', 'ip');
    }
    
    function OnlinehistoryHistoryHandler(&$db)
    {
        $this->__construct($db);
    }
    
    function makemax($wert=0) {  
        $logdatei = XOOPS_CACHE_PATH. "/onlinehistory_" . md5( XOOPS_URL.XOOPS_ROOT_PATH.$GLOBALS['xoopsDB']->prefix('')) . ".txt";
        $oldmax = $this->readmax();
        $oldmax = intval($oldmax[0]);
        if ($oldmax<intval($wert)) {
            $LogText = $wert."|".time()."\n";
			if ($fp = fopen($logdatei, 'w')) {
				fputs($fp, $LogText);
				fclose($fp);
			}        
        }
    }	
    
    function readmax() {
        $oldmax = 0;
        $logdatei = XOOPS_CACHE_PATH. "/onlinehistory_" . md5( XOOPS_URL.XOOPS_ROOT_PATH.$GLOBALS['xoopsDB']->prefix('')) . ".txt";
        if (file_exists($logdatei)) {
			if ($fp = fopen($logdatei, 'r')) {
				while(!feof($fp)) { $oldmax .= fgets($fp, 4096); }
				fclose($fp);
			}
		}
        $oldmax = explode("|",$oldmax);
        return array(intval($oldmax[0]),$oldmax[1]);
    }
    
    function getUpdate($guest = 0, $user=0)
    {
        $past       = time() - $guest; // anonymous records are deleted after 10 minutes
        $userpast   = time() - $user;  // user records idle for the past 100 days are deleted
        $sql = "DELETE FROM " . $this->table . " WHERE (uid<1 AND time<=".$past.") OR (uid>0 AND time<=".$userpast.")";
        $this->db->queryF($sql);
        $sql = "UPDATE " . $this->table . " SET online=0 WHERE uid>0 AND time< ".$past."";
		$this->db->queryF($sql);
        return true;
    }
    
    function getUpdateUser($uid=0, $uname='', $ip='', $agent='', $module=0) 
    {
        if ($uid <= 0 && $ip == '') return false;        
        $sql = "SELECT count(uid) as cuid FROM ".$this->table." WHERE uid=".$uid;
        if ( $uid == 0 ) {
            $sql .= " AND ip='".$ip."'";
        }
        $result = $this->db->query($sql);
        list($cuid) = $this->db->fetchRow($result);
        if ( $cuid > 0) {
            $sql = "UPDATE ".$this->table." SET time = " . time() . ", ip='".$ip."' ,uagent='".$agent."', username='".$uname."', module=".$module.", online=1 WHERE uid=".$uid."";
            if($uid == 0)  {
                $sql .= " AND ip='".$ip."'";
            }
            $this->db->queryF($sql);
        } else {
            $sql = "INSERT INTO ".$this->table." (uid, username, time, ip, online, uagent, module) VALUES (".$uid.", '".$uname."', ".time().", '".$ip."', 1, '".$agent."', ".$module.")";
            $this->db->queryF($sql);
        }
        return true;
    }
    
    
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(uid) as count FROM ' . $this->table;
        if (is_object($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        list ($ret) = $this->db->fetchRow($result);
        return $ret;
    }
    
       
    function getOnline($criteria = NULL)
    {
        $ret=array();
        $module_handler =& xoops_gethandler('module');
        $modules = $module_handler->getList(new Criteria('isactive', 1));
        $online = $this->getObjects($criteria);
        
        foreach ($online as $ol) {
            $l=array();
            $l['uid']       = $ol->getVar('uid');
            $l['ip']        = $ol->getVar('ip');
            $l['name']      = $ol->getVar('username');
            $utime          = $ol->getVar('time');
            $l['time']      = formatTimestamp($utime, 'l');
            $l['online']    = $ol->getVar('online');
            $l['modul']     = (!empty($modules[$ol->getVar('module')]) ) ? $modules[$ol->getVar('module')] : '';
            $l['online'] = $ol->getVar('online');
            $ret[]=$l;
            unset($l);
        }
        return $ret;
    }
}
?>