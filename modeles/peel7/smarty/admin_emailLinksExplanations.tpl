{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_emailLinksExplanations.tpl 35064 2013-02-08 14:16:40Z gboussin $
*}<p class="center"><b>{$STR_ADMIN_EMAIL_TEMPLATES_EXAMPLES_TITLE}{$STR_BEFORE_TWO_POINTS}:</b></p>
<p class="center">{$STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN}</p>
<table width="350">
	<tr>
		<td>[SITE] ou [SITE_NAME]{$STR_BEFORE_TWO_POINTS}:</td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_EMAIL_TEMPLATES_TAG_SITE}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[WWWROOT]{$STR_BEFORE_TWO_POINTS}:</td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_EMAIL_TEMPLATES_TAG_WWWROOT}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[PHP_SELF]{$STR_BEFORE_TWO_POINTS}:</td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_EMAIL_TEMPLATES_TAG_PHP_SELF}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[REMOTE_ADDR]{$STR_BEFORE_TWO_POINTS}:</td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_REMOTE_ADDR}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[DATETIME]{$STR_BEFORE_TWO_POINTS}: </td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_EMAIL_TEMPLATES_TAG_DATETIME}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[NEWSLETTER]{$STR_BEFORE_TWO_POINTS}: </td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_EMAIL_TEMPLATES_TAG_NEWSLETTER}</td>
	</tr>
{if $is_vitrine_module_active}
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[NEWSLETTER_VITRINE_VERIFIED]{$STR_BEFORE_TWO_POINTS}: </td>
	</tr>
	<tr>
		<td>{$explication_tag_windows}</td>
	</tr>
{/if}
{if $is_annonce_module_active}
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[NEWSLETTER_LIST_LAST_ADS_VERIFIED]{$STR_BEFORE_TWO_POINTS}: </td>
	</tr>
	<tr>
		<td>{$explication_tag_last_ads_verified}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[NEWSLETTER_LIST_CATEGORY_ADS]{$STR_BEFORE_TWO_POINTS}: </td>
	</tr>
	<tr>
		<td>{$explication_tag_list_category_ads}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>[NEWSLETTER_LIST_ADS]{$STR_BEFORE_TWO_POINTS}: </td>
	</tr>
	<tr>
		<td>{$explication_tag_list_ads_by_category}</td>
	</tr>
{/if}
	<tr>
		<td colspan="2">[link="$link"]{$STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXAMPLE}[/link]<br />({$STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXPLAIN})</td>
	</tr>
	<tr>
		<td colspan="2">{$STR_ADMIN_EMAIL_TEMPLATES_TAG_OTHER_AVAILABLE}</td>
	</tr>
</table>