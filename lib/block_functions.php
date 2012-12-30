<?php

function open($name) {
    \Silverplate\Block::get($name)->open();
}

function close($name) {
    \Silverplate\Block::get($name)->close();
}

function meta($name, $value=null) {
    return \Silverplate\Block::get($name)->contents($value);
}

function get($name, $default=null) {
    if($contents = \Silverplate\Block::get($name)->contents()) {
        return $contents;
    }

    return $default;
}
