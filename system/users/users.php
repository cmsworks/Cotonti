<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

/**
 * Users module main
 *
 * @package users
 * @version 0.9.4
 * @author Neocrome, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

// Environment
define('COT_USERS', TRUE);
$env['location'] = 'users';

// Bootstrap
require_once $cfg['system_dir'] . '/common.php';

require_once cot_incfile('extrafields');
require_once cot_incfile('uploads');

require_once cot_incfile('users', 'module');

if (!in_array($m, array('auth', 'details', 'edit', 'logout', 'passrecover', 'profile', 'register')))
{
	$m = 'main';
}

include cot_incfile('users', 'module', $m);

?>
