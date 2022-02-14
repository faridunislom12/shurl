window._ = require('lodash');

// Load JavaScript plugin which provides support the specific needs of our application
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    // window.Dropzone = require('dropzone');
    window.List = require('list.js');
    window.Quill = require('quill');

    require('../../sass/dashboard/bootstrap');
    require('jquery-mask-plugin');
    require('select2/dist/js/select2.full')($);
} catch (e) {}

$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

$('.toast').toast('show');

$('body').tooltip({
    selector: '[data-toggle="tooltip"]'
});

// Starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    $("input").unmask();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();


// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
