<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

//use App\Http\Requests\RoleRequest;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Roles/Index', [
            'roles' => Role::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Roles/Create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {

        try {
            Role::create($request->validated());
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
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {

        return \Inertia\Inertia::render('Roles/Profile', [
            'role' => $role
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {

        return Inertia::render('Roles/Edit', [
            'role' => $role,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {

        try {
            $role->update($request->validated());
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
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {

        try {
            $role->delete();
            return response()->json([
                'message' => 'Запись удалена!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }


    /**
     * Display a listing of the user permissions.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function access($roleId)
    {
        $sqlText = 'SELECT components.component, c.p_id as c_id, c.create, r.p_id as r_id, r.read, u.p_id as u_id, u.update, d.p_id as d_id, d.delete FROM
(SELECT count(*), SUBSTRING_INDEX(`name`, \'-\', 1) as component FROM `permissions`
GROUP BY SUBSTRING_INDEX(`name`, \'-\', 1)) as components
LEFT JOIN
(SELECT p.`id` as p_id, SUBSTRING_INDEX(`name`, \'-\', 1) as component, p.`name`, IF(p.`name` LIKE \'%-create%\' && pr.`permission_id` IS NOT NULL,1,0) as \'create\'
FROM (SELECT * FROM `permissions` WHERE `name` LIKE \'%-create%\') as p
LEFT JOIN `permission_role` as pr ON p.`id`=pr.`permission_id` AND pr.`role_id`=' . $roleId . ') as c
ON components.component=c.component
LEFT JOIN
(SELECT p.`id` as p_id, SUBSTRING_INDEX(`name`, \'-\', 1) as component, p.`name`, IF(p.`name` LIKE \'%-read%\' && pr.`permission_id` IS NOT NULL,1,0) as \'read\'
FROM (SELECT * FROM `permissions` WHERE `name` LIKE \'%-read%\') as p
LEFT JOIN `permission_role` as pr ON p.`id`=pr.`permission_id` AND pr.`role_id`=' . $roleId . ') as r
ON components.component=r.component
LEFT JOIN
(SELECT p.`id` as p_id, SUBSTRING_INDEX(`name`, \'-\', 1) as component, p.`name`, IF(p.`name` LIKE \'%-update%\' && pr.`permission_id` IS NOT NULL,1,0) as \'update\'
FROM (SELECT * FROM `permissions` WHERE `name` LIKE \'%-update%\') as p
LEFT JOIN `permission_role` as pr ON p.`id`=pr.`permission_id` AND pr.`role_id`=' . $roleId . ') as u
ON components.component=u.component
LEFT JOIN
(SELECT p.`id` as p_id, SUBSTRING_INDEX(`name`, \'-\', 1) as component, p.`name`, IF(p.`name` LIKE \'%-delete%\' && pr.`permission_id` IS NOT NULL,1,0) as \'delete\'
FROM (SELECT * FROM `permissions` WHERE `name` LIKE \'%-delete%\') as p
LEFT JOIN `permission_role` as pr ON p.`id`=pr.`permission_id` AND pr.`role_id`=' . $roleId . ') as d
ON components.component=d.component';

        return Inertia::render('Roles/Access', [
            'permissions' => DB::select($sqlText),
            'users' => User::all(),
            'role' => Role::with('users')->find($roleId),
        ]);
    }

    /**
     * Manage user permissions.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePermissions(Request $request, Role $role)
    {

        try {
//            $role->detachPermissions($role->permissions());
            DB::select('DELETE FROM `permission_role` WHERE `role_id`=' . $role->id);
            foreach ($request->all() as $component) {
                if ($component['create'] == '1') {
                    $role->attachPermission($component['c_id']);
                }
                if ($component['read'] == '1') {
                    $role->attachPermission($component['r_id']);
                }
                if ($component['update'] == '1') {
                    $role->attachPermission($component['u_id']);
                }
                if ($component['delete'] == '1') {
                    $role->attachPermission($component['d_id']);
                }
            }
            return response()->json([
                'message' => 'Разрешения изменены!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

    }


    /**
     * Manage user permissions.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeUsers(Request $request, Role $role)
    {
        try {

            DB::select('DELETE FROM `role_user` WHERE `role_id`=' . $role->id);
            foreach ($request->all() as $user) {
                User::find($user['id'])->attachRole($role->id);
            }

            return response()->json([
                'message' => 'Пользователи изменены!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

}
