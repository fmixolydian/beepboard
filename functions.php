<?php
	
	if (!defined('BB_IMPORTED')) {
		
		define('BEEPMODS_URL', array(
			"abyssbox"    => 'https://choptop84.github.io/abyssbox-app/',
			"beepbox"     => 'https://www.beepbox.co',
			"goldbox"     => 'https://aurysystem.github.io/goldbox/',
			"jummbox"     => 'https://jummb.us',
			"modbox"      => 'https://moddedbeepbox.github.io/3.0/',
			"pandorasbox" => 'https://paandorasbox.github.io/',
			"sandbox"     => 'https://fillygroove.github.io/sandbox-3.1/',
			"slarmoosbox" => 'https://slarmoo.github.io/slarmoosbox/website/',
			"ultrabox"    => 'https://ultraabox.github.io/',
			"wackybox"    => 'https://bluecatgamer.github.io/Wackybox/'
		));
		
		define('BB_IMPORTED', 1);
		
		$db = new SQLite3(DB_PATH);
		
		function BB_getCommentdataById($id) {
			$result = BB_sqlStatement("SELECT * FROM comments WHERE commentid = :id",
									array(':id' => $id));
			
			return $result ? $result->fetchArray() : false;
		}
		
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
		
		function SECONDS($n) {return $n;}
		function MINUTES($n) {return floor($n / 60);}
		function HOURS($n)   {return floor($n / 60 / 60);}
		function DAYS($n)    {return floor($n / 60 / 60 / 24);}
		function WEEKS($n)   {return floor($n / 60 / 60 / 24 / 7);}
		function MONTHS($n)  {return floor($n / 60 / 60 / 24 / 30);}
		function YEARS($n)   {return floor($n / 60 / 60 / 24 / 365);}
		
		function BB_time_ago($t) {
			$ago = time() - $t;
			
			    if (MONTHS($ago)  >= 18) return YEARS($ago)   . " year(s) ago";
			elseif (WEEKS($ago)   >= 4)  return MONTHS($ago)  . " month(s) ago";
			elseif (DAYS($ago)    >= 7)  return WEEKS($ago)   . " week(s) ago";
			elseif (HOURS($ago)   >= 24) return DAYS($ago)    . " day(s) ago";
			elseif (MINUTES($ago) >= 60) return HOURS($ago)   . " hour(s) ago";
			elseif (SECONDS($ago) >= 60) return MINUTES($ago) . " minute(s) ago";
			else                         return SECONDS($ago) . " second(s) ago";
		}
	}
?>