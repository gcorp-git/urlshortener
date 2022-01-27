<?
class DB {

	static function connect(array $config): mysqli {
		$link = new mysqli(
			$config['host'],
			$config['user'],
			$config['pass'],
			$config['name'],
		);

		if ( mysqli_connect_errno() ) {
			throw new ErrorException('Database connection error: ' . mysqli_connect_error());
		}

		$result = $link->query( 'SET NAMES utf8 COLLATE utf8_general_ci' );

		if ($result === false) {
			throw new ErrorException('Database error: ' . mysqli_connect_error());
		}

		return $link;
	}

	static function query(mysqli $link, string $sql, string $types='', array $args=[]): mysqli_result|bool {
		$stmt = $link->prepare($sql);

		if (!empty($args)) {
			$stmt->bind_param($types, ...$args);
		}

		if (!$stmt->execute()) {
			throw new ErrorException('Database query error: ' . $stmt->error);
		}

		$result = $stmt->get_result();

		$stmt->close();

		return $result;
	}

	static function assoc(mysqli_result $result): array {
		$list = [];

		while ($row = $result->fetch_assoc()) {
			$list[] = $row;
		}

		return $list;
	}

}