<aside id="sidebar-wrapper"
    class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out z-50">
    <div class="px-4">
        <a href="#" class="text-white text-2xl font-bold flex items-center">
            <i class="fas fa-cube text-indigo-400 mr-2"></i> BWI
        </a>
    </div>

    <nav>

        <a href="{{ route('kategori-artikels.list') }}"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white
            {{ request()->routeIs('kategori-artikels.*') ? 'bg-gray-700 text-white' : '' }}">
            <i class="fas fa-tags mr-3"></i> Kategori Artikel
        </a>
        <a href="{{ route('artikels.list') }}"
            class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition duration-200">
            <i class="fas fa-newspaper mr-3"></i> Artikel
        </a>
        <a href="{{ route('products.list') }}"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white
    {{ request()->routeIs('products.*') ? 'bg-gray-700 text-white' : '' }}">
            <i class="fas fa-box-open mr-3"></i> Produk
        </a>

           <a href="{{ route('kontak.index') }}"
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white
    {{ request()->routeIs('kontak.*') ? 'bg-gray-700 text-white' : '' }}">
            <i class="fas fa-phone mr-3"></i> Contact Us
        </a>

    </nav>
</aside>
