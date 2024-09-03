import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BookService {
  private apiUrl = 'http://localhost/biblioteca-digital-backend/public'; // Ajusta la URL según corresponda

  constructor(private http: HttpClient) {}

  getBooks(): Observable<any> {
    return this.http.get(`${this.apiUrl}/books.php`);
  }

  getCollectionTypes(): Observable<any> {
    return this.http.get(`${this.apiUrl}/collectionTypes.php`); // Endpoint para obtener los tipos de colección
  }

  addBook(book: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/books.php`, book);
  }

  updateBook(id: number, book: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/books.php?id=${id}`, book);
  }



  deleteBook(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/books.php?id=${id}`);
  }
}
