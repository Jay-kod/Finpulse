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
        new Chart(trendsCanvas, {
            type: 'line',
            data: {
                labels: window.sentimentTrendsData.labels.map(date => {
                    const d = new Date(date);
                    return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
                }),
                datasets: [
                    {
                        label: 'Avg Sentiment (%)',
                        // Convert -1 to 1 score into 0 to 100 percentage
                        data: window.sentimentTrendsData.data.map(val => val !== null ? Math.round((val + 1) * 50) : null),
                        borderColor: '#6366f1', // indigo-500
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        spanGaps: true,
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
