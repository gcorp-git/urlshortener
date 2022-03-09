<?
use Brick\Math\BigInteger;
use Brick\Math\RoundingMode;

class App {
	private static string $tableName = 'urls';
	private static string $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	private static mysqli $link;
	private static bool $isInited = false;

	static function init(): void {
		if (self::$isInited) return;

		self::$isInited = true;

		self::$link = db::connect(SETTINGS['db']);
	}

	static function migrate(): void {
		$table = self::$tableName;

		$sql = <<<SQL
		CREATE TABLE IF NOT EXISTS `{$table}` (
			`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`short` VARCHAR(11) NOT NULL,
			`original` VARCHAR(2083) NOT NULL,
			PRIMARY KEY (`id`)
		);
		SQL;

		db::query(self::$link, $sql);
	}

	static function process(): void {
		$uri = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');

		if (!empty($uri)) {
			web::redirect(self::restore($uri));
		}

		$content = '';

		switch (true) {
			case !empty($_POST['url']): {
				$short = self::shorten($_POST['url']);
				$content = web::render('main', [
					'url' => "https://{$_SERVER['SERVER_NAME']}/{$short}",
				]);
			} break;
			case !empty(INPUT): {
				$args = json_decode(INPUT, true);
				$short = self::shorten($args['url']);
				$content = $short;
			} break;
			default: {
				$content = web::render('main');
			} break;
		}

		echo $content;
	}

	static function restore(string $url): string {
		$data = self::_getBy('short', $url);

		if (empty($data[0]['original'])) web::error(404);

		return $data[0]['original'];
	}

	static function shorten(string $url): string {
		if (filter_var($url, FILTER_VALIDATE_URL) === false) {
			throw new AppError('Incorrect URL format');
		}

		$data = self::_getBy('original', $url);

		if (!empty($data[0]['short'])) return $data[0]['short'];

		return self::_createAndGetShort($url);
	}

	private static function _createAndGetShort(string $url): string {
		$table = self::$tableName;

		$sql = <<<SQL
		INSERT INTO `{$table}` (`short`, `original`) VALUES ('', ?)
		SQL;

		db::query(self::$link, $sql, 's', [$url]);

		$data = self::_getBy('original', $url);
		$short = self::_convertIdToShort($data[0]['id']);

		$sql = <<<SQL
		UPDATE `{$table}` SET `short`=? WHERE BINARY `id`=?
		SQL;

		db::query(self::$link, $sql, 'si', [$short, $data[0]['id']]);

		return $short;
	}

	private static function _getBy(string $field, string $url): array {
		$table = self::$tableName;

		$sql = <<<SQL
		SELECT * FROM `{$table}` WHERE BINARY `{$field}`=? LIMIT 1
		SQL;

		$result = db::query(self::$link, $sql, 's', [$url]);

		return db::assoc($result);
	}

	private static function _convertIdToShort(string $id): string {
		$id = BigInteger::of($id);
		$zero = BigInteger::zero();
		$alphabetLength = strlen(self::$alphabet);
		$chars = [];

		while ($id > $zero) {
			$mod = $id->remainder($alphabetLength);
			$id = $id->dividedBy($alphabetLength, RoundingMode::DOWN);
			$chars[] = self::$alphabet[$mod->toInt()];
		}

		array_reverse($chars);

		$short = implode('', $chars);

		if (strlen($short) > 11) {
			throw new AppError('Shortened URL length overflow');
		}

		return $short;
	}

}
