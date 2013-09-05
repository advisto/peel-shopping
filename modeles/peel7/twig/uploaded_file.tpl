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
// $Id: uploaded_file.tpl 37943 2013-08-29 09:31:55Z gboussin $
#}
<div {% if f.form_name %}id="{{ f.form_name|str_form_value }}"{% endif %}>
	{% if f.type == 'pdf' %}<a href="{{ f.url|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ f.pdf_logo_src|escape('html') }}" alt="pdf" width="100" height="100" /></a>{% else %}<img src="{{ f.url|escape('html') }}" alt="" style="max-height:100px" />{% endif %}<br />
	{{ f.name }}&nbsp;
	<a href="{{ f.drop_href|escape('html') }}"><img src="{{ f.drop_src|escape('html') }}" width="16" height="16" alt="" />{{ STR_DELETE }}</a>
	{% if f.form_name %}<input type="hidden" name="{{ f.form_name|str_form_value }}" value="{{ f.form_value|str_form_value }}" />{% endif %}
</div>