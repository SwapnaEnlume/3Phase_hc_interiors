<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->Html->meta('icon') ?>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="/js/additional.js"></script>
	<style>
	.calculatebutton{ float:left; text-align:left; }
	.calculatebutton button{ background:#171717; color:#FFF; border:1px solid #000; margin:0; padding:5px 10px; font-size:14px; font-weight:bold; }
		
	.addaslineitembutton{ float:right; text-align:right; }
	.addaslineitembutton button{ background:#26337A; color:#FFF; border:1px solid #000; margin:0; padding:10px 20px; font-size:18px; font-weight:bold; }
		.clear{clear:both;}
	</style>
</head>
<body>
    <?= $this->fetch('content') ?>
</body>
</html>
