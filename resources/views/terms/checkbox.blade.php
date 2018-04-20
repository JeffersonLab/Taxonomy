<div class="form-group">
    <?php $name = $name.'['.$term->id.']'; ?>
    {{ Form::checkbox($name, $term->id, null, ['id'=>'cb-term-'.$term->id]) }}

    @if ($term->hasSubscribers())
        <span class="subscribed" data-toggle="tooltip" title="{{$term->subscribersList()}}">
             {{ Form::label($name, $term->name, [
                'id' => 'lb-term-'.$term->id,
                'class' => 'control-label']) }}
         </span>
    @else
        {{ Form::label($name, $term->name, [
            'id' => 'lb-term-'.$term->id,
            'class' => 'control-label']) }}
    @endif
</div>