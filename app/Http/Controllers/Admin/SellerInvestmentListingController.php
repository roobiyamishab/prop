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

            // Location
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
            'completion_percent' => 'nullable|integer|min:0|max:100',

            // Documents (multiple files)
            'documents.*'           => 'nullable|file',
        ]);

        $adminId = Auth::guard('admin')->id();
        $user    = User::find($data['user_id']);

        // ---- FILE UPLOADS (DOCUMENTS JSON) ----
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                $documents[] = $doc->store('investment/documents', 'public');
            }
        }

        SellerInvestmentListing::create([
            'user_id'             => $data['user_id'],
            'created_by_admin_id' => $adminId,

            'property_code'       => $this->generatePropertyCode('INV'),
            'status'              => 'normal',

            'project_name'        => $data['project_name'],
            'project_type'        => $data['project_type'] ?? null,

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

}
