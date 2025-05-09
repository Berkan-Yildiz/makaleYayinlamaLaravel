<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCreateRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\UserLikeArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $categories = Category::all();
        $list = Article::query()
            ->with(['user', 'category'])
            ->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->search . '%')
                    ->orWhere('slug', 'like', '%' . $request->search . '%')
                    ->orWhere('body', 'like', '%' . $request->search . '%')
                    ->orWhere('tags', 'like', '%' . $request->search . '%')
                ;
            })
            ->status($request->status)
            ->category($request->category_id)
            ->user($request->user_id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view("admin.articles.list", compact("users", "categories", "list"));
    }

    public function create()
    {
        $categories = Category::all();

        return view("admin.articles.create-update", compact("categories"));
    }

    public function store(ArticleCreateRequest $request)
    {
        if(!is_null($request->image))
        {
            $imageFile = $request->file("image");
            $originalName = $imageFile->getClientOriginalName();
            $originalExtension = $imageFile->getClientOriginalExtension();
            $explodeName = explode(".", $originalName)[0];
            $fileName = Str::slug($explodeName) . "." . $originalExtension;

            $folder = "articles";
            $publicPath = "storage/" . $folder;

            if (file_exists(public_path($publicPath . $fileName)))
            {
                return redirect()
                    ->back()
                    ->withErrors([
                        'image' => "Aynı görsel daha önce yüklenmiştir."
                    ]);
            }
        }

        $data = $request->except("_token");
        $slug = $data['slug'] ?? $data["title"];
        $slug = Str::slug($slug);
        $slugTitle = Str::slug($data["title"]);

        $checkSlug = $this->slugCheck($slug);

        if (!is_null($checkSlug))
        {
            $checkTitleSlug = $this->slugCheck($slugTitle);
            if (!is_null($checkTitleSlug))
            {
                // Title Slug dolu geldiyse
                $slug = Str::slug($slug . time());
            }
            else
            {
                $slug = $slugTitle;
            }
        }

        $data["slug"] = $slug;
        if (!is_null($request->image))
        {
            $data["image"] = $publicPath . "/" . $fileName;
        }
        $data["user_id"] = auth()->id();

        $status = 0;
        if (isset($data['status']))
        {
            $status = 1;
        }
        $data['status'] = $status;


        Article::create($data);
        if (!is_null($request->image))
        {
            $imageFile->storeAs($folder,  $fileName);
        }

        alert()->success('Başarılı', "Makale Kaydedildi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->back();
    }

    public function slugCheck(string $text){
        return Category::where('slug', $text)->first();
    }

    public function changeStatus(Request $request):JsonResponse {
        $articleID = $request->articleID;

        $article = Article::query($articleID)
            ->where('id', $articleID)
            ->first();
        ;
        if ($article){
            $article->status = $article->status ? 0 : 1 ;
            $article->save();

            return response()->json(['status' => 'success', 'massage' => 'Başarılı','data' => $article, 'article_status' => $article->status])
                ->setStatusCode(200);
        }
        return response()->json(['status' => 'error', 'message'=> 'Başarısız','data' => $article, 'article_status' => $article->status])
            ->setStatusCode(404);
    }

    public function delete(Request $request)
    {
        $articleID = $request->articleID;

        $article = Article::query()
            ->where("id", $articleID)
            ->first();
        if ($article)
        {
            $article->delete();
            return response()
                ->json(['status' => "success", "message" => "Başarılı", "data" => "" ])
                ->setStatusCode(200);
        }
        return response()
            ->json(['status' => "error", "message" => "Makale bulunamadı" ])
            ->setStatusCode(404);
    }

    public function edit(Request $request, int $articleID)
    {
//        $article = Article::find($articleID);
//        $article = Article::where("id", $articleID)->firstOrFail();
        $article = Article::query()
            ->where("id", $articleID)
            ->first();
        $categories = Category::all();
        $users = User::all();
        if (is_null($article))
        {
            $statusText = "Makale bulunamadı";

            alert()->error('Hata', $statusText)->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
            return redirect()->route('article.index');
        }

        return view("admin.articles.create-update", compact("article", "categories", "users"));
    }

    public function update(ArticleUpdateRequest $request)
    {
        $articleQuery = Article::query()
            ->where("id", $request->id);

        $articleFind = $articleQuery->first();

        $data = $request->except("_token");

        //title değişti ise datadan gelen title'ı al. Oda boş ise datadan gelen slug ı al. Slugda boş ise title'ı al
        $slug = $articleFind->title != $data['title'] ? $data['title'] : ($data['slug'] ?? $data["title"]);
        $slug = Str::slug($slug);
        $slugTitle = Str::slug($data["title"]);


        if ($articleFind->slug != $slug)
        {
            $checkSlug = $this->slugCheck($slug);

            if (!is_null($checkSlug))
            {
                $checkTitleSlug = $this->slugCheck($slugTitle);
                if (!is_null($checkTitleSlug))
                {
                    $slug = Str::slug($slug . time());
                }
                else
                {
                    $slug = $slugTitle;
                }
            }

            $data["slug"] = $slug;
        }
//        else if (empty($data['slug']) && !is_null($articleFind->slug))
//        {
//            unset($data['slug']);
//        }
        else
        {
            unset($data['slug']);
        }

        if ($articleFind->title != $data['title'] || (isset($data['slug']) && $articleFind->slug != $data['slug'] ))
        {
//            if (Cache::has("most_popular_articles"))
//            {
//                $mpA = Cache::get("most_popular_articles");
//                $mpA->where("title", $articleFind->title)->first()->update([
//                    'title' =>  $data['title'],
//                    'slug' => $slug
//                ]);
//                Cache::put("most_popular_articles", $mpA, 3600);
//            }
            //            Cache::forget("most_popular_articles");
        }

        if (!is_null($request->image))
        {
            $imageFile = $request->file("image");
            $originalName = $imageFile->getClientOriginalName();
            $originalExtension = $imageFile->getClientOriginalExtension();
            $explodeName = explode(".", $originalName)[0];
            $fileName = Str::slug($explodeName) . "." . $originalExtension;

            $folder = "articles";
            $publicPath = "storage/" . $folder;

            if (file_exists(public_path($publicPath . $fileName)))
            {
                return redirect()
                    ->back()
                    ->withErrors([
                        'image' => "Aynı görsel daha önce yüklenmiştir."
                    ]);
            }

            $data["image"] = $publicPath . "/" . $fileName;

        }
        $data["user_id"] = auth()->id();

        $status = 0;
        if (isset($data['status']))
        {
            $status = 1;
        }
        $data['status'] = $status;


        $articleQuery->first()->update($data);


        if (!is_null($request->image))
        {
            if (file_exists(public_path($articleFind->image)))
            {
                \File::delete(public_path($articleFind->image));
            }
            $imageFile->storeAs($folder,  $fileName);
        }

        alert()->success('Başarılı', "Makale güncellendi")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);
        return redirect()->route("articles.index");
    }

    public function favorite(Request $request)
    {
        $article = Article::query()->with(['articleLikes' => function ($query) {
            $query->where("user_id", auth()->id());
        }
        ])->where('id', $request->id)->firstOrFail();

        if ($article->articleLikes->count() > 0 ){
            $article->articleLikes()->delete();
//            UserLikeArticle::query()->where("user_id", auth()->id())->where("article_id", $article->id)->delete();
            $article->like_count--;
            $process = 0;
        }else{
            UserLikeArticle::create([
                'user_id' => auth()->id(),
                'article_id' => $article->id,
            ]);
            $article->like_count++;
            $process = 1;
        }

        $article->save();

        return response()->json(['status' => 'success', 'massage' => 'Başarılı','like_count' => $article->like_count, 'process' => $process])
            ->setStatusCode(200);
    }

}
