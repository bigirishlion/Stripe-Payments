var stripe = {

	/* variables */
	yearsAmount: 12,
	debug: true,

	/* Logging function */
	log: function(text) {
		if (this.debug) {
			if (window.console && console.log) {
				console.log('log: ' + text);
			}
		}
	},

	init : function(){

		// add years to dropdown
		var select = $(".card-expiry-year"),
		year = new Date().getFullYear();

		for (var i = 0; i < this.yearsAmount; i++) {
		select.append($("<option value='"+(i + year)+"' "+(i === 0 ? "selected" : "")+">"+(i + year)+"</option>"))
		}

		// perform validation on submit
		$('#payment-form').bootstrapValidator({
		    message: 'This value is not valid',
		    feedbackIcons: {
		        valid: 'glyphicon glyphicon-ok',
		        invalid: 'glyphicon glyphicon-remove',
		        validating: 'glyphicon glyphicon-refresh'
		    },
		        submitHandler: function(validator, form, submitButton) {
		    // createToken returns immediately - the supplied callback submits the form if there are no errors
		    Stripe.card.createToken({
		      number: $('.card-number').val(),
		      cvc: $('.card-cvc').val(),
		      exp_month: $('.card-expiry-month').val(),
		      exp_year: $('.card-expiry-year').val(),
		            name: $('.card-holder-name').val(),
		            address_line1: $('.address').val(),
		            address_city: $('.city').val(),
		            address_zip: $('.zip').val(),
		            address_state: $('.state').val(),
		            address_country: $('.country').val()
		    }, stripeResponseHandler);
		    return false; // submit from callback
		    },
		    fields: {
		        first_name: {
		            validators: {
		                notEmpty: {
		                    message: 'First Name is required'
		                }
		            }
		        },
		        last_name: {
		            validators: {
		                notEmpty: {
		                    message: 'Last Name is required'
		                }
		            }
		        },
		        street: {
		            validators: {
		                notEmpty: {
		                    message: 'Address is required'
		                }
		            }
		        },
		        city: {
		            validators: {
		                notEmpty: {
		                    message: 'City is required'
		                }
		            }
		        },
		        state: {
		            validators: {
		                notEmpty: {
		                    message: 'State is required'
		                }
		            }
		        },
		              zip: {
		            validators: {
		                notEmpty: {
		                    message: 'Zip is required'
		                },
		                          stringLength: {
		                    min: 3,
		                    max: 9,
		                    message: 'The zip must be more than 3 and less than 9 characters long'
		                }
		            }
		        },
		        email: {
		            validators: {
		                notEmpty: {
		                    message: 'Email address is required'
		                },
		                emailAddress: {
		                    message: 'The input is not a valid email address'
		                },
		                          stringLength: {
		                    min: 6,
		                    max: 65,
		                    message: 'The email must be more than 6 and less than 65 characters long'
		                }
		            }
		        },
		        cardholdername: {
		            validators: {
		                notEmpty: {
		                    message: 'Card holder name is required and can\'t be empty'
		                },
		                stringLength: {
		                    min: 6,
		                    max: 70,
		                    message: 'Card holder name must be more than 6 and less than 70 characters long'
		                }
		            }
		        },
		        cardnumber: {
		    selector: '#cardnumber',
		            validators: {
		                notEmpty: {
		                    message: 'Credit card number is required'
		                },
		                creditCard: {
		                    message: 'Credit card number is invalid'
		                },
		            }
		        },
		        expMonth: {
		            selector: '[data-stripe="exp-month"]',
		            validators: {
		                notEmpty: {
		                    message: 'Expiration month is required'
		                },
		                digits: {
		                    message: 'Expiration month can contain digits only'
		                },
		                callback: {
		                    message: 'Expired',
		                    callback: function(value, validator) {
		                        value = parseInt(value, 10);
		                        var year         = validator.getFieldElements('expYear').val(),
		                            currentMonth = new Date().getMonth() + 1,
		                            currentYear  = new Date().getFullYear();
		                        if (value < 0 || value > 12) {
		                            return false;
		                        }
		                        if (year == '') {
		                            return true;
		                        }
		                        year = parseInt(year, 10);
		                        if (year > currentYear || (year == currentYear && value > currentMonth)) {
		                            validator.updateStatus('expYear', 'VALID');
		                            return true;
		                        } else {
		                            return false;
		                        }
		                    }
		                }
		            }
		        },
		        expYear: {
		            selector: '[data-stripe="exp-year"]',
		            validators: {
		                notEmpty: {
		                    message: 'Expiration year is required'
		                },
		                digits: {
		                    message: 'Expiration year can contain digits only'
		                },
		                callback: {
		                    message: 'Expired',
		                    callback: function(value, validator) {
		                        value = parseInt(value, 10);
		                        var month        = validator.getFieldElements('expMonth').val(),
		                            currentMonth = new Date().getMonth() + 1,
		                            currentYear  = new Date().getFullYear();
		                        if (value < currentYear || value > currentYear + 100) {
		                            return false;
		                        }
		                        if (month == '') {
		                            return false;
		                        }
		                        month = parseInt(month, 10);
		                        if (value > currentYear || (value == currentYear && month > currentMonth)) {
		                            validator.updateStatus('expMonth', 'VALID');
		                            return true;
		                        } else {
		                            return false;
		                        }
		                    }
		                }
		            }
		        },
		        cvv: {
		    selector: '#cvv',
		            validators: {
		                notEmpty: {
		                    message: 'CVV is required'
		                },
		                          cvv: {
		                    message: 'The value is not a valid CVV',
		                    creditCardField: 'cardnumber'
		                }
		            }
		        },
		    }
		});

		// this identifies your website in the createToken call below
		Stripe.setPublishableKey('pk_live_0jHqZ61Yrh0rkLyVFsCgDcPz');

		function stripeResponseHandler(status, response) {
		    if (response.error) {
		        // re-enable the submit button
		        $('.submit-button').removeAttr("disabled");
		        // show hidden div
		        document.getElementById('a_x200').style.display = 'block';
		        // show the errors on the form
		        $(".payment-errors").html(response.error.message);
		        console.log(status + " : " + response.error);
		    } else {
		        var form$ = $("#payment-form");
		        // token contains id, last4, and card type
		        var token = response['id'];
		        // insert the token into the form so it gets submitted to the server
		        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
		        // and submit
		        //form$.get(0).submit();                    
		        $.post( "../subscribe/charge.php", form$.serialize(), function( data ) {
		          $('.payment-success').html(data);
		        });
		        return false
		    }
		}

	},
}