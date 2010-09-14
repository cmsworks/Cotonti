<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

/**
 * Forums module
 *
 * @package forums
 * @version 0.7.0
 * @author Neocrome, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

cot_dieifdisabled($cfg['disable_forums']);

// Environment setup
define('COT_FORUMS', TRUE);
$location = 'Forums';

// Additional requirements
cot_require_api('extrafields');
cot_require('users');

// Mode choice
if (!in_array($m, array('topics', 'posts', 'editpost', 'newtopic')))
{
	$m = 'sections';
}

include cot_incfile($z, $m);
?>
