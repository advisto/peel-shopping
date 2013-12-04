{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bas.tpl 39162 2013-12-04 10:37:44Z gboussin $
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
			<div class="container">
				<div class="affiche_contenu_html_footer">
					{% if (CONTENT_HOME_BOTTOM) %}
					{{ CONTENT_HOME_BOTTOM }}
					{% endif %}
					{{ CONTENT_FOOTER }}
				</div>
			</div>
			<footer class="footer">
				<div class="container">
					<div class="row">
						{{ MODULES_FOOTER }}
						<div class="col-sm-4 col-md-3 footer_col">
							{{ FOOTER }}
						</div>
						<div class="clearfix visible-sm"></div>
						{% if (rss) %}
							{{ rss }}
						{% endif %}
						<div class="clearfix"></div>
						<div id="flags_xs" class="pull-right visible-xs">{% if (flags_links_array) %}{{ flags_links_array|join('&nbsp;') }}{% endif %}{{ flags }}</div>
						{% if (module_devise) %}<div id="currencies_xs" class="pull-right visible-xs">{{ module_devise }}</div>{% endif %}
						<div class="clearfix"></div>
						{{ footer_link }}
					</div>
				</div>
			</footer>
		</div>
		<!-- Fin Footer -->
		{{ js_output }}
		{{ tag_analytics }}
		{% if (butterflive_tracker) %}
			{{ butterflive_tracker }}
		{% endif %}
		{% if (peel_debug) %}
			{% set i=0 %}
			{% for key,value in peel_debug %}
				<span {% if value.duration<0.010 %}style="color:grey"{% else %}{% if value.duration>0.100 %}style="color:red"{% endif %}{% endif %}>{{ key }}{{ STR_BEFORE_TWO_POINTS }}: {{ (value.duration*1000)|number_format(2) }} ms - {% if (value.sql) %}{{ value.sql }}{% endif %} {% if (value.template) %}{{ value.template }}{% endif %}</span><br />
			{% endfor %}
		{% endif %}
	</body>
</html>