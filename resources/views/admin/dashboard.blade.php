@extends('adminlte::page')

@section('title', '学生管理系统')

@section('content_header')
    <h1>学生管理系统</h1>
@endsection

@section('content')
    <div id="app">
        <student-list></student-list>
    </div>
@endsection
