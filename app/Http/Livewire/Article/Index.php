<?php

namespace App\Http\Livewire\Article;

use App\Models\Article;
use App\Models\Classification;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArticleExport;

class Index extends Component
{

    public $article, $article_id, $classification_id;
    public $article_name, $classification_name;
    public $searchKey;

    use WithPagination;
 
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $access = explode(',', Auth()->user()->access);
        $permissions = explode(',', Auth()->user()->permissions);

        if (!in_array('Articles', $access)) {
            session()->flash('message', "Sorry, you don't have access to Article page.");
            $this->redirect('/profile');
        }

        $classifications = Classification::all();

        $searchKey_ = $this->searchKey;

        $articles = DB::table('articles')->select('articles.*', 'classifications.*')
            ->join('classifications', 'classifications.classification_id', '=', 'articles.classification_id')
            ->where('articles.article', 'LIKE', "%$searchKey_%")
            ->orWhere('classifications.classification', 'LIKE', "%$searchKey_%")
            ->orderBy('articles.article')
            ->paginate(10);

            return view('livewire.article.index', [
                'articles' => $articles,
                'classifications' => $classifications,
                'permissions' => $permissions
            ])->layout('livewire.layouts.base');

    }

    protected $rules = [
        'article' => 'required|min:3',
        'classification_id' => 'required',
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    protected $messages = [
        'classification_id.required' => 'The Classification is required.',
    ];

    public function saveArticle() {

        $this->validate();
        $a = Article::create([
            'article' => trim($this->article),
            'classification_id' => $this->classification_id
        ]);

        session()->flash('message', $this->article . ' has been added successfully.');
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }

    public function cancel() {
        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function viewArticleDetails($id) {

        $articles_ = Article::find($id);
        $this->article_name = $articles_->article;
        $this->classification_name = $articles_->Classification->classification;

        $this->dispatchBrowserEvent('show-article-modal');
    }

    public function getArticle($id) {
        $article = Article::where('article_id', $id)->first();
        $this->article = $article->article;
        $this->article_id = $article->article_id;
        $this->classification_id = $article->classification_id;

        $this->dispatchBrowserEvent('show-edit-article-modal');
    }

    public function updateArticle() {

        $this->validate();
        $article = Article::where('article_id', $this->article_id)->first();

        $article->classification_id = $this->classification_id;
        $article->article = trim($this->article);
        $article->save();

    
        session()->flash('message', trim($this->article) . ' has been updated successfully.');

        
        $this->dispatchBrowserEvent('close-modal');
        $this->reset();
    }


    public function deleteConfirmation($id) {
        $this->article_id = $id;

        $this->dispatchBrowserEvent('show-delete-article-modal');
    }

    public function deleteArticle() {
        $article = Article::where('article_id', $this->article_id)->first();
        $this->article = $article->article;
        $article->delete();

        session()->flash('message', $this->article . ' has been deleted successfully.');

        $this->reset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function export() {
        return Excel::download(new ArticleExport, 'articles.xlsx');
    }
}
