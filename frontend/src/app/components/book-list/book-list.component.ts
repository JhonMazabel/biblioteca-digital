import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { BookService } from '../../services/book.service';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatSelectModule } from '@angular/material/select';
import { MatOptionModule } from '@angular/material/core';

@Component({
  selector: 'app-book-list',
  standalone: true,
  imports: [
    CommonModule,
    HttpClientModule,
    FormsModule,
    MatCardModule,
    MatButtonModule,
    MatInputModule,
    MatFormFieldModule,
    MatIconModule,
    MatSelectModule,
    MatOptionModule
  ],
  templateUrl: './book-list.component.html',
  styleUrls: ['./book-list.component.css'],
  providers: [BookService]
})
export class BookListComponent implements OnInit {
  books: any[] = [];
  newBook: any = {};
  editingBook: any = null;
  collectionTypes: any[] = [];
  constructor(private bookService: BookService) {}

  ngOnInit(): void {
    this.loadBooks();
    this.loadCollectionTypes();
  }
  loadCollectionTypes(): void {
    this.bookService.getCollectionTypes().subscribe({
      next: (data: any) => {
        this.collectionTypes = data; // Suponiendo que el servicio retorna un array de tipos.
      },
      error: (error: any) => {
        console.error('Error al cargar los tipos de colección', error);
      }
    });
  }
  getCollectionTypeName(id: number): string {
    const type = this.collectionTypes.find(type => type.id === id);
    return type ? type.name : 'Desconocido';
  }
  
  loadBooks(): void {
    this.bookService.getBooks().subscribe({
      next: (data: any) => {
        this.books = data.records;
      },
      error: (error: any) => {
        console.error('Error al cargar los libros', error);
      }
    });
  }

  addBook(): void {
    this.bookService.addBook(this.newBook).subscribe({
      next: (response: any) => {
        console.log('Libro agregado', response);
        this.loadBooks();
        this.newBook = {};
      },
      error: (error: any) => {
        console.error('Error al agregar libro', error);
      }
    });
  }

  editBook(book: any): void {
    this.editingBook = { ...book };
  }

        updateBook(): void {
          this.bookService.updateBook(this.editingBook.id, this.editingBook).subscribe({
              next: (response: any) => {
                  console.log('Libro actualizado', response);
                  this.loadBooks();
                  this.editingBook = null;
              },
              error: (error: any) => {
                  console.error('Error al actualizar libro', error);
              }
          });
      }




  deleteBook(id: number): void {
    if (confirm('¿Estás seguro de que deseas eliminar este libro?')) {
      this.bookService.deleteBook(id).subscribe({
        next: (response: any) => {
          console.log('Libro eliminado', response);
          this.loadBooks();
        },
        error: (error: any) => {
          console.error('Error al eliminar libro', error);
        }
      });
    }
  }

  cancelEdit(): void {
    this.editingBook = null;
  }
}
