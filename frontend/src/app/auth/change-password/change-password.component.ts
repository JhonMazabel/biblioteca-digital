import { Component } from '@angular/core';
import { AuthService } from '../auth.service';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { MatSnackBar, MatSnackBarModule } from '@angular/material/snack-bar'; 
import { CommonModule } from '@angular/common';
import { MatFormFieldModule } from '@angular/material/form-field'; // Importa MatFormFieldModule
import { MatInputModule } from '@angular/material/input'; // Importa MatInputModule

@Component({
  selector: 'app-change-password',
  standalone: true,
  imports: [
    ReactiveFormsModule, 
    MatSnackBarModule, 
    CommonModule, 
    MatFormFieldModule, // Asegúrate de incluir MatFormFieldModule aquí
    MatInputModule // Asegúrate de incluir MatInputModule aquí
  ],
  templateUrl: './change-password.component.html',
  styleUrls: ['./change-password.component.css'],
  providers: [MatSnackBar] 
})
export class ChangePasswordComponent {
  changePasswordForm: FormGroup;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private snackBar: MatSnackBar 
  ) {
    this.changePasswordForm = this.fb.group({
      currentPassword: ['', [Validators.required]],
      newPassword: ['', [Validators.required, Validators.minLength(6)]],
      confirmNewPassword: ['', [Validators.required]]
    });
  }

  onSubmit() {
    if (this.changePasswordForm.valid) {
      const { currentPassword, newPassword } = this.changePasswordForm.value;
      this.authService.changePassword(currentPassword, newPassword).subscribe(
        response => {
          this.snackBar.open('Contraseña cambiada con éxito', 'Cerrar', {
            duration: 3000
          });
          // Redirigir al usuario a la página de libros
          window.location.href = '/books'; // Redirigir a la página de libros después de cambiar la contraseña
        },
        error => {
          this.snackBar.open('verifica la contraseña, hubo un error de credenciales', 'Cerrar', {
            duration: 3000
          });
        }
      );
    } else {
      console.log('Formulario no válido');
    }
  }
}
