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
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Xoopsheadline\{
    Helper,
    XoopsheadlineUtility
};
/** @var Admin $adminObject */
/** @var Helper $helper */

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

$op = 'list';

if (Request::hasVar('op', 'GET') && ('delete' === $_GET['op'] || 'edit' === $_GET['op'] || 'flush' === $_GET['op'])) {
    $op          = $_GET['op'];
    $headline_id = Request::getInt('headline_id', 0, 'GET');
}

/* headline_id - an array of integers
 * headline_display
 * headline_asblock
 */
//@TODO: Replace following routine by only importing known variables
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

switch ($op) {
    case 'list':
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $headlineHandler    = $helper->getHandler('Headline');
        $criteria = new \CriteriaCompo();
        $criteria->setSort('headline_weight');
        $criteria->setOrder('ASC');
        $headlines = $headlineHandler->getObjects($criteria);
        $count     = count($headlines);

        global $thisModDir;

        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        echo "\n<div style='margin-bottom: 2em;'>\n"
             . '<h4>'
             . _AM_HEADLINES_HEADLINES
             . "</h4>\n"
             . "<form name='xoopsheadline_form' action='main.php' method='post'>\n"
             . "  <table class='outer' style='margin: 1px;' id='hllist'>\n"
             . "    <thead><tr style='text-align: left;'>\n"
             . '      <th>'
             . _AM_HEADLINES_ORDER
             . "</th>\n"
             . '      <th>'
             . _AM_HEADLINES_SITENAME
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_CACHETIME
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_ENCODING
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_DISPLAY
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_ASBLOCK
             . "</th>\n"
             . "      <th class='center'>"
             . _AM_HEADLINES_ACTIONS
             . "</th>\n"
             . "      <th>&nbsp;</th>\n"
             . "    </tr></thead>\n";
        $cachetime = [
            '3600'    => sprintf(_HOUR, 1),
            '18000'   => sprintf(_HOURS, 5),
            '86400'   => sprintf(_DAY, 1),
            '259200'  => sprintf(_DAYS, 3),
            '604800'  => sprintf(_WEEK, 1),
            '2592000' => sprintf(_MONTH, 1),
        ];
        $encodings = ['utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII'];
        $tdclass   = 'odd';
        echo '    <tbody>';
        for ($i = 0; $i < $count; ++$i) {
            echo "    <tr>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><input style='text-align: right;' type='text' maxlength='3' size='4' name='headline_weight[]' value='"
                 . $headlines[$i]->getVar('headline_weight')
                 . "'></td>\n"
                 . "      <td class='{$tdclass}' style='vertical-align: middle; padding-left: 1em;'><a href='"
                 . XOOPS_URL
                 . "/modules/{$thisModDir}/index.php?id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'>"
                 . $headlines[$i]->getVar('headline_name')
                 . "</a></td>\n"
                 //               . "      <td class='{$tdclass}' style='vertical-align: middle; padding-left: 1em;'>" . $headlines[$i]->getVar('headline_name') . "</td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><select name=\"headline_cachetime[]\">";
            foreach ($cachetime as $value => $name) {
                $sel = ($value == $headlines[$i]->getVar('headline_cachetime')) ? ' selected="selected"' : '';
                echo "<option value=\"{$value}\"{$sel}>{$name}</option>";
            }
            echo "</select></td>\n" . "      <td class='center {$tdclass}' style='vertical-align: middle;'><select name=\"headline_encoding[]\">";
            foreach ($encodings as $value => $name) {
                $sel = ($value == $headlines[$i]->getVar('headline_encoding')) ? ' selected = "selected"' : '';
                echo "<option value=\"{$value}\"{$sel}>{$name}</option>";
            }
            $chkd = (1 == $headlines[$i]->getVar('headline_display')) ? ' checked' : '';
            $chkb = (1 == $headlines[$i]->getVar('headline_asblock')) ? ' checked' : '';
            echo "</select></td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><input type=\"checkbox\" value=\"1\" name=\"headline_display["
                 . $headlines[$i]->getVar('headline_id')
                 . "]\"{$chkd}></td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><input type=\"checkbox\" value=\"1\" name=\"headline_asblock["
                 . $headlines[$i]->getVar('headline_id')
                 . "]\"{$chkb}></td>\n"
                 . "      <td class='center {$tdclass}' style='vertical-align: middle;'><a href='main.php?op=edit&amp;headline_id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'><img src={$pathIcon16}/edit.png alt='"
                 . _EDIT
                 . "' title='"
                 . _EDIT
                 . "'></a>&nbsp;\n"
                 . "          <a href='main.php?op=delete&amp;headline_id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'><img src={$pathIcon16}/delete.png alt='"
                 . _DELETE
                 . "' title='"
                 . _DELETE
                 . "'></a>\n"
                 . "          <a href='main.php?op=flush&amp;headline_id="
                 . $headlines[$i]->getVar('headline_id')
                 . "'><img src='../assets/images/reload.png' alt='"
                 . _AM_HEADLINES_CACHEFL
                 . "' title='"
                 . _AM_HEADLINES_CACHEFL
                 . "'></a>\n"
                 . "          <input type='hidden' name='headline_id[]' value='"
                 . $headlines[$i]->getVar('headline_id')
                 . "'>\n"
                 . "      </td>\n"
                 . "    </tr>\n";
            $tdclass = ('odd' === $tdclass) ? 'even' : 'odd';
        }

        echo "    </tbody>\n"
             . "    <tfoot><tr><td class='center {$tdclass}' colspan='7' style='padding: .5em;'>\n"
             . "      <input type='hidden' name='op' value='update'>\n"
             . "      <input type='submit' name='headline_submit' value='"
             . _AM_HEADLINES_UPDATE
             . "'>\n"
             . "    </td></tr></tfoot>\n"
             . "  </table>\n"
             . "</form>\n"
             . "</div>\n"
             . "<div style='margin-bottom: 1em;'>\n"
             . "<h4 style='padding-left: 1em;'>"
             . _AM_HEADLINES_ADDHEADL
             . "</h4>\n";
        $form = new \XoopsThemeForm(_AM_HEADLINES_ADDHEADL, 'xoopsheadline_form_new', 'main.php', 'post', true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_SITENAME, 'headline_name', 50, 255), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URL, 'headline_url', 50, 255, 'http://'), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URLEDFXML, 'headline_rssurl', 50, 255, 'http://'), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_ORDER, 'headline_weight', 4, 3, 0));

        $enc_sel = new \XoopsFormSelect(_AM_HEADLINES_ENCODING, 'headline_encoding', 'utf-8');
        $enc_sel->addOptionArray($encodings);
        $form->addElement($enc_sel);

        $cache_sel = new \XoopsFormSelect(_AM_HEADLINES_CACHETIME, 'headline_cachetime', 86400);
        $cache_sel->addOptionArray(
            [
                '3600'    => _HOUR,
                '18000'   => sprintf(_HOURS, 5),
                '86400'   => _DAY,
                '259200'  => sprintf(_DAYS, 3),
                '604800'  => _WEEK,
                '2592000' => _MONTH,
            ]
        );
        $form->addElement($cache_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_MAINSETT . '</span>', 'center');
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPLAY, 'headline_display', 1, _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_mainimg', 0, _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPFULL, 'headline_mainfull', 0, _YES, _NO));

        $mmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_mainmax', 10);
        $mmax_sel->addOptionArray(
            [
                '1'  => 1,
                '5'  => 5,
                '10' => 10,
                '15' => 15,
                '20' => 20,
                '25' => 25,
                '30' => 30,
            ]
        );
        $form->addElement($mmax_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_BLOCKSETT . '</span>', 'center');
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_ASBLOCK, 'headline_asblock', 1, _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_blockimg', 0, _YES, _NO));

        $bmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_blockmax', 5);
        $bmax_sel->addOptionArray(
            [
                '1'  => 1,
                '5'  => 5,
                '10' => 10,
                '15' => 15,
                '20' => 20,
                '25' => 25,
                '30' => 30,
            ]
        );
        $form->addElement($bmax_sel);

        $form->insertBreak();
        $form->addElement(new \XoopsFormHidden('op', 'addgo'));
        $form->addElement(new \XoopsFormButtonTray('headline_submit', _SUBMIT));
        $form->display();
        echo "</div>\n";
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'update':
        $headlineHandler = $helper->getHandler('Headline');
        $i     = 0;
        $msg   = '';
        foreach ($headline_id as $id) {
            $headline = $headlineHandler->get($id);
            if (!is_object($headline)) {
                $i++;
                continue;
            }
            $headline_display[$id] = empty($headline_display[$id]) ? 0 : $headline_display[$id];
            $headline_asblock[$id] = empty($headline_asblock[$id]) ? 0 : $headline_asblock[$id];
            $old_cachetime         = $headline->getVar('headline_cachetime');
            $headline->setVar('headline_cachetime', $headline_cachetime[$i]);
            $old_display = $headline->getVar('headline_display');
            $headline->setVar('headline_display', $headline_display[$id]);
            $headline->setVar('headline_weight', $headline_weight[$i]);
            $old_asblock = $headline->getVar('headline_asblock');
            $headline->setVar('headline_asblock', $headline_asblock[$id]);
            $old_encoding = $headline->getVar('headline_encoding');
            if (!$headlineHandler->insert($headline)) {
                $msg .= '<br>' . sprintf(_AM_HEADLINES_FAILUPDATE, $headline->getVar('headline_name'));
            } elseif ('' === $headline->getVar('headline_xml')) {
                    $renderer = XoopsheadlineUtility::getRenderer($headline);
                    if (!$renderer->updateCache()) {
                        xoops_error($headline->getErrors(true));
                        require_once __DIR__ . '/admin_footer.php';
                    }
            }
            $i++;
        }
        if ('' != $msg) {
            xoops_cp_header();
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error($msg);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'addgo':
        if ($GLOBALS['xoopsSecurity']->check()) {
            $headlineHandler = $helper->getHandler('Headline');
            $headline    = $headlineHandler->create();
            $headline->setVar('headline_name', $headline_name);
            $headline->setVar('headline_url', $headline_url);
            $headline->setVar('headline_rssurl', $headline_rssurl);
            $headline->setVar('headline_display', $headline_display);
            $headline->setVar('headline_weight', $headline_weight);
            $headline->setVar('headline_asblock', $headline_asblock);
            $headline->setVar('headline_encoding', $headline_encoding);
            $headline->setVar('headline_cachetime', $headline_cachetime);
            $headline->setVar('headline_mainfull', $headline_mainfull);
            $headline->setVar('headline_mainimg', $headline_mainimg);
            $headline->setVar('headline_mainmax', $headline_mainmax);
            $headline->setVar('headline_blockimg', $headline_blockimg);
            $headline->setVar('headline_blockmax', $headline_blockmax);
            $headline->setVar('headline_xml', $headline_blockmax);
            $hlIdx = $headlineHandler->insert($headline);
            if (!$hlIdx) {
                $msg         = sprintf(_AM_HEADLINES_FAILUPDATE, $headline->getVar('headline_name'));
                $msg         .= '<br>' . $headline->getErrors();
                $adminObject = Admin::getInstance();
                $adminObject->displayNavigation(basename(__FILE__));
                echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
                xoops_error($msg);
                require_once __DIR__ . '/admin_footer.php';
                exit();
            }
            if ('' == $headline->getVar('headline_xml')) {
                $hlObj    = $headlineHandler->get($hlIdx);
                $renderer = XoopsheadlineUtility::getRenderer($hlObj);
                if (!$renderer->updateCache()) {
                    xoops_error($hlObj->getErrors(true));
                    require_once __DIR__ . '/admin_footer.php';
                }
            }
        } else {
            redirect_header('main.php', 2, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'edit':
        if ($headline_id <= 0) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $headlineHandler = $helper->getHandler('Headline');
        $headline    = $headlineHandler->get($headline_id);
        if (!is_object($headline)) {
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $form = new \XoopsThemeForm(_AM_HEADLINES_EDITHEADL, 'xoopsheadline_form', 'main.php', 'post', true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_SITENAME, 'headline_name', 100, 255, $headline->getVar('headline_name')), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URL, 'headline_url', 100, 255, $headline->getVar('headline_url')), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_URLEDFXML, 'headline_rssurl', 100, 255, $headline->getVar('headline_rssurl')), true);
        $form->addElement(new \XoopsFormText(_AM_HEADLINES_ORDER, 'headline_weight', 4, 3, $headline->getVar('headline_weight')));

        $enc_sel = new \XoopsFormSelect(_AM_HEADLINES_ENCODING, 'headline_encoding', $headline->getVar('headline_encoding'));
        $enc_sel->addOptionArray(['utf-8' => 'UTF-8', 'iso-8859-1' => 'ISO-8859-1', 'us-ascii' => 'US-ASCII']);
        $form->addElement($enc_sel);

        $cache_sel = new \XoopsFormSelect(_AM_HEADLINES_CACHETIME, 'headline_cachetime', $headline->getVar('headline_cachetime'));
        $cache_sel->addOptionArray(
            [
                '3600'    => _HOUR,
                '18000'   => sprintf(_HOURS, 5),
                '86400'   => _DAY,
                '259200'  => sprintf(_DAYS, 3),
                '604800'  => _WEEK,
                '2592000' => _MONTH,
            ]
        );
        $form->addElement($cache_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_MAINSETT . '</span>', 'center');
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPLAY, 'headline_display', $headline->getVar('headline_display'), _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_mainimg', $headline->getVar('headline_mainimg'), _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPFULL, 'headline_mainfull', $headline->getVar('headline_mainfull'), _YES, _NO));

        $mmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_mainmax', $headline->getVar('headline_mainmax'));
        $mmax_sel->addOptionArray(
            [
                '1'  => 1,
                '5'  => 5,
                '10' => 10,
                '15' => 15,
                '20' => 20,
                '25' => 25,
                '30' => 30,
            ]
        );
        $form->addElement($mmax_sel);

        $form->insertBreak('<span style="font-weight: bold; line-height: 3em;">' . _AM_HEADLINES_BLOCKSETT . '</span>', 'center');
        $form->insertBreak(_AM_HEADLINES_BLOCKSETT);
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_ASBLOCK, 'headline_asblock', $headline->getVar('headline_asblock'), _YES, _NO));
        $form->addElement(new \XoopsFormRadioYN(_AM_HEADLINES_DISPIMG, 'headline_blockimg', $headline->getVar('headline_blockimg'), _YES, _NO));

        $bmax_sel = new \XoopsFormSelect(_AM_HEADLINES_DISPMAX, 'headline_blockmax', $headline->getVar('headline_blockmax'));
        $bmax_sel->addOptionArray(
            [
                '1'  => 1,
                '5'  => 5,
                '10' => 10,
                '15' => 15,
                '20' => 20,
                '25' => 25,
                '30' => 30,
            ]
        );
        $form->addElement($bmax_sel);

        $form->insertBreak();
        $form->addElement(new \XoopsFormHidden('headline_id', $headline->getVar('headline_id')));
        $form->addElement(new \XoopsFormHidden('op', 'editgo'));
        $form->addElement(new \XoopsFormButtonTray('headline_submit', _SUBMIT));
        echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4><br>';
        //echo '<a href="main.php">'. _AM_HEADLINES_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$headline->getVar('headline_name').'<br><br>';
        $form->display();
        require_once __DIR__ . '/admin_footer.php';
        exit();
        break;
    case 'editgo':
        $headline_id = $headline_id;
        if ($headline_id <= 0) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $headlineHandler = $helper->getHandler('Headline');
        $headline    = $headlineHandler->get($headline_id);
        if (!is_object($headline)) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $headline->setVar('headline_name', $headline_name);
        $headline->setVar('headline_url', $headline_url);
        $headline->setVar('headline_encoding', $headline_encoding);
        $headline->setVar('headline_rssurl', $headline_rssurl);
        $headline->setVar('headline_display', $headline_display);
        $headline->setVar('headline_weight', $headline_weight);
        $headline->setVar('headline_asblock', $headline_asblock);
        $headline->setVar('headline_cachetime', $headline_cachetime);
        $headline->setVar('headline_mainfull', $headline_mainfull);
        $headline->setVar('headline_mainimg', $headline_mainimg);
        $headline->setVar('headline_mainmax', $headline_mainmax);
        $headline->setVar('headline_blockimg', $headline_blockimg);
        $headline->setVar('headline_blockmax', $headline_blockmax);

        if (!$GLOBALS['xoopsSecurity']->check() || !$headlineHandler->insert($headline)) {
            $msg         = sprintf(_AM_HEADLINES_FAILUPDATE, $headline->getVar('headline_name'));
            $msg         .= '<br>' . $headline->getErrors();
            $msg         .= '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error($msg);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        if ('' == $headline->getVar('headline_xml')) {
            $renderer = XoopsheadlineUtility::getRenderer($headline);
            if (!$renderer->updateCache()) {
                xoops_error($headline->getErrors(true));
                require_once __DIR__ . '/admin_footer.php';
            }
        }

        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'delete':
        if ($headline_id <= 0) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $headlineHandler = $helper->getHandler('Headline');
        $headline    = $headlineHandler->get($headline_id);
        if (!is_object($headline)) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $name = $headline->getVar('headline_name');
        echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
        //        echo '<a href="main.php">'. _AM_HEADLINES_HLMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$name.'<br><br>';
        xoops_confirm(['op' => 'deletego', 'headline_id' => $headline->getVar('headline_id')], 'main.php', sprintf(_AM_HEADLINES_WANTDEL, $name));
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'deletego':
        $headline_id = $headline_id;
        if ($headline_id <= 0) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $headlineHandler = $helper->getHandler('Headline');
        $headline    = $headlineHandler->get($headline_id);
        if (!is_object($headline)) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        if (!$GLOBALS['xoopsSecurity']->check() || !$headlineHandler->delete($headline)) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(sprintf(_AM_HEADLINES_FAILUPDELETE, $headline->getVar('headline_name')) . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        redirect_header('main.php', 2, _AM_HEADLINES_DBUPDATED);
        break;
    case 'flush':
        if ($headline_id <= 0) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $headlineHandler = $helper->getHandler('Headline');
        $headline    = $headlineHandler->get($headline_id);
        if (!is_object($headline)) {
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $adminObject = Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $name = $headline->getVar('headline_name');
        echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
        xoops_confirm(['op' => 'flushgo', 'headline_id' => $headline->getVar('headline_id')], 'main.php', sprintf(_AM_HEADLINES_WANTFLUSH, $name));
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'flushgo':
        if ($headline_id <= 0) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_INVALIDID);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $headlineHandler = $helper->getHandler('Headline');
        $headline    = $headlineHandler->get($headline_id);
        if (!is_object($headline)) {
            echo '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>';
            xoops_error(_AM_HEADLINES_OBJECTNG);
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        if (!$GLOBALS['xoopsSecurity']->check()) {
            $adminObject = Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__)) . '<h4>' . _AM_HEADLINES_HEADLINES . '</h4>' . "<div style='margin: 1em;'>\n";
            xoops_error(sprintf(_AM_HEADLINES_FAILFLUSH, $headline->getVar('headline_name')) . '<br>' . implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            echo "</div>\n";
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        $renderer = XoopsheadlineUtility::getRenderer($headline);
        if (!$renderer->updateCache()) {
            xoops_error($headline->getErrors(true));
            require_once __DIR__ . '/admin_footer.php';
            exit();
        }
        redirect_header('main.php', 2, _AM_HEADLINES_CACHEUPD);
        break;
}
