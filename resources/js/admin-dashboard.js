import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    const data = window.adminDashboardData;
    if (!data) return;

    // Common chart options
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#9ca3af' : '#6b7280';
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Review Growth Line Chart
    const growthCtx = document.getElementById('adminGrowthChart');
    if (growthCtx) {
        // Data comes in newest-first (from DB query), so reverse it for chronological display (left-to-right)
        const chronologicalData = [...data.reviewGrowth].reverse();
        
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: chronologicalData.map(d => d.month),
                datasets: [{
                    label: 'Reviews Processed',
                    data: chronologicalData.map(d => d.count),
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#fff',
                        titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                        bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                        borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 4,
                        usePointStyle: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false,
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    }

    // Roles Distribution Doughnut Chart
    const rolesCtx = document.getElementById('adminRolesChart');
    if (rolesCtx) {
        // Extract labels and data from roleCounts object
        const labels = Object.keys(data.roleCounts);
        const values = Object.values(data.roleCounts);
        
        new Chart(rolesCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#3b82f6', // primary/blue
                        '#10b981', // success/emerald
                        '#ef4444', // danger/red
                        '#f59e0b', // warning/amber
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#fff',
                        titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                        bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                        borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        usePointStyle: true,
                    }
                }
            }
        });
    }
});
