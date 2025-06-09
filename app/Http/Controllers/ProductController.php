<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    // Відображаємо каталог, групуючи по категоріях
    public function index(Request $request)
    {
        // 1. Дізнаємося, хто зараз залогінений (якщо потрібно в шаблоні)
        $userId = $request->session()->get('user_id');
        $user   = $userId ? DB::table('users')->find($userId) : null;

        // 2. Параметри з GET: пошук і вибрана категорія
        $search     = $request->input('search');
        $categoryId = $request->input('category');

        // 3. Беремо всі категорії для випадачки фільтра
        $allCategories = Category::all();

        // 4. Будуємо запит на товари із фільтрацією
        $productsQuery = Product::with('category')
            // фільтр по пошуку в назві
            ->when($search, function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%");
            })
            // фільтр по вибраній категорії
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            // за потреби: сортування, наприклад, за назвою
            //->orderBy('name')

            // пагінуємо по 9 товарів
            ->paginate(9)
            // щоб при натисканні “Завантажити ще” лишалися параметри search/category
            ->appends([
                'search'   => $search,
                'category' => $categoryId,
            ]);

        // 5. Передаємо у в’ю
        return view('catalog', compact(
            'user',
            'allCategories',
            'productsQuery',
            'search',
            'categoryId'
        ));
    }

    // Форма додавання товару
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Обробка додавання товару
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $file = $request->file('photo');

        $slug     = Str::slug($data['name']);
        $ext      = $file->getClientOriginalExtension();
        $filename = "{$slug}.{$ext}";

        if (Storage::disk('public')->exists("products/{$filename}")) {
            $filename = "{$slug}-".now()->format('YmdHis').".{$ext}";
        }

        $data['photo'] = $file->storeAs('products', $filename, 'public');

        Product::create($data);

        return redirect()->route('catalog')
                         ->with('success', 'Товар додано');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product','categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($product->photo);

            $file     = $request->file('photo');
            $slug     = Str::slug($data['name']);
            $ext      = $file->getClientOriginalExtension();
            $filename = "{$slug}.{$ext}";
            if (Storage::disk('public')->exists("products/{$filename}")) {
                $filename = "{$slug}-".now()->format('YmdHis').".{$ext}";
            }
            $data['photo'] = $file->storeAs('products', $filename, 'public');
        }

        $product->update($data);

        return redirect()->route('catalog')
                         ->with('success', 'Товар оновлено');
    }

    // Форма видалення товару
    public function destroy(Product $product)
    {
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }
        $product->delete();

        return redirect()->route('catalog')
                         ->with('success', 'Товар видалено');
    }

}
