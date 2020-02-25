<!-- toggle off submit, no double dip -->
<script>
    $("form").submit(function (e) {
        $('button').prop("disabled", true);
        $('button').css('background-color', 'gray');
        return true;
    });
</script>
