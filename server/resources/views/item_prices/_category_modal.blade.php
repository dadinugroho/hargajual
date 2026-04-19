<div class="modal fade" id="newCategoryModal" tabindex="-1" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="newCategoryModalLabel">New Category</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="newCategoryName" class="form-control" placeholder="Category name">
                <div id="newCategoryError" class="text-danger small mt-1 d-none"></div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary" id="btnCreateCategory">Create</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const modal        = new bootstrap.Modal(document.getElementById('newCategoryModal'));
    const nameInput    = document.getElementById('newCategoryName');
    const errorDiv     = document.getElementById('newCategoryError');
    const btnCreate    = document.getElementById('btnCreateCategory');
    const categorySelect = document.getElementById('category_id');
    const csrfToken    = document.querySelector('meta[name="csrf-token"]').content;
    const quickUrl     = '{{ route("item_price_categories.quick_store") }}';

    categorySelect.addEventListener('change', function () {
        if (this.value !== '__new__') return;
        nameInput.value = '';
        errorDiv.classList.add('d-none');
        modal.show();
        document.getElementById('newCategoryModal').addEventListener('shown.bs.modal', () => nameInput.focus(), { once: true });
    });

    async function createCategory() {
        const name = nameInput.value.trim();
        if (!name) { nameInput.focus(); return; }

        const orgId = document.querySelector('input[name="org_id"]').value;
        btnCreate.disabled = true;

        try {
            const res = await fetch(quickUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ org_id: orgId, name }),
            });

            if (!res.ok) throw new Error();
            const cat = await res.json();

            const newOpt = new Option(cat.name, cat.id, false, true);
            const newOrgOpt = categorySelect.querySelector('option[value="__new__"]');
            categorySelect.insertBefore(newOpt, newOrgOpt);
            categorySelect.value = cat.id;
            modal.hide();
        } catch {
            errorDiv.textContent = 'Failed to create category. Try again.';
            errorDiv.classList.remove('d-none');
        } finally {
            btnCreate.disabled = false;
        }
    }

    btnCreate.addEventListener('click', createCategory);
    nameInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); createCategory(); }
    });
})();
</script>
@endpush
