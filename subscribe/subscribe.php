  <link rel="stylesheet" href="./subscribe/css/bootstrap-formhelpers-min.css" media="screen">
  <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  <script type="text/Javascript" src="./subscribe/js/bootstrap-formhelpers-min.js"></script>
  <script type="text/javascript" src="./subscribe/js/bootstrapValidator-min.js"></script>
  <!-- <script type="text/javascript" src="./subscribe/js/payment-form-init.js"></script> -->

    <div class="modal fade" id="stripeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title" id="myModalLabel">Secure Form</h3>
        </div>
        <div class="modal-body">

          <style type="text/css">
            <?php
              /*$icon_font_path = "./fonts/";
              $icon_font_name = "glyphicons-halflings-regular";
              $gicon = $icon_font_path . $icon_font_name; 

              @font-face {
                font-family: 'Glyphicons Halflings';
                src: "url('<?php echo $gicon ?>.eot')";
                src: "url('<?php echo $gicon ?>.eot?#iefix') format('embedded-opentype')",
                     "url('<?php echo $gicon ?>.woff') format('woff')",
                     "url('<?php echo $gicon ?>.ttf') format('truetype')",
                     "url('<?php echo $gicon ?>.svg#@{icon-font-svg-id}') format('svg')";
              }*/
            ?>
          </style>

          <script type="text/javascript">
          // on document ready
          $(document).ready(function() {

              // add years to dropdown
              var yearsAmount = 12;
                var select = $(".card-expiry-year"),
                year = new Date().getFullYear();

                for (var i = 0; i < yearsAmount; i++) {
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
          </script>
          <form action="" method="POST" id="payment-form" class="form-horizontal">
            <noscript>
            <div class="bs-callout bs-callout-danger">
              <h4>JavaScript is not enabled!</h4>
              <p>This payment form requires your browser to have JavaScript enabled. Please activate JavaScript and reload this page. Check <a href="http://enable-javascript.com" target="_blank">enable-javascript.com</a> for more informations.</p>
            </div>
            </noscript>

            <div class="alert alert-danger" id="a_x200" style="display: none;"> <strong>Error!</strong> <span class="payment-errors"></span> </div>
            <span class="payment-success">
            <?= $success ?>
            <?= $error ?>
            </span>
            <div class="row">
              <fieldset>  
              <!-- Form Name -->
              <legend style="text-align: center; border: none;">Billing Details</legend>

              <input type="hidden" id="cafe_member" name="cafe_member" value="">
              
              <!-- First Name -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">First Name</label>
                <div class="col-sm-6">
                  <input type="text" name="first_name" placeholder="First Name" class="f-name form-control">
                </div>
              </div>
              
              <!-- Last Name -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">Last Name</label>
                <div class="col-sm-6">
                  <input type="text" name="last_name" placeholder="Last Name" class="l-name form-control">
                </div>
              </div>

              <!-- Street -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">Address</label>
                <div class="col-sm-6">
                  <input type="text" name="street" placeholder="Street" class="address form-control">
                </div>
              </div>
              
              <!-- City -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">City</label>
                <div class="col-sm-6">
                  <input type="text" name="city" placeholder="City" class="city form-control">
                </div>
              </div>
              
              <!-- State -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">State</label>
                <div class="col-sm-6">
                  <input type="text" name="state" maxlength="65" placeholder="State" class="state form-control">
                </div>
              </div>
              
              <!-- Postcal Code -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">Postal Code</label>
                <div class="col-sm-6">
                  <input type="text" name="zip" maxlength="9" placeholder="Postal Code" class="zip form-control">
                </div>
              </div>
              
              <!-- Country -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">Country</label>
                <div class="col-sm-6"> 
                  <!--input type="text" name="country" placeholder="Country" class="country form-control"-->
                  <div class="country bfh-selectbox bfh-countries" name="country" placeholder="Select Country" data-country="US" data-flags="true" data-filter="true"> </div>
                </div>
              </div>
              
              <!-- Email -->
              <div class="form-group">
                <label class="col-sm-4 control-label" for="textinput">Email</label>
                <div class="col-sm-6">
                  <input type="text" name="email" maxlength="65" placeholder="Email" class="email form-control">
                </div>
              </div>
              </fieldset>
              <fieldset>
                <legend style="text-align: center; border: none;">Card Details</legend>
                
                <!-- Card Holder Name -->
                <div class="form-group">
                  <label class="col-sm-4 control-label"  for="textinput">Card Holder's Name</label>
                  <div class="col-sm-6">
                    <input type="text" name="cardholdername" maxlength="70" placeholder="Card Holder Name" class="card-holder-name form-control">
                  </div>
                </div>
                
                <!-- Card Number -->
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="textinput">Card Number</label>
                  <div class="col-sm-6">
                    <input type="text" id="cardnumber" maxlength="19" placeholder="Card Number" class="card-number form-control">
                  </div>
                </div>
                
                <!-- Expiry-->
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="textinput">Card Expiration Date</label>
                  <div class="col-sm-6">
                    <div class="form-inline">
                      <select name="select2" data-stripe="exp-month" class="card-expiry-month stripe-sensitive required form-control">
                        <option value="01" selected="selected">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                      <span> / </span>
                      <select name="select2" data-stripe="exp-year" class="card-expiry-year stripe-sensitive required form-control">
                      </select>
                    </div>
                  </div>
                </div>
                
                <!-- CVV -->
                <div class="form-group">
                  <label class="col-sm-4 control-label" for="textinput">CVV/CVV2</label>
                  <div class="col-sm-3">
                    <input type="text" id="cvv" placeholder="CVV" maxlength="4" class="card-cvc form-control">
                  </div>
                </div>
                
                <!-- Submit -->
                <div class="control-group">
                  <div class="controls">
                    <center>
                      <button class="btn btn-success" type="submit" name="submit">Pay Now</button>
                    </center>
                  </div>
                </div>
              </fieldset>
            </div>
          </form>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $('#stripeModal').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var cafe_member = button.data('cafe-member');
        var is_donate = button.data('is-donate');
        $('#cafe_member').val(cafe_member);
    });
  </script>