<?php

declare(strict_types=1);

$admin_mydirname = \basename(\dirname(__DIR__, 2));

// Admin Module Name  Headlines
define('_AM_XOOPSHEADLINES_DBUPDATED', 'Database Updated Successfully!');
define('_AM_XOOPSHEADLINES_HEADLINES', 'Headlines Configuration');
//define('_AM_XOOPSHEADLINES_HLMAIN','Headline Main');
define('_AM_XOOPSHEADLINES_SITENAME', 'Site Name');
define('_AM_XOOPSHEADLINES_URL', 'URL');
define('_AM_XOOPSHEADLINES_ORDER', 'Order');
define('_AM_XOOPSHEADLINES_ENCODING', 'RSS Encoding');
define('_AM_XOOPSHEADLINES_CACHETIME', 'Cache Time');
define('_AM_XOOPSHEADLINES_CACHEFL', 'Flush Cache');
define('_AM_XOOPSHEADLINES_CACHEUPD', 'Cache Updated');
define('_AM_XOOPSHEADLINES_UPDATE', 'Update');
define('_AM_XOOPSHEADLINES_MAINSETT', 'Main Page Settings');
define('_AM_XOOPSHEADLINES_BLOCKSETT', 'Block Settings');
define('_AM_XOOPSHEADLINES_DISPLAY', 'Display in main page');
define('_AM_XOOPSHEADLINES_DISPIMG', 'Display image');
define('_AM_XOOPSHEADLINES_DISPFULL', 'Display in full view');
define('_AM_XOOPSHEADLINES_DISPMAX', 'Max items to display');
define('_AM_XOOPSHEADLINES_ASBLOCK', 'Display in block');
define('_AM_XOOPSHEADLINES_ADDHEADL', 'Add Headline');
define('_AM_XOOPSHEADLINES_URLEDFXML', 'URL of RDF/RSS file');
define('_AM_XOOPSHEADLINES_EDITHEADL', 'Edit Headline');
define('_AM_XOOPSHEADLINES_WANTDEL', 'Are you sure you want to delete headline for %s?');
define('_AM_XOOPSHEADLINES_WANTFLUSH', 'Are you sure you want to flush the %s headline cache?');
define('_AM_XOOPSHEADLINES_INVALIDID', 'Invalid ID');
define('_AM_XOOPSHEADLINES_OBJECTNG', 'Object does not exist');
define('_AM_XOOPSHEADLINES_FAILUPDATE', 'Failed saving data to database for headline %s');
define('_AM_XOOPSHEADLINES_FAILUPDELETE', 'Failed deleting data from database for headline %s');
define('_AM_XOOPSHEADLINES_FAILFLUSH', 'Failed attempting to flush the cache for %s');
define('_AM_XOOPSHEADLINES_SHOW', 'Show Feed');
define('_AM_XOOPSHEADLINES_HIDE', 'Hide Feed');
define('_AM_XOOPSHEADLINES_ACTIONS', 'Actions');

// Text for Admin footer
define('_AM_XOOPSHEADLINES_ADMIN_FOOTER', "<div class='center smallsmall italic pad5'><strong>{$admin_mydirname}</strong> is maintained by the <a class='tooltip' rel='external' href='https://xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a></div>");

//1.12
define('_AM_XOOPSHEADLINES_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_XOOPSHEADLINES_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_XOOPSHEADLINES_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_XOOPSHEADLINES_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_XOOPSHEADLINES_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_XOOPSHEADLINES_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_XOOPSHEADLINES_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
