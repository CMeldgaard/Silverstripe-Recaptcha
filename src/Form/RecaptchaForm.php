<?php

namespace Lundco\SilverStripe\ReCaptcha\Form;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\Validator;

class RecaptchaForm extends Form
{
    public function __construct(RequestHandler $controller = null,$name,FieldList $formFields = null,FieldList $formActions = null, Validator $validator = null, $customFunction = null){

        $formFields->push(RecaptchaField::create($customFunction));

        parent::__construct(
            $controller,
            $name,
            $formFields,
            $formActions,
            $validator
        );
    }
}