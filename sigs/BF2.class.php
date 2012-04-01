<?php
class Signature {
	var $Username;
	var $Found = false; 
	var $stats;	
	var $contents;
	var $rank;	
	var $settings;
	var $Picture;
	
	function init() {
		require_once("config.php");
		$this->settings = $CONFIG;
	}	
	function CheckName() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://battlefield.ea.com/battlefield/bf2/playerSearch.aspx?searchPattern='.$this->Username);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$this->contents = curl_exec ($ch);
		curl_close ($ch);
		if (preg_match("/\b{$this->Username}\b/i", $this->contents) && strlen($this->Username < 3) && preg_match("/playerstats\.aspx\?profileid=[0-9]{1,}/", $this->contents)) {
			$this->Found = true;
			return true;
		} else {
			return false;
		}		
	}
	function GetStats() { 
		if ($this->Found) {
			$ch = curl_init();
			preg_match("/playerstats\.aspx\?profileid=[0-9]{1,}/", $this->contents,$matches);
			curl_setopt($ch, CURLOPT_URL, "http://battlefield.ea.com/battlefield/bf2/".$matches[0]);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$this->contents = curl_exec ($ch);		
			$data = explode("Begin Content PlaceHolders",$this->contents);		
			$data = explode("SUB FOOTER",$data[1]);		
			$rank = explode("ctl00_content_SoldierRank\">",$data[0]);		
			$rank = explode("</span>",$rank[1]);
			$this->rank = $rank[0];		
			$replace  = array("/\t/","/\&nbsp;/");//,"/[ ]/","/:/","/\&nbsp;/","/[a-zA-Z]{0,12}/");		
			$this->stats = explode("|",preg_replace("/\r\n/","|", preg_replace($replace,"",strip_tags($data[0]))));
			curl_close ($ch);	
		}
	}
	function Writelinks($i) {
		if ($this->Found) {					
			switch ($i) {			
				case 0:
					echo "{$this->settings['url']}/{$this->Picture}_th.png";
					break;		
				case 1:
					echo "{$this->settings['url']}/{$this->Picture}.png";
					break;
				case 2:
					echo "[img]{$this->settings['url']}/{$this->Picture}.png[/img]";
					break;
			}
		}
	}
	function CreateImage($background = "Default") { 
		$this->Picture = time();

		
		$im = @imagecreate(110, 20) or die("Cannot Initialize new GD image stream");
		$srcImg			= imagecreatefrompng("sigs/images/BF2/{$background}.png");
		
		$this->DoText($srcImg, 6,   10, "head", "{$this->rank} {$this->Username}");
		
		$this->DoText($srcImg, 44,  30, "",     "SCORE:");   
		$this->DoText($srcImg, 100, 30, "",     $this->stats[42]);			
		$this->DoText($srcImg, 44,  37, "",     "KILLS:"); 
		$this->DoText($srcImg, 100, 37, "",     $this->stats[172]);	
		$this->DoText($srcImg, 44,  44, "",     "DEATHS:");  
		$this->DoText($srcImg, 100, 44, "",     $this->stats[191]);
		$this->DoText($srcImg, 44,  51, "",     "K/D RATIO:");	
		$this->DoText($srcImg, 100, 51, "",     $this->stats[165]);
		
		$this->DoText($srcImg, 162, 30, "",     "WINS/LOSSES: " );
		$this->DoText($srcImg, 235, 30, "",     $this->stats[99]);
		$this->DoText($srcImg, 162, 37, "",     "TEAM KILLS: "  );	
		$this->DoText($srcImg, 235, 37, "",     $this->stats[137]);
		$this->DoText($srcImg, 162, 44, "",     "KILL ASSISTS: ");
		$this->DoText($srcImg, 235, 44, "",     $this->stats[121]);
		$this->DoText($srcImg, 162, 51, "",     "ACCURACY: "    );	
		$this->DoText($srcImg, 235, 51, "",     $this->stats[153]);
		
		$this->DoText($srcImg, 295, 30, "",     "HEALS: "		);			
		$this->DoText($srcImg, 360, 30, "",     $this->stats[125]);
		$this->DoText($srcImg, 295, 37, "",     "REVIVES: "		);	
		$this->DoText($srcImg, 360, 37, "",     $this->stats[129]);
		$this->DoText($srcImg, 295, 44, "",     "RE-SUPPLIES: " );
		$this->DoText($srcImg, 360, 44, "",     $this->stats[145]);
		$this->DoText($srcImg, 295, 51, "",     "REPAIRS: "     );	
		$this->DoText($srcImg, 360, 51, "",     $this->stats[133]);
		
		imagepng($srcImg, "signatures/".$this->Picture.".png");
		imagedestroy($srcImg);		
		$this->createThumbnail("signatures", $this->Picture, "signatures", 307, 58);
		mysql_connect($this->settings['db_hostname'], $this->settings['db_username'], $this->settings['db_password']) or die("could not connect to MySQL server");
		mysql_select_db($this->settings['db_database']) or die("could not select MySQL database"); 
		mysql_query("INSERT INTO {$this->settings['db_prefix']}Signatures (`index`, `id`, `background`, `lastupdate`) VALUES (NULL, {$this->Picture}, '{$background}', CURRENT_TIMESTAMP);");
		mysql_close();
		
	}
	function DoText($srcImg, $x, $y, $font = "", $text) {
		$Head 			= "sigs/fonts/STAN0753.TTF";
		$Norm 			= "sigs/fonts/NONSTEP.TTF";
		$white 			= imagecolorallocate($srcImg, 255, 255, 255);
		$black 			= imagecolorallocate($srcImg, 0, 0, 0);
		
		if ($font == "head") { $font = $Head; } else { $font = $Norm; }
		imagettftext($srcImg, 6, 0, $x+1, $y+1, $black*-1, $font, $text);
		imagettftext($srcImg, 6, 0, $x,   $y,   $white*-1, $font, $text);	
	}	
	
	function createThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight) {
		$srcImg			= imagecreatefrompng("{$imageDirectory}/{$imageName}.png");
		$origWidth		= imagesx($srcImg);
		$origHeight		= imagesy($srcImg);
		$ratio			= $origWidth / $thumbWidth;
		$thumbImg		= imagecreate($thumbWidth, $thumbHeight);
		imagecopyresized($thumbImg, $srcImg, 0, 0, 1, 1, $thumbWidth, $thumbHeight, imagesx($thumbImg), imagesy($thumbImg));
		imagepng($thumbImg, "{$thumbDirectory}/{$imageName}_th.png");
	}
}	
?>
