<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends DashboardController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.article.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.article.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:20',
            'content' => 'required|min:30',
        ]);

        $request['slug'] = str_slug($request['title'], '-');

        $article = $this->article;
        
        $article->title = $request->title;
        $article->content = $request->content;
        $article->slug = $request->slug;

        $article->save();

        $article->category()->sync($request['article_category']);

        $article->tag()->sync($request['article_tag']);

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = $this->article->findOrFail($id);

        return view('dashboard.article.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:20',
            'content' => 'required|min:30',
        ]);

        $input = $request->all();
        
        $article = $this->article->findOrFail($id);

        $article->update($input);

        $article->category()->sync($request['article_category']);

        $article->tag()->sync($request['article_tag']);
        
        return redirect()->back();
    }
}
