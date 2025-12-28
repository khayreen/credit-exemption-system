@php
    $statusConfig = [
        'draft' => ['class' => 'warning text-dark', 'icon' => 'edit', 'label' => 'Draft'],
        'submitted' => ['class' => 'info', 'icon' => 'paper-plane', 'label' => 'Submitted'],
        'under_review' => ['class' => 'secondary', 'icon' => 'search', 'label' => 'Under Review'],
        'endorsed' => ['class' => 'primary', 'icon' => 'check-circle', 'label' => 'Endorsed'],
        'published' => ['class' => 'success', 'icon' => 'check-double', 'label' => 'Published'],
        'rejected' => ['class' => 'danger', 'icon' => 'times-circle', 'label' => 'Rejected'],
    ];
    $config = $statusConfig[$list->status] ?? ['class' => 'secondary', 'icon' => 'question', 'label' => ucfirst($list->status)];
@endphp

<span class="badge bg-{{ $config['class'] }}">
    <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['label'] }}
</span>

@if($list->is_active && $list->status === 'published')
    <span class="badge bg-success ms-1" title="This is the active list for students">
        <i class="fas fa-star"></i> Active
    </span>
@endif
