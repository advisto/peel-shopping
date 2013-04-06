{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: menu.tpl 35304 2013-02-15 18:32:47Z gboussin $
#}<nav class="main_menu_wide">
	<div class="main_menu">
		<ul id="menu1">
			{% for item in menu %}
				<li class="menu_main_item menu_{{ item.name }}">
					{% if item.href %}
						<a href="{{ item.href|htmlspecialchars }}"{% if item.selected %} class="current"{% endif %}>{% if item.name == 'home' %}<img src="{{ wwwroot }}/images/home.png" alt="" style="padding: 4px 6px 0px 4px;" />{% else %}{{ item.label }}{% endif %}</a>
					{% else %}
						<span>{{ item.label }}</span>
					{% endif %}
					{% if (item.submenu) %}
						<ul class="sousMenu">
							{% for sitem in item.submenu %}
								<li>
								{% if sitem.href %}
									<a href="{{ sitem.href|htmlspecialchars }}"{% if sitem.selected %} class="current"{% endif %}>{{ sitem.label }}</a>
								{% else %}
									<span{% if sitem.selected %} class="current"{% endif %}>{{ sitem.label }}</span>
								{% endif %}
								</li>
							{% endfor %}
						</ul>
					{% endif %}
					{{ item.submenu_global }}
				</li>
			{% endfor %}
		</ul>
	</div>
</nav>