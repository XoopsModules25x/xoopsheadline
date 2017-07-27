<?php
//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                         <https://xoops.org/>                               //
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Class XoopsheadlineHeadline
 */
class XoopsheadlineHeadline extends XoopsObject
{

    /**
     * XoopsheadlineHeadline constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->initVar('headline_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('headline_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('headline_url', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('headline_rssurl', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('headline_cachetime', XOBJ_DTYPE_INT, 600, false);
        $this->initVar('headline_asblock', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_display', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_encoding', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('headline_weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_mainimg', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('headline_mainfull', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('headline_mainmax', XOBJ_DTYPE_INT, 10, false);
        $this->initVar('headline_blockimg', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('headline_blockmax', XOBJ_DTYPE_INT, 10, false);
        $this->initVar('headline_xml', XOBJ_DTYPE_SOURCE, null, false);
        $this->initVar('headline_updated', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @return bool
     */
    public function cacheExpired() {
        if (time() - $this->getVar('headline_updated') > $this->getVar('headline_cachetime')) {
            return true;
        }

        return false;
    }
}

/**
 * Class xoopsheadlineHeadlineHandler
 */
class xoopsheadlineHeadlineHandler extends XoopsPersistableObjectHandler
{

    /**
     * xoopsheadlineHeadlineHandler constructor.
     * @param XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db) {
        parent::__construct($db, 'xoopsheadline', 'xoopsheadline' . 'Headline', 'headline_id');
    }
}
