import { Component, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';
import { BookService } from '../../services/book.service';
import { MatGridListModule } from '@angular/material/grid-list'; 
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatSnackBarModule } from '@angular/material/snack-bar';
@Component({
  selector: 'app-add-edit-book-dialog',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatButtonModule,
    MatGridListModule,
    MatSnackBarModule
    

  ],
  templateUrl: './add-edit-book-dialog.component.html',
  styleUrls: ['./add-edit-book-dialog.component.css']
})
export class AddEditBookDialogComponent {
  bookData: any = {};
  mode: string;
  collectionTypes: any[]; // Define la propiedad collectionTypes

  constructor(
    public dialogRef: MatDialogRef<AddEditBookDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: any,
    private bookService: BookService,
    private snackBar: MatSnackBar
  ) {
    this.mode = data.mode;
    this.bookData = { ...data.book }; // Inicializa bookData con los datos del libro
    this.collectionTypes = data.collectionTypes; // Inicializa collectionTypes con los datos pasados desde el BookListComponent
  }

  onSave(): void {
    if (this.mode === 'add') {
      this.bookService.addBook(this.bookData).subscribe({
        next: () => {
          this.dialogRef.close(true);
        },
        error: (error) => {
          if (error.status === 400 && error.error.message === 'Error: El ISBN ya existe.') {
            // Mostrar un mensaje de alerta en la vista usando MatSnackBar
            this.snackBar.open('El ISBN ya existe. Por favor, ingresa un ISBN diferente.', 'Cerrar', {
              duration: 3000,
              horizontalPosition: 'center',
              verticalPosition: 'top'
            });
          } else {
            // Manejar otros tipos de errores
            this.snackBar.open('Error al agregar el libro (ISBN YA REGISTRADO). Por favor, intenta nuevamente.', 'Cerrar', {
              duration: 3000,
              horizontalPosition: 'center',
              verticalPosition: 'top'
            });
          }
        }
      });
    } else if (this.mode === 'edit') {
      this.bookService.updateBook(this.bookData.id, this.bookData).subscribe({
        next: () => {
          this.dialogRef.close(true);
        },
        error: (error) => {
          this.snackBar.open('Error al actualizar el libro. Por favor, intenta nuevamente.', 'Cerrar', {
            duration: 3000,
            horizontalPosition: 'center',
            verticalPosition: 'top'
          });
        }
      });
    }
  }



  onCancel(): void {
    this.dialogRef.close(); // Cierra el diálogo sin enviar ningún dato
  }
}