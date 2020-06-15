<div id="sidebar-menu">

    <ul class="metismenu" id="side-menu">

        <li class="menu-title">Navigation</li>

        <li>
            <a href="{{ route('dashboard') }}">
                <i class="mdi mdi-view-dashboard"></i>
                <span class="font-12"> Dashboard </span>
            </a>
        </li>

        <li>
            @canany([
                'access_product',
                'access_deals',
                'access_product_movement',
                'access_soon_expiring_product',
                'access_expired_product',
                'access_loss_product',
            ])
                <a href="javascript: void(0);">
                    <i class="mdi mdi-invert-colors"></i>
                    <span class="font-12"> Inventory </span>
                    <span class="menu-arrow"></span>
                </a>
            @endcanany
            <ul class="nav-second-level" aria-expanded="false">
                @can('access_product')
                    <li class="font-12"><a href="{{ route('inventory.products') }}">Product</a></li>
                @endcan
    
                {{-- <li class="font-12"><a href="{{ route('inventory.in-and-out') }}">In and Out</a></li> --}}

                @can('access_deals')
                    <li class="font-12"><a href="{{ route('inventory.deals') }}">Deals</a></li>
                @endcan
                {{-- @can('access_price') --}}
                    <li class="font-12"><a href="{{ route('inventory.price') }}">Price</a></li>
                {{-- @endcan --}}
                @can('access_product_movement')
                    <li class="font-12"><a href="{{ route('inventory.movement') }}">Movement</a></li>
                @endcan
                @can('access_soon_expiring_product')
                    <li class="font-12"><a href="{{ route('inventory.soon-expiring') }}">Soon Expiring</a></li>
                @endcan
                @can('access_expired_product')
                    <li class="font-12"><a href="{{ route('inventory.expired') }}">Expired</a></li>
                @endcan
                @can('access_returned_product')
                    <li class="font-12"><a href="{{ route('inventory.returns') }}">Returns</a></li>
                @endcan
                @can('access_loss_product')
                    <li class="font-12"><a href="{{ route('inventory.loss') }}">Loss</a></li>
                @endcan
    

            </ul>
        </li>
        
        @can('access_sales')
            <li>
                <a href="{{ route('transactions.sales') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span class="font-12"> Sales </span>
                </a>
            </li>
        @endcan

        
        @can('access_receivables')
            <li>
                <a href="{{ route('transactions.receivables') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span class="font-12"> Account Receivables </span>
                </a>
            </li>
        @endcan

        
        @can('access_collections')
            <li>
                <a href="{{ route('transactions.collections') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span class="font-12"> Collections </span>
                </a>
            </li>
        @endcan

        
        <li>
            @canany([
                'access_create_order',
                'access_pending_order',
                'access_track_order',
            ])
                <a href="javascript: void(0);">
                    <i class="mdi mdi-view-list"></i>
                    <span class="font-12"> Order </span>
                    <span class="menu-arrow"></span>
                </a>
            @endcanany
            <ul class="nav-second-level" aria-expanded="false">
                @can('access_create_order')
                    <li class="font-12"><a href="{{ route('order.create') }}">Create Order</a></li>
                @endcan
                @can('access_pending_order')
                    <li class="font-12"><a href="{{ route('order.pending') }}">Pending Orders</a></li>
                @endcan
                @can('access_track_order')
                    <li class="font-12"><a href="{{ route('order.track') }}">Track Orders</a></li>
                @endcan
                {{-- @can('access_track_order') --}}
                    <li class="font-12"><a href="{{ route('order.re-dr') }}">Re DR's</a></li>
                {{-- @endcan --}}
            </ul>
        </li>
        
        @can('access_account')
            <li>
                <a href="{{ route('users.customers') }}">
                    <i class="mdi mdi-calendar"></i>
                    <span class="font-12"> Customer Accounts </span>
                </a>
            </li>
        @endcan

        @can('access_role')
            <li>
                <a href="{{ route('roles') }}">
                    <i class="mdi mdi-email"></i>
                    <span class="font-12"> Roles </span>
                </a>
            </li>
        @endcan

        @can('access_employee')
            <li>
                <a href="{{ route('users.employees') }}">
                    <i class="mdi mdi-email"></i>
                    <span class="font-12"> Employee </span>
                </a>
            </li>
        @endcan

        @can('access_supplier')
            <li>
                <a href="{{ route('users.suppliers') }}">
                    <i class="mdi mdi-email"></i>
                    <span class="font-12"> Supplier </span>
                </a>
            </li>
        @endcan

        @can('access_notifications')
    		<li>
                <a href="{{ route('notifications') }}">
                    <i class="mdi mdi-email"></i>
                    <span class="font-12"> Notifications </span>
                    <span class="badge badge-primary float-right --all-notif-count"></span>
                </a>
            </li>
        @endcan

        @can('access_backup_restore')
            <li>
                <a href="{{ route('backup.restore') }}">
                    <i class="mdi mdi-email"></i>
                    <span class="font-12"> Backup and Restore </span>
                </a>
            </li>
        @endcan
        
    </ul>

</div>