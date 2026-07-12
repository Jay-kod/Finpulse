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

    // Helper to render empty state
    const renderEmptyState = (canvasElement, message) => {
        const parent = canvasElement.parentElement;
        canvasElement.style.display = 'none';
        
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'absolute inset-0 flex flex-col items-center justify-center bg-gray-50/50 dark:bg-gray-800/50 rounded-xl';
        emptyDiv.innerHTML = `
            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">${message}</span>
        `;
        parent.appendChild(emptyDiv);
    };

    // 1. Sentiment Over Time (Line Chart)
    const sentimentTimeCanvas = document.getElementById('sentimentOverTimeChart');
    if (sentimentTimeCanvas) {
        const labels = JSON.parse(sentimentTimeCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(sentimentTimeCanvas.dataset.chartData || '[]');

        if (labels.length === 0 || data.length === 0) {
            renderEmptyState(sentimentTimeCanvas, 'No sentiment data available');
        } else {
            // Format labels to show only day/month
            const formattedLabels = labels.map(dateStr => {
                const d = new Date(dateStr);
                return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' });
            });

            const ctx = sentimentTimeCanvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, `${colorAccent}60`);
            gradient.addColorStop(1, `${colorAccent}05`);

            new Chart(sentimentTimeCanvas, {
                type: 'line',
                data: {
                    labels: formattedLabels,
                    datasets: [{
                        label: 'Avg Compound Sentiment',
                        data: data,
                        borderColor: colorAccent,
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#ffffff',
                        pointHoverBorderColor: colorAccent,
                        pointHoverBorderWidth: 2,
                        spanGaps: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: isDarkMode ? '#f9fafb' : '#111827',
                            bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                            borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: (context) => `Score: ${context.parsed.y ?? 'N/A'}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { maxTicksLimit: 8, maxRotation: 0, font: { weight: 500 } }
                        },
                        y: {
                            grid: { color: gridColor, drawBorder: false, borderDash: [5, 5] },
                            suggestedMin: -1,
                            suggestedMax: 1,
                            ticks: { font: { weight: 500 } }
                        }
                    }
                }
            });
        }
    }

    // 2. Sentiment Distribution (Doughnut)
    const sentimentDistCanvas = document.getElementById('sentimentDistributionChart');
    if (sentimentDistCanvas) {
        const labels = JSON.parse(sentimentDistCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(sentimentDistCanvas.dataset.chartData || '[]');

        if (labels.length === 0 || data.reduce((a,b) => a+b, 0) === 0) {
            renderEmptyState(sentimentDistCanvas, 'No distribution data');
        } else {
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
                        borderWidth: 4,
                        borderColor: isDarkMode ? '#1f2937' : '#ffffff',
                        hoverOffset: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20, usePointStyle: true, font: { weight: 500 } }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: isDarkMode ? '#f9fafb' : '#111827',
                            bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                            borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 12
                        }
                    }
                }
            });
        }
    }

    // 3. Topic Distribution (Bar Chart)
    const topicCanvas = document.getElementById('topicDistributionChart');
    if (topicCanvas) {
        const labels = JSON.parse(topicCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(topicCanvas.dataset.chartData || '[]');

        if (labels.length === 0 || data.length === 0) {
            renderEmptyState(topicCanvas, 'No topic data available');
        } else {
            const ctx = topicCanvas.getContext('2d');
            const gradient = ctx.createLinearGradient(200, 0, 0, 0);
            gradient.addColorStop(0, '#6366f1');
            gradient.addColorStop(1, '#a855f7');

            new Chart(topicCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Reviews',
                        data: data,
                        backgroundColor: gradient,
                        borderRadius: 8,
                        barPercentage: 0.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: isDarkMode ? '#f9fafb' : '#111827',
                            bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                            borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 12
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor, drawBorder: false, borderDash: [5, 5] },
                            beginAtZero: true,
                            ticks: { font: { weight: 500 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { weight: 600 } }
                        }
                    }
                }
            });
        }
    }

    // 4. Intent Distribution (Bar Chart)
    const intentCanvas = document.getElementById('intentDistributionChart');
    if (intentCanvas) {
        const labels = JSON.parse(intentCanvas.dataset.chartLabels || '[]');
        const data = JSON.parse(intentCanvas.dataset.chartData || '[]');

        if (labels.length === 0 || data.length === 0) {
            renderEmptyState(intentCanvas, 'No intent data available');
        } else {
            const ctx = intentCanvas.getContext('2d');
            const gradient = ctx.createLinearGradient(200, 0, 0, 0);
            gradient.addColorStop(0, '#3b82f6');
            gradient.addColorStop(1, '#0ea5e9');

            new Chart(intentCanvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Reviews',
                        data: data,
                        backgroundColor: gradient,
                        borderRadius: 8,
                        barPercentage: 0.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                            titleColor: isDarkMode ? '#f9fafb' : '#111827',
                            bodyColor: isDarkMode ? '#d1d5db' : '#4b5563',
                            borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            padding: 12
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor, drawBorder: false, borderDash: [5, 5] },
                            beginAtZero: true,
                            ticks: { font: { weight: 500 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { weight: 600 } }
                        }
                    }
                }
            });
        }
    }
});
