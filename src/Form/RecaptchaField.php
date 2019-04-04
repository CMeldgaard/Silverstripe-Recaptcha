<?php

namespace Lundco\SilverStripe\ReCaptcha\Form;

use SilverStripe\Forms\FormField;
use SilverStripe\View\Requirements;

class RecaptchaField extends FormField
{
    /**
     * @var string
     */
    protected $schemaDataType = FormField::SCHEMA_DATA_TYPE_HIDDEN;

    /**
     * @var string
     */
    protected $inputType = 'hidden';

    /**
     * @var string
     */
    private $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * RecaptchaField constructor.
     */
    public function __construct()
    {
        parent::__construct('reCaptchaToken');
    }

    public function getSiteKey(){
        return self::config()->get('siteKey');
    }

    public function getSecretKey(){
        return self::config()->get('secretKey');
    }

    public function getSpamLevel(){
        return self::config()->get('spamLevel')/100;
    }

    /**
     * @param array $properties
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     */
    public function Field($properties = array())
    {
        //Requirements::javascript();
        $vars = [
            "siteKey" => $this->getSiteKey(),
        ];

        Requirements::javascript('https://www.google.com/recaptcha/api.js?render='.$this->getSiteKey());
        Requirements::javascriptTemplate('lundco/silverstripe-recaptcha: js/recaptcha.js', $vars);

        return parent::Field($properties);
    }

    /**
     * @param \SilverStripe\Forms\Validator $validator
     * @return bool
     */
    public function validate($validator)
    {
        //What value does the tokenfield have
        $recaptcha_response = $this->value();

        if(!$recaptcha_response){
            $this->getForm()->sessionMessage('Der skete en fejl - Prøv venligst igen', 'bad');
            return false;
        }

        // Make and decode POST request:
        $recaptcha = file_get_contents($this->recaptcha_url . '?secret=' . $this->getSecretKey() . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        if(!isset($recaptcha->score)){
            $validator->validationError($this->name, 'Der skete en fejl - Prøv venligst igen');
            return false;
        }

        // Take action based on the score returned:
        if ($recaptcha->score < $this->getSpamLevel()) {
            $validator->validationError($this->name, 'reCaptcha vurdere at beskeden er spam');
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function IsHidden()
    {
        return true;
    }
}