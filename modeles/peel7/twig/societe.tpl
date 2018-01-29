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
// $Id: societe.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<br />
{% if (societe) %}<br /><b>{{ societe|html_entity_decode_if_needed }}</b>&nbsp;{% endif %}
{% if (adresse) %}<br />{{ adresse|html_entity_decode_if_needed }}&nbsp;{% endif %}
{% if (code_postal) %}<br />{{ code_postal }}&nbsp;{% endif %}
{% if (ville) %}<br />{{ ville|html_entity_decode_if_needed }}&nbsp;{% endif %}
{% if (pays) %}<br />{{ pays|html_entity_decode_if_needed }}&nbsp;{% endif %}
{% if (tel) %}<br />{{ tel_label }}: {{ tel }}{% endif %}
{% if (fax) %}<br />{{ fax_label }}: {{ fax }}{% endif %}
{% if (siren) %}<br />{{ siren_label }}: {{ siren }}{% endif %}
{% if (tvaintra) %}<br />{{ tvaintra_label }}: {{ tvaintra }}{% endif %}
{% if (cnil) %}<br />{{ cnil_label }}: {{ cnil }}{% endif %}
<br />