"use strict";

document.addEventListener('DOMContentLoaded', pageStart);

function pageStart() {
    document.forms.bform.addEventListener('submit', function (event) {

        let currentForm = this;
        event.preventDefault();

        let phone = currentForm.querySelector('input[name="PHONE"]').value;
        let code = currentForm.querySelector('input[name="SMS_CODE"]').value;
        let newPass = currentForm.querySelector('input[name="USER_PASSWORD"]').value;
        let confPass = currentForm.querySelector('input[name="USER_CONFIRM_PASSWORD"]').value;

        if(code.length != 6){
            console.log('sms код должен быть равен 6 символам');
        }
        else if(newPass.length < 6){
            console.log('пароль меньше 6 символов');
        }
        else if(newPass && confPass && newPass !== confPass){
            console.log('пароли не равны');
        }
        else {

            let xhr = new XMLHttpRequest();
            xhr.open('get', '/auth/sms-change.php?AJAX=Y&PHONE=' + phone
                + '&SMS_CODE=' + code + '&PASS=' + newPass);
            xhr.onload = function (data) {

                let respTxt = data.currentTarget.responseText;
                let respObj = JSON.parse(respTxt);
                console.log(respObj);

                if (!respObj || respObj.ERRORS.length) {

                    for (let i = 0; i < respObj.ERRORS.length; i++) {
                        console.log(respObj.ERRORS[i]);
                    }
                } else if (respObj && respObj.SUCCESS) {

                    console.log(respObj);
                    //console.log();
                    //currentForm.querySelector('input[name="USER_LOGIN"]').value = respObj.VALUE[0].USER_LOGIN;
                    //currentForm.querySelector('input[name="USER_CHECKWORD"]').value = respObj.VALUE[0].USER_CHECKWORD;
                    //currentForm.submit();
                    location.href = '/user/profile/?login=yes';
                }
            };
            xhr.send();
        }
    })
}