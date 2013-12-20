{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: image_upload_error_option.tpl 39392 2013-12-20 11:08:42Z gboussin $
*}{foreach $labels as $lbl}
	{$STR_PICTURE}{$STR_BEFORE_TWO_POINTS}{$lbl}{$STR_BEFORE_TWO_POINTS}{$STR_NO_UPLOADED}<br />
{/foreach}
<br />{$picture_size_extention_error_txt}