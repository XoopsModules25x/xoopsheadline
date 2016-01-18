<?php
// $Id: headline.php 10523 2012-12-23 12:48:50Z beckmi $
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

xoops_load('XoopsheadlineUtility', 'xoopsheadline');

function b_xoopsheadline_show($options)
{
    global $xoopsConfig;
    $hlDir = basename( dirname( dirname( __FILE__ ) ) );

  $module_handler =& xoops_gethandler('module');
  $module =& $module_handler->getByDirname($hlDir);
  $config_handler =& xoops_gethandler('config');
    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    $block = array();
    $hlman =& xoops_getmodulehandler('headline', 'xoopsheadline');
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('headline_asblock',1, '='));
    switch ($moduleConfig['sortby'])
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
    $count = count($headlines);
    for ($i = 0; $i < $count; $i++) {
        $renderer = XoopsheadlineUtility::xoopsheadline_getrenderer($headlines[$i]);
        if (!$renderer->renderBlock()) {
            if (2 == $xoopsConfig['debug_mode']) {
                $block['feeds'][] = sprintf(_MD_HEADLINES_FAILGET, $headlines[$i]->getVar('headline_name')).'<br />'.$renderer->getErrors();
            }
            continue;
        }
        $block['feeds'][] = $renderer->getBlock();
    }

    return $block;
}
