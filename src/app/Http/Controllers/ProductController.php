<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('keyword');
        $sort    = $request->query('sort'); // ← デフォルトなし（重要）

        $query = Product::with('seasons');

        // get() ではなく paginate()
        $products = $query->paginate(6)->appends($request->query());

        return view('products.index', compact('products'));
    }

    public function register()
    {
        $seasons = Season::all();
        return view('products.register', compact('seasons'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|between:0,10000',
            'image' => 'required|image|mimes:jpeg,png',
            'seasons' => 'required|array',
            'description' => 'required|max:120',
        ], [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください
            数値で入力してください
            0〜10000円以内で入力してください',
            'price.numeric' => '数値で入力してください',
            'price.between' => '0〜10000円以内で入力してください',
            'image.required' => '商品画像を登録してください
            「.png」または「.jpeg」形式でアップロードしてください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'seasons.required' => '季節を選択してください',
            'description.required' => '商品説明を入力してください
            120文字以内で入力してください',
            'description.max' => '120文字以内で入力してください',
        ]);

        // 画像保存（storage/app/public/uploads）
        $file = $request->file('image');
        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $filename = $original . '_' . time() . '.' . $ext;

        $file->storeAs('uploads', $filename, 'public');

        // ★ここが重要：DBにはファイル名（文字列）を入れる
        $validated['image'] = $filename;


        // products 保存
        $product = new Product();
        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->description = $validated['description'];
        $product->image = $validated['image']; // ←これが必須（ないと “image default value”）
        $product->save();

        // 中間テーブル
        $product->seasons()->sync($validated['seasons']);

        return redirect()->route('products.index');
    }

    public function detail($id)
    {
        $product = Product::with('seasons')->findOrFail($id);
        $seasons = Season::all();

        return view('products.detail', compact('product', 'seasons'));
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        // 画像削除（uploads運用なら不要）
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $sort = $request->sort;

        $query = Product::with('seasons');

        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        }

        $products = $query->paginate(6)->appends($request->query());
        $products->withPath(url('/products/search')); // ←これ重要

        return view('products.index', compact('products'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('seasons')->findOrFail($id);

        // 1) validate
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric|between:0,10000',
            'image' => 'required|image|mimes:jpeg,png',
            'seasons' => 'required|array',
            'description' => 'required|max:120',
        ], [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください
            数値で入力してください
            0〜10000円以内で入力してください',
            'price.numeric' => '数値で入力してください',
            'price.between' => '0〜10000円以内で入力してください',
            'image.required' => '商品画像を登録してください
            「.png」または「.jpeg」形式でアップロードしてください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'seasons.required' => '季節を選択してください',
            'description.required' => '商品説明を入力してください
            120文字以内で入力してください',
            'description.max' => '120文字以内で入力してください',
        ]);

        // 2) 画像
        if ($request->hasFile('image')) {
            $original = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = $request->file('image')->getClientOriginalExtension();
            $filename = $original . '_' . time() . '.' . $ext;

            // storageに保存
            $request->file('image')->storeAs('uploads', $filename, 'public');

            $validated['image'] = $filename;
        }

        // 3) Product本体更新
        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $filename, // ←ここ重要
        ]);

        // 4) seasons（中間テーブル）
        $product->seasons()->sync($validated['seasons']);

        // 5) 詳細へ戻す（今の詳細ルートに合わせて）
        return redirect()->route('products.detail', $product->id);
    }
}
