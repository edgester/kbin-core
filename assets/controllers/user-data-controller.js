import {Controller} from '@hotwired/stimulus';
import Cookies from 'js-cookie';

export default class extends Controller {
    static targets = ['auto_refresh', 'auto_embed', 'notifications', 'federation'];

    connect() {
        if (Cookies.get('user_option_auto_embed') === 'true') {
            document.addEventListener('turbo:load', () => {
                this.togglePreview()
                const sleep = ms => new Promise(r => setTimeout(r, Math.random() * (500 - 2000)) + 500);
            });
        }
    }

    toggle(e) {
        Cookies.set('user_option_' + e.target.dataset.userDataValue, e.target.checked);

        if (e.target.dataset.userDataValue === 'auto_embed') {
            this.togglePreview();
            location.reload();
        }
    }

    togglePreview() {
        document.querySelectorAll('.kbin-preview').forEach(el => {
            el.click();
        });
    }
}
