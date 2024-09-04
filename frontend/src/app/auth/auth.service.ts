// src/app/auth/auth.service.ts

import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import { RegisterResponse } from './register-response.model';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost/biblioteca-digital-backend/public';

  constructor(private http: HttpClient) {}

  register(data: any): Observable<RegisterResponse> {
    return this.http.post<RegisterResponse>(`${this.apiUrl}/register.php`, data);
  }

  login(email: string, password: string): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/login.php`, { email, password }).pipe(
      tap(response => {
        if (response.message === 'Inicio de sesión exitoso') {
          localStorage.setItem('userId', response.user.id);
          localStorage.setItem('isLoggedIn', 'true');
          localStorage.setItem('userRole', response.user.role_id.toString());
          localStorage.setItem('userName', response.user.first_name); 
          localStorage.setItem('userEmail', response.user.email);
        }
      })
    );
  }

  changePassword(currentPassword: string, newPassword: string): Observable<any> {
    const email = this.getUserEmail(); // Obtener el email del usuario actualmente autenticado
    const headers = {
      'Authorization': `Bearer ${localStorage.getItem('authToken')}` // Agrega el token JWT al encabezado
    };
    return this.http.post<any>(`${this.apiUrl}/change-password.php`, { email, currentPassword, newPassword }, { headers }).pipe(
      tap(response => {
        console.log('Contraseña cambiada con éxito');
      })
    );
  }

  logout() {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userRole');
    localStorage.removeItem('userName');
    localStorage.removeItem('userEmail');
  }

  isAuthenticated(): boolean {
    return localStorage.getItem('isLoggedIn') === 'true';
  }

  getUserId(): number | null {
    const userId = localStorage.getItem('userId');
    return userId ? parseInt(userId, 10) : null;
  }
  getUserName(): string | null {
    return localStorage.getItem('userName');
  }

  getUserRole(): string | null {
    return localStorage.getItem('userRole');
  }

  getUserEmail(): string {
    return localStorage.getItem('userEmail') || '';
  }

  isAdmin(): boolean {
    return this.getUserRole() === '1'; // Supongamos que el rol de administrador tiene el ID '1'
  }
  getCurrentUserId(): number {
    const userId = localStorage.getItem('userId'); // Asumiendo que guardaste el ID del usuario al momento del login
    return userId ? parseInt(userId, 10) : 0; // Devuelve 0 si no está definido
  }
}
