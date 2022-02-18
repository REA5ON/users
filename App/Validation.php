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

    public function validation($rules)
    {
        try {
            $v = new Validator($_POST);
            $v->rules($rules);
            if ($v->validate()) {
                return true;
            } else {
                //get all errors
                foreach ($v->errors() as $error) {
                    flash()->error($error);
                }
                Redirect::stay();
            }
            //Создать новый exception
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}