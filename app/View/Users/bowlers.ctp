
<div id="bowlers"></div>
<table class="table table-striped">
	<thead>
    <tr>
      <th>Bowler Name</th>
      <th></th>
    </tr>
  </thead>
  <tbody>  
   <?php
     if(!empty($bowlers) && is_array($bowlers)) {
        foreach($bowlers as $bowler) {
          echo "<tr>
                <td>".$bowler['firstname']." ".$bowler['lastname']."</td>
                <td>Edit | Delete</td>
              </tr>";
        }
     } else {
       echo "<tr>
                <td colspan='2'>No bowlers found, add a new one!</td>
              </tr>";
     }
    

   ?>
  </tbody>
</table>

<div id="message"></div>

<a class="btn" data-toggle="modal" href="#myModal1">Add New Bowler</a>
<div class="modal hide" id="myModal1">
	<?php 
    echo $this->Form->create(null, array(
      'type' => 'file', 
      'action' => 'addBowler',
      'default' => false)); 


    //UserAddBowlerForm
  ?>
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Add a New Bowler</h3>
  </div>
  <div class="modal-body">
    <?php
    	
		echo $this->TwitterBootstrap->flashes(array("auth" => true));
		echo $this->TwitterBootstrap->input("First Name:", array(
		    "input" => $this->Form->text("firstname"),
		    "help_inline" => "",
		    "help_block" => ""
		));
		echo $this->TwitterBootstrap->input("Last Name:", array(
		    "input" => $this->Form->text("lastname"),
		    "help_inline" => "",
		    "help_block" => ""
		));

		echo $this->TwitterBootstrap->input("Email:", array(
		    "input" => $this->Form->text("email"),
		    "help_inline" => "",
		    "help_block" => ""
		));

    echo $this->TwitterBootstrap->input("Birthday:", array(
        "input" => $this->Form->text("birthdate"),
        "help_inline" => "",
        "help_block" => ""
    ));

    echo $this->TwitterBootstrap->input("Address Line 1:", array(
        "input" => $this->Form->text("address1"),
        "help_inline" => "",
        "help_block" => ""
    ));

    echo "<div><div class='profileImage'><span id='uploadButton'>Change Image</span></div>";
		echo $this->Form->input('Profile Image:', array(
        'type' => 'file',
        'div' => array(
          'class' => 'imageUpload')));
    echo "</div>";
		
    ?>



  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <?php echo $this->TwitterBootstrap->button("Add Bowler", array("style" => "primary", "size" => "medium")); ?>
  </div>

  

  <?php echo $this->Form->end(); ?>
  <!--<script src="/js/fileuploader.js" type="text/javascript"></script>-->
    <script>
      $(function() {
        
          function createUploader(){            
              var uploader = new qq.FileUploader({
                  element: document.getElementById('uploadButton'),
                  action: 'do-nothing.htm',
                  debug: true
              }); 
          }  

          //$( "#UserBirthdate" ).datepicker();
          //createUploader();        

          $(".btn-primary").click(function() {  
            alert('submitting form');
            var dataString = "firstname=aaron";
            $.ajax({  
              type: "POST",  
              url: "/users/bowlers",  
              data: dataString,  
              success: function() {  
               alert('woo');
                $('#message').html("<h2>Contact Form Submitted!</h2>")  
                .append("<p>We will be in touch soon.</p>")  
                .show();
                $('#myModal1').modal('hide');    
              }  
            });  
            return false;   
          });  
        
      });
    </script>
</div>