
<?php



use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display all categories.
     */
    public function index(): View
    {
        $categories = Category::withCount('foodItems')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return back()->with('success', 'Category added successfully.');
    }

    /**
     * Update an existing category.
     */
    public function update(
        Request $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->foodItems()->exists()) {
            return back()->withErrors([
                'category' => 'This category contains food items. Reassign or remove those food items before deleting it.',
            ]);
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
