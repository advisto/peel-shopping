{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: order_step3.tpl 53855 2017-05-19 13:42:10Z sdelaporte $
#}<h1 property="name" class="order_step3">{{ STR_STEP3 }}</h1>
<p>{{ STR_MSG_THANKS }}</p>
{% if payment_form %}
{{ payment_form }}<br />
{% endif %}
<fieldset>
	{{ resume_commande }}
</fieldset>
{{ conversion_page }}