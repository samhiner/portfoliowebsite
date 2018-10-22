<html>
<head>
	<style>
		body {
			color: white;
		}
		body pre {
			color: black;
		}
	</style>
</head>
<body>
	<pre>
				          .--.
				        .'    ',
				      .'        ',
				     /\/\/\/\/\/\/\
				    /\/\/\/\/\/\/\/\
				   |                |
				   |/\/\/\/\/\/\/\/\|
				   |\/\/\/\/\/\/\/\/|
				   |                |
				    \/\/\/\/\/\/\/\/
				     \/\/\/\/\/\/\/
				      `.        .'
				        `..__..'
	</pre>
</body>
</html>
<?php
function isHash($secret) {
	global $algo;
	global $rawPost;
	global $hash;
	return $hash === hash_hmac($algo, $rawPost, $secret);
}

/*one of my secrets are hard-coded. if anyone ever reads this, 
try to imitate a webhook and update my site that'd be pretty cool
just don't go too crazy*/
//following script modified from https://gist.github.com/milo/daed6e958ea534e4eba3.

set_error_handler(function($severity, $message, $file, $line) {
	throw new \ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function($e) {
	header('HTTP/1.1 500 Internal Server Error');
	echo "Error on line {$e->getLine()}: " . htmlSpecialChars($e->getMessage());
	die();
});

$rawPost = NULL;

if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
	throw new \Exception("You are not a webhook.");
} elseif (!extension_loaded('hash')) {
	throw new \Exception("Missing 'hash' extension to check the secret code validity.");
}

list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');

if (!in_array($algo, hash_algos(), TRUE)) {
	throw new \Exception("Hash algorithm '$algo' is not supported.");
}

$rawPost = file_get_contents('php://input');

$done = false;
foreach (file('../../shellscripts/webhooklist.txt') as $line) {
	$line = trim(preg_replace('/\s\s+/', '', $line));
	if (isHash($line)) {
		var_dump(shell_exec('sudo ../../shellscripts/serverupdate.sh ' . $line . ' 2>&1'));
		$done = true;
	}
}

if (isHash('portfoliowebsite')) {
	var_dump(shell_exec('sudo ../../shellscripts/serverupdate.sh portfoliowebsite 2>&1'));
	$done = true;
}

if (!$done) {
	throw new \Exception('Hook secret does not match.');	
}

?>