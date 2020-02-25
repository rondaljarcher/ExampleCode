<!-- script to retrieve the appropriate number to call based on zipcode submitted from user -->
<script>
$('input#notsure').on('click', function() {
    $('p#notsureError').css('display','block');
    $('p#noError').css('display','none');
    $('button#submitButton').css('background-color','#EC570C');
    $('button#submitButton').prop("disabled", false);
    //pull value out of the modal
    var modalzip = document.querySelectorAll( "#contactZip" );
    var zip = modalzip[1].value;
    //call our file
    $.getJSON("/JsonFilledWithOfficesAndNumbers.json", function(data)
    {
        $.each(data, function(key,value) {
            //if there's a match
            if(value.MailingPostalCode == zip) {
            // we always want to append the default text so we can replace the same value every time
            var defaultText = " Please call your local Social Security office at [NDA number] and ask for your work credits and your date last insured. ";
            $("#notsureError").text(defaultText);
            var text = $("#notsureError").text();
            //swaps in the right number
            var new_text = text.replace('[NDA number]', value.Phone);
            $("p#notsureError").text(new_text);
            }
        });
    });
});
</script>