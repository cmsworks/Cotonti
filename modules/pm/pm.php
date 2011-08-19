<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

/**
 * Private messages module main
 *
 * @package pm
 * @version 0.9.2
 * @author Neocrome, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

// Environment setup
define('COT_PM', true);
$env['location'] = 'private_messages';

// Bootstrap
require_once $cfg['system_dir'] . '/common.php';

// Additional API requirements
require_once cot_incfile('extrafields');
require_once cot_incfile('users', 'module');

// Self requirements
require_once cot_incfile('pm', 'module');

// Mode choice
if (!in_array($m, array('send', 'message')))
{
	$m = 'list';
}

require_once cot_incfile('pm', 'module', $m);
?>
