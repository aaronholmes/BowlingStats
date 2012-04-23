<?php 

echo $this->TwitterBootstrap->page_header("My Profile");

//echo $this->TwitterBootstrap->flash("success");

echo $this->Form->create(null, array('type' => 'file', 'action' => 'profile'));
echo $this->TwitterBootstrap->flashes(array("auth" => true));
echo $this->TwitterBootstrap->input("First Name:", array(
    "input" => $this->Form->text("firstname", array('value' => $user['User']['firstname'])),
    "help_inline" => "",
    "help_block" => ""
));
echo $this->TwitterBootstrap->input("Last Name:", array(
    "input" => $this->Form->text("lastname", array('value' => $user['User']['lastname'])),
    "help_inline" => "",
    "help_block" => ""
));

echo $this->TwitterBootstrap->input("Email:", array(
    "input" => $this->Form->text("email", array('value' => $user['User']['email'])),
    "help_inline" => "",
    "help_block" => ""
));

echo $this->Form->input('Profile Image:', array('type' => 'file'));

echo $this->TwitterBootstrap->button("Update", array("style" => "primary", "size" => "large"));

echo $this->Form->end();
?>