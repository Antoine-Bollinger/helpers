<?php 
namespace Abollinger;

use \Symfony\Component\Yaml\Yaml;

/**
 * Class RouteReader
 *
 * This class is responsible for extracting routes from controller classes
 * located within a specified directory.
 */
final class RouteReader extends Abstract\Bootstrap
{
    /**
     * Retrieves routes from YAML files present in the specified directory.
     *
     * @param string $dir The directory containing YAML route files
     * @return array An array of parsed routes from YAML files
     */
    public function getRoutesFromYaml(
        $dir = ""
    ) :array {
        try {
            $routes = [];
            foreach(scandir($dir) as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if (pathinfo($file, PATHINFO_EXTENSION) === 'yaml') {
                    $tmp = Yaml::parseFile(implode("/", [$dir, $file]));
                    if (is_array($tmp)) {
                        foreach($tmp as $v) {
                            $routes[] = $v;
                        }
                    }
                }
            }
            return $routes;
        } catch(\Exception $e) {
            return [];
        }
    }

    /**
     * Retrieves routes from PHP controller files within the specified directory.
     *
     * @param string $directory The directory path containing PHP controller files.
     *
     * @return array An array containing extracted routes with their path, name, and controller information.
     */
    public function getRoutesFromDirectory(
        $directory,
        $namespace
    ) {
        try {
            $routes = [];
            $controllerFiles = glob($directory . "/*.php");
            foreach ($controllerFiles as $file) {
                require_once $file; 
                $className = basename($file, ".php");
                $fullClassName = $namespace . "\\Controller\\" . $className;
                if (class_exists($fullClassName)) {
                    $reflectionClass = new \ReflectionClass($fullClassName);
                    foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                        $docComment = $method->getDocComment();
                        if (strpos($docComment, "@Route") !== false) {
                            $route = $this->parseRouteAnnotation($docComment);
                            if ($route !== null) {
                                $routes[] = [
                                    "path" => $route["path"],
                                    "name" => $route["name"],
                                    "auth" => $route["auth"],
                                    "controller" => $fullClassName,
                                ];
                            }
                        }
                    }
                }
            }
            return $routes;
        } catch(\Exception $e) {
            return [];
        }
    }
    
    /**
     * Parses the @Route annotation from a docblock comment.
     *
     * @param string $docComment The docblock comment containing the @Route annotation.
     *
     * @return array|null An array with the extracted path and name if found, otherwise null.
     */
    private function parseRouteAnnotation(
        $docComment
    ) {
        $pattern = '/@Route\("(.*?)"[^)]*name="(.*?)"(?:[^)]*auth=(true|false))?/';
    
        preg_match($pattern, $docComment, $matches);
    
        if (count($matches) >= 3) {
            $path = $matches[1];
            $name = $matches[2];
            $auth = isset($matches[3]) ? ($matches[3] === 'true') : false;
    
            return [
                'path' => $path,
                'name' => $name,
                'auth' => $auth,
            ];
        }
    
        return null;
    }
    
}