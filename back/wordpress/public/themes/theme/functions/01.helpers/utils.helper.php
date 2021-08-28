<?php

class SolidUtils
{
    /**
     * Traverse an array from a path as a string.
     * Ex : $pPath = 'my.nested.array' will traverse your array and get the value of 'array' inside 'nested' inside 'my' inside $pObject
     * @param $path : The path
     * @param $object : The associative array to traverse
     * @return mixed|null : value if found, else null
     */
	static function traverse ( $path, $object )
	{
		// Check if our object is null
		if (is_null($object)) return null;

		// Split the first part of the path
		$explodedPath = explode('.', $path, 2);

		// One element in path selector
		// Check if this element exists and return it if found
		if ( !isset($explodedPath[1]) )
			return isset($object[$explodedPath[0]]) ? $object[$explodedPath[0]] : null;

		// Nesting detected in path
		// Check if first part of the path is in object
		// Target child from first part of path and traverse recursively
		else if ( isset($explodedPath[0]) && isset($object[$explodedPath[0]]) )
			return self::traverse($explodedPath[1], $object[$explodedPath[0]]);

		// Not found
		else return null;
	}

	/**
	 * Ultra simple template engine
	 * Delimiters are double mustaches like this : {{myVar}}
	 * Compatible with nested variables !
	 * Will keep the placeholder if the property is not in $values
	 * @param $template : Template string to process.
	 * @param $values : Parameters bag including variables to replace.
	 * @return mixed : Templatised string
	 */
	static function quickMustache ($template, $values)
	{
		return preg_replace_callback(
			'/{{([a-zA-Z0-9\.?]+)}}/',
			function ($matches) use ($values)
			{
				// Traverse the parameters bag with this path
				$traversedValue = self::traverse($matches[1], $values);

				// Return the value if found, else keep the placeholder
				return is_null($traversedValue) ? $matches[0] : $traversedValue;
			},
			$template
		);
	}

	/**
	 * Default options helper.
	 * Will set $defaults values into $options and return result.
	 * Will unset values to null to clean $options array.
	 * @param array $options List of options from function argument (associative array)
	 * @param array $defaults List of defaults (associative array)
	 * @return array Cleaned options with defaults.
	 */
	static function defaultOptions ( $options, $defaults )
	{
		$options = array_merge($defaults, $options);

		// Remove null values
		foreach ( $options as $key => $value)
			if ( is_null($value) )
				unset( $options[$key] );

		return $options;
	}
}