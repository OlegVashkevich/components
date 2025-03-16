<?php

declare(strict_types=1);

/** @var Devanych\View\Renderer $this */
/** @var mixed $variableName */

$this->layout('layouts/main');
$this->block('title', 'Page Title');
?>
<?= $this->component('catalog/button', []) ?>
<?= $this->component('catalog/button', ['id' => 1, 'name' => 'button', 'lvl2' => ['id2' => 2, 'name2' => 'button2']]) ?>
