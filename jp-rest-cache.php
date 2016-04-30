<?php
/**
 * The cache
 *
 * @version 0.1.0
 */
//make sure we have the TLC transients one way or another.
if (  ! function_exists( 'tlc_transient' ) ) {

	$vendor_dir = __DIR__ . '/vendor/autoload.php';
	if ( ! file_exists( $vendor_dir) ) {
		return;
	}

	require_once( $vendor_dir );
}

/**
 * Default Cache Length
 *
 * @since 0.1.0
 */
if ( ! defined( 'JP_REST_CACHE_DEFAULT_CACHE_TIME' ) ) {
	define( 'JP_REST_CACHE_DEFAULT_CACHE_TIME', 360 );
}
if ( ! function_exists( 'jp_rest_cache_get' ) ) :
add_filter( 'rest_pre_dispatch', 'jp_rest_cache_get', 10, 3 );
	/**
	 * Run the API query or get from cache
	 *
	 * @uses 'rest_pre_dispatch' filter

	 * @param null $result
	 *
	 * @param obj| WP_JSON_Server $server
	 * @param obj| WP_REST_Request $request
	 * @since 0.1.0
	 */
	function jp_rest_cache_get( $result, $server, $request ) {
		if ( ! function_exists( 'jp_rest_cache_rebuild') ) {
			return $result;

		}
		

		/**
		 * Cache override.
		 *
		 * @since 0.1.0
		 *
		 * @param bool $no_cache If true, cache is skipped. If false, there will be caching.
		 * @param string $endpoint The endpoint for the current request.
		 * @param string $method The HTTP method being used to make current request.
		 *
		 * @return bool
		 */

		$endpoint = $request->get_route();
		$method = $request->get_method();
		$request_uri = $_SERVER[ 'REQUEST_URI' ];


		$skip_cache = apply_filters( 'jp_rest_cache_skip_cache', false, $endpoint, $method);
			if ( $skip_cache )  {
				return $result;
			}

		if($request->get_param('refresh-cache') === true){
			return $result;
		}


		/**
		 * Set cache time
		 *
		 * @since 0.1.0
		 *
		 * @param int $cache_time Time in seconds to cache for. Defaults to value of JP_REST_CACHE_DEFAULT_CACHE_TIME.
		 * @param string $endpoint The endpoint for the current request.
		 * @param string $method The HTTP method being used to make current request.
		 *
		 * @return bool
		 */

		$cache_time = apply_filters( 'jp_rest_cache_skip_cache', JP_REST_CACHE_DEFAULT_CACHE_TIME, $endpoint, $method );

		$result =  tlc_transient( __FUNCTION__ . $request_uri  )
			->updates_with( 'jp_rest_cache_rebuild', array( $server, $request  ) )
			->expires_in( $cache_time )
			->get();

		return $result;
	}
endif;

if ( ! function_exists( 'jp_rest_cache_rebuild' ) ) :
	/**
	 * Rebuild the cache if needed.
	 *
	 * @since 0.1.0
	 *
	 * @param obj|WP_JSON_Server $server
	 * @param obj| WP_REST_Request $request
	 *
	 * @return mixed
	 */

	function jp_rest_cache_rebuild( $server, $request ) {

		$request->set_param('refresh-cache', true);
		return $server->dispatch($request);

	}
endif;


