<div class="left-side-tabs">
    <div class="dashboard-left-links">
        <a href="{{ route('account.overview') }}" class="user-item @if(Route::currentRouteName() === 'account.overview') active @endif"><i class="uil uil-apps"></i>Overview</a>
        <a href="{{ route('account.orders') }}" class="user-item @if(Route::currentRouteName() === 'account.orders') active @endif"><i class="uil uil-box"></i>My Orders</a>
        <a href="{{ route('account.addresses') }}" class="user-item @if(Route::currentRouteName() === 'account.addresses') active @endif"><i class="uil uil-location-point"></i>My Address</a>
        <a href="{{ route('account.profile') }}" class="user-item @if(Route::currentRouteName() === 'account.profile') active @endif"><i class="uil uil-user-circle"></i>Profile</a>
        <a href="#" onclick="event.preventDefault();document.getElementById('logout-form-account').submit();" class="user-item">
            <i class="uil uil-exit"></i>Logout
        </a>
        <form id="logout-form-account" action="{{ route('logout') }}" method="POST" style="display: none;mb-1">
            @csrf
        </form>
    </div>
</div>
