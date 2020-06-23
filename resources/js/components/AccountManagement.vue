<template>
    <div class="account-management">
        <!-- Register Form -->
        <v-form ref="form" v-model="valid" lazy-validation class="register-form">
            <div class="txtGroup">
                <div class="txtGroup1">
                    <v-text-field v-model="name" :counter="30" :rules="nameRules" label="Name" required></v-text-field>
                    <v-text-field v-model="email" :rules="emailRules" label="E-mail" @click="changemailrule" required ></v-text-field>
                    <v-select v-model="select" :items="items" item-text="title" item-value="id" :rules="[v => !!v || 'Role is required']" label="Role" required>
                    </v-select>
                    
                    
                </div>
                <div class="btnGroup">
                    <v-text-field v-model="password" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.required, rules.min]"
                    :type="show1 ? 'text' : 'password'" label="Password" hint="At least 6 characters" 
                    counter @click:append="show1 = !show1"></v-text-field>
                    <v-text-field v-model="rePassword" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.required, rules.min, passwordConfirmationRule]"
                    :type="show1 ? 'text' : 'password'" label="Password again" hint="At least 6 characters" 
                    counter @click:append="show1 = !show1"></v-text-field>

                    
                    <div class="btnGroup1">
                        <v-btn :disabled="!valid" color="success" class="mr-4" @click="validate">Add New</v-btn>
                        <v-btn color="primary" @click="updatecheck" class="mr-4">Update</v-btn>
                        <v-btn color="warning" class="mr-4" @click="reset">Reset</v-btn>
                    </div>
                </div>
            </div>

            <!-- Dialog -->
            <v-dialog v-model="dialogUpdate1" max-width="30%">
                <v-card>
                    <v-card-title class="headline">Please choose user</v-card-title>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn color="green darken-1" text @click="dialogUpdate1 = false">OK</v-btn>
                            </v-card-actions>
                        </v-card>
            </v-dialog>
            <v-dialog v-model="dialogUpdate" max-width="30%">
                <v-card>
                    <v-card-title class="headline">Do you want to update?</v-card-title>

                        <v-card-text>Choose OK to Update</v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-1" text @click="dialogUpdate = false">Cancel</v-btn>
                                <v-btn color="green darken-1" text @click="update">OK</v-btn>
                            </v-card-actions>
                        </v-card>
            </v-dialog>
            <v-dialog v-model="alertDelete" max-width="30%">
                <v-card>
                    <v-card-title class="headline">Do you want to delete?</v-card-title>

                        <v-card-text>Choose OK to Delete</v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red darken-1" text @click="alertDelete = false">Cancel</v-btn>
                                <v-btn color="green darken-1" text >OK</v-btn>
                            </v-card-actions>
                        </v-card>
            </v-dialog>
    
        <!-- End Dialog -->
        </v-form>
        <!-- Table Account -->
        <v-card>
            <v-card-title> Account list <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="mdi-account-search" label="Search" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="sortedAccounts" :search="search" 
            :footer-props="{'items-per-page-options': [4, 8, 12, 16, -1]
            }"
            item-key="id"
            @click:row="selectAccount" single-select
            >
            <template #item.actions="{item}">
                <td @click.stop class="non-clickable">
                <v-btn small >View</v-btn>
                <v-btn small >Edit</v-btn>
                <v-btn color="red" small @click="selectdete(item)">Delete</v-btn>
                </td>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="mdi-alert">
                Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
            </v-data-table>
        </v-card>
        <!-- <v-simple-table class="tb-account" ref="table" dark>
            <thead>
                <tr>
                <th class="text-left">Name</th>
                <th class="text-left">Email</th>
                <th class="text-left">Role</th>
                <th class="text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="account in sortedAccounts" :key="account.id" @click="selectAccount(account)" :class="{'selectedRow': account == selectedAccount}">
                <td>{{ account.name }}</td>
                <td>{{ account.email }}</td>
                <td>{{ account.role }}</td> 
                <td><v-icon medium color="red" @click="alertDelete = true">mdi-delete</v-icon></td>               
                </tr>
            </tbody>
        </v-simple-table> -->
    </div>
</template>

<script>
import { fail } from 'assert';
    export default {
        props: {
            accounts: {
                type: Array,
                default: []
            }
        },
        data: () =>({
            search: '',
            headers: [
            {
                text: 'ID',
                align: 'left',
                sortable: false,
                value: 'id'
            },
            { text: 'Name', value: 'name' },
            { text: 'Email', value: 'email' },
            { text: 'Role', value: 'role' },
            { text: 'Action', value: 'actions' },
            ],
            valid: true,
            name: '',
            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length <=30) || 'Name must be less than 30 characters',
            ],
            email: '',
            select: null,
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid',
            ],
            items:
            [
                { title: "Admin", id: '2' },
                { title: "Customer management", id: '1' }
            ],
            show1: false,
            password: '',
            rePassword: '',
            rules: {
                required: value => !!value || 'Required.',
                min: v => (v && v.length >= 6) || 'Min 6 characters',
                emailMatch: () => ('The email and password you entered don\'t match'),
            },
            selectedAccount: null,
            dialogUpdate: false,
            dialogUpdate1: false,
            alertDelete: false,
        }),
        computed: {
                passwordConfirmationRule() {
                    return () => (this.password === this.rePassword) || 'Password must match'
                },
                sortedAccounts() {
                return _.orderBy(this.accounts, ['updated_at'], ['desc']);
            }        
        },
        methods: {
            changemailrule() {
                    this.emailRules = [
                        v => !!v || 'E-mail is required',
                        v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid',
                    ]
            },
            validate () {
                if(this.$refs.form.validate())
                {
                        axios.post('/account/new', {
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            role: this.select
                        }).then((response) => {
                            if(response.data=="Email exist")
                            {
                                this.emailRules = [
                                    v => "Email matching with another account"
                                ];
                            }
                            else
                            {
                                this.accounts.push(response.data);
                                this.$refs.form.reset();
                            }
                        })
                }
            },
            reset () {
                this.$refs.form.reset()
            },
            updatecheck () {
                if(this.selectedAccount === null )
                {
                    this.dialogUpdate1 = true;
                }
                else
                {
                    this.dialogUpdate = true;
                }
            },
            update () {
                this.dialogUpdate = false;
                axios.post('/account/update', {
                        id: this.selectedAccount.id,
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        role: typeof(this.select.id) == 'undefined' ? this.select : this.select.id
                    }).then((response) => {
                        window.location.reload();
                        this.$refs.form.reset();
                })
            },
            selectAccount(account, row) {
                row.select(true);
                this.name = account.name;
                this.email = account.email;
                this.select = { id: '' + account.role +'' },
                this.selectedAccount = account;
            },
            selectdete(item) {
                alert(item.name);
            }

        },
    }
</script>
 <style lang="scss" scoped>
    .tb-account {
        margin-top: 20px;
    }
    .register-form {
        width: 100%;
    }
    .txtGroup {
        display: flex;
        .txtGroup1 {
            width: 40%;
        }
    }
    .btnGroup {
        margin-left: 5%;
        width: 40%;
        .btnGroup1 {
            display: flex;
        }
    }
    .selectedRow {
        background-color: goldenrod;
        font-weight: bold;
    }

 </style>
 
