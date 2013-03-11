{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_backoffice_home_block.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<table class="home_block">
	<tr class="header">
		<td style="cursor:pointer; background-image: url('{{ bg_src|filtre_javascript(true,true,true) }}');" onclick="document.location='{{ link }}'"><h2>{{ title }}</h2></td>
	</tr>
	<tr class="content">
		<td>
			<div style="padding:15px">
			{% if (logo) %}
			<a href="{{ link }}" style="align:left;"><img src="{{ logo }}" alt="{{ title|str_form_value }}" style="margin-right:20px; margin-bottom:10px;float:left;" /></a>
			{% endif %}
			{{ description1 }}
			</div>
			{{ description2 }}
		</td>
	</tr>
	<tr class="footer">
		<td>&nbsp;</td>
	</tr>
</table>