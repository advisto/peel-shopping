{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: image_upload_error_option.tpl 47145 2015-10-04 11:56:35Z sdelaporte $
#}{% for lbl in labels %}
	{{ STR_PICTURE }}{{ STR_BEFORE_TWO_POINTS }}{{ lbl }}{{ STR_BEFORE_TWO_POINTS }}{{ STR_NO_UPLOADED }}<br />
{% endfor %}
<br />{{ picture_size_extention_error_txt }}