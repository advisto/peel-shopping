<?php
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
// $Id: cookie.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("configuration.inc.php");

include($GLOBALS['repertoire_modele'] . "/haut.php");

echo '
<h1 property="name">' . $GLOBALS['STR_EMPTY_CADDIE'] . '</h2>
<h3>' . $GLOBALS['STR_COOKIES_REMINDER'] . '</h3>
<p>
	' . $GLOBALS['STR_COOKIES_INFO'] . '
</p>
<p>
	' . $GLOBALS['STR_COOKIES_INFO2'] . '
</p>
<p>
	' . $GLOBALS['STR_SUPPORT'] . '
</p>
	' . $GLOBALS['STR_COOKIES_HOWTO'] . '
</p>
<p>
	<b>Mozilla Firefox 3+</b><br />
	' . $GLOBALS['STR_COOKIES_MOZ'] . '
</p>

<p>
	<b>Safari 4+</b><br />
' . $GLOBALS['STR_COOKIES_SAFARI'] . '
</p>
<p>
	<b>Google Chrome 5+</b><br />
' . $GLOBALS['STR_COOKIES_CHROME'] . '
</p>
<p>
	<b>Internet Explorer 6+</b><br />
' . $GLOBALS['STR_COOKIES_IE'] . '
</p>
<p>
	<b>Netscape 6+</b><br />
' . $GLOBALS['STR_COOKIES_NETSCAPE'] . '
</p>
';

include($GLOBALS['repertoire_modele'] . "/bas.php");

