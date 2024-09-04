// src/app/components/loan-request/loan-request.component.ts

import { Component, OnInit, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LoanService } from '../../services/loan.service';
import { BookService } from '../../services/book.service';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { MatSnackBarModule, MatSnackBar } from '@angular/material/snack-bar';
import { MatOptionModule } from '@angular/material/core';
import { MatCardModule } from '@angular/material/card';
import { MatChipsModule } from '@angular/material/chips';
import { HttpClientModule } from '@angular/common/http';
import { AuthService } from '../../auth/auth.service';
import { EditLoanDialogComponent } from '../edit-loan-dialog/edit-loan-dialog.component';
import { MatDialog } from '@angular/material/dialog';
import { Book } from '../../interfaces/book.interface';

@Component({
  selector: 'app-loan-request',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    MatFormFieldModule,
    MatSelectModule,
    MatButtonModule,
    MatSnackBarModule,
    MatOptionModule,
    HttpClientModule,
    MatCardModule,
    MatChipsModule
  ],
  templateUrl: './loan-request.component.html',
  styleUrls: ['./loan-request.component.css'],
  schemas: [CUSTOM_ELEMENTS_SCHEMA] // Añadir esta línea
})
export class LoanRequestComponent implements OnInit {
  books: Book[] = [];
  loans: any[] = []; // Propiedad para almacenar los préstamos
  selectedBookId: number | null = null;

  constructor(
    private loanService: LoanService,
    private bookService: BookService,
    private authService: AuthService,
    private snackBar: MatSnackBar,
    public dialog: MatDialog
  ) {}

  ngOnInit(): void {
    this.loadLoans(); // Cargar los préstamos primero
    this.loadBooks();
  }

  loadLoans(): void {
    this.loanService.getLoans().subscribe({
      next: (data: any) => {
        if (data && data.records) {
          this.loans = data.records; // Almacenar los préstamos
          this.updateBookStatuses(); // Llamar a la función para actualizar los estados de los libros después de cargar los préstamos
        } else {
          console.error('El formato de datos de los préstamos es incorrecto.', data);
          this.snackBar.open('Error al cargar los préstamos.', 'Cerrar', {
            duration: 3000,
            horizontalPosition: 'center',
            verticalPosition: 'top',
          });
        }
      },
      error: (error: any) => {
        console.error('Error al cargar los préstamos', error);
        this.snackBar.open('Error al cargar los préstamos.', 'Cerrar', {
          duration: 3000,
          horizontalPosition: 'center',
          verticalPosition: 'top',
        });
      }
    });
  }

  updateBookStatuses(): void {
    this.books.forEach((book: Book) => {
      book.status = this.getBookStatus(book.id); // Llamada para obtener el estado real del libro
    });
  }

  getBookStatus(bookId: number): 'Disponible' | 'On Loan' {
    // Lógica para determinar si el libro está disponible o no
    let loan = this.loans.find(loan => loan.book_id === bookId);
    return loan ? 'On Loan' : 'Disponible'; // Devuelve exactamente los valores esperados
}

  loadBooks(): void {
    this.bookService.getBooks().subscribe({
      next: (data: any) => {
        if (data && data.records) {
          this.books = data.records.map((book: any) => ({
            ...book,
            status: this.getBookStatus(book.id) // Llamada para obtener el estado real del libro
          }));
        } else {
          console.error('El formato de datos de los libros es incorrecto.', data);
          this.snackBar.open('Error al cargar los libros.', 'Cerrar', {
            duration: 3000,
            horizontalPosition: 'center',
            verticalPosition: 'top',
          });
        }
      },
      error: (error: any) => {
        console.error('Error al cargar los libros', error);
        this.snackBar.open('Error al cargar los libros.', 'Cerrar', {
          duration: 3000,
          horizontalPosition: 'center',
          verticalPosition: 'top',
        });
      }
    });
  }

  editLoan(loan: any): void {
    this.openEditDialog(loan);
  }

  openEditDialog(loan: any): void {
    const dialogRef = this.dialog.open(EditLoanDialogComponent, {
      width: '400px',
      data: { ...loan } // Pasar los datos del préstamo al diálogo
    });
  
    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.updateLoan(result); // Actualizar el préstamo si hay resultados
      }
    });
  }

  updateLoan(updatedLoan: any): void {
    this.loanService.updateLoan(updatedLoan.id, updatedLoan).subscribe({
      next: (response: any) => {
        console.log('Préstamo actualizado:', response);
        this.snackBar.open('Préstamo actualizado exitosamente.', 'Cerrar', {
          duration: 3000,
          horizontalPosition: 'center',
          verticalPosition: 'top',
        });
        this.loadLoans(); // Recargar los préstamos después de la actualización.
        this.loadBooks(); // Recargar los libros después de la actualización.
      },
      error: (error: any) => {
        console.error('Error al actualizar préstamo:', error);
        this.snackBar.open('Error al actualizar préstamo.', 'Cerrar', {
          duration: 3000,
          horizontalPosition: 'center',
          verticalPosition: 'top',
        });
      }
    });
  }

  requestLoan(book: any): void {
    const userId = this.authService.getUserId();
    if (!userId) {
      this.snackBar.open('Error: Usuario no autenticado.', 'Cerrar', {
        duration: 3000,
        horizontalPosition: 'center',
        verticalPosition: 'top',
      });
      return;
    }
  
    const loanData = {
      user_id: userId,
      book_id: book.id,
      loan_date: this.calculateLoanDate(),
      return_date: this.calculateReturnDate(),
      status: 'On Loan'
    };
  
    this.loanService.requestLoan(loanData).subscribe({
      next: (response: any) => {
        console.log('Préstamo solicitado:', response);
        this.snackBar.open('Préstamo solicitado exitosamente.', 'Cerrar', {
          duration: 3000,
          horizontalPosition: 'center',
          verticalPosition: 'top',
        });
        book.status = 'On Loan'; // Actualizar el estado local del libro.
        this.loadLoans(); // Recargar los préstamos después de solicitar el préstamo.
      },
      error: (error: any) => {
        console.error('Error al solicitar préstamo:', error);
        this.snackBar.open('Error al solicitar préstamo.', 'Cerrar', {
          duration: 3000,
          horizontalPosition: 'center',
          verticalPosition: 'top',
        });
      }
    });
  }

  calculateLoanDate(): string {
    const loanDate = new Date();
    return loanDate.toISOString().slice(0, 10);
  }

  calculateReturnDate(): string {
    const returnDate = new Date();
    returnDate.setDate(returnDate.getDate() + 14); // Ejemplo: 14 días después de la fecha de préstamo
    return returnDate.toISOString().slice(0, 10);
  }
  deleteLoan(loanId: number, book: any): void {
    if (confirm('¿Estás seguro de que deseas eliminar este préstamo?')) {
      this.loanService.deleteLoan(loanId).subscribe({
        next: (response: any) => {
          console.log('Préstamo eliminado:', response);
          this.snackBar.open('Préstamo eliminado exitosamente.', 'Cerrar', {
            duration: 3000,
            horizontalPosition: 'center',
            verticalPosition: 'top',
          });
          this.loadBooks(); // Recargar la lista de libros
        },
        error: (error: any) => {
          console.error('Error al eliminar préstamo:', error);
          this.snackBar.open('Error al eliminar préstamo.', 'Cerrar', {
            duration: 3000,
            horizontalPosition: 'center',
            verticalPosition: 'top',
          });
        }
      });
    }
  }

}

