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
// $Id: advisto-chart.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

if(vb($GLOBALS['site_parameters']['chart_product']) == 'plot') {
	require($GLOBALS['dirroot'] . '/modules/chart/plot.php');
} else {
	require($GLOBALS['dirroot'] . '/modules/chart/open-flash-chart.php');
}

/**
 * advistoChart()
 *
 * @param mixed $data
 * @param mixed $title
 * @param mixed $all_graph_type
 * @param array $graph_type
 * @param array $colors
 * @param integer $legend_font_size
 * @param string $force_chart_product
 * @return
 */
function advistoChart(&$data, $title = null, $all_graph_type = null, $graph_type = array(), $colors = array(), $legend_font_size = 12, $width = 300, $force_chart_product = null)
{
	if(empty($width)){
		$width=300;
	}
	if(!empty($force_chart_product)) {
		$chart_product = $force_chart_product;
	} else {
		$chart_product = vb($GLOBALS['site_parameters']['chart_product']);
	}
	// use the chart class to build the chart:
	if($chart_product == 'flot') {
		$output = '';
	} else {
		$graph = new graph();		
		$graph->title((str_replace(array(',', '&'), array('.', '-'), $title)), '{font-size:12px; padding:5px}');
		if (!empty($all_graph_type) && $all_graph_type == 'pie') {
			$graph->pie(80, '0x505050', '{font-size: ' . $legend_font_size . 'px; color: #404040;}');
		}
	}
	if (!empty($data)) {
		$max_all_data = 0;
		$min_all_data = 0;
		$temp = array_keys($data);
		foreach($temp as $key) {
			// On va remplacer le $key par sa valeur UTF8 et sans virgule
			$value = $data[$key];
			if (str_replace(array(',', '&'), array('.', '-'), ($key)) != $key) {
				unset($data[$key]);
				$key = str_replace(array(',', '&'), array('.', '-'), ($key));
			}
			if (!empty($value) && !is_array($value) && (!isset($data[$key]) || strpos($value, ',') !== false)) {
				// Si on est en locale settings qui font que la décimale est séparée par une virgule et non un point
				$data[$key] = str_replace(array(',', '&'), array('.', '-'), $value);
			} elseif (!isset($data[$key])) {
				$data[$key] = $value;
			}
		}
		$temp = array_keys($graph_type);
		foreach($temp as $key) {
			// On va remplacer le $key par sa valeur UTF8 et sans virgule
			$value = $graph_type[$key];
			if (($key) != $key) {
				unset($graph_type[$key]);
				$graph_type[($key)] = $value;
			}
		}
		if (!empty($all_graph_type) && $all_graph_type == 'pie') {
			arsort($data);
			$total = array_sum($data);
			foreach($data as $key => $value) {
				if ($key != 'Autre') {
					$data[$key] = round($value / $total * 100 * 100) / 100;
					if (empty($data[$key]) || $data[$key] < 1) {
						if (empty($data['Autre'])) {
							$data['Autre'] = 0;
						}
						$data['Autre'] += $data[$key];
						unset($data[$key]);
					}
				}
			}
			if(!empty($graph)) {
				$graph->set_tool_tip('#x_label#<br>#val#%');
			}
		} else {
			if(!empty($graph)) {
				$graph->set_tool_tip('#key#<br>#x_label#<br>#val#');
			}
		}
		foreach($data as $data_title => $data_array) {
			if (empty($all_graph_type) || $all_graph_type != 'pie') {
				if (!empty($data_array)) {
					foreach($data_array as $value) {
						$value = floatval($value);
						if ($value > $max_all_data) {
							$max_all_data = $value;
						}
					}
				}
				if (!empty($data_array)) {
					foreach($data_array as $value) {
						$value = floatval($value);
						if ($value < $min_all_data) {
							$min_all_data = $value;
						}
					}
				}
			}
			if (!empty($colors[($data_title)])) {
				$color = $colors[($data_title)];
			} elseif ($data_title == 'FALSE') {
				$color = 'FF0000';
			} elseif ($data_title == 'TRUE') {
				$color = '009900';
			} elseif ($data_title == 'DESACTIVATED') {
				$color = '999977';
			} elseif ($data_title == 'F') {
				$color = 'FF00CC';
			} elseif ($data_title == 'H') {
				$color = '0000FF';
			} else {
				$r = base_convert(base_convert(StringMb::substr(md5($data_title), 0, 4), 16, 10) % 15, 10, 16);
				$g = base_convert(base_convert(StringMb::substr(md5($data_title), 5, 4), 16, 10) % 15, 10, 16);
				$b = base_convert(base_convert(StringMb::substr(md5($data_title), 9, 4), 16, 10) % 15, 10, 16);
				$color = StringMb::strtoupper($r . '0' . $g . '0' . $b . '0');
			}
			if (!empty($graph_type[$data_title]) && $graph_type[$data_title] == 'bar') {
				if($chart_product == 'flot') {
					$i=0;
					foreach($data_array as $x => $y) {
						$bar_data[] = array($x, $y);
						$i++;
					}
					$output .= json_encode($bar_data);
				} else {
					$graph->set_data($data_array);
					$graph->bar(80, '0x' . $color, $data_title);				
				}
			} elseif (!empty($graph_type[$data_title]) && $graph_type[$data_title] == 'dot') {
				if($chart_product == 'flot') {
					$output .= json_encode($points_data_array);
				} else {
					$i=0;
					foreach($data_array as $x => $y) {
						$points_data_array[] = new point($i, round($y), 6);
						$i++;
					}
					$graph->scatter($points_data_array, 2, '#' . $color, $data_title, 10);
					unset($points_data_array);
				}
			} elseif (!empty($all_graph_type) && $all_graph_type == 'pie') {
				$pie_slice_colours[] = '#' . $color;
			} elseif (!empty($graph_type[$data_title]) && $graph_type[$data_title] == 'line') {
				if($chart_product == 'flot') {
					$output .= json_encode($data_array);
				} else {
					$graph->set_data($data_array);
					$graph->line(2, '0x' . $color, $data_title, 10);
				}
			} else {
				// Par défaut line_dot
				if($chart_product == 'flot') {
					ksort($data_array);
					$i=0;
					foreach($data_array as $x => $y) {
						$line_data[] = array($x, $y);
						$i++;
					}
					$output .= json_encode($line_data);
				} else {
					$graph->set_data($data_array);
					$graph->line_dot(2, 4, '0x' . $color, $data_title, 10);
				}
			}
			if ((empty($graph_type[$data_title]) || $graph_type[$data_title] !== 'dot') && (empty($all_graph_type) || $all_graph_type !== 'pie')) {
				$x_array = array_keys($data_array);
			}
		}
		if (empty($all_graph_type) || $all_graph_type != 'pie') {
			$max_y = round($max_all_data);
			if (!empty($min_all_data)) {
				$max_y = $max_y * 1.2;
			}
			if ($max_y > 0) {
				$max_y_base = pow(10, floor(log10(abs($max_y)))-1);
				$max_y = round($max_y / $max_y_base) * $max_y_base;
			}
			$min_y = round($min_all_data * 1.2);
			if (!empty($min_y)) {
				$min_y_base = pow(10, floor(log10(abs($min_y)))-1);
				$min_y = round($min_y / $min_y_base) * $min_y_base;
			}
			// On essaie de trouver min_y qui permette d'avoir 0 qui tomnbe juste sur une des 10 numérations de y
			if (!empty($min_y)) {
				if (- $min_y < $max_y * 1.3 && - $min_y > $max_y * 0.7) {
					$min_y = - $max_y;
				} elseif (- $min_y < $max_y * 0.1) {
					$max_y = 9 * $max_y / 10;
					$min_y = - $max_y / 9;
				} elseif (- $min_y < $max_y * 0.2) {
					$max_y = 8 * $max_y / 10;
					$min_y = -2 * $max_y / 8;
				}
			}
			if($chart_product == 'flot') {
			} else {
				$graph->set_y_max($max_y);
				$graph->set_y_min($min_y);
				$graph->y_label_steps(10);

				$graph->set_x_labels($x_array);
				$steps = round(count($x_array) * 9 * strlen(current($x_array)) / ($width-30));
				$graph->set_x_label_style(10, '0x000000', 0, $steps);
			}
		} else {
			if($chart_product == 'flot') {
				foreach($data as $title => $value) {
					$temp = new stdClass();
					$temp->label = $title;
					$temp->data = intval($value);
					$pie_data[] = $temp;
					unset($temp);
				}
				$output .= json_encode($pie_data);
			} else {
				$graph->pie_values($data, array_keys($data));
				$graph->pie_slice_colours($pie_slice_colours);
			}
		}
	} elseif($chart_product == 'flot') {
		$data = array();
		$output .= json_encode($data);
	}
	if($chart_product == 'flot') {
		return $output;
	} else {
		// display the data
		return $graph->render();
	}
}

