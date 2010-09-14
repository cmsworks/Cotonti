<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

/**
 * Displays statistics info
 *
 * @package statistics
 * @version 0.7.0
 * @author Neocrome, Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

// TODO show statistics for installed modules only

cot_require('forums');
cot_require('page');
cot_require('pfs');
cot_require('pm');
cot_require('polls');

$s = cot_import('s', 'G', 'TXT');

$plugin_title = $L['plu_title'];

$totaldbpages = cot_db_rowcount($db_pages);
$totaldbratings = cot_db_rowcount($db_ratings);
$totaldbratingsvotes = cot_db_rowcount($db_rated);
$totaldbpolls = cot_db_rowcount($db_polls);
$totaldbpollsvotes = cot_db_rowcount($db_polls_voters);
$totaldbposts = cot_db_rowcount($db_forum_posts);
$totaldbtopics = cot_db_rowcount($db_forum_topics);
$totaldbfiles = cot_db_rowcount($db_pfs);
$totaldbusers = cot_db_rowcount($db_users);

$totalpages = cot_stat_get('totalpages');
$totalmailsent = cot_stat_get('totalmailsent');
$totalpmsent = cot_stat_get('totalpms');

$totaldbviews = cot_db_query("SELECT SUM(fs_viewcount) FROM $db_forum_sections");
$totaldbviews = cot_db_result($totaldbviews, 0, "SUM(fs_viewcount)");

$sql = cot_db_query("SELECT SUM(fs_topiccount_pruned) FROM $db_forum_sections");
$totaldbtopicspruned = cot_db_result($sql, 0, "SUM(fs_topiccount_pruned)");

$sql = cot_db_query("SELECT SUM(fs_postcount_pruned) FROM $db_forum_sections");
$totaldbpostspruned = cot_db_result($sql, 0, "SUM(fs_postcount_pruned)");

$totaldbfilesize = cot_db_query("SELECT SUM(pfs_size) FROM $db_pfs");
$totaldbfilesize = cot_db_result($totaldbfilesize, 0, "SUM(pfs_size)");

$totalpmactive = cot_db_query("SELECT COUNT(*) FROM $db_pm WHERE pm_tostate<2");
$totalpmactive = cot_db_result($totalpmactive, 0, "COUNT(*)");

$totalpmarchived = cot_db_query("SELECT COUNT(*) FROM $db_pm WHERE pm_tostate=2");
$totalpmarchived = cot_db_result($totalpmarchived, 0, "COUNT(*)");

$sql = cot_db_query("SELECT stat_name FROM $db_stats WHERE stat_name LIKE '20%' ORDER BY stat_name ASC LIMIT 1");
$row = cot_db_fetcharray($sql);
$since = $row['stat_name'];

$sql = cot_db_query("SELECT * FROM $db_stats WHERE stat_name LIKE '20%' ORDER BY stat_value DESC LIMIT 1");
$row = cot_db_fetcharray($sql);
$max_date = $row['stat_name'];
$max_hits = $row['stat_value'];

if ($usr['id'] > 0)
{
	$sql = cot_db_query("SELECT COUNT(*) FROM $db_forum_posts WHERE fp_posterid='".$usr['id']."'");
	$user_postscount = cot_db_result($sql, 0, "COUNT(*)");
	$sql = cot_db_query("SELECT COUNT(*) FROM $db_forum_topics WHERE ft_firstposterid='".$usr['id']."'");
	$user_topicscount = cot_db_result($sql, 0, "COUNT(*)");

	$t->assign(array(
		'STATISTICS_USER_POSTSCOUNT' => $user_postscount,
		'STATISTICS_USER_TOPICSCOUNT' => $user_topicscount
	));

	/* === Hook === */
	foreach (cot_getextplugins('statistics.user') as $pl)
	{
		include $pl;
	}
	/* ===== */

	$t->parse('MAIN.IS_USER');
}
else
{
	$t->parse('MAIN.IS_NOT_USER');
}

if ($s == 'usercount')
{
	$sql1 = cot_db_query("DROP TEMPORARY TABLE IF EXISTS tmp1");
	$sql = cot_db_query("CREATE TEMPORARY TABLE tmp1 SELECT user_country, COUNT(*) as usercount FROM $db_users GROUP BY user_country");
	$sql = cot_db_query("SELECT * FROM tmp1 WHERE 1 ORDER by usercount DESC");
	$sql1 = cot_db_query("DROP TEMPORARY TABLE IF EXISTS tmp1");
}
else
{
	$sql = cot_db_query("SELECT user_country, COUNT(*) as usercount FROM $db_users GROUP BY user_country ASC");
}

$sqltotal = cot_db_query("SELECT COUNT(*) FROM $db_users WHERE 1");
$totalusers = cot_db_result($sqltotal, 0, "COUNT(*)");

$ii = 0;

while ($row = cot_db_fetcharray($sql))
{
	$country_code = $row['user_country'];

	if (!empty($country_code) && $country_code != '00')
	{
		$ii = $ii + $row['usercount'];
		$t->assign(array(
			'STATISTICS_COUNTRY_FLAG' => cot_build_flag($country_code),
			'STATISTICS_COUNTRY_COUNT' => $row['usercount'],
			'STATISTICS_COUNTRY_NAME' => cot_build_country($country_code)
		));
		$t->parse('MAIN.ROW_COUNTRY');
	}
}

$t->assign(array(
	'STATISTICS_PLU_URL' => cot_url('plug', 'e=statistics'),
	'STATISTICS_SORT_BY_USERCOUNT' => cot_url('plug', 'e=statistics&s=usercount'),
	'STATISTICS_MAX_DATE' => $max_date,
	'STATISTICS_MAX_HITS' => $max_hits,
	'STATISTICS_SINCE' => $since,
	'STATISTICS_TOTALPAGES' => $totalpages,
	'STATISTICS_TOTALDBUSERS' => $totaldbusers,
	'STATISTICS_TOTALDBPAGES' => $totaldbpages,
	'STATISTICS_TOTALMAILSENT' => $totalmailsent,
	'STATISTICS_TOTALPMSENT' => $totalpmsent,
	'STATISTICS_TOTALPMACTIVE' => $totalpmactive,
	'STATISTICS_TOTALPMARCHIVED' => $totalpmarchived,
	'STATISTICS_TOTALDBVIEWS' => $totaldbviews,
	'STATISTICS_TOTALDBPOSTS_AND_TOTALDBPOSTSPRUNED' => ($totaldbposts + $totaldbpostspruned),
	'STATISTICS_TOTALDBPOSTS' => $totaldbposts,
	'STATISTICS_TOTALDBPOSTSPRUNED' => $totaldbpostspruned,
	'STATISTICS_TOTALDBTOPICS_AND_TOTALDBTOPICSPRUNED' => ($totaldbtopics + $totaldbtopicspruned),
	'STATISTICS_TOTALDBTOPICS' => $totaldbtopics,
	'STATISTICS_TOTALDBTOPICSPRUNED' => $totaldbtopicspruned,
	'STATISTICS_TOTALDBRATINGS' => $totaldbratings,
	'STATISTICS_TOTALDBRATINGSVOTES' => $totaldbratingsvotes,
	'STATISTICS_TOTALDBPOLLS' => $totaldbpolls,
	'STATISTICS_TOTALDBPOLLSVOTES' => $totaldbpollsvotes,
	'STATISTICS_TOTALDBFILES' => $totaldbfiles,
	'STATISTICS_TOTALDBFILESIZE' => floor($totaldbfilesize / 1024),
	'STATISTICS_UNKNOWN_COUNT' => $totalusers - $ii,
	'STATISTICS_TOTALUSERS' => $totalusers
));

/* === Hook === */
foreach (cot_getextplugins('statistics.tags') as $pl)
{
	include $pl;
}
/* ===== */

?>