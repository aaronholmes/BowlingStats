My Profile
<br><br>
<?php 
echo $this->TwitterBootstrap->input("field_name", array(
    "input" => $this->Form->text("field_name"),
    "help_inline" => "Text to the right of the input",
    "help_block" => "Text under the input"
));
?>