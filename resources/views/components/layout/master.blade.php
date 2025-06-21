<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin' }}</title>

    {{-- Tambahkan baris meta tag CSRF ini --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen" id="wrapper">
        <x-shared.sidebar />
        <div id="page-content-wrapper" class="flex-1 flex flex-col overflow-hidden">
            <x-shared.header />
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
            </div>
        </div>

    <script>
        // Javascript untuk toggle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar-wrapper');
            const toggleButton = document.getElementById('menu-toggle');

            if (toggleButton && sidebar) {
                toggleButton.onclick = function () {
                    // Toggle class untuk menyembunyikan/menampilkan sidebar
                    sidebar.classList.toggle('-ml-64'); // Default hidden on mobile
                    sidebar.classList.toggle('ml-0');    // Visible state
                };
            }

            // Adjust sidebar visibility on window resize (for responsiveness)
            function adjustSidebarVisibility() {
                if (window.innerWidth >= 768) { // md breakpoint in Tailwind
                    sidebar.classList.remove('-ml-64'); // Ensure sidebar is visible on desktop
                    sidebar.classList.add('ml-0');
                } else {
                    sidebar.classList.add('-ml-64'); // Hide sidebar on mobile by default
                    sidebar.classList.remove('ml-0');
                }
            }

            window.addEventListener('resize', adjustSidebarVisibility);
            adjustSidebarVisibility(); // Initial adjustment on page load
        });
    </script>
</body>
</html>