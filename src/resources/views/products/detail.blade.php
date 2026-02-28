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

    <main class="container detail-page">

        {{-- 更新フォーム --}}
        <form id="update-form" class="product-form"
            action="{{ route('products.update', $product->id) }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf

            {{-- パンくず--}}
            <div class="breadcrumb">
                <a href="{{ route('products.index') }}">商品一覧</a> &gt; {{ $product->name }}
            </div>

            {{-- 上：2カラム --}}
            <div class="product-form__top">
                {{-- 左：画像 + ファイル --}}
                <div class="product-form__left">
                    <div class="image-wrap">
                        <img
                            class="product-form__image"
                            src="{{ str_contains($product->image, '/')
                        ? asset($product->image)
                        : asset('storage/uploads/' . $product->image) }}"
                            alt="{{ $product->name }}">
                    </div>

                    @php
                    $storagePath = 'storage/uploads/' . $product->image;
                    $imagePath = file_exists(public_path($storagePath))
                    ? $storagePath
                    : 'fruits-img/' . $product->image;
                    @endphp

                </div>

                {{-- プレビュー枠--}}
                <img
                    id="imagePreview"
                    class="image-preview"
                    src=""
                    alt="プレビュー"
                    style="display:none;">

                <input class="file-input" type="file" name="image">
                @error('image')
                <p class="error-text">{!! nl2br(e($message)) !!}</p>
                @enderror
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
                <div class="product-form__narrow">
                    <label class="form-label">商品説明</label>
                    <textarea class="form-textarea" name="description">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                    <p class="error-text">{!! nl2br(e($message)) !!}</p>
                    @enderror
                </div>
            </div>

            {{-- ボタンエリア --}}
            <div class="actions-wrap">
                <div class="actions-center">
                    <a href="{{ route('products.index') }}" class="btn btn-gray">戻る</a>
                    <button type="submit" class="btn btn-yellow">変更を保存</button>
                </div>

                {{-- ※削除ボタンは「更新formの中」に置いてOK（ただし formタグは置かない） --}}
                <button
                    class="delete__btn"
                    type="submit"
                    form="delete-form"
                    onclick="return confirm('削除しますか？')">
                    <img src="{{ asset('images/trash.png') }}" alt="削除">
                </button>
            </div>

            {{-- 削除フォーム（更新フォームの外に出す！） --}}
            <form id="delete-form" action="{{ route('products.delete', $product->id) }}" method="POST">
                @csrf
            </form>
    </main>
    <script>
        const fileInput = document.querySelector('.file-input');
        const preview = document.getElementById('imagePreview');

        if (fileInput && preview) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
</body>

</html>