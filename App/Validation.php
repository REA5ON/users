<?php

namespace App;

use Valitron\Validator;

class Validation
{
    protected $v;

    public function __construct(Validator $validator)
    {
        $this->v = $validator;
    }

    public function validation()
    {
        try {
            //El problema
            $v = new Validator(array($_POST));
            $v->rules([
                'required' => ['username', 'password', 'email'],
                'lengthMin' => ['password', 6]

            ]);
            $v->validate();

        } catch (\Exception $exception){
            d($v->errors());
        }
    }
}