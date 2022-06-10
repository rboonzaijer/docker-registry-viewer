<?php

// Settings
$host = getenv('HOST');
$host_ui = getenv('HOST_UI');
$https = getenv('HTTPS');

if($host === false) {
	$host = 'localhost:5000';
}
if($host_ui === false) {
	$host_ui = $host;
}
if($https === true || $https === 'true' || $https === 1 || $https === '1') {
	$https = true;
} else {
	$https = false;
}

// Fetch the images + tags
$api_url = ($https ? 'https://' : 'http://') . $host . '/v2/';
$results = [];
$images = json_decode(file_get_contents($api_url . '_catalog'), true)['repositories'];
foreach($images as $image_name) {
	$results[$image_name] = json_decode(file_get_contents($api_url . $image_name . '/tags/list'), true)['tags'];
}

?><html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<title>Docker Registry Viewer</title>

	<style type="text/css">
		html, body {
			margin:0;
			padding:0;
			font-family: Arial, Helvetica, sans-serif;
		}
		.container {
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		h3 {
			width: 100%;
			text-align: center;
			background: #2496ed;
			color: #fff;
			padding: 25px 5px 10px 5px;
			margin-top: 0;
			font-family: 'Courier New', Courier, monospace;
		}
		h3 span {
			display: block;
    		margin-top: 20px;
    		font-size: 90%;
		}
		h2 {
			margin-bottom: 0;
		}
		input {
			width: 400px;
			cursor: pointer;
			background: #f1f4f6;
			border: none;
    		border-radius: 2px;
			padding: 5px 12px;
		}
		ul li {
			list-style: none;
		}
		span.copy {
			cursor: pointer;
			background: #333;
			color: #fff;
			font-size: 10px;
			padding: 5px 15px;
		}
		span.copy:hover {
			background: #000;
		}
		#copy-message {
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%,-50%);
			background: #189e22;
			color: #fff;
			pointer-events: none;
			opacity: 0;
			margin: 0;
			padding: 5px 20px;
		}
		#copy-message.visible {
			opacity: 1;
		}
	</style>
	<script>
		window.copyTimeout = null;

		function copyToClipboard(element) {
			element.select();
			document.execCommand("copy");

			clearTimeout(window.copyTimeout);
			document.querySelector('#copy-message').className = 'visible';
			window.copyTimeout = setTimeout(function() {
				document.querySelector('#copy-message').className = '';
			}, 1000);
		}
	</script>
</head>
<body>
	<div id="copy-message">
		<p>Copied to clipboard!</p>
	</div>

	<div class="container">
		<h3>Docker Registry Viewer <span><?php echo $host_ui; ?></p></h3>
		
		<?php if(count($results) === 0) { ?>
			<p>No images found</p>
			<p>( <a href="<?php echo $api_url . '_catalog'; ?>"><?php echo $api_url . '_catalog'; ?>)</a> </p>
		<?php } else { ?>
			<?php foreach($results as $image => $tags) { ?>
				<h2><?php echo $image; ?></h2>
				<ul>
					<?php foreach($tags as $tag) { ?>
						<li>
							<input id="input_<?php echo $image . $tag; ?>" type="text" readonly value="docker pull <?php echo $host_ui . '/' . $image . ':' . $tag; ?>">
							<span class="copy" onclick="copyToClipboard(document.getElementById('input_<?php echo $image . $tag; ?>'));">COPY</span>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		<?php } ?>
	</div>

	<script>
		var inputs = document.querySelectorAll('input[type="text"]');
		for(var i=0; i<inputs.length; i++) {
			inputs[i].addEventListener('click', function(e) {
				copyToClipboard(e.target);
			});
		}
	</script>
</body>
</html>