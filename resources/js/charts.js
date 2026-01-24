import Chart from 'chart.js/auto';

// Initialize all charts on page load
document.addEventListener('DOMContentLoaded', function () {
    // Income vs Expense Line Chart
    const incomeExpenseChart = document.getElementById('incomeExpenseChart');
    if (incomeExpenseChart) {
        initIncomeExpenseChart(incomeExpenseChart);
    }

    // Category Breakdown Pie Chart
    const categoryPieChart = document.getElementById('categoryPieChart');
    if (categoryPieChart) {
        initCategoryPieChart(categoryPieChart);
    }

    // Monthly Trend Chart
    const monthlyTrendChart = document.getElementById('monthlyTrendChart');
    if (monthlyTrendChart) {
        initMonthlyTrendChart(monthlyTrendChart);
    }
});

function initIncomeExpenseChart(canvas) {
    const ctx = canvas.getContext('2d');
    const chartData = JSON.parse(canvas.dataset.chartData || '{}');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels || [],
            datasets: [
                {
                    label: 'Income',
                    data: chartData.income || [],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Expense',
                    data: chartData.expense || [],
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
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
                title: {
                    display: true,
                    text: 'Income vs Expense Trend'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                        }
                    }
                }
            }
        }
    });
}

function initCategoryPieChart(canvas) {
    const ctx = canvas.getContext('2d');
    const chartData = JSON.parse(canvas.dataset.chartData || '{}');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.labels || [],
            datasets: [{
                data: chartData.data || [],
                backgroundColor: [
                    'rgb(239, 68, 68)',
                    'rgb(249, 115, 22)',
                    'rgb(234, 179, 8)',
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(147, 51, 234)',
                    'rgb(236, 72, 153)',
                    'rgb(20, 184, 166)',
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Expenses by Category'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

function initMonthlyTrendChart(canvas) {
    const ctx = canvas.getContext('2d');
    const chartData = JSON.parse(canvas.dataset.chartData || '{}');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels || [],
            datasets: [
                {
                    label: 'Income',
                    data: chartData.income || [],
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                },
                {
                    label: 'Expense',
                    data: chartData.expense || [],
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
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
                title: {
                    display: true,
                    text: 'Monthly Financial Summary'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                        }
                    }
                }
            }
        }
    });
}
