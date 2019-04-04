<?php

namespace Lundco\SilverStripe\ReCaptcha\Form;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\Validator;

class RecaptchaForm extends Form
{
    public function __construct(RequestHandler $controller,$name,FieldList $formFields,FieldList $formActions, Validator $validator = null){

        $formFields->push(RecaptchaField::create());

        parent::__construct(
            $controller,
            $name,
            $formFields,
            $formActions
        );
    }
}