import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    // Shared Chart.js options for dark mode support
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#9ca3af' : '#6b7280';
    const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Read theme colors from CSS variables
    const getThemeColor = (varName, defaultHex) => {
        const value = getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
        return value || defaultHex;
    };

    const colorPrimary = getThemeColor('--color-primary', '#1E3A8A');
    const colorAccent = getThemeColor('--color-accent', '#6366F1');
    const colorPositive = getThemeColor('--color-positive', '#16A34A');
    const colorNegative = getThemeColor('--color-negative', '#DC2626');
    const colorNeutral = getThemeColor('--color-neutral', '#CA8A04');

    // 1. Sentiment Over Time (Line Chart)
    const sentimentTimeCanvas = document.getElementById('sentimentOverTimeChart');
    if (sentimentTimeCanvas) {
        const labels = JSON.parse(sentimentTimeCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(sentimentTimeCanvas.dataset.chartData || '[]');

        // Format labels to show only day/month
        const formattedLabels = labels.map(dateStr => {
            const d = new Date(dateStr);
            return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' });
        });

        new Chart(sentimentTimeCanvas, {
            type: 'line',
            data: {
                labels: formattedLabels,
                datasets: [{
                    label: 'Avg Compound Sentiment',
                    data: data,
                    borderColor: colorAccent,
                    backgroundColor: `${colorAccent}20`, // 20 hex opacity
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                    spanGaps: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => `Score: ${context.parsed.y ?? 'N/A'}`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { maxTicksLimit: 10, maxRotation: 0 }
                    },
                    y: {
                        grid: { color: gridColor, drawBorder: false },
                        suggestedMin: -1,
                        suggestedMax: 1,
                    }
                }
            }
        });
    }

    // 2. Sentiment Distribution (Doughnut)
    const sentimentDistCanvas = document.getElementById('sentimentDistributionChart');
    if (sentimentDistCanvas) {
        const labels = JSON.parse(sentimentDistCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(sentimentDistCanvas.dataset.chartData || '[]');

        new Chart(sentimentDistCanvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        colorPositive, // Positive
                        colorNeutral,  // Neutral
                        colorNegative, // Negative
                    ],
                    borderWidth: 2,
                    borderColor: isDarkMode ? '#1f2937' : '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '55%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 15, usePointStyle: true }
                    }
                }
            }
        });
    }

    // 3. Topic Distribution (Bar Chart)
    const topicCanvas = document.getElementById('topicDistributionChart');
    if (topicCanvas) {
        const labels = JSON.parse(topicCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(topicCanvas.dataset.chartData || '[]');

        new Chart(topicCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Reviews',
                    data: data,
                    backgroundColor: [
                        '#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe'
                    ],
                    borderRadius: 6,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: {
                        grid: { color: gridColor, drawBorder: false },
                        beginAtZero: true,
                    },
                    y: {
                        grid: { display: false },
                    }
                }
            }
        });
    }

    // 4. Intent Distribution (Bar Chart)
    const intentCanvas = document.getElementById('intentDistributionChart');
    if (intentCanvas) {
        const labels = JSON.parse(intentCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(intentCanvas.dataset.chartData || '[]');

        new Chart(intentCanvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Reviews',
                    data: data,
                    backgroundColor: [
                        '#f59e0b', '#10b981', '#ef4444', '#3b82f6', '#8b5cf6'
                    ],
                    borderRadius: 6,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: {
                        grid: { color: gridColor, drawBorder: false },
                        beginAtZero: true,
                    },
                    y: {
                        grid: { display: false },
                    }
                }
            }
        });
    }
});
