<template>
    <div class="container">
        <p><h2>Liste des contacts</h2></p>
        <div class="d-flex">
            <div class="actions justify-content-start">
                <button type="button" class="btn btn-primary" @click="ajouterContact()">Ajouter un contact</button>
            </div>
            <div class="search-form ml-auto">
                <form class="form-inline my-2 my-lg-0" @submit.prevent="search()">
                    <input class="form-control mr-sm-2" v-model="formSeach.q" type="search" name="q" placeholder="Search" aria-label="Search" required>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Recherche</button>
                </form>
            </div>
        </div>
        <table class="table table-responsive mt-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" class="small">Nom</th>
                    <th scope="col" class="small">PostNom</th>
                    <th scope="col" class="small">Prénom</th>
                    <th scope="col" class="small">Email</th>
                    <th scope="col" class="small">Téléphone</th>
                    <th scope="col" class="small">Société</th>
                    <th scope="col" class="small">Data de naissance</th>
                    <th scope="col" class="small">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="contact in contacts" :key="contact.id">
                    <th scope="row">{{ contact.id }}</th>
                    <td class="small">{{ contact.nom }}</td>
                    <td class="small">{{ contact.postnom }}</td>
                    <td class="small">{{ contact.prenom }}</td>
                    <td class="small">{{ contact.email }}</td>
                    <td class="small">{{ contact.telephone }}</td>
                    <td class="small">{{ contact.societe }}</td>
                    <td class="small">{{ contact.birth }}</td>
                    <td>
                        <a href="#" data-id="contact.id" @click="editContact(contact)">
                            <i class="fa fa-edit"></i>
                        </a>
                        |
                        <a href="#" @click="deleteContact(contact.id)">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="modalContact" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Nouveau Contact</h5>
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Modifier Contact</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateContact() : createContact()" novalidate>
                    <input type="hidden" name="_token" :value="token"/>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="col-form-label">Nom</label>
                                <input type="text" v-model="form.nom" class="form-control" :class="{ 'is-invalid': form.errors.has('nom') }" name="nom" id="nom" required>
                                 <has-error :form="form" field="nom"></has-error>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="postnom" class="col-form-label">Postnom</label>
                                <input type="text" v-model="form.postnom" class="form-control" name="postnom" id="postnom">
                                
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="col-form-label">Prénom</label>
                                <input type="text" v-model="form.prenom" class="form-control" :class="{ 'is-invalid': form.errors.has('prenom') }" id="prenom"  name="prenom" required>
                                <has-error :form="form" field="prenom"></has-error>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="col-form-label">Email</label>
                                <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" name="email" id="email"  required>
                                <has-error :form="form" field="email"></has-error>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="col-form-label">Téléphone</label>
                                <input type="phone" v-model="form.telephone" class="form-control" :class="{ 'is-invalid': form.errors.has('telephone') || phoneInValid }"  id="telephone" name="telephone" required>
                                <has-error :form="form" field="telephone"></has-error>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="societe" class="col-form-label">Société</label>
                                <input type="text" v-model="form.societe" class="form-control" :class="{ 'is-invalid': form.errors.has('societe') }" id="societe" name="societe">
                               <has-error :form="form" field="societe"></has-error>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="birth" class="col-form-label">Date de naissance</label>
                            <input type="date" v-model="form.birth" class="form-control" :class="{ 'is-invalid': form.errors.has('birth') }" id="birth" name="birth">
                            <has-error :form="form" field="birth"></has-error>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button v-show="!editMode" type="submit" class="btn btn-primary">Ajouter</button>
                        <button v-show="editMode" type="submit" class="btn btn-primary">Modifier</button>

                    </div>
                </form>

                </div>
            </div>
        </div>

        
        <!-- <ModalEdit :editMode="editMode" :form="form" :token="csrf_token"></ModalEdit> -->
    </div>
    
</template>

<script>
import ModalEdit from './ModalEdit'

export default {
    data() {
        return {
            contacts : [],
            contact: {},
            editMode: false,
            form : new Form({
                id: '',
                nom: '',
                postnom: '',
                prenom: '',
                email: '',
                telephone: '',
                societe: '',
                birth: '',
                _token: ''
            }),
            formSeach : new Form({
                q : ''
            }),
            token: window.csrf_token,
            searchInput : '',
            phoneInValid : false,
        }
    },
    components : {
        ModalEdit
    },
    methods: {
        
        editContact(contact) {
            this.editMode = true
            $('#modalContact').modal('show')
            this.form.fill(contact)
        },
        ajouterContact() {
            this.editMode = false
            this.form.reset()
            $('#modalContact').modal('show')
        },
        loadContacts() {
            axios.get("api/contact").then( data => (this.contacts = data.data));
        },
        updateContact(){
            var phonereg = "^[\+](243)(81|84|85|99|97|82)[0-9]?[0-9]{7}$";
            const matchPhone = this.form.telephone.match(phonereg);

            if(!matchPhone){
                this.phoneInValid = true;
                return
            }  else {
                this.phoneInValid = false
            }

            this.form._token = this.token
            this.form.put('api/contact/'+this.form.id)
               .then(()=>{
                   Toast.fire({
                      icon: 'success',
                      title: 'Contact à jour'
                    })

                    $('#modalContact').modal('hide');
                    this.loadContacts();
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        createContact(){
            var phonereg = "^[\+](243)(81|84|85|99|97|82)[0-9]?[0-9]{7}$";
            const matchPhone = this.form.telephone.match(phonereg);

            if(!matchPhone){
                this.phoneInValid = true;
                return
            }  else {
                this.phoneInValid = false
            }

            this.form._token = this.token

            this.$Progress.start()
           
            this.form.post('api/contact')
                .then((data) => {

                    Toast.fire({
                        icon: 'success',
                        title: 'Contact Ajouté'
                    })

                    this.$Progress.finish()

                    $('#modalContact').modal('hide');
                    this.loadContacts();
                })
                .catch((err) => {
                   console.log(err)
                })
        },
        deleteContact(id) {
            Swal.fire({
              title: 'Êtes vous sûre?',
              text: "Cette action est irreversible",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                
              if (result.value) {

                this.form.delete('api/contact/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Supprimé!',
                              'Contact supprimé',
                              'success'
                            )
                    this.loadContacts();

                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Une erreur de suppression',
                        })
                    })
                }

            })
        },
        search() {
            this.$Progress.start()

            axios.get("api/search/?q=" + this.formSeach.q).then( data => {
                this.contacts = data.data
                this.$Progress.finish()
            });

        }
    },
    created() {
        this.loadContacts();
    }
}
</script>

<style scoped>
   
</style>