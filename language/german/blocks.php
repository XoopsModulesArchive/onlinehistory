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
 * @version         $Id: blocks.php 1 2009-11-29 20:00:00 dhcst $
 */
 
// Blocks
define("_MB_ONLINEHISTORY_TITLE1","Online Historie");

//first %s represents the number of guests, the second represents members. The order cannot be changed! 
define("_MB_ONLINEHISTORY_THERE","Das sind %s Gäste und %s Mitglieder");
define("_MB_ONLINEHISTORY_THERE_ONLYONEGUEST","Das ist ein Gast");
define("_MB_ONLINEHISTORY_THERE_ONLYMEMBER","Das ist ein Mitglied");
define("_MB_ONLINEHISTORY_THERE_ONLYON","Das ist ein Gast und ein Mitglied");
define("_MB_ONLINEHISTORY_THERE_ONEGUESTMORE","Das sind %s Gäste und ein Mitglied");
define("_MB_ONLINEHISTORY_THERE_ONEMEMBERMORE","Das sind ein Gast und %s Mitglieder");
define("_MB_ONLINEHISTORY_THERE_NOGUESTMORE","Das sind %s Gäste.");
define("_MB_ONLINEHISTORY_THERE_NOMEMBERMORE","Das sind %s Mitglieder");

// %s represents user name
define("_MB_ONLINEHISTORY_URLAS","Willkommen <b>%s</b>");

define("_MB_ONLINEHISTORY_MORE","mehr...");
define("_MB_ONLINEHISTORY_URAU","Willkommen Besucher.");
define("_MB_ONLINEHISTORY_RNOW","Bitte melde dich jetzt an!");
define("_MB_ONLINEHISTORY_CURU","Zur Zeit Registrierte User Online:");
define("_MB_ONLINEHISTORY_DAYS","%s Tage");
define("_MB_ONLINEHISTORY_1DAY","1 Tag");
define("_MB_ONLINEHISTORY_HRS"," %s Stunden");
define("_MB_ONLINEHISTORY_1HR"," 1 Stunde");
define("_MB_ONLINEHISTORY_MINS"," %s Minuten");
define("_MB_ONLINEHISTORY_1MIN"," 1 Minute");
define("_MB_ONLINEHISTORY_SCNDS"," %s Sekunden");
define("_MB_ONLINEHISTORY_AGO"," vorher");
define("_MB_ONLINEHISTORY_MDAYS","Max. Anzahl Tage");
define("_MB_ONLINEHISTORY_MMEM","Max Anzahl Mitglieder");
define("_MB_ONLINEHISTORY_DAYS2","Tage");
define("_MB_ONLINEHISTORY_MEMS","Mitglieder");
define("_MB_ONLINEHISTORY_SLAST","Letzte Mitglieder Online");
define("_MB_ONLINEHISTORY_THERE_SPIDERS","sowie %s Suchmaschine(n).");

define("_MB_ONLINEHISTORY_TIMEGUEST","Zeit in der Gäste aus der History gelöscht werden");
define("_MB_ONLINEHISTORY_TIMEUSER","Zeit in der User aus der History gelöscht werden");

define("_MB_ONLINEHISTORY_UNAME","Richtiger Name statt Username");
define("_MB_ONLINEHISTORY_ALLUSER","Es sind %s Besucher insgesamt online.");
define("_MB_ONLINEHISTORY_MAXUSER","Am %s waren mit %s Besuchern die meisten online.");
?>