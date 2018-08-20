
jQuery(document).ready(function () {



    addFormation('.add-formation-collection-widget',  e = null);
    addExperience('.add-experience-collection-widget',  e = null);
    addProject('.add-project-collection-widget',  e = null);
    addLanguage('.add-language-collection-widget',  e = null);

    jQuery('.add-formation-collection-widget').click(function (e) {
        addFormation(this,e);
        requestForm();

        });


    jQuery('.add-language-collection-widget').click(function (e) {
        addLanguage(this,e);

    });

    jQuery('.add-project-collection-widget').click(function (e) {
        addProject(this,e);

    });

    jQuery('.add-experience-collection-widget').click(function (e) {
        addExperience(this,e);

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



    function addExperience(className,e){
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

        $collectionHolder = $('#experience-fields-list');

        $collectionHolder.find('.experience').each(function() {
            if (!$(this).hasClass("dirty")) {
                addTagFormDeleteLink($(this));
            }
        });
    }

    function addLanguage(className,e){
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

        $collectionHolder = $('#language-fields-list');

        $collectionHolder.find('.language').each(function() {
            if (!$(this).hasClass("dirty")) {
                addTagFormDeleteLink($(this));
            }
        });
    }


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

        $collectionHolder.find('.formation').each(function(i) {
            if (!$(this).hasClass("dirty") && i != 0) {
                addTagFormDeleteLink($(this));
            }
        });
    }

    function requestForm() {


        $("#formation-submit").click(function(){
            var $formationForm = $('#formation-form');

            $.ajax({
                type: "POST",
                url: Routing.generate('new-formation'),
                data: $formationForm.serialize(),
                success: function(dataF){

                    console.log(dataF);
                    $("#modal-content").modal('hide');
                    var myObject = JSON.parse(dataF);
                    var objectArray = new Array();
                    $(".formation-select").each(function(){
                        var current = $(this).attr('current');
                        if (typeof current !== typeof undefined){
                            var option = new Option(myObject.value, myObject.id, true, true);
                        }else{
                            var option = new Option(myObject.value, myObject.id, false, false);
                        }
                        objectArray.push(option);
                        $(this).append(option);
                    });
                    $('#formationModal').modal('hide');
                },
                error: function(){
                    alert("failure2");
                }
            });
        });
        $(".other-formation").click(function(){
            $.ajax({
                type: "GET",
                url: Routing.generate('new-formation'),
                success: function(msg){
                    console.log(msg);
                    $("#new-formation-label").empty();
                    $("#new-formation-label").append(msg);
                },
                error: function(){
                    alert("failure");
                }
            });
        });
    }



    function addProject(className,e){
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

        $collectionHolder = $('#project-fields-list');

        $collectionHolder.find('.project').each(function() {
            if (!$(this).hasClass("dirty")) {
                addTagFormDeleteLink($(this));
            }
        });
    }


    $('.select2').select2();

    $('.datepicker').datepicker({
        autoclose: true
    });

    $('body').on('click','.other-formation',function () {
        $('.formation-select').removeAttr('current');
        $(this).closest('div .form-group').find('.formation-select').attr('current','');
    });

});




/*************** step and input validation ****************/

var regex = {};
regex.phoneNumber={exp:new RegExp(/^0[756][0-9]{8,8}$/),msg:'(exemple: 0600102050, 0500405060, 0700506070...'};


function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByClassName("form-control");
    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
        // If a field is empty...
        if (y[i].value == "") {
            if(!y[i].classList.contains('invalid'))
                y[i].className += " invalid";
            // and set the current valid status to false
            valid = false;
        }
        $.each(regex, function(index, value) {
            if(y[i].classList.contains(index)){
                if(!value.exp.test(y[i].value)){
                    y[i].value='';
                    y[i].setAttribute("placeholder",  value.msg);
                    if(!y[i].classList.contains('invalid'))
                        y[i].className += " invalid";
                    valid = false;
                }
            }
        });
    }

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
        document.getElementById("nextBtn").innerHTML = "Envoyer";
    } else {
        document.getElementById("nextBtn").innerHTML = "Suivant";
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