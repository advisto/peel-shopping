function form_template_content_add(select_name, content_name, mode, wwwroot){
  jQuery.ajax({
   type: "POST",
   url: wwwroot+"/modules/webmail/administrer/template_mail.php",
   data: "id="+jQuery("#"+select_name).val()+"&mode="+mode,
   success: function(msg){
	if (msg !='') {
		jQuery("#"+content_name).val(msg);
	 }
   }
 });
}