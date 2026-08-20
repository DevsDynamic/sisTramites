{{-- BRAND --}}
<img src="{{ setting()?->logo ? asset('storage/' . setting()->logo) : 'https://placehold.co/200x200?text=LOGO' }}"
    class="sidebar-logo">
<div>
    <div class="sidebar-title">
        @if (setting()?->company_name)
            {{ setting()->company_name }}
        @endif
    </div>
    <div class="sidebar-plan">
        Plan SaaS
    </div>
</div>
