<?php
	
	if (!defined('BB_IMPORTED')) {
		
		define('BB_IMPORTED', 1);
		
		$db = new SQLite3(DB_PATH);
		
		function BB_getUserdataById($id) {
			
			$result = BB_sqlStatement("SELECT * FROM users WHERE userid = :id",
									array(':id' => $id));
			
			return $result ? $result->fetchArray() : false;
		}
		
		function BB_getSongdataById($id) {
			$result = BB_sqlStatement("SELECT * FROM songs WHERE songid = :id",
								array(':id' => $id));
			
			return $result ? $result->fetchArray() : false;
		}
		
		
		function BB_getUserdataByName($name) {
			$result = BB_sqlStatement("SELECT * FROM users WHERE username = :name",
									array(':name' => $name));
			
			return $result ? $result->fetchArray() : false;
		}
		
		function BB_getUserdataByToken($token) {
			$result = BB_sqlStatement("SELECT * FROM users WHERE token = :t",
									array(':t' => $token));
			
			return $result ? $result->fetchArray() : false;
		}
		
		function BB_sqlStatement($statement, $params) {
		
			global $db;
			
			$st = $db->prepare($statement);
			
			foreach ($params as $param => $value) {
				$st->bindValue($param, $value);
			}
			
			return $st->execute();
		}
		
		function BB_default(&$value, $default) {
			if ($value == NULL) {
				$value = $default;
			}
		}
	}
?>