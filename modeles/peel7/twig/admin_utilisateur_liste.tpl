{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_utilisateur_liste.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<form method="get" action="{{ action|escape('html') }}">
	<table class="full_width" cellpadding="2">
		<tr>
			<td class="entete">{{ STR_ADMIN_CHOOSE_SEARCH_CRITERIA }}</td>
		</tr>
		<tr>
			<td class="input_search">
				<table class="full_width center" cellpadding="2">
					<tr>
						<td>{{ STR_ADMIN_ID }} / {{ STR_EMAIL }} / {{ STR_PSEUDO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_FIRST_NAME }} / {{ STR_LAST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_COMPANY }} / {{ STR_SIREN }} / {{ STR_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_TELEPHONE }} / {{ STR_FAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					</tr>
					<tr>
						<td><input type="text" name="email" value="{{ email|str_form_value }}" style="width: 200px;" /></td>
						<td><input type="text" name="client_info" value="{{ client_info|str_form_value }}" style="width: 200px;" /></td>
						<td><input type="text" name="societe" value="{{ societe|str_form_value }}" style="width: 200px;" /></td>
						<td><input type="text" name="tel" value="{{ tel|str_form_value }}" style="width: 200px;" /></td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_PROFILE_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_UTILISATEURS_NEWSLETTER_SUBSCRIBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_UTILISATEURS_COMMERCIAL_OFFERS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_UTILISATEURS_MANAGED_BY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					</tr>
					<tr>
						<td>
							<select name="priv" id="priv" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ profil_select_options }}
							</select>
						</td>
						<td>
							<select name="newsletter" id="newsletter" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ newsletter_options }}
							</select>
						</td>
						<td>
							<select name="offre_commercial" id="newsletter" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ offre_commercial_options }}
							</select>
						</td>
						<td>
							<select id="liste_commerciaux" name="commercial" style="width: 200px;">
								<option value="0">--</option>
								{% for o in commercial_options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.prenom }}{{ STR_BEFORE_TWO_POINTS }}{{ o.nom_famille }}</option>
								{% endfor %}
							</select>
						</td>
					</tr>
					<tr>
						<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ORIGIN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_LANGUAGE }} {% if is_crons_module_active %}{{ STR_ADMIN_UTILISATEURS_MANDATORY_FOR_MULTIPLE_SEND }}{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_TOWN }} / {{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					</tr>
					<tr>
						<td>
							<select name="etat" id="etat" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								<option value="1"{% if etat == '1' %} checked="checked"{% endif %}>{{ STR_ADMIN_ACTIVATED }}</option>
								<option value="0"{% if etat == '0' %} checked="checked"{% endif %}>{{ STR_ADMIN_DEACTIVATED }}</option>
							</select>
						</td>
						<td>
							<select id="origin" name="origin" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{% for o in user_origin_options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
								{% endfor %}
							</select>
						</td>
						<td>
							<select name="user_lang" id="user_lang" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{% for o in langs %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
								{% endfor %}
							</select>
						</td>
						<td><input style="width:200px;" type="text" name="ville_cp" value="{{ ville_cp|str_form_value }}" /></td>
					</tr>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_WHO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_FONCTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					</tr>
					<tr>
						<td>
							<select name="seg_who" id="seg_who" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ seg_who }}
							</select>
						</td>
						<td>
							<select id="type" name="type" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{{ STR_BUYERS }}{{ STR_BEFORE_TWO_POINTS }}:</option>
								<option value="importers_exporters"{% if type=='importers_exporters' %} selected="selected"{% endif %}>{{ STR_IMPORTERS_EXPORTERS }}</option>
								<option value="commercial_agent"{% if type=='commercial_agent' %} selected="selected"{% endif %}>{{ STR_COMMERCIAL_AGENT }}</option>
								<option value="purchasing_manager"{% if type=='purchasing_manager' %} selected="selected"{% endif %}>{{ STR_PURCHASING_MANAGER }}</option>
								<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{{ STR_WORD_SELLERS }}{{ STR_BEFORE_TWO_POINTS }}:</option>
								<option value="wholesaler"{% if type=='wholesaler' %} selected="selected"{% endif %}>{{ STR_WHOLESALER }}</option>
								<option value="half_wholesaler"{% if type=='half_wholesaler' %} selected="selected"{% endif %}>{{ STR_HALF_WHOLESALER }}</option>
								<option value="retailers"{% if type=='retailers' %} selected="selected"{% endif %}>{{ STR_RETAILERS }}</option>
							</select>
						</td>
						<td>
							<select id="fonction" name="fonction" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								<option value="leader"{% if fonction=='leader' %} selected="selected"{% endif %}>{{ STR_LEADER }}</option>
								<option value="manager"{% if fonction=='manager' %} selected="selected"{% endif %}>{{ STR_MANAGER }}</option>
								<option value="employee"{% if fonction=='employee' %} selected="selected"{% endif %}>{{ STR_EMPLOYEE }}</option>
							</select>
						</td>
						<td>
							<select name="site_on" id="site_on" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								<option value="1"{% if site_on=='1' %} selected="selected"{% endif %}>{{ STR_YES }}</option>
								<option value="0"{% if site_on=='0' %} selected="selected"{% endif %}>{{ STR_NO }}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_BUY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_UTILISATEURS_WANTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_UTILISATEURS_THINKS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_BY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					</tr>
					<tr>
						<td>
							<select name="seg_buy" id="seg_buy" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ seg_buy }}
							</select>
						</td>
						<td>
							<select name="seg_want" id="seg_want" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ seg_want }}
							</select>
						</td>
						<td>
							<select name="seg_think" id="seg_think" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ seg_think }}
							</select>
						</td>
						<td>
							<select name="seg_followed" id="seg_followed" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ seg_followed }}
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<br />
				<table class="full_width left" cellpadding="2">
					<tr>
						<td>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<select name="pays" id="list_pays" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ country_select_options }}
							</select>
						</td>
						<td colspan="2">
						{% for c in continent_inputs %}
							<input type="checkbox" name="continent[]" value="{{ c.value|str_form_value }}"{% if c.issel %} checked="checked"{% endif %} /> {{ c.name }}
						{% endfor %}
						</td>
					</tr>
					<tr>
						<td width="170">{{ STR_ADMIN_UTILISATEURS_REGISTRATION_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td width="155">
							<select name="date_insert" id="date_insert" onkeyup="display_input2_element(this.id)" onclick="display_input2_element(this.id)" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{% for o in date_insert_options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
								{% endfor %}
							</select>
						</td>
						<td width="155"><input type="text" name="date_insert_input1" id="date_insert_input1" class="datepicker" value="{{ date_insert_input1|str_form_value }}" /></td>
						<td><span id="date_insert_input2_span"> {{ STR_ADMIN_DATE_BETWEEN_AND }} <input type="text" name="date_insert_input2" id="date_insert_input2" class="datepicker" value="{{ date_insert_input2|str_form_value }}" /></span><script>display_input2_element('date_insert');</script></td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<select name="date_last_paiement" id="date_last_paiement" onkeyup="display_input2_element(this.id)" onclick="display_input2_element(this.id)" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{% for o in date_last_paiement_options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
								{% endfor %}
							</select>
						</td>
						<td><input type="text" name="date_last_paiement_input1" id="date_last_paiement_input1" class="datepicker" value="{{ date_last_paiement_input1|str_form_value }}" /></td>
						<td><span id="date_last_paiement_input2_span"> {{ STR_ADMIN_DATE_BETWEEN_AND }} <input type="text" name="date_last_paiement_input2" id="date_last_paiement_input2" class="datepicker" value="{{ date_last_paiement_input2|str_form_value }}" /></span><script>display_input2_element('date_last_paiement');</script></td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_ORDER_STATUS_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<select name="date_statut_commande" id="date_statut_commande" onkeyup="display_input2_element(this.id)" onclick="display_input2_element(this.id)" style="width: 200px;">
								<option value="">{{ STR_CHOOSE }}...</option>
								{% for o in date_statut_commande_options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
								{% endfor %}
							</select>
						</td>
						<td><input type="text" name="date_statut_commande_input1" id="date_statut_commande_input1" class="datepicker" value="{{ date_statut_commande_input1|str_form_value }}" /></td>
						<td><span id="date_statut_commande_input2_span"> {{ STR_ADMIN_DATE_BETWEEN_AND }} <input type="text" name="date_statut_commande_input2" id="date_statut_commande_input2" class="datepicker" value="{{ date_statut_commande_input2|str_form_value }}" /></span><script>display_input2_element('date_statut_commande');</script></td>
					</tr>
					{% if is_abonnement_module_active %}
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_SUBSCRIBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<select name="abonne" id="abonne" style="width:200px">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ abonne }}
							</select>
						</td>
					</tr>
					{% endif %}
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_PRODUCT_BOUGHT_AND_QUANTITY }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
						<td>
							<select name="list_produit" id="list_produit" style="width:200px">
								<option value="">{{ STR_CHOOSE }}...</option>
								{% for o in produits_options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }} (id={{ o.id }})</option>
								{% endfor %}
							</select>
						</td>
						<td>
							<select name="nombre_produit" id="nombre_produit">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ nombre_produit }}
							</select>
						</td>
					</tr>
					{% if is_annonce_module_active %}
					<tr>
						<td>
							<tr>
								<td>{{ STR_MODULE_ANNONCES_ADMIN_USER_WITH_GOLD }}{{ STR_BEFORE_TWO_POINTS }}:</td>
								<td><input name="with_gold_ad" id="with_gold_ad" type="checkbox" value="1"{% if with_gold_ad == '1' %} checked="checked"{% endif %} /></td>
							</tr>
						</td>
					</tr>
					<tr>
						<td>{{ STR_MODULE_ANNONCES_ADMIN_USER_ADS_COUNT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<table>
								<tr>
									<td>
										<select name="ads_count" id="ads_count" onkeyup="display_input2_element(this.id)" onclick="display_input2_element(this.id)" style="width: 200px;">
											<option value="">{{ STR_CHOOSE }}...</option>
											{% for o in ads_options %}
											<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
											{% endfor %}
										</select>
									</td>
								</tr>
							</table>
						</td>
						<td><input type="text" name="ads_count_input1" id="ads_count_input1" value="{{ ads_count_input1|str_form_value }}" /></td>
						<td><span id="ads_count_input2_span"> {{ STR_AND }} <input type="text" name="ads_count_input2" id="ads_count_input2" value="{{ ads_count_input2|str_form_value }}" /></span><script>display_input2_element('ads_count');</script></td>
					</tr>
					<tr>
						<td>{{ STR_MODULE_ANNONCES_ADMIN_ADS_AVAILABLE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<select name="list_annonce" id="list_annonce" style="width: 200px;>
								<option value="">{{ STR_CHOOSE }}...</option>
								{% for o in annonces_options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
								{% endfor %}
							</select>
						</td>
						<td>{{ STR_MODULE_ANNONCES_ADMIN_ADS_CONTAIN }}</td>
						<td><input type="text" name="annonces_contiennent" id="annonces_contiennent" value="{{ annonces_contiennent|str_form_value }}" /></td>
					</tr>			
					{% endif %}
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_CONTACT_FORECASTED_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<table cellspadding="0" style="width:100%">
								<tr>
									<td>
										<select name="date_contact_prevu" id="date_contact_prevu" onkeyup="display_input2_element(this.id)" onclick="display_input2_element(this.id)" style="width: 200px;">
											<option value="">{{ STR_CHOOSE }}...</option>
											{% for o in date_contact_prevu_options %}
											<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
											{% endfor %}
										</select>
									</td>
								</tr>
							</table>
						</td>
						<td colspan="2">
							<table cellspadding="0" style="width:100%">
								<tr>
									<td>
										<input type="text" name="date_contact_prevu_input1" id="date_contact_prevu_input1" class="datepicker" value="{{ date_contact_prevu_input1|str_form_value }}" />
									</td>
									<td>
										<span id="date_contact_prevu_input2_span"> {{ STR_ADMIN_DATE_BETWEEN_AND }} <input type="text" name="date_contact_prevu_input2" id="date_contact_prevu_input2" class="datepicker" value="{{ date_contact_prevu_input2|str_form_value }}" /></span>
									</td> 
									<td>
<script><!--//--><![CDATA[//><!--
display_input2_element('date_contact_prevu');
//--><!]]></script>
										{{ STR_ADMIN_REASON }}{{ STR_BEFORE_TWO_POINTS }}:
										<select name="raison" style="width:200px;">
											<option value="">{{ STR_CHOOSE }}...</option>
											{{ raison }}
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_LAST_LOGIN_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<table>
								<tr>
									<td>
										<select name="date_derniere_connexion" id="date_derniere_connexion" onkeyup="display_input2_element(this.id)" onclick="display_input2_element(this.id)" style="width: 200px;">
											<option value="">{{ STR_CHOOSE }}...</option>
											{% for o in date_derniere_connexion_options %}
											<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
											{% endfor %}
										</select>
									</td>
								</tr>
							</table>
						</td>
						<td><input type="text" name="date_derniere_connexion_input1" id="date_derniere_connexion_input1" class="datepicker" value="{{ date_derniere_connexion_input1|str_form_value }}" /></td>
						<td><span id="date_derniere_connexion_input2_span"> {{ STR_ADMIN_DATE_BETWEEN_AND }} <input type="text" name="date_derniere_connexion_input2" id="date_derniere_connexion_input2" class="datepicker" value="{{ date_derniere_connexion_input2|str_form_value }}" /></span><script>display_input2_element('date_derniere_connexion');</script></td>
					</tr>
				</table>
				<p align="center">
					<input type="hidden" name="mode" value="search" /><input type="submit" class="bouton" value="{{ STR_SEARCH|str_form_value }}" />
				</p>
			</td>
		</tr>
	</table>
</form>

<form action="{{ wwwroot }}/modules/webmail/administrer/webmail_send.php" method="post">
	<table id="tablesForm" class="main_table">
		<tr>
			<td class="entete" colspan="{{ count_HeaderTitlesArray }}">
			{{ STR_ADMIN_UTILISATEURS_USERS_COUNT }}{{ STR_BEFORE_TWO_POINTS }}: {{ nbRecord }}
			</td>
		</tr>
		<tr>
			<td colspan="{{ count_HeaderTitlesArray }}"><div class="global_help">{{ STR_ADMIN_UTILISATEURS_LIST_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td colspan="{{ count_HeaderTitlesArray }}" >
			<table>
				<tr>
					<td><img src="{{ administrer_url }}/images/add.png" width="17" height="17" alt="" /></td>
					<td><a href="{{ administrer_url }}/utilisateurs.php?mode=ajout">{{ STR_ADMIN_UTILISATEURS_CREATE }}</a><br /></td>
				</tr>
			</table>

			</td>
		</tr>
		<tr>
			<td colspan="{{ count_HeaderTitlesArray }}">
				<a href="{{ wwwroot_in_admin }}/modules/export/administrer/export_clients.php?priv={{ priv }}&amp;cle={{ cle }}">{{ STR_ADMIN_UTILISATEURS_EXCEL_EXPORT }}</a>
			</td>
		</tr>
		<tr><td colspan="{{ count_HeaderTitlesArray }}" align="center">{{ link_multipage }}</td></tr>
		{{ link_HeaderRow }}
		{% if (results) %}
		{% for res in results %}
		{{ res.tr_rollover }}
			<td style="width:70px">
				<table>
					<tr>
						<td>
							<td class="center"><input name="user_ids[]" type="checkbox" value="{{ res.id_utilisateur|str_form_value }}" id="{{ res.id_utilisateur }}" /></td>
						</td>
						<td>
							<a onclick="return confirm('{{ STR_ADMIN_DELETE_WARNING|filtre_javascript(true,true,true) }}');" title="{{ STR_DELETE|str_form_value }} {{ res.email }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ administrer_url }}/images/b_drop.png" alt="" /></a>
						</td>
						<td>
							<a onclick="return confirm('{{ STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD_CONFIRM }}');" title="{{ STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD|str_form_value }} {{ res.email }}" href="{{ res.init_href|escape('html') }}"><img src="{{ administrer_url }}/images/password-24.gif" alt="" /></a>
						</td>
						<td>
							<a title="{{ STR_ADMIN_UTILISATEURS_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ administrer_url }}/images/b_edit.png" width="17" height="17" alt="" /></a>
						</td>
						<td>
							<a {% if (res.etat) %} onclick="return confirm('{{ STR_ADMIN_UTILISATEURS_DEACTIVATE_USER }}');"{% endif %} title="{{ STR_ADMIN_UTILISATEURS_UPDATE_STATUS|str_form_value }}" href="{{ res.modif_etat_href|escape('html') }}"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" /></a>
						</td>
					</tr>
				</table>
			</td>
			<td class="center">
				{{ res.profil_name }}<br /><a title="{{ STR_ADMIN_UTILISATEURS_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.code_client|html_entity_decode_if_needed }}</a>
				{% if is_annonce_module_active %}
				<br />{{ res.pseudo|html_entity_decode_if_needed }}<br />{{ res.annonces_count }} {{ STR_MODULE_ANNONCES_AD }}
				{% endif %}
			</td>
			<td class="center"><b>{{ res.prenom|html_entity_decode_if_needed }} {{ res.nom_famille|html_entity_decode_if_needed }}</b><br />{% if is_not_demo %}<a href="{{ wwwroot_in_admin }}/modules/webmail/administrer/webmail_send.php?id_utilisateur={{ res.id_utilisateur }}">{{ res.email|html_entity_decode_if_needed }}</a>{% endif %}
			{% if is_annonce_module_active %}
				<br />{{ res.societe|html_entity_decode_if_needed }}, {% if res.siret_length > 6 %}{{ STR_NUMBER }}{{ res.siret|html_entity_decode_if_needed }}{% else %}{% if (res.siret) %}<br />
				<span style="color:red">{{ STR_SIRET }} {{ res.siret|html_entity_decode_if_needed }}</span>{% else %}<br /><span style="color:red">{{ STR_SIRET }} : {{ STR_NONE }}.</span>{% endif %}{% endif %}<br />
					{{ res.code_postal|html_entity_decode_if_needed }} {{ res.ville }} - {{ res.country_name }}
			{% endif %}
			</td>
			<td>{{ res.phone_output }}</td>
			{% if is_groups_module_active %}
			<td class="center">
			{% if (res.group_nom) and (res.group_remise) %}
				{{ res.group_nom|html_entity_decode_if_needed }} - {{ res.group_remise }} %
			{% else %}
				-
			{% endif %}
			</td>
			{% endif %}
			<td class="center">{{ res.date_insert }}</td>
			<td class="center">{{ res.remise_percent }} %</td>
			{% if is_parrainage_module_active %}
			<td class="center">{{ res.calculer_avoir_client_prix }}</td>
			{% endif %}
			<td class="center">{{ res.avoir_prix }}</td>
			<td class="center">{{ res.points }}</td>
			{% if is_parrainage_module_active %}
			<td class="center">{{ res.compter_nb_commandes_parrainees }}</td>
			<td class="center">{{ res.recuperer_parrain }}</td>
			{% endif %}
			<td class="center"><a href="{{ administrer_url }}/codes_promos.php?mode=code_pour_client&amp;id_utilisateur={{ res.id_utilisateur }}"><img src="{{ wwwroot_in_admin }}/icones/cheque.gif" width="25" height="25" alt="{{ STR_ADMIN_UTILISATEURS_GIFT_CHECK|str_form_value }}" /></a></td>
			<td class="center"><a href="{{ administrer_url }}/commander.php?mode=ajout&amp;id_utilisateur={{ res.id_utilisateur }}"><img src="{{ wwwroot_in_admin }}/icones/proforma.gif" width="25" height="25" alt="{{ STR_ORDER_FORM|str_form_value }}" /></a></td>
		</tr>
		{% endfor %}
		<tr><td colspan="{{ count_HeaderTitlesArray }}" align="center">{{ link_multipage }}</td></tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr><td colspan="{{ count_HeaderTitlesArray }}" align="center">
			<input type="button" class="bouton" onclick="if (markAllRows('tablesForm')) return false;" value="{{ STR_ADMIN_CHECK_ALL|str_form_value }}" />
			<input type="button" class="bouton" onclick="if (unMarkAllRows('tablesForm')) return false;" value="{{ STR_ADMIN_UNCHECK_ALL|str_form_value }}" />
				&nbsp; &nbsp; &nbsp; &nbsp; <input class="bouton" type="submit" value="{{ STR_ADMIN_UTILISATEURS_SEND_EMAIL_TO_SELECTED_USERS|str_form_value }}" /></td>
		</tr>
		{% else %}
		<tr><td colspan="{{ count_HeaderTitlesArray }}"><br /><b>{{ STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND }}</b></td></tr>
		{% endif %}
		<tr>
			<td colspan="{{ count_HeaderTitlesArray }}"></td>
		</tr>
	</table>
</form>
{% if (send_email_all_form) %}
<center>{{ send_email_all_form }}</center>
{% else %}
<div style="width:405px; margin:auto auto;" class="global_help">{{ STR_ADMIN_UTILISATEURS_FILER_EXPLAIN }}</div>
{% endif %}