<li id="term-{{$term->id}}">
    <div class="taxonomy-term">
        <div class="drag-handle"><i class="glyphicon glyphicon-move"></i></div>
        {{--<div class="taxonomy-label">{{$term}} {{$term->id}}</div>--}}
        <div class="taxonomy-label">{{$term->name}}</div>
        <div class="taxonomy-actions actions-wrapper"
             data-model="term" data-id="{{$term->id}}"
             data-vocabulary_id="{{$term->vocabulary_id}}">
            {{--@can('update', $vocabulary)--}}
                <a href="#" class="btn-xs btn-default btn-edit">
                    <i class="glyphicon glyphicon-edit"></i>Edit
                </a>
                @if ($term->isLeaf())
                    <a href="#" class="btn-xs btn-default btn-delete" data-confirm="{{$term->name}}">
                        <i class="glyphicon glyphicon-trash"></i>Delete
                    </a>
                @endif
            {{--@endcan--}}

        </div>
    </div>


    <ol id="term-{{$term->id}}">
        @foreach ($term->children as $child)
            @include('taxonomy::terms.branch', ['term'=>$child])
        @endforeach
    </ol>

</li>