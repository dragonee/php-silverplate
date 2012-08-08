<?php

class Block {
    private static $blocks;

    private $contents;

    public static function get($name) {
        if(isset(self::$blocks[$name])) {
            return self::$blocks[$name];
        }

        self::$blocks[$name] = new Block;

        return self::$blocks[$name];
    }

    public function open() {
        ob_start();
    }

    public function close() {
        $this->contents = ob_get_clean();
    }

    public function contents($value=null) {
        if($value !== null) {
            $this->contents = $value;
        }

        return $this->contents;
    }
}

function open($name) {
    Block::get($name)->open();
}

function close($name) {
    Block::get($name)->close();
}

function set_block($name, $value=null) {
    return Block::get($name)->contents($value);
}

function get_block($name, $default=null) {
    if($contents = Block::get($name)->contents()) {
        return $contents;
    }

    return $default;
}
