// src/app/app.routes.ts

import { Routes } from '@angular/router';
import { LoginComponent } from './auth/login/login.component';
import { RegisterComponent } from './auth/register/register.component';
import { AboutComponent } from './components/about/about.component';
import { BookListComponent } from './components/book-list/book-list.component';
import { CollectionComponent } from './components/collection/collection.component';
import { LoansComponent } from './components/loans/loans.component';
import { ChangePasswordComponent } from './auth/change-password/change-password.component';

export const appRoutes: Routes = [

  { path: 'login', component: LoginComponent }, // Sin guard

  { path: 'register', component: RegisterComponent },
  { path: 'about', component: AboutComponent },
  { path: 'books', component: BookListComponent },
  { path: 'collection', component: CollectionComponent },
  { path: 'loans', component: LoansComponent },
  { path: 'change-password', component: ChangePasswordComponent }, // Nueva ruta
  { path: '', redirectTo: '/books', pathMatch: 'full' }
];
