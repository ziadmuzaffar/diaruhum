/**
 * Dashboard Utility Functions
 * Common utilities for dashboard functionality
 */

export class DashboardUtils {
    /**
     * Format numbers with Arabic locale
     */
    static formatNumber(num) {
        return new Intl.NumberFormat('ar-SA').format(num);
    }

    /**
     * Format currency with Saudi Riyal
     */
    static formatCurrency(amount) {
        return new Intl.NumberFormat('ar-SA', {
            style: 'currency',
            currency: 'SAR'
        }).format(amount);
    }

    /**
     * Format date with Arabic locale
     */
    static formatDate(date) {
        return new Intl.DateTimeFormat('ar-SA', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    }

    /**
     * Calculate percentage change
     */
    static calculatePercentageChange(current, previous) {
        if (previous === 0) return current > 0 ? 100 : 0;
        return Math.round(((current - previous) / previous) * 100 * 10) / 10;
    }

    /**
     * Debounce function calls
     */
    static debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Throttle function calls
     */
    static throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Generate random color
     */
    static generateColor() {
        const colors = [
            'blue', 'green', 'yellow', 'purple', 'red', 'indigo', 'pink', 'gray'
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    /**
     * Animate counter
     */
    static animateCounter(element, target, duration = 2000) {
        const start = parseInt(element.textContent) || 0;
        const increment = (target - start) / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= target) || (increment < 0 && current <= target)) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString('ar-SA');
        }, 16);
    }

    /**
     * Show loading state
     */
    static showLoading(element) {
        element.classList.add('opacity-50', 'pointer-events-none');
        const loader = document.createElement('div');
        loader.className = 'absolute inset-0 flex items-center justify-center';
        loader.innerHTML = '<i class="fas fa-spinner fa-spin text-2xl text-primary-600"></i>';
        element.style.position = 'relative';
        element.appendChild(loader);
    }

    /**
     * Hide loading state
     */
    static hideLoading(element) {
        element.classList.remove('opacity-50', 'pointer-events-none');
        const loader = element.querySelector('.absolute.inset-0');
        if (loader) loader.remove();
    }

    /**
     * Copy text to clipboard
     */
    static async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            return true;
        }
    }

    /**
     * Validate email
     */
    static isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Validate phone number (Saudi format)
     */
    static isValidSaudiPhone(phone) {
        const re = /^(\+966|966|0)?5[0-9]{8}$/;
        return re.test(phone.replace(/\s/g, ''));
    }

    /**
     * Generate UUID
     */
    static generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    /**
     * Storage helpers
     */
    static storage = {
        set(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify(value));
                return true;
            } catch (e) {
                console.error('Storage set error:', e);
                return false;
            }
        },

        get(key, defaultValue = null) {
            try {
                const item = localStorage.getItem(key);
                return item ? JSON.parse(item) : defaultValue;
            } catch (e) {
                console.error('Storage get error:', e);
                return defaultValue;
            }
        },

        remove(key) {
            try {
                localStorage.removeItem(key);
                return true;
            } catch (e) {
                console.error('Storage remove error:', e);
                return false;
            }
        },

        clear() {
            try {
                localStorage.clear();
                return true;
            } catch (e) {
                console.error('Storage clear error:', e);
                return false;
            }
        }
    };

    /**
     * API helpers
     */
    static api = {
        async get(url, options = {}) {
            return this.request(url, { ...options, method: 'GET' });
        },

        async post(url, data, options = {}) {
            return this.request(url, {
                ...options,
                method: 'POST',
                body: JSON.stringify(data)
            });
        },

        async put(url, data, options = {}) {
            return this.request(url, {
                ...options,
                method: 'PUT',
                body: JSON.stringify(data)
            });
        },

        async delete(url, options = {}) {
            return this.request(url, { ...options, method: 'DELETE' });
        },

        async request(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            };

            try {
                const response = await fetch(url, { ...defaultOptions, ...options });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return await response.json();
            } catch (error) {
                console.error('API request error:', error);
                throw error;
            }
        }
    };
}
