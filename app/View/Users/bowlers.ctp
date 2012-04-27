<table class="table table-striped">
	<thead>
    <tr>
      <th>Bowler Name</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Aaron Holmes</td>
      <td>Edit | Delete</td>
    </tr>
    <tr>
      <td>Aaron Holmes</td>
      <td>Edit | Delete</td>
    </tr>
    <tr>
      <td>Aaron Holmes</td>
      <td>Edit | Delete</td>
    </tr>
    <tr>
      <td>Aaron Holmes</td>
      <td>Edit | Delete</td>
    </tr>
  </tbody>
</table>

<a class="btn" data-toggle="modal" href="#myModal1">Add New Bowler</a>
<div class="modal fade" id="myModal1">
	<?php echo $this->Form->create(null, array('type' => 'file', 'action' => 'bowlers')); ?>
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

    <div id="file-uploader-demo1">    
    <noscript>      
      <p>Please enable JavaScript to use file uploader.</p>
      <!-- or put a simple form for upload here -->
    </noscript>         
  </div>

    <script src="/js/fileuploader.js" type="text/javascript"></script>
    <script>
      $(function() {
        
          function createUploader(){            
              var uploader = new qq.FileUploader({
                  element: document.getElementById('uploadButton'),
                  action: 'do-nothing.htm',
                  debug: true
              }); 
          }  

          $( "#UserBirthdate" ).datepicker();
          createUploader();        
        
      });
    </script>

  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <?php echo $this->TwitterBootstrap->button("Add Bowler", array("style" => "primary", "size" => "medium")); ?>
  </div>

  <?php echo $this->Form->end(); ?>
</div>