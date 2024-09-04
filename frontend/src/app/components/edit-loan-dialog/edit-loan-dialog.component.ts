// src/app/components/edit-loan-dialog/edit-loan-dialog.component.ts

import { Component, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatChipsModule } from '@angular/material/chips';
@Component({
  selector: 'app-edit-loan-dialog',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatChipsModule  
  ],
  templateUrl: './edit-loan-dialog.component.html',
  styleUrls: ['./edit-loan-dialog.component.css']
})
export class EditLoanDialogComponent {
  constructor(
    public dialogRef: MatDialogRef<EditLoanDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public loan: any
  ) {}

  onCancel(): void {
    this.dialogRef.close();
  }

  onSave(): void {
    // Aquí podrías añadir validaciones antes de cerrar el diálogo
    this.dialogRef.close(this.loan);
  }
}
