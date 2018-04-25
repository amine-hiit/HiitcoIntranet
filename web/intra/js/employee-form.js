

jQuery(document).ready(function () {

    addFormation('.add-another-collection-widget',  e = null);

    jQuery('.add-another-collection-widget').click(function (e) {
        addFormation(this,e);

    });


    function addTagFormDeleteLink($tagFormLi) {
        var $removeFormA = $('<i class="fa fa-times"></i>');


        $tagFormLi.find('.removing').append($removeFormA);
        $tagFormLi.addClass('dirty');
        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL

            $tagFormLi.remove();
        });
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#avatar').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".inputfile").change(function(){
        readURL(this);
    });

    function addFormation(className,e){
        if (e!=null)
            e.preventDefault();

        var list = jQuery(jQuery(className).attr('data-list'));

        var counter = list.children().length;

        if (!counter) { counter = list.children().length; }


        var newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, counter);

        counter++;


        var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);

        $collectionHolder = $('#formation-fields-list');

        $collectionHolder.find('.formation').each(function() {
            if (!$(this).hasClass("dirty")) {
                addTagFormDeleteLink($(this,));
            }
        });
    }

    $('.select2').select2();

    $('.datepicker').datepicker({
        autoclose: true
    });

    $("#addFormation").click(function(){
        $("#newFormation").append("<li>Appended item</li>");
    });

});




/*************** step and input validation ****************/



function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByClassName("form-control");
    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
        // If a field is empty...
        if (y[i].value == "") {
            // add an "invalid" class to the field:
            y[i].className += " invalid";
            // and set the current valid status to false
            valid = false;
        }
    }
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid; // return the valid status
}

var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the crurrent tab

function showTab(n) {
    // This function will display the specified tab of the form...
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    //... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    //... and run a function that will display the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form...
    if (currentTab >= x.length) {
        document.getElementById("regForm").submit();
        return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
}



function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class on the current step:
    x[n].className += " active";
}