{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bas.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}								</td>
							</tr>
							<tr>
								<td class="center">
									{{ MODULES_BOTTOM_MIDDLE }}
								</td>
							</tr>
						</table>
					</div>
					<div class="middle_column_footer">&nbsp;</div>
				</div>
				<!-- Fin middle_column -->
				{% if page_columns_count == 3 %}
				<!-- Début right_column -->
				<div class="right_column">
				{{ MODULES_RIGHT }}
				</div>
				<!-- Fin right_column -->
				{% endif %}
			</div>
			<!-- Fin main_content -->
			<!-- Début Footer -->
			<footer id="footer">
				<div class="affiche_contenu_html_footer">
					{% if (CONTENT_HOME_BOTTOM) %}
					{{ CONTENT_HOME_BOTTOM }}
					{% endif %}
					{{ CONTENT_FOOTER }}
				</div>
				{{ MODULES_FOOTER }}
				{{ FOOTER }}
			</footer>
			<!-- Fin Footer -->
		</div>
		<!-- Fin Total -->
		{% if (add_cart_alert) %}
			<script><!--//--><![CDATA[//><!--
			alert('{{ add_cart_alert|filtre_javascript(true,true,false) }}');
			//--><!]]></script>
		{% endif %}
		{{ tag_analytics }}
		{% if (butterflive_tracker) %}
			{{ butterflive_tracker }}
		{% endif %}
		{% if (peel_debug) %}
			{% for key in peel_debug|keys %}
				<span {% if peel_debug.key.duration<0.010 %}style="color:grey"{% else %}{% if peel_debug.key.duration>0.100 %}style="color:red"{% endif %}{% endif %}>{{ key }}{{ STR_BEFORE_TWO_POINTS }}: {{ peel_debug.key.duration*1000 }} ms - {% if (peel_debug.key.sql) %}{{ peel_debug.key.sql }}{% endif %} {% if (peel_debug.key.template) %}{{ peel_debug.key.template }}{% endif %}</span><br />
			{% endfor %}
		{% endif %}
	</body>
</html>