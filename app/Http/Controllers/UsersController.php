<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class UsersController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Full Name',
            'Address',
            'Contact',
            'Email',
            'Role',
            'Action'
        ];
        $items = User::when($search, function ($query, $search) {
            return $query->where('fullname', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('role', 'like', '%' . $search . '%')
                ->orWhere('contact', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })
            // ->where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $user = User::find(auth()->user()->id);


        return view('pages.users', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function vendor(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Full Name',
            'Address',
            'Contact',
            'Email',
            'Store Name',
            'Sell',
            'Status',
        ];
        $items = User::when($search, function ($query, $search) {
            return $query->where('fullname', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('role', 'like', '%' . $search . '%')
                ->orWhere('contact', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->with('document', 'gcash')
            ->where('role', 'vendor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $user = User::find(auth()->user()->id);


        return view('pages.vendor', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search]);
    }
    public function client(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Full Name',
            'Address',
            'Contact',
            'Email',
            'Status',
            'Action'
        ];
        $items = User::when($search, function ($query, $search) {
            return $query->where('fullname', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('role', 'like', '%' . $search . '%')
                ->orWhere('contact', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })
            ->where('role', 'client')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $user = User::find(auth()->user()->id);


        return view('pages.client', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function getEntities()
    {
        $entity = User::where('role', 'NOT LIKE', 'student')->get();

        return response()->json(['entity' => $entity]);
    }

    public function storeId(Request $request)
    {
        $request->validate([
            'userId' => 'required|string|max:255',
        ]);
        $user = new User();
        $user->userId = $request->input('userId');
        $user->save();

        return redirect()->route('users');
    }

    public function registers(Request $request)
    {


        // Create a new user instance
        $user = new User();
        $user->fullname = $request->input('fullname');
        $user->email = $request->input('email');
        $user->contact = $request->input('contact');
        $user->gender = $request->input('gender');
        $user->address = $request->input('address');
        $user->role = $request->input('role');
        $user->shop_name = $request->input('shop_name', null); // Default to null if not provided
        $user->type = $request->input('type', null); // Default to null if not provided
        $user->status = $request->input('role') === 'vendor' ? 'disabled' : 'active';
        $user->password = Hash::make($request->input('password'));

        // Handle profile picture upload
        if ($request->hasFile('profilePicture')) {
            $imageName = time() . '.' . $request->profilePicture->extension();
            $request->profilePicture->move(public_path('profile'), $imageName);
            $user->profile = $imageName;
        }

        // Save the user to the database
        $user->save();

        // Redirect to login with success message
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }


    public function update(Request $request)
    {

        // Find the user by ID
        $user = User::findOrFail(auth()->user()->id);

        // Update user properties
        $user->fullname = $request->input('fullname', $user->fullname);
        $user->email = $request->input('email', $user->email);
        $user->contact = $request->input('contact', $user->contact);
        $user->gender = $request->input('gender', $user->gender);
        $user->address = $request->input('address', $user->address);
        $user->role = $request->input('role', $user->role);
        $user->shop_name = $request->input('shop_name', $user->shop_name);
        $user->type = $request->input('type', $user->type);

        // Handle profile picture upload
        if ($request->hasFile('profilePicture')) {
            $imageName = time() . '.' . $request->profilePicture->extension();
            $request->profilePicture->move(public_path('profile'), $imageName);
            $user->profile = $imageName;
        }

        // Update the user in the database
        $user->save();

        // Return a success response
        return response()->json(['message' => 'User updated successfully.']);
    }
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:active,disabled,rejected',
        ]);

        $user = User::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('vendor')->with('success', 'Vendor status updated successfully');
    }


    public function self()
    {
        $user = User::with('details')->find(auth()->user()->id);
        $token = $user->createToken('authToken')->accessToken;
        return response(['user' => $user, 'access_token' => $token]);
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'userId' => 'required',
            'password' => 'required',
        ]);

        // Attempt authentication
        if (!Auth::attempt(['userId' => $request->userId, 'password' => $request->password])) {
            return response(['message' => 'Login credentials are incorrect'], 401);
        }

        // Find the authenticated user and eager load the 'details' relationship
        $user = User::where('userId', $request->userId)->first();

        // Generate the access token
        $token = $user->createToken('authToken')->accessToken;

        // Return the response with user details and token
        return response(['user' => $user, 'access_token' => $token], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->tokem()->delete();
    }
    public function indexMobile()
    {
        return User::get();
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Ensure $user is an instance of User
        if (!($user instanceof \App\Models\User)) {
            return response()->json(['error' => 'User instance is incorrect'], 500);
        }

        $user->password = Hash::make($request->input('password'));
        $user->status = 'old';
        $user->save(); // Save method should be available



        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function validateToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return Response::json(['error' => 'Token not provided'], 401);
        }

        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        $user = User::where('api_token', $token)->first();

        if ($user) {
            return Response::json(['valid' => true], 200);
        } else {
            return Response::json(['valid' => false], 401);
        }
    }


    public function storeApi(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'userId' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|string',
        ]);

        $user = new User();
        $user->userId = $request->input('userId');
        $user->name = $request->input('name');
        $user->role = $request->input('role');
        $user->password = Hash::make($request->input('password'));

        $user->save();

        return response()->json(['message' => 'Account created successfully.']);
    }

    public function createaccount(Request $request)
    {
        // Validate incoming request



        // dd($request);
        // Handle the profile file upload
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('profile'), $fileName);

            // Create the user
            $user = new User();
            $user->name = $request->input('name');
            $user->role = $request->input('role');
            $user->userId = $request->input('userId');
            $user->password = Hash::make($request->input('password'));
            $user->profile = $fileName;
            $user->save();

            // Generate an access token for the user
            $token = $user->createToken('authToken')->accessToken;

            // Return a JSON response with the user and token
            return response()->json([
                'user' => $user,
                'access_token' => $token
            ], 201);
        } else {
            return response()->json(['error' => 'Profile file is required.'], 400);
        }
    }

    public function sellerShow($id, Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Full Name',
            'Address',
            'Contact',
            'Email',
            'Role',
            'Action'
        ];
        $items = User::with([
            'sellers' => function ($query) {
                $query->select(
                    'products.id',
                    'products.name',
                    'products.description',
                    'products.category',
                    'products.image',
                    'products.price',
                    'products.user_id',
                    'products.created_at',
                    'products.status',
                    \DB::raw('(SELECT COUNT(*) FROM ratings WHERE ratings.product_id = products.id) AS ratings_count'),
                    \DB::raw('IFNULL(AVG(ratings.rating), 0) AS average_rating')
                )->leftJoin('ratings', 'ratings.product_id', '=', 'products.id')
                    ->groupBy(
                        'products.id',
                        'products.name',
                        'products.description',
                        'products.category',
                        'products.image',
                        'products.price',
                        'products.user_id',
                        'products.created_at',
                        'products.status'
                    );
            }
        ])
            ->where('id', $id)
            ->orderBy('created_at', 'desc')
            ->get();


        $user = User::find(auth()->user()->id);


        return view('pages.sellerproduct', ['test' => $user, 'headers' => $table_header, 'items' => $items, 'search' => $search]);
    }

    public function resetMyPassword(Request $request)
    {

        $user = User::where('email', $request->input('email'))->where('contact', $request->input('contact'))->first();

        if ($user) {
            // Update the password
            $user->password = Hash::make('password');
            $user->isReset = 1;
            $user->save();

            // return redirect()->route('login');

            return view('auth.reset', [
                'message' => 'Success! Please use "password" to log in as Temporary Password',
                'status' => 'success'
            ]);
        } else {
            // Return an error response if user does not exist
            return view('auth.reset', [
                'message' => 'Failed! Details provided are not in the database',
                'status' => 'error'
            ]);
        }
    }

    public function changePassword(Request $request)
    {

        $user = User::find(auth()->user()->id);

        $user->password =  Hash::make($request->input('password'));
        $user->isReset =  0;
        $user->save();
        return response()->json(['message' => 'Password Update Success'], 200);
    }

    public function resetPassword(Request $request)
    {

        return view('auth.reset');
    }
}
