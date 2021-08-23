import { Dashboard } from './dashboard'

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
        if (document.body.classList.contains('page-template-template-dashboard')) {
            this.dashboard = new Dashboard();
        }
		// eslint-disable-next-line no-console
		console.log('Main was started');
	}
}
