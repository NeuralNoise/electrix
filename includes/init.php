<?php

include SITE_PATH . '/application/BaseController.class.php';

include SITE_PATH . '/application/BaseService.class.php';

include SITE_PATH . '/application/Log.class.php';

include SITE_PATH . '/application/Registry.class.php';

include SITE_PATH . '/application/Router.class.php';

include SITE_PATH . '/application/Template.class.php';

function __autoload($class_name)
{
	$filename = strtolower($class_name) . '.class.php';
	$file = SITE_PATH . '/model/' . $filename;

	if (file_exists($file) == false)
	{
		return false;
	}
  include ($file);
}

?>
