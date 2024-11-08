<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>
@if (backpack_user()->can('create_category'))
<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('categorie') }}'><i class='nav-icon las la-file-alt'></i> Categories
    </a>
</li>
@endif
@if (backpack_user()->can('access_product'))
<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('product') }}'><i class='nav-icon las la-coffee'></i> Products
    </a>
</li>
@endif
@if (backpack_user()->can('create_transaction'))
<li class='nav-item'>
    <a class='nav-link' href='{{ backpack_url('transaction') }}'><i class='nav-icon las la-file-invoice'></i> Transactions
    </a>
</li>
@endif
@if (backpack_user()->can('access_user') || backpack_user()->can('access_role') || backpack_user()->can('access_permission'))
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
        <i class="las la-cogs"></i>
        Manage Authantication
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('user') }}">
                <i class=" la la-user"></i> <span> Users </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('role') }}">
                <i class=" la la-group"></i> <span> Roles </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ backpack_url('permission') }}">
                <i class=" la la-key"></i> <span> Permission </span>
            </a>
        </li>
    </ul>
</li>
@endif