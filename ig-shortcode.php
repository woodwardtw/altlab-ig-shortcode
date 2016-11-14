<?php
/**
 * Plugin Name: ALT LAB IG shortcode
 * Plugin URI: https://github.com/woodwardtw/
 * Description: Shortcode to throw IG pics by tag or user //[igpics user="" or tag="" pics="5"]

 * Version: 1.7
 * Author: Tom Woodward
 * Author URI: http://bionicteaching.com
 * License: GPL2
 */
 
 /*   2016 Tom  (email : bionicteaching@gmail.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 
//[csvToList file="YourFolderID" sol="" type=""]
add_action('init','remove_wpautop',201);

function remove_wpautop() {
    remove_filter ('acf_the_content', 'wpautop');
    remove_filter( 'the_content', 'wpautop' );

}  


function igpics_enqueue_scripts() {
    wp_enqueue_style( 'igStyles', plugins_url( '/css/igstyles.css', __FILE__ )  ); 

}
add_action( 'wp_enqueue_scripts', 'igpics_enqueue_scripts' );


ini_set("allow_url_fopen", 1);
 
function ig_shortcode( $atts, $content = null ) {
    extract(shortcode_atts( array(
         'user' => '', //user account
         'tag' => '', //tag         
         'pics'=>'6',
    ), $atts));         

    $url = "https://rampages.us/extras/ig/scrape.php?";                
    //$url = "http://192.168.33.10/ig/user.php?"; //for local testing

    if($user) {
        $account = 'user='.$user;
        $url = $url . $account;
    }
    else{
        $hash = 'tag='. $tag;
        $url = $url . $hash;
    }

    $json = file_get_contents($url);
    $obj = json_decode($json);
    $elementCount  = count($obj);
    $size ="";

    if($elementCount<$pics){
        $size = $elementCount;        
    }
    else {
        $size = $pics;
    }

    $i = 0;
    $print="";
    $html ="";
    while ($i < $size){
        $html .= '<a href="'.$obj[$i]->{'link'}.'"><div class="ig_thumb" style="background-image:url('.$obj[$i]->{'imageStandardResolutionUrl'}.');"></div></a>';
        $i++;
    }

    return  $html;
}

add_shortcode( 'igpics', 'ig_shortcode' );
