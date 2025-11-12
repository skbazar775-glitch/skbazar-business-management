<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Sales Performance Analytics</h2>
        <div class="flex space-x-2">
            <button id="monthlyBtn" class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">Monthly</button>
            <button id="quarterlyBtn" class="px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Quarterly</button>
            <button id="yearlyBtn" class="px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Yearly</button>
            <button id="dailyBtn" class="px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Daily</button>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="h-80 relative">
        <canvas id="salesChart"></canvas>
    </div>
    
    <!-- Performance Metrics -->
    <div class="mt-6 grid grid-cols-3 gap-4 text-center">
        <div class="bg-blue-50 p-3 rounded-lg">
            <p class="text-sm text-gray-500">Current Period</p>
            <p id="currentSalesDisplay" class="font-medium text-lg">₹0</p>
            <p id="currentPeriodText" class="text-xs mt-1 text-blue-500">Current Month</p>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-sm text-gray-500">Last Period</p>
            <p id="lastPeriodDisplay" class="font-medium text-lg">₹0</p>
            <p id="lastPeriodText" class="text-xs mt-1 text-gray-400">Previous Month</p>
        </div>
        <div class="bg-green-50 p-3 rounded-lg">
            <p class="text-sm text-gray-500">Growth</p>
            <p id="growthDisplay" class="font-medium text-lg text-green-600">0%</p>
            <p id="growthText" class="text-xs mt-1 text-green-500">Compared to Last Period</p>
        </div>
    </div>
</div>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize with default view
    let currentView = 'monthly';

    // Dynamic data from Laravel
    const monthlyData = {
        labels: @json($monthlyLabels),
        sales: @json($monthlySalesData)
    };
    
    const quarterlyData = {
        labels: @json($quarterlyLabels),
        sales: @json($quarterlySalesData)
    };
    
    const yearlyData = {
        labels: @json($yearlyLabels),
        sales: @json($yearlySalesData)
    };
    
    const dailyData = {
        labels: @json($dailyLabels),
        sales: @json($dailySalesData)
    };
    
    // Initialize chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    let salesChart = new Chart(ctx, {
        type: 'bar',
        data: getChartData('monthly'),
        options: getChartOptions('monthly')
    });
    
    // Update chart based on view
    function updateChart(view) {
        currentView = view;
        salesChart.data = getChartData(view);
        salesChart.options = getChartOptions(view);
        salesChart.update();
        updateMetrics(view);
    }
    
    // Get chart data for view
    function getChartData(view) {
        let data;
        switch(view) {
            case 'quarterly': data = quarterlyData; break;
            case 'yearly': data = yearlyData; break;
            case 'daily': data = dailyData; break;
            default: data = monthlyData;
        }
        
        return {
            labels: data.labels,
            datasets: [
                {
                    label: 'Actual Sales',
                    data: data.sales,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false,
                }
            ]
        };
    }
    
    // Get chart options for view
    function getChartOptions(view) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 6,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += '₹' + context.parsed.y.toLocaleString('en-IN');
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString('en-IN');
                        }
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(229, 231, 235, 1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            }
        };
    }
    
    // Update metrics display
    function updateMetrics(view) {
        let currentSales, lastPeriodSales, periodText, lastPeriodText;
        
        switch(view) {
            case 'quarterly':
                currentSales = quarterlyData.sales[quarterlyData.sales.length - 1] || 0;
                lastPeriodSales = quarterlyData.sales[quarterlyData.sales.length - 2] || 0;
                periodText = 'Current Quarter';
                lastPeriodText = 'Previous Quarter';
                break;
            case 'yearly':
                currentSales = yearlyData.sales[yearlyData.sales.length - 1] || 0;
                lastPeriodSales = yearlyData.sales[yearlyData.sales.length - 2] || 0;
                periodText = 'Current Year';
                lastPeriodText = 'Previous Year';
                break;
            case 'daily':
                currentSales = dailyData.sales.reduce((a, b) => a + b, 0) || 0;
                lastPeriodSales = 0; // No previous daily comparison
                periodText = 'Last 30 Days';
                lastPeriodText = 'No Previous Data';
                break;
            default: // monthly
                currentSales = monthlyData.sales[monthlyData.sales.length - 1] || 0;
                lastPeriodSales = monthlyData.sales[monthlyData.sales.length - 2] || 0;
                periodText = 'Current Month';
                lastPeriodText = 'Previous Month';
        }
        
        // Calculate growth
        const growth = lastPeriodSales ? ((currentSales - lastPeriodSales) / lastPeriodSales * 100).toFixed(1) : 0;
        
        // Update displays
        document.getElementById('currentSalesDisplay').textContent = '₹' + currentSales.toLocaleString('en-IN');
        document.getElementById('lastPeriodDisplay').textContent = '₹' + lastPeriodSales.toLocaleString('en-IN');
        document.getElementById('growthDisplay').textContent = growth > 0 ? '+' + growth + '%' : growth + '%';
        document.getElementById('growthDisplay').className = growth > 0 ? 'font-medium text-lg text-green-600' : 'font-medium text-lg text-red-600';
        document.getElementById('currentPeriodText').textContent = periodText;
        document.getElementById('lastPeriodText').textContent = lastPeriodText;
        document.getElementById('growthText').textContent = 'Compared to ' + lastPeriodText;
    }
    
    // View switching
    document.getElementById('monthlyBtn').addEventListener('click', function() {
        updateActiveButton('monthlyBtn');
        updateChart('monthly');
    });
    
    document.getElementById('quarterlyBtn').addEventListener('click', function() {
        updateActiveButton('quarterlyBtn');
        updateChart('quarterly');
    });
    
    document.getElementById('yearlyBtn').addEventListener('click', function() {
        updateActiveButton('yearlyBtn');
        updateChart('yearly');
    });
    
    document.getElementById('dailyBtn').addEventListener('click', function() {
        updateActiveButton('dailyBtn');
        updateChart('daily');
    });
    
    function updateActiveButton(activeId) {
        // Reset all buttons
        document.querySelectorAll('#monthlyBtn, #quarterlyBtn, #yearlyBtn, #dailyBtn').forEach(btn => {
            btn.className = 'px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors';
        });
        
        // Set active button
        document.getElementById(activeId).className = 'px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors';
    }
    
    // Initialize
    updateMetrics('monthly');
</script>