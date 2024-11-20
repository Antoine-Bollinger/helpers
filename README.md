# Abollinger/Helpers

Provides some usefull PHP functions to help developpers in their daily work.

[![Total Downloads](https://img.shields.io/packagist/dt/abollinger/helpers)](https://packagist.org/packages/abollinger/helpers)
[![Latest Stable Version](https://img.shields.io/packagist/v/abollinger/helpers)](https://packagist.org/packages/abollinger/helpers)
[![License](https://img.shields.io/packagist/l/abollinger/helpers)](https://packagist.org/packages/abollinger/helpers)

## Getting started

### Installation

You can easily add this library in your project using [Composer](https://getcomposer.org/):

```bash
composer require abollinger/helpers
```

Or add it manually in your ```composer.json``` file.

## Usage

Here are all the function you can, showd in the Classes tree they belong to:

<details open="open">
    <summary>Functions in the Classes tree</summary>
    <ul>
        <li>Abollinger/
            <ul>
                <li>Helpers::
                    <ul>
                        <li><a href="#cleanarray">cleanArray($array)</a></li>
                        <li><a href="#printarray">printArray($array, $classes)</a></li>
                        <li><a href="#defaultparams">defaultParams($default, $array)</a></li>
                        <li><a href="#getyaml">getYaml($filePath)</a></li>
                        <li><a href="#getscan">getScan($dir, $rootDir, $allData)</a></li>
                        <li><a href="#isassociativearray">isAssociativeArray($array)</a></li>
                        <li><a href="#largestelementinarray">largestElementInArray($array)</a></li>
                        <li><a href="#defineconstants">defineConstants($array)</a></li>
                        <li><a href="#getAppSubdirectory">getAppSubdirectory($appRoot, $documentRoot)</a></li>
                        <li><a href="#getAppCompleteHost">getAppCompleteHost()</a></li>
                    </ul>
                </li>
                <li>Parsedown::
                    <ul>
                        <li><a href="#erusev/parsedown">extends the erusev/parsedown library</a></li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>    
</details>

### cleanArray

Function that clean an array of parameters using the htmlspecialchars functions, to avoid the risk of XSS attacks.
* ```@param array $arr```: The array that is to be cleaned.
* ```@return array```: The cleaned array. Returns false if @param is not an array.

Example : 
```
$myArray = Helpers::cleanArray([
    "attack" => "<script>alert('Hey, I'm trying to hack you!')</script>"
]);

echo var_dump($myArray);

// output:

array(1) {
    ["attack"]=>
    string(66) "&lt;script&gt;alert('Hey, I'm trying to hack you!')&lt;/script&gt;"
}
```

### printArray

Render PHP array into a HTML ul list
* ```@param array $arr```: The array to render as a list.
* ```@param array $classes```: A array of classes to apply to the ul/li. Expecting ["ul" => "classForTheUlTag", "li" => "classForTheLiTag"].
* ```@return string```: Default return is an empty string, else it's a HTML ul list of the array.

Example : 
```
$myArray = Helpers::printArray([
    "firstName" => "Antoine",
    "lastName" => "Bollinger"
]);

echo $myArray;

// output:

<ul>
    <li>firstName => <strong>Antoine</strong></li>
    <li>lastName => <strong>Bollinger</strong></li>
</ul>
```

### defaultParams

Function that create an array of default value that will be replace by the client value when exist
* 
* ```@param array $default```: an array of default values that are necessary but not mandatory on client side.
* ```@param array $params```: an array of parameters mandatories that will be filled with the default values.
* ```@return array```: the array $params filled with default values.

Example:
```
$params = [
    "firstName" => "Victor"
];

$test = Abollinger\Helpers::defaultParams([
    "firstName" => "Antoine",
    "lastName" => "Bollinger"
], $params);

echo var_dump($test);

// output:
array(2) {
    ["firstName"] => string(6) "Victor"
    ["lastName"] => string(9) "Bollinger"
}
```

### getYaml

Yaml files reader returning the content as a array. Base on Symfony/Yaml package.
* ```@param string $filePath```: The path to the YAML file.
* ```@return array```: Return a PHP array of the YAML file content.

### getScan

Scan a directory and return an array of all paths. Escape the .class.php files.
* ```@param string $dir```:	Directory to scan.
* ```@param string $rootDir```: Root of the directory. Initially $dir = $rootDir, but in the loop $dir will change.
* ```@return array```: Array of all detailles path/files. Not nested.

### isAssociativeArray

Function that evaluate if an array is Associative (ex.: ["key1" => "value1", "key2" => "value2"]) or not (["value1", "value2"]).
* ```@param array $arr```: An array to be evaluated.
* ```@return bool```: True if the array is associative, false otherwise.

### largestElementInArray

Function that determine the max lenght among elements of an array
* ```@param array $arr```: An array to be evaluated.
* ```@return int```: The max lenght.

### defineConstants

Defines constants from an associative array if they are not already defined.
* ```@param array $arr```: An associative array containing constant names as keys and their respective values.
* ```@return bool```: Returns true if constants are defined or if the input is not an array. Returns false otherwise.

### getAppSubdirectory

Function that returns the subdirectory path of an application relative to the document root.
* ```@param string $appRoot```: The path to the application's root directory.
* ```@param string $documentRoot```: The path to the document root directory.
* ```@return string```: The subdirectory path or an empty string if the document root is longer than or equal to the application root.

### getAppCompleteHost

This function returns the complete host URL of the current application.
* ```@return string```: The complete host URL of the current application.

### Parsedown

Extends the [erusev/parsedown](https://github.com/erusev/parsedown) library by adding # in the title, so the anchor links can work.

## Classes tree

```bash
Abollinger/
├── Helpers::
│   ├── cleanArray($array)
│   ├── printArray($array, $classes)
│   ├── defaultParams($default, $array)
│   ├── getYaml($filePath)
│   ├── getScan($dir, $rootDir, $allData)
│   ├── isAssociativeArray($array)
│   ├── largestElementInArray($array)
│   ├── defineConstants($array)
│   ├── getAppSubdirectory($appRoot, $documentRoot)
│   └── getAppCompleteHost()
└── Parsedown::
    └── extends the erusev/parsedown library
```

## Licence 

This library is licensed under the MIT License. For full license details, see the `LICENCE` file distributed with this source code.

## Author

Antoine Bollinger
Email: abollinger@partez.net

For contributions, issues, or feedback, feel free to contact the author or open a GitHub issue.