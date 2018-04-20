

    {!! Former::open()->id('termForm')
        ->action(route('api.terms.users', [$term->id]))
        ->method('PUT')
        ->autocomplete('off')
    !!}

    {!! Former::populateField('users', implode(', ', $term->users->pluck('username')->toArray())) !!}
    {!! Former::populateField('groups', implode(', ', $term->users->pluck('name')->toArray())) !!}

    {!! Former::input()->type('hidden')->name('id')->forceValue($term->id) !!}


<fieldset>
    <legend>Subscribers for {{$term->name}}</legend>

    {!! Former::textarea('users')->label('Users')->blockhelp('Enter usernames separated by commas, spaces, and/or new lines.') !!}

    {!! Former::textarea('groups')->label('Groups')->blockhelp('Enter group names separated by commas, spaces, and/or new lines.') !!}

</fieldset>



{!! Former::close() !!}