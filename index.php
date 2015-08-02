<?php include("./header.php"); ?>

<!-- contributing editor profile -->

<div class="row">

      <div class="col-xs-3 ">
        <div class="row">
          <div class="col-sm-12">
            <button class="btn btn-default btn-block" data-toggle="modal" data-target="#stripeModal" data-is-donate="false" data-list-id="00a570c259" data-cafe-member="Abby Morse">Subscribe</button>
          </div>
          <!-- <div class="col-sm-6">
            <button class="btn btn-default btn-block" data-toggle="modal" data-target="#stripeModal" data-is-donate="true" data-cafe-member="Abby Morse">Donate</button>
          </div> -->
         </div>
         <br>
         <div class="row">
          <div class="col-sm-12">
            <button class="btn btn-default btn-block">Twitter Follow </button>
          </div>
        </div>

      </div>
</div>
          
<?php 
  // Load the Strip form Code
  include_once './subscribe/subscribe.php'; 
?>
   
<?php include("./footer.php"); ?>