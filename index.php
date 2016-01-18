<?php
// $Id: index.php 10640 2013-01-03 02:50:36Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

include '../../mainfile.php';
xoops_load('XoopsheadlineUtility', $xoopsModule->getVar('dirname'));

$hlman =& xoops_getmodulehandler('headline');
$hlid = (!empty($_GET['id']) && (intval($_GET['id']) > 0)) ? intval($_GET['id']) : 0;

$xoopsOption['template_main'] = 'xoopsheadline_index.html';
include XOOPS_ROOT_PATH . '/header.php';

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('headline_display',1, '='));
$criteria->add(new Criteria('headline_xml', '', '!='));
switch (intval($xoopsModuleConfig['sortby']))
{
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
$headlines =& $hlman->getObjects($criteria);

global $xoopsModule;
$pathIcon16 = $xoopsModule->getInfo('icons16');

$userIsAdmin = ((is_object($xoopsUser)) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) ? true : false ;
$count = count($headlines);
for ($i = 0; $i < $count; $i++) {
    $thisId = $headlines[$i]->getVar('headline_id');
    $editUrl = ($userIsAdmin) ?"&nbsp;<a href='" . XOOPS_URL . "/modules/{$modDirName}/admin/main.php?op=edit&amp;headline_id={$thisId}'><img src='" . $pathIcon16 . "/edit.png' alt='" . _EDIT . "' title='" . _EDIT . "'></a>" : '';
    $xoopsTpl->append('feed_sites', array('id' => $thisId, 'name' => $headlines[$i]->getVar('headline_name'), 'editurl' => $editUrl));
}
$xoopsTpl->assign('lang_headlines', _MD_HEADLINES_HEADLINES);
if ( 0 == $hlid ) {
  $hlid = $headlines[0]->getVar('headline_id');
}
if ($hlid > 0) {
  $headline =& $hlman->get($hlid);
  if (is_object($headline)) {
    $renderer = XoopsheadlineUtility::xoopsheadline_getrenderer($headline);
    if (!$renderer->renderFeed()) {
      if (2 == $xoopsConfig['debug_mode']) {
        $xoopsTpl->assign('headline', '<p>'
                                        . sprintf(_MD_HEADLINES_FAILGET, $headline->getVar('headline_name'))
                                        .'<br />'
                                        .$renderer->getErrors()
                                        .'</p>');
      }
    } else {
      $xoopsTpl->assign('headline', $renderer->getFeed());
    }
  }
}
include XOOPS_ROOT_PATH . '/footer.php';
