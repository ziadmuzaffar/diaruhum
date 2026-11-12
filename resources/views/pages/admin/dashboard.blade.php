@extends('layouts.admin.app')

@section('title', 'لوحة التحكم الرئيسية')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <!-- Key Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.stats-card
            title="إجمالي المستخدمين"
            :value="number_format($stats['total_users'])"
            icon="users"
            color="blue"
            :growth="$stats['user_growth']"
        />

        <x-admin.stats-card
            title="إجمالي الطلبات"
            :value="number_format($stats['total_orders'])"
            icon="shopping-cart"
            color="green"
            :growth="$stats['order_growth']"
        />

        <x-admin.stats-card
            title="إجمالي الإيرادات"
            :value="'$' . number_format($stats['total_revenue'], 2)"
            icon="dollar-sign"
            color="yellow"
            :growth="$stats['revenue_growth']"
        />

        <x-admin.stats-card
            title="التوصيلات النشطة"
            :value="number_format($stats['active_deliveries'])"
            icon="shipping-fast"
            color="purple"
            :subtitle="$stats['available_drivers'] . ' سائق متاح'"
        />
    </div>

    <!-- Secondary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Products -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">المنتجات</h3>
                <i class="fas fa-box text-gray-400"></i>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">إجمالي المنتجات</span>
                    <span class="font-semibold">{{ number_format($stats['total_products']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">منتجات نشطة</span>
                    <span class="font-semibold text-green-600">{{ number_format($stats['active_products']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">منتجات غير متوفرة</span>
                    <span class="font-semibold text-red-600">{{ number_format($stats['out_of_stock_products']) }}</span>
                </div>
            </div>
        </div>

        <!-- Support Tickets -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">تذاكر الدعم</h3>
                <i class="fas fa-headset text-gray-400"></i>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">تذاكر مفتوحة</span>
                    <span class="font-semibold text-orange-600">{{ number_format($stats['open_tickets']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">قيد المعالجة</span>
                    <span class="font-semibold text-blue-600">{{ number_format($stats['pending_tickets']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">تم الحل</span>
                    <span class="font-semibold text-green-600">{{ number_format($stats['resolved_tickets']) }}</span>
                </div>
            </div>
        </div>

        <!-- Real Estate -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">العقارات</h3>
                <i class="fas fa-home text-gray-400"></i>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">عقارات متاحة</span>
                    <span class="font-semibold">{{ number_format($stats['available_properties']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">استفسارات جديدة</span>
                    <span class="font-semibold text-blue-600">{{ number_format($stats['new_inquiries']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">عقارات مباعة</span>
                    <span class="font-semibold text-green-600">{{ number_format($stats['sold_properties']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">إحصائيات المبيعات</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1">
                    <option>آخر 7 أيام</option>
                    <option>آخر 30 يوم</option>
                    <option>آخر 3 أشهر</option>
                </select>
            </div>

            <!-- Simple Chart Representation -->
            <div class="space-y-4">
                @foreach($stats['sales_chart_data'] as $day)
                    <x-admin.chart-bar
                        :label="$day['day']"
                        :value="'$' . number_format($day['revenue'], 0)"
                        :percentage="$day['percentage']"
                    />
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">النشاطات الأخيرة</h3>
                <a href="#" class="text-sm text-primary-600 hover:text-primary-700">عرض الكل</a>
            </div>

            <div class="space-y-4">
                <div class="flex items-start space-x-3 space-x-reverse">
                    <div class="bg-green-100 p-2 rounded-full">
                        <i class="fas fa-shopping-cart text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">طلب جديد #1234</p>
                        <p class="text-sm text-gray-500">من العميل أحمد محمد</p>
                        <p class="text-xs text-gray-400">منذ 5 دقائق</p>
                    </div>
                    <span class="text-sm font-semibold text-green-600">$125.00</span>
                </div>

                <div class="flex items-start space-x-3 space-x-reverse">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">عميل جديد</p>
                        <p class="text-sm text-gray-500">سارة أحمد انضمت للمنصة</p>
                        <p class="text-xs text-gray-400">منذ 15 دقيقة</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 space-x-reverse">
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <i class="fas fa-truck text-yellow-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">تم تسليم الطلب</p>
                        <p class="text-sm text-gray-500">الطلب #1230 تم تسليمه بنجاح</p>
                        <p class="text-xs text-gray-400">منذ 30 دقيقة</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 space-x-reverse">
                    <div class="bg-purple-100 p-2 rounded-full">
                        <i class="fas fa-home text-purple-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">استفسار عقاري جديد</p>
                        <p class="text-sm text-gray-500">استفسار عن شقة في الرياض</p>
                        <p class="text-xs text-gray-400">منذ ساعة</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 space-x-reverse">
                    <div class="bg-red-100 p-2 rounded-full">
                        <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">تذكرة دعم جديدة</p>
                        <p class="text-sm text-gray-500">مشكلة في الدفع - عالية الأولوية</p>
                        <p class="text-xs text-gray-400">منذ ساعتين</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">إجراءات سريعة</h3>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <x-admin.quick-action
                icon="plus"
                title="إضافة منتج"
                color="blue"
                href="#"
            />

            <x-admin.quick-action
                icon="user-plus"
                title="إضافة عميل"
                color="green"
                href="#"
            />

            <x-admin.quick-action
                icon="file-invoice"
                title="إنشاء فاتورة"
                color="yellow"
                href="#"
            />

            <x-admin.quick-action
                icon="home"
                title="إضافة عقار"
                color="purple"
                href="#"
            />

            <x-admin.quick-action
                icon="headset"
                title="دعم فني"
                color="red"
                href="#"
            />

            <x-admin.quick-action
                icon="chart-bar"
                title="التقارير"
                color="indigo"
                href="#"
            />
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any dashboard-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh data every 30 seconds
        setInterval(function() {
            // You can add AJAX calls here to refresh dashboard data
            console.log('Dashboard data refresh...');
        }, 30000);
    });
</script>
@endpush
