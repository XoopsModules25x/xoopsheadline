<?php declare(strict_types=1);
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
 * @author       XOOPS Development Team
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

$modversion = [
    'version'             => '1.12.0',
    'module_status'       => 'RC 1',
    'release_date'        => '2021/11/26',
    'name'                => _MI_XOOPSHEADLINE_NAME,
    'description'         => _MI_XOOPSHEADLINE_DESC,
    'official'            => 1,    // maintained by XOOPS Module Development Team
    'author'              => 'Kazumi Ono ( https://xoops.org/ https://www.myweb.ne.jp/ )',
    'credits'             => 'The Xoops Module Development Team',
    'license'             => 'GNU GPL 2.0',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'help'                => 'page=help',
    'image'               => 'assets/images/logoModule.png',
    'dirname'             => $moduleDirName,
    //about
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS',
    'module_website_url'  => 'https://xoops.org',
    'module_website_name' => 'XOOPS',
    'min_php'             => '7.4',
    'min_xoops'           => '2.5.10',
    'min_db'              => ['mysql' => '5.5'],
    'min_admin'           => '1.2',
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
];

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
//$modversion['sqlfile']['mysqli'] = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'xoopsheadline';

// Config Options
$modversion['config'][] = [
    'name'        => 'sortby',
    'title'       => '_MI_XOOPSHEADLINE_SORTORDER',
    'description' => '_MI_XOOPSHEADLINE_SORTORDERDSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 4,
    'options'     => [
        '_MI_XOOPSHEADLINE_SORT1' => 1,
        '_MI_XOOPSHEADLINE_SORT2' => 2,
        '_MI_XOOPSHEADLINE_SORT3' => 3,
        '_MI_XOOPSHEADLINE_SORT4' => 4,
    ],
];

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

// Admin
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Set to 1 if you want to display menu generated by system module
$modversion['system_menu'] = 1;

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_XOOPSHEADLINE_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_XOOPSHEADLINE_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_XOOPSHEADLINE_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_XOOPSHEADLINE_SUPPORT, 'link' => 'page=support'],
];

// ------------------- Blocks ------------------- //
$modversion['blocks'][] = [
    'file'        => 'headline.php',
    'name'        => _MI_XOOPSHEADLINE_BNAME,
    'description' => 'Shows headline news via RDF/RSS news feed',
    'show_func'   => 'b_xoopsheadline_show',
    'template'    => 'xoopsheadline_block_rss.tpl',
];

// Menu
$modversion['hasMain'] = 1;

// ------------------- Templates ------------------- //
$modversion['templates'] = [
    ['file' => 'xoopsheadline_index.tpl', 'description' => ''],
    ['file' => 'xoopsheadline_feed.tpl', 'description' => ''],
    ['file' => 'blocks/headline_block.tpl', 'description' => ''],
];
