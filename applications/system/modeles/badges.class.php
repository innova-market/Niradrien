<?php 
	class badges extends base {
		function __construct() {
			$this->tableName = 'badges';
			$this->uploadFileDirectory = 'badges/';
			$this->idColumn = 'id';
		}

		public function getAll($start = 0, $limit = 0, $where = '') {
			global $mysql, $language;
			
			$limitQuery = '';
			if ($limit != 0) {
				$limitQuery = " LIMIT $start, $limit ";
			}
			
			if ($where != '') {
				$where = " WHERE " . $where;
			}
			
			$mysql->query("
				SELECT SQL_CALC_FOUND_ROWS *
				FROM badges
				$where
				ORDER BY id desc
				$limitQuery
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$return = array();
			while ($d = $mysql->fetch_array()) {
				$return[$d['id']] = $d;
			}
			
			$this->foundRows = $mysql->getFoundRows();
			
			return $return;
		}
		
		public function getAllFront() {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM badges
				WHERE visible = 'true'
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			$return = array();
			while ($d = $mysql->fetch_array()) {
				if ($d['type'] == 'system') {
					$return[$d['type']][$d['sys_key']] = array(
						'name' => $d['name'],
						'name_en' => $d['name_en'],
						'photo' => $d['photo']
					);
				}

				elseif ($d['type'] == 'other') {
					$return[$d['type']][$d['id']] = array(
						'name' => $d['name'],
						'name_en' => $d['name_en'],
						'photo' => $d['photo']
					);
				}

				else {
					if (strpos($d['from'], '+') !== false) {
						$key = (int)$d['from'] . '-2147483646';
					}

					else {
						$key = $d['from'] . '-' . $d['to'];
					}

					$return[$d['type']][$key] = array(
						'name' => $d['name'],
						'name_en' => $d['name_en'],
						'photo' => $d['photo']
					);
				}
			}
			
			return $return;
		}
		
		public function get($id) {
			global $mysql;
			
			$mysql->query("
				SELECT *
				FROM badges
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			if ($mysql->num_rows() == 0) {
				return false;
			}
			
			return $mysql->fetch_array();
		}
		
		private function fromToNormalize($string) {
			$news_string = preg_replace('/[^0-9](\+$)?/', '', $string);
			
			if ($news_string && isset($string{(mb_strlen($string, 'utf-8') - 1)}) && $string{(mb_strlen($string, 'utf-8') - 1)} == '+') {
				$news_string .= '+';
			}

			return $news_string;
		}

		public function add() {
			global $mysql, $langArray, $config;
			
			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}
			
			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['name_en']) || trim($_POST['name_en']) == '') {
				$error['name_en'] = $langArray['error_fill_this_field'];
			}
			
			$photo = $this->upload('photo', '', false);
			if (substr($photo, 0, 6) == 'error_') {
				$error['photo'] = $langArray[$photo];
			}
					
			if (isset($error)) {
				return $error;
			}
			
			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}
			
			$from = 0;
			$to = 0;

			if (isset($_POST['from'])) {
				$from = $this->fromToNormalize($_POST['from']);
			}
			
			if (isset($_POST['to'])) {
				$to = $this->fromToNormalize($_POST['to']);
			}
			
			$mysql->query("
				INSERT INTO badges (
					name,
					name_en,
					photo,
					visible,
					type,
					from,
					to
				)
				VALUES (
					'" . sql_quote($_POST['name']) . "',
					'" . sql_quote($_POST['name_en']) . "',
					'" . sql_quote($photo) . "',
					'" . sql_quote($_POST['visible']) . "',
					'" . sql_quote($_GET['type']) . "',
					'" . sql_quote($from) . "',
					'" . sql_quote($to) . "'
				)
			", __FUNCTION__ );
			
			return true;
		}

		public function edit($id) {
			global $mysql, $langArray;
			
			if (!isset($_POST['name']) || trim($_POST['name']) == '') {
				$error['name'] = $langArray['error_fill_this_field'];
			}

			if (isset($error)) {
				return $error;
			}

			if (!isset($_POST['name_en']) || trim($_POST['name_en']) == '') {
				$error['name_en'] = $langArray['error_fill_this_field'];
			}
			
			$photo = $this->upload('photo', '', false);
			if (substr($photo, 0, 6) == 'error_') {
				$error['photo'] = $langArray[$photo];
			}
			
			if (isset($error)) {
				return $error;
			}
			
			$setQuery = '';
			if ($photo != '' || isset($_POST['deletePhoto'])) {
				$this->deletePhoto($id);
			}

			if ($photo != '') {
				$setQuery .= " photo = '" . sql_quote($photo) . "', ";
			}
			
			if (!isset($_POST['visible'])) {
				$_POST['visible'] = 'false';
			}
			
			$from = 0;
			$to = 0;
			
			if (isset($_POST['from'])) {
				$from = $this->fromToNormalize($_POST['from']);
			}
			if (isset($_POST['to'])) {
				$to = $this->fromToNormalize($_POST['to']);
			}
			
			$mysql->query("
				UPDATE badges
				SET name = '" . sql_quote($_POST['name']) . "',
					name_en = '" . sql_quote($_POST['name_en']) . "',
					$setQuery
					visible = '" . sql_quote($_POST['visible']) . "',
					from = '" . $from . "',
					to = '" . $to . "',
					sys_key = '" . sql_quote($_POST['sys_key']) . "'
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			return true;
		}

		public function delete($id) {
			global $mysql;
			
			$this->deletePhoto($id);
			
			$mysql->query("
				DELETE FROM badges
				WHERE id = '" . intval($id) . "'
			", __FUNCTION__ );
			
			return true;
		}	
		
		private function deletePhoto($id) {
			global $mysql, $config;
			
			$post = $this->get($id);
			
			if ($post['photo'] != '') {
				@unlink(DATA_SERVER_PATH . 'uploads/' . $this->uploadFileDirectory . $post['photo']);
			}
			
			$mysql->query("
				UPDATE badges
				SET photo = ''
				WHERE id = '" . intval($id) . "'
			");
			
			return true;
		}
	}
?>