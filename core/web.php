<?
class Web {

	static function render(string $chunk, array $args=[]): string {
		$file = self::_getChunkFile($chunk);

		if (isset($args['file'])) unset($args['file']);
		if (isset($args['args'])) unset($args['args']);

		if (!empty($args)) extract( $args );

		ob_start();

		include $file;

		$output = ob_get_contents();

		if (ob_get_length()) ob_end_clean();

		return $output;
	}

	static function redirect(string $uri): never {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: {$uri}", true, 301);

		exit();
	}

	static function error(int $code=500, string $body=''): never {
		if (ob_get_length()) ob_end_clean();

		http_response_code( $code );

		if (!empty($body)) echo $body;

		exit();
	}

	private static function _getChunkFile(string $chunk): string {
		$dir = HOME . "/view";
		$file = realpath("{$dir}/{$chunk}.php");

		if (strpos($file, $dir) !== 0) {
			throw new ErrorException('Incorrect chunk name');
		}

		if (!file_exists($file)) {
			throw new ErrorException('Page not found');
		}

		return $file;
	}

}
