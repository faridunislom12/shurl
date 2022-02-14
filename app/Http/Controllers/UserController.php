<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use \GuzzleHttp\Exception;
use App\Http\Requests\UserRequest;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::with('department', 'roles')->get(),
            'all_columns' => $this->get_all_columns(new UserRequest()),
            'columns' => Auth::user()->component_columns['user'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Users/Create', [
            'departments' => Department::all(),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $component_columns = [];
            $component_columns['application'] = (new Controller())->get_all_columns(new ApplicationRequest());
            $component_columns['category'] = (new Controller())->get_all_columns(new CategoryRequest());
            $component_columns['product'] = (new Controller())->get_all_columns(new ProductRequest());
            $component_columns['supplier'] = (new Controller())->get_all_columns(new SupplierRequest());
            $component_columns['user'] = (new Controller())->get_all_columns(new UserRequest());
            $validated['component_columns'] = $component_columns;

            $user = User::create($validated);
            $user->roles()->attach($request->role_id);

            return response()->json([
                'message' => 'Запись сохранена!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Inertia\Response
     */
    public function show(User $user)
    {
        return Inertia::render('Users/Profile', [
            'user' => $user->load('department')
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Inertia\Response
     */
    public function edit(User $user)
    {
        if (isset($user->roles[0])) {
            $user['role_id'] = $user->roles[0]->id;
        }
        return Inertia::render('Users/Edit', [
            'user' => $user,
            'departments' => Department::all(),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();
            $key = array_key_first($validated['component_columns']);
            $value = $validated['component_columns'][$key];
            $validated['component_columns'] = $user->component_columns;
            $validated['component_columns'][$key] = $value;
            $user->update($validated);
            if ($request->role_id) {
                $user->roles()->sync([$request->role_id]);
            }
            return response()->json([
                'message' => 'Запись изменена!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'message' => 'Запись удалена!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
