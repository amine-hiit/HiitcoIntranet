
$(document).ready(function () {
    $('.select2').select2();

    $('.datepicker').datepicker({
        autoclose: true
    });

    $("#addFormation").click(function(){
        $("#newFormation").append("<li>Appended item</li>");
    });
})

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

jQuery(document).ready(function () {
    jQuery('.add-another-collection-widget').click(function (e) {
        e.preventDefault();

        var list = jQuery(jQuery(this).attr('data-list'));
        // Try to find the counter of the list
        var counter = list.data('widget-counter') | list.children().length;
        // If the counter does not exist, use the length of the list
        if (!counter) { counter = list.children().length; }

        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data(' widget-counter', counter);

        // create a new list element and add it to the list
        var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);

        // Get the ul that holds the collection of tags
        $collectionHolder = $('#formation-fields-list');

        // add a delete link to all of the existing tag form li elements
        $collectionHolder.find('.formation').each(function() {
            if (!$(this).hasClass("dirty")) {
                addTagFormDeleteLink($(this));
            }
        });

        function addTagForm() {

            // add a delete link to the new form
            addTagFormDeleteLink($newFormLi);
        }
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



});
(function(e,t,n){var r=e.querySelectorAll("#avatar-upload-box")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);
