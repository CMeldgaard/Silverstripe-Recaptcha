window.addEventListener("load",function() {
    var forms = document.getElementsByTagName("form");
    for (var i = 0 ; i < forms.length; i++) {
        forms[i].addEventListener("submit",function(event) {
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
                    submittedForm.submit();
                });
            });
        });
    }
});