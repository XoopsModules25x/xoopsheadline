<?php
/**
 * XoopsHeadline module
 * Description: Category Admin file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright ::  The XOOPS Project (http://www.xoops.org)
 * @license   ::    GNU GPL (http://www.gnu.org/licenses/gpl-2.0.html/)
 * @package   ::    xoopsheadline
 * @subpackage:: admin
 * @since     ::      1.10
 * @author    ::     Mage, Mamba
 **/
include_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$aboutAdmin = new ModuleAdmin();

echo $aboutAdmin->addNavigation(basename(__FILE__));
echo $aboutAdmin->renderAbout('xoopsfoundation@gmail.com', false);

include __DIR__ . '/admin_footer.php';
