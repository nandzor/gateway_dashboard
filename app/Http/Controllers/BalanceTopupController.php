<?php

namespace App\Http\Controllers;

use App\Models\BalanceTopup;
use App\Models\Client;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BalanceTopupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BalanceTopup::with(['client', 'user'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $topups = $query->paginate(20);
        $clients = Client::select(['id', 'client_name'])->get();

        return view('balance-topups.index', compact('topups', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('is_active', 1)->get();
        return view('balance-topups.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                // Get current balance
                $balance = Balance::where('client_id', $request->client_id)->first();
                $currentBalance = $balance ? $balance->balance : 0;

                // Create topup record
                BalanceTopup::create([
                    'client_id' => $request->client_id,
                    'amount' => $request->amount,
                    'previous_balance' => $currentBalance,
                    'new_balance' => $currentBalance + $request->amount,
                    'payment_method' => $request->payment_method,
                    'reference_number' => $request->reference_number,
                    'notes' => $request->notes,
                    'status' => 'pending',
                ]);
            });

            return redirect()->route('balance-topups.index')
                ->with('success', 'Topup berhasil diajukan dan menunggu persetujuan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat topup: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BalanceTopup $balanceTopup)
    {
        $balanceTopup->load(['client', 'user']);
        return view('balance-topups.show', compact('balanceTopup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BalanceTopup $balanceTopup)
    {
        if (!$balanceTopup->isPending()) {
            return redirect()->route('balance-topups.index')
                ->with('error', 'Hanya topup yang menunggu persetujuan yang dapat diedit.');
        }

        $clients = Client::where('is_active', 1)->get();
        return view('balance-topups.edit', compact('balanceTopup', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BalanceTopup $balanceTopup)
    {
        if (!$balanceTopup->isPending()) {
            return redirect()->route('balance-topups.index')
                ->with('error', 'Hanya topup yang menunggu persetujuan yang dapat diedit.');
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $balanceTopup->update([
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'new_balance' => $balanceTopup->previous_balance + $request->amount,
            ]);

            return redirect()->route('balance-topups.index')
                ->with('success', 'Topup berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui topup: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BalanceTopup $balanceTopup)
    {
        if (!$balanceTopup->isPending()) {
            return redirect()->route('balance-topups.index')
                ->with('error', 'Hanya topup yang menunggu persetujuan yang dapat dihapus.');
        }

        try {
            $balanceTopup->delete();

            return redirect()->route('balance-topups.index')
                ->with('success', 'Topup berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('balance-topups.index')
                ->with('error', 'Terjadi kesalahan saat menghapus topup: ' . $e->getMessage());
        }
    }

    /**
     * Approve a topup
     */
    public function approve(Request $request, BalanceTopup $balanceTopup)
    {
        if (!$balanceTopup->isPending()) {
            return redirect()->back()
                ->with('error', 'Hanya topup yang menunggu persetujuan yang dapat disetujui.');
        }

        try {
            $success = $balanceTopup->approve(Auth::user());

            if ($success) {
                return redirect()->back()
                    ->with('success', 'Topup berhasil disetujui dan saldo klien telah diperbarui.');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal menyetujui topup. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui topup: ' . $e->getMessage());
        }
    }

    /**
     * Reject a topup
     */
    public function reject(Request $request, BalanceTopup $balanceTopup)
    {
        if (!$balanceTopup->isPending()) {
            return redirect()->back()
                ->with('error', 'Hanya topup yang menunggu persetujuan yang dapat ditolak.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $success = $balanceTopup->reject(Auth::user(), $request->reason);

            if ($success) {
                return redirect()->back()
                    ->with('success', 'Topup berhasil ditolak.');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal menolak topup. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak topup: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a topup
     */
    public function cancel(Request $request, BalanceTopup $balanceTopup)
    {
        if (!$balanceTopup->isPending()) {
            return redirect()->back()
                ->with('error', 'Hanya topup yang menunggu persetujuan yang dapat dibatalkan.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $success = $balanceTopup->cancel(Auth::user(), $request->reason);

            if ($success) {
                return redirect()->back()
                    ->with('success', 'Topup berhasil dibatalkan.');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal membatalkan topup. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan topup: ' . $e->getMessage());
        }
    }
}
