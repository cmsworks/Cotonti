<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.page.loop
Tags=admin.page.inc.tpl:{ADMIN_TAGS_ROW_TAG},{ADMIN_TAGS_ROW_URL}
[END_COT_EXT]
==================== */

/**
 * Shows tags in page administration area
 *
 * @package tags
 * @version 0.7.0
 * @author Dayver - Pavel Tkachenko
 * @copyright Copyright (c) Cotonti Team 2008-2010
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

if ($cfg['plugin']['tags']['pages'])
{
	cot_require('tags', true);
	$item_id = $row['page_id'];
	$tags = cot_tag_list($item_id);
	if (count($tags) > 0)
	{
		$tag_i = 0;
		foreach ($tags as $tag)
		{
			$tag_u = cot_urlencode($tag, $cfg['plugin']['tags']['translit']);
			$tl = ($lang != 'en' && $tag_u != urlencode($tag)) ? '&tl=1' : '';
			$t->assign(array(
				'ADMIN_TAGS_ROW_TAG' => $cfg['plugin']['tags']['title'] ? htmlspecialchars(cot_tag_title($tag)) : htmlspecialchars($tag),
				'ADMIN_TAGS_ROW_URL' => cot_url('plug', 'e=tags&a=pages'.$tl.'&t='.$tag_u)
			));
			$t->parse('PAGE.PAGE_ROW.ADMIN_TAGS_ROW');
			$tag_i++;
		}
	}
	else
	{
		$t->parse('PAGE.PAGE_ROW.ADMIN_NO_TAGS');
	}
}

?>