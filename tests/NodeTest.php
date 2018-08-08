<?php

use PHPUnit\Framework\TestCase;
use Dancee\Node;

class NodeTest extends TestCase
{

    public function dataProviderGetNameSpace()
    {
        return [

            ["namespace Dancee;", "Dancee"],
            ["namespace Dancee;;", "Dancee"],
            [" namespace Dancee; ", "Dancee"],
            [" namespace Dancee ;", "Dancee"],
            ["namespace \Dancee;", "Dancee"],
            ["namespace App\Dancee;", "App\Dancee"],
            ["<?php namespace App\Services\PriceFetcher;", "App\Services\PriceFetcher"],
            //            ["namespace  Dancee;", "Dancee"], // we probably need preg_split for this one
            ["use Dancee;", null],
            ["class Dancee implements hasInterface", null],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderGetNameSpace
     * @param $codeLine
     * @param $nameSpace
     */
    public function getNameSpace($codeLine, $nameSpace)
    {

        $this->assertEquals($nameSpace, Node::getNameSpace($codeLine));
    }


    public function dataProviderGetClassName()
    {
        return [
            ["class Dancee{}", "Dancee"],
            ["class Dancee{", "Dancee"],
            ["class Dancee extends Base ", "Dancee"],
            ["class Dancee implements hasInterface", "Dancee"],
            ["class Dancee implements hasInterface", "Dancee"],
            ["namespace App\Dancee;", null],
            ["use Dancee;", null],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderGetClassName
     * @param $codeLine
     * @param $className
     */
    public function getClassName($codeLine, $className)
    {

        $this->assertEquals($className, Node::getClassName($codeLine));
    }

}