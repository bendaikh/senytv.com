<!-- Modern Sidebar Navigation -->
<aside class="navbar">
    <!-- Brand/Logo -->
    <div class="navbar-brand">
        <a href="{{ route('admin.dashboard') }}">
            @if (siteLogo())
                <img src="{{ url(siteLogo()) }}" alt="{{ siteName() }}" />
            @else
                <span>{{ siteName() }}</span>
            @endif
        </a>
    </div>

    <!-- Navigation Menu -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                        <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                        <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                    </svg>
                </span>
                <span class="nav-link-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.channels.*') ? 'active' : '' }}" href="{{ route('admin.channels.index') }}">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                        <path d="M16 3l-4 4l-4 -4" />
                        <path d="M15 7v13" />
                        <path d="M18 15v.01" />
                        <path d="M18 12v.01" />
                    </svg>
                </span>
                <span class="nav-link-title">Channels</span>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                    </svg>
                </span>
                <span class="nav-link-title">Clients</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.clients.index') }}">Client Directory</a>
                <a class="dropdown-item" href="{{ route('admin.clients.create') }}">Add Client</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 4l-8 4l8 4l8 -4l-8 -4" />
                        <path d="M8 14l-4 2l8 4l8 -4l-4 -2" />
                        <path d="M8 10l-4 2l8 4l8 -4l-4 -2" />
                    </svg>
                </span>
                <span class="nav-link-title">Plans</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.plans.index') }}">All Plans</a>
                <a class="dropdown-item" href="{{ route('admin.plans.create') }}">New Plan</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11.5 17h-7.5a1 1 0 0 1 -1 -1v-12a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v9" />
                        <path d="M3 13h18" />
                        <path d="M8 21h3.5" />
                        <path d="M10 17l-.5 4" />
                        <path d="M15 19l2 2l4 -4" />
                    </svg>
                </span>
                <span class="nav-link-title">Subscriptions</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.subscriptions.index') }}">All Subscriptions</a>
                <a class="dropdown-item" href="{{ route('admin.subscriptions.create') }}">New Subscription</a>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <span class="nav-link-title">Support Tickets</span>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 21h8a5 5 0 0 0 5 -5v-3a3 3 0 0 0 -3 -3h-1v-2a5 5 0 0 0 -5 -5h-4a5 5 0 0 0 -5 5v8a5 5 0 0 0 5 5z" />
                        <path d="M7 7m0 1.5a1.5 1.5 0 0 1 1.5 -1.5h3a1.5 1.5 0 0 1 1.5 1.5v0a1.5 1.5 0 0 1 -1.5 1.5h-3a1.5 1.5 0 0 1 -1.5 -1.5z" />
                        <path d="M7 14m0 1.5a1.5 1.5 0 0 1 1.5 -1.5h7a1.5 1.5 0 0 1 1.5 1.5v0a1.5 1.5 0 0 1 -1.5 1.5h-7a1.5 1.5 0 0 1 -1.5 -1.5z" />
                    </svg>
                </span>
                <span class="nav-link-title">Blogs</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.blogs.index') }}">All Articles</a>
                <a class="dropdown-item" href="{{ route('admin.blogs.create') }}">New Article</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.landing-page.*') || request()->routeIs('admin.payment-methods.*') || request()->routeIs('admin.tos.*') || request()->routeIs('admin.languages.*') || request()->routeIs('admin.color-setup.*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                    </svg>
                </span>
                <span class="nav-link-title">Setup</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.landing-page.index') }}">Landing Page</a>
                <a class="dropdown-item" href="{{ route('admin.payment-methods.index') }}">Payment Setup</a>
                <a class="dropdown-item" href="{{ route('admin.settings.edit') }}">System Settings</a>
                <a class="dropdown-item" href="{{ route('admin.color-setup.index') }}">Color Setup</a>
                <a class="dropdown-item" href="{{ route('admin.tos.index') }}">Terms of Service</a>
                <a class="dropdown-item" href="{{ route('admin.languages.index') }}">Languages</a>
            </div>
        </li>
    </ul>

    <!-- User Profile at Bottom -->
    <div class="navbar-user">
        <div class="nav-item dropdown">
            <a href="#" class="nav-link" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar">{{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}</span>
                <div class="d-none d-xl-block ps-2">
                    <div style="color: #fff; font-weight: 600;">{{ auth('admin')->user()->name }}</div>
                    <div style="color: rgba(255,255,255,0.6); font-size: 0.75rem;">Administrator</div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end" data-bs-theme="light" style="position: absolute !important; bottom: 100% !important; top: auto !important; margin-bottom: 0.5rem !important;">
                <a href="{{ route('admin.profile') }}" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                    Profile
                </a>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path d="M9 12h12l-3 -3" />
                            <path d="M18 15l3 -3" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
