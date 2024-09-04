import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost/biblioteca-digital-backend/controllers/UserController.php';

  constructor(private http: HttpClient) { }

  login(email: string, password: string): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}?action=login`, { email, password });
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

  // Nuevo método para verificar si el usuario es administrador
  isAdmin(): boolean {
    return this.getUserRole() === '1'; // Aquí estamos verificando si el `role_id` del usuario es '1' (considerando que '1' es para admin)
  }
  getCurrentUserId(): number {
    const userId = localStorage.getItem('userId'); // Asumiendo que guardaste el ID del usuario al momento del login
    return userId ? parseInt(userId, 10) : 0; // Devuelve 0 si no está definido
  }
  
}
