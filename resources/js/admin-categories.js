/**
 * Admin Categories Management JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize category management features
    initCategorySearch();
    initCategoryFilters();
    initCategoryOrdering();
    initImagePreview();
    initSlugGeneration();
    initFormValidation();
});

/**
 * Initialize category search functionality
 */
function initCategorySearch() {
    const searchInput = document.querySelector('input[name="search"]');
    if (!searchInput) return;

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length >= 2 || this.value.length === 0) {
                this.form.submit();
            }
        }, 500);
    });
}

/**
 * Initialize category filters
 */
function initCategoryFilters() {
    const typeFilter = document.querySelector('select[name="type"]');
    const statusFilter = document.querySelector('select[name="status"]');

    if (typeFilter) {
        typeFilter.addEventListener('change', function() {
            this.form.submit();
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            this.form.submit();
        });
    }
}

/**
 * Initialize drag and drop ordering for categories
 */
function initCategoryOrdering() {
    const categoryTable = document.querySelector('#categories-table tbody');
    if (!categoryTable) return;

    // Add sortable functionality if SortableJS is available
    if (typeof Sortable !== 'undefined') {
        new Sortable(categoryTable, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                updateCategoryOrder();
            }
        });
    }
}

/**
 * Update category order via AJAX
 */
function updateCategoryOrder() {
    const rows = document.querySelectorAll('#categories-table tbody tr');
    const categories = [];

    rows.forEach((row, index) => {
        const categoryId = row.dataset.categoryId;
        if (categoryId) {
            categories.push({
                id: parseInt(categoryId),
                sort_order: index + 1
            });
        }
    });

    fetch('/admin/categories/update-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ categories: categories })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم تحديث ترتيب الفئات بنجاح', 'success');
        } else {
            showNotification('حدث خطأ أثناء تحديث الترتيب', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ أثناء تحديث الترتيب', 'error');
    });
}

/**
 * Initialize image preview functionality
 */
function initImagePreview() {
    const imageInput = document.querySelector('input[type="file"][name="image"]');
    if (!imageInput) return;

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            showNotification('يرجى اختيار ملف صورة صحيح', 'error');
            this.value = '';
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showNotification('حجم الصورة يجب أن يكون أقل من 2 ميجابايت', 'error');
            this.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            showImagePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    });
}

/**
 * Show image preview
 */
function showImagePreview(src) {
    let preview = document.querySelector('#image-preview');

    if (!preview) {
        preview = document.createElement('div');
        preview.id = 'image-preview';
        preview.className = 'mt-2';
        document.querySelector('input[name="image"]').parentNode.appendChild(preview);
    }

    preview.innerHTML = `
        <div class="relative inline-block">
            <img src="${src}" alt="معاينة الصورة" class="w-32 h-32 object-cover rounded-lg border">
            <button type="button" onclick="removeImagePreview()"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                ×
            </button>
        </div>
    `;
}

/**
 * Remove image preview
 */
function removeImagePreview() {
    const preview = document.querySelector('#image-preview');
    const input = document.querySelector('input[name="image"]');

    if (preview) preview.remove();
    if (input) input.value = '';
}

/**
 * Initialize automatic slug generation
 */
function initSlugGeneration() {
    const nameArInput = document.querySelector('input[name="name_ar"]');
    const slugInput = document.querySelector('input[name="slug"]');

    if (!nameArInput || !slugInput) return;

    nameArInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = generateSlug(this.value);
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    slugInput.addEventListener('input', function() {
        if (this.value) {
            delete this.dataset.autoGenerated;
        }
    });
}

/**
 * Generate URL-friendly slug from Arabic text
 */
function generateSlug(text) {
    return text
        .toLowerCase()
        .trim()
        .replace(/[أإآ]/g, 'ا')
        .replace(/[ة]/g, 'ه')
        .replace(/[ى]/g, 'ي')
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]/g, '')
        .replace(/\-+/g, '-')
        .replace(/^-|-$/g, '');
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Validate form fields
 */
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'هذا الحقل مطلوب');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });

    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    clearFieldError(field);

    field.classList.add('border-red-500');

    const error = document.createElement('p');
    error.className = 'mt-1 text-sm text-red-600 field-error';
    error.textContent = message;

    field.parentNode.appendChild(error);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.classList.remove('border-red-500');

    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

/**
 * Show notification message
 */
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();

    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${getNotificationClasses(type)}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);

    // Add click to dismiss
    notification.addEventListener('click', () => {
        notification.remove();
    });
}

/**
 * Get notification CSS classes based on type
 */
function getNotificationClasses(type) {
    const classes = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };

    return classes[type] || classes.info;
}

/**
 * Get categories by type (AJAX helper)
 */
function getCategoriesByType(type, parentId = null) {
    const params = new URLSearchParams({ type });
    if (parentId) params.append('parent_id', parentId);

    return fetch(`/admin/categories/by-type?${params}`)
        .then(response => response.json())
        .catch(error => {
            console.error('Error fetching categories:', error);
            return [];
        });
}

/**
 * Populate select element with categories
 */
function populateSelect(selectElement, categories, selectedValue = null) {
    selectElement.innerHTML = '<option value="">اختر الفئة</option>';

    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name_ar;

        if (selectedValue && category.id == selectedValue) {
            option.selected = true;
        }

        selectElement.appendChild(option);
    });
}

// Export functions for global use
window.CategoryManager = {
    getCategoriesByType,
    populateSelect,
    showNotification,
    generateSlug
};
