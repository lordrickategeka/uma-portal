<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="/">
            <span class="align-middle">UMA PP</span>
        </a>

        @auth
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Pages
                </li>

                <li class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('home') }}">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>

                @hasanyrole('admin|super-admin|member')
                    <li class="sidebar-item {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('subscriptions.index') }}">
                            <i class="align-middle" data-feather="user-plus"></i> <span
                                class="align-middle">Subscriptions</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('installments.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('installments.index') }}">
                            <i class="align-middle" data-feather="user-plus"></i> <span
                                class="align-middle">Installments</span>
                        </a>
                    </li>
                    <hr />
                @endhasanyrole

                @hasanyrole('admin|super-admin|editor')
                    <li class="sidebar-header">
                        Manage Website
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('posts.*') ? 'active' : '' }}"
                            href="{{ route('posts.all') }}">
                            <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Posts</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('posts.*') ? 'active' : '' }}"
                            href="{{ route('posts.all') }}">
                            <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Events</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('subscribers.*') ? 'active' : '' }}" href="{{route('subscribers.index')}}">
                            <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Subscribers</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{route('notifications.index')}}">
                            <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Notifications</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">
                            <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Post Category</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('branches.*') ? 'active' : '' }}"
                            href="{{ route('branches.index') }}">
                            <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Branches</span>
                        </a>
                    </li>
                    {{-- <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('events.*') ? 'active' : '' }}"
                            href="{{ route('events.index') }}">
                            <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Events</span>
                        </a>
                    </li> --}}
                    <hr />
                @endhasanyrole

                @hasanyrole('admin|super-admin')
                    <li class="sidebar-header">
                        Portal Settings
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('plans.*') ? 'active' : '' }}"
                            href="{{ route('plans.index') }}">
                            <i class="align-middle" data-feather="codepen"></i> <span class="align-middle">Plans</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('members.*') ? 'active' : '' }}"
                            href="{{ route('members.index') }}">
                            <i class="align-middle" data-feather="users"></i> <span class="align-middle">Members</span>
                        </a>
                    </li>


                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                            href="{{ route('transactions.index') }}">
                            <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Transactions</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" href="#">
                            <i class="align-middle" data-feather="book-open"></i> <span class="align-middle">Reports</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        System Settings
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" href="#">
                            <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Payment
                                Methods</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('membership-categories.*') ? 'active' : '' }}"
                            href="{{ route('membership-categories.index') }}">
                            <i class="align-middle" data-feather="copy"></i> <span class="align-middle">Membership
                                Category</span>
                        </a>
                    </li>
                @endhasanyrole

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" href="#">
                        <i class="align-middle" data-feather="user"></i> <span class="align-middle">Profile</span>
                    </a>
                </li>


                @role('super-admin')
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Users</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                            href="{{ route('roles.index') }}">
                            <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Roles</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}"
                            href="{{ route('permissions.index') }}">
                            <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Permissions</span>
                        </a>
                    </li>
                @endrole

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('logout.*') ? 'active' : '' }}"
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">

                        <i class="align-middle" data-feather="lock"></i> <span class="align-middle">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        @endauth
    </div>
</nav>
