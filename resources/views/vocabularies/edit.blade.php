@extends('layouts.default')

@section('pageTitle')
    Vocabulary
@stop

@section('content')

    <h2>Vocabulary Form</h2>

    <div class="container-fluid">

        {!! Former::setOption('default_form_type', 'vertical')  !!}
        {!! Former::setOption('fetch_errors', true)  !!}

        {!! Former::populate( $vocabulary ) !!}
        {!! Former::populateField('terms', $vocabulary->rootTermsFormValue()) !!}

        {!! Former::open()->id('vocabularyForm')
              ->secure()
              ->autocomplete('off')
              ->action($formAction)
              ->rules([
                'name' => 'required|max:80',
                'description' => 'max:1000',
              ])
              ->method($formMethod)
        !!}

        {!! Former::input()->type('hidden')->name('_token')->forceValue(csrf_token()) !!}
        @if ( $vocabulary->id)
            {!! Former::input()->type('hidden')->name('id')->forceValue($vocabulary->id) !!}
        @endif


        {!! Former::text('name')->label('Vocabulary Name')
                    ->blockHelp('A unique name for the vocabulary')  !!}



        {!! Former::textarea('description')->label('Description')->blockHelp('A description vocabulary') !!}


        @if (! $vocabulary->id)
            {!! Former::textarea('terms')->label('Terms')
            ->value($vocabulary->rootTermsFormValue())
            ->blockHelp('[Optional] Seed the vocabulary with some top level terms.  '.
                        'Term | Description - separated by newlines') !!}

        @endif

        <div class="form-actions">
            {!! Former::input()->type('Submit')
                        ->name('submit')->forceValue('Save')
                        ->class('btn-primary btn btn-large')->raw()
            !!}
            {!! Former::input()->type('Submit')
                        ->name('submit')->forceValue('Cancel')
                        ->class('btn-secondary btn btn-large')->raw()
            !!}
        </div>

        {!! Former::close() !!}
    </div>


@stop

