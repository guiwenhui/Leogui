@extends('adminlte::page')

@section('title', '新增学生')

@section('content_header')
    <h1 style="font-family: 'Helvetic', sans-serif; font-size: 30px;">CampusMate</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">New Student</h3>
        </div>
        <div class="card-body">
            <form id="studentForm" method="POST" action="{{ route('admin.students.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">姓名</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">邮箱</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="age">年龄</label>
                    <input type="number" class="form-control" id="age" name="age" required>
                </div>
                <div class="form-group">
                    <label for="area">地区</label>
                    <select class="form-control" id="area" name="area" required>
                        <option value="">请选择地区</option>
                        <option value="西安">西安</option>
                        <option value="上海">上海</option>
                        <option value="香港">香港</option>
                        <option value="成都">成都</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">提交</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">返回学生列表</a>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('studentForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const route = "{{ route('admin.students.store') }}";

            fetch(route, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(formData).toString()
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || '提交失败');
                    });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
                if (data.success) {
                    window.location.href = "{{ route('admin.students.index') }}";
                }
            })
            .catch(error => {
                alert('提交失败：' + error.message);
                console.error('错误详情:', error);
            });
        });
    </script>
@endsection
