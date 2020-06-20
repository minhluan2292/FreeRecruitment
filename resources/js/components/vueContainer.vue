<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list dense>
        <v-list-item link>
          <v-list-item-action>
            <v-icon color="blue darken-2">mdi-home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link>
          <v-list-item-action>
            <v-icon color="teal darken-2">mdi-account-circle</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{ user.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link>
          <v-list-item-action>
            <v-icon color="red">mdi-exit-to-app</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title @click="logout">Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="indigo"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Account Management</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container id="container"
        class="fill-height"
        fluid 
      >
        <AccountManagement id="account-management"></AccountManagement>
      </v-container>
    </v-main>
    <v-footer
      color="indigo"
      app
    >
      <span class="white--text">&copy; Free Frecruitment</span>
    </v-footer>
  </v-app>
</template>

<script>
    import AccountManagement from './AccountManagement.vue'

    export default {
        props: {       
          user: {
                type: Object,
                required: true
                },
          accounts: {
                type: Array,
                default: []
            }
        },
        mounted() {
            axios.get('/accounts')
                .then((response) => {
                    console.log(response.data);
                    this.accounts = response.data;
                });
        },
        data: () => ({
        drawer: null,
        }),
        methods: {
            logout() {
                axios.post('/logout')
                    .then(response =>{
                        window.location.href="login";
                    })
            }
        },
        components: {AccountManagement}
    }
</script>

<style lang="scss" scoped>
.container {
    align-items: baseline;
}
.account-management {
    display: inline-block;
    vertical-align: top;
    width: 80%;
}
</style>