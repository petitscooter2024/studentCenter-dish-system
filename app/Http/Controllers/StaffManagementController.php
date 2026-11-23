
<?php



use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffManagementController extends Controller
{
    /**
     * Display all staff members.
     */
    public function index(): View
    {
        $staffMembers = User::where('role', 'staff')
            ->latest()
            ->get();

        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Create a new staff account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff',
        ]);

        return back()->with('success', 'Staff account created successfully.');
    }

    /**
     * Delete a staff account.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->role === 'staff', 404);

        $user->delete();

        return back()->with('success', 'Staff account deleted successfully.');
    }
}
