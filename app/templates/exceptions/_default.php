test
<?php
	if(AgaviContext::getInstance()->getName() == 'web') {
		header('HTTP/1.0 500 Internal Server Error');
		header('Content-Type: text/plain');
	}
?>
Error.