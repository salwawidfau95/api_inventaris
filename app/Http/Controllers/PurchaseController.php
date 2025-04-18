<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10); // default 10

        $purchases = Purchase::with(['user', 'member'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('total_price', 'like', "%$search%")
                    ->orWhere('created_at', 'like', "%$search%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', "%$search%");
                    })
                    ->orWhereHas('member', function ($memberQuery) use ($search) {
                        $memberQuery->where('name', 'like', "%$search%");
                    });
                });
            })
            ->paginate($entries)
            ->appends($request->query()); // supaya query param tetap nempel saat pindah halaman

        return view('transactions.admin.index', compact('purchases', 'search', 'entries'));
    }

    public function create()
    {
        // $products = Product::all();
        // return view('purchases.admin.create', compact('products'));
    }

    public function confirm(Request $request)
    {
    //     $quantities = $request->quantities ?? [];

    //     $selectedProducts = [];
    //     $total = 0;

    //     foreach ($quantities as $productId => $qty) {
    //         if ($qty > 0) {
    //             $product = Product::findOrFail($productId);
    //             $price = $product->price;
    //             $subtotal = $price * $qty;

    //             $selectedProducts[] = [
    //                 'product_id' => $product->id,
    //                 'name' => $product->name,
    //                 'qty' => $qty,
    //                 'price' => $price,
    //                 'subtotal' => $subtotal,
    //             ];

    //             $total += $subtotal;
    //         }
    //     }

    //     Session::put('cart', $selectedProducts);
    //     Session::put('total', $total);

    //     return view('purchases.admin.confirm', compact('selectedProducts', 'total'));
    }

    public function finalize(Request $request)
    {
        // $request->validate([
        //     'total_payment' => 'required|numeric|min:0',
        //     'member_status' => 'required|in:member,non-member',
        // ]);

        // $cart = Session::get('cart', []);
        // $totalPrice = Session::get('total', 0);
        // $change = $request->total_payment - $totalPrice;
        // $user = Auth::user();

        // $today = now()->format('Ymd');
        // $random = strtoupper(Str::random(5));
        // $transactionCode = "TRX-{$today}-{$random}";

        // $transaction = \App\Models\Transaction::create([
        //     'transaction_code' => $transactionCode,
        //     'user_id' => $user->id,
        //     'member_id' => $request->member_status === 'member' ? $request->member_id : null,
        //     'total_price' => $totalPrice,
        //     'total_payment' => $request->total_payment,
        //     'change' => $change,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // $transactionItems = [];

        // foreach ($cart as $item) {
        //     $transactionItem = \App\Models\TransactionItem::create([
        //         'transaction_id' => $transaction->id,
        //         'product_id' => $item['product_id'],
        //         'quantity' => $item['qty'],
        //         'price' => $item['price'],
        //         'subtotal' => $item['subtotal'],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);

        //     $transactionItems[] = $transactionItem;

        //     // Kurangi stok produk jika perlu
        //     Product::where('id', $item['product_id'])->decrement('stock', $item['qty']);
        // }

        // $receiptData = [
        //     'transaction' => $transaction,
        //     'items' => $transactionItems,
        //     'total_price' => $totalPrice,
        //     'total_payment' => $request->total_payment,
        //     'change' => $change,
        //     'user' => $user,
        //     'member_points' => $request->member_status === 'member'
        //         ? optional(Member::find($request->member_id))->point ?? 0
        //         : null,
        //     'date' => now()->format('Y-m-d H:i:s'),
        //     'used_point' => $request->member_status === 'member'
        //         ? Member::find($request->member_id)->point_used ?? 0
        //         : 0,
        // ];

        // Session::forget(['cart', 'total']);

        // return view('purchases.admin.receipt', $receiptData);
    }

    /**
     * Display the specified resource.
     */
    public function show($purchaseCode)
    {
        // $purchases = Purchase::with(['product', 'member', 'user'])
        //     ->where('purchase_code', $purchaseCode)
        //     ->get();

        // if ($purchases->isEmpty()) {
        //     abort(404);
        // }

        // $firstPurchase = $purchases->first();

        // $memberStatus = $firstPurchase->member_id ? 'Member' : 'Bukan Member';
        // $memberPhone = $firstPurchase->member_id ? $firstPurchase->member->no_phone : '-';
        // $memberPoint = $firstPurchase->member_id ? $firstPurchase->member->point : '-';
        // $memberJoined = $firstPurchase->member_id ? $firstPurchase->member->created_at->format('d F Y') : '-';

        // $products = $purchases->map(function ($purchase) {
        //     return [
        //         'name' => $purchase->product->name,
        //         'qty' => $purchase->stock,
        //         'price' => $purchase->total_price,
        //         'subtotal' => $purchase->stock * $purchase->total_price,
        //     ];
        // });

        // $total = $products->sum('subtotal');

        // return view('purchase.admin.show', compact(
        //     'firstPurchase',
        //     'memberStatus',
        //     'memberPhone',
        //     'memberPoint',
        //     'memberJoined',
        //     'products',
        //     'total'
        // ));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    // Menghapus produk
    public function destroy($id)
    {
        //
    }
}