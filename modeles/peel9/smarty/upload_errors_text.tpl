{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: upload_errors_text.tpl 60372 2019-04-12 12:35:34Z sdelaporte $
*}<div class="alert alert-danger">
	{$msg}
	{if isset($allowed_types)}
		<ul>
		{foreach $allowed_types as $type => $name}
			<li>{$type} ({$name})</li>
		{/foreach}
		</ul>
	{/if}
</div>