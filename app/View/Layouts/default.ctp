<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
  	<title><?php echo $title_for_layout; ?></title>
  	<!--  meta info -->
	<?php
	    echo $this->Html->meta(array("name"=>"viewport",
	      "content"=>"width=device-width,  initial-scale=1.0"));
	    echo $this->Html->meta(array("name"=>"description",
	      "content"=>"this is the description"));
	    echo $this->Html->meta(array("name"=>"author",
	      "content"=>"TheHappyDeveloper.com - @happyDeveloper"))
  	?>

	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!-- styles -->

	<link rel="stylesheet/less" href="/css/bootstrap/bootstrap.less">
	<link rel="stylesheet/less" href="/css/bootstrap/bootstrap-responsive.less">
	<link rel="stylesheet/less" href="/css/bootstrap/dropdown.less">
	<link rel="stylesheet/less" href="/css/bootstrap/docs.less">
	<link rel="stylesheet/less" href="/css/bootstrap/prettify.less">
	<script src="/js/less-1.3.0.min.js"></script>

	<link rel="stylesheet" href="/css/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/css/app-style.css" type="text/css" media="all" />

	<style type="text/css">
      body {
        padding-top: 60px;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 40px;
      }
    </style>

	<!-- icons -->
	<?php
		echo  $this->Html->meta('icon',$this->webroot.'img/bootstrap/favicon.ico');
		echo $this->Html->meta(array('rel' => 'apple-touch-icon',
		  'href'=>$this->webroot.'img/bootstrap/apple-touch-icon.png'));
		echo $this->Html->meta(array('rel' => 'apple-touch-icon',
		  'href'=>$this->webroot.'img/bootstrap/apple-touch-icon.png',  'sizes'=>'72x72'));
		echo $this->Html->meta(array('rel' => 'apple-touch-icon',
		  'href'=>$this->webroot.'img/bootstrap/apple-touch-icon.png',  'sizes'=>'114x114'));
		?>
	<!-- page specific scripts -->
	<?php echo $scripts_for_layout; ?>
</head>
<body data-spy="scroll" data-target=".subnav" data-offset="50">	
	<script src="/js/bootstrap/jquery.js"></script>
	<script src="/js/jquery-1.8.19-ui.min.js" type="text/javascript"></script>
	<script src="/js/bootstrap/bootstrap-dropdown.js"></script>
	<script src="/js/bootstrap/bootstrap-modal.js"></script>
	<script src="/js/bootstrap/bootstrap-tooltip.js"></script>
	<script src="/js/bootstrap/bootstrap-popover.js"></script>
	<script src="/js/bootstrap/bootstrap-carousel.js"></script>
	<script src="/js/bootstrap/application.js"></script>
	<?php echo $this->element('users/admin_nav'); ?>
	<div id="container">
	  	<div id="row">
	    	<?php echo $this->Session->flash(); ?>
	    	<?php echo $content_for_layout; ?>
	  	</div>
  	</div>

  	

  	<?php echo $this->element('sql_dump'); ?>

</body>
</html>
