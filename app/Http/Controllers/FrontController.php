<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Category;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class FrontController extends Controller
{
//    public function __construct(){
//        $settings = Settings::first();
//        $categories = Category::query()->where(['status' => 1])->get();
//        View::share(['settings' => $settings, 'categories' => $categories]);
//    }
    public function home(){
//        $settings = Settings::first();
//        $categories = Category::query()->where(['status' => 1])->get();

        $mostPopularCategories = Article::query()
            ->select('id','category_id')
            ->with('category:id,name,slug,description,created_at,image,color')
            ->whereHas('category' , function ($query) {
                $query->where('status', 1)
                    ->where('feature_status', 1);
                ;
            })
            ->orderBy('view_count', 'desc')
            ->groupBy('category_id')
            ->get();

        $categoryNames = [];
        $mostPopularCategories->map(function ($item) use (&$categoryNames) {
            if (count($categoryNames) < 4)
            $categoryNames[] = $item->category;
        });

        $mostPopularArticles = Article::query()
            ->with('user', 'category')
            ->status(1)
            ->whereHas('user')
            ->whereHas('category')
            ->orderBy('view_count', 'desc')
            ->limit(6)
            ->get()
        ;

        $lastPublishedArticles = Article::query()
            ->with('user', 'category')
            ->whereHas('user')
            ->whereHas('category')
            ->orderBy('publish_date', 'desc')
            ->limit(6)
            ->get()
        ;

        return view('front.index', [
            'mostPopularCategories' => $categoryNames ,
            'mostPopularArticles' => $mostPopularArticles,
            'lastPublishedArticles' =>  $lastPublishedArticles
        ]);
    }

    public function category(Request $request, string $slug)
    {
        $articles = Article::query()
            ->with(['category:id,name,slug', "user:id,name,username"])
            ->whereHas("category", function($query) use ($slug){
                $query->where("slug", $slug);
            })->paginate(21);

        $category = Category::query()->where('slug', $slug)->first(); // Tek kategori

        $title = $category->name . " Kategorisine Ait Makaleler";

        return view("front.article-list", compact("articles", 'title', 'category'));

    }

    public function articleDetail(Request $request, string $username, Article $article)
    {
        $settings = Settings::first();
        $categories = Category::query()->where('status', 1)->get();

        $article->load([
            'user.articleLike',
            'user.commentLike',
            'comments' => function ($query) {
                $query->where('status', 1)->whereNull('parent_id');
            },
            'comments.commentLikes',
            'comments.user',
            'comments.children' => function ($query) {
                $query->where('status', 1);
            },
            'comments.children.user',
            'comments.children.commentLikes',
        ]);

        $userLike = $article->user->articleLike
            ->where('article_id', $article->id)
            ->where('user_id', Auth::id())
            ->first();

        $article->increment('view_count');
        $article->save();

        return view("front.article-detail", compact("article", "categories", "settings", 'userLike'));
    }

//    public function articleDetail(Request $request, string $username, string $articleSlug)
//    {
//        $article = session()->get("last_article");
//        $visitedArticles = session()->get("visited_articles");
//        $visitedArticlesCategoryIds = [];
//        $visitedArticleAuthorIds = [];
//        $visitedInfo = Article::query()
//            ->select("user_id", 'category_id')
//            ->whereIn("id", $visitedArticles)
//            ->get();
//        foreach ($visitedInfo as $item)
//        {
//            $visitedArticlesCategoryIds[] = $item->category_id;
//            $visitedArticleAuthorIds[] = $item->user_id;
//        }
//
//        $suggestArticles = Article::query()
//            ->with(['user', 'category'])
//            ->where(function($query) use ($visitedArticlesCategoryIds, $visitedArticleAuthorIds){
//                $query->whereIn("category_id", $visitedArticlesCategoryIds)
//                    ->orWhereIn('user_id', $visitedArticleAuthorIds);
//            })
//            ->whereNotIn("id", $visitedArticles)
//            ->limit(6)
//            ->get();
//
//        $userLike = $article
//            ->articleLikes
//            ->where("article_id", $article->id)
//            ->where("user_id", \auth()->id())
//            ->first();
//
//        $article->increment("view_count");
//        $article->save();
//
//        return view("front.article-detail",
//            compact("article",  "userLike", 'suggestArticles'));
//    }

    public function articleComment(Request $request, Article $article){
        $data = $request->except("_token");
        if (Auth::check()){
            $data['user_id'] = Auth::id();
        }
        $data['article_id'] = $article->id;
        $data['ip'] = $request->ip();

        ArticleComment::create($data);

        alert()->success('Başarılı', "Yorum Yapıldı. Onay verildikten sonra yayımlancaktır.")->showConfirmButton('Tamam', '#3085d6')->autoClose(5000);

        return redirect()->back();
    }

    public function authorArticles(Request $request, string $username){
        $articles = Article::query()
            ->with(['category:id,name,slug', "user:id,name,username"])
            ->whereHas("user", function($query) use ($username){
                $query->where("username", $username);
            })->paginate(21);

        $title = User::query()->where('username', $username)->firstOrFail()->name . 'Makaleleri'; ;

        return view("front.article-list", compact("articles",'title'));
    }

    public function search(Request $request)
    {
        $searchText = $request->q;

        $articles = Article::query()
            ->with([
                'user',
                'category'
            ])
            ->whereHas("user", function($query) use ($searchText){
                $query->where('name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere("username", "LIKE", "%" . $searchText . "%")
                    ->orWhere("about", "LIKE", "%" . $searchText . "%");

            })
            ->whereHas("category", function($query) use ($searchText){
                $query->orWhere('name', 'LIKE', '%' . $searchText . '%')
                    ->orWhere("description", "LIKE", "%" . $searchText . "%")
                    ->orWhere("slug", "LIKE", "%" . $searchText . "%");
            })
            ->orWhere("title", "LIKE", "%" . $searchText . "%")
            ->orWhere("slug", "LIKE", "%" . $searchText . "%")
            ->orWhere("body", "LIKE", "%" . $searchText . "%")
            ->orWhere("tags", "LIKE", "%" . $searchText . "%")
            ->paginate(30);

        $title = $searchText . " Arama Sonucu";
        return view("front.article-list", compact(  "articles", 'title'));
    }

    public function articleList(){
        $articles = Article::orderBy("publish_date", "desc")->paginate(6);

        return view("front.article-list", compact(  "articles"));
    }
}
