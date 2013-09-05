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
// $Id: menu_recherche.tpl 37943 2013-08-29 09:31:55Z gboussin $
#}<div{% if display_mode == 'header' %} id="top_search"{% endif %}>
<form method="get" action="{{ action|escape('html') }}" id="recherche">
	<fieldset>
		<input type="hidden" name="match" value="1" />
		<input type="search" name="search" id="search" value="" />
		<input type="submit" class="bouton_go" value="" name="action" />
		<div id="placement_produit" class="autocomplete"></div>
	</fieldset>
</form>
{% if use_autocomplete %}
<script><!--//--><![CDATA[//><!--
function positionAuto(element, entry) {
	setTimeout( function() {
	  Element.clonePosition("placement_produit", "search", {
	  "setWidth": false,
	  "setHeight": false,
	  "offsetTop": $("search").offsetHeight
	});
  }, 600);
  return entry;
}
{# document.observe('dom:loaded', function() { #}
	new Ajax.Autocompleter('search','placement_produit','{{ autocomplete_href }}', {
	  minChars: 2,
	  callback: positionAuto }});
{# }}); #}
//--><!]]></script>
{% endif %}
{% if (advanced_search_script) and (select_marque) %}
{{ advanced_search_script }}
{{ select_marque }}
{% endif %}
</div>