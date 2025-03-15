# Components
Web components extension for [view-renderer](https://github.com/devanych/view-rendere)

## Usage
component - is a directory with files
- `template.php` - template - required
- `style.css` - css styles - optional
- `script.js` - js scripts - optional

add extension
```php
$renderer = new Renderer('tests/views');
$extension = new ComponentExtension('components',$renderer);
$renderer->addExtension($extension);
```

add `$this->componentsCss()` and `$this->componentsJs()` to your layout
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$this->renderBlock('title');?></title>
    <?=$this->renderBlock('meta');?>
    <?=$this->componentsCss(); //show all css of components  ?>
</head>
<body class="app">
<?=$this->renderBlock('content');?>
<?=$this->componentsJs(); //show all js of components  ?>
</body>
</html>
```
use `component` function in your view or in another component
```php
<?=$this->component('content', ['var_key'=>'var_value'])?>
```