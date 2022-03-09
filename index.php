<?
define('HOME', realpath(getenv('DOCUMENT_ROOT')));
define('INPUT', file_get_contents('php://input'));
define('SETTINGS', include(HOME . '/settings.php'));

error_reporting(0);

require_once HOME . '/vendor/autoload.php';

require_once HOME . '/core/error.php';
require_once HOME . '/core/db.php';
require_once HOME . '/core/web.php';
require_once HOME . '/core/app.php';

try {
	app::init();
	app::migrate();
	app::process();
} catch (AppError $e) {
	web::error(500, json_encode([
		'error' => $e->getMessage(),
	]));
} catch (Error|Exception $e) {
	web::error(500);
}
