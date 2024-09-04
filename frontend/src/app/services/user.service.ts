import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  private apiUrl = 'http://localhost/biblioteca-digital-backend/controllers/UserController.php';
  private rolesUrl = 'http://localhost/biblioteca-digital-backend/controllers/RoleController.php';

  constructor(private http: HttpClient) { }

  getUsers(): Observable<any> {
    return this.http.get(this.apiUrl);
  }

  getRoles(): Observable<any> {
    return this.http.get(this.rolesUrl);
  }

  addUser(userData: any): Observable<any> {
    const payload = { ...userData, action: 'register' };

    return this.http.post(this.apiUrl, payload, {
      headers: new HttpHeaders({
        'Content-Type': 'application/json'
      })
    }).pipe(
      catchError((error: any) => {
        if (error.status === 409) {
          alert('El usuario con este correo ya está registrado.');
        } else {
          alert('Error al agregar el usuario. Por favor, inténtelo de nuevo.');
        }
        return throwError(() => error);
      })
    );
  }

  updateUser(id: string, userData: any): Observable<any> {
    return this.http.put(`${this.apiUrl}?id=${id}`, userData, {
      headers: new HttpHeaders({
        'Content-Type': 'application/json'
      })
    }).pipe(
      map(response => {
        if (response && typeof response === 'string' && response.includes('<br')) {
          // Si hay un HTML en la respuesta, esto es probablemente una advertencia o error del servidor.
          console.warn('Advertencia del servidor:', response);
          return { success: false, message: 'Advertencia o error del servidor' };
        }
        return { success: true, response };  // Éxito
      }),
      catchError((error: any) => {
        console.error('Error al actualizar el usuario:', error);
        alert('Error al actualizar el usuario. Por favor, inténtelo de nuevo.');
        return throwError(() => error);
      })
    );
  }

  deleteUser(id: string): Observable<any> {
    return this.http.delete(`${this.apiUrl}?id=${id}`);
  }

  loginUser(email: string, password: string): Observable<any> {
    const payload = { email, password, action: 'login' };

    return this.http.post(this.apiUrl, payload, {
      headers: new HttpHeaders({
        'Content-Type': 'application/json'
      })
    });
  }
}
