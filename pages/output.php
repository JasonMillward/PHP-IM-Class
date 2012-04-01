<?PHP
	//if ( !isset($_SESSION['Sig']) ) {
	//	header("location: ./");
	//}	
	require_once("inc/functions.php");
	require_once("sigs/{$_SESSION['Sig']}.class");
	
	$jSigs = new Signature;
	$jSigs->Username = ucfirst($_POST['Username']);
	$jSigs->init();	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Your Signature</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-language" content="en">
<link href="style.css" rel="stylesheet" type="text/css" media="screen, projection">
</head><body>
<div class="centered" style="width: 400px;">
<div class="containter">
<h5><?PHP echo "Your {$_SESSION['Signature']} signature"; ?></h5>

	<form method="POST" action="" id="SigForm">
	<fieldset>
		<ul class="searchform">
		<?PHP 
			if (!$jSigs->CheckName()) {
		?>
			<li>
				<dl>
					<dd>
						User not found.
					</dd>
				</dl>
			</li>
		<?PHP
			} else {
				$jSigs->GetStats();
				$jSigs->CreateImage();
		?>
		
			<li>
				<dl>
					<dd>
						<img class="imgthumb" src="<?PHP $jSigs->Writelinks(0); ?>" title="Thumbnail" />						
					</dd>
				</dl>
			</li>
			<li>
				<dl>
					<dt>Direct</dt>
					<dd>
						<input class="output" type="text" name="Direct" value="<?PHP $jSigs->Writelinks(1); ?>" onClick="javascript:this.form.Direct.focus();this.form.Direct.select();">						
					</dd>
				</dl>
			</li>	
			<li>
				<dl>
					<dt>Forum</dt>
					<dd>
						<input class="output" type="text" name="Forum" value="<?PHP $jSigs->Writelinks(2); ?>" onClick="javascript:this.form.Forum.focus();this.form.Forum.select();">						
					</dd>
				</dl>
			</li>
		<?PHP
			}
		?>			
		</ul>
	</fieldset>
	</form>
</div>
</div>
</body></html>
<?PHP
	//session_unset();
	//session_destroy();
?>