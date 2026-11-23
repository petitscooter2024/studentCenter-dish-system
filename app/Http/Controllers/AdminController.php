
<?php



use App\Models\WeeklyMenu;
use App\Models\Rating;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): View
    {
        $totalMenus = WeeklyMenu::where('is_active', true)->count();

        $totalRatings = Rating::count();

        $totalStaff = User::where('role', 'staff')->count();

        $recentRatings = Rating::with([
            'weeklyMenuItem.foodItem',
            'dailyMenuItem.foodItem',
            'dailyMenuItem.dailyMenu',
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMenus',
            'totalRatings',
            'totalStaff',
            'recentRatings'
        ));
    }

    /**
     * Display all ratings.
     */
    public function ratings(): View
    {
        $ratings = Rating::with([
            'weeklyMenuItem.foodItem',
            'dailyMenuItem.foodItem',
            'dailyMenuItem.dailyMenu',
        ])
            ->latest()
            ->paginate(15);

        return view('admin.ratings.index', compact('ratings'));
    }
}
