<?php
/**
 * @var array{
 *     "id": int,
 *     "name": string,
 *     "lvl2": array{
 *          "id2": int|null,
 *          "name2": string|null
 *      },
 * } $data
 */

?>
<button
        class="superbtn js-superbtn-click"
        data-id="<?= $data['id'] ?>">
    <?= $data['name'] ?>
</button>
<button
        class="superbtn js-superbtn-click"
        data-id="<?= $data['lvl2']['id2'] ?>">
    <?= $data['lvl2']['name2'] ?>
</button>