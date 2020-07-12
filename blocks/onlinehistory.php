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
 * @version         $Id: onlinehistory.php 1 2009-11-29 20:00:00 dhcst $
 */

xoops_loadLanguage('main','onlinehistory');

function b_onlinehistory_checkshow($options) {
   $time_guest = $options[0] * 60;  // Angabe in Minuten bei Gästen
   $time_user = $options[1] * 60 * 60 *24 ;  // Angabe in Tagen bei Usern
   b_onlinehistory_update($time_guest,$time_user);  
}

function b_onlinehistory_checkedit($options) {
    $form = _MB_ONLINEHISTORY_TIMEGUEST.'<br />';
    $form .= "<select name ='options[]'>";
    $guesttime = array('2'=>2, '5'=>5, '10' =>10, '30' =>30);
    foreach ($guesttime as $timeid => $timezahl) {
        $sel = '';
        if ($options[0] == $timeid) {
            $sel = " selected='selected'";
        }
        $form .= "<option value='$timeid'$sel>".$timezahl.'</option>';
    }
    $form .= '</select> '.sprintf(_MINUTES,"").'<br /><br />';
    $form .= _MB_ONLINEHISTORY_TIMEUSER.'<br />';
    $form .= "<select name ='options[]'>";
    $usertime = array('1'=>1, '3'=>3, '7' =>7, '14' => 14, '30' =>30, '90' => 90, '365' => 365);
    foreach ($usertime as $timeid => $timezahl) {
        $sel = '';
        if ($options[1] == $timeid) {
            $sel = " selected='selected'";
        }
        $form .= "<option value='$timeid'$sel>".$timezahl.'</option>';
    }
    $form .= '</select> '.sprintf(_DAYS,"").'<br /><br />';
    
    return $form;
}

function b_onlinehistory_show($options) {
    global $xoopsDB, $xoopsUser, $xoopsModule;
    
    $history_handler =& xoops_getModuleHandler('history','onlinehistory');             
    $online_spider      = 0;
    $guest_online_num   = 0;
    $member_online_num  = 0;
    $list_online = array();
	$block = array();
    $module_handler =& xoops_gethandler( 'module' );
    $config_handler =& xoops_gethandler('config');
    $olModule =& $module_handler->getByDirname('onlinehistory');
    $olConfig = $config_handler->getConfigsByCat(0, $olModule->getVar('mid')); 
    unset($olModule);    
    $moduleid = (is_object($xoopsModule)) ? $xoopsModule->getVar('mid') : 0;      
	$block['title'] = _MB_ONLINEHISTORY_TITLE1;
	$myts =& MyTextSanitizer::getInstance();
    
    $result = $xoopsDB->query("SELECT uid,uagent,module FROM ".$xoopsDB->prefix("lastseen")." WHERE online=1");
    while ($r = $xoopsDB->fetchArray($result))
    {
        if (intval($r['uid']) > 0 ) {
            $member_online_num++;            
        } else {
            if (intval($r['uid']) < 0) {
                if ($olConfig['viewsumaonline'] == 1) {
                    $online_spider++;
                } 
            } else {
                $guest_online_num++;
            }
        }
    }
    
    $online_history_num = $guest_online_num + $member_online_num + $online_spider;
    if ($olConfig['viewmaxonline'] == 1) {
        $maxuser = $history_handler->readmax();
        $block['maxonline'] = sprintf(_MB_ONLINEHISTORY_MAXUSER,formatTimestamp($maxuser[1],'m'),$maxuser[0]);
    }
    if ( $xoopsUser ) {
		$block['user'] = sprintf(_MB_ONLINEHISTORY_URLAS,$xoopsUser->getVar("uname"));
	} else {
		$block['user'] = _MB_ONLINEHISTORY_URAU;
		$block['user'] .= "<br /><a href='" . XOOPS_URL . "/register.php'>" . _MB_ONLINEHISTORY_RNOW . "</a>";
	}
    $block['online_total'] = sprintf(_MB_ONLINEHISTORY_ALLUSER,$online_history_num);
    $block['online_users'] = "";
    if ($guest_online_num == 1 && $member_online_num == 0)
        $block['online_users'] .= _MB_ONLINEHISTORY_THERE_ONLYONEGUEST;
    elseif ($guest_online_num == 0 && $member_online_num == 1)
        $block['online_users'] .= _MB_ONLINEHISTORY_THERE_ONLYMEMBER;
    elseif ($guest_online_num == 1 && $member_online_num == 1)
        $block['online_users'] .= _MB_ONLINEHISTORY_THERE_ONLYON;
    elseif ($guest_online_num == 1 && $member_online_num > 1)
        $block['online_users'] .= sprintf(_MB_ONLINEHISTORY_THERE_ONEMEMBERMORE,$member_online_num);
    elseif ($guest_online_num > 1 && $member_online_num == 1)
        $block['online_users'] .= sprintf(_MB_ONLINEHISTORY_THERE_ONEGUESTMORE,$guest_online_num);
    elseif ($guest_online_num == 0 && $member_online_num > 1)
        $block['online_users'] .= sprintf(_MB_ONLINEHISTORY_THERE_NOMEMBERMORE,$member_online_num);
    elseif ($guest_online_num > 1 && $member_online_num == 0)
        $block['online_users'] .= sprintf(_MB_ONLINEHISTORY_THERE_NOGUESTMORE,$guest_online_num);
    else
        $block['online_users'] .= sprintf(_MB_ONLINEHISTORY_THERE,$guest_online_num,$member_online_num);
    
    if ($online_spider > 0) $block['online_suma'] = sprintf(_MB_ONLINEHISTORY_THERE_SPIDERS,$online_spider);

    $block['useronline'] = '';
	if ($member_online_num > 0) {
        $first = 0;
		$result = $xoopsDB->query("SELECT l.uid, l.username, u.name FROM ".$xoopsDB->prefix("lastseen")." as l, ".$xoopsDB->prefix("users")." as u WHERE l.uid>0 AND l.online=1 AND l.uid=u.uid AND 1=1 LIMIT 10");
		while(list($memuid, $memusername, $memname) = $xoopsDB->fetchRow($result)) {
            if ($first!=0) $block['useronline'] .=", ";
            $list_online[] = $memuid;
            $name = ( $options[3] == 1 ) ? ((trim($memname) != '') ? $memname : $memusername) : $memusername;
            $block['useronline'] .="<a href=\"".XOOPS_URL."/userinfo.php?uid=$memuid\">".$myts->htmlSpecialChars($name)."</a>";
            $first= 1;
		}
        if ($first==1) $block['useronline'] .="";        
	} 
    $block['lang_more'] ="<a href=\"javascript:openWithSelfMain('".XOOPS_URL."/modules/".basename(dirname(dirname(__FILE__)))."/blocks/showuser.php?action=showpopups&amp;type=online','Online',450,490);\">"._MB_ONLINEHISTORY_MORE."</a><br />";
    $block['content'] = "";
	if ( $options[0] == 1 ) {
        $usonly = array();
		$mintime = time() - ($options[1] * 86400);
		$result = $xoopsDB->query("SELECT l.uid, l.username, l.time, u.name FROM ".$xoopsDB->prefix("lastseen")." as l, ".$xoopsDB->prefix("users")." as u WHERE l.uid>0 AND l.time>".$mintime." AND l.uid=u.uid AND 1=1 ORDER BY l.time DESC ",$options[2],0);
		while (list($uid, $uname, $time, $rname) = $xoopsDB->fetchRow($result)) {
			if (!in_array($uid,$list_online)) {
                $u = array();
				$lastvisit = b_onlinehistory_create($time);     
                $name = ( $options[3] == 1 ) ? ((trim($rname) != '') ? $rname : $uname) : $uname;                         
				$u['user'] = "<a href=\"".XOOPS_URL."/userinfo.php?uid=".$uid."\">".$myts->htmlSpecialChars($name)."</a>";
				$u['last'] = $lastvisit;
                $usonly[]=$u;
			}
		}
        $block['content'] = $usonly;
	}
    return $block;
}

function b_onlinehistory_create($date){
	$realtime = time() - $date;
	$lastvisit ="";
	$days = "";
	$hours = "";
	$mins = "";
	//echo $realtime;
	// how many days ago?
	if ( $realtime >= 86400 ) { // if it's been more than a day
		$days = floor($realtime / (86400));
		$realtime -= (86400 * $days);
	}
	
	// how many hours ago?
	if ( $realtime >= (3600) ) {
		$hours = floor($realtime / (3600));
		$realtime -= (3600*$hours);
	}
	
	// how many minutes ago?
	if ( $realtime >= 60 ) {
		$mins = floor($realtime / 60);
		$realtime -= (60*$mins);				
	}

	// just a little precation, although I don't *think* mins will ever be 60...
	if ( $mins == 60 ) {
		$mins = 0;
		$hours += 1;
	}
	if ( $days > 1 ) {
		$lastvisit .= sprintf(_MB_ONLINEHISTORY_DAYS,$days);
	} elseif($days == 1) {
		$lastvisit .= _MB_ONLINEHISTORY_1DAY;
	}
	if ( $hours > 0 ) {
		if($hours == 1){
			$lastvisit .= _MB_ONLINEHISTORY_1HR;
		}else{
			$lastvisit .= sprintf(_MB_ONLINEHISTORY_HRS,$hours);
		}
	}
	if ( $mins > 0 ) {
		if ( $mins == 1 ) {
			$lastvisit .= _MB_ONLINEHISTORY_1MIN;
		} else {
			$lastvisit .= sprintf(_MB_ONLINEHISTORY_MINS,$mins);
		}
	}
	if ( !$days && !$hours && !$mins ) {
		$lastvisit .= sprintf(_MB_ONLINEHISTORY_SCNDS,$realtime);
	}
			
	$lastvisit .= _MB_ONLINEHISTORY_AGO;
	return $lastvisit;
}

function b_onlinehistory_edit($options) {
	$form = _MB_ONLINEHISTORY_SLAST."&nbsp;";
    if ( $options[0] == 1 ) {
		$chk  = " checked='checked'";
        $chk1 = "";
	} else {
        $chk1 = " checked='checked'";
        $chk  = "";
	}
	$form .= "<input type='radio' name ='options[0]' value='1'".$chk." />&nbsp;"._YES."";	
	$form .= "&nbsp;<input type='radio' name ='options[0]' value='0'".$chk1." />"._NO."<br />";
    $form .= _MB_ONLINEHISTORY_MDAYS."&nbsp;<input type='text' name='options[1]' value='".$options[1]."' />&nbsp;"._MB_ONLINEHISTORY_DAYS2."\n";
	$form .= "<br />"._MB_ONLINEHISTORY_MMEM."&nbsp;<input type='text' name='options[2]' value='".$options[2]."' />&nbsp;"._MB_ONLINEHISTORY_MEMS."\n";
    $form .= "<br />"._MB_ONLINEHISTORY_UNAME."&nbsp;";
    if ( $options[3] == 1 ) {
		$chk  = " checked='checked'";
        $chk1 = "";
	} else {
        $chk1 = " checked='checked'";
        $chk  = "";
	}
	$form .= "<input type='radio' name ='options[3]' value='1'".$chk." />&nbsp;"._YES."";	
	$form .= "&nbsp;<input type='radio' name ='options[3]' value='0'".$chk1." />"._NO."<br />";
    return $form;
}

/*
* Function to update last seen table
*/
function b_onlinehistory_update($guest_online=300, $user_online=8640000){
	global $xoopsUser, $xoopsModule ;
      
    $history_handler =& xoops_getModuleHandler('history','onlinehistory');
    $history_handler->getUpdate($guest_online,$user_online);
        
	$ip     = $_SERVER['REMOTE_ADDR']; 
    $agent  = $_SERVER['HTTP_USER_AGENT'];
    
	if ($xoopsUser) {
		$uid = $xoopsUser->getVar("uid");
		$uname = $xoopsUser->getVar("uname");
	} else {
		$uid = 0;
		$uname = _MA_ONLINEHISTORY_GUEST;
	}
    
    if ($sumaagent = _getAgent($agent)) 
    {
       $uname = "Spider: ".$sumaagent;
       $uid = -1;
    }
    
    if ($agent=='') $agent = 'Unknown';
    
    $module_handler =& xoops_gethandler( 'module' );
    $config_handler =& xoops_gethandler('config');
    $olModule =& $module_handler->getByDirname('onlinehistory');
    $olConfig = $config_handler->getConfigsByCat(0, $olModule->getVar('mid')); 
    unset($olModule);
    
    if ($olConfig['viewsumaonline'] == 1 || $uid > -1) {
        $moduleid = (is_object($xoopsModule)) ? $xoopsModule->getVar('mid') : 0;
        $history_handler->getUpdateUser($uid, $uname, $ip, $agent, $moduleid);
    }  
    
    if ($olConfig['viewmaxonline'] == 1) {
        $criteria = new CriteriaCompo();
        $criteria->add(new criteria('online','0','>'));
        if ($olConfig['viewsumaonline'] == 1) {
            $criteria->add(new criteria('uid','-1','>'));
        }
        $user = $history_handler->getCount($criteria);
        $history_handler->makemax($user);
    } 
    
}


function _getAgent($user_agent = "")
{
	$_spiders = array (
		'WISENutbot'                        => 'Looksmart',
		'MSNBot'                            => 'MSN',
		'W3C_Validator'                     => 'W3C Validator',
        'Googlebot-Image'                   => 'Google-Image',
        'Googlebot'                         => 'Google',
		'Mediapartners-Google'              => 'Google AdSense',
		'Openbot'                           => 'Openfind',
		'Yahoo! Slurp'                      => 'Yahoo',
		'FAST-WebCrawler'                   => '',
		'Wget'                              => '',
		'Ask Jeeves'                        => '',
		'Speedy Spider'                     => 'Speedy',
		'SurveyBot'                         => '',
		'IBM_Planetwide'                    => '',
		'GigaBot'                           => '',
		'ia_archiver'                       => '',
		'FAST-WebCrawler'                   => '',
		'Inktomi Slurp'                     => '',
		'appie'                             => 'Walhello',
		'FeedBurner/1.0'                    => 'Feedburner',
        'Feedfetcher-Google'                => '',
        'OmniExplorer_Bot/6.68'             => 'OmniExplorer Bot',
        'http://www.relevantnoise.com'      => 'relevantNOISE',
        'NewsGatorOnline/2.0'               => 'NewsGatorOnline',
        'ping.blo.gs/2.0'                   => '',
        'Jakarta Commons-HttpClient'  => 'Amazon',
        'Sogou web spider'                  => 'Sogou web',
        'Baiduspider'                       => 'Baidu',
        'Speedy Spider'                     => 'Speedy',
        'Twiceler'                          => '',
        'affilimatch'                       => 'Affilinet',
        'Yandex'                            => 'Yandex',
	);

	   
    foreach($_spiders AS $agent => $name)
		if (stripos(strtolower($user_agent), strtolower($agent)) !== false)
		{
			$spider_name = ($name != '') ? $name : $agent; 
			return $spider_name;
		}

	return false;
}
?>