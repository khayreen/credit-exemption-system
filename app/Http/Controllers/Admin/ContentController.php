<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermsVersion;
use App\Models\Announcement;
use App\Models\FaqItem;
use App\Models\HelpArticle;
use App\Models\ContactSetting;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    // ==================== TERMS & CONDITIONS ====================

    /**
     * Display terms and conditions management
     */
    public function termsIndex()
    {
        $currentTerms = TermsVersion::getCurrent();
        $termsHistory = TermsVersion::getHistory();

        return view('admin.content.terms.index', compact('currentTerms', 'termsHistory'));
    }

    /**
     * Show form to create new terms version
     */
    public function termsCreate()
    {
        $currentTerms = TermsVersion::getCurrent();
        $nextVersion = TermsVersion::generateNextVersion();

        return view('admin.content.terms.create', compact('currentTerms', 'nextVersion'));
    }

    /**
     * Store new terms version
     */
    public function termsStore(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20',
            'content' => 'required|string',
            'effective_date' => 'required|date',
            'set_as_current' => 'boolean',
        ]);

        $terms = TermsVersion::create([
            'version' => $validated['version'],
            'content' => $validated['content'],
            'effective_date' => $validated['effective_date'],
            'is_current' => false,
            'created_by' => auth()->id(),
        ]);

        if ($request->boolean('set_as_current')) {
            $terms->setAsCurrent();
        }

        // Log action
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'terms_created',
            'details' => json_encode([
                'version' => $terms->version,
                'set_as_current' => $request->boolean('set_as_current'),
            ]),
        ]);

        return redirect()->route('admin.content.terms.index')
            ->with('success', 'Terms & Conditions version created successfully.');
    }

    /**
     * Show form to edit terms version
     */
    public function termsEdit(TermsVersion $terms)
    {
        return view('admin.content.terms.edit', compact('terms'));
    }

    /**
     * Update terms version
     */
    public function termsUpdate(Request $request, TermsVersion $terms)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20',
            'content' => 'required|string',
            'effective_date' => 'required|date',
        ]);

        $terms->update($validated);

        // Log action
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'terms_updated',
            'details' => json_encode([
                'version' => $terms->version,
            ]),
        ]);

        return redirect()->route('admin.content.terms.index')
            ->with('success', 'Terms & Conditions updated successfully.');
    }

    /**
     * Set terms version as current
     */
    public function termsSetCurrent(TermsVersion $terms)
    {
        $terms->setAsCurrent();

        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => auth()->id(),
            'action' => 'terms_set_current',
            'details' => json_encode(['version' => $terms->version]),
        ]);

        return back()->with('success', "Version {$terms->version} is now the current Terms & Conditions.");
    }

    /**
     * Delete terms version
     */
    public function termsDestroy(TermsVersion $terms)
    {
        if ($terms->is_current) {
            return back()->with('error', 'Cannot delete the current Terms & Conditions version.');
        }

        $version = $terms->version;
        $terms->delete();

        return back()->with('success', "Terms version {$version} has been deleted.");
    }

    // ==================== ANNOUNCEMENTS ====================

    /**
     * Display announcements list
     */
    public function announcementsIndex()
    {
        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.content.announcements.index', compact('announcements'));
    }

    /**
     * Show form to create announcement
     */
    public function announcementsCreate()
    {
        $roles = [
            'all' => 'All Users',
            'student' => 'Students',
            'academic_advisor' => 'Academic Advisors',
            'program_coordinator' => 'Program Coordinators',
            'resource_person' => 'Resource Persons',
            'hea_personnel' => 'HEA Personnel',
        ];

        return view('admin.content.announcements.create', compact('roles'));
    }

    /**
     * Store new announcement
     */
    public function announcementsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_dismissible' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_dismissible'] = $request->boolean('is_dismissible', true);
        $validated['is_active'] = $request->boolean('is_active', true);

        Announcement::create($validated);

        return redirect()->route('admin.content.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Show form to edit announcement
     */
    public function announcementsEdit(Announcement $announcement)
    {
        $roles = [
            'all' => 'All Users',
            'student' => 'Students',
            'academic_advisor' => 'Academic Advisors',
            'program_coordinator' => 'Program Coordinators',
            'resource_person' => 'Resource Persons',
            'hea_personnel' => 'HEA Personnel',
        ];

        return view('admin.content.announcements.edit', compact('announcement', 'roles'));
    }

    /**
     * Update announcement
     */
    public function announcementsUpdate(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_dismissible' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_dismissible'] = $request->boolean('is_dismissible', true);
        $validated['is_active'] = $request->boolean('is_active', true);

        $announcement->update($validated);

        return redirect()->route('admin.content.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Delete announcement
     */
    public function announcementsDestroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Toggle announcement active status
     */
    public function announcementsToggle(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);
        $status = $announcement->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Announcement {$status} successfully.");
    }

    // ==================== FAQ ====================

    /**
     * Display FAQ list
     */
    public function faqIndex()
    {
        $faqItems = FaqItem::ordered()->paginate(20);
        $categories = FaqItem::getCategories();

        return view('admin.content.faq.index', compact('faqItems', 'categories'));
    }

    /**
     * Show form to create FAQ item
     */
    public function faqCreate()
    {
        $categories = FaqItem::getCategories();
        return view('admin.content.faq.create', compact('categories'));
    }

    /**
     * Store new FAQ item
     */
    public function faqStore(Request $request)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:100',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        FaqItem::create($validated);

        return redirect()->route('admin.content.faq.index')
            ->with('success', 'FAQ item created successfully.');
    }

    /**
     * Show form to edit FAQ item
     */
    public function faqEdit(FaqItem $faq)
    {
        $categories = FaqItem::getCategories();
        return view('admin.content.faq.edit', compact('faq', 'categories'));
    }

    /**
     * Update FAQ item
     */
    public function faqUpdate(Request $request, FaqItem $faq)
    {
        $validated = $request->validate([
            'category' => 'nullable|string|max:100',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $faq->update($validated);

        return redirect()->route('admin.content.faq.index')
            ->with('success', 'FAQ item updated successfully.');
    }

    /**
     * Delete FAQ item
     */
    public function faqDestroy(FaqItem $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ item deleted successfully.');
    }

    // ==================== HELP ARTICLES ====================

    /**
     * Display help articles list
     */
    public function helpIndex()
    {
        $articles = HelpArticle::with('creator')->ordered()->paginate(20);
        $categories = HelpArticle::getCategories();

        return view('admin.content.help.index', compact('articles', 'categories'));
    }

    /**
     * Show form to create help article
     */
    public function helpCreate()
    {
        $categories = HelpArticle::getCategories();
        return view('admin.content.help.create', compact('categories'));
    }

    /**
     * Store new help article
     */
    public function helpStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['created_by'] = auth()->id();

        HelpArticle::create($validated);

        return redirect()->route('admin.content.help.index')
            ->with('success', 'Help article created successfully.');
    }

    /**
     * Show form to edit help article
     */
    public function helpEdit(HelpArticle $article)
    {
        $categories = HelpArticle::getCategories();
        return view('admin.content.help.edit', compact('article', 'categories'));
    }

    /**
     * Update help article
     */
    public function helpUpdate(Request $request, HelpArticle $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $article->update($validated);

        return redirect()->route('admin.content.help.index')
            ->with('success', 'Help article updated successfully.');
    }

    /**
     * Delete help article
     */
    public function helpDestroy(HelpArticle $article)
    {
        $article->delete();
        return back()->with('success', 'Help article deleted successfully.');
    }

    // ==================== CONTACT SETTINGS ====================

    /**
     * Display contact settings
     */
    public function contactIndex()
    {
        $contacts = ContactSetting::orderBy('key')->get();

        return view('admin.content.contact.index', compact('contacts'));
    }

    /**
     * Show form to create contact setting
     */
    public function contactCreate()
    {
        return view('admin.content.contact.create');
    }

    /**
     * Store new contact setting
     */
    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100|unique:contact_settings,key',
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:500',
            'type' => 'required|in:email,phone,url,text',
        ]);

        $validated['updated_by'] = auth()->id();

        ContactSetting::create($validated);

        return redirect()->route('admin.content.contact.index')
            ->with('success', 'Contact setting created successfully.');
    }

    /**
     * Show form to edit contact setting
     */
    public function contactEdit(ContactSetting $contact)
    {
        return view('admin.content.contact.edit', compact('contact'));
    }

    /**
     * Update contact setting
     */
    public function contactUpdate(Request $request, ContactSetting $contact)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:500',
            'type' => 'required|in:email,phone,url,text',
        ]);

        $validated['updated_by'] = auth()->id();

        $contact->update($validated);

        return redirect()->route('admin.content.contact.index')
            ->with('success', 'Contact setting updated successfully.');
    }

    /**
     * Delete contact setting
     */
    public function contactDestroy(ContactSetting $contact)
    {
        $contact->delete();
        return back()->with('success', 'Contact setting deleted successfully.');
    }
}
