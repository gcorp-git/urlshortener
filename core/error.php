<?
class AppError extends Exception {

	function __construct($message, $code=0, Throwable $previous=null) {
		parent::__construct($message, $code, $previous);
	}

}
