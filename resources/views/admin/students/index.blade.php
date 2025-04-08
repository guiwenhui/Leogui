@extends('adminlte::page')

@section('title', '学生管理系统')

@section('content_header')
    <h1 style="font-family: 'Helvetic', sans-serif; font-size: 30px;" >CampusMate</h1>
@endsection

@section('content')
    <div class="card">

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>姓名</th>
                        <th>邮箱</th>
                        <th>年龄</th>
                        <th>地区</th>
                        <th>操作</th> <!-- ✅ 修正：添加“操作”列 -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr id="student-row-{{ $student->id }}">
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->age }}</td>
                            <td>{{ $student->area }}</td>
                            <td>
                            <button class="btn-sm edit-student" style="border: none; border-radius: 12px; padding: 5px 10px; color: #808080; background-color: transparent; font-size: 15px;" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-email="{{ $student->email }}" data-age="{{ $student->age }}" data-area="{{ $student->area }}">
                                ✎
                            </button>
                            <button class="btn-sm delete-student" style="border: none; border-radius: 12px; padding: 5px 10px; color: #808080; background-color: transparent; font-size: 15px;" data-id="{{ $student->id }}">
                                ⌫
                            </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm">
                    @csrf
                    <input type="hidden" id="edit-student-id" name="id">
                    <div class="form-group">
                        <label for="edit-name">姓名</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-email">邮箱</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-age">年龄</label>
                        <input type="number" class="form-control" id="edit-age" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-area">地区</label>
                        <input type="text" class="form-control" id="edit-area" name="area" required>
                    </div>
                    <button type="submit" class="btn btn-primary">提交修改</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 删除学生功能
    document.querySelectorAll('.delete-student').forEach(button => {
        button.addEventListener('click', function () {
            if (!confirm('确定要删除该学生吗？')) return;

            const studentId = this.getAttribute('data-id');
            console.log('正在删除的学生 ID:', studentId);
            fetch(`/admin/students/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById(`student-row-${studentId}`).remove(); // 删除表格行
                } else {
                    alert('删除失败: ' + data.message);
                }
            })
            .catch(error => console.error('删除错误:', error));
        });
    });

    // 编辑学生功能
    document.querySelectorAll('.edit-student').forEach(button => {
        button.addEventListener('click', function () {
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
            $('#editStudentModal').modal('show');
        });
    });

    // 提交修改
    document.getElementById('editStudentForm').addEventListener('submit', function (event) {
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
                alert(data.message);
                window.location.reload(); // 刷新页面
            } else {
                alert('修改失败: ' + data.message);
            }
        })
        .catch(error => console.error('修改错误:', error));
    });
});

</script>