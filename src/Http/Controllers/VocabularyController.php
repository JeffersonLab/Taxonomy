<?php

namespace Jlab\Taxonomy\Http\Controllers;

use Jlab\Taxonomy\Term;
use Jlab\Taxonomy\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use \Jlab\Taxonomy\Http\Requests\VocabularyFormRequest;
use Krucas\Notification\Facades\Notification;
use \Exception;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Vocabulary::all()->sortBy('name');
        return View::make('taxonomy::vocabularies.index')
            ->with('vocabularies', $models);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Vocabulary::class);

        return View::make('taxonomy::vocabularies.edit')
            ->with('vocabulary', new Vocabulary())
            ->with('formAction', action('\Jlab\Taxonomy\Http\Controllers\VocabularyController@store'))
            ->with('formMethod', 'POST');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VocabularyFormRequest|Request $request pre-validated form request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(VocabularyFormRequest $request)
    {
        $this->authorize('create', Vocabulary::class);

        $vocabulary = new Vocabulary($request->input());
        DB::beginTransaction();
        if ($vocabulary->save()){
                try{
                    if ($request->has('terms')) {
                        Term::makeFromText($request->get('terms',[]), $vocabulary->id);
                    }
                    DB::commit();
                    Notification::success("The vocabulary was created");
                    return redirect()->action('\Jlab\Taxonomy\Http\Controllers\VocabularyController@show',[$vocabulary->id]);
                }catch (Exception $e){
                    $msg = "An error was encountered and terms could not be added: ";
                    Notification::warning($msg.$e->getMessage());
                    Log::error($e->getMessage());
                }
        }else{
            Notification::error('Failed to save the vocabulary');
        }
        DB::rollback();
        return redirect()->action('\Jlab\Taxonomy\Http\Controllers\VocabularyController@create')
            ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param Vocabulary $vocabulary
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param int $id
     */
    public function show(\Jlab\Taxonomy\Vocabulary $vocabulary)
    {
        // lazy eager-loading of relations we will use
        $vocabulary->load('terms','terms.children');

        return view('taxonomy::vocabularies.item')
            ->with('vocabulary', $vocabulary);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Vocabulary $vocabulary
     * @return \Illuminate\Http\Response
     */
    public function edit(Vocabulary $vocabulary)
    {
        $this->authorize('update', $vocabulary);

        return View::make('taxonomy::vocabularies.edit')
            ->with('vocabulary', $vocabulary)
            ->with('formAction', action('\Jlab\Taxonomy\Http\Controllers\VocabularyController@update',['id' => $vocabulary->id]))
            ->with('formMethod', 'PUT');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VocabularyFormRequest|Request $request
     * @param Vocabulary                    $vocabulary
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(VocabularyFormRequest $request, Vocabulary $vocabulary)
    {
        $this->authorize('update', $vocabulary);

        $vocabulary->fill($request->input());
        if ($vocabulary->save()){
            Notification::success("The vocabulary was updated");
            return redirect()->action('\Jlab\Taxonomy\Http\Controllers\VocabularyController@show',[$vocabulary->id]);
        }else{
            Notification::error('Failed to save the vocabulary');
        }
        return redirect()->action('\Jlab\Taxonomy\Http\Controllers\VocabularyController@create')
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vocabulary $vocabulary
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Vocabulary $vocabulary)
    {
        $this->authorize('delete', $vocabulary);

        if ($vocabulary->delete()){
            Notification::success("Vocabulary $vocabulary->name was deleted");
        }else{
            Notification::error("Vocabulary $vocabulary->name could not be deleted");
        }
        return redirect()->action('\Jlab\Taxonomy\Http\Controllers\VocabularyController@index');
    }
}
