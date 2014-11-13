<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title><?php secho($meta['title'].'-'.conf('app', 'site_name')) ?></title>
    <meta name="keywords" content="<?php secho($meta['keywords']) ?>" />
    <meta name="description" content="<?php secho($meta['description']) ?>" />


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Fixed Top Navbar Example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo src_url('bootstrap/css/bootstrap.min.css'); ?>" type="text/css" />
    <!-- <link rel="stylesheet" href="<?php echo src_url('style/base.css'); ?>" type="text/css" /> -->
    <link href="<?php echo src_url('style/common.css'); ?>" rel="stylesheet" type="text/css" />


    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo src_url('scripts/ie-emulation-modes-warning.js'); ?>"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    
</head>
<body>
<?php include APP . "views/common/header.tpl.php"; ?>

<!-- 模板内容↓ -->
<?php include APP . "views/{$tpl}.tpl.php"; ?>
<!-- 模板内容↑ -->

<?php include APP . "views/common/footer.tpl.php"; ?>


<script src="<?php echo src_url('scripts/jquery1.7.1.min.js'); ?>"></script>
<script src="<?php echo src_url('bootstrap/js/bootstrap.min.js'); ?>"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?php echo src_url('scripts/ie10-viewport-bug-workaround.js'); ?>"></script>
<script src="<?php echo src_url('scripts/common.js'); ?>"></script>
</body>
</html>