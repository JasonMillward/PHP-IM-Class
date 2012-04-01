<?PHP
	require_once("inc/functions.php");
	$_SESSION['Sig'] = 'BF2';
	$_SESSION['Signature'] = 'Battle Field 2';	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>
<title>Battle Field 2 Signature</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="content-language" content="en">
<link href="style.css" rel="stylesheet" type="text/css" media="screen, projection">
</head><body>
<div class="centered" style="width: 400px;">
<div class="containter">
<h5>Battle Field 2</h5>
	<form method="post" action="" id="BF2">
	<fieldset>
		<ul class="searchform">
			<li>
				<dl>
					<dt>Username</dt>
					<dd>
						<input name="Username" class="keyword" type="text">						
					</dd>
				</dl>
			</li>	
			<li>
				<dl>
					<dt>Background</dt>
					<dd>
						<input name="Background" class="keyword" type="text" value="Default" disabled >						
					</dd>
				</dl>
			</li>	
			<li><div class="centered" style="width: 150px;"><input id="submit" value="Submit" type="submit"></div></li>
		</ul>
	</fieldset>
	</form>
</div>
</div>
</body></html>