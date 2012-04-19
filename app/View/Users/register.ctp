<h2>Create an Account</h2>

<?php

echo $this->Session->flash('auth');
echo $this->Form->create('User', array('action' => 'register'));
echo $this->Form->input('username', array('between' => 'Pick a username'));

// Force the FormHelper to render a password field, and change the label.
echo $this->Form->input('passwrd', array('type' => 'password', 'label' => 'Password'));
echo $this->Form->input('passwrd2', array('type' => 'password', 'label' => 'Repeat Password'));
echo $this->Form->input('email', array('between' => 'We need to send you a confirmation email to check you are human'));
echo $this->Form->submit('Create Account');
echo $this->Form->end();
?>