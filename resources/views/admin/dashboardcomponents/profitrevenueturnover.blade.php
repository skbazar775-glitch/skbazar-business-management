<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Financial Performance Dashboard</h2>
        <div class="flex space-x-2">
            <select id="monthSelector" class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                @foreach ($monthlyLabels ?? [] as $index => $label)
                    <option value="{{ $index }}" {{ $index === count($monthlyLabels ?? []) - 1 ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button id="timeMonthly" class="px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">Monthly</button>
            <button id="timeQuarterly" class="px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Quarterly</button>
            <button id="timeYearly" class="px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">Yearly</button>
        </div>
    </div>

    <!-- Financial Metrics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Revenue Card -->
        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-green-600 font-medium">Revenue</p>
                    <p id="revenueValue" class="text-2xl font-bold text-green-800 mt-1">₹{{ number_format($monthlySalesData[array_key_last($monthlySalesData)] ?? 0, 0, '.', ',') }}</p>
                </div>
                <span id="revenuePercentage" class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">{{ $salesPercentageChange > 0 ? '+' : '' }}{{ $salesPercentageChange }}%</span>
            </div>
            <div class="mt-4 h-40">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Profit Card -->
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Profit</p>
                    <p id="profitValue" class="text-2xl font-bold text-blue-800 mt-1">₹{{ number_format($monthlyProfitData[array_key_last($monthlyProfitData)] ?? 0, 0, '.', ',') }}</p>
                </div>
                <span id="profitPercentage" class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $profitPercentageChange > 0 ? '+' : '' }}{{ $profitPercentageChange }}%</span>
            </div>
            <div class="mt-4 h-40">
                <canvas id="profitChart"></canvas>
            </div>
        </div>

        <!-- Expenses Card -->
        <div class="bg-red-50 p-4 rounded-lg border border-red-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-red-600 font-medium">Expenses</p>
                    <p id="expensesValue" class="text-2xl font-bold text-red-800 mt-1">₹{{ number_format($monthlyExpensesData[array_key_last($monthlyExpensesData)] ?? 0, 0, '.', ',') }}</p>
                </div>
                <span id="expensesPercentage" class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">{{ $expensesPercentageChange > 0 ? '-' : '+' }}{{ abs($expensesPercentageChange) }}%</span>
            </div>
            <div class="mt-4 h-40">
                <canvas id="expensesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Combined Financial Chart -->
    <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-medium text-gray-700">Financial Trend Analysis</h3>
            <div class="flex space-x-2">
                <button id="chartRevenue" class="px-2 py-1 text-xs bg-green-50 text-green-600 rounded hover:bg-green-100">Revenue</button>
                <button id="chartProfit" class="px-2 py-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100">Profit</button>
                <button id="chartExpenses" class="px-2 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100">Expenses</button>
                <button id="chartAll" class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">All</button>
            </div>
        </div>
        <div class="h-80">
            <canvas id="combinedChart"></canvas>
        </div>
    </div>

    <!-- Financial Ratios -->
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-xs">
            <p class="text-xs text-gray-500">Profit Margin</p>
            <p id="profitMargin" class="font-medium text-lg">{{ round(($monthlyProfitData[array_key_last($monthlyProfitData)] ?? 0) / ($monthlySalesData[array_key_last($monthlySalesData)] ?: 1) * 100, 1) }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                <div id="profitMarginBar" class="bg-green-500 h-1.5 rounded-full" style="width: {{ round(($monthlyProfitData[array_key_last($monthlyProfitData)] ?? 0) / ($monthlySalesData[array_key_last($monthlySalesData)] ?: 1) * 100, 1) }}%"></div>
            </div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-xs">
            <p class="text-xs text-gray-500">Revenue Growth</p>
            <p id="revenueGrowth" class="font-medium text-lg text-green-600">{{ $salesPercentageChange > 0 ? '+' : '' }}{{ $salesPercentageChange }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                <div id="revenueGrowthBar" class="bg-blue-500 h-1.5 rounded-full" style="width: {{ min(abs($salesPercentageChange), 100) }}%"></div>
            </div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-xs">
            <p class="text-xs text-gray-500">Expense Reduction</p>
            <p id="expenseReduction" class="font-medium text-lg {{ $expensesPercentageChange > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $expensesPercentageChange > 0 ? '-' : '+' }}{{ abs($expensesPercentageChange) }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                <div id="expenseReductionBar" class="bg-red-500 h-1.5 rounded-full" style="width: {{ min(abs($expensesPercentageChange), 100) }}%"></div>
            </div>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-xs">
            <p class="text-xs text-gray-500">Operating Ratio</p>
            <p id="operatingRatio" class="font-medium text-lg">{{ round(($monthlySalesData[array_key_last($monthlySalesData)] ?? 0 - ($monthlyProfitData[array_key_last($monthlyProfitData)] ?? 0)) / ($monthlySalesData[array_key_last($monthlySalesData)] ?: 1), 2) }}</p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                <div id="operatingRatioBar" class="bg-yellow-500 h-1.5 rounded-full" style="width: {{ round(($monthlySalesData[array_key_last($monthlySalesData)] ?? 0 - ($monthlyProfitData[array_key_last($monthlyProfitData)] ?? 0)) / ($monthlySalesData[array_key_last($monthlySalesData)] ?: 1) * 100, 1) }}%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dynamic financial data from controller
    const financialData = {
        monthly: {
            labels: @json($monthlyLabels ?? []),
            revenue: @json($monthlySalesData ?? []),
            profit: @json($monthlyProfitData ?? []),
            expenses: @json($monthlyExpensesData ?? [])
        },
        quarterly: {
            labels: @json($quarterlyLabels ?? []),
            revenue: @json($quarterlySalesData ?? []),
            profit: @json($quarterlyProfitData ?? []),
            expenses: @json($quarterlyExpensesData ?? [])
        },
        yearly: {
            labels: @json($yearlyLabels ?? []),
            revenue: @json($yearlySalesData ?? []),
            profit: @json($yearlyProfitData ?? []),
            expenses: @json($yearlyExpensesData ?? [])
        }
    };

    // Debugging: Log financial data
    console.log('Monthly Financial Data:', financialData.monthly);
    console.log('Quarterly Financial Data:', financialData.quarterly);
    console.log('Yearly Financial Data:', financialData.yearly);

    // Validate data before rendering charts
    function validateData(data) {
        const isValid = data.labels && Array.isArray(data.labels) && data.labels.length > 0 &&
                       data.revenue && Array.isArray(data.revenue) && data.revenue.length === data.labels.length &&
                       data.profit && Array.isArray(data.profit) && data.profit.length === data.labels.length &&
                       data.expenses && Array.isArray(data.expenses) && data.expenses.length === data.labels.length &&
                       data.revenue.every(val => typeof val === 'number' && !isNaN(val)) &&
                       data.profit.every(val => typeof val === 'number' && !isNaN(val)) &&
                       data.expenses.every(val => typeof val === 'number' && !isNaN(val));
        if (!isValid) {
            console.error(`Invalid data for period:`, data);
        }
        return isValid;
    }

    // Initialize all charts
    let revenueChart, profitChart, expensesChart, combinedChart;
    let currentPeriod = 'monthly';
    let currentCombinedView = 'all';

    // Determine the current month and year
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth(); // 0-based (0 = January, 11 = December)
    const currentYear = currentDate.getFullYear();

    // Find the index of the current month in monthlyLabels
    const monthlyLabels = financialData.monthly.labels;
    let selectedIndex = monthlyLabels.findIndex(label => {
        // Assuming labels are in the format "Month Year" (e.g., "July 2025")
        const [month, year] = label.split(' ');
        const monthIndex = new Date(`${month} 1, ${year}`).getMonth();
        const labelYear = parseInt(year);
        return monthIndex === currentMonth && labelYear === currentYear;
    });

    // Fallback to the last index if no match is found
    if (selectedIndex === -1) {
        selectedIndex = monthlyLabels.length > 0 ? monthlyLabels.length - 1 : 0;
    }

    // Format currency
    function formatCurrency(value) {
        return '₹' + (Number.isFinite(value) ? value.toLocaleString('en-IN') : '0');
    }

    // Initialize small charts
    function initMiniCharts() {
        if (!validateData(financialData[currentPeriod])) {
            console.error(`Cannot initialize charts: Invalid ${currentPeriod} data`);
            updateSummaryCards();
            return;
        }

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: getMiniChartData('revenue'),
            options: getMiniChartOptions('Revenue', '#10B981')
        });

        // Profit Chart
        const profitCtx = document.getElementById('profitChart').getContext('2d');
        profitChart = new Chart(profitCtx, {
            type: 'line',
            data: getMiniChartData('profit'),
            options: getMiniChartOptions('Profit', '#3B82F6')
        });

        // Expenses Chart
        const expensesCtx = document.getElementById('expensesChart').getContext('2d');
        expensesChart = new Chart(expensesCtx, {
            type: 'line',
            data: getMiniChartData('expenses'),
            options: getMiniChartOptions('Expenses', '#EF4444')
        });
    }

    // Initialize combined chart
    function initCombinedChart() {
        if (!validateData(financialData[currentPeriod])) {
            console.error(`Cannot initialize combined chart: Invalid ${currentPeriod} data`);
            return;
        }

        const combinedCtx = document.getElementById('combinedChart').getContext('2d');
        combinedChart = new Chart(combinedCtx, {
            type: 'bar',
            data: getCombinedChartData(),
            options: getCombinedChartOptions()
        });
    }

    // Get data for mini charts
    function getMiniChartData(type) {
        const data = financialData[currentPeriod][type];
        const lastIndex = currentPeriod === 'monthly' ? selectedIndex : data.length - 1;
        const lastValue = data[lastIndex] || 0;
        const prevValue = lastIndex > 0 ? data[lastIndex - 1] : 0;
        const growth = prevValue ? ((lastValue - prevValue) / prevValue * 100).toFixed(1) : 0;

        return {
            labels: financialData[currentPeriod].labels,
            datasets: [{
                label: type,
                data: data,
                borderColor: getColorHex(type),
                backgroundColor: 'rgba(255, 255, 255, 0)',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 0,
                pointHoverRadius: 4
            }]
        };
    }

    // Get data for combined chart
    function getCombinedChartData() {
        const data = financialData[currentPeriod];
        const datasets = [];
        
        if (currentCombinedView === 'all' || currentCombinedView === 'revenue') {
            datasets.push({
                label: 'Revenue',
                data: data.revenue,
                backgroundColor: '#10B981',
                borderColor: '#10B981',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false
            });
        }
        
        if (currentCombinedView === 'all' || currentCombinedView === 'profit') {
            datasets.push({
                label: 'Profit',
                data: data.profit,
                backgroundColor: '#3B82F6',
                borderColor: '#3B82F6',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false
            });
        }
        
        if (currentCombinedView === 'all' || currentCombinedView === 'expenses') {
            datasets.push({
                label: 'Expenses',
                data: data.expenses,
                backgroundColor: '#EF4444',
                borderColor: '#EF4444',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false
            });
        }

        return {
            labels: data.labels,
            datasets: datasets
        };
    }

    // Get chart options for mini charts
    function getMiniChartOptions(label, color) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    intersect: false,
                    mode: 'index',
                    callbacks: {
                        label: function(context) {
                            return `${label}: ${formatCurrency(context.parsed.y)}`;
                        }
                    }
                }
            },
            scales: {
                x: { display: false },
                y: { display: false }
            },
            elements: {
                line: {
                    fill: true
                }
            }
        };
    }

    // Get chart options for combined chart
    function getCombinedChartOptions() {
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
                            return `${context.dataset.label}: ${formatCurrency(context.parsed.y)}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
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
                mode: 'index'
            }
        };
    }

    // Helper function to get color based on metric type
    function getColor(type) {
        switch(type) {
            case 'revenue': return 'green';
            case 'profit': return 'blue';
            case 'expenses': return 'red';
            default: return 'gray';
        }
    }

    // Helper function to get color hex
    function getColorHex(type) {
        switch(type) {
            case 'revenue': return '#10B981';
            case 'profit': return '#3B82F6';
            case 'expenses': return '#EF4444';
            default: return '#6B7280';
        }
    }

    // Update summary cards for selected period/index
    function updateSummaryCards() {
        const data = financialData[currentPeriod];
        const lastIndex = currentPeriod === 'monthly' ? selectedIndex : (data.labels.length > 0 ? data.labels.length - 1 : 0);
        const revenue = data.revenue[lastIndex] || 0;
        const profit = data.profit[lastIndex] || 0;
        const expenses = data.expenses[lastIndex] || 0;
        const prevRevenue = lastIndex > 0 ? (data.revenue[lastIndex - 1] || 0) : 0;
        const prevProfit = lastIndex > 0 ? (data.profit[lastIndex - 1] || 0) : 0;
        const prevExpenses = lastIndex > 0 ? (data.expenses[lastIndex - 1] || 0) : 0;

        // Update values
        document.getElementById('revenueValue').textContent = formatCurrency(revenue);
        document.getElementById('profitValue').textContent = formatCurrency(profit);
        document.getElementById('expensesValue').textContent = formatCurrency(expenses);

        // Update percentage changes
        const revenueGrowth = prevRevenue ? ((revenue - prevRevenue) / prevRevenue * 100).toFixed(1) : 0;
        const profitGrowth = prevProfit ? ((profit - prevProfit) / prevProfit * 100).toFixed(1) : 0;
        const expenseReduction = prevExpenses ? ((prevExpenses - expenses) / prevExpenses * 100).toFixed(1) : 0;

        document.getElementById('revenuePercentage').textContent = revenueGrowth > 0 ? `+${revenueGrowth}%` : `${revenueGrowth}%`;
        document.getElementById('profitPercentage').textContent = profitGrowth > 0 ? `+${profitGrowth}%` : `${profitGrowth}%`;
        document.getElementById('expensesPercentage').textContent = expenseReduction > 0 ? `-${expenseReduction}%` : `+${Math.abs(expenseReduction)}%`;
    }

    // Update all charts when time period or month changes
    function updateCharts() {
        if (!validateData(financialData[currentPeriod])) {
            console.error(`Cannot update charts: Invalid ${currentPeriod} data`);
            document.getElementById('revenueValue').textContent = '₹0';
            document.getElementById('profitValue').textContent = '₹0';
            document.getElementById('expensesValue').textContent = '₹0';
            document.getElementById('revenuePercentage').textContent = '0%';
            document.getElementById('profitPercentage').textContent = '0%';
            document.getElementById('expensesPercentage').textContent = '0%';
            return;
        }

        revenueChart.data = getMiniChartData('revenue');
        profitChart.data = getMiniChartData('profit');
        expensesChart.data = getMiniChartData('expenses');
        combinedChart.data = getCombinedChartData();
        
        revenueChart.update();
        profitChart.update();
        expensesChart.update();
        combinedChart.update();
        
        updateFinancialRatios();
        updateSummaryCards();
    }

    // Update financial ratios
    function updateFinancialRatios() {
        const data = financialData[currentPeriod];
        const lastIndex = currentPeriod === 'monthly' ? selectedIndex : (data.labels.length > 0 ? data.labels.length - 1 : 0);
        const lastRevenue = data.revenue[lastIndex] || 0;
        const lastProfit = data.profit[lastIndex] || 0;
        const lastExpenses = data.expenses[lastIndex] || 0;
        const prevExpenses = lastIndex > 0 ? (data.expenses[lastIndex - 1] || 0) : 0;
        
        // Profit Margin
        const profitMargin = lastRevenue ? ((lastProfit / lastRevenue) * 100).toFixed(1) : 0;
        document.getElementById('profitMargin').textContent = `${profitMargin}%`;
        document.getElementById('profitMarginBar').style.width = `${Math.min(profitMargin, 100)}%`;
        
        // Revenue Growth
        const lastRevenuePrev = lastIndex > 0 ? (data.revenue[lastIndex - 1] || 0) : 0;
        const revenueGrowth = lastRevenuePrev ? ((lastRevenue - lastRevenuePrev) / lastRevenuePrev * 100).toFixed(1) : 0;
        document.getElementById('revenueGrowth').textContent = revenueGrowth > 0 ? `+${revenueGrowth}%` : `${revenueGrowth}%`;
        document.getElementById('revenueGrowth').className = `font-medium text-lg ${revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600'}`;
        document.getElementById('revenueGrowthBar').style.width = `${Math.min(Math.abs(revenueGrowth), 100)}%`;
        
        // Expense Reduction
        const expenseReduction = prevExpenses ? ((prevExpenses - lastExpenses) / prevExpenses * 100).toFixed(1) : 0;
        document.getElementById('expenseReduction').textContent = expenseReduction > 0 ? `-${expenseReduction}%` : `+${Math.abs(expenseReduction)}%`;
        document.getElementById('expenseReduction').className = `font-medium text-lg ${expenseReduction >= 0 ? 'text-green-600' : 'text-red-600'}`;
        document.getElementById('expenseReductionBar').style.width = `${Math.min(Math.abs(expenseReduction), 100)}%`;
        
        // Operating Ratio
        const operatingRatio = lastRevenue ? ((lastRevenue - lastProfit) / lastRevenue).toFixed(2) : 0;
        document.getElementById('operatingRatio').textContent = operatingRatio;
        document.getElementById('operatingRatioBar').style.width = `${Math.min(operatingRatio * 100, 100)}%`;
    }

    // Month selection handler
    document.getElementById('monthSelector').addEventListener('change', function() {
        selectedIndex = parseInt(this.value);
        console.log('Selected Month Index:', selectedIndex, 'Label:', financialData.monthly.labels[selectedIndex] || 'N/A');
        updateCharts();
    });

    // Time period buttons
    document.getElementById('timeMonthly').addEventListener('click', function() {
        currentPeriod = 'monthly';
        // Reset selectedIndex to current month when switching to monthly view
        selectedIndex = monthlyLabels.findIndex(label => {
            const [month, year] = label.split(' ');
            const monthIndex = new Date(`${month} 1, ${year}`).getMonth();
            const labelYear = parseInt(year);
            return monthIndex === currentMonth && labelYear === currentYear;
        });
        if (selectedIndex === -1) {
            selectedIndex = monthlyLabels.length > 0 ? monthlyLabels.length - 1 : 0;
        }
        document.getElementById('monthSelector').value = selectedIndex;
        document.getElementById('monthSelector').style.display = 'block';
        setActiveTimeButton('timeMonthly');
        updateCharts();
    });

    document.getElementById('timeQuarterly').addEventListener('click', function() {
        currentPeriod = 'quarterly';
        selectedIndex = financialData.quarterly.labels.length > 0 ? financialData.quarterly.labels.length - 1 : 0;
        document.getElementById('monthSelector').style.display = 'none';
        setActiveTimeButton('timeQuarterly');
        updateCharts();
    });

    document.getElementById('timeYearly').addEventListener('click', function() {
        currentPeriod = 'yearly';
        selectedIndex = financialData.yearly.labels.length > 0 ? financialData.yearly.labels.length - 1 : 0;
        document.getElementById('monthSelector').style.display = 'none';
        setActiveTimeButton('timeYearly');
        updateCharts();
    });

    // Combined chart view buttons
    document.getElementById('chartRevenue').addEventListener('click', function() {
        currentCombinedView = 'revenue';
        setActiveChartButton('chartRevenue');
        updateCharts();
    });

    document.getElementById('chartProfit').addEventListener('click', function() {
        currentCombinedView = 'profit';
        setActiveChartButton('chartProfit');
        updateCharts();
    });

    document.getElementById('chartExpenses').addEventListener('click', function() {
        currentCombinedView = 'expenses';
        setActiveChartButton('chartExpenses');
        updateCharts();
    });

    document.getElementById('chartAll').addEventListener('click', function() {
        currentCombinedView = 'all';
        setActiveChartButton('chartAll');
        updateCharts();
    });

    // Helper function to set active time button
    function setActiveTimeButton(activeId) {
        document.querySelectorAll('#timeMonthly, #timeQuarterly, #timeYearly').forEach(btn => {
            btn.className = 'px-3 py-1 text-xs bg-gray-50 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors';
        });
        document.getElementById(activeId).className = 'px-3 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors';
        document.getElementById('monthSelector').style.display = activeId === 'timeMonthly' ? 'block' : 'none';
    }

    // Helper function to set active chart button
    function setActiveChartButton(activeId) {
        document.querySelectorAll('#chartRevenue, #chartProfit, #chartExpenses, #chartAll').forEach(btn => {
            const color = getColor(btn.id.replace('chart', '').toLowerCase());
            btn.className = `px-2 py-1 text-xs bg-${color}-50 text-${color}-600 rounded hover:bg-${color}-100`;
        });
        document.getElementById(activeId).className = 'px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200';
    }

    // Initialize all charts when page loads
    window.addEventListener('load', function() {
        document.getElementById('monthSelector').value = selectedIndex;
        initMiniCharts();
        initCombinedChart();
        setActiveTimeButton('timeMonthly');
        setActiveChartButton('chartAll');
        updateFinancialRatios();
        updateSummaryCards();
    });
</script>