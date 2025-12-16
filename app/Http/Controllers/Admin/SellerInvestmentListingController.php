<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerInvestmentListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerInvestmentListingController extends Controller
{
    /**
     * Store a new seller investment listing created by super admin.
     */
    public function storeInvestment(Request $request)
    {
        $data = $request->validate([
            'user_id'               => 'required|exists:users,id',

            'project_name'          => 'required|string|max:255',
            'project_type'          => 'nullable|string|max:200',

            // ✅ NEW: Location IDs
            'country_id'            => 'nullable|integer',
            'state_id'              => 'nullable|integer',
            'district_id'           => 'nullable|integer',

            // Location (keep for backward compatibility / display)
            'district'              => 'nullable|string|max:100',
            'micro_location'        => 'nullable|string|max:255',
            'landmark'              => 'nullable|string|max:255',
            'map_link'              => 'nullable|string',

            // Investment
            'project_cost'          => 'nullable|numeric',
            'investment_required'   => 'nullable|numeric',
            'profit_sharing_model'  => 'nullable|string|max:255',
            'payback_period'        => 'nullable|string|max:100',

            // Project status
            'project_status'        => 'nullable|string|max:150',
            'completion_percent'    => 'nullable|integer|min:0|max:100',

            // Documents (multiple files)
            'documents.*'           => 'nullable|file',
        ]);

        $adminId = Auth::guard('admin')->id();
        $user    = User::find($data['user_id']);

        // ---- FILE UPLOADS (DOCUMENTS JSON) ----
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                if ($doc) {
                    $documents[] = $doc->store('investment/documents', 'public');
                }
            }
        }

        SellerInvestmentListing::create([
            'user_id'             => $data['user_id'],
            'created_by_admin_id' => $adminId,

            'property_code'       => $this->generatePropertyCode('INV'),
            'status'              => 'normal',

            'project_name'        => $data['project_name'],
            'project_type'        => $data['project_type'] ?? null,

            // ✅ NEW: location IDs
            'country_id'          => $data['country_id'] ?? null,
            'state_id'            => $data['state_id'] ?? null,
            'district_id'         => $data['district_id'] ?? null,

            // Location strings
            'district'            => $data['district'] ?? null,
            'micro_location'      => $data['micro_location'] ?? null,
            'landmark'            => $data['landmark'] ?? null,
            'map_link'            => $data['map_link'] ?? null,

            'project_cost'        => $data['project_cost'] ?? null,
            'investment_required' => $data['investment_required'] ?? null,
            'profit_sharing_model'=> $data['profit_sharing_model'] ?? null,
            'payback_period'      => $data['payback_period'] ?? null,

            'project_status'      => $data['project_status'] ?? null,
            'completion_percent'  => $data['completion_percent'] ?? null,

            'documents'           => $documents ?: null,
        ]);

        return back()->with(
            'success',
            'Seller investment listing saved successfully for user: ' . ($user?->name ?? $data['user_id'])
        );
    }

    /**
     * Simple property code generator: INV001, INV002, etc.
     */
    protected function generatePropertyCode(string $prefix = 'INV'): string
    {
        $last = SellerInvestmentListing::orderByDesc('id')->first();

        if ($last && $last->property_code) {
            $number = (int) preg_replace('/\D/', '', $last->property_code);
            $number++;
        } else {
            $number = 1;
        }

        return sprintf('%s%03d', $prefix, $number);
    }

    public function showInvestment(SellerInvestmentListing $investment)
    {
        $seller = $investment->user;

        return view('admin.seller.investment.show', [
            'investment' => $investment,
            'seller'     => $seller,
        ]);
    }

    public function destroyInvestment(SellerInvestmentListing $investment)
    {
        $sellerId = $investment->user_id;
        $investment->delete();

        return redirect()
            ->route('admin.seller.properties.index', ['seller' => $sellerId, 'tab' => 'investment'])
            ->with('success', 'Investment listing deleted successfully.');
    }

    public function updateInvestment(Request $request, SellerInvestmentListing $investment)
    {
        $data = $request->validate([
            'project_name'          => 'required|string|max:255',
            'project_type'          => 'nullable|string|max:200',

            // ✅ NEW: Location IDs
            'country_id'            => 'nullable|integer',
            'state_id'              => 'nullable|integer',
            'district_id'           => 'nullable|integer',

            'district'              => 'nullable|string|max:100',
            'micro_location'        => 'nullable|string|max:255',
            'landmark'              => 'nullable|string|max:255',
            'map_link'              => 'nullable|string',

            'project_cost'          => 'nullable|numeric',
            'investment_required'   => 'nullable|numeric',
            'profit_sharing_model'  => 'nullable|string|max:255',
            'payback_period'        => 'nullable|string|max:100',

            'project_status'        => 'nullable|string|max:150',
            'completion_percent'    => 'nullable|integer|min:0|max:100',

            'status'                => 'required|in:normal,hot,urgent,sold,booked,off_market',

            // documents
            'documents.*'           => 'nullable|file',
        ]);

        // track who updated (re-using existing column)
        $adminId = Auth::guard('admin')->id();
        $investment->created_by_admin_id = $adminId;

        $investment->project_name         = $data['project_name'];
        $investment->project_type         = $data['project_type'] ?? null;

        // ✅ NEW: location IDs
        $investment->country_id           = $data['country_id'] ?? $investment->country_id;
        $investment->state_id             = $data['state_id'] ?? $investment->state_id;
        $investment->district_id          = $data['district_id'] ?? $investment->district_id;

        // Location strings
        $investment->district             = $data['district'] ?? $investment->district;
        $investment->micro_location       = $data['micro_location'] ?? null;
        $investment->landmark             = $data['landmark'] ?? null;
        $investment->map_link             = $data['map_link'] ?? null;

        $investment->project_cost         = $data['project_cost'] ?? null;
        $investment->investment_required  = $data['investment_required'] ?? null;
        $investment->profit_sharing_model = $data['profit_sharing_model'] ?? null;
        $investment->payback_period       = $data['payback_period'] ?? null;

        $investment->project_status       = $data['project_status'] ?? null;
        $investment->completion_percent   = $data['completion_percent'] ?? null;

        $investment->status               = $data['status'];

        // ---- append documents ----
        $documents = $investment->documents ?? [];
        if (is_string($documents)) {
            $documents = json_decode($documents, true) ?: [];
        }
        if (!is_array($documents)) {
            $documents = [];
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                if ($doc) {
                    $documents[] = $doc->store('investment/documents', 'public');
                }
            }
        }

        $investment->documents = $documents ?: null;

        $investment->save();

        return redirect()
            ->route('admin.seller.investment.show', $investment)
            ->with('success', 'Investment listing updated successfully.');
    }

    public function editInvestment(SellerInvestmentListing $investment)
    {
        $seller = $investment->user;

        return view('admin.seller.investment.edit', compact('investment', 'seller'));
    }
}
