<template>
  <div class="main">
    <p class="alert-danger">{{error}}</p>
    <div v-if="checkUser == ''">
      <login></login>
    </div>
    <div v-else>
      <div class="header col-md-1">
          <button v-on:click="logoutUser()" class="btn btn-info">logout</button>
      </div>
      <calendar :role="role" :userss="userss"></calendar>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Login from "./Login";
import Calendar from "./Calendar";
export default {
  name: "Main",
  data() {
    return {
      checkUser: "",
      error: "",
      role: "",
      user: {},
      userss: ""
    };
  },
  methods: {
    checkUsers: function() {
      var self = this;
      if (localStorage["user"]) {
        self.user = JSON.parse(localStorage["user"]);
        axios
          .get(getUrl() + "user/" + self.user.id + "/")
          .then(function(response) {
            if (response.status == 200) {
              self.role = response.data.id_role;
              self.userss = response.data;
              self.checkUser = 1;
              return true;
            } else {
              delete localStorage["user"];
              self.error = response.data;
              self.checkUser = "";
              return false;
            }
          })
          .catch(function(error) {
            self.error = error;
          });
      } else {
        self.checkUser = "";
        return false;
      }
    },
    logoutUser: function() {
      var self = this;
      if (localStorage["user"]) {
        delete localStorage["user"];
        (self.user = {}), (self.checkUser = "");
        return true;
      } else {
        return false;
      }
    }
  },
  created() {
    this.checkUsers();
  },
  components: {
    Login: Login,
    Calendar: Calendar
  }
};
</script>

<style scoped>
.header {
  margin-top: 10px;
}
</style>