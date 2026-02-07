@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --uitm-primary: #1e3a8a;
        --uitm-primary-dark: #1e293b;
        --uitm-primary-light: #3b82f6;
        --uitm-red: #dc2626;
        --uitm-amber: #f59e0b;
        --uitm-green: #10b981;
        --neutral-900: #171717;
        --neutral-800: #262626;
        --neutral-700: #404040;
        --neutral-600: #525252;
        --neutral-500: #737373;
        --neutral-400: #a3a3a3;
        --neutral-300: #d4d4d4;
        --neutral-200: #e5e5e5;
        --neutral-100: #f5f5f5;
        --neutral-50: #fafafa;
    }

    body {
        font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--neutral-100);
    }

    .faq-container {
        width: 100%;
        padding: 0 1rem;
    }

    /* Page Header */
    .page-header {
        position: relative;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 16px;
        padding: 2.5rem;
        color: white;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-header-content {
        position: relative;
        z-index: 1;
    }

    .page-header .eyebrow {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--uitm-amber);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-header .eyebrow::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--uitm-amber);
        border-radius: 2px;
    }

    .page-header h1 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: -0.025em;
    }

    .page-header p {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 0;
        max-width: 600px;
    }

    .header-illustration {
        position: absolute;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 6rem;
        opacity: 0.1;
    }

    /* Empty Alert */
    .empty-alert {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(14, 165, 233, 0.05) 100%);
        border: 2px solid #0ea5e9;
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .empty-alert i {
        color: #0ea5e9;
        font-size: 1.25rem;
    }

    .empty-alert span {
        color: var(--neutral-700);
        font-size: 0.9rem;
    }

    /* Category Filter */
    .filter-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .filter-title {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--neutral-700);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-title i {
        color: var(--uitm-primary);
    }

    .category-btn {
        font-family: 'IBM Plex Sans', sans-serif;
        padding: 0.5rem 1rem;
        border: 2px solid var(--neutral-200);
        background: white;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
        color: var(--neutral-600);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .category-btn:hover {
        border-color: var(--uitm-primary);
        color: var(--uitm-primary);
    }

    .category-btn.active {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-color: transparent;
        color: white;
    }

    /* FAQ Cards */
    .faq-card {
        background: white;
        border: 2px solid var(--neutral-200);
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .faq-card:hover {
        border-color: var(--neutral-300);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .faq-card.hidden {
        display: none;
    }

    .faq-card-header {
        padding: 0;
    }

    .faq-question {
        width: 100%;
        padding: 1.25rem 1.5rem;
        border: none;
        background: none;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .faq-question:hover {
        background: var(--neutral-50);
    }

    .faq-question:not(.collapsed) {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.05) 0%, rgba(30, 58, 138, 0.02) 100%);
    }

    .question-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
        padding-right: 1rem;
    }

    .question-text {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--neutral-800);
    }

    .faq-category-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        color: white;
        border-radius: 12px;
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        width: fit-content;
    }

    .faq-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--neutral-100);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--uitm-primary);
        font-size: 0.8rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .faq-question:not(.collapsed) .faq-icon {
        background: var(--uitm-primary);
        color: white;
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0 1.5rem 1.5rem;
        color: var(--neutral-600);
        line-height: 1.8;
        font-size: 0.95rem;
        border-top: 1px solid var(--neutral-100);
        margin-top: 0;
        padding-top: 1rem;
    }

    /* Contact Section */
    .contact-section {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        color: white;
        margin-top: 2rem;
    }

    .contact-section h4 {
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .contact-section p {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .btn-contact {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-contact:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    /* Back Button */
    .btn-back {
        background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
        border: none;
        color: white;
        font-family: 'IBM Plex Sans', sans-serif;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.875rem 2rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }

        .header-illustration {
            display: none;
        }

        .faq-question {
            padding: 1rem;
        }

        .question-text {
            font-size: 0.95rem;
        }

        .contact-section {
            text-align: center;
        }

        .contact-section .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="faq-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="eyebrow">Support Center</div>
            <h1><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions</h1>
            <p>Find answers to commonly asked questions about the credit exemption process.</p>
        </div>
        <div class="header-illustration">
            <i class="fas fa-comments"></i>
        </div>
    </div>

    @if($faqs->isEmpty())
        <div class="empty-alert">
            <i class="fas fa-info-circle"></i>
            <span>No FAQs have been published yet. Please check back later or contact support for assistance.</span>
        </div>
    @else
        <!-- Category Filter -->
        @if($categories->isNotEmpty())
        <div class="filter-card">
            <h5 class="filter-title">
                <i class="fas fa-filter"></i>Filter by Category
            </h5>
            <div class="d-flex flex-wrap gap-2">
                <button class="category-btn active" data-category="all">
                    <i class="fas fa-th-large me-1"></i> All
                </button>
                @foreach($categories as $category)
                <button class="category-btn" data-category="{{ Str::slug($category) }}">
                    {{ $category }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <!-- FAQ Accordion -->
        <div class="accordion" id="faqAccordion">
            @foreach($faqs as $index => $faq)
            <div class="faq-card" data-category="{{ Str::slug($faq->category ?? 'general') }}">
                <div class="faq-card-header" id="heading{{ $index }}">
                    <button class="faq-question collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                            aria-controls="collapse{{ $index }}">
                        <div class="question-content">
                            @if($faq->category)
                            <span class="faq-category-badge">{{ $faq->category }}</span>
                            @endif
                            <span class="question-text">{{ $faq->question }}</span>
                        </div>
                        <span class="faq-icon">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>
                </div>
                <div id="collapse{{ $index }}" class="collapse"
                     aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                    <div class="faq-answer">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Contact Section -->
    <div class="contact-section">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4><i class="fas fa-headset me-2"></i>Still have questions?</h4>
                <p>Can't find what you're looking for? Visit our Help & Support page or contact the HEA office.</p>
            </div>
            <a href="{{ route('student.help') }}" class="btn-contact">
                <i class="fas fa-life-ring me-1"></i> Help & Support
            </a>
        </div>
    </div>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('student.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>Back to Dashboard
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryBtns = document.querySelectorAll('.category-btn');
    const faqCards = document.querySelectorAll('.faq-card');

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const category = this.dataset.category;

            // Filter FAQ cards
            faqCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
});
</script>
@endpush
