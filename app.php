<?php namespace Silverplate;

require 'vendor/autoload.php';

class Http404 extends \Exception {
}

class Http302 extends \Exception {
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

    protected static $path = null;

    public static function uri() {
        return trim($_GET['p'], '/');
    }

    public static function path($filename='') {
        if(static::$path === null) {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
            $server = $_SERVER['SERVER_NAME'];
            $directory = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

            static::$path = sprintf("%s://%s%s/", $protocol, $server, $directory);
        }

        return static::$path . $filename;
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
        
        foreach(array(App::MD, App::PHP, App::REDIR) as $extension) {
            if($pathname = $this->get_pathname($filename . '.' . $extension)) {
                return $pathname;
            }
        }

        throw new Http404;
    }

    public function run() {
        $uri = static::uri();

        try {
            if(!$uri) {
                throw new Http404;
            }

            if(in_array($uri, array('404', 'layout', 'app'))) {
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

            $response = $this->renderLayout($content, $extension);
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
        $parser = new \dflydev\markdown\MarkdownParser;

        $contents = $this->parseMDMeta(file_get_contents($pathname));

        return $parser->transformMarkdown(str_replace('path://', App::path(), $contents));
    }

    public function renderLayout($content, $extension) {
        return $this->renderHTML($this->get_pathname(get('layout', 'layout') . '.php'), array('content' => $content, 'type' => $extension . '-file'));
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
