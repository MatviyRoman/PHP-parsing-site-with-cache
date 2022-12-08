<?php
namespace App\Models;

class Source extends Config
{
    /**
     * Summary of show
     * @return void
     */
    public function show()
    {
        Cache::top();
        echo $this->render();
        Cache::bottom();
    }

    /**
     * Render result
     *
     * @return string
     */
    protected function render(): string
    {
        if (empty($_GET)) {
            $ch = curl_init();
            curl_setopt_array(
                $ch,
                [
                    CURLOPT_URL            => $this->url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_REFERER => $this->url,
                ]
            );
            $output = curl_exec($ch);
            $output = str_replace('assets', '?file=assets', $output);
            $output = str_replace('/cdn-cgi', '?file=cdn-cgi', $output);
            $output = str_replace('favicon.ico', '?file=favicon.ico', $output);

            curl_close($ch);
            return $output;
        }

        if (isset($_GET['file'])) {
            return $this->getFile((string)$_GET['file']);
        }
    }

    /**
     * Get file from other resource
     * @param string $file
     * @return void|bool file
     */
    protected function getFile(string $file): string
    {
        if (empty($file)) {
            return false;
        }
        $file      = htmlspecialchars(addslashes($file));
        $file      = $this->url . $file;
        $ch        = curl_init($this->url);

        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL            => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_REFERER => $this->url,
            ]
        );

        $data = curl_exec($ch);
        curl_close($ch);

        return file_get_contents($file, file_get_contents($this->url));
    }
}
