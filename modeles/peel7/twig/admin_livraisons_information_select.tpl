{# Twig
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
// $Id: admin_livraisons_information_select.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{{ STR_ORDER_STATUT_LIVRAISON }}{{ STR_BEFORE_TWO_POINTS }}:
<select class="form-control" name="statut" style="width:200px;">
	<option value="">{{ STR_ADMIN_ALL_ORDERS }}</option>
	{{ delivery_status_options }}
</select>