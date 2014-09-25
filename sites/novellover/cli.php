<?php
$f3 = require('/var/www/lib/fatfree/lib/base.php');
$f3->route('GET /command', function($f3) { 
	echo "Here our command starts".PHP_EOL;
	// lots of operations here
	echo "Done! Be proud!"; 
});
$f3->run();
