<header class="bg-white shadow-md py-4 px-6 flex items-center justify-between">
    <button id="menu-toggle" class="md:hidden text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <h1 class="text-2xl font-semibold text-gray-800 hidden md:block">Dashboard</h1>

    <div class="flex items-center space-x-4">
        <a href="#" class="text-gray-600 hover:text-gray-800 hidden md:block">
            <!-- <i class="fas fa-bell text-lg"></i> -->
        </a>
        <div class="relative">
            <button class="flex items-center text-gray-600 hover:text-gray-800 focus:outline-none" id="userMenuButton">
                <img src="https://ui-avatars.com/api/?name=User+Name&background=0D8ABC&color=fff&size=30" class="rounded-full mr-2" alt="User Avatar">
                <span class="hidden md:block">{{ Auth::user()->name ?? 'Nama Pengguna' }}</span>
                <i class="fas fa-chevron-down text-xs ml-2"></i>
            </button>
            <div id="userMenuDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 hidden">
                <div class="border-t border-gray-100 my-1"></div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        if (userMenuButton && userMenuDropdown) {
            userMenuButton.addEventListener('click', function() {
                userMenuDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }
    });
</script>
