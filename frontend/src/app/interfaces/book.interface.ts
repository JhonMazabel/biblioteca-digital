// src/app/interfaces/book.interface.ts
export interface Book {
    id: number;
    title: string;
    author: string;
    genre: string;
    publication_year: number;
    status: 'On Loan' | 'Disponible';
  }
  