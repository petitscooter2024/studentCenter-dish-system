
<?php



use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\MenuSuggestion;
use App\Models\WeeklyMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuSuggestionController extends Controller
{
    public function index(): View
    {
        $weeklyMenus = WeeklyMenu::with([
            'weeklyMenuItems.foodItem.category',
        ])
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->get();

        $categories = Category::orderBy('name')->get();

        $foodItems = FoodItem::with('category')
            ->orderBy('name')
            ->get();

        $suggestions = MenuSuggestion::with([
            'weeklyMenu',
            'weeklyMenuItem.foodItem',
            'foodItem.category',
            'category',
            'submitter',
        ])
            ->where('submitted_by', Auth::id())
            ->latest()
            ->get();

        return view('staff.suggestions.index', compact(
            'weeklyMenus',
            'categories',
            'foodItems',
            'suggestions'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weekly_menu_id' => [
                'required',
                'exists:weekly_menus,id',
            ],
            'action' => [
                'required',
                'in:add,remove,replace,remove_category',
            ],
            'weekly_menu_item_id' => [
                'nullable',
                'exists:weekly_menu_items,id',
            ],
            'category_id' => [
                'nullable',
                'exists:categories,id',
            ],
            'food_item_id' => [
                'nullable',
                'exists:food_items,id',
            ],
            'reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $weeklyMenu = WeeklyMenu::where('is_active', true)
            ->findOrFail($validated['weekly_menu_id']);

        $action = $validated['action'];
        $categoryId = $validated['category_id'] ?? null;
        $foodItemId = $validated['food_item_id'] ?? null;
        $weeklyMenuItemId = $validated['weekly_menu_item_id'] ?? null;

        // Validate category selection.
        if (in_array($action, ['add', 'replace', 'remove_category'])) {
            if (!$categoryId) {
                return back()->withErrors([
                    'category_id' => 'Please select a category.',
                ])->withInput();
            }
        }

        // Validate the existing meal selection.
        if (in_array($action, ['remove', 'replace'])) {
            if (!$weeklyMenuItemId) {
                return back()->withErrors([
                    'weekly_menu_item_id' => 'Please select the existing meal.',
                ])->withInput();
            }

            $itemExists = $weeklyMenu->weeklyMenuItems()
                ->whereKey($weeklyMenuItemId)
                ->exists();

            if (!$itemExists) {
                return back()->withErrors([
                    'weekly_menu_item_id' => 'The selected meal does not belong to this weekday.',
                ])->withInput();
            }
        }

        // Validate the new food item.
        if (in_array($action, ['add', 'replace'])) {
            if (!$foodItemId) {
                return back()->withErrors([
                    'food_item_id' => 'Please select the food item.',
                ])->withInput();
            }

            $foodItem = FoodItem::where('id', $foodItemId)
                ->where('category_id', $categoryId)
                ->first();

            if (!$foodItem) {
                return back()->withErrors([
                    'food_item_id' => 'The selected food item does not belong to the selected category.',
                ])->withInput();
            }

            $alreadyExists = $weeklyMenu->weeklyMenuItems()
                ->where('food_item_id', $foodItemId)
                ->exists();

            if ($alreadyExists) {
                return back()->withErrors([
                    'food_item_id' => 'This meal is already listed for the selected weekday.',
                ])->withInput();
            }
        }

        // Validate category removal.
        if ($action === 'remove_category') {
            $categoryExistsInMenu = $weeklyMenu->weeklyMenuItems()
                ->whereHas('foodItem', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                })
                ->exists();

            if (!$categoryExistsInMenu) {
                return back()->withErrors([
                    'category_id' => 'This category has no meals in the selected weekday menu.',
                ])->withInput();
            }
        }

        // Save the suggestion.
        MenuSuggestion::create([
            'weekly_menu_id' => $weeklyMenu->id,
            'weekly_menu_item_id' => $weeklyMenuItemId,
            'category_id' => $categoryId,
            'food_item_id' => $foodItemId,
            'action' => $action,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
            'submitted_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Your weekly menu suggestion has been submitted successfully.'
        );
    }
}
