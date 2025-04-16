@extends('adminlte::page')

@section('title', '学生管理系统')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 style="font-family: 'Helvetica', sans-serif; font-size: 24px; margin: 0;">CampusMate</h1>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">学生管理</h5>
                        <div>
                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus"></i> 新增学生
                            </a>
                            <a href="{{ route('admin.students.area-distribution-page') }}" class="btn btn-info">
                                <i class="fas fa-chart-bar"></i> 地区分布
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">学生列表</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>姓名</th>
                                    <th>邮箱</th>
                                    <th>年龄</th>
                                    <th>地区</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr id="student-row-{{ $student->id }}">
                                        <td>{{ $student->id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->age }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $student->area }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-student" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-email="{{ $student->email }}" data-age="{{ $student->age }}" data-area="{{ $student->area }}">
                                                <i class="fas fa-edit"></i> 修改
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-student" data-id="{{ $student->id }}">
                                                <i class="fas fa-trash"></i> 删除
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 新增学生 Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增学生</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.students.store') }}">
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
                        <input type="text" class="form-control" id="area" name="area" required>
                    </div>
                    <button type="submit" class="btn btn-primary">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- 修改学生 Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">修改学生</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm">
                    @csrf
                    <input type="hidden" id="edit-student-id" name="id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">姓名</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">邮箱</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-age" class="form-label">年龄</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                            <input type="number" class="form-control" id="edit-age" name="age" required min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-area" class="form-label">地区</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <select class="form-select" id="edit-area" name="area" required>
                                <option value="">请选择地区</option>
                                <option value="西安">西安</option>
                                <option value="上海">上海</option>
                                <option value="香港">香港</option>
                                <option value="成都">成都</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 保存修改
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> 取消
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 删除学生功能
    document.querySelectorAll('.delete-student').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            
            Swal.fire({
                title: '确认删除',
                text: '您确定要删除这名学生吗？此操作不可撤销！',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '是的，删除',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const route = `/admin/students/${studentId}`;
                    
                    fetch(route, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '删除成功',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                document.getElementById(`student-row-${studentId}`).remove();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '删除失败',
                                text: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('删除错误:', error);
                        Swal.fire({
                            icon: 'error',
                            title: '删除失败',
                            text: '操作失败，请稍后重试'
                        });
                    });
                }
            });
        });
    });

    // 编辑学生功能
    document.querySelectorAll('.edit-student').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            const studentName = this.getAttribute('data-name');
            const studentEmail = this.getAttribute('data-email');
            const studentAge = this.getAttribute('data-age');
            const studentArea = this.getAttribute('data-area');

            // 填充编辑表单的字段
            document.getElementById('edit-student-id').value = studentId;
            document.getElementById('edit-name').value = studentName;
            document.getElementById('edit-email').value = studentEmail;
            document.getElementById('edit-age').value = studentAge;
            document.getElementById('edit-area').value = studentArea;

            // 显示模态框
            const editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
            editModal.show();
        });
    });

    // 提交修改
    document.getElementById('editStudentForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const studentId = document.getElementById('edit-student-id').value;
        const route = `/admin/students/${studentId}`;

        fetch(route, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(formData).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '修改成功',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload(); // 刷新页面
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '修改失败',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('修改错误:', error);
            Swal.fire({
                icon: 'error',
                title: '修改失败',
                text: '操作失败，请稍后重试'
            });
        });
    });
});
</script>
@endsection

@section('css')
<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        font-weight: 600;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
    .btn {
        border-radius: 5px;
        padding: 0.375rem 0.75rem;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .modal-content {
        border-radius: 10px;
        border: none;
    }
    .modal-header {
        border-radius: 10px 10px 0 0;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>
@endsection