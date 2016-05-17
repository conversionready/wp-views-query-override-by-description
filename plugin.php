<?php
/*
Plugin Name: Toolset Views Query Override By Description
Plugin URI: https://wp-types.com/forums/topic/non-public-post-type-is-not-listed-for-views-selection/
Description: Views gains ability to modify final query arguments with description field JSON spec. Example `{ "query": { "orderby": "include" } }`
Version: 2016.05.17
Author: Leho Kraav
Author URI: https://conversionready.com
*/

if ( is_admin() ) {
    return;
}

final class WPV_Query_Override_Description {

    public static function plugins_loaded() {

        if ( ! defined( "WPV_VERSION" ) ) {
            return;
        }

        # plugins/wp-views/embedded/inc/wpv-filter-users-embedded.php wpv_users_query_user_filters()
        add_filter( "wpv_filter_query", [ __CLASS__, "wpv_filter_query" ], 61, 3 );
        add_filter( "wpv_filter_user_query", [ __CLASS__, "wpv_filter_query" ], 61, 3 );

    }

    public static function wpv_filter_query( $query, $settings, $view_id ) {

        $settings["view_description"] = json_decode( get_post_meta( $view_id, "_wpv_description", true ), true );

        if ( is_array( $settings["view_description"] ) && isset( $settings["view_description"]["query"] ) ) {
            $query = array_merge( $query, $settings["view_description"]["query"] );
        }

        return $query;

    }

}

add_action( "plugins_loaded", [ "WPV_Query_Override_Description", "plugins_loaded" ] );
