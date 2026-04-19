import { Application } from '@hotwired/stimulus';
import NavController from './controllers/nav_controller.js';
import ParallaxController from './controllers/parallax_controller.js';
import TypewriterController from './controllers/typewriter_controller.js';

const app = Application.start();
app.register('nav', NavController);
app.register('parallax', ParallaxController);
app.register('typewriter', TypewriterController);

const registerOptionalController = async (name, loader) => {
    try {
        const module = await loader();
        if (module?.default) {
            app.register(name, module.default);
        }
    } catch (error) {
        console.warn(`[ui] controller "${name}" not loaded`, error);
    }
};

void registerOptionalController('pretext',       () => import('./controllers/pretext_controller.js'));
void registerOptionalController('lang-switcher', () => import('./controllers/lang_switcher_controller.js'));
void registerOptionalController('svg-simulator', () => import('./controllers/svg_simulator_controller.js'));
void registerOptionalController('svg-charts',    () => import('./controllers/svg_charts_controller.js'));
