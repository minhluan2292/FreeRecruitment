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
                        <v-btn color="primary" @click="resetValidation" class="mr-4">Update</v-btn>
                        <v-btn color="warning" class="mr-4" @click="reset">Reset</v-btn>
                    </div>
                </div>
            </div>
    

        </v-form>

        <v-simple-table class="tb-account" dark>
            <thead>
                <tr>
                <th class="text-left">Name</th>
                <th class="text-left">Email</th>
                <th class="text-left">Role</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="account in accounts" :key="account.id">
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
                                this.$refs.form.reset();
                            }
                        })
                }
            },
            reset () {
                this.$refs.form.reset()
            },
            resetValidation () {
                this.$refs.form.resetValidation()
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
 </style>
 
