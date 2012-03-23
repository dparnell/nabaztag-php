<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nabaztag</title>

    <link href="css/bootstrap.css" rel="stylesheet"/>
    <link href="css/bootstrap-responsive.css" rel="stylesheet"/>
    <link href="css/docs.css" rel="stylesheet"/>
    <link href="css/jquery.mobile-1.0.1.css" rel="stylesheet"/>
    <link href="css/jquery.mobile.structure-1.0.1.css" rel="stylesheet"/>    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/jquery.mobile-1.0.1.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
  </head>
  <body data-ajax="false">

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
	<div class="container">
	  <a href="index.php" class="brand">Nabaztag Server</a>
	  <?php require('links.php'); ?>
	</div>
      </div>
      <?php if(isset($error)) { ?>
      <div class="alert alert-error"><?php echo $error; ?></div>
      <?php } ?>
      <?php if(isset($info)) { ?>
      <div class="alert alert-info"><?php echo $info; ?></div>
      <?php } ?>

    </div>
    <div class="container">
