{{--Incoming Vars:
    Term   $term  Taxonomy Term to display
    string $name  for the checkboxes to share
    string $colClass  class name from col2|col3|col4|col5|col6
 --}}

@if ($term->isLeaf())
    <li class="checkbox-list-item" id="term-{{$term->id}}">
        {!! Form::taxonomyTermCheckbox($term, $name) !!}
    </li>
@else
    <li class="checkbox-list-heading" id="term-{{$term->id}}">
        {{$term->name}}
        <ul class="{{$colClass}} checkbox-sublist" id="list-{{$term->id}}">
            @foreach ($term->children as $child)
                @include('taxonomy.terms.checkbox_list', ['term'=>$child, 'name'=>$name, 'colClass'=>$colClass])
            @endforeach
        </ul>
    </li>
@endif