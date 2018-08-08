<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/7/2018
 * Time: 1:43 PM
 */

namespace Dancee;

class Dancee
{

    protected $nodes;

    public function start()
    {
        $this->nodes = [];
        while ($line = fgets(STDIN)) {
            try {
                $line = self::removeNewLines($line);
                $nodeName = Node::getName($line);
                $node = self::getNode($nodeName);
                $nodeReference = Node::getReference($line);
                $node->references($nodeReference);
            } catch (\Exception $exc) {

            }
        }

        foreach ($this->nodes as $node) {
            $node->dumpScruffy();
        }
    }

    protected function getNode($nodeName)
    {
        if (isset($this->nodes[$nodeName])) {
            return $this->nodes[$nodeName];
        }
        $node = new Node($nodeName);
        $this->nodes[$nodeName] = $node;

        return $node;
    }

    protected static function removeNewLines($line)
    {
        return str_replace(["\r\n", "\n", "\r"], '', $line);
    }

}