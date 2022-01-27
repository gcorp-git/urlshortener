<?
define('HOME', realpath(getenv('DOCUMENT_ROOT')));
define('INPUT', file_get_contents('php://input'));
define('SETTINGS', include(HOME . '/settings.php'));

require_once HOME . '/vendor/autoload.php';

require_once HOME . '/core/db.php';
require_once HOME . '/core/web.php';
require_once HOME . '/core/app.php';

app::init();
app::migrate();
app::process();