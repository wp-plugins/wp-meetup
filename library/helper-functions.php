<?php
/**
 * Created: 2014-04-11
 * Last Revised: 2014-04-11
 *
 * CHANGELOG:
 * v0.0.1 - 2014-04-11
 *      - Initial Class Creation
 */

/**
 * dump function for debug
 */
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE) {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left; width: 100% !important; font-size: 12px !important;">' . $label . ' => ' . $output . '</pre>';
        if ($echo == TRUE) {
            echo $output;}else {return $output;}
    }
}
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);exit;
    }
}


/**
 * Given a string, returns whether or not the string is json encoded
 * @param string $string
 * @return bool
 */
if (!function_exists('nm_is_json')) {
    function nm_is_json($string) {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }
}


/**
 * Given a string, returns whether or not this string is xml encoded
 * @param string $string
 * @return bool
 */
if (!function_exists('nm_is_xml')) {
    function nm_is_xml($string) {
        return (is_string($string) && (is_object(simplexml_load_string($string)))) ? true : false;
    }
}

/**
 * Given a string, returns a form of the string with the beginning of every word capitalized.
 * @param string $string
 * @return string
 */
if (!function_exists('nm_strtocap')) {
    function nm_strtocap($string) {

        $string = trim($string);
        $string = strtolower($string);
        $string = ucwords($string);

        return $string;
    }
}

if (!function_exists('nm_get_year')) {
    function nm_get_year() {
        return intval(date('Y'));
    }
}

function nm_reset_permalinks() {
    if (!class_exists('WP_Rewrite')) {
        require_once(ABSPATH . '/wp-includes/rewrite.php');
    }
    $wp_rewrite->flush_rules(TRUE);
}

/**
 * Short circuit function for POST requests. mostly used for
 * querying google, since wp_remote_post does not play nicely with
 * the goog's. This is likely not very comptaible with multiple machines
 * @param string $url
 * @param array $postdata A $key => $val array of POST data
 * @return string A json- or xml- encoded string on success, or NULL on fail
 */
if (!function_exists('nm_remote_post')) {
    function nm_remote_post($url, $postData = array()) {
        $ch = curl_init();
        curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
                CURLOPT_POSTFIELDS => json_encode($postData)
            )
        );
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code == '200' && !empty($response)) {
            //return json_decode($response, true);
            return $response;
        }
        return NULL;
    }
}

if (!function_exists('nm_clean_input')) {
    function nm_clean_input($string) {
        $output = strip_tags($string);
        $output = htmlspecialchars($output);
        $output = mysql_real_escape_string($output);
        return $output;
    }
}


function echo_clear() {
    echo '<div class="clear"></div>';
}

function echo_div_close() {
    echo '</div>';
}

function echo_div($array) {
    $output = '<div';
    foreach ($array as $key=>$value) {
        $output .= ' ' . $key . '="' . $value . '"';
    }
    $output .= '>';
    echo $output;
}

/**
 * Emails Naunced Media Admin for various debug purposes.
 * This function will not be used unless a custom version 
 * of the plugin is personally sent to the user. In which case,
 * the user will be fully informed of the functions use. 
 */
if (!function_exists('nm_email_admin')) {
    function nm_email_admin($subject, $body) {
        $mail = wp_mail('plugins@nuancedmedia.com', $subject, $body);
    }
}