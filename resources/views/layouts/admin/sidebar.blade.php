<!-- Dashboard -->
<div class="mb-2">
    <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
        <i class="fas fa-tachometer-alt ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">الرئيسية</span>
    </a>
</div>

<!-- Users Management -->
<div class="mb-6">
    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">إدارة المستخدمين</h3>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-users ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">العملاء</span>
        <span class="mr-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">1,234</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-store ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">التجار</span>
        <span class="mr-auto bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">89</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-truck ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">السائقين</span>
        <span class="mr-auto bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">45</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-user-tie ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">الموظفين</span>
        <span class="mr-auto bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">23</span>
    </a>
</div>

<!-- Business Management -->
<div class="mb-6">
    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">إدارة الأعمال</h3>

    <div x-data="{ open: false }" class="mb-1">
        <button @click="open = !open" class="w-full flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group">
            <i class="fas fa-shopping-cart ml-3 text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">التجارة الإلكترونية</span>
            <i class="fas fa-chevron-down mr-auto transition-transform" :class="{ 'rotate-180': open }"></i>
        </button>
        <div x-show="open" x-transition class="mr-6 mt-2 space-y-1">
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-box ml-2"></i>
                المنتجات
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-tags ml-2"></i>
                الفئات
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-clipboard-list ml-2"></i>
                الطلبات
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-warehouse ml-2"></i>
                المخزون
            </a>
        </div>
    </div>

    <div x-data="{ open: false }" class="mb-1">
        <button @click="open = !open" class="w-full flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group">
            <i class="fas fa-concierge-bell ml-3 text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">إدارة الخدمات</span>
            <i class="fas fa-chevron-down mr-auto transition-transform" :class="{ 'rotate-180': open }"></i>
        </button>
        <div x-show="open" x-transition class="mr-6 mt-2 space-y-1">
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-project-diagram ml-2"></i>
                المشاريع
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-file-invoice ml-2"></i>
                عروض الأسعار
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-user-check ml-2"></i>
                تعيين الموظفين
            </a>
        </div>
    </div>

    <div x-data="{ open: false }" class="mb-1">
        <button @click="open = !open" class="w-full flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group">
            <i class="fas fa-home ml-3 text-lg group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">العقارات</span>
            <i class="fas fa-chevron-down mr-auto transition-transform" :class="{ 'rotate-180': open }"></i>
        </button>
        <div x-show="open" x-transition class="mr-6 mt-2 space-y-1">
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-building ml-2"></i>
                العقارات
            </a>
            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-800">
                <i class="fas fa-question-circle ml-2"></i>
                الاستفسارات
            </a>
        </div>
    </div>
</div>

<!-- Financial Management -->
<div class="mb-6">
    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">الإدارة المالية</h3>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-credit-card ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">المدفوعات</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-file-invoice-dollar ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">الفواتير</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-percentage ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">الكوبونات</span>
    </a>
</div>

<!-- Operations -->
<div class="mb-6">
    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">العمليات</h3>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-shipping-fast ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">التوصيل</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-map-marker-alt ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">تتبع GPS</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-headset ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">الدعم الفني</span>
    </a>
</div>

<!-- System -->
<div class="mb-6">
    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">النظام</h3>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-bell ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">الإشعارات</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-history ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">سجل الأنشطة</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-star ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">التقييمات</span>
    </a>

    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-all group mb-1">
        <i class="fas fa-cog ml-3 text-lg group-hover:scale-110 transition-transform"></i>
        <span class="font-medium">إعدادات النظام</span>
    </a>
</div>
