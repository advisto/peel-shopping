{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2017 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_ventes_information_select.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
*}
{$STR_ORDER_STATUT_PAIEMENT}:
<select class="form-control" name="statut" style="width:200px;margin:auto;">
	<option value="">{$STR_ADMIN_ALL_ORDERS}</option>
	{$payment_status_options}
</select>