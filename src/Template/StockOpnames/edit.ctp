<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\StockOpname $stockOpname
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $stockOpname->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $stockOpname->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Stock Opnames'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Stock Opnames Details'), ['controller' => 'StockOpnamesDetails', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Stock Opnames Detail'), ['controller' => 'StockOpnamesDetails', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="stockOpnames form large-9 medium-8 columns content">
    <?= $this->Form->create($stockOpname) ?>
    <fieldset>
        <legend><?= __('Edit Stock Opname') ?></legend>
        <?php
            echo $this->Form->control('code');
            echo $this->Form->control('date');
            echo $this->Form->control('created_by');
            echo $this->Form->control('updated_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
