<?php

declare(strict_types=1);

/** @var Devanych\View\Renderer $this */
/** @var string $variableName */

$this->layout('layouts/main');
$this->block('title', 'Page Title');
?>

    <p>Page Content</p>
<?= $this->component('bad_component') ?>
    <div><?= $variableName ?></div>
<?php
$this->beginBlock('meta'); ?>
    <meta name="description" content="Page Description">
<?php
$this->endBlock(); ?>