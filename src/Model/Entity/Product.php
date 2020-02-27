<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $sub_category_id
 * @property string|null $name
 * @property string|null $unit
 * @property bool|null $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int|null $created_by
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int|null $modified_by
 *
 * @property \App\Model\Entity\SubCategory $sub_category
 * @property \App\Model\Entity\ExpendituresDistributionsDetail[] $expenditures_distributions_details
 * @property \App\Model\Entity\ExpendituresGrantsDetail[] $expenditures_grants_details
 * @property \App\Model\Entity\ExpendituresOthersDetail[] $expenditures_others_details
 * @property \App\Model\Entity\ExpendituresReclarificationsDetail[] $expenditures_reclarifications_details
 * @property \App\Model\Entity\ExpendituresTransfersDetail[] $expenditures_transfers_details
 * @property \App\Model\Entity\InitStocksDetail[] $init_stocks_details
 * @property \App\Model\Entity\InternalOrdersDetail[] $internal_orders_details
 * @property \App\Model\Entity\ProductUnit[] $product_units
 * @property \App\Model\Entity\PurchaseOrdersDetail[] $purchase_orders_details
 * @property \App\Model\Entity\PurchaseRequestsDetail[] $purchase_requests_details
 * @property \App\Model\Entity\ReceiptGrantsDetail[] $receipt_grants_details
 * @property \App\Model\Entity\ReceiptOthersDetail[] $receipt_others_details
 * @property \App\Model\Entity\ReceiptPurchasesDetail[] $receipt_purchases_details
 * @property \App\Model\Entity\ReceiptReclarificationsDetail[] $receipt_reclarifications_details
 * @property \App\Model\Entity\ReceiptTransfersDetail[] $receipt_transfers_details
 * @property \App\Model\Entity\StockInstitute[] $stock_institutes
 * @property \App\Model\Entity\StockOpnamesDetail[] $stock_opnames_details
 * @property \App\Model\Entity\Stock[] $stocks
 * @property \App\Model\Entity\StocksNew[] $stocks_new
 * @property \App\Model\Entity\UseInstitutesDetail[] $use_institutes_details
 */
class Product extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'code' => true,
        'sub_category_id' => true,
        'name' => true,
        'unit' => true,
        'no_catalog' => true,
        'min_unit' => true,
        'status' => true,
        'created' => true,
        'created_by' => true,
        'modified' => true,
        'modified_by' => true,
        'sub_category' => true,
        'expenditures_distributions_details' => true,
        'expenditures_grants_details' => true,
        'expenditures_others_details' => true,
        'expenditures_reclarifications_details' => true,
        'expenditures_transfers_details' => true,
        'init_stocks_details' => true,
        'internal_orders_details' => true,
        'product_units' => true,
        'purchase_orders_details' => true,
        'purchase_requests_details' => true,
        'receipt_grants_details' => true,
        'receipt_others_details' => true,
        'receipt_purchases_details' => true,
        'receipt_reclarifications_details' => true,
        'receipt_transfers_details' => true,
        'stock_institutes' => true,
        'stock_opnames_details' => true,
        'stocks' => true,
        'stocks_new' => true,
        'use_institutes_details' => true
    ];
}
