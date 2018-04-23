@extends('layouts.default')

@section('pageTitle')
    Vocabulary {{$vocabulary->name}}
@stop

@section('content')

    <style>

        /*
        * Taxonomy Specific
        * Maybe can later be generalized
         */
        div.taxonomy-label {
            display: table-cell;
            width: 60%;
        }

        div.taxonomy-actions {
            display: table-cell;
            text-align: right;
            width: 40%;
        }

        div.taxonomy-term {
            padding-right: 15px;
            display: table;
            width: 100%;
            border-bottom: 1px dotted #ccc;
        }

        div.taxonomy-term:hover {
            background-color: #ccc;
        }


    </style>


    <div class="container-fluid">
        <h2>Vocabulary {{$vocabulary->name}}</h2>

        {{--@can('update', $vocabulary)--}}

            <a class="btn btn-sm btn-default" href="{{route('vocabularies.edit',[$vocabulary->id])}}" role="button">
                <i class="glyphicon glyphicon-edit">Edit</i>
            </a>
        {{--@endcan--}}
    </div>

    <div class="container-fluid">

        <h3>Description</h3>
        <div class="row">
            <p class="col-md-12">{{$vocabulary->description}}</p>
        </div>

        <h3>Terms</h3>
        {{--@can('update', $vocabulary)--}}
            <div class="container actions-wrapper" data-model="term" data-vocabulary_id="{{$vocabulary->id}}"
                 role="button">
                <a href="#" class="btn btn-sm btn-default btn-add"><i class="glyphicon glyphicon-plus"></i>Add</a>
            </div>
        {{--@endcan--}}

        @if ( $vocabulary->terms->count() > 0)
            <ol id="term-0" class="sortable">
                @foreach ($vocabulary->rootTerms() as $term)
                    @include('taxonomy::terms.branch', ['term'=>$term])
                @endforeach
            </ol>
        @endif


        {{--@include('includes.ownership_details',['model'=>'vocabulary', 'item'=>$vocabulary])--}}

    </div>




    {{--@include('includes.modal', [$modal_id='editItem', $modal_title='Edit Term']);--}}

    <script src="{{asset('vendor/jlab/taxonomy/js/taxonomy.js')}}"></script>

    <script>
        $(function () {
            //@see http://a.shinynew.me/post/4641524290/jquery-ui-nested-sortables
            $('.sortable').sortable({
                connectWith: '.sortable',
                update: function (event, ui) {
                    taxonomy.reorderTerms($(event.target).attr('id'), $(event.target).sortable("toArray"))
                }
            });
            $('.sortable>li>ol').sortable({
                connectWith: '.sortable>li>ol',
                update: function (event, ui) {
                    taxonomy.reorderTerms($(event.target).attr('id'), $(event.target).sortable("toArray"))
                }
            });

            $('.btn-edit').on('click', taxonomy.editItemDialog);
            $('.btn-add').on('click', taxonomy.editItemDialog);
            $('.btn-users').on('click', taxonomy.editUsersDialog);
            $('.btn-delete').on('click', taxonomy.deleteItemDialog);


            $('.editable').editable();

        });


    </script>


@stop


<?php
//Move to an HTML Macro

?>