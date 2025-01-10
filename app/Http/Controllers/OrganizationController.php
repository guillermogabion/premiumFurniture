<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Details;
use App\Models\UserOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    //

    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Details::find(auth()->user()->id);

        // Check if user has 'org_admin' role and valid organization_id
        if ($user && $user->role !== 'org_admin' && $user->organization_id) {
            // If the user is unauthorized, abort with a 403 status code
            abort(403, 'Unauthorized access.');
        }

        // Proceed with the existing logic if the user is authorized
        $search = $request->input('search');
        $table_header = [
            'Organization ID',
            'Organization Name',
            'Address',
            'Contact',
            'Status',
            // 'Action'
        ];

        // Filter organizations based on search input
        $items = Organization::when($search, function ($query, $search) {
            return $query->where('orgName', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('owner', 'like', '%' . $search . '%')
                ->orWhere('website', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Return the view with headers, items, and search
        return view('pages.organizations', [
            'headers' => $table_header,
            'items' => $items,
            'search' => $search
        ]);
    }


    private function generateOrgId()
    {
        $letters = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4));
        $numbers = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        return $letters . $numbers;
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'orgName' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'required|string',
            'orgImage' => 'nullable|file|max:10240|mimes:jpg,jpeg,png' // Image can be optional
        ]);

        // Generate organization ID
        $orgId = $this->generateOrgId();

        // Initialize the organization data array
        $organizationData = [
            'orgId' => $orgId,
            'orgName' => $request->input('orgName'),
            'address' => $request->input('address'),
            'contact' => $request->input('contact'),
        ];



        if ($request->hasFile('orgImage')) {
            $file = $request->file('orgImage');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('orgImage'), $fileName);
            $organizationData['orgImage'] = $fileName; // Add the image file name to the data array
        }


        // Create the organization
        $organization = Organization::create($organizationData);

        $userorganization = new UserOrganization();

        $userorganization->user_id = auth()->user()->id;
        $userorganization->organization_id = $organization->id;
        $userorganization->save();

        return response()->json(['success' => true, 'message' => 'Organization created successfully'], 200);
    }




    public function update(Request $request)
    {
        $request->validate([
            'orgName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:organizations,email',
            'address' => 'required|string',
            'owner' => 'required|string',
            'website' => 'required|string',
        ]);

        // Find the user by ID
        $item = Organization::findOrFail($request->id);

        // Update the user's fields
        $item->fill($request->all());

        // Save the updated user
        $item->save();

        return redirect()->route('organizations')->with('success', 'Organization updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:active,inactive',
        ]);

        $user = Organization::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('organizations')->with('success', 'Organization status updated successfully');
    }
}
