<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.users.update
[END_COT_EXT]
==================== */

/**
 * Hidden groups
 *
 * @package hiddengroups
 * @version 1.0
 * @author Koradhil, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */

(defined('COT_CODE') && defined('COT_ADMIN')) or die('Wrong URL.');

$cache && $cache->db->remove('cot_hiddenusers', 'system');

?>