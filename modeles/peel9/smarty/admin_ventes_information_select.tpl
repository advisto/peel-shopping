{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_ventes_information_select.tpl 59873 2019-02-26 14:47:11Z sdelaporte $
*}
<div>
{$STR_ORDER_STATUT_PAIEMENT}:
<select class="form-control" name="statut" style="width:200px;margin:auto;">
	<option value="">{$STR_ADMIN_ALL_ORDERS}</option>
	{$payment_status_options}
</select>
</div>
<div style="padding-top:1px;">
{$STR_SHIPPING_ZONE}:
<select class="form-control" name="zone" style="width:200px;margin:auto;">
	<option value="">{$STR_ADMIN_ALL_ZONES}</option>
{foreach $options as $o}
	<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name|html_entity_decode_if_needed}</option>
{/foreach}
</select>
</div>