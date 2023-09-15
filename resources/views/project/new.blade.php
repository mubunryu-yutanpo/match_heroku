@extends('layouts.parent')

@section('title', '新規案件登録')

@section('header')
    @parent
@show

@section('main')
    <h1>
        NEW案件を{{ $user->name }}さんが登録したがってるようです。
    </h1>

    <form action="{{ route('create') }}" method="post" class="p-form" enctype="multipart/form-data">
        @csrf

        <div class="c-title p-form__title">案件登録</div>

        <!-- 案件タイプ -->
        <div class="p-form__container">
            <label for="type" class="c-label p-form__label">案件の種類:</label>
                <select name="type" id="type" class="c-select p-form__select @error('type') valid-error @enderror">

                    <option value="" hidden>選択してください</option>
                    
                    @foreach ($projectType as $type)
                        <option value="{{ $type['id'] }}" {{ old('type', $project->type ?? '') == $type['id'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                    @endforeach
                </select>

                @error('type')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>


        <!-- 案件名 -->
        <div class="p-form__container">
            <label for="title" class="c-label p-form__label">タイトル:</label>
                <input id="title" type="text" class="c-input p-form__input @error('title') valid-error @enderror" name="title" value="{{ old('title', $user->title) }}" required autocomplete="title" autofocus>
                @error('title')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- 料金（上限） -->
        <div class="p-form__container">
            <label for="upperPrice" class="c-label p-form__label">料金の上限:</label>
                <input id="upperPrice" type="number" class="c-input p-form__input @error('upperPrice') valid-error @enderror" name="upperPrice" value="{{ old('upperPrice') }}" required autofocus placeholder="〜99999">（単位：千円）
                @error('upperPrice')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- 料金（下限） -->
        <div class="p-form__container">
            <label for="lowerPrice" class="c-label p-form__label">料金の下限:</label>
                <input id="lowerPrice" type="number" class="c-input p-form__input @error('lowerPrice') valid-error @enderror" name="lowerPrice" value="{{ old('lowerPrice') }}" required autofocus placeholder="1〜">（単位：千円）
                @error('lowerPrice')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- サムネ画像 -->
        <div id="app">
            <thumbnail-preview-component :project_id="{{ $project->id ?? 'null' }}"></thumbnail-preview-component>
        </div>

        <!-- 案件内容 -->
        <div class="p-form__container">
            <label for="content" class="c-label p-form__label">案件の内容:</label>
                <textarea name="content" id="content" cols="30" rows="10" class="c-textarea p-form__textarea" placeholder="2,000文字以内で入力してください">{{ old('content') }}</textarea>
                @error('content')
                    <span class="c-error-text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <div class="p-submit">
            <button class="c-button p-submit__button">登録する！</button>
        </div>

    </form>


@endsection

@section('footer')
    @parent
@show