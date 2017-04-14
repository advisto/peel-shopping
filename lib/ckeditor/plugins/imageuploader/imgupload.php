<?php

// Including the plugin config file, don't delete the following row!
require(__DIR__ . '/pluginconfig.php');


$upload = upload("upload", false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
$ckfile = $GLOBALS['repertoire_upload'] .'/'. $upload;
if (empty($GLOBALS['notification_output_array'])) {
	if(isset($_GET['CKEditorFuncNum'])){
		$CKEditorFuncNum = $_GET['CKEditorFuncNum'];
		echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$ckfile', '');</script>";
	}
} else {
	foreach($GLOBALS['notification_output_array'] as $this_error) {
		echo "<script>alert('". filtre_javascript(strip_tags($this_error), true, true, true)."');</script>";
	}
}
//Back to previous site
if(!isset($_GET['CKEditorFuncNum'])){
    echo '<script>history.back();</script>';
}
