<?php

declare(strict_types=1);

namespace XoopsModules\Xoopsheadline;

use XoopsModules\Xoopsheadline\Common;
//use XoopsModules\Xoopsheadline\Constants;

/**
 * Class Utility
 */
class Utility extends Common\SysUtility
{
    //--------------- Custom module methods -----------------------------

    /**
     * Utility
     *
     * Function to create appropriate Renderer
     * (based on locale)
     * @param $headline
     * @return HeadlineRenderer
     */
    public static function getRenderer(Headline $headline)
    {
        if (\is_file(XOOPS_ROOT_PATH . '/modules/xoopsheadline/language/' . $GLOBALS['xoopsConfig']['language'] . '/headlinerenderer.php')) {
            require_once XOOPS_ROOT_PATH . '/modules/xoopsheadline/language/' . $GLOBALS['xoopsConfig']['language'] . '/headlinerenderer.php';
            if (\class_exists('HeadlineRendererLocal')) {
                $myhl = new HeadlineRendererLocal($headline);

                return $myhl;
            }
        }
        $myhl = new HeadlineRenderer($headline);

        return $myhl;
    }
}
