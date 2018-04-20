@extends('layouts.default')

@section('pageTitle')
    Taxonomy Vocabularies
@stop

@section('content')

    <style>
        table.listing{
            width: 95%;
        }
    </style>
    <h2>Taxonomy</h2>

    <div class="container-fluid">
        <div class="row">
            <p class="col-md-12">
                Taxonomy here refers to the sets of terms that provide classification of ATLis tasks,
                and to the "vocabularies" of related terms by which the terms are grouped.  The taxonomy
                terms are sometimes referred to as "tags" and "tagging" the process of associating the
                terms with tasks.
            </p>
        </div>
    </div>


        <h3>Vocabularies</h3>
    <div class="container-fluid">
        <div class="row">
            <p class="col-md-12">The table below lists the taxonomy vocabularies that have been defined.
                Clicking on a vocabulary name will let the user view the terms it contaings.  Creating
                a new vocabulary is an administrative task.  Afer a vocabulary has been created, its
                management is delegated to a set of users to manage addition and removal of terms.</p>
        </div>

        {{--@can('create', new \Jlab\Taxonomy\Vocabulary())--}}
        <a class="btn btn-sm btn-default" href="{{route('vocabularies.create')}}" role="button">
            <span class="glyphicon glyphicon-plus" ></span>New</a>
        {{--@endcan--}}
        <table class="table-striped listing">
            <tr><th>Vocabulary</th><th>Description</th></tr>
        @foreach($vocabularies as $vocab)
            <tr>
                <td>
                    {!! link_to_route('vocabularies.show',$vocab->name,[$vocab->id]) !!}
                </td>
                <td>{{$vocab->description}}</td>
            </tr>

        @endforeach
        </table>
    </div>
@stop

