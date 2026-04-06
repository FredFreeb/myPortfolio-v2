const bootStimulus = async () => {
    try {
        await import('./stimulus_bootstrap.js');
    } catch (error) {
        console.warn('[ui] Stimulus bootstrap unavailable, inline nav fallback stays active.', error);
    }
};

void bootStimulus();
