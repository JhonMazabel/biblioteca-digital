// src/app/services/loan.service.ts

import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class LoanService {
  private apiUrl = 'http://localhost/biblioteca-digital-backend/public/loans.php'; // URL del endpoint para préstamos

  constructor(private http: HttpClient) {}

  // Método para solicitar un préstamo
  getLoans(): Observable<any> {
    return this.http.get<any>(this.apiUrl);
  }

  requestLoan(loanData: any): Observable<any> {
    return this.http.post<any>(this.apiUrl, loanData);
  }
  // Método para actualizar un préstamo existente
  updateLoan(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}?id=${id}`, data);
  }

  // Método para eliminar un préstamo
  deleteLoan(loanId: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/loans.php?id=${loanId}`);
  }

}
