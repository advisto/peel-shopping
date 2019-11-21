{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: actu.tpl 61970 2019-11-20 15:48:40Z sdelaporte $
*}
{foreach $data as $item}
	<h2>{$item.titre|html_entity_decode_if_needed}</h2>
	<p>{$item.date}</p>
	{if !empty($item.image_src)}
	<img src="{$item.image_src|escape:'html'}" alt="" /><br />
	{/if}
	{$item.chapo|html_entity_decode_if_needed|nl2br_if_needed}
{/foreach}