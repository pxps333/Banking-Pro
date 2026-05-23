<?php
// Usage: set $breadcrumbs array before including this file.
// e.g. $breadcrumbs = [['Home','./dashboard.php'],['Transactions',null]];
// If $breadcrumbs is not set, auto-generate from $pageName.
if (!isset($breadcrumbs)) {
    $breadcrumbs = [['Home', './dashboard.php'], [$pageName, null]];
}
?>
<div class="bp-page-header">
    <div>
        <h1 class="bp-page-title"><?= htmlspecialchars($pageName) ?></h1>
        <ol class="bp-breadcrumb">
            <?php foreach ($breadcrumbs as $i => $crumb):
                $isLast = ($i === count($breadcrumbs) - 1);
            ?>
            <li>
                <?php if (!$isLast && $crumb[1]): ?>
                    <a href="<?= htmlspecialchars($crumb[1]) ?>"><?= htmlspecialchars($crumb[0]) ?></a>
                <?php else: ?>
                    <?= htmlspecialchars($crumb[0]) ?>
                <?php endif; ?>
            </li>
            <?php if (!$isLast): ?>
            <li class="bp-breadcrumb-sep"><i class="ri-arrow-right-s-line"></i></li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</div>
