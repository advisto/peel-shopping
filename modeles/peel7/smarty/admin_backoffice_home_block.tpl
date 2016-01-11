{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_backoffice_home_block.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
*}<div class="home_block home_block_{$title_bg_color} panel panel-primary">
	<div class="panel-heading" style="cursor:pointer" onclick="document.location='{$link}'">
		<h2 class="panel-title">{$title}</h2>
	</div>
	<div class="panel-body">
		<div style="padding-bottom: 10px">
{if !empty($logo)}
			<a href="{$link}" style="align:left;"><img src="{$logo}" alt="{$title|str_form_value}" style="margin-right:20px; margin-bottom:10px;float:left;" /></a>
{/if}
			{$description1}
		</div>
		{$description2}
	</div>
</div>