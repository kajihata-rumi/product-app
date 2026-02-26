<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>

<body>
    {{-- ヘッダー（ロゴだけ） --}}
    <header class="header">
        <div class="header__logo">mogitate</div>
    </header>

    {{-- 更新フォーム --}}
    <form class="product-form"
        action="{{ route('products.update', $product->id) }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf

        {{-- 上：2カラム --}}
        <div class="product-form__top">
            {{-- 左：画像 + ファイル --}}
            <div class="product-form__left">

                <div class="image-wrap">
                    <div class="breadcrumb">
                        <a href="{{ route('products.index') }}">商品一覧</a> &gt; {{ $product->name }}
                    </div>
                </div>

                @php
                $storagePath = 'storage/uploads/' . $product->image;
                $imagePath = file_exists(public_path($storagePath))
                ? $storagePath
                : 'fruits-img/' . $product->image;
                @endphp
                <img src="{{ asset($product->image) }}" class="product-image" alt="{{ $product->name }}">

                <input class="product-form__file" type="file" name="image">
                @error('image') <p class="error-text">{!! nl2br(e($message)) !!}</p> @enderror
            </div>

            {{-- 右：入力欄 --}}
            <div class="product-form__right">
                <label class="form-label">商品名</label>
                <input class="form-input" type="text" name="name" value="{{ old('name', $product->name) }}">
                @error('name') <p class="error-text">{!! nl2br(e($message)) !!}</p> @enderror

                <label class="form-label">値段</label>
                <input class="form-input" type="text" name="price" value="{{ old('price', $product->price) }}">
                @error('price') <p class="error-text">{!! nl2br(e($message)) !!}</p> @enderror

                <p class="form-label">季節</p>

                @php
                $selectedSeasons = old('seasons', $product->seasons->pluck('id')->toArray());
                @endphp

                <div class="season">
                    @foreach($seasons as $season)
                    <label>
                        <input type="checkbox"
                            name="seasons[]"
                            value="{{ $season->id }}"
                            @checked(in_array($season->id, $selectedSeasons))>
                        {{ $season->name }}
                    </label>
                    @endforeach
                </div>

                @error('seasons')
                <p class="error-text">{!! nl2br(e($message)) !!}</p>
                @enderror
            </div>
        </div>

        {{-- 下：説明（横いっぱい） --}}
        <div class="product-form__bottom">
            <label class="form-label">商品説明</label>
            <textarea class="form-textarea" name="description">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="error-text">{!! nl2br(e($message)) !!}</p>@enderror
        </div>

        {{-- ボタン --}}
        <div class="actions-wrap">
            <a href="{{ route('products.index') }}" class="btn btn--gray">戻る</a>
            <button type="submit" class="btn btn--yellow">変更を保存</button>
    </form>

    {{-- 削除（右下のゴミ箱） --}}
    <form class="delete" action="{{ route('products.delete', $product->id) }}" method="POST">
        @csrf
        <button class="delete__btn" type="submit" onclick="return confirm('削除しますか？')">
            <img src="{{ asset('images/trash.png') }}" alt="削除">
        </button>
    </form>
    </div>
    </div>
</body>

</html>