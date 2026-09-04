<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CryptoWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CryptoWalletController extends Controller
{
    protected const ALLOWED_IMAGE_MIMES = 'jpeg,png,jpg,webp';
    protected const MAX_IMAGE_SIZE_KB = 2048;
    protected const UPLOAD_DIRECTORY = 'crypto-wallets';

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $wallets = CryptoWallet::ordered()->get();

        return view('admin.crypto-wallets.index', compact('wallets'));
    }

    public function create()
    {
        return view('admin.crypto-wallets.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            $data['slug'] = $this->makeSlugUnique($data['slug']);
        }

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('wallet_image')) {
            $data['wallet_image'] = $this->uploadImage($request->file('wallet_image'));
        }

        if ($request->hasFile('qr_code_image')) {
            $data['qr_code_image'] = $this->uploadImage($request->file('qr_code_image'));
        }

        CryptoWallet::create($data);

        return redirect()->route('admin.crypto-wallets.index')
            ->with('success', 'Crypto wallet created successfully.');
    }

    public function show(CryptoWallet $cryptoWallet)
    {
        $bookingsCount = $cryptoWallet->bookings()->count();
        $totalRevenue = $cryptoWallet->bookings()
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        return view('admin.crypto-wallets.show', compact('cryptoWallet', 'bookingsCount', 'totalRevenue'));
    }

    public function edit(CryptoWallet $cryptoWallet)
    {
        return view('admin.crypto-wallets.edit', compact('cryptoWallet'));
    }

    public function update(Request $request, CryptoWallet $cryptoWallet)
    {
        $data = $this->validateRequest($request, $cryptoWallet);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
            $data['slug'] = $this->makeSlugUnique($data['slug'], $cryptoWallet->id);
        }

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('wallet_image')) {
            $this->deleteStoredFile($cryptoWallet->wallet_image);
            $data['wallet_image'] = $this->uploadImage($request->file('wallet_image'));
        }

        if ($request->hasFile('qr_code_image')) {
            $this->deleteStoredFile($cryptoWallet->qr_code_image);
            $data['qr_code_image'] = $this->uploadImage($request->file('qr_code_image'));
        }

        $cryptoWallet->update($data);

        return redirect()->route('admin.crypto-wallets.index')
            ->with('success', 'Crypto wallet updated successfully.');
    }

    public function destroy(CryptoWallet $cryptoWallet)
    {
        if ($cryptoWallet->bookings()->count() > 0) {
            return back()->with('error', 'Cannot delete a crypto wallet that has been used in bookings. Deactivate it instead.');
        }

        $this->deleteStoredFile($cryptoWallet->wallet_image);
        $this->deleteStoredFile($cryptoWallet->qr_code_image);

        $cryptoWallet->delete();

        return redirect()->route('admin.crypto-wallets.index')
            ->with('success', 'Crypto wallet deleted successfully.');
    }

    public function toggleStatus(CryptoWallet $cryptoWallet)
    {
        $oldStatus = $cryptoWallet->is_active;
        $newStatus = !$cryptoWallet->is_active;

        $cryptoWallet->update(['is_active' => $newStatus]);
        $cryptoWallet->refresh();

        $message = 'Crypto wallet "' . $cryptoWallet->name . '" '
            . ($cryptoWallet->is_active ? 'activated' : 'deactivated') . ' successfully.';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $cryptoWallet->is_active,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.crypto-wallets.index')
            ->with('success', $message);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'crypto_wallet_ids' => 'required|array',
            'crypto_wallet_ids.*' => 'exists:crypto_wallets,id',
        ]);

        foreach ($request->crypto_wallet_ids as $index => $id) {
            CryptoWallet::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Crypto wallet order updated successfully.',
        ]);
    }

    protected function validateRequest(Request $request, ?CryptoWallet $cryptoWallet = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('crypto_wallets')->ignore($cryptoWallet?->id)],
            'wallet_address' => 'required|string|max:500',
            'wallet_image' => 'nullable|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE_KB,
            'qr_code_image' => 'nullable|image|mimes:' . self::ALLOWED_IMAGE_MIMES . '|max:' . self::MAX_IMAGE_SIZE_KB,
            'instructions' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ];

        return $request->validate($rules);
    }

    protected function uploadImage($file)
    {
        $filename = Str::random(24) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs(self::UPLOAD_DIRECTORY, $filename, 'public');
    }

    protected function deleteStoredFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function makeSlugUnique(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $counter = 1;

        while (CryptoWallet::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}