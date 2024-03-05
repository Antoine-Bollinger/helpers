<?php
namespace Abollinger;


use \Symfony\Component\Yaml\Yaml;

/**
 * Class Helpers
 * 
 * Some functions that can be used in the app as Helpers
 * Feel free to create your own functions !
 */
final class Helpers
{
    /**
     * Function that clean an array of parameters using the htmlspecialchars functions, to avoid the risk of XSS attacks.
     * 
     * @param $arr      The array that is to be cleaned.
     * @return $result  The cleaned array. Returns false if @param is not an array.
     */
    public static function cleanArray(
		$arr
	) :array {
		if (!is_array($arr)) return false;
		$result = [];
		foreach ($arr as $k => $v) {
			if (is_array($v)) {
				$result[$k] = self::cleanArray($v);
			} else {
				$result[$k] = trim(htmlspecialchars($v, ENT_NOQUOTES, "UTF-8"));
			}
		}
		return $result;
	}

	/**
	 * Render PHP array into a HTML ul list
	 * 
	 * @param array $arr		The array to render as a list
	 * @param array $classes 	A array of classes to apply to the ul/li. Expecting ["ul" => "classForTheUlTag", "li" => "classForTheLiTag"]
	 * @return string 			Default return is an empty string, else it's a HTML ul list of the array
	 */
    public static function printArray(
		$arr,
		$classes = []
	) :string {
		if (!is_array($arr)) return "";
		static $closingTag = array();
		$str = "<ul".($classes["ul"] ?? "").">\r\n";
		$closingTag[] = "</ul>\r\n";
		foreach ($arr as $k => $v) {
			if(is_array($v)){
				$str .= "<li".($classes["li"] ?? "").">$k => <em>array</em>\r\n";
				$str .= self::printArray($v);
			} else {
				$display = is_bool($v) ? ($v ? 'true' : 'false') : htmlentities(is_string($v) || is_float($v) || is_int($v) ? $v : '');
				$str .= "<li".($classes["li"] ?? "").">$k => <strong>".$display."</strong>";
			}
			$closingTag[] = "</li>\r\n";
			$str .= array_pop($closingTag);
		}
		$str .= array_pop($closingTag);
		return $str;
	}

	/**
	 * Function that create an array of default value that will be replace by the client value when exist
	 * 
	 * @param $default 	an array of default values that are necessary but not mandatory on client side
	 * @return $params	an array of paramters with the values sended by the client and, when not provided, the default value set in the $default by the developper
	 */
	public static function defaultParams(
		$default = [], 
		$params = []
	) :array {

		foreach ($default as $k => $v) {
			if (is_array($v) && count($v) !== 0 && self::isAssociativeArray($v)) {
				$default[$k] = self::defaultParams($v, $params[$k]);
			} else {
				$default[$k] = $params[$k] ?? $v;
			}
		}
		return $default;
	}

	/**
	 * Yaml files reader returning the content as a array. Base on Symfony/Yaml package
	 * 
	 * @param string $filePath	The path to the YAML file
	 * @return array 			Return a PHP array of the YAML file content
	 */
	public static function getYaml(
		$filePath = ""
	) :array {
		try {
			return Yaml::parseFile($filePath);
		} catch (\Exception $e) {
			return ["code" => $e->getCode(), "message" => $e->getMessage()];
		}
	}

	/**
	 * Scan a directory and return an array of all paths. Escape the .class.php files.
	 * 
	 * @param string $dir 		Directory to scan
	 * @param string $rootDir	Root of the directory. Initially $dir = $rootDir, but in the loop $dir will change
	 * @return array $allData	Array of all detailles path/files. Not nested.
	 */
	public static function getScan(
		$dir, 
		$rootDir, 
		$allData = array()
	) : array {
		$invisibleFileNames = array(".", "..");
		$dirContent = scandir($dir);
		foreach ($dirContent as $key => $content) {
			$path = $dir.'/'.$content;
			if (!in_array($content, $invisibleFileNames) && !strpos($content, ".class.")) {
				if(is_file($path) && is_readable($path)) {
					$allData[] = str_replace($rootDir, "", $path);
				}elseif(is_dir($path) && is_readable($path)) {
					$allData = self::getScan($path, $rootDir, $allData);
				}
			}
		}
		return $allData;
	}

    /**
     * Function that evaluate if an array is Associative (ex.: ["key1" => "value1", "key2" => "value2"]) or not (["value1", "value2"]).
     * 
     * @param array $arr    An array to be evaluated.
     * @return bool         True if the array is associative, false otherwise.
     */
    public static function isAssociativeArray(
        $arr = []
    ) :bool {
		if (!is_array($arr)) return false;
        return count(array_filter(array_keys($arr), 'is_string')) > 0;
    }

    /**
     * Function that determine the max lenght among elements of an array
     * 
     * @param array $arr    An array to be evaluated
     * @return int          The max lenght   
     */
    public static function largestElementInArray(
        $arr = []
    ) :int {
		if (!is_array($arr)) return 0;
        $max = 0;
        foreach ($arr as $value) {
            if (is_array($value)) continue;
            $max = (strlen($value) > $max) ? strlen($value) : $max;
        }
        return $max;
    }

	/**
	 * Defines constants from an associative array if they are not already defined.
	 *
	 * @param array $arr An associative array containing constant names as keys and their respective values.
	 * @return bool Returns true if constants are defined or if the input is not an array. Returns false otherwise.
	 */
	public static function defineConstants(
		$arr = []
	) {
		if (!is_array($arr)) return false;
		foreach ($arr as $key => $value) {
			if (!defined($key)) {
				define($key, $value);
			}
		}
		return true;
	}
}