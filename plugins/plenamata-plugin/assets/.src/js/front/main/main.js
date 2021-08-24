/**
 * Class Main.
 *
 * @since 1.0.0
 */
export class Main {
	/**
	 * Main constructor.
	 *
	 * @since 1.0.0
	 */
	constructor() {
		// eslint-disable-next-line no-console
		console.log('Main was started');
        // sticky menu
        this.sticky_menu();

	}
    sticky_menu() {
        function isScrolledIntoView() {
            console.log( document.documentElement.scrollTop );
            if ( document.documentElement.scrollTop > 50 ) {
                return true;
            } else {
                return false;
            }
        }
        
        const sticky_element = document.querySelector('header.site-header');
        let prev_status = false;
        document.addEventListener('scroll', (e) => {

            let current_visibility = isScrolledIntoView( );
            if ( current_visibility != prev_status ) {

                sticky_element.classList.toggle( 'is-sticky');
                document.body.classList.toggle( 'is-sticky' );
                prev_status = current_visibility;
            }
        });
          
    }
}
