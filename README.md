# Silverstripe Recaptcha
This module adds Google reCaptcha to SilverStripe 4.x, which you can use in your custom forms.

## Installation
```composer require meldgaard/silverstripe-recaptcha```

## Usage
Put your keys and spamscore in your app/_config/app.yml
```yml
Lundco\SilverStripe\ReCaptcha\Form\RecaptchaField:
  siteKey: 'Insert site key'
  secretKey: 'Insert secret key'
  spamLevel: 0-100
```

### Spamscore
In config you set the spamscore to a value from 0 to 100. Recommended value to start is 50.


### Form setup
Then you can use it in your forms, by using `RecaptchaForm` instead of `Form

```php
public function HelloForm()
{
    $fields = new FieldList(
        TextField::create('Name', _t('HelloForm.Name', 'Name')),
        TextField::create('Email', _t('HelloForm.Email', 'E-Mail')),
        TextareaField::create('Message', _t('HelloForm.Message', 'Message')),
    );

    $actions = new FieldList(
        FormAction::create('doSayHello')->setTitle(_t('HelloForm.Submit', 'Send'))
    );

    $required = new RequiredFields('Name', 'Email', 'Message');

    $form = new RecaptchaForm($this, 'HelloForm', $fields, $actions, $required);

    return $form;
}
```

## TODO
* Update module to handle v2 of recaptcha
* Update module to give possibility to hide badge in v3
