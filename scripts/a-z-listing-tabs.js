// props to @claytonchase (https://profiles.wordpress.org/claytonchase)
jQuery( document ).ready( function() {
    const tabs = document.getElementById( 'az-tabs' );
    if ( tabs ) {
        let link;
        const letters = tabs.getElementById( 'letters' );
        if ( letters ) {
            link = letters.querySelector( `a[href="${window.location.hash}"]` );
        }

        if ( window.location.hash && link ) {
            const activeTab = jQuery( link ).parent().index();
            jQuery( tabs ).tabs( { active: activeTab } );
        } else {
            jQuery( tabs ).tabs();
        }
    }
} );
