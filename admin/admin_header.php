<?php
/**
 * XOOPS Headline module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright ::  XOOPS Project (https://xoops.org)
 * @license   ::    {@link https://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package   ::    xoopsheadline
 * @subpackage:: admin
 * @since     ::      1.10
 * @author    ::     XOOPS Development Team
 **/


use Xmf\Module\Admin;
use XoopsModules\Xoopsheadline\{
    Helper
};
/** @var Admin $adminObject */
/** @var Helper $helper */

require dirname(__DIR__) . '/preloads/autoloader.php';

require dirname(__DIR__, 3) . '/include/cp_header.php';
//require dirname(__DIR__, 3) . '/class/xoopsformloader.php';
require  dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
$helper = Helper::getInstance();

$adminObject = Admin::getInstance();

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');
$helper->loadLanguage('main');

