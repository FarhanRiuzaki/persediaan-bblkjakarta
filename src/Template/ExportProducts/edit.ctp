<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExportProduct $exportProduct
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $exportProduct->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $exportProduct->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Export Products'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="exportProducts form large-9 medium-8 columns content">
    <?= $this->Form->create($exportProduct) ?>
    <fieldset>
        <legend><?= __('Edit Export Product') ?></legend>
        <?php
            echo $this->Form->control('product_id', ['options' => $products]);
            echo $this->Form->control('date');
            echo $this->Form->control('status');
            echo $this->Form->control('created_by');
            echo $this->Form->control('modified_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
