(function ($) {
    'use strict'; 

    moment.tz.setDefault(hh_params.timezone);

    let stripe = Stripe(hh_stripe.publish_key);

    let elements = stripe.elements();

    let style = {
        base: {
            color: '#32325d',
            fontFamily: 'Poppins, Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    let card = elements.create('card', {style: style});

    card.mount('#card-element');

    card.addEventListener('change', function (event) {
        let displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            displayError.style.display = 'block';
        } else {
            displayError.textContent = '';
            displayError.style.display = 'none';
        }
    });



    let tokenRequest = function () {
        stripe.createToken(card).then(function (result) {
            if (result.error) {
                console.log('error')
            } else {
                let stripe_token = $('#checkout-payment-info input[name="stripe_token"]');
                stripe_token.val(result.token.id);
                $('#checkout-payment-info .hh-loading').hide();

                $('.checkout-form-payment').submit();
            }
        });
    };

    $('#checkout-payment-info .btn-complete-payment').click(function (e) {
        let t = $(this),
            form = t.closest('form');

        let payment = $('.payment-item .payment-method:checked', t.closest('.checkout-form')).val();
        if (payment === 'stripe') {
            e.preventDefault();

            $('.hh-loading', form).show();
            tokenRequest();
        }
    });
})(jQuery);