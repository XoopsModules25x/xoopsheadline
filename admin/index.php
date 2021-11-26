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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

use Xmf\Module\Admin;
use Xmf\Request;
use Xmf\Yaml;
use XoopsModules\Xoopsheadline\{
    Common\TestdataButtons,
    Helper,
    Utility
};

/** @var Admin $adminObject */
/** @var Helper $helper */
/** @var Utility $utility */

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$adminObject = Admin::getInstance();

//-----------------------

$helper = Helper::getInstance();
$headlineHandler = $helper->getHandler('Headline');

$totalHls          = $headlineHandler->getCount();
$totalDisplayedHls = $headlineHandler->getCount(new \Criteria('headline_display', 1, '='));
$totalHiddenHls    = $totalHls - $totalDisplayedHls;

$displayedAsBlock = $headlineHandler->getCount(new \Criteria('headline_asblock ', 1, '='));

$adminObject->addInfoBox(_MD_XOOPSHEADLINE_XOOPSHEADLINECONF);
$adminObject->addInfoBoxLine(sprintf(_MD_XOOPSHEADLINE_TOTALDISPLAYED, $totalDisplayedHls), '', 'Green');
$adminObject->addInfoBoxLine(sprintf(_MD_XOOPSHEADLINE_TOTALHIDDEN, $totalHiddenHls), '', 'Red');
$adminObject->addInfoBoxLine(sprintf(_MD_XOOPSHEADLINE_TOTALHLS, $totalHls), '');
$adminObject->addInfoBoxLine(sprintf(_MD_XOOPSHEADLINE_TOTALASBLOCK, $displayedAsBlock), '', 'Green');

//----------------------------

$adminObject->displayNavigation(basename(__FILE__));

//------------- Test Data Buttons ----------------------------
if ($helper->getConfig('displaySampleButton')) {
    TestdataButtons::loadButtonConfig($adminObject);
    $adminObject->displayButton('left', '');
}
$op = Request::getString('op', 0, 'GET');
switch ($op) {
    case 'hide_buttons':
        TestdataButtons::hideButtons();
        break;
    case 'show_buttons':
        TestdataButtons::showButtons();
        break;
}
//------------- End Test Data Buttons ----------------------------

$adminObject->displayIndex();

require_once __DIR__ . '/admin_footer.php';
