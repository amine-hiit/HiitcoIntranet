
'use strict';

;( function ( document, window, index )
{
    var input = document.querySelector( '.inputfile' );

    var label	 = input.nextElementSibling,
        labelVal = label.innerHTML;

    input.addEventListener( 'change', function( e )
    {
        var fileName = '';

        fileName = e.target.value.split( '\\' ).pop();

        if( fileName )
            label.querySelector( '#avatar-upload-label' ).innerHTML = fileName;
        else
            label.innerHTML = labelVal;
    });

    // Firefox bug fix
    input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
    input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
}( document, window, 0 ));
