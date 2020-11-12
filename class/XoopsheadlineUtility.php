<?php

namespace XoopsModules\Xoopsheadline;

/**
 *  xoopsheadline Utility Class Elements
 *
 * @copyright ::  ZySpec Incorporated
 * @license   ::    {@link https://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package   ::    xoopsheadline
 * @subpackage:: class
 * @author    ::     unknown, zyspec (zyspec@yahoo.com)
 * @since     ::     File available since release 1.10
 */

/**
 * XoopsheadlineUtility
 *
 * @package  ::   xoopsheadline
 * @author   ::    zyspec (zyspec@yahoo.com)
 * @copyright:: Copyright (c) 2010 ZySpec Incorporated, Herve Thouzard
 * @access::    public
 */
class XoopsheadlineUtility
{
    /**
     * XoopsheadlineUtility
     *
     * Function to create appropriate Renderer
     * (based on locale)
     * @param $headline
     * @return HeadlineRenderer|\HeadlineRendererLocal
     */
    public static function getRenderer($headline)
    {
        if (is_file(XOOPS_ROOT_PATH . '/modules/xoopsheadline/language/' . $GLOBALS['xoopsConfig']['language'] . '/headlinerenderer.php')) {
            require_once XOOPS_ROOT_PATH . '/modules/xoopsheadline/language/' . $GLOBALS['xoopsConfig']['language'] . '/headlinerenderer.php';
            if (class_exists('HeadlineRendererLocal')) {
                $myhl = new HeadlineRendererLocal($headline);

                return $myhl;
            }
        }
        $myhl = new HeadlineRenderer($headline);

        return $myhl;
    }
}
