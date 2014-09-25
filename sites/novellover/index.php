<?php
// https://github.com/bcosca/fatfree
require_once('/var/www/lib/vendor/autoload.php');
$f3 = require(dirname(dirname(dirname(__file__))).'/lib/fatfree/lib/base.php');
$f3->config(__dir__.'/setup.cfg');
if(php_sapi_name() == "cli") {
	// php index.php "/cli/bear/12/123/66"
	$f3->route('GET /cli/@script/*', function($f3,$params) { 
		$className = '\Script\\'.$params['script'];
		new $className($f3,$params[2]);
	});
}
$f3->run();
