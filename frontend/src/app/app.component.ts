// src/app/app.component.ts

import { Component } from '@angular/core';
import { RouterModule, Router } from '@angular/router';
import { AuthService } from './auth/auth.service'; // Asegúrate de importar tu servicio de autenticación
import { CommonModule } from '@angular/common'; // Importa CommonModule aquí
import { MatSnackBarModule } from '@angular/material/snack-bar';
@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterModule, CommonModule], // Agrega CommonModule aquí
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'biblioteca-frontend';

  constructor(public authService: AuthService, private router: Router) {}

  logout() {
    this.authService.logout();
    this.router.navigate(['/login']); // Redirige a la página de inicio de sesión después de cerrar sesión
  }
  shouldShowBanner(): boolean {
    const currentRoute = this.router.url;
    return currentRoute === '/' || currentRoute === '/about' || currentRoute === '/books';
  }
}
