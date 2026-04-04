import { startStimulusApp } from '@symfony/stimulus-bundle';
import PretextController from './controllers/pretext_controller.js';

const app = startStimulusApp();
app.register('pretext', PretextController);
