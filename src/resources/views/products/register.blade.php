<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>

<body>
    {{-- ヘッダー（ロゴだけ） --}}
    <header class="header">
        <div class="header__logo">mogitate</div>
    </header>

    <div class="product-register">
        <h1 class="page-title">商品登録</h1>

        <form class="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 商品名 --}}
            <div class="form-row">
                <div class="form-head">
                    <label class="form-label">商品名</label>
                    <span class="badge-required">必須</span>
                </div>

                <input class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder="商品名を入力">
                @error('name')
                <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- 値段 --}}
            <div class="form-row">
                <div class="form-head">
                    <label class="form-label">値段</label>
                    <span class="badge-required">必須</span>
                </div>

                <input class="form-input" type="text" name="price" value="{{ old('price') }}" placeholder="値段を入力">
                @error('price')
                <p class="error-text">{!! nl2br(e($message)) !!}</p>
                @enderror
            </div>

            {{-- 画像 --}}
            <div class="form-row">
                <div class="form-head">
                    <label class="form-label">商品画像</label>
                    <span class="badge-required">必須</span>
                </div>

                {{-- プレビュー枠：最初は非表示 --}}
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

            {{-- 季節（複数選択） --}}
            <div class="form-row">
                <div class="form-head">
                    <label class="form-label">季節</label>
                    <span class="badge-required">必須</span>
                    <span class="badge-note">複数選択可</span>
                </div>

                @php
                // old('seasons') は配列。編集画面と同じ思想にする
                $selectedSeasons = old('seasons', []);
                @endphp

                <div class="season">
                    @foreach ($seasons as $season)
                    <label class="season-item">
                        <input
                            type="checkbox"
                            name="seasons[]"
                            value="{{ $season->id }}"
                            @checked(in_array($season->id, $selectedSeasons))>
                        <span class="season-text">{{ $season->name }}</span>
                    </label>
                    @endforeach
                </div>

                @error('seasons')
                <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            {{-- 商品説明 --}}
            <div class="form-row">
                <div class="form-head">
                    <label class="form-label">商品説明</label>
                    <span class="badge-required">必須</span>
                </div>
            </div>

            <textarea class="form-textarea" name="description" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
            @error('description')
            <p class="error-text">{!! nl2br(e($message)) !!}</p>
            @enderror
    </div>

    <div class="form-actions">
        <a class="btn btn-back" href="{{ route('products.index') }}">戻る</a>
        <button class="btn btn-primary" type="submit">登録</button>
    </div>
    </form>
    </div>
    <script>
        document.querySelector('.file-input').addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>