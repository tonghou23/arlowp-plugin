<?php

namespace Arlo;

use Arlo\Entities\Categories as CategoriesEntity;

class Utilities {

	public static function array_ikey_exists($key,$arr) { 
		if(preg_match("/".$key."/i", join(",", array_keys($arr))))                
			return true; 
		else 
			return false; 
	} 

	public static function get_now_utc() {
		$logger = new Logger();

		do {
			//this returns, check php doc 
			$now = \DateTime::createFromFormat('U', time());
			if (!is_object($now)) {
				$logger->log("Error DateTime::createFromFormat: " . implode(", ", DateTime::getLastErrors()));
			}
		} while (!is_object($now));
		
		return $now;    
    }

    public static function clean_string_url_parameter($parameter_name) {
        return !empty($_GET[$parameter_name]) ? wp_unslash($_GET[$parameter_name]) : wp_unslash(urldecode(get_query_var($parameter_name)));
    }

    public static function get_att_string($name, $atts) {
        $string_parameter = self::clean_string_url_parameter('arlo-'.$name);
        return !empty($string_parameter) || $string_parameter == "0" ? $string_parameter : ( is_array($atts) && array_key_exists($name, $atts) ? $atts[$name] : '' );
    }

    public static function get_att_int($name, $atts) {
        $int_parameter = self::clean_int_url_parameter('arlo-'.$name);
        return !empty($int_parameter) || $int_parameter == "0" ? $int_parameter : ( is_array($atts) && array_key_exists($name, $atts) ? intval($atts[$name]) : '' );
    }

    public static function clean_int_url_parameter($parameter_name) {
        if (isset($_GET[$parameter_name])) {
            return intval($_GET[$parameter_name]);
        } else {
            $value = get_query_var($parameter_name);
            if (is_numeric($value)) {
                return intval($value);
            }
        }

        return null;
    }

    public function process_att($new_atts_array, $callback, $att_name = '', $atts = []) {
        $value = call_user_func($callback, $att_name, $atts);
        
		if (!is_null($value) && (!empty($value) || is_numeric($value))) {
			$new_atts_array[$att_name] = $value;
        }
        
		return $new_atts_array;
	}
    
    public static function remove_url_protocol($url) {
        $url = parse_url($url);
        unset($url['scheme']);
        return '//'.implode($url);
    }

	public static function GUIDv4 ($trim = true, $remove_hyphens = false) {
        

        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                $guid = trim(com_create_guid(), '{}');
            else
                $guid = com_create_guid();
        }

        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            $guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        if ($remove_hyphens) {
            return str_replace('-', '', $guid); 
        }

        return $guid;
    }
    
    public static function get_random_int() {
        $guid = explode("-", self::GUIDv4());
        
        return substr((string)hexdec($guid[0]), 0, 8);
    }

    public static function glob_recursive($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::glob_recursive($dir.'/'.basename($pattern), $flags));
        }
        
        return $files;
    }

    public static function get_absolute_url($rel) {
        if (parse_url($rel, PHP_URL_SCHEME) != '' || empty($rel)) {
            return ($rel);
        }

        return (get_home_url() . $rel);
    }

    public static function convert_string_array_to_int_array($string_array) {
        if (!empty($string_array)) {
            return array_filter(
                array_map(function($int) {
                    return intval($int);
                }, explode(',', $string_array)), 
                function($int) {
                    return $int > 0;
                });
        }

        return null;
    }
}