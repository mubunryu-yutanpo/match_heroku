@extends('layouts.parent')

@section('title', '案件の詳細')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $project->title }}の案件詳細なんだな。
    </h1>
    <p>Auth::{{ $user->name }}</p>
    <p>発案者：{{ $project->user->name }}</p>

    <div id="app" class="">
        <detail-component :project_id="{{ $project->id }}" :user_id="{{ $user->id }}"></detail-component>
    </div>

    <!-- 応募部分 -->
    @if($user->id !== $project->user->id)
        <form action="{{ route('apply', ['project_id' => $project->id, 'user_id' => $user->id]) }}" method="post" class="">
            @csrf
            <button class="" type="submit" onclick="return confirm('この案件に応募します。よろしいですか？')">応募する！</button>
        </form>
    @else
        <a href="{{ route('project.edit', $project->id) }}" class="">案件内容を編集する</a>
    @endif
    

@endsection

@section('footer')
    @parent
@show