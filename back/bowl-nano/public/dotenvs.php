<?php

if (!function_exists('env')) {
	function env(string $key, mixed $default = null): mixed {
		$value = $_ENV[$key] ?? false;
		if ( $value === false )
			return $default;
		switch (strtolower($value)) {
			case 'true':
			case '(true)':
				return true;
			case 'false':
			case '(false)':
				return false;
			case 'empty':
			case '(empty)':
				return '';
			case 'null':
			case '(null)':
				return null;
		}
		if ( preg_match('/\A([\'"])(.*)\1\z/', $value, $matches) )
			return $matches[2];
		return $value;
	}
}

if (!function_exists('defineEnvs')) {
	function defineEnvs ( $envList ) {
		foreach ( $envList as $key => $value ) {
			if ( is_int($key) )
				$key = $value;
			define( $key, $_ENV[ $key ] ?? $value );
		}
	}
}