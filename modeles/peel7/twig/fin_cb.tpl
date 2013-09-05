{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fin_cb.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}<table class="full_width">
	<tr>
		<td {% if not payment_validated %}class="top"{% endif %}>
			<table style="width:100%; text-align:left;">
				<tr><td class="tetiere"><h2>{{ STR_ORDER_STATUT }}</h2></td></tr>
			</table>
			<p>{{ payment_msg }}</p>
			<p>{{ message }}</p>
			<p>{{ bottom_msg }}</p>
		</td>
	</tr>
</table>
{% if payment_validated %}{{ resume_commande }}{% endif %}