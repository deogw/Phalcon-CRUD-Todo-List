<?php

use Phalcon\Di;

if ( ! function_exists( 'app_path' ) ) {
	/**
	 * Get the application path.
	 *
	 * @param  string $path
	 *
	 * @return string
	 */
	function app_path( $path = '' ) {
		return dirname( __DIR__ ) . '/app' . ( $path ? "/{$path}" : '' );
	}
}

if ( ! function_exists( 'storage_path' ) ) {
	/**
	 * Get the storage  path.
	 *
	 * @param  string $path
	 *
	 * @return string
	 */
	function storage_path( $path = '' ) {
		return dirname( __DIR__ ) . '/storage' . ( $path ? "/{$path}" : '' );
	}
}

if ( ! function_exists( 'cache_path' ) ) {
	/**
	 * Get the cache path.
	 *
	 * @param  string $path
	 *
	 * @return string
	 */
	function cache_path( $path = '' ) {
		return storage_path( 'cache' ) . ( $path ? "/{$path}" : '' );
	}
}


if ( ! function_exists( 'public_path' ) ) {
	/**
	 * Get the storage  path.
	 *
	 * @param  string $path
	 *
	 * @return string
	 */
	function public_path( $path = '' ) {
		return dirname( __DIR__ ) . '/public' . ( $path ? "/{$path}" : '' );
	}
}

if ( ! function_exists( 'config_path' ) ) {
	/**
	 * Get the configuration path.
	 *
	 * @param  string $path
	 *
	 * @return string
	 */
	function config_path( $path = '' ) {
		return dirname( __DIR__ ) . '/config' . ( $path ? "/{$path}" : '' );
	}
}

if ( ! function_exists( 'value' ) ) {
	/**
	 * Return the default value of the given value.
	 *
	 * @param  mixed $value
	 *
	 * @return mixed
	 */
	function value( $value ) {
		return $value instanceof Closure ? $value() : $value;
	}
}

if ( ! function_exists( 'env' ) ) {
	/**
	 * Gets the value of an environment variable.
	 *
	 * @param  string $key
	 * @param  mixed $default
	 *
	 * @return mixed
	 */
	function env( $key, $default = null ) {
		$value = getenv( $key );
		if ( $value === false ) {
			return value( $default );
		}
		switch ( strtolower( $value ) ) {
			case 'true':
				return true;
			case 'false':
				return false;
			case 'empty':
				return '';
			case 'null':
				return null;
		}

		return $value;
	}
}

if ( ! function_exists( 'container' ) ) {
	/**
	 * Calls the default Dependency Injection container.
	 *
	 * @param  mixed
	 *
	 * @return mixed|\Phalcon\DiInterface
	 */
	function container() {
		$default = Di::getDefault();
		$args    = func_get_args();

		if ( empty( $args ) ) {
			return $default;
		}

		if ( ! $default ) {
			trigger_error( 'Unable to resolve Dependency Injection container.', E_USER_ERROR );
		}

		return call_user_func_array( [ $default, 'get' ], $args );
	}
}

if ( ! function_exists( 'environment' ) ) {
	/**
	 * Get or check the current application environment.
	 *
	 * @param  mixed
	 *
	 * @return string|bool
	 */
	function environment() {
		if ( func_num_args() > 0 ) {
			return call_user_func_array( [ container(), 'getEnvironment' ], func_get_args() );
		}

		return container()->getEnvironment();
	}
}
