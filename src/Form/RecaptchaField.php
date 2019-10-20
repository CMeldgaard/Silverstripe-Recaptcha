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

    private $customFunction = 0;
    private $customFunctionName = 'none';

    /**
     * RecaptchaField constructor.
     */
    public function __construct($customFunction)
    {
        if ($customFunction) {
            $this->customFunction = 1;
            $this->customFunctionName = $customFunction;
        }

        parent::__construct('reCaptchaToken');
    }

    public function getSiteKey()
    {
        return self::config()->get('siteKey');
    }

    public function getSecretKey()
    {
        return self::config()->get('secretKey');
    }

    public function getSpamLevel()
    {
        return self::config()->get('spamLevel') / 100;
    }

    /**
     * @param array $properties
     * @return \SilverStripe\ORM\FieldType\DBHTMLText
     */
    public function Field($properties = array())
    {
        static $jsIncluded = false;

        //Requirements::javascript();
        $vars = [
            "siteKey" => $this->getSiteKey(),
            "customFunction" => $this->customFunction,
            "customNameFunction" => $this->customFunctionName,
            "formName" => $this->getForm()->FormName()
        ];

        $recaptchaJs = 'https://www.google.com/recaptcha/api.js?render=' . $this->getSiteKey();

        //Load all requred scripts
        $scripts = Requirements::backend()->getJavascript();

        //Check if scrips already is loaded - if not, then require
        if (!isset($scripts[$recaptchaJs])) {
            Requirements::javascript($recaptchaJs);
        }

        Requirements::javascriptTemplate('meldgaard/silverstripe-recaptcha: js/recaptcha.js', $vars);

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

        $recaptcha_score_error = _t('ReCaptchaField.ScoreError', 'reCaptcha vurdere at beskeden er spam');
        $recaptcha_error = _t('ReCaptchaField.ErrorMessage', 'Der skete en fejl - Prøv venligst igen');

        if (!$recaptcha_response) {
            $this->getForm()->sessionMessage($recaptcha_error, 'bad');
            return false;
        }

        // Make and decode POST request:
        $recaptcha = file_get_contents($this->recaptcha_url . '?secret=' . $this->getSecretKey() . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        if (!isset($recaptcha->score)) {
            $validator->validationError($this->name, $recaptcha_error);
            return false;
        }

        // Take action based on the score returned:
        if ($recaptcha->score < $this->getSpamLevel()) {
            $validator->validationError($this->name, $recaptcha_score_error);
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
