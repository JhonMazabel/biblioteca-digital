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
import { MatDialogModule, MatDialog } from '@angular/material/dialog';
import { AddEditBookDialogComponent } from '../add-edit-book-dialog/add-edit-book-dialog.component';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { MatSnackBar } from '@angular/material/snack-bar';
import { AuthService } from '../../auth/auth.service';
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
    MatOptionModule,
    MatDialogModule,
    MatSnackBarModule
  ],
  templateUrl: './book-list.component.html',
  styleUrls: ['./book-list.component.css'],
  providers: [BookService]
})
export class BookListComponent implements OnInit {
  books: any[] = [];
  collectionTypes: any[] = [];
  searchTerm: string = ''; // Propiedad para el término de búsqueda

  constructor(public authService: AuthService,private bookService: BookService, public dialog: MatDialog, private snackBar: MatSnackBar) {}

  ngOnInit(): void {
    this.loadBooks();
    this.loadCollectionTypes();
  }

  // Método para filtrar libros según el término de búsqueda
  filteredBooks(): any[] {
    if (!this.searchTerm) {
      return this.books; // Si no hay término de búsqueda, mostrar todos los libros
    }
    return this.books.filter(book =>
      book.title.toLowerCase().includes(this.searchTerm.toLowerCase())
    );
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

  loadCollectionTypes(): void {
    this.bookService.getCollectionTypes().subscribe({
      next: (data: any) => {
        this.collectionTypes = data;
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

  openDialog(mode: string, book?: any): void {
    const dialogRef = this.dialog.open(AddEditBookDialogComponent, {
      width: '600px',
      data: {
        mode: mode,
        book: book || {},
        collectionTypes: this.collectionTypes // Pasar collectionTypes aquí
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.loadBooks();
      }
    });
  }

  deleteBook(id: number): void {
    if (confirm('¿Estás seguro de que deseas eliminar este libro?')) {
      this.bookService.deleteBook(id).subscribe({
        next: (response: any) => {
          console.log('Libro eliminado', response);
          this.loadBooks();
          this.snackBar.open('Libro eliminado exitosamente.', 'Cerrar', {
            duration: 3000, // Duración en milisegundos
            horizontalPosition: 'center',
            verticalPosition: 'top'
          });
        },
        error: (error: any) => {
          console.error('Error al eliminar libro', error);
        }
      });
    }
  }
}
