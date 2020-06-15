<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;

class ViewsIncludesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // PRODUCT VIEWS
        Blade::include('includes.product.product-form', 'productForm');
        Blade::include('includes.product.batch-no-form', 'batchNoForm');
        // PRICE VIEWS
        Blade::include('includes.product.price-list', 'priceList');

        // ACCOUNT VIEWS
        Blade::include('includes.customer.customer-form', 'customerForm');

        // EMPLOYEE VIEWS
        Blade::include('includes.employee.employee-form', 'employeeForm');
        Blade::include('includes.employee.qoutas-display', 'qoutasDisplay');

        // SUPPLIER VIEWS
        Blade::include('includes.supplier.supplier-form', 'supplierForm');

        // CREATE ORDER
        Blade::include('includes.create-order.account-list', 'accountList');
        Blade::include('includes.create-order.product-list', 'productList');
        Blade::include('includes.create-order.review-order', 'reviewOrder');

        // ACCOUNT RECEIVABLES
        Blade::include('includes.receivables.receivables-list', 'receivablesList');
        // Blade::include('includes.receivables.success-response', 'receivable_successResponse');
        Blade::include('includes.receivables.bills-list', 'billsList');
        Blade::include('includes.receivables.order-medicines-to-pay', 'orderMedicinesToPay');

        // SALES
        Blade::include('includes.sales.return-product', 'returnProduct');


        // ROLES
        Blade::include('includes.roles.permissions-list', 'permissionsList');


        // NOTIFICATIONS
        Blade::include('includes.notifications.btn-notification-action', 'btnNotificationAction');

        Blade::include('includes.notifications.critical-stock', 'criticalStock');
        Blade::include('includes.notifications.out-of-stock', 'outOfStock');
        Blade::include('includes.notifications.soon-expiring', 'soonExpiring');
        Blade::include('includes.notifications.expired', 'expired');

        Blade::include('includes.notifications.new-order', 'newOrder');
        Blade::include('includes.notifications.updates-on-created-order', 'updateOnCreatedOrder');
        Blade::include('includes.notifications.paid-bills-in-collection', 'paidBillsInCollection');



    }
}
