{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: diaporama.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}
<div class="col-md-12">
	<div class="row">
	{% for diapo in diaporama %}
		<div class="col-md-{{ (12 // nb_colonnes_md) }} col-sm-{{ (12 // nb_colonnes_sm) }}">
			<div class="diaporama_image_container">
				<a typeof="ImageObject" class="lightbox" href="{{ diapo.image }}" onclick="return false;">
					<img property="image" class="zoom" src="{{ diapo.thumbs }}" alt="">
				</a>
			</div>
		</div>
			{% if diapo.is_row_md %}
				<div class="clearfix visible-md visible-lg"></div>
			{% endif %}
			{% if diapo.is_row_sm %}
				<div class="clearfix visible-sm"></div>
			{% endif %}
	{% endfor %}
	</div>
</div>