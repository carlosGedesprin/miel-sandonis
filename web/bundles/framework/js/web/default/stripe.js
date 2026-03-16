var stripe = Stripe(stripe_p);
var elements = stripe.elements();
var cardElement = elements.create('card', { style: {
        base: {
            iconColor: '#a9a9a9',
            color: '#707070',
            backgroundColor: 'rgba(255,255,255,0.1)',
            fontWeight: '500',
            fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
            fontSize: '28px',
            fontSmoothing: 'antialiased',
            ':-webkit-autofill': {
                color: '#fce883',
            },
            '::placeholder': {
                color: '#a9a9a9',
            },
        },
        invalid: {
            iconColor: '#e63500',
            color: '#e63500',
        },
    },
});
cardElement.mount('#card-element');
var stripeToken = '';

$(document).ready(function() {
    $("input[type=submit]").click(function() {
        $('input[type=submit]').attr("disabled", "disabled");
        $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
        $("#payment_form").submit();
    });
    
    $("#payment_form").submit(function(event) {
        event.preventDefault();
// create stripe token to make payment
        if (stripeToken.length === 0)
        {
            stripe.createToken(cardElement).then(function(result){
                if (result.error) {
                    $('input[type=submit]').removeAttr("disabled");
                    $(".paymentErrors").html(result.error.message);
                } 
                else {
                    handleStripeResponse(result);
                }
            });
        }
    });
    
});

// handle the response from stripe
function handleStripeResponse(result) {
//get stripe token id from result
        stripeToken = result.token.id;
//set the token into the form hidden input to make payment
        $("#payment_form").append("<input type='hidden' name='stripeToken' value="+stripeToken+" />");
        let submit = $("input[type=submit][clicked=true]").val();
        $("#payment_form").append("<input type='hidden' name='submitted' value="+submit+" />");
        $("#payment_form").get(0).submit();
}