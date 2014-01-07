{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_ventes_information_select.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}{{ STR_ORDER_STATUT_PAIEMENT }}:
<select class="form-control" name="statut" style="width:200px;margin:auto;">
	<option value="">{{ STR_ADMIN_ALL_ORDERS }}</option>
	{{ payment_status_options }}
</select>