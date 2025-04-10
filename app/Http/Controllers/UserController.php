<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index(Request $request){
        $list = User::query()
//            ->withTrashed()
            ->status($request->status)
            ->searchText($request->search)
            ->isAdmin($request->isAdmin)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('admin.users.list', compact('list'));
    }

    public function create(){
        return view('admin.users.create-update');
    }

    public function store(UserStoreRequest $request){

        $data = $request->except('_token');
        $data['password']=bcrypt($data['password']);
        $data['status']=isset($data['status']) ? 1 : 0;
        User::create($data);

        alert()->success('Başarılı', "Kullanıcı Oluşturuldu")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->route('user.create');
    }

    public function edit(Request $request, User $user){
//        $user = User::findOrFail($request->id);
        return view('admin.users.create-update', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->except("_token");

        if (!is_null($data["password"]))
        {
            $data['password'] = bcrypt($data['password']);
        }
        else
        {
            unset($data['password']);
        }
        $data['status'] = isset($data['status']) ? 1 : 0;
        $user->update($data);

        alert()->success('Başarılı', "Kullanıcı güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->route("user.index");
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->id);
//        dd($user);
//        $user->articles()->delete();
//        $user->categories()->delete();
        $user->delete();

        return response()
            ->json(['status' => "success", "message" => "Başarılı", "data" => "" ])
            ->setStatusCode(200);
    }

    public function restore(Request $request)
    {
        $user = User::withTrashed()->findOrFail($request->id);
        $user->restore();

        return response()
            ->json(['status' => "success", "message" => "Başarılı", "data" => "" ])
            ->setStatusCode(200);
    }

    public function changeStatus(Request $request):JsonResponse {

        $user = User::query()
            ->where('id', $request->id)
            ->first();
        ;
        if ($user){
            $user->status = $user->status ? 0 : 1 ;
            $user->save();

            return response()->json(['status' => 'success', 'massage' => 'Başarılı','data' => $user, 'user_status' => $user->status])
                ->setStatusCode(200);
        }
        return response()->json(['status' => 'error', 'message'=> 'User Bulunamdı'])
            ->setStatusCode(404);
    }

    public function changeIsAdmin(Request $request):JsonResponse {

        $user = User::query()
            ->where('id', $request->id)
            ->first();
        ;
        if ($user){
            $user->is_admin = $user->is_admin ? 0 : 1 ;
            $user->save();

            return response()->json(['is_admin' => 'success', 'massage' => 'Başarılı','data' => $user, 'is_admin' => $user->is_admin])
                ->setStatusCode(200);
        }
        return response()->json(['is_admin' => 'error', 'message'=> 'User Bulunamdı'])
            ->setStatusCode(404);
    }
}
