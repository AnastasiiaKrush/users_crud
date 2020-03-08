@extends('layouts.default')

@section('content')
   <div id="success-message" class="col valid-feedback"></div>
   <form id="user-create-form" name="user" class="needs-validation" novalidate>
        <label>Create new user</label>
        <fieldset>
            <div class="row">
                <div class="col">
                    <input name="name"type="text" class="form-control" placeholder="Name" required >
                </div>
                <div class="col">
                    <input id="email" name="email"type="email" class="form-control" placeholder="Email" required>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input id="birthday" name="birthday"type="date" min="1900-01-01" class="form-control" required>
                </div>
                <div class="col">
                    <input name="phone_number" type="tel" class="form-control"
                           pattern="/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/"
                           placeholder="Phone number">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input name="password" id="pass" type="password" class="form-control" placeholder="Password" required >
                </div>
                <div class="col">
                    <input name="password_confirm" id="pass-conf" type="password" class="form-control" placeholder="Confirm password" required>
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

               $('#birthday').attr('max', new Date().toDateInputValue());

               window.addEventListener('load', function() {
                   let forms = document.getElementsByClassName('needs-validation');

                   Array.prototype.filter.call(forms, function(form) {
                       form.addEventListener('submit', function(e) {
                           let passwordElem = document.getElementById('pass'),
                               passwordConfirmElem = document.getElementById('pass-conf'),
                               errorMessageElem = document.getElementById("error-message"),
                               successMessageElem = document.getElementById("success-message"),
                               passMatch = (passwordElem.value == passwordConfirmElem.value) ? true : false,
                               formCheck = (form.checkValidity() !== false) ? true : false;

                           e.preventDefault();

                           if (!passMatch || !formCheck) {
                               if(!passMatch) setMessage(errorMessageElem, "The password confirm and password must match.");
                               if(!formCheck) setMessage(errorMessageElem, "Please, fill all required fields.");
                               e.stopPropagation();
                               return;
                           }

                           errorMessageElem.style.display = 'none';
                           form.classList.add('was-validated');

                           $.ajax({
                               type: "POST",
                               url: "/users",
                               async: true,
                               data: $(form).serialize(),
                           }).done(function() {
                               setMessage(successMessageElem, "User created successfully.<br/>");
                               setTimeout(() => {setMessage(successMessageElem,'')}, 2000);
                               document.getElementById("user-create-form").reset();
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

       function setMessage (messageElem, messageText) {
           messageElem.style.display = 'block';
           messageElem.innerHTML = messageText;
       }
   </script>
@stop
