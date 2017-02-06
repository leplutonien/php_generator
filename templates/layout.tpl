<?php
    use lib\BoxFunctions;
?>
<!DOCTYPE html>
<html lang="${lang}">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo BoxFunctions::generatePath($httpRequest->requestURI(),'assets/images/shortcut.ico'); ?>">
    <link rel="stylesheet" href="<?php echo BoxFunctions::generatePath($httpRequest->requestURI(),'assets/css/style.css'); ?>">
    <script src="<?php echo BoxFunctions::generatePath($httpRequest->requestURI(),'assets/js/script.js'); ?>">  </script>
    <title>${title}</title>
</head>
<body>
<?php
 echo $content;
?>
</body>
</html>