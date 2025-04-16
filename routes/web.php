<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// 管理后台路由
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // 使用资源路由处理后台学生管理操作（包括 index, create, store, edit, update, destroy）
    Route::resource('students', StudentController::class)->except(['show']);
    
    // 获取地区分布数据
    Route::get('/students/area-distribution', [StudentController::class, 'getAreaDistribution'])->name('students.area-distribution');
    
    // 地区分布页面
    Route::get('/students/area-distribution-page', [StudentController::class, 'areaDistribution'])->name('students.area-distribution-page');
});

// 普通学生页面路由
// 新增学生页面å
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
// 新增学生提交表单
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
// 学生列表页面（前端）
Route::get('/students', [StudentController::class, 'index'])->name('students.index');

// 登录路由
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// 主页路由
Route::get('/home', [HomeController::class, 'index'])->name('home');

// 删除学生
Route::delete('/admin/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
// 更新学生
Route::put('/admin/students/{id}', [StudentController::class, 'update'])->name('admin.students.update');
