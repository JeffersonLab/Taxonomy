
{!! Former::populate( $term ) !!}


@if (isset($term->id))
    {!! Former::open()->id('termForm')
        ->action(route('api.terms.update', [$term->id]))
        ->method('PUT')
        ->autocomplete('off')
        ->rules(Atlis\Taxonomy\Term::$rules)
    !!}
@else
    {!! Former::open()->id('termForm')
        ->action(route('api.terms.create'))
        ->method('POST')
        ->autocomplete('off')
        ->rules(Atlis\Taxonomy\Term::$rules)

    !!}
@endif

@if ( isset($term->id))
    {!! Former::input()->type('hidden')->name('id')->forceValue($term->id) !!}
@endif

{!! Former::text('name')->label('Term Name')
            ->blockHelp('A name for the term.  Must be unique within its vocabulary.')  !!}

<?php
//dd(Atlis\Taxonomy\Term::$rules);
?>

{!! Former::select('parent_id')->label('Parent Term')
    ->options(['' => '_TOP'] + $term->vocabulary->rootTerms()->pluck('name','id')->toArray())
     ->blockHelp('The root term beneath which to place this one.')!!}


{!! Former::url('url')->label('Term Link')
            ->blockHelp('A URL to which the term should link when displayed.')  !!}


{!! Former::textarea('description')->label('Description')->blockHelp('A description of the term') !!}



@if ( isset($term->vocabulary_id))
    {!! Former::input()->type('hidden')->name('vocabulary_id')->forceValue($term->vocabulary_id) !!}
@endif


{!! Former::close() !!}