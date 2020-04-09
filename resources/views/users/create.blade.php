@extends('layouts.default')

@section('content')
   <div id="success-message" class="col valid-feedback"></div>
   <form id="user-create-form" name="user" class="needs-validation" novalidate >
        <label>Create new user</label>
        <fieldset>
            <div class="row">
                <div class="col">
                    <input name="name" type="text" class="form-control" placeholder="Name" required >
                </div>
                <div class="col">
                    <input id="email" name="email" type="email" class="form-control" placeholder="Email" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input id="birthday" name="birthday" type="date" min="1900-01-01" class="form-control" required>
                </div>
                <div class="col">
                    <input id="phone-number" name="phone_number" type="number" class="form-control" placeholder="Phone number">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input name="password" id="pass" type="password" class="form-control" placeholder="Password" required >
                </div>
                <div class="col">
                    <input name="password_confirm" id="pass-conf" type="password" class="form-control" placeholder="Confirm password">
                </div>
            </div>
            <div id="error-message" class="col invalid-feedback"></div>
            <button type="submit" class="btn btn-primary">Create</button>
        </fieldset>
   </form>

   <script>
       $(document).ready(
           (function() {
               Date.prototype.toDateInputValue = (function() {
                   let local = new Date(this);
                   local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
                   return local.toJSON().slice(0,10);
               });

                let currentDate = new Date().toDateInputValue();
                $('#birthday').attr('max', currentDate);

               window.addEventListener('load', function() {
                   let forms = document.getElementsByClassName('needs-validation');

                   Array.prototype.filter.call(forms, function(form) {
                       form.addEventListener('submit', function(e) {
                           let passwordElem = document.getElementById('pass'),
                               passwordConfirmElem = document.getElementById('pass-conf'),
                               errorMessageElem = document.getElementById("error-message"),
                               successMessageElem = document.getElementById("success-message"),
                               phoneNumberElem = document.getElementById("phone-number"),
                               birthdayElem = document.getElementById("birthday"),
                               emailElem = document.getElementById("email"),

                               birthdayCheck = (currentDate >= birthdayElem.value) ? true : false,
                               passMatch = (passwordElem.value == passwordConfirmElem.value) ? true : false,
                               formCheck = (form.checkValidity() !== false) ? true : false,
                               phoneNumberLength = (phoneNumberElem.value.length < 16) ? true : false;

                           e.preventDefault();

                           if (passMatch) {
                               passwordElem.classList.remove('is-invalid');
                               passwordConfirmElem.classList.remove('is-invalid');
                           }

                           if (!passMatch || !formCheck || !phoneNumberLength || !birthdayCheck) {
                               if(!passMatch) {
                                   setMessage(errorMessageElem, "The password confirm and password must match.");
                                   passwordElem.classList.add('is-invalid');
                                   passwordConfirmElem.classList.add('is-invalid');
                               }

                               if(!phoneNumberLength) setMessage(errorMessageElem, "Please, enter phone number a max of 15 digits.");
                               if(!formCheck) setMessage(errorMessageElem, "Please, fill all required fields.");
                               if(!birthdayCheck && !formCheck) setMessage(errorMessageElem, "Please, enter correct birthday date.");

                               emailElem.classList.remove('is-invalid');
                               form.classList.add('was-validated');

                               e.stopPropagation();

                               return;
                           }

                           errorMessageElem.style.display = 'none';

                           $.ajax({
                               type: "POST",
                               url: "/users",
                               async: true,
                               data: $(form).serialize(),
                           }).done(function() {
                               emailElem.classList.remove('is-invalid');
                               setMessage(successMessageElem, "User created successfully.<br/>");
                               setTimeout(() => {setMessage(successMessageElem,'')}, 2000);
                               document.getElementById("user-create-form").reset();
                               form.classList.remove('was-validated');
                           }).fail(function (request) {
                               let errors = JSON.parse(request.responseText).errors;
                               if (errors.email) {
                                   emailElem.classList.add('is-invalid');
                                   setMessage(errorMessageElem, errors.email[0]);
                               }
                           });
                       }, false);
                   });
               }, false);
           })()
       );

       function setMessage (messageElem, messageText) {
           messageElem.style.display = 'block';
           messageElem.innerHTML = messageText;
       }
   </script>
@stop
