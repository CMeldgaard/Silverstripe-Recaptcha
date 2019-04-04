window.addEventListener("load",function() {
    document.getElementById('RecaptchaForm_'+'$formName').addEventListener('submit', function (event) {
        event.preventDefault();    //stop form from submitting
        var submittedForm = event.target;
        // needs for recaptacha ready
        grecaptcha.ready(function () {
            // do request for recaptcha token
            // response is promise with passed token
            grecaptcha.execute('$siteKey', {action: 'contact'}).then(function (token) {
                // add token to form
                submittedForm.querySelector("input[name=reCaptchaToken]").value = token;

                //submit the form
                if($customFunction === 0){
                    submittedForm.submit();
                }else{
                    this['$customNameFunction']();
                }
            });
        });
    }, false);
});