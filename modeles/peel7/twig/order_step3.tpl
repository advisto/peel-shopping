{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: order_step3.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<h2 class="order_step3">&nbsp;{{ STR_STEP3 }}</h2>
<p>{{ STR_MSG_THANKS }}</p>
{{ payment_form }}<br />
<fieldset>
	{{ resume_commande }}
</fieldset>
{{ conversion_page }}