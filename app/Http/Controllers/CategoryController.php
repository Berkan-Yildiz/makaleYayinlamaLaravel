<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request){
        $parentCategories = Category::all();
        $users = User::all();

        $categories = Category::with('parentCategory:id,name')
            ->name($request->name)
            ->description($request->description)
            ->slug($request->slug)
            ->order($request->order)
            ->status($request->status)
//            ->user($request->user_id)
            ->parentCategory($request->parent_id)
//            ->featureStatus($request->feature_status)
            ->orderBy('id', 'desc')
            ->paginate(5);
        return view('admin.categories.list',[
            'list' => $categories
            ,'parentCategories' => $parentCategories
            ,'users' => $users
        ]);
    }

    public function create(){
        $categories = Category::all();
        return view('admin.categories.create-update', compact('categories'));
    }

    public function store(CategoryStoreRequest $request)
    {
        $slug = Str::slug($request->slug);

        try
        {
            $category                  = new Category();
            $category->name            = $request->name;
            $category->color            = $request->color;
            $category->slug            = is_null($this->slugCheck($slug)) ? $slug : Str::slug($slug . time());
            $category->description     = $request->description;
            $category->status          = $request->status ? 1 : 0;
            $category->parent_id       = $request->parent_id;
            $category->feature_status  = $request->feature_status ? 1 : 0;
            $category->seo_keywords    = $request->seo_keywords;
            $category->seo_description = $request->seo_description;
            $category->user_id         = auth()->id();
            $category->order           = $request->order;



            if (!is_null($request->image))
            {
                $imageFile = $request->file("image");
                $originalName = $imageFile->getClientOriginalName();
                $originalExtension = $imageFile->getClientOriginalExtension();
                $explodeName = explode(".", $originalName)[0];
                $fileName = Str::slug($explodeName) . "." . $originalExtension;

                $folder = "categories";
                $publicPath = "storage/" . $folder;

                if (file_exists(public_path($publicPath . $fileName)))
                {
                    return redirect()
                        ->back()
                        ->withErrors([
                            'image' => "Aynı görsel daha önce yüklenmiştir."
                        ]);
                }

                $category->image = $publicPath . "/" . $fileName;
                $imageFile->storeAs($folder,  $fileName);
            }


            $category->save();
        }
        catch (\Exception $exception)
        {
            abort(404, $exception->getMessage());
        }

        alert()->success('Başarılı', "Kategori Kaydedildi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->back();
    }

    public function slugCheck(string $text){
         return Category::where('slug', $text)->first();
    }

    public function about(Request $request){

        return view('mainPage');
    }

    public function list()
    {
        $list = Category::with(['parentCategory'])->get();

        return view('admin.categories.list', compact('list'));
    }

    public function changeStatus(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', "exists:categories"]]);

        $categoryID = $request->id;


        $category = Category::where("id", $categoryID)->first();

        $oldStatus = $category->status;

        $category->status = !$category->status;
        $category->save();

        $statusText = ($oldStatus == 1 ? "Aktif" : "Pasif") . "'ten " . ($category->status == 1 ? "Aktif" : "Pasif");

        alert()->success('Başarılı', $category->name . " status " . $statusText . " olarak güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        //        return redirect()->route("category.index");
        return redirect()->back();
    }

    public function changeFeatureStatus(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', "exists:categories"]]);

        $categoryID = $request->id;


        $category = Category::where("id", $categoryID)->first();

        $oldStatus = $category->feature_status;

        $category->feature_status = !$category->feature_status;
        $category->save();

        $statusText = ($oldStatus == 1 ? "Aktif" : "Pasif") . "'ten " . ($category->feature_status == 1 ? "Aktif" : "Pasif");

        alert()->success('Başarılı', $category->name . " feature status değeri " . $statusText . " olarak güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->route("categories.index");

    }

    public function delete(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', "exists:categories"]]);

        $categoryID = $request->id;

        Category::where("id", $categoryID)->delete();

        $statusText = "Kategori Silindi";

        alert()->success('Başarılı', $statusText)->showConfirmButton('Tamam', '#3085d6');

        return redirect()->route("categories.index");
    }

    public function edit(Request $request)
    {
        $categories = Category::all();

        $categoryID = $request->id;

        $category = Category::where("id", $categoryID)->first();

        if (is_null($category))
        {
            $statusText = "Kategori bulunamadı";

            alert()->error('Hata', $statusText)->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->route('category.index');
        }

        return view("admin.categories.create-update", compact("category", 'categories'));

    }

    public function update(Request $request){
        $slug      = Str::slug($request->slug);
        $slugCheck = $this->slugCheck($slug);

        $category       = Category::find($request->id);
        $category->name = $request->name;
        if ((!is_null($slugCheck) && $slugCheck->id == $category->id) || is_null($slugCheck))
        {
            $category->slug = $slug;
        }
        else if (!is_null($slugCheck) && $slugCheck->id != $category->id)
        {
            $category->slug = Str::slug($slug . time());
        }
        else
        {
            $category->slug = Str::slug($slug . time());
        }

        $category->color           = $request->color;
        $category->description     = $request->description;
        $category->status          = $request->status ? 1 : 0;
        $category->parent_id       = $request->parent_id;
        $category->feature_status  = $request->feature_status ? 1 : 0;
        $category->seo_keywords    = $request->seo_keywords;
        $category->seo_description = $request->seo_description;
        //        $category->user_id         = random_int(1, 10);
        $category->order = $request->order;
//        Cache::forget("most_popular_categories");
        if (!is_null($request->image))
        {
            $imageFile = $request->file("image");
            $originalName = $imageFile->getClientOriginalName();
            $originalExtension = $imageFile->getClientOriginalExtension();
            $explodeName = explode(".", $originalName)[0];
            $fileName = Str::slug($explodeName) . "." . $originalExtension;

            $folder = "categories";
            $publicPath = "storage/" . $folder;

            if (file_exists(public_path($publicPath . $fileName)))
            {
                return redirect()
                    ->back()
                    ->withErrors([
                        'image' => "Aynı görsel daha önce yüklenmiştir."
                    ]);
            }

            if (file_exists(public_path($category->image)))
            {
                \File::delete(public_path($category->image));
            }
            $category->image = $publicPath . "/" . $fileName;
            $imageFile->storeAs($folder, $fileName);

        }


        $category->save();

        alert()->success('Başarılı', "Kategori güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route("categories.index");
    }

}
