<?php
//ik your not supposed to hardcode your github secret, much less put it online
//but it just updates the server, so go ahead and update it hackerman.
$hookSecret = 'pepperoni pepperoni give me the pizzaroni';

//following script from https://gist.github.com/milo/daed6e958ea534e4eba3.

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
	throw new \Exception("HTTP header 'X-Hub-Signature' is missing.");
} elseif (!extension_loaded('hash')) {
	throw new \Exception("Missing 'hash' extension to check the secret code validity.");
}
list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
if (!in_array($algo, hash_algos(), TRUE)) {
	throw new \Exception("Hash algorithm '$algo' is not supported.");
}
$rawPost = file_get_contents('php://input');
if ($hash !== hash_hmac($algo, $rawPost, $hookSecret)) {
	throw new \Exception('Hook secret does not match.');
} else {
	var_dump(shell_exec('sudo ../../shellscripts/serverupdate.sh 2>&1'));
	echo '<strong>nice job hackerman</strong>';
}

?>
<html>
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
