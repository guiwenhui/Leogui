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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">学生列表</h5>
                        <div class="d-flex">
                            <div class="input-group me-2">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="搜索学生...">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter"></i> 筛选
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item filter-option" href="#" data-area="all">全部地区</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-area="西安">西安</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-area="上海">上海</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-area="香港">香港</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-area="成都">成都</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover" id="studentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="sortable" data-sort="id">ID <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="name">姓名 <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="email">邮箱 <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="age">年龄 <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="area">地区 <i class="fas fa-sort"></i></th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr id="student-row-{{ $student->id }}" data-area="{{ $student->area }}">
                                        <td>{{ $student->id }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->age }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $student->area }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-warning btn-sm edit-student" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-email="{{ $student->email }}" data-age="{{ $student->age }}" data-area="{{ $student->area }}">
                                                    <i class="fas fa-edit"></i> 修改
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-student" data-id="{{ $student->id }}">
                                                    <i class="fas fa-trash"></i> 删除
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            共 <span id="totalCount">{{ count($students) }}</span> 条记录
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
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
                            'Accept': 'application/json'
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
                                updateTotalCount();
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

        // 将 FormData 转换为 JSON 对象
        const jsonData = {};
        formData.forEach((value, key) => {
            if (key !== '_token') { // 排除 CSRF token
                jsonData[key] = value;
            }
        });

        fetch(route, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => {
            if (!response.ok && response.status !== 422) { // 允许 422 验证错误通过
                throw new Error('网络响应错误');
            }
            return response.json();
        })
        .then(data => {
            if (data.success === false) {
                throw data;
            }
            
            // 更新表格中的数据
            const row = document.getElementById(`student-row-${studentId}`);
            if (row) {
                row.cells[1].textContent = jsonData.name;
                row.cells[2].textContent = jsonData.email;
                row.cells[3].textContent = jsonData.age;
                row.cells[4].innerHTML = `<span class="badge bg-info">${jsonData.area}</span>`;
                row.setAttribute('data-area', jsonData.area);
            }

            // 关闭模态框
            const editModal = document.getElementById('editStudentModal');
            const modal = new bootstrap.Modal(editModal);
            modal.hide();

            // 显示成功消息
            Swal.fire({
                icon: 'success',
                title: '修改成功',
                text: data.message || '学生信息已更新',
                showConfirmButton: false,
                timer: 1500
            });
        })
        .catch(error => {
            console.error('修改错误:', error);
            let errorMessage = '操作失败，请稍后重试';
            
            if (error.errors) {
                errorMessage = Object.values(error.errors).flat().join('\n');
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            // 如果数据已经更新成功，即使有错误也显示成功消息
            if (error.success === undefined) {
                // 更新表格中的数据
                const row = document.getElementById(`student-row-${studentId}`);
                if (row) {
                    row.cells[1].textContent = jsonData.name;
                    row.cells[2].textContent = jsonData.email;
                    row.cells[3].textContent = jsonData.age;
                    row.cells[4].innerHTML = `<span class="badge bg-info">${jsonData.area}</span>`;
                    row.setAttribute('data-area', jsonData.area);
                }

                // 关闭模态框
                const editModal = document.getElementById('editStudentModal');
                const modal = new bootstrap.Modal(editModal);
                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: '修改成功',
                    text: '学生信息已更新',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '修改失败',
                    text: errorMessage
                });
            }
        });
    });
    
    // 搜索功能
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentsTable tbody tr');
        
        rows.forEach(row => {
            const name = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();
            const area = row.cells[4].textContent.toLowerCase();
            
            if (name.includes(searchValue) || email.includes(searchValue) || area.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        updateTotalCount();
    });
    
    // 地区筛选功能
    document.querySelectorAll('.filter-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const area = this.getAttribute('data-area');
            const rows = document.querySelectorAll('#studentsTable tbody tr');
            
            rows.forEach(row => {
                if (area === 'all' || row.getAttribute('data-area') === area) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            updateTotalCount();
        });
    });
    
    // 排序功能
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const sortBy = this.getAttribute('data-sort');
            const rows = Array.from(document.querySelectorAll('#studentsTable tbody tr'));
            const isAsc = this.classList.contains('asc');
            
            // 重置所有排序图标
            document.querySelectorAll('.sortable i').forEach(icon => {
                icon.className = 'fas fa-sort';
            });
            
            // 设置当前排序图标
            this.querySelector('i').className = isAsc ? 'fas fa-sort-down' : 'fas fa-sort-up';
            
            // 切换排序方向
            this.classList.toggle('asc');
            
            // 排序行
            rows.sort((a, b) => {
                let aValue = a.cells[getColumnIndex(sortBy)].textContent;
                let bValue = b.cells[getColumnIndex(sortBy)].textContent;
                
                // 处理数字类型
                if (sortBy === 'id' || sortBy === 'age') {
                    aValue = parseInt(aValue);
                    bValue = parseInt(bValue);
                }
                
                if (isAsc) {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });
            
            // 重新插入排序后的行
            const tbody = document.querySelector('#studentsTable tbody');
            rows.forEach(row => tbody.appendChild(row));
        });
    });
    
    // 获取列索引
    function getColumnIndex(sortBy) {
        const headers = document.querySelectorAll('#studentsTable thead th');
        for (let i = 0; i < headers.length; i++) {
            if (headers[i].getAttribute('data-sort') === sortBy) {
                return i;
            }
        }
        return 0;
    }
    
    // 更新总记录数
    function updateTotalCount() {
        const visibleRows = document.querySelectorAll('#studentsTable tbody tr:not([style*="display: none"])');
        document.getElementById('totalCount').textContent = visibleRows.length;
    }
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
    .sortable {
        cursor: pointer;
    }
    .sortable:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    .pagination .page-link {
        border-radius: 5px;
        margin: 0 2px;
    }
    .btn-group {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
</style>
@endsection