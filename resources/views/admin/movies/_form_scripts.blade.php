<script>
// Show/hide required_plan_id based on access_type
(function () {
    const sel      = document.getElementById('access_type');
    const planRow  = document.getElementById('plan_row');
    if (!sel) return;
    sel.addEventListener('change', () => {
        planRow.style.display = sel.value === 'subscription' ? '' : 'none';
    });
})();

// Genre toggle pill styling
document.querySelectorAll('.genre-toggle').forEach(label => {
    const cb = label.querySelector('input[type=checkbox]');
    label.addEventListener('click', () => {
        setTimeout(() => {
            if (cb.checked) {
                label.style.borderColor = '#fff';
                label.style.background  = 'rgba(255,255,255,0.14)';
            } else {
                label.style.borderColor = 'rgba(255,255,255,0.12)';
                label.style.background  = 'rgba(255,255,255,0.06)';
            }
        }, 0);
    });
});
</script>
