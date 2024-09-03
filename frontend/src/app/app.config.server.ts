// src/app/app.config.server.ts

import { ApplicationConfig } from '@angular/core';
import { provideServerRendering } from '@angular/platform-server';
import { appConfig } from './app.config';

const serverConfig: ApplicationConfig = {
  providers: [
    provideServerRendering()
  ]
};

// No necesitas `mergeApplicationConfig` aquí, simplemente exporta las configuraciones
export const config = {
  ...appConfig,
  ...serverConfig
};
