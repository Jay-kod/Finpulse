import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    // Shared Chart.js options for dark mode support
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#9ca3af' : '#6b7280';
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // 1. Sentiment Trends Line Chart
    const trendsCanvas = document.getElementById('sentimentTrendsChart');
    if (trendsCanvas) {
        new Chart(trendsCanvas, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [
                    {
                        label: 'OPay',
                        data: [65, 70, 68, 75, 80, 78, 85],
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'PalmPay',
                        data: [50, 45, 55, 60, 58, 62, 65],
                        borderColor: '#f59e0b', // amber-500
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Kuda',
                        data: [80, 75, 78, 72, 70, 75, 72],
                        borderColor: '#6366f1', // indigo-500
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: gridColor,
                            drawBorder: false,
                        }
                    },
                    y: {
                        grid: {
                            color: gridColor,
                            drawBorder: false,
                        },
                        min: 0,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    // 2. Sentiment Breakdown Doughnut Chart
    const breakdownCanvas = document.getElementById('sentimentBreakdownChart');
    if (breakdownCanvas) {
        new Chart(breakdownCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Positive', 'Neutral', 'Negative'],
                datasets: [{
                    data: [68, 15, 17],
                    backgroundColor: [
                        '#10b981', // emerald-500 (Positive)
                        '#9ca3af', // gray-400 (Neutral)
                        '#ef4444', // red-500 (Negative)
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
                    }
                }
            }
        });
    }
});
