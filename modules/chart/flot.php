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
// $Id: flot.php 55332 2017-12-01 10:44:06Z sdelaporte $
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
 * @return
 */
function get_flot_chart($width, $height, $url, $chart_type = 'bar', $base = '', $x_format = 'raw')
{
	$output = '';
	$id = 'chart_'.md5($url);
	$js_by_chart_type = array('bar'=>array('time.min', 'resize.min'), 'line'=>array('time.min', 'resize.min'), 'pie'=>array('pie.min', 'resize.min'));
	if(empty($base)){
		$base = $GLOBALS['wwwroot'] . '/modules/chart/';
	}
	if($chart_type == 'bar') {
		$plot = 'jQuery.plot(jQuery("#'.$id.'"), [data], 
        {
            series: {
				bars: {
					show: true,
					barWidth: .9, 
					fill: 0.9,
					align: "center"
				}
			},';
		if($x_format == 'date_format_short' || $x_format == 'date_format_veryshort') {
			$plot .= 'xaxis: {
				mode: "time",
				timeformat: "'.$GLOBALS[$x_format].'",
				timezone: "browser"
			},';
		}
		$plot .= 'grid: {
				hoverable: true,
				clickable: true
			}
        }
    );
';
	} else	if($chart_type == 'line') {
		$plot = 'jQuery.plot(jQuery("#'.$id.'"), [data], 
        {
            series: {
				lines: {
					show: true,
				},
				points: { 
					show: true
				}
			},';
		if($x_format == 'date_format_short' || $x_format == 'date_format_veryshort') {
			$plot .= 'xaxis: {
				mode: "time",
				timeformat: "'.$GLOBALS[$x_format].'",
				timezone: "browser"
			},';
		}
		$plot .= '
			yaxis: {
				min: 0
			},
			grid: {
				hoverable: true,
				clickable: true
			}
        }
    );
';
	} elseif($chart_type == 'pie') {
		$plot = '
	jQuery.plot(jQuery("#'.$id.'"),data,{
	series: {
		' . $chart_type.': {
			show: true,
			innerRadius: 0,
			radius: 0.8,
            tilt: 1,
			label: {
				show: true,
				radius: 1.0,
				tilt:0.8,
				formatter: function(label, series)
					{
						return \'<div style="font-size:8pt;text-align:center;padding:2px;color:white;">\' + label + \'<br/>\' + Math.round(series.percent) + \'% (\' + series.data[0][1] + \')</div>\';
					},
				background:
					{
						opacity: 0.6
					}
			},
			combine: {
                color: "#999999",
                threshold: 0.03
            }
		}
	},
	legend: {
		show: false
	},
	grid: {
		hoverable: true,
		clickable: true
	}
});';
	}
	if(!empty($plot)) {
		// return the HTML as a string
		if(empty($GLOBALS['js_files_pageonly']['jquery.flot.min.js'])) {
			$output .='<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="' . $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/excanvas.min.js"></script><![endif]-->
';
			$GLOBALS['js_files_pageonly']['jquery.flot.min.js'] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.min.js';
		}
		foreach($js_by_chart_type[$chart_type] as $this_js) {
			$GLOBALS['js_files_pageonly']['jquery.flot_'.$this_js.'.js'] = $GLOBALS['wwwroot_in_admin'] . '/modules/chart/js/jquery.flot.'.$this_js.'.js';
		}
		$GLOBALS['js_ready_content_array'][] = '
jQuery.ajax({
	type:"GET",
	dataType:"json",
	url:"'.$url.'",
	success: function(data) {
		if(window.console) {
			console.log(data);
		}
		'.$plot.'
	}
});';
	$output .='
<div id="'.$id.'" style="height:300px; width:100%"></div>';
	}
	return $output;
}

