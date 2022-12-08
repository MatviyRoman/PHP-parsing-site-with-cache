<?php
namespace App\Models;

abstract class Config
{
    /**
     * Set url
     * @var mixed
     */
    public $url = 'https://roman.matviy.pp.ua/';

    /**
     * Abstract method show
     * @return string
     */
    abstract public function show();

    /**
     * Abstract method render
     * @return string
     */
    abstract protected function render();

    /**
     * Abstract method get file
     * @param string $file
     * @return string
     */
    abstract protected function getFile(string $file): string;
}
