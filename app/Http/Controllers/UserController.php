<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Users\UsersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @param UsersRepository $repository
     * @return View
     */
    public function index(UsersRepository $repository): View
    {
        $users = $repository->search((string)request('q'));

        return view('users.index', ['users' => $users]);
    }

    /**
     * Return users from index.
     *
     * @param Request $request
     * @param UsersRepository $repository
     * @return JsonResponse
     */
    public function getUsers(Request $request, UsersRepository $repository): JsonResponse
    {
        $count = $request->limit;
        $page = $request->offset / $count;
        $totalCount = $repository->count();

        $users = $repository->search((string)request('q'), $page, $count);

        return response()->json([
            'total' => $totalCount,
            'rows' => $users
        ], 200);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @param UsersRepository $repository
     * @return JsonResponse
     */
    public function store(Request $request, UsersRepository $repository): JsonResponse
    {
        $user = new User();

        $request->validate([
            'name' => 'required',
            'birthday' => [
                'required',
                'regex:/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/'
            ],
            'phone_number' => [
                'required',
                'regex:/^(\(0[5-9][0-9]\)[ ]\d{3}-\d{4})$/'
            ],
            'email' => [
                'required',
                function ($attribute, $value, $fail) use ($repository, $user) {
                    if (!$repository->uniqueField($user, $attribute, $value)) {
                        $fail(ucfirst($attribute) . ' already exists.');
                    }
                },
            ],
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);

        $maxId = $repository->getOneWithMaxID()['id'];

        $input = array_merge(
            $request->except(['password', 'password_confirm']),
            [
                'password' => Hash::make($request->password),
                'id' => ++$maxId
            ]
        );

        foreach ($input as $key => $value) {
            $user->$key = $value;
        }

        $repository->create($user);

        return response()->json(['user_id' => $user->id], 200);
    }

    /**
     * Display the specified user.
     *
     * @param UsersRepository $repository
     * @param int $id
     * @return View
     */
    public function show(UsersRepository $repository, int $id): View
    {
        $user = $repository->searchByID($id);

        return view('users.show', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request
     * @param UsersRepository $repository
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, UsersRepository $repository, int $id): JsonResponse
    {
        $user = new User();

        $request->validate([
            'name' => 'required',
            'birthday' => [
                'required',
                'regex:/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/'
            ],
            'phone_number' => [
                'required',
                'regex:/^(\(0[5-9][0-9]\)[ ]\d{3}-\d{4})$/'
            ],
            'email' => [
                'required',
                function ($attribute, $value, $fail) use ($repository, $user, $id) {
                    if (!$repository->uniqueField($user, $attribute, $value, $id)) {
                        $fail(ucfirst($attribute) . ' already exists.');
                    }
                },
            ],
        ]);

        $repository->update($id, $request->all());

        return response()->json(['user_id' => $id], 200);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param UsersRepository $repository
     * @param int $id
     * @return View
     */
    public function edit(UsersRepository $repository, int $id): View
    {
        $user = $repository->searchByID($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param UsersRepository $repository
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(UsersRepository $repository, int $id): JsonResponse
    {
        $repository->delete($id);

        return response()->json([], 200);
    }
}
