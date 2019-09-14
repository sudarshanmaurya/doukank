<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * SellerDataGrid Class
 *
 * @author Khaled Badenjki <m.k.badenjki@gmail.com>
 * @copyright 2019 Doukank Pvt Ltd (https://www.doukank.com)
 */
class SellerDataGrid extends DataGrid
{
    protected $sortOrder = 'desc'; //asc or desc

    protected $index = 'seller_id';

    protected $itemsPerPage = 20;

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customers')
            ->leftJoin('stores', 'customers.store_id', '=', 'stores.id')
            ->select('customers.id as seller_id', 'stores.title as store_name', 'stores.status as status')
            ->addSelect(DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as full_name'))
            ->whereNotNull('customers.store_id');

//        $queryBuilder = DB::table('product_flat')
//            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
//            ->leftJoin('attribute_families', 'products.attribute_family_id', '=', 'attribute_families.id')
//            ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
//            ->select('product_flat.product_id as product_id', 'product_flat.sku as product_sku', 'product_flat.name as product_name', 'products.type as product_type', 'product_flat.status', 'product_flat.price', 'attribute_families.name as attribute_family', DB::raw('SUM(product_inventories.qty) as quantity'))
//            ->where('channel', core()->getCurrentChannelCode())
//            ->where('locale', app()->getLocale())
//            ->groupBy('product_flat.product_id');

        $this->addFilter('seller_id', 'customers.id');
        $this->addFilter('full_name', DB::raw('CONCAT(customers.first_name, " ", customers.last_name)'));
        $this->addFilter('store_name', 'stores.title');
        $this->addFilter('status', 'stores.status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'seller_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
//
        $this->addColumn([
            'index' => 'store_name',
            'label' => trans('admin::app.datagrid.store'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
//
        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function($row) {
                if ($row->status == 1)
                    return 'Active';
                else
                    return 'Inactive';
            }
        ]);

    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'admin.marketplace.sellers.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST', // use GET request only for redirect purposes
            'route' => 'admin.marketplace.sellers.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'seller']),
            'icon' => 'icon trash-icon'
        ]);

        $this->enableAction = true;
    }
//
//    public function prepareMassActions() {
//        $this->addMassAction([
//            'type' => 'delete',
//            'label' => 'Delete',
//            'action' => route('admin.catalog.products.massdelete'),
//            'method' => 'DELETE'
//        ]);
//
//        $this->addMassAction([
//            'type' => 'update',
//            'label' => 'Update Status',
//            'action' => route('admin.catalog.products.massupdate'),
//            'method' => 'PUT',
//            'options' => [
//                'Active' => 1,
//                'Inactive' => 0
//            ]
//        ]);
//
//        $this->enableMassAction = true;
//    }
}