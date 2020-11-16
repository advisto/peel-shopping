<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: flot.php 64750 2020-10-21 16:20:30Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * get_flot_chart()
 *
 * @param string $width
 * @param string $height
 * @param string $url
 * @param string $chart_type
 * @param string $base
 * @param string $x_format
 * @param array $params
 * @return
 */
function get_flot_chart($width, $height, $url, $chart_type = 'bar', $base = '', $x_format = 'raw', $params = array())
{
	$output = '';
	$id = 'chart_'.substr(md5($url), 0, 8);
	$period_days = array('mois' => 28, 'quinzaine' => 15, 'semaine' => 7, 'jour' => 1);
	$period_min_ticks = array('mois' => 30, 'quinzaine' => 15, 'semaine' => 7, 'jour' => 30); // Si pour jour on met 1,7 ou 15 on a xaxis vide dans le graphe de 90 jours de temps au jour => quand on met 30 ça marche
	$js_by_chart_type = array('bar' => array('time', 'resize'), 'line' => array('time', 'resize'), 'pie' => array('pie', 'resize'));
	if(empty($base)){
		$base = $GLOBALS['wwwroot'] . '/modules/chart/';
	}
	$options = array();
	if($chart_type == 'bar') {
		if(empty($params['barWidth']) && !empty($params['period'])) {
			$params['barWidth'] = '[' . (vn($period_days[$params['period']], 1)*24*3600*0.6) . ', "absolute"]';
		}
		$series = '';
		if(!empty($params['stack'])) {
			$js_by_chart_type['bar'][] = 'stack';
			$series .= '
			stack: true,';
		}
		$series .= '
			bars: {
				show: true,
				barWidth: ' . (vb($params['barWidth'])?$params['barWidth']:'0.6') . ', 
				fill: 0.9,
				align: "center"
			}
';
		// PS : sur des forums certains parlent de order: 1 pour mettre côte à côte des barres, mais c'est un plugin qui n'est pas bien supporté dans la durée
	} elseif($chart_type == 'line') {
		$series = '
			lines: {
				show: true,
			},
			points: { 
				show: true
			}
';
	}
	if($chart_type == 'bar' || $chart_type == 'line') {
		if($x_format == 'date_format_short' || $x_format == 'date_format_veryshort') {
			$options[] = '
		xaxis: {
			mode: "time",
			timeformat: "'.$GLOBALS[$x_format].'",
			minTickSize: [' . vn($params['minTickSize'], vn($period_min_ticks[vb($params['period'], 'jour')], 1)) . ', "day"],
		}';
		}
			
			//ticks: 16
		/*$options[] = '
		yaxis: {
			min: 0
		}
';*/
	}
	if($chart_type == 'pie') {
		$series = '
		' . $chart_type.': {
			show: true,
			innerRadius: 0.3,
			radius: 0.7,
            tilt: 1,
			label: {
				show: true,
				radius: 0.85,
				tilt:0.8,
				formatter: function(label, series)
					{
						return \'<div style="font-size:8pt;text-align:center;padding:2px;color:white;">\' + label + \'<br/>\' + Math.round(series.percent) + \'% (\' + Math.round(series.data[0][1]*100)/100 + \'' . vb($params['unit']) . ')</div>\';
					},
				background:
					{
						opacity: 0.7
					}
			},
			combine: {
                color: "#999999",
                threshold: 0.03,
				label: "' . $GLOBALS["STR_OTHER"] . '"
            }
		}
'; 
		$options[] = '
		colors: ["#4f88d8", "#cb4b4b", "#4da74d", "#9440ed","#9d7240"],
		yaxis: {
			min: 0
		}
';
	} 
	if(!empty($params['legend'])) {
		// 			labelFormatter: legendLabelFormatter
		// 
		$options[] = '
		legend: {
			show: true,
			container: $("#'.$id.'_container_legend")
		}
';
		$GLOBALS['header_css_output_array'][] = '
.legend {
		display: block;
		-webkit-padding-start: 2px;
		-webkit-padding-end: 2px;
		border-width: initial;
		border-style: none;
		border-color: initial;
		border-image: initial;
		padding-left: 10px;
        padding-right: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
}

.legendLayer .background {
    fill: rgba(255, 255, 255, 0.85);
    stroke: rgba(0, 0, 0, 0.85);
    stroke-width: 1;
}';
		
	}
	$options[] = '
		grid: {
			hoverable: true,
			clickable: true,
			autoHighlight: true,
			margin: {
				left: 6
			}
		}
';	
	/*
	// Proposer des couleurs => c'est bogué même si je renvoie toujours #ff0000, alors ce n'est pas toujours le même rouge !
	$options[] = '
		colors: $.map( data, function ( o, i ) {
					return $.Color({ hue: (o.color*320/'.$id.'_data_length), saturation: 0.95, lightness: 0.35, alpha: 1 }).toHexString();
				  }) 
';	
	*/
	
	$plot = '
window.'.$id.' = $.plot($("#'.$id.'"), data, 
	{
		series: {
		' . $series . '
		},
		' . implode(', ', $options) . '
	}
);
';
	/* 
	 * NE MARCHE PAS : checkbox dans legend : les input ne s'affichent pas, c'est dans du svg et compliqué au niveau CSS
	  	// insert checkboxes
		seriesIndex = 0;
		function legendLabelFormatter(label, series) {
			var linkHTML = "<input type=\'checkbox\' name=\'" + seriesIndex + "\' checked=\'checked\' id=\'checkbox_' . $id .'_" + seriesIndex + "\'></input>" + label;
			seriesIndex += 1;
			return linkHTML;
		}

	 */
	if(!empty($params['choose_series'])) {
		$plot = '
		// hard-code color indices to prevent them from shifting as
		// countries are turned on/off
		window.'.$id.'_data_length = data.length;
		var i = 0;
		$.each(data, function(key, val) {
			val.color = i;
			++i;
		});
 
		// insert checkboxes
		var choiceContainer = $("#choose_series");
		$.each(data, function(key, val) {
			choiceContainer.append(" &nbsp; <input type=\'checkbox\' name=\'" + key + "\' checked=\'checked\' id=\'id" + key + "\'></input>&nbsp;" + "<label for=\'id" + key + "\' class=\'flot_color" + key + "\'>" + val.label + "</label>");
		});
		choiceContainer.find("input").click(plotAccordingToChoices);

		function plotAccordingToChoices() {

			var selected_data = [];

			choiceContainer.find("input:checked").each(function () {
				var key = $(this).attr("name");
				if (key && data[key]) {
					selected_data.push(data[key]);
				}
			});

			' . str_replace('data,', 'selected_data,', $plot) . '
		}

		plotAccordingToChoices();
		var colorArray = $.map('.$id.'.getData(), function(s){return s.color});
		$.each(data, function(key, val) {
			$(".flot_color" + key).css("color", colorArray[key]);
		});
		';
		$choose_series_div = '<div id="choose_series"></div>';
	} else {
		$choose_series_div = '';
	}
	if(!empty($plot)) {
		// return the HTML as a string
		if(empty($GLOBALS['flot_loaded'])) {
			$GLOBALS['flot_loaded'] = true;
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.canvaswrapper.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.colorhelpers.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.saturated.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.browser.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.drawSeries.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.uiConstants.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.legend.js';
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.hover.js';
		}
		foreach($js_by_chart_type[$chart_type] as $this_js) {
			if(empty($GLOBALS['flot_loaded_by_chart_js']) || empty($GLOBALS['flot_loaded_by_chart_js'][$this_js])) {
				$GLOBALS['flot_loaded_by_chart_js'][$this_js] = true;
				$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.'.$this_js.'.js';
			}
		}
		$plot .= '
		$("<div id=\"tooltip\"></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");

		$("#' . $id . '").bind("plothover", function (event, pos, item) {
			if (!pos.x || !pos.y) {
				return;
			}
			if (item) {
				var s = new Date(item.datapoint[0]*1000).toLocaleDateString("fr-FR")
				$("#tooltip").html(item.series.label + " " + s+ " : " + item.datapoint[1] + "' . vb($params['unit']) . '")
					.css({top: item.pageY+5, left: item.pageX+5})
					.fadeIn(200);
			} else {
				$("#tooltip").hide();
			}
		});
';
		$GLOBALS['js_ready_content_array'][] = '
jQuery.ajax({
	type: "GET",
	dataType: "json",
	url: "'.$url.'",
	success: function(data) {
		'.$plot.'
	}
});';
		$output .='
<div id="'.$id.'_container_legend" style="width:100%"></div>
<div id="'.$id.'" style="height:' . $height . 'px; width:' . $width . (strpos($width, '%')!==false?'':'px') . '; margin:auto"></div>' . $choose_series_div;
	}
	return $output;
}

