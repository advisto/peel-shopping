<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) Butterflive - en collaboration avec contact@peel.fr    |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// $Id: utils.php 36232 2013-04-05 13:16:01Z gboussin $

/**
 * Returns the Javascript code to be inserted on each page to include Butterflive.
 * @return string
 */
function get_butterflive_tracker()
{
	if (get_butterflive_param('activation') == 'checked')
	{
		$key = get_butterflive_param('key');
		if ($key)
		{
			// Uncomment to enable future caddie support
			/*require_once(dirname(__FILE__)."/include/add_json_functions.php");

			$caddieJson = array();
			$caddie = $_SESSION['session_caddie'];
			$caddieJson['total'] = $caddie->total_produit;
			$caddieJson['nbProducts'] = $caddie->total_quantite;
			$caddieJson['product'] = array();
			foreach ($caddie->articles as $i=>$productId) {
				$product = new Product($productId, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
				$productJson = array("name"=>$product->name,
									 "quantity"=>$caddie->quantite[$i],
									 "unitprice"=>$product->prix);
				$caddieJson['product'][] = $productJson;
			}*/

			// + A rajouter dans le JSON:
			// ,session: ".json_encode($caddieJson)."

			return "
	<script type=\"text/javascript\">
      bflOptions = {
        key: \"$key\",
        src: \"http://api.butterflive.com/butterflive.js\",
        shareAll: true
      };

      (function() {
        var btf = document.createElement('script'); btf.type = 'text/javascript'; btf.async = true;
        btf.src = bflOptions.src;
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(btf, s);
      })();
    </script>  ";
		}
	}
	return "";
}

/**
 * Returns the value of a parameter stored in the peel_butterflive table (or null if none is set).
 * @param string $key
 * @return string
 */
function get_butterflive_param($key)
{
	$row = fetch_row(query("SELECT value
		FROM peel_butterflive
		WHERE param = '".real_escape_string($key)."'"));
	if ($row === false) {
		return null;
	} else {
		return $row[0];
	}
}

/**
 * Stores a configuration parameter in the peel_butterflive table.
 * @param string $key
 * @param string $value
 */
function set_butterflive_param($key, $value) {
	$res = query("SELECT count(id)
		FROM peel_butterflive
		WHERE param = '".real_escape_string($key)."'");
	$resultat = fetch_row($res);

	if ($resultat[0] == 0)
	{
		//Insert
		query("INSERT INTO peel_butterflive (param,value)
			VALUES ('".real_escape_string($key)."','".real_escape_string($value)."')");
	}
	else
	{
		//Update
		query("UPDATE peel_butterflive
			SET value = '".real_escape_string($value)."'
			WHERE param = '".real_escape_string($key)."'");
	}
}

/**
 * Outputs the CSS style sheet in the HTML page.
 */
function butterflive_output_style() {
?>
<style>
div.good {
	background: #DDFFDD none repeat scroll 0 0;
	color: #002200;
	padding: 5px 5px 5px 26px;
	border: 1px solid #10F030;
	background-image: url("../images/icons/good.png");
	background-repeat: no-repeat;
	background-position: 5px 5px;
}

div.bad {
	background: #FFDDDD none repeat scroll 0 0;
	color: #220000;
	padding: 5px 5px 5px 26px;
	border: 1px solid #F03010;
	background-image: url("../images/icons/bad.png");
	background-repeat: no-repeat;
	background-position: 5px 5px;
}

div.wait {
	background: #F3F3F3 none repeat scroll 0 0;
	color: #111111;
	padding: 5px 5px 5px 26px;
	/*border:1px solid #999999;*/
	background-image: url("../images/icons/ajaxloader.gif");
	background-repeat: no-repeat;
	background-position: 5px 5px;
}

div#download-widget {
    background: url("../images/download-widget.png") no-repeat scroll 0 0 #FFFFFF;
    height: 95px;
    left: 0;
    margin: 15px 0 10px;
    position: relative;
    top: 0;
    width: 343px;
}
div#download-widget:hover {
    background-position: 0 -95px;
    color: white;
    cursor: pointer;
    text-decoration: none;
}
div.version-widget {
    color: white;
    left: 120px;
    position: relative;
    top: 58px;
}
div#download-widget a, div#download-widget a:hover {
    text-decoration: none;
}
</style>
<?php
}

?>