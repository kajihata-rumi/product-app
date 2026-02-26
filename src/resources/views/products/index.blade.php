<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>

<body class="page-products-index">
    {{-- ヘッダー --}}
    <header class="header">
        <div class="header__logo">mogitate</div>
    </header>

    <div class="products-page">

        {{-- ヘッダー（タイトル＋追加ボタン） --}}
        <div class="page-header">
            @if (!empty(request('keyword')))
            <h1 class="page-title">“{{ request('keyword') }}”の商品一覧</h1>
            @else
            <h1 class="page-title">商品一覧</h1>
            @endif

            @if (request()->routeIs('products.index'))
            <a href="{{ route('products.register') }}" class="btn-add">＋ 商品を追加</a>
            @endif
        </div>

        <div class="layout">

            {{-- サイドバー --}}
            <aside class="sidebar">

                {{-- 検索 --}}
                <form method="GET" action="{{ url('/products/search') }}">
                    <div class="filter__search">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="商品名で検索"
                            class="filter__input">
                        <button type="submit" class="filter__btn">検索</button>
                    </div>
                </form>

                {{-- 並び替え --}}
                <h2 class="filter__label">価格順で表示</h2>

                <form method="GET" action="{{ url('/products') }}">
                    <select name="sort" class="filter__select" onchange="this.form.submit()">
                        <option value="" {{ (request('sort') ?? '') === '' ? 'selected' : '' }}>
                            価格で並び替え
                        </option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                            高い順に表示
                        </option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                            低い順に表示
                        </option>
                    </select>
                </form>

                {{-- ソートチップ --}}
                @php
                $sortLabel = match(request('sort')) {
                'price_desc' => '高い順に表示',
                'price_asc' => '低い順に表示',
                default => null,
                };
                @endphp

                @if ($sortLabel)
                <div class="sort-chip">
                    <span class="sort-chip__text">{{ $sortLabel }}</span>
                    <a class="sort-chip__close" href="{{ url('/products') }}">×</a>
                </div>
                @endif

            </aside>

            {{-- メインコンテンツ --}}
            <main class="content">
                <div class="product-grid">
                    @foreach ($products as $product)
                    <a href="{{ route('products.detail', $product->id) }}" class="product-card">

                        <div class="product-card__img">
                            @php
                            $storagePath = 'storage/uploads/' . $product->image;
                            $imagePath = file_exists(public_path($storagePath))
                            ? $storagePath
                            : 'fruits-img/' . $product->image;
                            @endphp

                            <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}">
                        </div>

                        <div class="product-card__meta">
                            <div class="product-card__name">{{ $product->name }}</div>
                            <div class="product-card__price">¥{{ number_format($product->price) }}</div>
                        </div>

                    </a>
                    @endforeach
                </div>

                {{-- ページネーション --}}
                {{ $products->links('vendor.pagination.custom') }}
            </main>

        </div>
    </div>
</body>

</html>