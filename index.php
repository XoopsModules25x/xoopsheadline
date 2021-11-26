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

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Xoopsheadline\{
    Headline,
    Helper,
    Utility
};
/** @var Helper $helper */

require_once \dirname(__DIR__, 2) . '/mainfile.php';

$helper = Helper::getInstance();

$hlman = $helper->getHandler('Headline');
$hlid  = (!empty($_GET['id']) && (Request::getInt('id', 0, 'GET') > 0)) ? Request::getInt('id', 0, 'GET') : 0;

$GLOBALS['xoopsOption']['template_main'] = 'xoopsheadline_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('headline_display', 1, '='));
$criteria->add(new \Criteria('headline_xml', '', '!='));
switch ((int)$helper->getConfig('sortby')) {
    case 1:
        $criteria->setSort('headline_name');
        $criteria->setOrder('DESC');
        break;
    case 2:
        $criteria->setSort('headline_name');
        $criteria->setOrder('ASC');
        break;
    case 3:
        $criteria->setSort('headline_weight');
        $criteria->setOrder('DESC');
        break;
    case 4:
    default:
        $criteria->setSort('headline_weight');
        $criteria->setOrder('ASC');
        break;
}
$headlines = $hlman->getObjects($criteria);

global $xoopsModule;
$pathIcon16    = Admin::iconUrl('', '16');
$moduleDirName = $xoopsModule->getVar('dirname');

$userIsAdmin = (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid')));
$count       = count($headlines);
for ($i = 0; $i < $count; ++$i) {
    $thisId  = $headlines[$i]->getVar('headline_id');
    $editUrl = $userIsAdmin ? "&nbsp;<a href='" . XOOPS_URL . "/modules/{$moduleDirName}/admin/main.php?op=edit&amp;headline_id={$thisId}'><img src='" . $pathIcon16 . "/edit.png' alt='" . _EDIT . "' title='" . _EDIT . "'></a>" : '';
    $xoopsTpl->append('feed_sites', ['id' => $thisId, 'name' => $headlines[$i]->getVar('headline_name'), 'editurl' => $editUrl]);
}
$xoopsTpl->assign('lang_headlines', _MD_XOOPSHEADLINE_HEADLINES);
if (0 == $hlid) {
    $hlid = $headlines[0]->getVar('headline_id');
}
if ($hlid > 0) {
    /** @var Headline $headline */
    $headline = $hlman->get($hlid);
    if (is_object($headline)) {
        $renderer = Utility::getRenderer($headline);
        if ($renderer->renderFeed()) {
            $xoopsTpl->assign('headline', $renderer->getFeed());
        } elseif (2 == $xoopsConfig['debug_mode']) {
                $xoopsTpl->assign('headline', '<p>' . sprintf(_MD_XOOPSHEADLINE_FAILGET, $headline->getVar('headline_name')) . '<br>' . $renderer->getErrors() . '</p>');
        }
    }
}
require_once XOOPS_ROOT_PATH . '/footer.php';
