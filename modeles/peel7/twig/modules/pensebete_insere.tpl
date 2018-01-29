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
// $Id: pensebete_insere.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<h1>{{ STR_AJOUT_PENSE_BETE }}</h1>
<p>{{ item }} &laquo;{{ name|html_entity_decode_if_needed }}&raquo; {{ STR_MODULE_PENSEBETE_HAS_BEEN_ADD_REMINDER }}.</p>
<p>{{ STR_MODULE_PENSEBETE_YOUR_REMINDER_ON_RUB }} <a href="{{ account_url|escape('html') }}">{{ STR_COMPTE }}</a> {{ STR_MODULE_PENSEBETE_OF_OUR_ONLINE_SHOP }}.</p>
<p style="margin-top: 20px;">
	<a href="{{ url|escape('html') }}">{{ back_to_item|escape('html') }}</a>
</p>