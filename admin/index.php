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
 * @copyright    XOOPS Project (http://xoops.org)
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require __DIR__ . '/admin_header.php';
xoops_cp_header();

$indexAdmin = new ModuleAdmin();

//-----------------------
$xhl_handler = xoops_getModuleHandler('headline', $xoopsModule->getVar('dirname', 'n'));

$totalHls          = $xhl_handler->getCount();
$totalDisplayedHls = $xhl_handler->getCount(new Criteria('headline_display', 1, '='));
$totalHiddenHls    = $totalHls - $totalDisplayedHls;

$displayedAsBlock = $xhl_handler->getCount(new Criteria('headline_asblock ', 1, '='));

$indexAdmin->addInfoBox(_MD_HEADLINES_XOOPSHEADLINECONF);
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALDISPLAYED, $totalDisplayedHls, 'Green');
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALHIDDEN, $totalHiddenHls, 'Red');
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALHLS, $totalHls);
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALASBLOCK, $displayedAsBlock, 'Green');

//----------------------------

echo $indexAdmin->addNavigation(basename(__FILE__));
echo $indexAdmin->renderIndex();

include __DIR__ . '/admin_footer.php';
