<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:team,email',
                'age' => 'required|integer|min:1',
                'area' => ['required', Rule::in(['西安','上海','香港','成都'])],
            ], [
                'name.required' => '姓名不能为空',
                'name.max' => '姓名不能超过255个字符',
                'email.required' => '邮箱不能为空',
                'email.email' => '邮箱格式不正确',
                'email.unique' => '该邮箱已被使用',
                'age.required' => '年龄不能为空',
                'age.integer' => '年龄必须是整数',
                'age.min' => '年龄必须大于0',
                'area.required' => '地区不能为空',
                'area.in' => '请选择有效的地区'
            ]);

            $student = Team::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => '学生添加成功！',
                'data' => $student
            ]);
        } catch (ValidationException $e) {
            Log::error('验证错误:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => '验证失败',
                'errors' => $e->errors()
            ], 422);
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

    public function getAreaDistribution()
    {
        // 使用实际数据库数据
        $areaDistribution = Team::select('area', DB::raw('count(*) as count'))
            ->groupBy('area')
            ->get();
            
        return response()->json($areaDistribution);
        
    }
    
    public function areaDistribution()
    {
        return view('admin.students.area-distribution');
    }
}
