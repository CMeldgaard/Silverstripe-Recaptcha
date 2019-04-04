<?php

namespace Lundco\SilverStripe\ReCaptcha\Form;

use SilverStripe\Forms\Form;

class RecaptchaForm extends Form
{
    public function __construct($controller,$name,$formFields,$formActions){

        $formFields->push(RecaptchaField::create());

        parent::__construct(
            $controller,
            $name,
            $formFields,
            $formActions
        );
    }
}