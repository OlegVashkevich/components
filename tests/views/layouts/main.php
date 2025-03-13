<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$this->renderBlock('title');?></title>
    <?=$this->renderBlock('meta');?>
    <?=$this->componentsCss();?>
</head>
<body class="app">
<?=$this->renderBlock('content');?>
<?=$this->componentsJs();?>
</body>
</html>