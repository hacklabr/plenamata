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
        //this.sticky_menu();

        // hide main menu ul when modal search is active
        // this.hide_menu_items_on_search();

        // toggle cookie management in mobile
        this.toggle_cookie_banner();
	}
    sticky_menu() {
        function isScrolledIntoView() {
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

    hide_menu_items_on_search() {

        let main_menu = document.getElementById('site-navigation');
        let search_btn = document.getElementsByClassName('search-toggle')[0];
        let search_icon = document.getElementsByClassName('search-icon')[0];
        let masthead = document.getElementById('masthead');

        search_btn.addEventListener('click', function(){

            let is_search_icon_active = window.getComputedStyle(search_icon).getPropertyValue('display');

            main_menu.style.display = (is_search_icon_active == 'none') ? 'block' : 'none';

            masthead.classList.toggle('bg-primary');
            document.getElementsByTagName("BODY")[0].classList.toggle('freeze');

        }, false);

    }

    toggle_cookie_banner () {
        const buttons = document.querySelectorAll("a[href='#cookies-consent']");
        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const buttonCokkie = document.querySelector('.cmplz-manage-consent');
                document.querySelector("button.mobile-menu-toggle").click()
                buttonCokkie.click();
            });
        });
    }

}
