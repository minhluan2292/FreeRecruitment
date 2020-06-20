<template>
    <div class="account-management">
        <!-- Register Form -->
        <v-form ref="form" v-model="valid" lazy-validation class="register-form">
            <div class="txtGroup">
                <div class="txtGroup1">
                    <v-text-field v-model="name" :counter="30" :rules="nameRules" label="Name" required></v-text-field>
                    <v-text-field v-model="email" :rules="emailRules" label="E-mail" required @change="resetemailvalidate"></v-text-field>
                    <v-text-field v-model="password" :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" :rules="[rules.required, rules.min]"
                    :type="show1 ? 'text' : 'password'" label="Password" hint="At least 6 characters" 
                    counter @click:append="show1 = !show1"></v-text-field>
                </div>
                <div class="btnGroup">
                    <v-select v-model="select" :items="items" item-text="title" item-value="id" :rules="[v => !!v || 'Role is required']" label="Role" required>
                    </v-select>
                    
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
    

        </v-form>

        <v-simple-table class="tb-account" ref="table" dark>
            <thead>
                <tr>
                <th class="text-left">Name</th>
                <th class="text-left">Email</th>
                <th class="text-left">Role</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="account in accounts" :key="account.id" @click="selectAccount(account)" :class="{'selectedRow': account == selectedAccount}">
                <td>{{ account.name }}</td>
                <td>{{ account.email }}</td>
                <td>{{ account.role }}</td>                
                </tr>
            </tbody>
        </v-simple-table>
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
            rules: {
                required: value => !!value || 'Required.',
                min: v => (v && v.length >= 6) || 'Min 6 characters',
                emailMatch: () => ('The email and password you entered don\'t match'),
            },
            selectedAccount: null,
            dialogUpdate: false,
            dialogUpdate1: false,
         }),
        methods: {
            resetemailvalidate()
            {
                this.emailRules = [
                    v => !!v || 'E-mail is required',
                    v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
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
                                    v=>"Email exist",                               
                                ]
                            }
                            else
                            {
                                window.location.reload();
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
                        role: this.select.id
                    }).then((response) => {
                        window.location.reload();
                        this.$refs.form.reset();
                })
            },
            selectAccount(account) {
                this.name = account.name;
                this.email = account.email;
                this.select = { id: '' + account.role +'' },
                this.selectedAccount = account;
            },

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
            padding-top: 10%;
        }
    }
    .selectedRow {
        background-color: goldenrod;
        font-weight: bold;
    }

 </style>
 
