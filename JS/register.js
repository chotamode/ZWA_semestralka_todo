/**
 * @description This file, register.js, contains JavaScript code that handles the registration form functionality.
 * It listens for the DOMContentLoaded event and attaches an event listener to the register button.
 * When the register button is clicked, it performs validation on the form fields and displays error messages if necessary.
 * The form fields are stored in the 'fields' object, and the validation rules are applied to each field.
 * If any errors are found, they are stored in the 'errors' array and displayed in an alert.
 * Additionally, the code adds event listeners to each form field, so that when a field is clicked, any error styling is removed.
 * 
 */

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('register_button').addEventListener('click', function (event) {
        var fields = {
            'username': document.getElementById('username'),
            'password': document.getElementById('password'),
            'password_confirm': document.getElementById('password_confirm'),
            'name': document.getElementById('name'),
            'surname': document.getElementById('surname'),
            'email': document.getElementById('email')
        };

        var errors = [];

        for (var field in fields) {
            if (fields[field].value.length < 3) {
                fields[field].classList.add('error_field');
                errors.push(field + ' is too short! (min 3 characters)');
                event.preventDefault();
            } else if (fields[field].value.length > 50) {
                fields[field].classList.add('error_field');
                errors.push(field + ' is too long!' + ' (max 50 characters)');
                event.preventDefault();
            } else {
                fields[field].classList.remove('error_field');
            }
        }

        var email = fields['email'].value;
        var atpos = email.indexOf('@');
        var dotpos = email.lastIndexOf('.');
        if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
            fields['email'].classList.add('error_field');
            errors.push('Email should be in format: email@domain.domain');
            event.preventDefault();
        } else {
            fields['email'].classList.remove('error_field');
        }

        if (fields['password'].value != fields['password_confirm'].value) {
            fields['password'].classList.add('error_field');
            fields['password_confirm'].classList.add('error_field');
            errors.push('Passwords do not match!');
            event.preventDefault();
        } else {
            fields['password'].classList.remove('error_field');
            fields['password_confirm'].classList.remove('error_field');
        }

        if (errors.length > 0) {
            alert(errors.join('\n'));
        }

        for (var field in fields) {
            fields[field].addEventListener('click', function (event) {
                event.target.classList.remove('error_field');
            });
        }
    }
    );
}
);