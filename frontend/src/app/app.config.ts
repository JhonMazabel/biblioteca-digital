// src/app/app.config.ts

import { provideClientHydration } from '@angular/platform-browser';
import { provideRouter } from '@angular/router';
import { provideZoneChangeDetection } from '@angular/core';
import { appRoutes } from './app.routes';  // Importa las rutas desde app.routes.ts

export const appConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }),
    provideRouter(appRoutes),  // Usa las rutas importadas desde app.routes.ts
    provideClientHydration()
  ]
};
