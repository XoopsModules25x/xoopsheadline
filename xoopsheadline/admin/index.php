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
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 * @version    $Id $
 */

require 'admin_header.php';
xoops_cp_header();

$indexAdmin = new ModuleAdmin();

//-----------------------
$xhl_handler =& xoops_getmodulehandler('headline', $xoopsModule->getVar('dirname', 'n'));

$totalHls = $xhl_handler->getCount();
$totalDisplayedHls = $xhl_handler->getCount(new Criteria('headline_display', 1, '='));
$totalHiddenHls = $totalHls - $totalDisplayedHls;

$displayedAsBlock = $xhl_handler->getCount(new Criteria('headline_asblock ', 1, '='));

$indexAdmin->addInfoBox(_MD_HEADLINES_XOOPSHEADLINECONF);
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALDISPLAYED, $totalDisplayedHls, 'Green');
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALHIDDEN, $totalHiddenHls, 'Red');
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALHLS, $totalHls);
$indexAdmin->addInfoBoxLine(_MD_HEADLINES_XOOPSHEADLINECONF, _MD_HEADLINES_TOTALASBLOCK, $displayedAsBlock, 'Green');

//----------------------------

echo $indexAdmin->addNavigation('index.php');
echo $indexAdmin->renderIndex();

include 'admin_footer.php';
