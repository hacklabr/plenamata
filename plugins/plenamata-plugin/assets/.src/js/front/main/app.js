import { GlossaryTooltips } from './glossary-tooltips';
import { Main } from './main';

document.defaultView.document.addEventListener('DOMContentLoaded', () => {
	new Main();
    new GlossaryTooltips();
});
