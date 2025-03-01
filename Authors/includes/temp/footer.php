
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Click event to toggle the description visibility
        $('.see-more').on('click', function(e) {
            e.preventDefault();
            $(this).hide(); // Hide the "See more" link
            $(this).prev('.short-description').hide(); // Hide the short description
            $(this).next('.full-description').fadeIn(); // Reveal the full description smoothly
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>