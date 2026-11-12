# قاعدة بيانات ديارهم للهندسة - Database Schema

## نظرة عامة
قاعدة بيانات شاملة لمنصة ديارهم للهندسة تدعم جميع الخدمات الهندسية من التصميم والعمالة والمواد واللوجستيات والعقارات.

## هيكل الجداول

### 1. إدارة المستخدمين (User Management) - جداول منفصلة
- **users**: المستخدمون الأساسيون (بيانات مشتركة)
- **admins**: الإداريون والمشرفون
- **customers**: العملاء والمقاولون
- **merchants**: التجار والموردون
- **drivers**: السائقون ومقدمو خدمات النقل
- **staff**: الموظفون والمهندسون
- **activity_logs**: سجل أنشطة المستخدمين

### 2. الخدمات والمنتجات (Services & Products)
- **categories**: تصنيفات الخدمات والمنتجات
- **services**: الخدمات المتاحة (تصميم، عمالة، لوجستيات، عقارات)
- **products**: المنتجات والمواد (مواد البناء، أدوات، إلخ)
- **inventory**: إدارة المخزون للمنتجات

### 3. المشاريع والطلبات (Projects & Orders)
- **projects**: المشاريع الهندسية
- **orders**: طلبات العملاء
- **order_items**: عناصر الطلبات
- **quotations**: عروض الأسعار
- **quotation_items**: عناصر عروض الأسعار

### 4. التوصيل والنقل (Delivery & Logistics)
- **deliveries**: عمليات التوصيل
- **gps_tracking**: تتبع GPS للسائقين
- **staff_assignments**: تكليفات الموظفين

### 5. المدفوعات والفواتير (Payments & Invoicing)
- **payments**: المدفوعات
- **invoices**: الفواتير
- **coupons**: كوبونات الخصم
- **coupon_usages**: استخدام الكوبونات

### 6. العقارات (Real Estate)
- **real_estate_properties**: العقارات
- **property_inquiries**: استفسارات العقارات

### 7. التقييمات والدعم (Reviews & Support)
- **reviews_ratings**: تقييمات العملاء
- **support_tickets**: تذاكر الدعم الفني
- **support_messages**: رسائل الدعم الفني

### 8. النظام والإشعارات (System & Notifications)
- **notifications**: الإشعارات
- **system_settings**: إعدادات النظام
- **file_uploads**: رفع الملفات

## العلاقات الرئيسية

### علاقات المستخدمين المنفصلة
```sql
users (1) -> (0..1) admins
users (1) -> (0..1) customers  
users (1) -> (0..1) merchants
users (1) -> (0..1) drivers
users (1) -> (0..1) staff

customers (1) -> (*) orders
customers (1) -> (*) projects
customers (1) -> (*) quotations
customers (1) -> (*) support_tickets

merchants (1) -> (*) products
merchants (1) -> (*) inventory

drivers (1) -> (*) deliveries
drivers (1) -> (*) gps_tracking

staff (1) -> (*) staff_assignments
staff (1) -> (*) projects (as assigned_staff)
staff (1) -> (*) quotations (as created_by)
```

### علاقات الطلبات والمشاريع
```sql
orders (1) -> (*) order_items
orders (1) -> (1) deliveries
orders (1) -> (*) payments
orders (1) -> (1) invoices
projects (1) -> (*) orders
quotations (1) -> (*) quotation_items
quotations (1) -> (0..1) orders
```

### علاقات المنتجات والخدمات
```sql
categories (1) -> (*) services
categories (1) -> (*) products
products (1) -> (1) inventory
services/products -> (*) order_items
```

## الفهارس المهمة

### فهارس الأداء
- `users`: status, email
- `admins`: admin_level, department, user_id
- `customers`: customer_type, city, user_id
- `merchants`: business_type, city, is_verified, user_id
- `drivers`: current_status, city, is_verified, user_id
- `staff`: staff_type, department, current_status, user_id
- `orders`: customer_id, status, created_at
- `deliveries`: driver_id, status, scheduled_delivery
- `products`: merchant_id, category_id, stock_quantity
- `gps_tracking`: delivery_id, driver_id, recorded_at

### فهارس البحث
- `real_estate_properties`: type, city, price, area_sqm
- `services`: type, category_id, is_active
- `support_tickets`: customer_id, status, category

## أنواع البيانات المهمة

### Enums المستخدمة
```php
// حالة المستخدمين
'user_status' => ['active', 'inactive', 'suspended']

// أدوار الإداريين
'admin_level' => ['super_admin', 'admin', 'moderator']

// أنواع العملاء
'customer_type' => ['individual', 'company', 'contractor', 'developer']

// أنواع التجار
'business_type' => ['supplier', 'manufacturer', 'distributor', 'retailer', 'service_provider']

// حالات السائقين
'driver_status' => ['available', 'busy', 'offline', 'on_break']

// أنواع الموظفين
'staff_type' => ['engineer', 'technician', 'supervisor', 'coordinator', 'specialist']

// حالات الطلبات
'order_status' => ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled']

// أنواع الخدمات
'service_type' => ['design', 'labor', 'materials', 'logistics', 'realestate']

// حالات التوصيل
'delivery_status' => ['pending', 'assigned', 'picked_up', 'in_transit', 'delivered']
```

### JSON Fields
- `admins.permissions`: صلاحيات الإداري
- `customers.emergency_contact`: معلومات الطوارئ
- `merchants.specializations`: تخصصات التاجر
- `drivers.vehicle_info`: معلومات المركبة
- `staff.skills`: المهارات التقنية
- `products.specifications`: مواصفات المنتج
- `orders.metadata`: بيانات إضافية
- `gps_tracking.metadata`: بيانات GPS إضافية
- `system_settings.validation_rules`: قواعد التحقق

## الأمان والصلاحيات

### Foreign Key Constraints
- جميع الجداول تستخدم `constrained()` مع `onDelete('cascade')` أو `onDelete('set null')`
- حماية البيانات من الحذف العرضي

### Data Integrity
- استخدام `unique()` للحقول المهمة مثل email, order_number, invoice_number
- استخدام `nullable()` للحقول الاختيارية
- تحديد `default()` values للحقول المطلوبة

## Migration Commands

```bash
# تشغيل جميع الـ migrations
php artisan migrate

# التراجع عن آخر batch
php artisan migrate:rollback

# إعادة تشغيل جميع الـ migrations
php artisan migrate:refresh

# إنشاء قاعدة البيانات مع البيانات الأولية
php artisan migrate --seed
```

## ملاحظات التطوير

1. **الفصل بين الجداول**: تم فصل جدول المستخدمين عن باقي الجداول كما طُلب
2. **دعم اللغة العربية**: جميع الحقول النصية تدعم UTF-8 والمحتوى العربي
3. **المرونة**: استخدام JSON fields للبيانات المتغيرة
4. **الأداء**: فهارس محسنة للاستعلامات الشائعة
5. **التوسع**: هيكل قابل للتوسع لإضافة ميزات جديدة

## البيانات الأولية المقترحة

يُنصح بإنشاء Seeders للبيانات التالية:
- المستخدم الإداري الأول
- التصنيفات الأساسية للخدمات
- إعدادات النظام الافتراضية
- الخدمات الأساسية لكل قسم
- المناطق والمدن السعودية
