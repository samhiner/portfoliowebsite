<!--
This file updates this repos and other subrepos when they are pushed to on GitHub.

Also, if there is a .unpack config (I made it up), it will replace content according
to the config. For example, it will change login data for the SQL server as the dev
server has no password.

To make this work, this should be in a "html" directory where the site is served from.
Then, any subrepos go into a "projects" directly that is directly under "html".
Then, make a "shellscripts" folder outside of the html directory where you put two
scripts from my code/shell repo.
In the "shellscripts" repo, write "webhooklist.txt" which lists all of your subprojects,
delimited by \n
Finally, put any .unpack configs in the "shellscripts" folder and you are good to go.
-->
<!DOCTYPE html>
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

function configSetup($repo) {
	if (file_exists('../../shellscripts/$repo.unpack')) {
		$line = trim(preg_replace('/\s\s+/', '', $line));
		if ($line == $repo) {
			var_dump(shell_exec('sudo ../../shellscripts/dotunpack-exec.sh /var/www/shellscripts/$repo.unpack'));
		}
	}
}

/*one of my secrets is hard-coded. if anyone ever reads this, 
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

		configSetup($line);
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