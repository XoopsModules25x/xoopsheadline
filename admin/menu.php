<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xmf\Module\Admin;
use Xmf\Module\Helper;

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

//$path = dirname(dirname(dirname(__DIR__)));
//require_once $path . '/mainfile.php';

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Helper::getHelper('system');
}
$pathIcon32    = Admin::menuIconPath('');
$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

xoops_loadLanguage('modinfo', $moduleDirName);

$adminmenu            = [];
$i                      = 0;
$adminmenu[$i]['title'] = _MI_HEADLINES_MENU_ADMININDEX;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['desc']  = _MI_HEADLINES_MENU_ADMININDEX_DESC;
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
$i++;
$adminmenu[$i]['title'] = _MI_HEADLINES_MENU_ADMINHL;
$adminmenu[$i]['link']  = 'admin/main.php';
$adminmenu[$i]['desc']  = _MI_HEADLINES_MENU_ADMINHL_DESC;
$adminmenu[$i]['icon']  = $pathIcon32 . '/content.png';
$i++;
$adminmenu[$i]['title'] = _MI_HEADLINES_MENU_ADMINABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['desc']  = _MI_HEADLINES_MENU_ADMINABOUT_DESC;
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
