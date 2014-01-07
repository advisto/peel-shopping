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
// $Id: fin_cb.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}<h1>{{ STR_ORDER_STATUT }}</h1>
<p>{{ payment_msg }}</p>
<p>{{ message }}</p>
<p>{{ bottom_msg }}</p>
{% if payment_validated %}{{ resume_commande }}{% endif %}