{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: delete_installation_folder.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<br /><br />
<h1 class="center">{{ STR_INSTALLATION_PROCEDURE }}</h1>
<p class="center">{{ installation_links }}</p>
<br /><br />
<h1 class="center">{{ STR_INSTALLATION_DELETE_EXPLAIN }}</h1>
<p class="center">{{ STR_INSTALLATION_DELETE_EXPLAIN_ALTERNATIVE }}</p>
<p class="center"><a href="{{ wwwroot }}/">{{ STR_INSTALLATION_DELETED_LINK }}</a></p>