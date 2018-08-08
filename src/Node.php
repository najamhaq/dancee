<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/7/2018
 * Time: 1:56 PM
 */

namespace Dancee;


class Node
{

    protected $name;
    protected $references;


    public function __construct($name)
    {
        $this->name = $name;
    }


    public function references($uses)
    {
        $this->references[] = $uses;
    }

    /**
     * @param $useLine
     * @return mixed
     * @throws \Exception
     */
    public static function getName($useLine)
    {
        $usedLineParts = explode(":use ", $useLine);
        if (count($usedLineParts) < 2) {
            throw new \Exception("Probably incorrect usage." . $useLine);
        }
        $fullPath = $usedLineParts[0];

        $FullyQualifiedName = self::getFullyQualifiedName($fullPath);

        return $FullyQualifiedName;
    }

    public static function getFullyQualifiedName($fullPath)
    {
        $handle = fopen($fullPath, "r");
        if ( ! $handle) {
            throw new \Exception("Unable to read file");
        }
        $phpCodeLine = fgets($handle);
        $found = false;
        $nameSpace = null;
        $className = null;
        while ( ! $found && $phpCodeLine != null) {
            if ( ! $nameSpace) {
                $nameSpace = self::getNameSpace($phpCodeLine);
            }
            if ( ! $className) {
                $className = self::getClassName($phpCodeLine);
            }
            $found = $className !== null;
            $phpCodeLine = fgets($handle);
        }
        fclose($handle);

        $fullyQualifiedName = $className;
        if ($nameSpace){
            $fullyQualifiedName = $nameSpace . "\\" . $className;
        }
        return $fullyQualifiedName;
    }

    public static function getNameSpace($codeLine)
    {
        $codeLine = str_replace(";", "", $codeLine);
        $codeLine = str_replace([";" , "<?php"], "", $codeLine);
        $codeLine = trim($codeLine);

        // TODO improve this by using preg_split;
        $namespaceLineParts = explode(" ", $codeLine);
        if ($namespaceLineParts[0] == 'namespace') {
            if ($namespaceLineParts[1][0] == "\\") {
                return substr($namespaceLineParts[1], 1);
            }

            return $namespaceLineParts[1];
        }
    }

    public static function getClassName($codeLine)
    {
        $codeLine = trim($codeLine);
        $codeLine = str_replace(["{" , "}"], "", $codeLine);
        $classNameLineParts = explode(" ", $codeLine);
        if ($classNameLineParts[0] !== 'class') {
            return null;
        }

        if ($classNameLineParts[1][0] == "\\") {
            return substr($classNameLineParts[1], 1);
        }

        return $classNameLineParts[1];
    }

    /**
     * @param $useLine
     * @return mixed
     * @throws \Exception
     */
    public static function getReference($useLine)
    {
        $usedLineParts = explode(":use ", $useLine);
        if (count($usedLineParts) < 2) {
            throw new \Exception("Probably incorrect usage." . $useLine);
        }

        return $usedLineParts[1];
    }

    public function dumpScruffy()
    {
        foreach ($this->references as $reference) {
            echo "[" . self::clean($this->name) . "]<-->[" . self::clean($reference) . ']' . PHP_EOL;
        }
    }

    public function clean($name)
    {
        $removePath = str_replace("/var", "", $name);
        $removePath = str_replace("/www", "", $removePath);
        $removePath = str_replace("/bookarb", "", $removePath);
        $removePath = str_replace("/current", "", $removePath);
        $removePath = str_replace("/app", "", $removePath);
        $removePath = str_replace("/Services", "", $removePath);

        $removePath = str_replace(";", "", $removePath);
        $as = strpos($removePath, "as");
        if ($as !== false) {
            $removePath = substr($removePath, 0, $as);
        }
        $removePath = str_replace("/", "", $removePath);


        return str_replace(".php", "", $removePath);
    }

}