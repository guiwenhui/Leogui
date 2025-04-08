<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>新增学生</title>
</head>
<body>
    <h2>新增学生</h2>

    <form id="studentForm">
    <div>
        <label for="name">姓名:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">邮箱:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="age">年龄:</label>
        <input type="number" id="age" name="age" required>
    </div>
    <div>
    <label for="area">地区:</label>
    <select class="form-control" id="area" name="area" required>
        <option value="西安">Xian</option>
        <option value="上海">Shanghai</option>
        <option value="香港">Hongkong</option>
        <option value="成都">Chengdu</option>
    </select>
</div>

    <button type="submit">提交</button>
</form>

    <script>
        document.getElementById('studentForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            console.log('选择的 area:', formData.get('area'));
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
                    throw new Error(`HTTP 错误! 状态码: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
                if (data.success) {
                    window.location.reload(); // 刷新页面
                }
            })
            .catch(error => {
                alert('网络错误，请稍后重试！');
                console.error('错误详情:', error);
            });
        });
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
</body>
</html>

