<?php
spl_autoload_register(function ($className) {
	$parts = explode("_", $className);
	$base = array_shift($parts);
	if ($base === "REBuilder") {
		$baseDir = __DIR__ . DIRECTORY_SEPARATOR;
		if (!count($parts)) {
			$file = $baseDir . $base . ".php";
		} else {
			$file = $baseDir . implode(DIRECTORY_SEPARATOR, $parts) . ".php";
		}
		if (file_exists($file)) {
			require_once $file;
		}
	}
});