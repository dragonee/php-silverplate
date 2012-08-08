<?php

include 'lib/markdown/markdown.php';
include 'lib/blocks.php';

class Http404 extends Exception {
}

class Http302 extends Exception {
    private $location;

    public function __construct($location) {
        $this->location = $location;    
    }

    public function getLocation() {
        return $this->location;
    }
}

class App {
    const 
        MD = 'md', 
        PHP = 'php',
        REDIR = 'redir';

    public static function uri() {
        return trim($_SERVER['REDIRECT_INFO_REQUEST_URI'], '/');
    }

    public function get_pathname($filename) {
        $filename = __DIR__ . '/' . $filename; 
        if(!file_exists($filename)) {
            return false;
        }

        $pathname = realpath($filename);
        if(!$pathname) {
            return false;
        }

        if(strpos($pathname, __DIR__) !== 0) {
            return false;
        }

        return $pathname;
    }

    public function find_file($filename) {
        if(is_dir($filename)) {
            $filename = rtrim($filename, '/') . '/index';
        }

        if($pathname = $this->get_pathname($filename . '.' . App::MD)) {
            return $pathname;
        }

        if($pathname = $this->get_pathname($filename . '.' . App::PHP)) {
            return $pathname;
        }

        if($pathname = $this->get_pathname($filename . '.' . App::REDIR)) {
            return $pathname;
        }

        throw new Http404;
    }

    public function run() {
        $uri = static::uri();

        try {
            if(!$uri) {
                throw new Http404;
            }

            $pathname = $this->find_file($uri);
        
            $extension = pathinfo($pathname, PATHINFO_EXTENSION);
            if($extension == App::REDIR) {
                throw new Http302(trim(file_get_contents($pathname)));
            }

            if($extension == App::PHP) {
                $content = $this->renderHTML($pathname);
            } elseif($extension == App::MD) {
                $content = $this->renderMD($pathname);
            }

            $response = $this->renderLayout($content);
        } catch(Http404 $e) {
            header("HTTP/1.1 404 Not Found");
            $response = $this->renderHTML('404.php');
        } catch(Http302 $e) {
            header("HTTP/1.1 302 Found");
            header("Location: " . $e->getLocation());
            exit();
        }

        echo $response;
    }

    public function parseMDMeta($file_contents) {
        return preg_replace_callback('/^Meta\s+([^:]+):(.*)$/mi', function($matches) {
            meta(trim($matches[1]), trim($matches[2]));
            return '';
        }, $file_contents);
    }

    public function renderMD($pathname) {
        return Markdown($this->parseMDMeta(file_get_contents($pathname)));
    }

    public function renderLayout($content) {
        return $this->renderHTML($this->get_pathname(get('layout', 'layout') . '.php'), array('content' => $content));
    }

    public function renderHTML($pathname, $data=array()) {
        extract($data);

        ob_start();
        include $pathname;
        return ob_get_clean();
    }
}

$app = new App;
$app->run();
