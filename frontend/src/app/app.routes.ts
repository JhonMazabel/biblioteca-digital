import { Routes } from '@angular/router';
import { LoginComponent } from './auth/login/login.component';
import { RegisterComponent } from './auth/register/register.component';
import { LoanRequestComponent } from './components/loan-request/loan-request.component';
import { BookListComponent } from './components/book-list/book-list.component';
import { ChangePasswordComponent } from './auth/change-password/change-password.component';
import { UserListComponent } from './components/user-list/user-list.component';
import { HomeComponent } from './components/home/home.component';
export const appRoutes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'loan-request', component: LoanRequestComponent },
  { path: 'books', component: BookListComponent },
  { path: 'users', component: UserListComponent }, // Ruta para la vista de usuarios
  { path: 'change-password', component: ChangePasswordComponent },
  { path: '**', redirectTo: '', pathMatch: 'full' }
  // Agrega otras rutas según sea necesario
];
