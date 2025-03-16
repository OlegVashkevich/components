<?php
/**
 * @var OlegV\Components\Data<int|string, mixed> $data
 */

?>
<pre><?php
    print_r($data); ?></pre>
<button class="superbtn js-superbtn-click" data-id="<?= $data['id'] ?>"><?= $data['name'] ?></button>