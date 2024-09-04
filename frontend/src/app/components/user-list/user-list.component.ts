import { Component, OnInit } from '@angular/core';
import { UserService } from '../../services/user.service';
import { MatDialog } from '@angular/material/dialog';
import { AddEditUserDialogComponent } from '../add-edit-user-dialog/add-edit-user-dialog.component';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-user-list',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
  ],
  templateUrl: './user-list.component.html',
  styleUrls: ['./user-list.component.css'],
  providers: [UserService]
})
export class UserListComponent implements OnInit {
  users: any[] = [];
  filteredUsers: any[] = [];
  searchTerm: string = '';
  roles: any[] = [];  // Variable para almacenar los roles

  constructor(private userService: UserService, public dialog: MatDialog) {}

  ngOnInit(): void {
    this.loadUsers();
    this.loadRoles(); // Cargar los roles cuando se inicializa el componente
  }

  loadUsers(): void {
    this.userService.getUsers().subscribe({
      next: (data: any) => {
        this.users = data.records;
        this.filteredUsers = this.users;
      },
      error: (error: any) => {
        console.error('Error al cargar los usuarios', error);
      }
    });
  }

  loadRoles(): void {
    this.userService.getRoles().subscribe({  // Método para cargar los roles
      next: (data: any) => {
        this.roles = data.records;  // Asegúrate de que el campo 'records' corresponde con la respuesta de tu backend
      },
      error: (error: any) => {
        console.error('Error al cargar los roles', error);
      }
    });
  }

  filterUsers(): void {
    this.filteredUsers = this.users.filter(user =>
      user.username.toLowerCase().includes(this.searchTerm.toLowerCase())
    );
  }

  openDialog(mode: string, user?: any): void {
    const dialogRef = this.dialog.open(AddEditUserDialogComponent, {
      width: '600px',
      data: {
        mode: mode,
        user: user || {},
        roles: this.roles  // Pasar los roles al diálogo
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.loadUsers();
      }
    });
  }

  deleteUser(id: string): void {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
      this.userService.deleteUser(id).subscribe({
        next: (response: any) => {
          console.log('Usuario eliminado', response);
          this.loadUsers();
        },
        error: (error: any) => {
          console.error('Error al eliminar usuario', error);
          alert('Error al eliminar el usuario. Por favor, inténtelo de nuevo.');
        }
      });
    }
  }
  
}
