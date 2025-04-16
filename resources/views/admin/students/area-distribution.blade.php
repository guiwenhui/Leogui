@extends('adminlte::page')

@section('title', '学生地区分布')

@section('content_header')
    <h1 style="font-family: 'Helvetic', sans-serif; font-size: 30px;">CampusMate</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">学生地区分布</h3>
        </div>
        <div class="card-body">
            <div style="height: 400px;">
                <canvas id="areaChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 获取地区分布数据
    fetch('{{ route("admin.students.area-distribution") }}')
        .then(response => response.json())
        .then(data => {
            console.log('获取到的数据:', data);
            
            const areas = data.map(item => item.area);
            const counts = data.map(item => item.count);
            
            // 创建柱状图
            const ctx = document.getElementById('areaChart');
            if (!ctx) {
                console.error('找不到 canvas 元素');
                return;
            }
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: areas,
                    datasets: [{
                        label: '学生人数',
                        data: counts,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0'
                        ],
                        borderColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: '各地区学生人数分布'
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('获取数据失败:', error);
        });
});
</script>
@endsection 