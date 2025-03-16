<?php

declare(strict_types=1);

/** @var Devanych\View\Renderer $this */
/** @var string $variableName */

$this->layout('layouts/main');
$this->block('title', 'Page Title');
?>

    <p>Page Content</p>
<?= $this->component('content', ['tt1' => 'tttt1']) ?>
<?= $this->component('content') ?>
<?= $this->component('content2') ?>
<?= $this->component('content3') ?>
    <div><?= $variableName ?></div>
<?php
$this->beginBlock('meta'); ?>
    <meta name="description" content="Page Description">
<?php
$this->endBlock(); ?>