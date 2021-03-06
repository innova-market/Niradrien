<?php 
	_setView(__FILE__);

	require_once ROOT_PATH . '/applications/members/modeles/members.class.php';
	$cms = new members();
	$membersClass = new members();

	$members = $membersClass->getAll(0, 0, $cms->membersWhere);
	abr('members', $members);

	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		refresh('?m=' . $_GET['m'] . '&c=list', $langArray['invalid_member_id'], 'error');
	}
	
	$member = $cms->get($_GET['id']);
	if (!$member) {
		refresh('?m=' . $_GET['m'] . '&c=list', $langArray['invalid_member_id'], 'error');
	}

	_setTitle ($member['username'] . ' › ' . $langArray['licenses']);

	require_once ROOT_PATH . '/applications/product/modeles/orders.class.php';
	$ordersClass = new orders();

	$data = $ordersClass->getAllMemberLicenses($_GET['id']);

	abr('data', $data);
?>