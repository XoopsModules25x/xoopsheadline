<?php

declare(strict_types=1);
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
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @author      XOOPS Development Team
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

use Xmf\Module\Admin;
use XoopsModules\Xoopsheadline\Helper;
/** @var Helper $helper */

include \dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
$pathModIcon32 = XOOPS_URL .   '/modules/' . $moduleDirName . '/assets/images/icons/32/';
if (is_object($helper->getModule()) && false !== $helper->getModule()->getInfo('modicons32')) {
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}

$adminmenu[] = [
    'title' => _MI_XOOPSHEADLINES_MENU_ADMININDEX,
    'link'  => 'admin/index.php',
    'desc'  => _MI_XOOPSHEADLINES_MENU_ADMININDEX_DESC,
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_XOOPSHEADLINES_MENU_ADMINHL,
    'link'  => 'admin/main.php',
    'desc'  => _MI_XOOPSHEADLINES_MENU_ADMINHL_DESC,
    'icon'  => $pathIcon32 . '/content.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_XOOPSHEADLINES_MENU_ADMINABOUT,
    'link'  => 'admin/about.php',
    'desc'  => _MI_XOOPSHEADLINES_MENU_ADMINABOUT_DESC,
    'icon'  => $pathIcon32 . '/about.png',
];
