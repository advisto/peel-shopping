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
// $Id: chart_ofc.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}{% if (swfobject_src) %}
<script src="{{ swfobject_src|escape('html') }}"></script>
{% endif %}
{% if use_swfobject %}
<div id="{{ div_name|escape('html') }}"></div>
<script>
	var so = new SWFObject("{{ base|escape('html') }}chart.swf", "{{ obj_id|escape('html') }}", "{{ width|escape('html') }}", "{{ height|escape('html') }}", "9", "#FFFFFF");
	so.addVariable("wmode", "transparent");
	so.addVariable("data", "{{ url|escape('html') }}");
	so.addParam("wmode", "transparent");
	so.addParam("allowScriptAccess", "sameDomain");
	so.write("{{ div_name }}");
</script>
<noscript>
{% endif %}
<embed src="{{ base|escape('html') }}chart.swf?data={{ url|escape('html') }}" width="{{ width|escape('html') }}" height="{{ height }}" id="ie_{{ obj_id }}" class="middle" wmode="transparent" />
{% if use_swfobject %}
</noscript>
{% endif %}