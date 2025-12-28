@if(in_array($list->status, ['draft', 'rejected']))
    <a href="{{ route('resource_person.equivalency_lists.edit', $list) }}" class="btn btn-sm btn-primary" title="Edit List">
        <i class="fas fa-edit"></i> Edit
    </a>
@else
    <a href="{{ route('resource_person.equivalency_lists.show', $list) }}" class="btn btn-sm btn-outline-primary" title="View List">
        <i class="fas fa-eye"></i> View
    </a>
@endif
