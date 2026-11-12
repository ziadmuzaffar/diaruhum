/**
 * Admin Dashboard JavaScript
 * Handles interactive features and real-time updates
 */

class AdminDashboard {
    constructor() {
        this.init();
        this.setupEventListeners();
        this.startAutoRefresh();
    }

    init() {
        console.log('Admin Dashboard initialized');
        this.animateCounters();
        this.animateCharts();
    }

    setupEventListeners() {
        // Mobile sidebar toggle
        this.setupSidebarToggle();

        // Stats cards hover effects
        this.setupStatsCards();

        // Quick actions
        this.setupQuickActions();

        // Notification handling
        this.setupNotifications();
    }

    setupSidebarToggle() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

        if (openSidebar) {
            openSidebar.addEventListener('click', () => {
                sidebar?.classList.remove('translate-x-full');
                sidebarOverlay?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }

        if (closeSidebar) {
            closeSidebar.addEventListener('click', () => {
                this.closeSidebar();
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                this.closeSidebar();
            });
        }

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeSidebar();
            }
        });

        // Auto-hide sidebar on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                this.closeSidebar();
            }
        });
    }

    closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebar?.classList.add('translate-x-full');
        sidebarOverlay?.classList.add('hidden');
        document.body.style.overflow = '';
    }

    setupStatsCards() {
        const statsCards = document.querySelectorAll('.stats-card');

        statsCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    }

    setupQuickActions() {
        const quickActions = document.querySelectorAll('.quick-action');

        quickActions.forEach(action => {
            action.addEventListener('click', (e) => {
                // Add ripple effect
                this.createRipple(e, action);
            });
        });
    }

    createRipple(event, element) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');

        element.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    setupNotifications() {
        // Simulate real-time notifications
        setInterval(() => {
            this.updateNotificationBadge();
        }, 30000);
    }

    updateNotificationBadge() {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            // Simulate random notification updates
            if (Math.random() > 0.7) {
                badge.textContent = currentCount + 1;
                badge.classList.add('pulse-animation');
                setTimeout(() => {
                    badge.classList.remove('pulse-animation');
                }, 2000);
            }
        }
    }

    animateCounters() {
        const counters = document.querySelectorAll('[data-counter]');

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-counter'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current).toLocaleString();
            }, 16);
        });
    }

    animateCharts() {
        const chartBars = document.querySelectorAll('.chart-bar');

        chartBars.forEach((bar, index) => {
            setTimeout(() => {
                bar.style.transform = 'scaleX(1)';
                bar.style.transformOrigin = 'right';
            }, index * 100);
        });
    }

    startAutoRefresh() {
        // Auto-refresh dashboard data every 5 minutes
        setInterval(() => {
            this.refreshDashboardData();
        }, 300000);
    }

    async refreshDashboardData() {
        try {
            const response = await fetch('/admin/dashboard/refresh', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateDashboardStats(data);
                console.log('Dashboard data refreshed');
            }
        } catch (error) {
            console.error('Failed to refresh dashboard data:', error);
        }
    }

    updateDashboardStats(data) {
        // Update stats cards with new data
        Object.keys(data).forEach(key => {
            const element = document.querySelector(`[data-stat="${key}"]`);
            if (element) {
                element.textContent = data[key];
                element.classList.add('fade-in');
                setTimeout(() => {
                    element.classList.remove('fade-in');
                }, 500);
            }
        });
    }

    // Utility methods
    formatNumber(num) {
        return new Intl.NumberFormat('ar-SA').format(num);
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('ar-SA', {
            style: 'currency',
            currency: 'SAR'
        }).format(amount);
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 left-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .chart-bar {
        transform: scaleX(0);
        transition: transform 0.8s ease-out;
    }
`;
document.head.appendChild(style);
