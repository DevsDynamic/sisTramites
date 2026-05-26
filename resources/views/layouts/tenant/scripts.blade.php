<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

<script>
    function toggleDarkMode() {

        document.body.classList.toggle('dark-mode');

        localStorage.setItem(
            'dark-mode',
            document.body.classList.contains('dark-mode')
        );
    }

    if (localStorage.getItem('dark-mode') === 'true') {
        document.body.classList.add('dark-mode');
    }
</script>
