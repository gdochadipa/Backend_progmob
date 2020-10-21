<template>
    <div class="container mt-5">
        <div class="row">
            <h1>Book</h1>
        </div>
        <div class="row">
        <button class="btn btn-primary"  data-toggle="modal" data-target="#addModal" >ADD DATA</button>
        </div>
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">description</th>
                    <th scope="col">writer</th>
                    <th scope="col">Cover</th>
                    <th scope="col">Language</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr :key="item.id" v-for="(item,id) in allBooks" >
                        <td>{{id}}</td>
                        <td>{{item.title}}</td>
                        <td>{{item.description}}</td>
                        <td>{{item.writer}}</td>
                        <td><img :src="item.cover" style="width:100px;" alt="" srcset=""></td>
                        <td>{{item.language}}</td>
                        <td>{{item.price}}</td>
                        <td>
                            <button class="btn btn-light" data-toggle="modal" @click="onSetBook(item)" data-target="#editModal"> <i class="fa fa-pencil" aria-hidden="true"></i> </button>
                            <button class="btn btn-light" @click="onDelete(item.id)"> <i class="fa fa-trash" aria-hidden="true"></i> </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Book</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">title book</label>
                                <input type="text" class="form-control" v-model="book.title" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">description book</label>
                                <textarea class="form-control" v-model="book.description" id="message-text"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">writer</label>
                                <input type="text" class="form-control" v-model="book.writer" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlFile1">Cover Book </label>
                                <input type="file" class="form-control-file" @change="onImage()"  accept="image/*" id="exampleFormControlFile1">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Language</label>
                                <input type="text" class="form-control" v-model="book.language" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Price</label>
                                <input type="text" class="form-control" v-model="book.price" id="recipient-name">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" @click.prevent="addBook()" data-dismiss="modal" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1"  role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Book</h5>
                        <button type="button" @click="onNormalize()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">title book</label>
                                <input type="text" class="form-control" v-model="book.title" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">description book</label>
                                <textarea class="form-control" v-model="book.description" id="message-text"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">writer</label>
                                <input type="text" class="form-control" v-model="book.writer" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlFile1">Cover Book </label>
                                <input type="file" class="form-control-file" @change="onImage()"  accept="image/*" id="exampleFormControlFile1">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Language</label>
                                <input type="text" class="form-control" v-model="book.language" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Price</label>
                                <input type="text" class="form-control" v-model="book.price" id="recipient-name">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="onNormalize()" data-dismiss="modal">Close</button>
                        <button type="submit" @click.prevent="updateBook(book)" data-dismiss="modal" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { booksCollection, init } from './../firebase';
import 'vue-toast-notification/dist/theme-default.css';
import { storage } from 'firebase';
import api from '../api';
export default {
    data(){
        return{
            book:{
                id:'',
                title:'',
                description:'',
                writer:'',
                cover:'',
                language:'',
                price:0

            },
            imageData:null,
            image1:'',
            allBooks:[],
            
        }
    },
    async created(){
        await this.onLoad();
    },
    methods:{
         async addBook(book){
            
            if(this.imageData !== null){

                console.log(this.book);

                let uri = `/api/books`;
                api.post(uri,book).then((response) => {
                    this.$swal(
                        'Added!',
                        'Your file has been Added.',
                        'success'
                        );
                         this.onLoad();
                });

                this.book = {
                    id:'',
                    title:'',
                    description:'',
                    writer:'',
                    cover:'',
                    language:'',
                    price:0
                }

                
            }else{
                console.log("Image input is empty");
                this.$toast.open({
                    message: 'Image is empty',
                    type: 'error',
                    position: 'top-left',

                });
            }

            


        },
        onImage(){
            this.uploadValue=0;
            this.imageData = event.target.files[0];
                const storageRef = init.storage().ref(`${this.imageData.name}`).put(this.imageData);
                storageRef.on(`state_changed`, snapshot=>{
                this.uploadValue=(snapshot.bytesTransferred/snapshot.totalBytes)*100;
                },error=>{console.log(error.message)},
                ()=>{
                    this.uploadValue=100;
                    storageRef.snapshot.ref.getDownloadURL().then((url)=>{
                        this.book.cover = url;
                        
                    })
                })
        },
        async onDelete(id){
            this.$swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {

                    let uri = `/api/books/${id}`;
                    api.delete(uri).then((response) => {
                        
                        this.$swal(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                        )
                            this.onLoad();
                    });
                    
                }
                })
        },
        async updateBook(book){
            
            let uri = `/api/books/${book.id}`;
            api.put(uri,book).then((response) => {
                console.log(response);
                this.$swal(
                    'Edited!',
                    'Your file has been Edited.',
                    'success'
                    );
                    this.onLoad();
            });
            this.book = {
                    id:'',
                    title:'',
                    description:'',
                    writer:'',
                    cover:'',
                    language:'',
                    price:0
                }
        },
        onSetBook(item){
            this.book = {
                    id:item.id,
                    title:item.title,
                    description:item.description,
                    writer:item.writer,
                    cover:item.cover,
                    language:item.language,
                    price:item.price
                }
        },
        onNormalize(){
            this.book = {
                    id:'',
                    title:'',
                    description:'',
                    writer:'',
                    cover:'',
                    language:'',
                    price:0
                }
        },
        async onLoad(){
            let uri = `/api/books`;
            await api.get(uri).then((response) => {
                this.allBooks = response.data.data;
            });
        }
        
    }
}
</script>