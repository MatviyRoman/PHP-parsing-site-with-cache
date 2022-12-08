<?php
namespace App\Models;

class Cache
{
    private static $cacheFile;

    /**
     * Render result
     *
     * @return string
     */
    public static function top()
    {
        $file = $_SERVER['SCRIPT_NAME'];

        if (!empty($_GET['file'])) {
            $file = $_GET['file'];
        }

        $ext = self::getExt($file);

        $break                = Explode('/', $file);
        $file                 = $break[\count($break) - 1];
        self::$cacheFile      = 'cached-' . substr_replace($file, '', -4) . '.' . $ext;
        $cacheTime            = 18000;

        // Serve from the cache if it is younger than $cacheTime
        if (file_exists('cache/' . self::$cacheFile) && time() - $cacheTime < filemtime('cache/' . self::$cacheFile)) {
            if ($ext != 'jpg' || $ext != 'ico' || $ext != 'js') {
                readfile('cache/' . self::$cacheFile);
                exit;
            }

            echo '<!-- Cached copy, generated ' . date('H:i', filemtime('cache/' . self::$cacheFile)) . " -->\n";

            readfile('cache/' . self::$cacheFile);
            exit;
        }
        ob_start(); // Start the output buffer
    }

    /**
     * Cache the contents to a cache file
     */
    public static function bottom()
    {
        $cached = fopen('cache/' . self::$cacheFile, 'w');
        fwrite($cached, ob_get_contents());
        fclose($cached);
        return ob_end_flush(); // Send the output to the browser
    }

    private static function getExt($file)
    {
        $fileInfo       = pathinfo($file);
        $ext            = $fileInfo['extension'];

        switch ($ext) {
            case 'php':
                header('Content-Type: text/html;charset=UTF-8');
                $ext = 'html';
                break;

            case 'js':
                header('Content-Type: text/javascript;charset=UTF-8:');
                    $ext = 'js';
                    break;

            case 'css':
                header('Content-Type: text/css');
                    $ext = 'css';
                    break;

            case 'jpg':
                header('Content-Type: image/jpeg');
                    $ext = 'jpg';
                    break;

            case 'ico':
                header('Content-Type: image/jpeg');
                    $ext = 'ico';
                    break;

            default:
                header('Content-Type: application/json');
                $ext = 'html';
                break;
        }

        return $ext;
    }
}
