{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bas.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
#}
							<div class="row bottom_middle">
								<div class="col-md-12">
									{{ MODULES_BOTTOM_MIDDLE }}
								</div>
							</div>
						</div>
						<div class="middle_column_footer">&nbsp;</div>
					</div>
				</div>
				<!-- Fin middle_column -->
				{% if page_columns_count == 3 %}
				<!-- Début right_column -->
				<div class="right_column container">
					<div class="row">
						{{ MODULES_RIGHT }}
					</div>
				</div>
				<!-- Fin right_column -->
				{% endif %}
			</div>
			<!-- Fin main_content -->
			<div class="push"></div>
		</div>
		<!-- Fin Total -->
		<!-- Début Footer -->
		<div id="footer" class="clearfix">
			{% if (CONTENT_HOME_BOTTOM) or (CONTENT_FOOTER) or (footer_column) %}
			<div class="container">
				<div class="affiche_contenu_html_footer">
					{% if (CONTENT_HOME_BOTTOM) %}
					{{ CONTENT_HOME_BOTTOM }}
					{% endif %}
					{{ CONTENT_FOOTER }}
				{% if (footer_column) %}
					<div class="footer_column">{{ footer_column }}</div>
				{% endif %}
				</div>
			</div>
			{% endif %}
			<footer class="footer">
				<div class="container">
					<div class="row">
						{{ MODULES_FOOTER }}
						<div class="col-sm-{{ block_columns_width_sm }} col-md-{{ block_columns_width_md }} footer_col">
							{{ FOOTER }}
						</div>
						<div class="clearfix visible-sm"></div>
						{% if (rss) %}
							{{ rss }}
						{% endif %}
						<div class="clearfix"></div>
						{% if (flags_links_array) or (flags) %}<div id="flags_xs" class="pull-right visible-xs">{% if (flags_links_array) %}{{ flags_links_array|join('&nbsp;') }}{% endif %}{{ flags }}</div>{% endif %}
						{% if (module_devise) %}<div id="currencies_xs" class="pull-right visible-xs">{{ module_devise }}</div>{% endif %}
						<div class="clearfix"></div>
						{% if (footer_link) %}
						<div class="footer_link">{{ footer_link }}</div>
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
		{% if (butterflive_tracker) %}
			{{ butterflive_tracker }}
		{% endif %}
		{% if (peel_debug) %}
			{% set i=0 %}
			{% for key,value in peel_debug %}
				<span {% if value.duration<0.010 %}style="color:grey"{% else %}{% if value.duration>0.100 %}style="color:red"{% endif %}{% endif %}>{{ key }}{{ STR_BEFORE_TWO_POINTS }}: {{ (value.duration*1000)|number_format(2) }} ms - Start{{ STR_BEFORE_TWO_POINTS }}{{ value.start*1000|number_format(2) }} ms  - {% if (value.sql) %}{{ value.sql }}{% endif %} {% if (value.template) %}{{ value.template }}{% endif %}</span><br />
			{% endfor %}
		{% endif %}
	</body>
</html>