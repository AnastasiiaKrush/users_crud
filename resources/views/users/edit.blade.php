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
                    <input value="{{ $user['phone_number'] }}" name="phone_number" type="tel" class="form-control"
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

                $('#birthday').attr('max', new Date().toDateInputValue());

                window.addEventListener('load', function () {
                    let forms = document.getElementsByClassName('needs-validation');

                    Array.prototype.filter.call(forms, function (form) {
                        form.addEventListener('submit', function (e) {
                            let errorMessageElem = document.getElementById("error-message"),
                                successMessageElem = document.getElementById("success-message"),
                                formCheck = (form.checkValidity() !== false) ? true : false;

                            e.preventDefault();

                            if (!formCheck) {
                                setMessage(errorMessageElem, "Please, fill all required fields.");
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
                                setMessage(successMessageElem, "User updated successfully.<br/>");
                                setTimeout(() => {
                                    setMessage(successMessageElem, '')
                                }, 2000);
                                form.classList.remove('was-validated');
                            }).fail(function (request) {
                                let errors = JSON.parse(request.responseText).errors;
                                if (errors.email) setMessage(errorMessageElem, errors.email[0]);
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
