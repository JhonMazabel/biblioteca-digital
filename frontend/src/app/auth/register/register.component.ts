import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { AuthService } from '../auth.service'; 
import { Router } from '@angular/router';
import { RegisterResponse } from '../register-response.model'; 

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})
export class RegisterComponent {
  registerForm: FormGroup;

  constructor(private fb: FormBuilder, private authService: AuthService, private router: Router) {
    this.registerForm = this.fb.group({
      username: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
      firstName: [''],
      lastName: ['']
    });
  }

  onSubmit() {
    if (this.registerForm.valid) {
      const { username, email, password, firstName, lastName } = this.registerForm.value;
      const data = {
        username,
        email,
        password,
        first_name: firstName,
        last_name: lastName,
        role_id: 2  // Asigna automáticamente el rol de estudiante (rol_id 2)
      };

      this.authService.register(data).subscribe(
        (response: RegisterResponse) => {
          console.log('Registro exitoso', response);
          this.router.navigate(['/login']); 
        },
        (error) => {
          console.error('Error al registrar', error);
        }
      );
    } else {
      console.log('Formulario no válido');
    }
  }
}
