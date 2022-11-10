<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateUserApi;
use App\Http\Resources\UserApiResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{

    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->model->paginate();

        return UserApiResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateUserApi $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);

        $user = $this->model->create($data);

        return new UserApiResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $identify
     * @return \Illuminate\Http\Response
     */
    public function show($identify)
    {
        $user = $this->model->where('uuid', $identify)->firstOrFail();

        return new UserApiResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $identify
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateUserApi $request, $identify)
    {
        $user = $this->model->where('uuid', $identify)->firstOrFail();

        $data = $request->validated();

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return new UserApiResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $identify
     * @return \Illuminate\Http\Response
     */
    public function destroy($identify)
    {
        $user = $this->model->where('uuid', $identify)->firstOrFail();

        $user->delete();

        return response()->json([], 205);
    }
}
