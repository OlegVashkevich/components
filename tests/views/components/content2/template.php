<?php
/** @var Devanych\View\Renderer $this */

?>
<p>Page Content2</p>
<?= $this->component('content', []) ?>
<?= $this->component('content', ['tt1' => 'tttt11']) ?>
<?= $this->component('content', []) ?>
<?= $this->component('content', ['tt1' => 'tttt22']) ?>
