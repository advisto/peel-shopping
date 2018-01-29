{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: xml_value.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<!-- BEGIN xml file content -->
<div class="bloc-contenu">
<ul class="xml_file_content">
{foreach $links as $link}
	<li><a href="{$link.href|escape:'html'}">- {$link.label|html_entity_decode_if_needed|strip_tags|str_shorten:{$line_length_max}:'':'...'}</a></li>
{/foreach}
</ul>
</div>
<!-- END xml file content -->