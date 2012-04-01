<?PHP
	function OutputImage() {
		header("Content-type: image/png");			
		$filename = "signatures/{$_GET['id']}.png";
		$handle = fopen($filename, "r");
		echo fread($handle, filesize($filename));
		fclose($handle);		
	}
	
	function UpdateSQL() {
		require_once("config.php");
		mysql_connect($CONFIG['db_hostname'], $CONFIG['db_username'], $CONFIG['db_password']) or die("could not connect to MySQL server");
		mysql_select_db($CONFIG['db_database']) or die("could not select MySQL database"); 
		mysql_query("UPDATE `{$CONFIG['db_prefix']}Signatures}` SET `lastupdate` =  '".time()."' WHERE `id` = {$_GET['id']} LIMIT 1");
		mysql_close();
	}

?>