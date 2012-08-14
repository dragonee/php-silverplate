<html>
<head>
    <title><?php echo get('title', 'Default Title') ?></title>
</head>
<body class="<?php echo $type ?> <?php echo get('classes') ?>">
<?php echo $content ?>

<p>This paragraph is a part of the layout.php file.</p>

<footer>
    <?php echo get('footer') ?>
</footer>
</body>
</html>
