<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="/">
            <span class="align-middle">UMA PP</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Pages
            </li>

            <li class="sidebar-item active">
                <a class="sidebar-link" href="{{route('home')}}">
                    <i class="align-middle" data-feather="sliders"></i> <span
                        class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Manage Website
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('posts.all')}}">
                    <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Posts</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('categories.index')}}">
                    <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Post Category</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('branches.index')}}">
                    <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Branches</span>
                </a>
            </li>
            <hr/>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('plans.index')}}">
                    <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Plans</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('members.index')}}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Members</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('subscriptions.index')}}">
                    <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Subscriptions</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('transactions.index')}}">
                    <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Transactions</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="ui-buttons.html">
                    <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Reports</span>
                </a>
            </li>

           
            <li class="sidebar-header">
                System Settings
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="ui-buttons.html">
                    <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Payment Methods</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('membership-categories.index')}}">
                    <i class="align-middle" data-feather="copy"></i> <span class="align-middle">Membership Category</span>
                </a>
            </li>
          

            <li class="sidebar-item">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="ui-cards.html">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Users</span>
                </a>
            </li>

            <li class="sidebar-header">
                Plugins & Addons
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">

                    <i class="align-middle" data-feather="lock"></i> <span class="align-middle">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>