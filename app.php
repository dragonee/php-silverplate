<?php

include 'lib/markdown/markdown.php';

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
    private static $layout = 'layout';
    
    public static function uri() {
        return trim($_SERVER['REDIRECT_INFO_REQUEST_URI'], '/');
    }

    public static function layout($layout=null) {
        if(isset($layout)) {
            static::$layout = $layout;
        }

        return static::$layout;
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

        if($pathname = $this->get_pathname($filename . '.md')) {
            return $pathname;
        }

        if($pathname = $this->get_pathname($filename . '.php')) {
            return $pathname;
        }

        if($pathname = $this->get_pathname($filename . '.redir')) {
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
            if($extension == 'redir') {
                throw new Http302(trim(file_get_contents($pathname)));
            }

            if($extension == 'php') {
                $content = $this->renderHTML($pathname);
            } elseif($extension == 'md') {
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

    public function renderMD($pathname) {
        return Markdown(file_get_contents($pathname));
    }

    public function renderLayout($content) {
        return $this->renderHTML($this->get_pathname(static::layout() . '.php'), array('content' => $content));
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
