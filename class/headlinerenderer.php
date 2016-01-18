<?php
// $Id: headlinerenderer.php 10523 2012-12-23 12:48:50Z beckmi $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

defined('XOOPS_ROOT_PATH') or die('Restricted access');

include_once XOOPS_ROOT_PATH . '/class/template.php';
if (file_exists(XOOPS_ROOT_PATH . '/modules/xoopsheadline/language/' . $GLOBALS['xoopsConfig']['language'] . '/main.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/xoopsheadline/language/' . $GLOBALS['xoopsConfig']['language'] . '/main.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/xoopsheadline/language/english/main.php';
}

class XoopsHeadlineRenderer
{
  // holds reference to xoopsheadline class object
  protected $hl;

  protected $tpl;

  protected $feed;

  protected $block;

  protected $errors = array();

  // RSS2 SAX parser
  protected $parser;

  public function __construct(&$headline)
  {
    $this->hl =& $headline;
    $this->tpl = new XoopsTpl();
  }

  public function XoopsHeadlineRenderer(&$headline)
  {
      $this->__construct($headline);
  }
  public function updateCache()
  {
    /**
     * Update cache - first try using fopen and then cURL
     */
    $retval = false;
    if (!$fp = @fopen($this->hl->getVar('headline_rssurl'), 'r')) {
        // failed open using fopen, now try cURL
        $ch = curl_init($this->hl->getVar('headline_rssurl'));
        if (curl_setopt($ch, CURLOPT_RETURNTRANSFER, true)) {
            if (!$data = curl_exec($ch)) {
                curl_close($ch);
                $errmsg = sprintf(_MD_HEADLINES_NOTOPEN, $this->hl->getVar('headline_rssurl'));
                $this->_setErrors($errmsg);
            } else {
                curl_close($ch);
                $this->hl->setVar('headline_xml', $this->convertToUtf8($data));
                $this->hl->setVar('headline_updated', time());
                $headline_handler =& xoops_getmodulehandler('headline', 'xoopsheadline');
                $retval = $headline_handler->insert($this->hl);
            }
        } else {
            $this->_setErrors(_MD_HEADLINES_BADOPT);
        }
    } else {  // successfully openned file using fopen
        $data = '';
        while (!feof ($fp)) {
            $data .= fgets($fp, 4096);
        }
        fclose ($fp);
        $this->hl->setVar('headline_xml', $this->convertToUtf8($data));
        $this->hl->setVar('headline_updated', time());
        $headline_handler =& xoops_getmodulehandler('headline', 'xoopsheadline');
        $retval = $headline_handler->insert($this->hl);
    }

    return $retval;
  }

  public function renderFeed($force_update = false)
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
        $channel_data =& $this->parser->getChannelData();
        array_walk($channel_data, array($this, 'convertFromUtf8'));
        $this->tpl->assign_by_ref('channel', $channel_data);
        if (1 == $this->hl->getVar('headline_mainimg')) {
            $image_data =& $this->parser->getImageData();
            array_walk($image_data, array($this, 'convertFromUtf8'));
            $max_width = 256;
            $max_height = 92;
            if (!isset($image_data['height']) || !isset($image_data['width'])) {
                if ($image_size = @getimagesize($image_data['url'])) {
                    $image_data['width']  = $image_size[0];
                    $image_data['height'] = $image_size[1];
                }
            }
            if (array_key_exists('height', $image_data) && array_key_exists('width', $image_data) && ($image_data['width'] > 0)) {
                $width_ratio = $image_data['width'] / $max_width;
                $height_ratio = $image_data['height'] / $max_height;
                $scale = max($width_ratio, $height_ratio);
                if ($scale > 1) {
                    $image_data['width']  = intval($image_data['width'] / $scale);
                    $image_data['height'] = intval($image_data['height'] / $scale);
                }
            }
            $this->tpl->assign_by_ref('image', $image_data);
        }
        if (1 == $this->hl->getVar('headline_mainfull')) {
            $this->tpl->assign('show_full', true);
        } else {
            $this->tpl->assign('show_full', false);
        }
        $items =& $this->parser->getItems();
        $count = count($items);
        $max = ($count > $this->hl->getVar('headline_mainmax')) ? $this->hl->getVar('headline_mainmax') : $count;
        for ($i = 0; $i < $max; $i++) {
            array_walk($items[$i], array($this, 'convertFromUtf8'));
            $this->tpl->append_by_ref('items', $items[$i]);
        }
        $this->tpl->assign(array('lang_lastbuild' => _MD_HEADLINES_LASTBUILD, 'lang_language' => _MD_HEADLINES_LANGUAGE, 'lang_description' => _MD_HEADLINES_DESCRIPTION, 'lang_webmaster' => _MD_HEADLINES_WEBMASTER, 'lang_category' => _MD_HEADLINES_CATEGORY, 'lang_generator' => _MD_HEADLINES_GENERATOR, 'lang_title' => _MD_HEADLINES_TITLE, 'lang_pubdate' => _MD_HEADLINES_PUBDATE, 'lang_description' => _MD_HEADLINES_DESCRIPTION, 'lang_more' => _MORE));
        $this->feed =& $this->tpl->fetch('db:xoopsheadline_feed.html');
        $retval = true;
    }

    return $retval;
  }

  public function renderBlock($force_update = false)
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
        $channel_data =& $this->parser->getChannelData();
        array_walk($channel_data, array($this, 'convertFromUtf8'));
        $this->tpl->assign_by_ref('channel', $channel_data);
        if ($this->hl->getVar('headline_blockimg') == 1) {
            $image_data =& $this->parser->getImageData();
            array_walk($image_data, array($this, 'convertFromUtf8'));
            $this->tpl->assign_by_ref('image', $image_data);
        }
        $items =& $this->parser->getItems();
        $count = count($items);
        $max = ($count > $this->hl->getVar('headline_blockmax')) ? $this->hl->getVar('headline_blockmax') : $count;
        for ($i = 0; $i < $max; $i++) {
            array_walk($items[$i], array($this, 'convertFromUtf8'));
            $this->tpl->append_by_ref('items', $items[$i]);
        }
        $this->tpl->assign(array('site_name' => $this->hl->getVar('headline_name'), 'site_url' => $this->hl->getVar('headline_url'), 'site_id' => $this->hl->getVar('headline_id')));
        $this->block =& $this->tpl->fetch('file:' . XOOPS_ROOT_PATH . '/modules/xoopsheadline/blocks/headline_block.html');
        $retval = true;
    }

    return $retval;
  }

  protected function &_parse()
  {
    $retval = true;
    if (!isset($this->parser)) {
        include_once XOOPS_ROOT_PATH . '/class/xml/rss/xmlrss2parser.php';
        $this->parser = new XoopsXmlRss2Parser($this->hl->getVar('headline_xml'));
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

  public function &getFeed()
  {
    return $this->feed;
  }

  public function &getBlock()
  {
    return $this->block;
  }

  protected function _setErrors($err)
  {
    $this->errors[] = $err;
  }

  public function &getErrors($ashtml = true)
  {
    if (!$ashtml) {
      $retval = $this->errors;
    } else {
        $retval = '';
        if (count($this->errors) > 0) {
          foreach ($this->errors as $error) {
            $retval .= $error . '<br />';
          }
        }
    }

    return $retval;
  }

  // abstract
  // overide this method in /language/your_language/headlinerenderer.php
  // this method is called by the array_walk function
  // return void
  public function convertFromUtf8(&$value, $key)
  {
  }

  // abstract
  // overide this method in /language/your_language/headlinerenderer.php
  // return string
  public function &convertToUtf8(&$xmlfile)
  {
    if (strtolower($this->hl->getVar('headline_encoding')) == 'iso-8859-1') {
        $xmlfile = utf8_encode($xmlfile);
    }

    return $xmlfile;
  }
}
