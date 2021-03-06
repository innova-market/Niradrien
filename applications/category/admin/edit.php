<?php
	_setView (ROOT_PATH . '/applications/' . $_GET['m'] . '/admin/add.php');
	_setTitle ($langArray['edit']);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', 'INVALID ID', 'error');
	}

	if(!isset($_GET['p'])) {
		$_GET['p'] = '';
	}

	require_once ROOT_PATH . '/applications/category/modeles/category.class.php';
	$cms = new category();

	if (isset($_POST['edit'])) {
		$status = $cms->edit($_GET['id']);
		
		if ($status !== true) {		
			abr('error', $status);
		}

		else {
			refresh ('?m=' . $_GET['m'] . '&c=list&sub_of=' . $_GET['sub_of'] . '&p=' . $_GET['p'], $langArray['edit_complete']);
		}
	}

	else {
		$_POST = $cms->get($_GET['id']);
	}

	// var_dump($cms); die();

	$pagesArr = $cms->getAllWithChilds($_GET['id']);
	$select = $cms->generateSelect($pagesArr, $_POST['sub_of']);
	abr('select', $select);

	if ($_GET['sub_of'] != 0) {
		$pdata = $cms->get($_GET['sub_of']);
		abr('pdata', $pdata);
	}
?>