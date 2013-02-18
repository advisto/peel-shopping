
function mail_signature(admin_name, admin_first_name, site_name, site_url, lang)
{
	//Si on n'a pas choisi de template, on prend la valeur de la langue dans le formulaire (et non plus celle du template)
	var message=document.getElementById('message').value;
	var cordialement = '';
	var admin_function = document.getElementById("function")[document.getElementById("function").selectedIndex].text;
	//Gestion des langues
	if(lang=='en') {
		cordialement = 'Yours Sincerely';
		switch (admin_function) {
			case 'Pas de signature':
				admin_function = 'none';
				break;
			case 'Service inconnu':
				admin_function = '';
				break;
			case 'Support clientèle':
				admin_function = 'Support customers';
				break;
			case 'Commercial':
				admin_function = 'Commercial department';
				break;
			case 'Comptabilité':
				admin_function = 'Accounting department';
				break;
			case 'Référencement':
				admin_function = 'Referencing department';
				break;
			case 'Technique':
				admin_function = 'Technical department';
				break;
			case 'Communication':
				admin_function = 'Communication department';
				break;
			case 'Marketing':
				admin_function = 'Marketing department';
				break;
			case 'Direction':
				admin_function = 'The direction';
				break;
			case 'Externe':
				admin_function = 'External department';
				break;
		}
	}else if(lang=='es') {
		cordialement = 'Cordialmente';
		switch (admin_function) {
			case 'Pas de signature':
				admin_function = 'none';
				break;
			case 'Service inconnu':
				admin_function = '';
				break;
			case 'Support clientèle':
				admin_function = 'Soporte clientela';
				break;
			case 'Commercial':
				admin_function = 'Servicio comercial';
				break;
			case 'Comptabilité':
				admin_function = 'Servicio contabilidad';
				break;
			case 'Référencement':
				admin_function = 'Servicio toma de referencia';
				break;
			case 'Technique':
				admin_function = 'Servicio técnico';
				break;
			case 'Communication':
				admin_function = 'Servicio comunicación';
				break;
			case 'Marketing':
				admin_function = 'Servicio marketing';
				break;
			case 'Direction':
				admin_function = 'La dirección';
				break;
			case 'Externe':
				admin_function = 'Servicio externo';
				break;
		}
	}else {
		cordialement = 'Cordialement';
		if(admin_function=="Pas de signature"){
			admin_function = 'none';
		}else if(admin_function=="Support Clientèle" || admin_function=="Direction") {
			admin_function = admin_function;
		}else if(admin_function=="Service inconnu") {
			admin_function = "";
		}else{
			admin_function = "Service " + admin_function.toLowerCase();
		}
	}

	var signature_begin="\n\n" + cordialement + ", \n\n";
	var signature = signature_begin;
	if(document.getElementById("signature_with_name").checked) {
		if(lang=='es'){
			signature += admin_first_name + " " + admin_name + "\n" + site_name + " " + admin_function + "."+ "\n" + site_url;
		}else{
			signature += admin_first_name + " " + admin_name + "\n" + admin_function + " " + site_name + "."+ "\n" + site_url;
		}
	} else {
		signature += admin_function + " " + site_name + "."+ "\n" + site_url;
	}
	// Compatibilité avec IE : les \n du message sont automatiquement replacés par \r\n => pour pouvoir remplacer il faut remettre les sauts de lignes \n
	message=message.replace(/\r\n/g, "\n");
	if(message.lastIndexOf(signature_begin)>-1){
		message=message.substr(0,message.lastIndexOf(signature_begin));
	}

	if (admin_function == 'none') {
		signature = '';
	}

	document.getElementById("message").value = message + signature;
}


function form_template_content_add(select_name, content_name, mode, wwwroot){
  jQuery.ajax({
   type: "POST",
   url: wwwroot+"/modules/webmail/administrer/template_mail.php",
   data: "id="+jQuery("#"+select_name).val()+"&mode="+mode,
   success: function(msg){
     jQuery("#"+content_name).val(msg);
   }
 });
 }