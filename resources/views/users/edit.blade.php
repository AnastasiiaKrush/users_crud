@extends('layouts.default')

@section('content')
    <div id="success-message" class="col valid-feedback"></div>
    <form id="user-update-form" name="user" class="needs-validation" novalidate>
        <label>Edit user</label>
        <fieldset>
            <div class="row">
                <div class="col">
                    <input value="{{ $user['name'] }}" name="name" type="text" class="form-control" placeholder="Name"
                           required>
                </div>
                <div class="col">
                    <input value="{{ $user['email'] }}" id="email" name="email" type="email" class="form-control"
                           placeholder="Email" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input value="{{ $user['birthday'] }}" id="birthday" name="birthday" type="date" min="1900-01-01"
                           class="form-control" required>
                </div>
                <div class="col">
                    <input value="{{ $user['phone_number'] }}" id="phone-number" name="phone_number" type="text"
                           class="form-control"
                           placeholder="Phone number">
                </div>
            </div>
            <div id="error-message" class="col invalid-feedback"></div>
            <button type="submit" class="btn btn-primary">Update</button>
        </fieldset>
    </form>

    <script>
        $(document).ready(
            (function () {
                Date.prototype.toDateInputValue = (function () {
                    let local = new Date(this);
                    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                    return local.toJSON().slice(0, 10);
                });

                let phoneNumberElem = document.getElementById("phone-number"),
                    currentDate = new Date().toDateInputValue(),

                    isChromium = window.chrome,
                    winNav = window.navigator,
                    vendorName = winNav.vendor,
                    isOpera = typeof window.opr !== "undefined",
                    isIEedge = winNav.userAgent.indexOf("Edge") > -1,
                    isIOSChrome = winNav.userAgent.match("CriOS");

                phoneNumberElem.autocomplete = 'off';

                if (isIOSChrome || (isChromium !== null && typeof isChromium !== "undefined" && vendorName === "Google Inc." &&
                    isOpera === false && isIEedge === false)) {
                    phoneNumberElem.autocomplete = 'disabled';
                }

                $('#phone-number').usPhoneFormat({format: '(xxx) xxx-xxxx'});
                $('#birthday').attr('max', currentDate);

                window.addEventListener('load', function () {
                    let forms = document.getElementsByClassName('needs-validation');

                    Array.prototype.filter.call(forms, function (form) {
                        form.addEventListener('submit', function (e) {
                            let errorMessageElem = document.getElementById("error-message"),
                                successMessageElem = document.getElementById("success-message"),
                                birthdayElem = document.getElementById("birthday"),
                                emailElem = document.getElementById("email"),

                                birthdayCheck = (currentDate > birthdayElem.value && birthdayElem.min <= birthdayElem.value) ? true : false,
                                phoneNumberCheck = (/^(\(0[5-9][0-9]\)[ ]\d{3}-\d{4})$/.test(phoneNumberElem.value)) ? true : false,
                                formCheck = (form.checkValidity() !== false) ? true : false;

                            e.preventDefault();

                            if (phoneNumberCheck) phoneNumberElem.classList.remove('is-invalid');

                            if (!formCheck || !phoneNumberCheck || !birthdayCheck) {

                                if (!phoneNumberCheck) {
                                    setMessage(errorMessageElem, "Please, enter valid phone number.");
                                    phoneNumberElem.classList.add('is-invalid');
                                }
                                if (!formCheck) setMessage(errorMessageElem, "Please, fill all required fields.");
                                if (!birthdayCheck && !formCheck) setMessage(errorMessageElem, "Please, enter correct birthday date.");

                                emailElem.classList.remove('is-invalid');
                                form.classList.add('was-validated');

                                e.stopPropagation();

                                return;
                            }

                            errorMessageElem.style.display = 'none';
                            form.classList.add('was-validated');

                            $.ajax({
                                type: "PUT",
                                url: "/users/" + window.location.pathname.split('/')[2],
                                async: true,
                                data: $(form).serialize(),
                            }).done(function () {

                                emailElem.classList.remove('is-invalid');

                                setMessage(successMessageElem, "User updated successfully.<br/>");
                                setTimeout(() => {
                                    setMessage(successMessageElem, '')
                                }, 2000);

                                form.classList.remove('was-validated');
                            }).fail(function (request) {
                                let errors = JSON.parse(request.responseText).errors;
                                if (errors.email) {
                                    emailElem.classList.add('is-invalid');
                                    setMessage(errorMessageElem, errors.email[0]);
                                }
                                if (errors.phone_number) {
                                    phoneNumberElem.classList.add('is-invalid');
                                    setMessage(errorMessageElem, errors.phone_number[0]);
                                }
                            });
                        }, false);
                    });
                }, false);
            })()
        );

        function setMessage(messageElem, messageText) {
            messageElem.style.display = 'block';
            messageElem.innerHTML = messageText;
        }
    </script>
@stop
