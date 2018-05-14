<?php

namespace Jlab\Taxonomy\Http\Controllers;


use Jlab\Taxonomy\Http\Requests\TermFormRequest;
use Jlab\Taxonomy\Term;
use Jlab\Taxonomy\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use \Exception;

class ApiController extends Controller
{


    public function terms(){
        $data = [];
        foreach (Term::all() as $item) {
            $data[] = $item->apiArray();
        }
        return $this->response($data);
    }

    public function termsCreate(TermFormRequest $request){
        Log::debug($request);
        $item = new Term($request->input());

        if ($item->save()){
            return $this->response($item->apiArray());
        }else{
            return $this->error('Failed to save new term');
        }

    }

    public function termsUpdate(TermFormRequest $request){

        //Log::debug($request);
        if ($request->has('id')){
            $item = Term::find($request->get('id'));
            if ($item) {
                $item->fill($request->input());
                Log::debug($request->input());
                if ($item->save()) {
                    //return $this->response(['ddp']);
                    return $this->response($item->apiArray());
                } else {
                    return $this->error('Failed to save new term');
                }
            }
        }
        return $this->error('Invalid id to update');
    }


    public function termUsers(Term $term, Request $request){

        try {

            $term = Term::findOrFail($request->get('id'));
            // Users
            $users = preg_split("/([\r\n|\n|\r|\,|\s]+)/", $request->input('users'));
            $users = array_map('trim', $users);
            //var_dump($term->toArray());
            //return $this->error('bad');
            $term->setUsers($users);
            // Groups
            $groups = preg_split("/(\r\n|\n|\r)/", $request->input('groups'));
            $groups = array_map('trim', $groups);
            $term->setGroups($groups);

            return $this->response($term->apiArray());
        } catch (\Exception $e){
            return $this->error($e->getMessage());
        }
        
        
    }

    /**
     * Centralized handling of requests for modal forms
     *
     * @return mixed
     */
    public function forms(){
        try {
            switch (Input::get('model', '')) {
                case 'term' :
                    return Term::form(Input::all());
            }
        } catch (Exception $e){
            Log::error($e->getMessage());
            return $this->error($e->getMessage());
        }
        return $this->error('Model name missing or not recognized.');
    }


    /**
     * Centralized handling of requests for ajax delete requests
     *
     * @return mixed
     */
    public function deletions(){
        try {
            switch (Input::get('model', '')) {
                case 'term' :
                    return $this->termsDelete(Input::get('id'));
            }
        } catch (Exception $e){
            Log::error($e->getMessage());
            return $this->error($e->getMessage());
        }
        return $this->error('Model name missing or not recognized.');
    }

    public function termsSort(){
        if (Input::has('parent_id') && Input::has('term_ids')){
            $weight = 1;
            try{
                $parent_id = Input::get('parent_id');
                if ($parent_id === 0){
                    $parent_id = '';
                }

                foreach (Input::get('term_ids') as $term_id){
                    $term = Term::findOrFail($term_id);
                    $term->weight = $weight;
                    $term->parent_id = $parent_id;
                    $term->saveOrFail();
                    $weight++;
                }
                return $this->response('saved');
            } catch (Exception $e){
                return $this->error($e->getMessage());
            }

        }
        return $this->error('Nothing to sort.');
    }

    public function termsDelete($id){
        if (Term::destroy($id)){
            return $this->response('Deleted');
        }
        return $this->error('Failed to delete');
    }


    public function termsItem($id){
        $item = Term::find($id);
        if ($item){
            return $this->response($item->apiArrayWithRelations());
        }
        return $this->error('Invalid Id');
    }


    public function vocabularies(){
        $data = [];
        foreach (Vocabulary::all() as $item) {
            $data[] = $item->apiArray();
        }
        return $this->response($data);
    }

    public function vocabulariesItem($id){
        $item = Vocabulary::find($id);
        if ($item){
            return $this->response($item->apiArrayWithRelations());
        }
        return $this->error('Invalid Id');
    }


    /**
     * Returns a json success response.
     *
     * @param $data
     * @return mixed
     */
    public function response($data){
        $struct['status'] = 'ok';
        $struct['data'] = $data;

        $options = 0;
        if (Input::get('pretty')){
            $options = JSON_PRETTY_PRINT;
        }

        $response = response()->json($struct, 200, [], $options);

        if (Input::has('jsonp')){
            $response->setCallback(Input::get('jsonp'));
        }

        return $response;
    }

    public function clearCache(){
        Cache::flush();
        return $this->response([]);
    }


    /**
     * Returns a json error response.
     *
     * @param  string $msg
     * @param  int $code
     * @return mixed
     */
    public function error($msg, $code=404){
        $struct['status'] = 'fail';
        $struct['message'] = $msg;

        $options = 0;
        if (Input::get('pretty')){
            $options = JSON_PRETTY_PRINT;
        }

        $response = response()->json($struct, $code, [], $options);

        if (Input::has('jsonp')){
            $response->setCallback(Input::get('jsonp'));
        }
        return $response;
    }
}
