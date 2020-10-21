import { initializeApp } from 'firebase';

const app = initializeApp({
    apiKey: "AIzaSyCv-exKqkCzEaZfQDAk7j2ZwVJYj45SCPY",
    authDomain: "progmob-pratikum-7.firebaseapp.com",
    databaseURL: "https://progmob-pratikum-7.firebaseio.com",
    projectId: "progmob-pratikum-7",
    storageBucket: "progmob-pratikum-7.appspot.com",
    messagingSenderId: "936498670713",
    appId: "1:936498670713:web:cbfbaac2ec96c32d3d78a8",
    measurementId: "G-WZ3RKHHF6N"
});
export const init = app;
export const db = app.firestore();
export const booksCollection = db.collection('books');