@extends('layouts.app')

@section('content')
<div class="faq-container">
    <!-- Header Section -->
    <div class="faq-header">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h1 class="faq-title">Frequently Asked Questions</h1>
                <p class="faq-subtitle">
                    <i class="fas fa-question-circle me-2"></i>
                    Credit Exemption Management System
                </p>
                <p class="faq-description">
                    Find answers to commonly asked questions about the credit exemption process.
                </p>
            </div>
            <div class="col-lg-3 text-end">
                <div class="faq-illustration">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>
    </div>

    @if($faqs->isEmpty())
        <div class="alert alert-info" style="border-radius: 12px;">
            <i class="fas fa-info-circle me-2"></i>
            No FAQs have been published yet. Please check back later or contact support for assistance.
        </div>
    @else
        <!-- Category Filter -->
        @if($categories->isNotEmpty())
        <div class="card mb-4" style="border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <div class="card-body" style="padding: 1.5rem;">
                <h5 class="mb-3" style="color: #2d3748; font-weight: 600;">
                    <i class="fas fa-filter me-2" style="color: #667eea;"></i>Filter by Category
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
    <div class="contact-section mt-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4><i class="fas fa-headset me-2"></i>Still have questions?</h4>
                <p class="mb-0">Can't find what you're looking for? Visit our Help & Support page or contact the HEA office.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('student.help') }}" class="btn btn-light me-2">
                    <i class="fas fa-life-ring me-1"></i> Help & Support
                </a>
            </div>
        </div>
    </div>

    <!-- Back to Dashboard Button -->
    <div class="text-center mt-4 mb-5">
        <a href="{{ route('student.dashboard') }}" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 12px; padding: 1rem 3rem; font-weight: 600; border: none; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<style>
.faq-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 1rem;
}

.faq-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
}

.faq-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.faq-subtitle {
    font-size: 1.15rem;
    margin-bottom: 1rem;
    opacity: 0.95;
}

.faq-description {
    font-size: 1.1rem;
    line-height: 1.6;
    opacity: 0.9;
}

.faq-illustration {
    font-size: 5rem;
    opacity: 0.2;
    text-align: center;
}

/* Category Filter */
.category-btn {
    padding: 0.5rem 1rem;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 20px;
    font-weight: 500;
    color: #4a5568;
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.category-btn.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    color: white;
}

/* FAQ Cards */
.faq-card {
    background: white;
    border-radius: 16px;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.faq-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.faq-card.hidden {
    display: none;
}

.faq-card-header {
    padding: 0;
}

.faq-question {
    width: 100%;
    padding: 1.5rem;
    border: none;
    background: none;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.faq-question:hover {
    background: #f8fafc;
}

.faq-question:not(.collapsed) {
    background: linear-gradient(135deg, #f0f4ff, #e8eeff);
}

.question-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex: 1;
    padding-right: 1rem;
}

.question-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
}

.faq-category-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    width: fit-content;
}

.faq-icon {
    color: #667eea;
    font-size: 1rem;
    transition: transform 0.3s ease;
}

.faq-question:not(.collapsed) .faq-icon {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0 1.5rem 1.5rem;
    color: #4a5568;
    line-height: 1.8;
    font-size: 1rem;
}

/* Contact Section */
.contact-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
}

.contact-section h4 {
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.contact-section p {
    opacity: 0.9;
}

/* Responsive */
@media (max-width: 768px) {
    .faq-header {
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .faq-title {
        font-size: 1.8rem;
    }

    .faq-illustration {
        font-size: 3rem;
        margin-top: 1rem;
    }

    .faq-question {
        padding: 1rem;
    }

    .question-text {
        font-size: 1rem;
    }
}
</style>

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
@endsection
