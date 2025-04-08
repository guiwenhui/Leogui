<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = Team::all();
        return view('admin.students.index', compact('students'));
    }
    //新增学生界面
    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
{
    Log::info('收到的请求数据:', $request->all());

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:team,email',
        'age' => 'required|integer|min:1',
        'area' => ['required', Rule::in(['西安','上海','香港','成都'])],
    ]);

    try {
        $student = Team::create($validatedData); // 创建记录并赋值

        // 返回 JSON 响应
        return response()->json([
            'success' => true,
            'message' => '学生添加成功！',
            'data' => $student,
        ]);
    } catch (\Exception $e) {
        Log::error('数据库错误:', ['error' => $e->getMessage()]);

        return response()->json([
            'success' => false,
            'message' => '操作失败: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
{
    try {
        // 使用 Eloquent 查找学生记录
        $student = Team::find($id);  // 使用 Eloquent 模型查找

        if (!$student) {
            return response()->json(['success' => false, 'message' => '记录不存在']);
        }

        // 执行删除操作
        $student->delete();

        return response()->json(['success' => true, 'message' => '删除成功！']);
    } catch (\Exception $e) {
        Log::error('删除失败:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => '删除失败: ' . $e->getMessage()]);
    }
}
    //更新学生
    public function update(Request $request, $id)
{
    Log::info('更新学生 ID: ' . $id);
    
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:team,email,' . $id, // 排除当前学生
        'age' => 'required|integer|min:1',
        'area' => ['required', Rule::in(['西安', '上海', '香港', '成都'])],
    ]);

    try {
        $student = Team::findOrFail($id);
        $student->update($validatedData);

        return response()->json(['success' => true, 'message' => '修改成功！']);
    } catch (\Exception $e) {
        Log::error('修改失败:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => '修改失败: ' . $e->getMessage()]);
    }
}
}
