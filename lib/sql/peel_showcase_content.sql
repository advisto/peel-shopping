# This file should be in UTF8 without BOM - Accents examples: éèê
# +----------------------------------------------------------------------+
# | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
# +----------------------------------------------------------------------+
# | This file is part of PEEL Shopping 9.0.0, which is subject to an	 |
# | opensource GPL license: you are allowed to customize the code		 |
# | for your own needs, but must keep your changes under GPL 			 |
# | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
# +----------------------------------------------------------------------+
# | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
# +----------------------------------------------------------------------+
# $Id: peel_showcase_content.sql 53676 2017-04-25 14:51:39Z sdelaporte $
#
-- configuration
UPDATE `peel_configuration` SET `string` = '263' WHERE `peel_configuration`.`technical_code` = "small_width";
UPDATE `peel_configuration` SET `string` = '172' WHERE `peel_configuration`.`technical_code` = "small_height";
UPDATE `peel_configuration` SET `string` = '800' WHERE `peel_configuration`.`technical_code` = "medium_width";
UPDATE `peel_configuration` SET `string` = '800' WHERE `peel_configuration`.`technical_code` = "medium_height";
UPDATE `peel_configuration` SET `string` = 'global' WHERE `peel_configuration`.`technical_code` = "category_count_method";
UPDATE `peel_configuration` SET `string` = '4' WHERE `peel_configuration`.`technical_code` = "product_category_pages_nb_column";
UPDATE `peel_configuration` SET `string` = '4' WHERE `peel_configuration`.`technical_code` = "search_pages_nb_column";
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'module_counter', 'sites.php', 'string', '1', '', '2017-11-10 09:44:06', '', 1, "[SITE_ID]");

-- rubriques
INSERT INTO `peel_rubriques` (`id`, `parent_id`, `image`, `lang`, `on_special`, `etat`, `position`, `articles_review`, `technical_code`, `date_insere`, `date_maj`, `site_id`) VALUES
(1, 0, '', '', 0, 1, 0, 0, '', '2017-10-17 17:09:43', '2017-10-17 17:09:43', "[SITE_ID]"),
(2, 0, '', '', 0, 1, 1, 0, '', '2017-10-17 17:10:40', '2017-10-17 17:10:40', "[SITE_ID]"),
(3, 0, '', '', 0, 1, 2, 0, '', '2017-10-17 17:58:36', '2017-10-17 17:58:36', "[SITE_ID]"),
(4, 0, '', '', 0, 1, 3, 0, '', '2017-10-17 17:58:55', '2017-10-17 17:58:55', "[SITE_ID]");