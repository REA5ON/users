<?php
namespace App;

use League\Plates\Engine;

class Template
{
    protected $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function template($render, $data = [])
    {
        echo $this->engine->render($render ,$data);
    }
}