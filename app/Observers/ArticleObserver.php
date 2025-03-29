<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\Log;
use App\Traits\Loggable;

class ArticleObserver
{
    use Loggable;

    public function __construct()
    {
        $this->model = Article::class;
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        $this->log('create', $article->id, $article->toArray(), $this->model);
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        if (!$article->wasChanged("view_count"))
            $this->updateLog($article, $this->model);
    }

//    public function updateLog(Article $article): void
//    {
//        $change = $article->getDirty();
//        $data = [];
//
//        foreach ($change as $key => $value)
//        {
//            $data[$key]['old'] = $article->getOriginal($key);
//            $data[$key]['new'] = $value;
//        }
//
//        if (isset($data['updated_at']))
//        {
//            $data['updated_at']['old'] = $data['updated_at']['old']->toDateTimeString();
//        }
//
//        if (!$article->wasChanged('deleted_at'))
//        {
//            $this->log('update', $article->id, $data);
//        }
//    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        $this->log('delete', $article->id, $article->toArray(), $this->model);
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        $this->log('restore', $article->id, $article->toArray(), $this->model);
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        $this->log('force delete', $article->id, $article->toArray(), $this->model);
    }
//
//    public function log(string $action, int $loggableID, $data):  void
//    {
//        Log::create([
//            'user_id' => auth()->id(),
//            'action' => $action,
////            'data' => $user->toJson();
//            'data' => json_encode($data),
//            'loggable_id' => $loggableID,
////            'loggable_type' => get_class($user),
//            'loggable_type' => Article::class,
//        ]);
//    }
}
