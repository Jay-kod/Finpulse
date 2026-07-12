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
    if (trendsCanvas && window.sentimentTrendsData) {
        // Define a palette of nice colors for the different apps
        const colors = [
            { border: '#6366f1', bg: 'rgba(99, 102, 241, 0.1)' }, // Indigo
            { border: '#10b981', bg: 'rgba(16, 185, 129, 0.1)' }, // Emerald
            { border: '#f59e0b', bg: 'rgba(245, 158, 11, 0.1)' }, // Amber
            { border: '#ec4899', bg: 'rgba(236, 72, 153, 0.1)' }, // Pink
            { border: '#3b82f6', bg: 'rgba(59, 130, 246, 0.1)' }, // Blue
            { border: '#8b5cf6', bg: 'rgba(139, 92, 246, 0.1)' }, // Violet
        ];

        let datasets = [];
        
        // Check if data is new format (per app) or old format
        if (window.sentimentTrendsData.datasets) {
            datasets = window.sentimentTrendsData.datasets.map((ds, index) => {
                const color = colors[index % colors.length];
                return {
                    label: ds.label + ' Sentiment (%)',
                    data: ds.data.map(val => val !== null ? Math.round((val + 1) * 50) : null),
                    borderColor: color.border,
                    backgroundColor: color.bg,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: false, // Don't fill when there are multiple lines to prevent visual clutter
                    spanGaps: true,
                };
            });
        } else {
            // Fallback for old format
            datasets = [{
                label: 'Avg Sentiment (%)',
                data: window.sentimentTrendsData.data.map(val => val !== null ? Math.round((val + 1) * 50) : null),
                borderColor: colors[0].border,
                backgroundColor: colors[0].bg,
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                spanGaps: true,
            }];
        }

        new Chart(trendsCanvas, {
            type: 'line',
            data: {
                labels: window.sentimentTrendsData.labels.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
                }),
                datasets: datasets
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
    if (breakdownCanvas && window.sentimentDistributionData) {
        // Update the center text
        const centerTextEl = document.getElementById('doughnutCenterText');
        if (centerTextEl && window.positivePercentage !== undefined) {
            centerTextEl.innerText = window.positivePercentage + '%';
        }

        new Chart(breakdownCanvas, {
            type: 'doughnut',
            data: {
                labels: window.sentimentDistributionData.labels,
                datasets: [{
                    data: window.sentimentDistributionData.data,
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
