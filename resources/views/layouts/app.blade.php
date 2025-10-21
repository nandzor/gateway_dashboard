<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

  <!-- Google Fonts - Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* Professional Sidebar Scrollbar */
    #sidebar-nav::-webkit-scrollbar {
      width: 6px;
    }

    #sidebar-nav::-webkit-scrollbar-track {
      background: rgba(31, 41, 55, 0.3);
      border-radius: 10px;
    }

    #sidebar-nav::-webkit-scrollbar-thumb {
      background: rgba(75, 85, 99, 0.5);
      border-radius: 10px;
      transition: background 0.2s ease;
    }

    #sidebar-nav::-webkit-scrollbar-thumb:hover {
      background: rgba(107, 114, 128, 0.7);
    }

    /* Firefox */
    #sidebar-nav {
      scrollbar-width: thin;
      scrollbar-color: rgba(75, 85, 99, 0.5) rgba(31, 41, 55, 0.3);
    }

    /* Smooth scrolling */
    #sidebar-nav {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body class="bg-gray-50">
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar"
      class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 -translate-x-full">
      <div class="flex items-center justify-center h-16 bg-gray-800">
        <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
      </div>

      <nav id="sidebar-nav" class="mt-8 px-4 space-y-1 overflow-y-auto pr-2" style="max-height: calc(100vh - 240px);">
        <!-- Dashboard -->
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6\' />'" />
          Dashboard
        </x-sidebar-link>

        <!-- Management Section -->
        <x-sidebar-section title="Management" />

        @if (auth()->user()->isAdmin())
          <!-- Company Groups (Admin only) -->
          <x-sidebar-link :href="route('company-groups.index')" :active="request()->routeIs('company-groups.*')">
            <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\' />'" />
            Company Groups
          </x-sidebar-link>
        @endif

        <!-- Company Branches -->
        <x-sidebar-link :href="route('company-branches.index')" :active="request()->routeIs('company-branches.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\' />'" />
          Branches
        </x-sidebar-link>

        <!-- Device Masters -->
        <x-sidebar-link :href="route('device-masters.index')" :active="request()->routeIs('device-masters.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z\' />'" />
          Devices
        </x-sidebar-link>

        <!-- Users -->
        <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\' />'" />
          Users
        </x-sidebar-link>

        <!-- Clients -->
        <x-sidebar-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\' />'" />
          Clients
        </x-sidebar-link>

        <!-- Currencies -->
        <x-sidebar-link :href="route('currencies.index')" :active="request()->routeIs('currencies.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' />'" />
          Currencies
        </x-sidebar-link>

        <!-- Histories -->
        <x-sidebar-link :href="route('histories.index')" :active="request()->routeIs('histories.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\' />'" />
          Histories
        </x-sidebar-link>

        <!-- Price Custom -->
        <x-sidebar-link :href="route('price-custom.index')" :active="request()->routeIs('price-custom.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z\' />'" />
          Price Custom
        </x-sidebar-link>

        <!-- Price Master -->
        <x-sidebar-link :href="route('price-master.index')" :active="request()->routeIs('price-master.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z\' />'" />
          Price Master
        </x-sidebar-link>

        <!-- Services -->
        <x-sidebar-link :href="route('services.index')" :active="request()->routeIs('services.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z\' />'" />
          Services
        </x-sidebar-link>

        <!-- Monitoring Section -->
        <x-sidebar-section title="Monitoring" />

        @if (auth()->user()->isAdmin())
          <!-- CCTV Layouts (Admin only) -->
          <x-sidebar-link :href="route('cctv-layouts.index')" :active="request()->routeIs('cctv-layouts.*')">
            <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1v-3z\' />'" />
            CCTV Layouts
          </x-sidebar-link>
        @endif

        <!-- CCTV Live Stream -->
        <x-sidebar-link :href="route('cctv-live-stream.index')" :active="request()->routeIs('cctv-live-stream.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z\' />'" />
          Live CCTV
        </x-sidebar-link>

        <!-- Re-ID Masters (Person Tracking) -->
        <x-sidebar-link :href="route('re-id-masters.index')" :active="request()->routeIs('re-id-masters.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\' />'" />
          Person Tracking
        </x-sidebar-link>

        <!-- Event Logs -->
        <x-sidebar-link :href="route('event-logs.index')" :active="request()->routeIs('event-logs.*')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\' />'" />
          Event Logs
        </x-sidebar-link>

        <!-- Reports Section -->
        <x-sidebar-section title="Reports" />

        <!-- Reports Dashboard -->
        <x-sidebar-link :href="route('reports.dashboard')" :active="request()->routeIs('reports.dashboard')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\' />'" />
          Analytics
        </x-sidebar-link>

        <!-- Daily Reports -->
        <x-sidebar-link :href="route('reports.daily')" :active="request()->routeIs('reports.daily')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\' />'" />
          Daily Reports
        </x-sidebar-link>

        <!-- Monthly Reports -->
        <x-sidebar-link :href="route('reports.monthly')" :active="request()->routeIs('reports.monthly')">
          <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\' />'" />
          Monthly Reports
        </x-sidebar-link>

        @if (auth()->user()->isAdmin())
          <!-- Admin Section -->
          <x-sidebar-section title="Admin" />

          <!-- API Credentials -->
          <x-sidebar-link :href="route('api-credentials.index')" :active="request()->routeIs('api-credentials.*')">
            <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z\' />'" />
            API Credentials
          </x-sidebar-link>

          <!-- Branch Event Settings -->
          <x-sidebar-link :href="route('branch-event-settings.index')" :active="request()->routeIs('branch-event-settings.*')">
            <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\' />'" />
            Event Settings
          </x-sidebar-link>

          <!-- WhatsApp Settings -->
          <x-sidebar-link :href="route('whatsapp-settings.index')" :active="request()->routeIs('whatsapp-settings.*')">
            <x-sidebar-icon :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z\' />'" />
            WhatsApp Settings
          </x-sidebar-link>
        @endif
      </nav>

      <div class="absolute bottom-0 w-64 px-4 py-4 border-t border-gray-800">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center">
              <span class="text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
          </div>
          <div class="ml-3 flex-1">
            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
          @csrf
          <button type="submit"
            class="w-full flex items-center justify-center px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-gray-800 rounded-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Navigation -->
      <header class="flex items-center justify-between px-6 py-4 bg-white shadow-md">
        <div class="flex items-center">
          <button id="sidebarToggle" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <h2 class="ml-4 text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
        </div>
        <div class="flex items-center space-x-4">
          <span
            class="px-3 py-1 text-xs font-semibold rounded-full {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
            {{ ucfirst(auth()->user()->role) }}
          </span>
        </div>
      </header>

      <!-- Page Content -->
      <main id="mainContent"
        class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100 p-6">
        @if (session('success'))
          <div data-toast-success="{{ session('success') }}"></div>
        @endif

        @if (session('error'))
          <div data-toast-error="{{ session('error') }}"></div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>

  @stack('scripts')

  <script>
    (() => {
      'use strict';

      const SidebarScroll = {
        config: {
          navId: 'sidebar-nav',
          activeClass: 'bg-gray-800',
          scrollDelay: 100,
          mobileScrollDelay: 320,
          visibilityBuffer: 80
        },

        /**
         * Check if element is visible in scrollable container
         */
        isElementVisible(element, container) {
          const containerTop = container.scrollTop;
          const containerBottom = containerTop + container.clientHeight;
          const elementTop = element.offsetTop;
          const elementBottom = elementTop + element.offsetHeight;

          return (
            elementTop >= containerTop + this.config.visibilityBuffer &&
            elementBottom <= containerBottom - this.config.visibilityBuffer
          );
        },

        /**
         * Scroll to active menu item
         */
        scrollToActive(delay = this.config.scrollDelay) {
          const nav = document.getElementById(this.config.navId);
          const activeLink = nav?.querySelector(`a.${this.config.activeClass}`);

          if (!activeLink || !nav) return;

          setTimeout(() => {
            // Only scroll if not already visible
            if (!this.isElementVisible(activeLink, nav)) {
              activeLink.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
                inline: 'nearest'
              });
            }
          }, delay);
        },

        /**
         * Initialize sidebar scroll
         */
        init() {
          // Auto-scroll on page load
          this.scrollToActive();
        }
      };

      const SidebarToggle = {
        /**
         * Initialize sidebar toggle
         */
        init() {
          const toggle = document.getElementById('sidebarToggle');
          const sidebar = document.getElementById('sidebar');

          if (!toggle || !sidebar) return;

          toggle.addEventListener('click', () => {
            const isOpening = sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full');

            // Scroll to active when opening sidebar
            if (isOpening) {
              SidebarScroll.scrollToActive(SidebarScroll.config.mobileScrollDelay);
            }
          });
        }
      };

      document.addEventListener('DOMContentLoaded', () => {
        SidebarScroll.init();
        SidebarToggle.init();
      });

    })();
  </script>
</body>

</html>
