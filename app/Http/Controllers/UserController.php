<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    public function login(Request $request)
{
    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user) {
            $message = $user->role === 'admin' 
                ? 'Login successfully as Admin' 
                : 'Login successfully as Staff';

            return redirect()->route('dashboard')->with('success', $message);
        }
    }

    return redirect()->back()->with('failed', 'Email and password do not match, please try again!');
}

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Successfully logged out.');
    }
    
    public function dashboard()
{
    $user = Auth::user();

    $salesPerDay = DB::table('transactions')
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_sales'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy(DB::raw('DATE(created_at)'))
        ->get();

    $productSales = DB::table('details')
        ->join('products', 'details.product_id', '=', 'products.id')
        ->select('products.name', DB::raw('SUM(details.quantity) as total_sold'))
        ->groupBy('products.name')
        ->get();

    $today = Carbon::today();
    $totalTransactionsToday = Transaction::whereDate('created_at', $today)->count();
    $lastUpdated = Transaction::orderBy('created_at', 'desc')->first();

    return view('home.dashboard', compact('user', 'salesPerDay', 'productSales', 'totalTransactionsToday', 'lastUpdated'));
}

    // Menampilkan semua pengguna
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan data pengguna baru
    public function store(Request $request)
    { 

        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|string|min:6',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,staff',
            // 'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Simpan gambar jika ada
        // $imagePath = $request->file('image') ? $request->file('image')->store('users', 'public') : null;

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Enkripsi password
            'email' => $request->email,
            'role' => $request->role,
            // 'image' => $imagePath,
        ]);

        // Set session success message
        session()->flash('success', ['type' => 'created', 'message' => 'Data successfully created.']);

        // Redirect back to the users page
        return redirect()->route('users.index');
    }

    // Menampilkan satu user berdasarkan ID
    public function show($id)
    {
        // $user = User::findOrFail($id);
        // return view('users.show', compact('user'));
    }

    public function up($id)
    {
        $user = User::findOrFail($id);
        return view('users.update', compact('user'));
    }

    // Mengupdate data pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'sometimes|required|unique:users,username,' . $id,
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'role' => 'sometimes|required|in:admin,staff',
            // 'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Perbarui gambar jika ada perubahan
        // if ($request->hasFile('image')) {
        //     if ($user->image) {
        //         Storage::disk('public')->delete($user->image);
        //     }
        //     $user->image = $request->file('image')->store('users', 'public');
        // }

        $user->update($request->only(['username', 'email', 'role']));

        // Set session success message
        session()->flash('success', ['type' => 'created', 'message' => 'Data successfully updated.']);

        // Redirect back to the users page
        return redirect()->route('users.index');
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return redirect()->back()->withErrors(['message' => 'Cannot delete an admin user.']);
        }    

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        // Set session success message
        session()->flash('success', ['type' => 'deleted', 'message' => 'Data successfully deleted.']);

        // Redirect back to the users page
        return redirect()->route('users.index');
    }
}
