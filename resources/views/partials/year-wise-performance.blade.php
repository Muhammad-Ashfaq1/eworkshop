@if(isset($chartData))
<style>
/* CSS overrides to guarantee high-contrast text rendering on ApexCharts */
#overallDistributionChart .apexcharts-datalabel-label, 
#overallDistributionChart .apexcharts-datalabel-name,
#overallDistributionChart .apexcharts-text.apexcharts-datalabel-label {
    fill: #64748b !important;
    color: #64748b !important;
}
#overallDistributionChart .apexcharts-datalabel-value,
#overallDistributionChart .apexcharts-text.apexcharts-datalabel-value {
    fill: #1e293b !important;
    color: #1e293b !important;
    font-weight: 700 !important;
}
#overallDistributionChart .apexcharts-pie-label {
    fill: #ffffff !important;
    color: #ffffff !important;
}
.year-perf-card {
    background: #ffffff !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
}
</style>

<div class="row mb-4">
    <!-- Year-wise Performance Line Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card year-perf-card card-height-100 border-0">
            <div class="card-header align-items-center d-flex bg-transparent border-0 pt-3 pb-2 px-4">
                <h4 class="card-title mb-0 flex-grow-1 d-flex align-items-center">
                    <span class="avatar-xs me-2 d-inline-flex align-items-center justify-content-center bg-primary-subtle rounded-2">
                        <i class="ri-bar-chart-fill text-primary fs-5"></i>
                    </span>
                    <span class="fs-16 fw-bold text-dark me-2">Year-wise Performance</span>
                    <span class="text-muted fw-normal fs-13">(FY 2026 – 27)</span>
                </h4>
                <div class="flex-shrink-0">
                    <div class="d-flex align-items-center gap-3 fs-13 text-secondary">
                        <span class="d-inline-flex align-items-center fw-medium">
                            <span class="rounded-circle me-1" style="width: 8px; height: 8px; background-color: #3b82f6;"></span> Reports
                        </span>
                        <span class="d-inline-flex align-items-center fw-medium">
                            <span class="rounded-circle me-1" style="width: 8px; height: 8px; background-color: #10b981;"></span> Orders
                        </span>
                        <span class="d-inline-flex align-items-center fw-medium">
                            <span class="rounded-circle me-1" style="width: 8px; height: 8px; background-color: #f59e0b;"></span> Fine
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <div id="yearWisePerformanceChart" class="apex-charts" dir="ltr" style="min-height: 330px;"></div>
            </div>
        </div>
    </div>

    <!-- Overall Distribution Donut Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card year-perf-card card-height-100 border-0">
            <div class="card-header align-items-center d-flex bg-transparent border-0 pt-3 pb-2 px-4">
                <h4 class="card-title mb-0 flex-grow-1 d-flex align-items-center">
                    <span class="avatar-xs me-2 d-inline-flex align-items-center justify-content-center bg-primary-subtle rounded-2">
                        <i class="ri-pie-chart-fill text-primary fs-5"></i>
                    </span>
                    <span class="fs-16 fw-bold text-dark">Overall Distribution</span>
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-center g-0">
                    <div class="col-sm-6">
                        <div id="overallDistributionChart" class="apex-charts" dir="ltr" style="min-height: 240px;"></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="ps-sm-3 pt-3 pt-sm-0">
                            <div class="d-flex align-items-center justify-content-between mb-4 pb-1">
                                <div class="d-flex align-items-center">
                                    <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #3b82f6;"></span>
                                    <span class="fw-medium text-secondary fs-14">Reports</span>
                                </div>
                                <span class="fw-bold fs-15 text-dark">{{ number_format($chartData['totals']['reports']) }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4 pb-1">
                                <div class="d-flex align-items-center">
                                    <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #10b981;"></span>
                                    <span class="fw-medium text-secondary fs-14">Orders</span>
                                </div>
                                <span class="fw-bold fs-15 text-dark">{{ number_format($chartData['totals']['orders']) }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span class="rounded-circle me-2" style="width: 10px; height: 10px; background-color: #f59e0b;"></span>
                                    <span class="fw-medium text-secondary fs-14">Fine</span>
                                </div>
                                <span class="fw-bold fs-15 text-dark">{{ number_format($chartData['totals']['fine']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Year-wise Performance Area Chart
    const perfCategories = @json($chartData['categories']);
    const perfSeries = @json($chartData['series']);

    const perfOptions = {
        series: perfSeries,
        chart: {
            height: 330,
            type: 'area',
            toolbar: { show: false },
            zoom: { enabled: false },
            fontFamily: 'Inter, sans-serif'
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [20, 100]
            }
        },
        colors: ['#3b82f6', '#10b981', '#f59e0b'],
        xaxis: {
            categories: perfCategories,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: {
                formatter: function (val) {
                    if (val === 0) return '0';
                    if (val >= 1000) return (val / 1000).toFixed(1).replace('.0', '') + 'K';
                    return Math.round(val);
                },
                style: {
                    colors: '#64748b',
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        legend: { show: false },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 3,
            padding: { top: 0, right: 10, bottom: 0, left: 10 }
        },
        markers: {
            size: 4,
            colors: ['#3b82f6', '#10b981', '#f59e0b'],
            strokeColors: '#ffffff',
            strokeWidth: 2,
            hover: { size: 6 }
        },
        tooltip: {
            shared: true,
            intersect: false
        }
    };

    if (document.querySelector("#yearWisePerformanceChart")) {
        const perfChart = new ApexCharts(document.querySelector("#yearWisePerformanceChart"), perfOptions);
        perfChart.render();
    }

    // 2. Overall Distribution Donut Chart
    const distTotals = @json($chartData['totals']);
    const distSeries = [distTotals.reports, distTotals.orders, distTotals.fine];

    const distOptions = {
        series: distSeries,
        labels: ['Reports', 'Orders', 'Fine'],
        chart: {
            height: 240,
            type: 'donut',
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#3b82f6', '#10b981', '#f59e0b'],
        legend: { show: false },
        dataLabels: { 
            enabled: true, 
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                colors: ['#ffffff']
            },
            dropShadow: { enabled: false },
            formatter: function(val) { return Math.round(val) + '%'; }
        },
        stroke: { width: 2, colors: ['#ffffff'] },
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '13px',
                            fontWeight: 500,
                            color: '#64748b',
                            offsetY: -10
                        },
                        value: {
                            show: true,
                            fontSize: '22px',
                            fontWeight: 700,
                            color: '#1e293b',
                            offsetY: 2,
                            formatter: function (val) {
                                return parseInt(val).toLocaleString();
                            }
                        },
                        total: {
                            show: true,
                            showAlways: true,
                            label: 'Total',
                            fontSize: '13px',
                            fontWeight: 500,
                            color: '#64748b',
                            formatter: function (w) {
                                return distTotals.grand_total.toLocaleString();
                            }
                        }
                    }
                }
            }
        }
    };

    if (document.querySelector("#overallDistributionChart")) {
        const distChart = new ApexCharts(document.querySelector("#overallDistributionChart"), distOptions);
        distChart.render();
    }
});
</script>
@endif
