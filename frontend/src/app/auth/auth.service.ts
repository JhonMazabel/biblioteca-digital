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
    localStorage.removeItem('userEmail'); // Añadido para asegurar que se elimine también el email del usuario
  }

  isAuthenticated(): boolean {
    return localStorage.getItem('isLoggedIn') === 'true';
  }

  getUserName(): string | null {
    return localStorage.getItem('userName'); // Obtener el nombre del usuario del Local Storage
  }

  getUserRole(): string | null {
    return localStorage.getItem('userRole');
  }

  getUserEmail(): string {
    return localStorage.getItem('userEmail') || ''; // Asegúrate de que este método obtenga el email del localStorage
  }
}
