@extends('layouts.parent')

@section('title', '案件の編集')

@section('header')
    @parent
@show

@section('main')
    <h1>
        {{ $user->name }}さんの、案件の編集・削除ページさ。
        案件のIDは{{ $project->id }}です。
    </h1>

    <form action="{{ route('project.update', $project->id) }}" method="post" class="p-form" enctype="multipart/form-data">
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
                    <span class="c-error c-error--text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>


        <!-- 案件名 -->
        <div class="p-form__container">
            <label for="title" class="c-label p-form__label">タイトル:</label>
                <input id="title" type="text" class="c-input p-form__input @error('title') valid-error @enderror" name="title" value="{{ old('title', $project->title) }}" required autocomplete="title" autofocus>
                @error('title')
                    <span class="c-error c-error--text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- 料金（上限） -->
        <div class="p-form__container">
            <label for="upperPrice" class="c-label p-form__label">料金（上限）:</label>
                <input id="upperPrice" type="number" class="c-input p-form__input @error('upperPrice') valid-error @enderror" name="upperPrice" value="{{ old('upperPrice', $savedUpperPrice ) }}" required autofocus placeholder="〜999999">（単位：千円）
                @error('upperPrice')
                    <span class="c-error c-error--text p-form__error-text" role="alert">
                        {{ $message }}
                    </span>
                @enderror
        </div>

        <!-- 料金（下限） -->
        <div class="p-form__container">
            <label for="lowerPrice" class="c-label p-form__label">料金（下限）:</label>
                <input id="lowerPrice" type="number" class="c-input p-form__input @error('lowerPrice') valid-error @enderror" name="lowerPrice" value="{{ old('lowerPrice', $savedLowerPrice ) }}" required autofocus placeholder="1〜">（単位：千円）
                @error('lowerPrice')
                    <span class="c-error c-error--text p-form__error-text" role="alert">
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
            <!-- テキストカウンター用コンポーネント -->
            <div id="counter">
                <text-counter-component
                    label="案件の内容"
                    name="content"
                    id="content"
                    :max="2000"
                    autocomplete="content"
                    placeholder="2,000文字以内で入力してください"
                    data="{{ $project->content }}"
                ></text-counter-component>
            </div>

            @error('content')
                <span class="c-error c-error--text p-form__error-text" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="p-submit">
            <button class="c-button p-submit__button">更新して保存</button>
        </div>

    </form>

    <form action="{{ route('project.delete', $project->id) }}" method="post" class="p-form">
        @csrf

        <div class="p-submit">
            <button class="c-button p-submit__button" onclick="return confirm('この案件を削除します。よろしいですか？')">削除</button>
        </div>
    </form>


@endsection

@section('footer')
    @parent
@show