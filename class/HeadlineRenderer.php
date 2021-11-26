<?php

declare(strict_types=1);

namespace XoopsModules\Xoopsheadline;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use think\console\command\Help;

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */


require_once XOOPS_ROOT_PATH . '/class/template.php';

$helper = Helper::getInstance();
$helper->loadLanguage('main');

/**
 * Class HeadlineRenderer
 */
class HeadlineRenderer
{
    // holds reference to xoopsheadline class object
    protected $headline;
    protected $tpl;
    protected $feed;
    protected $block;
    protected $errors = [];
    // RSS2 SAX parser
    protected $parser;

    /**
     * HeadlineRenderer constructor.
     * @param $headline
     */
    public function __construct(&$headline)
    {
        $this->hl  = &$headline;
        $this->tpl = new \XoopsTpl();
    }

    /**
     * @return int|bool
     */
    public function updateCache()
    {
        $helper = Helper::getInstance();
        /**
         * Update cache - first try using fopen and then cURL
         */
        $retval = false;
        if ($fp = @\fopen($this->hl->getVar('headline_rssurl'), 'r')) {  // successfully openned file using fopen
            $data = '';
            while (!\feof($fp)) {
                $data .= \fgets($fp, 4096);
            }
            \fclose($fp);
            $this->hl->setVar('headline_xml', $this->convertToUtf8($data));
            $this->hl->setVar('headline_updated', \time());
            $headlineHandler = $helper->getHandler('Headline');
            $retval          = $headlineHandler->insert($this->hl);
        } else {
            // failed open using fopen, now try cURL
            $ch = \curl_init($this->hl->getVar('headline_rssurl'));
            if (\curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true)) {
                if ($data = \curl_exec($ch)) {
                    \curl_close($ch);
                    $this->hl->setVar('headline_xml', $this->convertToUtf8($data));
                    $this->hl->setVar('headline_updated', \time());
                    $headlineHandler = $helper->getHandler('Headline');
                    $retval          = $headlineHandler->insert($this->hl);
                } else {
                    \curl_close($ch);
                    $errmsg = \sprintf(\_MD_HEADLINES_NOTOPEN, $this->hl->getVar('headline_rssurl'));
                    $this->_setErrors($errmsg);
                }
            } else {
                $this->_setErrors(\_MD_HEADLINES_BADOPT);
            }
        }

        return $retval;
    }

    public function renderFeed(bool $force_update = false): bool
    {
        $retval = false;
        if ($force_update || $this->hl->cacheExpired()) {
            if (!$this->updateCache()) {
                return $retval;
            }
        }
        if ($this->_parse()) {
            $this->tpl->clear_all_assign();
            $this->tpl->assign('xoops_url', XOOPS_URL);
            $channel_data = $this->parser->getChannelData();
            \array_walk($channel_data, [$this, 'convertFromUtf8']);
            $this->tpl->assign_by_ref('channel', $channel_data);
            if (1 == $this->hl->getVar('headline_mainimg')) {
                $image_data = $this->parser->getImageData();
                \array_walk($image_data, [$this, 'convertFromUtf8']);
                $max_width  = 256;
                $max_height = 92;
                if (!isset($image_data['height']) || !isset($image_data['width'])) {
                    $image_size = @\getimagesize($image_data['url']);
                    if ($image_size) {
                        $image_data['width']  = $image_size[0];
                        $image_data['height'] = $image_size[1];
                    }
                }
                if (\array_key_exists('height', $image_data) && \array_key_exists('width', $image_data)
                    && ($image_data['width'] > 0)) {
                    $width_ratio  = $image_data['width'] / $max_width;
                    $height_ratio = $image_data['height'] / $max_height;
                    $scale        = \max($width_ratio, $height_ratio);
                    if ($scale > 1) {
                        $image_data['width']  = (int)($image_data['width'] / $scale);
                        $image_data['height'] = (int)($image_data['height'] / $scale);
                    }
                }
                $this->tpl->assign_by_ref('image', $image_data);
            }
            if (1 == $this->hl->getVar('headline_mainfull')) {
                $this->tpl->assign('show_full', true);
            } else {
                $this->tpl->assign('show_full', false);
            }
            $items = $this->parser->getItems();
            $count = \count($items);
            $max   = ($count > $this->hl->getVar('headline_mainmax')) ? $this->hl->getVar('headline_mainmax') : $count;
            for ($i = 0; $i < $max; ++$i) {
                \array_walk($items[$i], [$this, 'convertFromUtf8']);
                $this->tpl->append_by_ref('items', $items[$i]);
            }
            $this->tpl->assign(
                [
                    'lang_lastbuild'   => \_MD_HEADLINES_LASTBUILD,
                    'lang_language'    => \_MD_HEADLINES_LANGUAGE,
                    'lang_description' => \_MD_HEADLINES_DESCRIPTION,
                    'lang_webmaster'   => \_MD_HEADLINES_WEBMASTER,
                    'lang_category'    => \_MD_HEADLINES_CATEGORY,
                    'lang_generator'   => \_MD_HEADLINES_GENERATOR,
                    'lang_title'       => \_MD_HEADLINES_TITLE,
                    'lang_pubdate'     => \_MD_HEADLINES_PUBDATE,
                    //                                   'lang_description2' => _MD_HEADLINES_DESCRIPTION2,
                    'lang_more'        => _MORE,
                ]
            );
            $this->feed = $this->tpl->fetch('db:xoopsheadline_feed.tpl');
            $retval     = true;
        }

        return $retval;
    }

    public function renderBlock(bool $force_update = false): bool
    {
        $retval = false;
        if ($force_update || $this->hl->cacheExpired()) {
            if (!$this->updateCache()) {
                return $retval;
            }
        }
        if ($this->_parse()) {
            $this->tpl->clear_all_assign();
            $this->tpl->assign('xoops_url', XOOPS_URL);
            $channel_data = $this->parser->getChannelData();
            \array_walk($channel_data, [$this, 'convertFromUtf8']);
            $this->tpl->assign_by_ref('channel', $channel_data);
            if (1 == $this->hl->getVar('headline_blockimg')) {
                $image_data = $this->parser->getImageData();
                \array_walk($image_data, [$this, 'convertFromUtf8']);
                $this->tpl->assign_by_ref('image', $image_data);
            }
            $items = $this->parser->getItems();
            $count = \count($items);
            $max   = ($count > $this->hl->getVar('headline_blockmax')) ? $this->hl->getVar('headline_blockmax') : $count;
            for ($i = 0; $i < $max; ++$i) {
                \array_walk($items[$i], [$this, 'convertFromUtf8']);
                $this->tpl->append_by_ref('items', $items[$i]);
            }
            $this->tpl->assign(
                [
                    'site_name' => $this->hl->getVar('headline_name'),
                    'site_url'  => $this->hl->getVar('headline_url'),
                    'site_id'   => $this->hl->getVar('headline_id'),
                ]
            );

            $this->block = $this->tpl->fetch('db:blocks/headline_block.tpl');
            $retval      = true;
        }

        return $retval;
    }

    protected function &_parse(): bool
    {
        $retval = true;
        if (!isset($this->parser)) {
            require_once XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php';
            $temp         = $this->hl->getVar('headline_xml');
            $this->parser = new \XoopsXmlRss2Parser($temp);
            switch ($this->hl->getVar('headline_encoding')) {
                case 'utf-8':
                    $this->parser->useUtfEncoding();
                    break;
                case 'us-ascii':
                    $this->parser->useAsciiEncoding();
                    break;
                default:
                    $this->parser->useIsoEncoding();
                    break;
            }
            $result = $this->parser->parse();
            if (!$result) {
                $this->_setErrors($this->parser->getErrors(false));
                unset($this->parser);
                $retval = false;
            }
        }

        return $retval;
    }

    /**
     * @return mixed
     */
    public function &getFeed()
    {
        return $this->feed;
    }

    /**
     * @return mixed
     */
    public function &getBlock()
    {
        return $this->block;
    }

    /**
     * @param $err
     */
    protected function _setErrors($err): void
    {
        $this->errors[] = $err;
    }

    /**
     * @return array|string
     */
    public function &getErrors(bool $ashtml = true)
    {
        if ($ashtml) {
            $retval = '';
            if (\count($this->errors) > 0) {
                foreach ($this->errors as $error) {
                    $retval .= $error . '<br>';
                }
            }
        } else {
            $retval = $this->errors;
        }

        return $retval;
    }

    // abstract
    // overide this method in /language/your_language/headlinerenderer.php
    // this method is called by the array_walk function
    // return void

    /**
     * @param $value
     * @param $key
     */
    public function convertFromUtf8(&$value, $key): void
    {
    }

    // abstract
    // overide this method in /language/your_language/headlinerenderer.php
    // return string

    public function &convertToUtf8(string &$xmlfile): string
    {
        if ('iso-8859-1' === \mb_strtolower($this->hl->getVar('headline_encoding'))) {
            $xmlfile = utf8_encode($xmlfile);
        }

        return $xmlfile;
    }
}
