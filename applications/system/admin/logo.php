<?php
	_setView (__FILE__);
	_setTitle ($langArray['edit_site_logo']);
	
	$cms = new system();
	
	$get_info = $cms->getLogo();
	
	if (isset($_POST['edit'])) {
		$status = $cms->editLogo();
		
		if ($status !== true) {			
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=logo', $langArray['edit_complete']);
		}
	}
	
	else {
		$_POST = $get_info;
	}		
?>