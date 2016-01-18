<?php
/**
 *  xoopsheadline Utility Class Elements
 *
 * @copyright::  ZySpec Incorporated
 * @license::    {@link http://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package::    xoopsheadline
 * @subpackage:: class
 * @author::     unknown, zyspec (owners@zyspec.com)
 * @version::    $Id:$
 * @since::     File available since release 1.10
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * XoopsheadlineUtility
 *
 * @package::   xoopsheadline
 * @author::    zyspec (owners@zyspec.com)
 * @copyright:: Copyright (c) 2010 ZySpec Incorporated, Herve Thouzard
 * @version::   $Id:$
 * @access::    public
 */
class XoopsheadlineUtility
{
    /**
     * XoopsheadlineUtility
     *
     * Function to create appropriate Renderer
     * (based on locale)
     *
     */
    function &xoopsheadline_getrenderer(&$headline)
    {
      include_once XOOPS_ROOT_PATH.'/modules/xoopsheadline/class/headlinerenderer.php';
      if (file_exists(XOOPS_ROOT_PATH.'/modules/xoopsheadline/language/'.$GLOBALS['xoopsConfig']['language'].'/headlinerenderer.php')) {
        include_once XOOPS_ROOT_PATH.'/modules/xoopsheadline/language/'.$GLOBALS['xoopsConfig']['language'].'/headlinerenderer.php';
        if (class_exists('XoopsHeadlineRendererLocal')) {
            $myhl = new XoopsHeadlineRendererLocal($headline);

            return $myhl;
        }
      }
      $myhl = new XoopsHeadlineRenderer($headline);

      return $myhl;
    }
}
