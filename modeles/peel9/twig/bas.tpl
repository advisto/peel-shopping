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
// $Id: bas.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}
							</div>
							{% if MODULES_BOTTOM_MIDDLE %}
							<div class="bottom_middle row">
								{{ MODULES_BOTTOM_MIDDLE }}
							</div>
							{% endif %}
							<div class="middle_column_footer">&nbsp;</div>
						</div>
						{% if MODULES_RIGHT %}
						<!-- Début right_column -->
						<div class="side_column right_column col-sm-3 col-lg-2">
							{{ MODULES_RIGHT }}
						</div>
						<!-- Fin right_column -->
						{% endif %}
					</div>
				</div>
				<!-- Fin middle_column -->
				{% if MODULES_BELOW_MIDDLE %}
				<!-- Début below_middle -->
				<div class="below_middle container">
					<div class="row">
						{{ MODULES_BELOW_MIDDLE }}
					</div>
				</div>
				<!-- Fin below_middle -->
				{% endif %}
			</div>
			<!-- Fin main_content -->	
			<div class="push"></div>
		</div>
		<!-- Fin Total -->
		{% if scroll_to_top %}<div class="scroll_to_top"><a href="#"><span class="glyphicon glyphicon-circle-arrow-up"></span></a></div>{% endif %}
		{% if call_back_form %} {{ call_back_form }} {% endif %}
		<!-- Début Footer -->
		<div id="footer" class="clearfix">
			{% if CONTENT_FOOTER or footer_column %}
			<div class="container">
				<div class="affiche_contenu_html_footer">
					{{ CONTENT_FOOTER }}
				</div>
			</div>
			{% endif %}
			<footer class="footer">
				<div class="container">
					<div class="row">
					{% if display_footer_full_custom_html is empty %}
							{{ MODULES_FOOTER }}
						{% if footer_column %}
							<div class="footer_column">{{ footer_column }}</div>
						{% endif %}
							{{ FOOTER }}
							<div class="clearfix visible-sm"></div>
							{% if rss is defined %}
								{{ rss }}
							{% endif %}
					{% else %}
						{{ FOOTER_FULL_CUSTOM_HTML }}
					{% endif %}
	
					</div>
					
					<div class="clearfix"></div>
						{% if (flags_links_array) or (flags) %}<div id="flags_xs" class="pull-right visible-xs">{% if (flags_links_array) %}{{ flags_links_array|join('&nbsp;') }}{% endif %}{{ flags }}</div>{% endif %}
						{% if (module_devise) %}<div id="currencies_xs" class="pull-right visible-xs">{{ module_devise }}</div>{% endif %}
					<div class="clearfix"></div>
						{% if (footer_link) %}
						<div class="footer_link">{{ footer_link }}</div>
						<div class="clearfix"></div>
						{% endif %}
				</div>
			</div>
			{% if (footer_bottom) %}
				<div class="footer_bottom">{{ footer_bottom }}</div>
			{% endif %}
			</footer>
		</div>
		<!-- Fin Footer -->
		{{ js_output }}
		{{ tag_analytics }}
		{% if end_javascript is defined %}
			{{ end_javascript }}
		{% endif %}
		{% if (peel_debug) %}
			{% set i=0 %}
			<div class="clearfix"></div>
			{% for key,value in peel_debug %}
			<span {% if value.duration<0.010 %}style="color:grey"{% else %}{% if value.duration>0.100 %}style="color:red"{% endif %}{% endif %}>{{ key }}{{ STR_BEFORE_TWO_POINTS }}: {{ (value.duration*1000)|number_format(2) }} ms - Start{{ STR_BEFORE_TWO_POINTS }}{{ value.start*1000|number_format(2) }} ms  - {% if (value.sql) %}{{ value.sql }}{% endif %}{% if (value.template) %}{{ value.template }}{% endif %}{% if (value.text) %}{{ value.text }}{% endif %}</span><br />
			{% endfor %}
		{% endif %}
	</body>
</html>