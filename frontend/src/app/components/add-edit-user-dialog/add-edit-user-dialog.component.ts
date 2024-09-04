import { Component, Inject } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { UserService } from '../../services/user.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatButtonModule } from '@angular/material/button';

@Component({
  selector: 'app-add-edit-user-dialog',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatButtonModule,
  ],
  templateUrl: './add-edit-user-dialog.component.html',
  styleUrls: ['./add-edit-user-dialog.component.css']
})
export class AddEditUserDialogComponent {
  userData: any = {};
  mode: string;
  roles: any[] = [];

  constructor(
    public dialogRef: MatDialogRef<AddEditUserDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: any,
    private userService: UserService
  ) {
    this.mode = data.mode;
    this.userData = { ...data.user };
    this.roles = data.roles;
  }

  onSave(): void {
    if (this.mode === 'add') {
      this.userService.addUser(this.userData).subscribe({
        next: () => {
          this.dialogRef.close(true);
        },
        error: (error) => {
          console.error('Error al agregar el usuario:', error);
          alert('Error al agregar el usuario. Por favor, inténtelo de nuevo (usuario ya existente).');
        }
      });
    } else if (this.mode === 'edit') {
      this.userService.updateUser(this.userData.id, this.userData).subscribe({
        next: () => {
          this.dialogRef.close(true);
        },
        error: (error) => {
          console.error('Error al actualizar el usuario:', error);
          alert('Error al actualizar el usuario. Por favor, inténtelo de nuevo.');
        }
      });
    }
  }

  onCancel(): void {
    this.dialogRef.close();
  }
}
